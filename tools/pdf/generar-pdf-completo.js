#!/usr/bin/env node
/**
 * Genera un PDF de TODO el sitio Rafiki tal cual se ve en escritorio:
 * texto real (seleccionable/buscable), fotos y diseno identicos, sin capturas.
 *
 * Cada pagina web se imprime como UNA hoja PDF alta (sin cortes a mitad de
 * seccion) y al final se unen todas en un solo archivo con marcadores
 * (requiere Python con PyMuPDF y Pillow: pip install pymupdf pillow).
 *
 * Uso:
 *   node generar-pdf-completo.js [--base http://localhost:8080] [--out <archivo.pdf>] [--width 1440]
 */

const fs = require('fs');
const path = require('path');
const os = require('os');
const { execFileSync } = require('child_process');
const puppeteer = require('puppeteer-core');

const REPO_ROOT = path.resolve(__dirname, '..', '..');

// [titulo del marcador, ruta]
const PAGINAS = [
  ['Home', ''],
  ['Why Rafiki', 'why-rafiki'],
  ['Meet Rafiki', 'meet-rafiki'],
  ['Ecological Mission', 'ecological-mission'],
  ['Stay', 'stay'],
  ['Stay - Luxury Safari Tents', 'stay/luxury-safari-tents'],
  ['Stay - Main Lodge', 'stay/main-lodge'],
  ['Stay - Beach Camp', 'stay/beach-camp'],
  ['Stay - Lekker Bar and Braai', 'stay/lekker-bar-and-braai'],
  ['Experiences', 'experiences'],
  ['Experiences - White Water Rafting', 'experiences/white-water-rafting'],
  ['Experiences - Horseback Riding', 'experiences/horseback-riding'],
  ['Experiences - Hiking', 'experiences/hiking'],
  ['Experiences - Kayaking', 'experiences/kayaking'],
  ['Experiences - Birding', 'experiences/birding'],
  ['Experiences - Fishing', 'experiences/fishing'],
  ['Experiences - Massage', 'experiences/massage'],
  ['Packages', 'packages'],
  ['Packages - Rafiki Safari', 'packages/rafiki-safari'],
  ['Packages - Safarito', 'packages/safarito'],
  ['Packages - Savegre Adventure', 'packages/savegre-adventure'],
  ['Packages - Super Lekker Safari', 'packages/super-lekker-safari'],
  ['Plan Your Trip', 'plan-your-trip'],
  ['Bring Your Group', 'bring-your-group'],
  ['Rafiki Journal', 'rafiki-journal'],
  ['Journal - Why Families Reconnect Differently at Rafiki', 'why-families-reconnect-differently-at-rafiki'],
  ["Journal - Why Rafiki Isn't a Safari", 'why-rafiki-isnt-a-safari'],
  ['Journal - What Retreat Leaders Need Before Choosing a Venue', 'what-retreat-leaders-need-before-choosing-a-venue'],
  ['Journal - Egravica Enduro Series', 'rafki-hosted-the-4th-leg-of-the-egravica-enduro-series'],
  ['Journal - A Visit from Costa Rica Vacations', 'a-visit-from-costa-rica-vacations'],
  ['Journal - New Safari Truck', 'new-safari-truck'],
  ['Journal - News from the Lodge', 'news-from-the-lodge'],
  ['Journal - Rafiki on Wonderlust', 'rafiki-on-wonderlust'],
  ['Journal - Rafiki on The Traveling Muggles', 'rafiki-on-the-traveling-muggles-website'],
  ['Journal - We Travel Responsible', 'rafiki-safari-lodge-on-we-travel-responsible-a-danish-travel-website'],
  ['Journal - Pura Vida Traveling', 'rafiki-featured-on-pura-vida-traveling'],
  ['Contact', 'contact'],
];

// Altura maxima de hoja que los visores PDF manejan bien (14400pt = 19200px CSS).
const MAX_PAGE_PX = 19000;

function parseArgs(argv) {
  const args = {};
  for (let i = 0; i < argv.length; i++) {
    if (argv[i].startsWith('--')) args[argv[i].slice(2)] = argv[++i];
  }
  return args;
}

function encontrarChrome() {
  const candidatos = [
    process.env.PUPPETEER_EXECUTABLE_PATH,
    'C:/Program Files/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
    process.env.LOCALAPPDATA + '/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
    'C:/Program Files/Microsoft/Edge/Application/msedge.exe',
  ];
  for (const c of candidatos) if (c && fs.existsSync(c)) return c;
  throw new Error('No se encontro Chrome/Edge. Define PUPPETEER_EXECUTABLE_PATH.');
}

// Prepara la pagina para imprimirse igual que en pantalla.
async function prepararPagina(page, viewportH) {
  await page.evaluate(async (viewportH) => {
    // 1) Unidades vh/svh/dvh/lvh: al imprimir se calculan contra la altura de
    //    la hoja (enorme), asi que se congelan en px segun el viewport real.
    const toPx = (v) =>
      v.replace(/(-?\d*\.?\d+)(s|d|l)?vh\b/g, (_, n) => `${(parseFloat(n) * viewportH) / 100}px`);
    const fixRules = (rules) => {
      for (const r of rules) {
        if (r.style) {
          for (let i = 0; i < r.style.length; i++) {
            const prop = r.style[i];
            const val = r.style.getPropertyValue(prop);
            if (/vh\b/.test(val)) r.style.setProperty(prop, toPx(val), r.style.getPropertyPriority(prop));
          }
        }
        if (r.cssRules) fixRules(r.cssRules);
      }
    };
    for (const sheet of document.styleSheets) {
      try { fixRules(sheet.cssRules); } catch (_) { /* hoja de otro dominio */ }
    }
    document.querySelectorAll('[style*="vh"]').forEach((el) => {
      el.setAttribute('style', toPx(el.getAttribute('style')));
    });

    // 2) Imagenes diferidas -> carga inmediata.
    document.querySelectorAll('img[loading="lazy"]').forEach((img) => { img.loading = 'eager'; });
    document.querySelectorAll('iframe[loading="lazy"]').forEach((f) => { f.loading = 'eager'; });

    // 3) Recorrer la pagina para disparar cualquier carga por scroll.
    for (let y = 0; y < document.body.scrollHeight; y += 500) {
      window.scrollTo(0, y);
      await new Promise((r) => setTimeout(r, 80));
    }
    window.scrollTo(0, 0);
    window.dispatchEvent(new Event('scroll'));

    // 4) Esperar a que todas las imagenes terminen de decodificar.
    await Promise.all(
      [...document.images].map((img) =>
        img.complete ? img.decode().catch(() => {}) : new Promise((r) => { img.onload = img.onerror = r; })
      )
    );
    if (document.fonts) await document.fonts.ready;
  }, viewportH);
  await new Promise((r) => setTimeout(r, 1200));

  // Los iframes externos (mapa de Google) salen en blanco al imprimir: se
  // reemplazan por una imagen de como se ven en pantalla.
  const iframes = await page.$$('iframe');
  if (iframes.length) await new Promise((r) => setTimeout(r, 2500));
  for (const frame of iframes) {
    const box = await frame.boundingBox();
    if (!box || box.width < 50 || box.height < 50) continue;
    await frame.scrollIntoView();
    await new Promise((r) => setTimeout(r, 1000));
    const png = await frame.screenshot({ encoding: 'base64' });
    await frame.evaluate((el, src) => {
      const img = document.createElement('img');
      img.src = `data:image/png;base64,${src}`;
      img.className = el.className;
      img.style.cssText = `display:block;width:${el.offsetWidth}px;height:${el.offsetHeight}px;border:0;`;
      el.replaceWith(img);
    }, png);
  }
  if (iframes.length) {
    await page.evaluate(() => window.scrollTo(0, 0));
    await new Promise((r) => setTimeout(r, 500));
  }
}

(async () => {
  const args = parseArgs(process.argv.slice(2));
  const base = (args.base || 'http://localhost:8080').replace(/\/$/, '');
  const width = parseInt(args.width, 10) || 1440;
  const viewportH = 900;
  const fecha = new Date().toISOString().slice(0, 10);
  const outPath = path.resolve(args.out || path.join(REPO_ROOT, `rafiki-web-completo-${fecha}.pdf`));
  const tmpDir = fs.mkdtempSync(path.join(os.tmpdir(), 'rafiki-pdf-'));

  const browser = await puppeteer.launch({
    executablePath: encontrarChrome(),
    headless: true,
    args: ['--no-sandbox', '--hide-scrollbars', '--font-render-hinting=none'],
  });

  const partes = [];
  try {
    const page = await browser.newPage();
    await page.setViewport({ width, height: viewportH, deviceScaleFactor: 2 });
    // Sin animaciones (el slideshow del hero queda fijo en la primera foto).
    await page.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
    await page.emulateMediaType('screen');

    for (const [i, [titulo, ruta]] of PAGINAS.entries()) {
      const url = ruta ? `${base}/${ruta}/` : `${base}/`;
      process.stdout.write(`[${i + 1}/${PAGINAS.length}] ${url} ... `);
      const resp = await page.goto(url, { waitUntil: 'networkidle0', timeout: 90000 });
      if (!resp || !resp.ok()) {
        console.log(`OMITIDA (HTTP ${resp ? resp.status() : '?'})`);
        continue;
      }
      await prepararPagina(page, viewportH);

      const altura = await page.evaluate(() =>
        Math.ceil(Math.max(document.documentElement.scrollHeight, document.body.scrollHeight))
      );
      // Si la pagina es mas alta de lo que un visor PDF soporta, se reduce la
      // escala (sigue siendo vectorial, solo cambia el tamano fisico de la hoja).
      const scale = Math.min(1, MAX_PAGE_PX / altura);
      const file = path.join(tmpDir, `${String(i).padStart(2, '0')}.pdf`);
      await page.pdf({
        path: file,
        printBackground: true,
        width: `${Math.floor(width * scale)}px`,
        height: `${Math.ceil(altura * scale) + 2}px`,
        scale,
        margin: { top: 0, right: 0, bottom: 0, left: 0 },
        pageRanges: '1',
      });
      partes.push({ titulo, file });
      console.log(`ok (${altura}px${scale < 1 ? `, escala ${scale.toFixed(2)}` : ''})`);
    }
  } finally {
    await browser.close();
  }

  // Unir todo con marcadores y recomprimir las fotos (Chrome las incrusta sin
  // perdida y el PDF pasa de 300+ MB); se usa PyMuPDF + Pillow.
  const manifest = path.join(tmpDir, 'manifest.json');
  fs.writeFileSync(manifest, JSON.stringify({ out: outPath, partes }));
  const py = `
import json, sys, io
import fitz
from PIL import Image
m = json.load(open(sys.argv[1], encoding='utf-8'))
doc = fitz.open()
toc = []
for p in m['partes']:
    toc.append([1, p['titulo'], len(doc) + 1])
    doc.insert_pdf(fitz.open(p['file']))
doc.set_toc(toc)
doc.set_metadata({'title': 'Rafiki Safari Lodge - Sitio web completo'})
seen = set()
for page in doc:
    for im in page.get_images(full=True):
        xref, smask = im[0], im[1]
        # Las imagenes con transparencia (logo, iconos) se dejan intactas.
        if xref in seen or smask:
            continue
        seen.add(xref)
        info = doc.extract_image(xref)
        if info['ext'] in ('jpeg', 'jpg') or len(info['image']) < 150000:
            continue
        buf = io.BytesIO()
        Image.open(io.BytesIO(info['image'])).convert('RGB').save(
            buf, 'JPEG', quality=88, optimize=True, progressive=True, subsampling=0)
        if buf.tell() < len(info['image']):
            page.replace_image(xref, stream=buf.getvalue())
doc.save(m['out'], garbage=4, deflate=True, use_objstms=1)
`;
  execFileSync('python', ['-c', py, manifest], { stdio: 'inherit' });
  fs.rmSync(tmpDir, { recursive: true, force: true });

  const mb = (fs.statSync(outPath).size / 1024 / 1024).toFixed(1);
  console.log(`\nListo: ${outPath} (${partes.length} paginas, ${mb} MB)`);
})().catch((err) => {
  console.error('\nError generando el PDF:', err);
  process.exit(1);
});
