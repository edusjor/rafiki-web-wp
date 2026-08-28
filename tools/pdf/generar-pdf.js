#!/usr/bin/env node
/**
 * Genera un PDF de UNA pagina del sitio Rafiki.
 *
 * Uso:
 *   node generar-pdf.js <pagina> [opciones]
 *
 * <pagina> puede ser:
 *   - "home"  -> http://localhost:8080/            -> home.pdf
 *   - un slug -> http://localhost:8080/<slug>/     -> <slug>.pdf
 *   - una URL completa (http...)                   -> <ultimo-segmento>.pdf
 *
 * Opciones:
 *   --out <carpeta>     Carpeta de salida (por defecto: raiz del repo)
 *   --name <archivo>    Fuerza el nombre del PDF (sin extension)
 *   --base <url>        Base del sitio (por defecto http://localhost:8080)
 *   --single           Un PDF de una sola pagina larga (tipo captura) en vez de A4
 *   --width <px>        Ancho del viewport (por defecto 1280)
 *   --list             Lista las paginas conocidas y termina
 *
 * Ejemplos:
 *   node generar-pdf.js home
 *   node generar-pdf.js why-rafiki
 *   node generar-pdf.js plan-your-trip --single
 *   npm run pdf -- meet-rafiki
 */

const fs = require('fs');
const path = require('path');
const os = require('os');
const puppeteer = require('puppeteer-core');

const REPO_ROOT = path.resolve(__dirname, '..', '..');

// Paginas conocidas (solo para --list y para aceptar alias comodos).
// Cualquier otro slug tambien funciona: se arma http://localhost:8080/<slug>/
const PAGINAS_CONOCIDAS = {
  home: '',
  'why-rafiki': 'why-rafiki',
  'meet-rafiki': 'meet-rafiki',
  'plan-your-trip': 'plan-your-trip',
  'bring-your-group': 'bring-your-group',
  'rafiki-journal': 'rafiki-journal',
  'home-draft-6-sections': 'home-draft-6-sections',
  shop: 'shop',
  cart: 'cart',
  checkout: 'checkout',
  'my-account': 'my-account',
};

function parseArgs(argv) {
  const args = { _: [] };
  for (let i = 0; i < argv.length; i++) {
    const a = argv[i];
    if (a === '--single' || a === '--list') args[a.slice(2)] = true;
    else if (a.startsWith('--')) args[a.slice(2)] = argv[++i];
    else args._.push(a);
  }
  return args;
}

function encontrarChrome() {
  if (process.env.PUPPETEER_EXECUTABLE_PATH && fs.existsSync(process.env.PUPPETEER_EXECUTABLE_PATH)) {
    return process.env.PUPPETEER_EXECUTABLE_PATH;
  }
  const candidatos = [
    'C:/Program Files/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
    process.env.LOCALAPPDATA + '/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
    'C:/Program Files/Microsoft/Edge/Application/msedge.exe',
    '/usr/bin/google-chrome',
    '/usr/bin/chromium',
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  ];
  for (const c of candidatos) {
    try { if (c && fs.existsSync(c)) return c; } catch (_) {}
  }
  throw new Error('No se encontro Chrome/Edge. Define PUPPETEER_EXECUTABLE_PATH con la ruta al ejecutable.');
}

async function autoScroll(page) {
  // Recorre toda la pagina para forzar la carga de cualquier imagen diferida.
  await page.evaluate(async () => {
    await new Promise((resolve) => {
      let total = 0;
      const paso = 400;
      const timer = setInterval(() => {
        window.scrollBy(0, paso);
        total += paso;
        if (total >= document.body.scrollHeight) {
          clearInterval(timer);
          window.scrollTo(0, 0);
          resolve();
        }
      }, 100);
    });
  });
  await new Promise((r) => setTimeout(r, 500));
}

(async () => {
  const args = parseArgs(process.argv.slice(2));

  if (args.list) {
    console.log('Paginas conocidas:');
    for (const k of Object.keys(PAGINAS_CONOCIDAS)) console.log('  - ' + k);
    console.log('\n(Tambien puedes pasar cualquier otro slug o una URL completa.)');
    return;
  }

  const pagina = args._[0];
  if (!pagina) {
    console.error('Falta el argumento <pagina>. Ej: node generar-pdf.js home');
    process.exit(1);
  }

  const base = (args.base || 'http://localhost:8080').replace(/\/$/, '');

  // Resolver URL + nombre de archivo
  let url;
  let nombre;
  if (/^https?:\/\//i.test(pagina)) {
    url = pagina;
    const segs = new URL(pagina).pathname.split('/').filter(Boolean);
    nombre = segs.length ? segs[segs.length - 1] : 'home';
  } else if (pagina in PAGINAS_CONOCIDAS) {
    const slug = PAGINAS_CONOCIDAS[pagina];
    url = slug ? `${base}/${slug}/` : `${base}/`;
    nombre = pagina === 'home' || slug === '' ? 'home' : slug;
  } else {
    const slug = pagina.replace(/^\/+|\/+$/g, '');
    url = `${base}/${slug}/`;
    nombre = slug || 'home';
  }

  if (args.name) nombre = String(args.name).replace(/\.pdf$/i, '');
  const outDir = args.out ? path.resolve(args.out) : REPO_ROOT;
  fs.mkdirSync(outDir, { recursive: true });
  const outPath = path.join(outDir, `${nombre}.pdf`);

  const width = parseInt(args.width, 10) || 1440;

  console.log(`Pagina : ${url}`);
  console.log(`Salida : ${outPath}`);
  console.log(`Modo   : ${args.single ? 'una pagina larga' : 'A4 paginado'}`);

  const browser = await puppeteer.launch({
    executablePath: encontrarChrome(),
    headless: true,
    args: ['--no-sandbox', '--disable-gpu', '--hide-scrollbars'],
  });

  try {
    const page = await browser.newPage();
    await page.setViewport({ width, height: 1200, deviceScaleFactor: 2 });
    const resp = await page.goto(url, { waitUntil: 'networkidle0', timeout: 60000 });
    if (resp && !resp.ok()) {
      console.warn(`Aviso: el servidor respondio ${resp.status()} para ${url}`);
    }
    await page.emulateMediaType('screen');
    await autoScroll(page);
    await page.evaluate(() => document.fonts && document.fonts.ready);

    const common = {
      path: outPath,
      printBackground: true,
      margin: { top: 0, right: 0, bottom: 0, left: 0 },
    };

    if (args.single) {
      // Una sola hoja tan alta como la pagina, al ancho de escritorio.
      const height = await page.evaluate(() =>
        Math.max(
          document.body.scrollHeight,
          document.documentElement.scrollHeight,
          document.body.offsetHeight
        )
      );
      await page.pdf({ ...common, width: `${width}px`, height: `${height}px`, pageRanges: '1' });
    } else {
      // Paginado: la hoja mantiene el ANCHO de escritorio (para que se
      // apliquen los breakpoints de desktop, no los de tablet/movil) y
      // se corta en varias paginas con proporcion tipo A4 vertical.
      const pageHeight = Math.round(width * (297 / 210));
      await page.pdf({ ...common, width: `${width}px`, height: `${pageHeight}px` });
    }

    const kb = (fs.statSync(outPath).size / 1024).toFixed(0);
    console.log(`\nListo: ${outPath} (${kb} KB)`);
  } finally {
    await browser.close();
  }
})().catch((err) => {
  console.error('\nError generando el PDF:', err.message);
  process.exit(1);
});
