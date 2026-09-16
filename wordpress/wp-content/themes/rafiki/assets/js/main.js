document.addEventListener('DOMContentLoaded', function () {
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

  navToggle.addEventListener('click', function () {
    var open = header.classList.toggle('nav-open');
    navToggle.classList.toggle('open', open);
    navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  document.querySelectorAll('.main-nav a').forEach(function (link) {
    link.addEventListener('click', function () {
      header.classList.remove('nav-open');
      navToggle.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
    });
  });
});
