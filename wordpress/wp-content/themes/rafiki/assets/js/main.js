document.addEventListener('DOMContentLoaded', function () {
  // Home hero slideshow — rotate .is-active through the stacked images.
  var heroSlides = document.querySelectorAll('[data-hero-slides] img');
  if (heroSlides.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    var heroIndex = 0;
    setInterval(function () {
      heroSlides[heroIndex].classList.remove('is-active');
      heroIndex = (heroIndex + 1) % heroSlides.length;
      heroSlides[heroIndex].classList.add('is-active');
    }, 3500);
  }

  var header = document.getElementById('siteHeader');
  var navToggle = document.getElementById('navToggle');

  var lastScrollY = window.scrollY;

  function onScroll() {
    var y = window.scrollY;

    if (y > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    // Hide-on-scroll-down / show-on-scroll-up (CSS limits this to mobile).
    // Never hide while the mobile menu is open or near the top of the page.
    if (!header.classList.contains('nav-open')) {
      if (y > lastScrollY && y > 120) {
        header.classList.add('header-hidden');
      } else if (y < lastScrollY) {
        header.classList.remove('header-hidden');
      }
    }

    lastScrollY = y;
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // Mobile menu: submenus collapse into an accordion, each opened by an
  // arrow button next to its parent link (hidden on desktop via CSS).
  // Opening one closes whichever was open before.
  var subItems = Array.prototype.slice.call(document.querySelectorAll('.main-nav .nav-item.has-sub'))
    .filter(function (item) { return item.querySelector('.sub-menu'); });

  function closeSubs(except) {
    subItems.forEach(function (item) {
      if (item === except) return;
      item.classList.remove('sub-open');
      var btn = item.querySelector('.sub-toggle');
      if (btn) btn.setAttribute('aria-expanded', 'false');
    });
  }

  subItems.forEach(function (item) {
    var parentLink = item.querySelector(':scope > a');
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'sub-toggle';
    btn.setAttribute('aria-expanded', 'false');
    btn.setAttribute('aria-label', 'Show ' + (parentLink ? parentLink.textContent.trim() : 'submenu'));
    item.insertBefore(btn, item.querySelector('.sub-menu'));
    btn.addEventListener('click', function () {
      var open = !item.classList.contains('sub-open');
      closeSubs(item);
      item.classList.toggle('sub-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  navToggle.addEventListener('click', function () {
    var open = header.classList.toggle('nav-open');
    navToggle.classList.toggle('open', open);
    navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (!open) closeSubs(null);
  });

  document.querySelectorAll('.main-nav a').forEach(function (link) {
    link.addEventListener('click', function () {
      header.classList.remove('nav-open');
      navToggle.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
      closeSubs(null);
    });
  });
});
