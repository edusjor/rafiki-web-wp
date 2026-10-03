// Mobile audit: screenshots + horizontal overflow detection at phone width.
// Usage: node mobile-audit.js <outdir> [width] [slug,slug,...]
const fs = require('fs'); const path = require('path');
const puppeteer = require('puppeteer-core');
const out = process.argv[2]; const W = +(process.argv[3] || 390);
const ALL = ['', 'why-rafiki', 'meet-rafiki', 'plan-your-trip', 'bring-your-group', 'rafiki-journal', 'ecological-mission',
  'stay/luxury-safari-tents', 'stay/main-lodge', 'stay/beach-camp', 'stay/lekker-bar-and-braai', 'stay',
  'experiences', 'experiences/white-water-rafting', 'experiences/fishing', 'experiences/horseback-riding', 'experiences/massage',
  'experiences/hiking', 'experiences/kayaking', 'experiences/birding', 'experiences/aqua-hike',
  'packages', 'packages/rafiki-safari', 'packages/safarito', 'why-rafiki-isnt-a-safari', 'cart', 'checkout'];
const slugs = process.argv[4] ? process.argv[4].split(',').map(s => s === 'home' ? '' : s) : ALL;
(async () => {
  const browser = await puppeteer.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe', headless: 'new' });
  const page = await browser.newPage();
  await page.setViewport({ width: W, height: 844, isMobile: true, hasTouch: true, deviceScaleFactor: 1 });
  await page.setUserAgent('Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1');
  for (const s of slugs) {
    const url = 'http://localhost:8080/' + (s ? s + '/' : '');
    const name = (s || 'home').replace(/\//g, '_');
    try {
      const r = await page.goto(url, { waitUntil: 'networkidle2', timeout: 60000 });
      // reveal scroll-animations & lazy images
      await page.evaluate(async () => {
        document.querySelectorAll('.reveal,[data-reveal],.fade-in,.animate').forEach(e => e.classList.add('is-visible', 'visible', 'in-view'));
        for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 60)); }
        window.scrollTo(0, 0);
      });
      await new Promise(r => setTimeout(r, 600));
      const info = await page.evaluate((W) => {
        const sel = el => { let s = el.tagName.toLowerCase(); if (el.id) s += '#' + el.id; if (el.classList.length) s += '.' + [...el.classList].slice(0, 3).join('.'); return s; };
        const clipped = el => { for (let p = el.parentElement; p && p !== document.body; p = p.parentElement) { const o = getComputedStyle(p); if (/(hidden|clip|auto|scroll)/.test(o.overflowX)) { const pr = p.getBoundingClientRect(); if (pr.right <= W + 1) return true; } } return false; };
        const bad = [];
        document.querySelectorAll('body *').forEach(el => {
          const r = el.getBoundingClientRect(); if (!r.width || !r.height) return;
          const cs = getComputedStyle(el); if (cs.visibility === 'hidden' || cs.position === 'fixed') return;
          if ((r.right > W + 1 || r.left < -1) && !clipped(el)) bad.push(sel(el) + ` [${Math.round(r.left)}..${Math.round(r.right)}]`);
        });
        const small = [];
        document.querySelectorAll('p,li,a,span,td,label,small').forEach(el => { if (el.childElementCount === 0 && el.textContent.trim() && parseFloat(getComputedStyle(el).fontSize) < 12 && el.getBoundingClientRect().width) small.push(sel(el) + ' ' + getComputedStyle(el).fontSize); });
        return { sw: document.documentElement.scrollWidth, h: document.documentElement.scrollHeight, bad: bad.slice(0, 25), nbad: bad.length, small: [...new Set(small)].slice(0, 10) };
      }, W);
      console.log(`\n== ${url} [${r.status()}] scrollW=${info.sw} h=${info.h} overflow=${info.nbad}`);
      info.bad.forEach(b => console.log('  OVF ' + b));
      info.small.forEach(b => console.log('  SMALL ' + b));
      // segmented screenshots
      const H = 1400; const n = Math.min(Math.ceil(info.h / H), 14);
      for (let i = 0; i < n; i++) {
        await page.screenshot({ path: path.join(out, `${name}-${W}-${String(i).padStart(2, '0')}.jpg`), type: 'jpeg', quality: 60, clip: { x: 0, y: i * H, width: W, height: Math.min(H, info.h - i * H) }, captureBeyondViewport: true });
      }
    } catch (e) { console.log(`\n== ${url} ERROR ${e.message}`); }
  }
  await browser.close();
})();
