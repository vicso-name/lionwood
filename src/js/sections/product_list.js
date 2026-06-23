/**
 * Product List — sticky scroll product switcher.
 *
 * Desktop: pl-pin-wrapper height is set by JS so that the sticky
 * pl-pin-inner stays pinned long enough for the user to scroll
 * through all products (STEP px per product).
 *
 * Progress 0→1 maps to products[0]→products[N-1].
 * Active index is updated on scroll; clicking a nav item
 * scrolls to the corresponding position within the wrapper.
 */

(function () {
  'use strict';

  var STEP = 250; // px of scroll budget per product

  function init() {
    var section = document.querySelector('.pl-section');
    if (!section) return;

    var wrapper    = section.querySelector('.pl-pin-wrapper');
    var navItems   = Array.prototype.slice.call(section.querySelectorAll('.pl-nav__item'));
    var visualSlides = Array.prototype.slice.call(section.querySelectorAll('.pl-visual__slide'));
    var infoSlides   = Array.prototype.slice.call(section.querySelectorAll('.pl-info__slide'));

    var total = navItems.length;
    if (total < 2) return; // nothing to switch

    var isMobile = false;

    function checkMobile() {
      isMobile = window.innerWidth < 1024;
    }

    // ── Desktop only: set wrapper height ────────────────────────────────────
    var pinInner = wrapper.querySelector('.pl-pin-inner');

    function setWrapperHeight() {
      if (isMobile) {
        wrapper.style.height = '';
        return;
      }
      wrapper.style.height = (window.innerHeight + (total - 1) * STEP) + 'px';
    }

    // ── Activate a product index ─────────────────────────────────────────────
    var currentIndex = 0;

    function activate(idx) {
      if (idx === currentIndex && navItems[idx].classList.contains('pl-nav__item--active')) return;
      currentIndex = idx;

      navItems.forEach(function (item, i) {
        item.classList.toggle('pl-nav__item--active', i === idx);
      });
      visualSlides.forEach(function (slide, i) {
        slide.classList.toggle('pl-visual__slide--active', i === idx);
      });
      infoSlides.forEach(function (slide, i) {
        slide.classList.toggle('pl-info__slide--active', i === idx);
      });
    }

    // ── Scroll handler (desktop) ─────────────────────────────────────────────
    function onScroll() {
      if (isMobile) return;

      var rect    = wrapper.getBoundingClientRect();
      var scrolled = Math.max(0, -rect.top);
      // Each product occupies exactly STEP px; last product has no upper bound
      // → zero dead-zone at the end of the wrapper.
      var idx = Math.min(Math.floor(scrolled / STEP), total - 1);
      activate(idx);
    }

    // ── Nav click: scroll to that product's position ─────────────────────────
    navItems.forEach(function (item, idx) {
      item.addEventListener('click', function () {
        if (isMobile) {
          activate(idx);
          return;
        }

        var wrapperTop   = wrapper.getBoundingClientRect().top + window.scrollY;
        var targetScroll = wrapperTop + idx * STEP;

        window.scrollTo({ top: targetScroll, behavior: 'smooth' });
      });

      // Keyboard support
      item.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          item.click();
        }
      });
    });

    // ── Init & event binding ─────────────────────────────────────────────────
    checkMobile();
    setWrapperHeight();
    onScroll();

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function () {
      checkMobile();
      setWrapperHeight();
      onScroll();
    }, { passive: true });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
