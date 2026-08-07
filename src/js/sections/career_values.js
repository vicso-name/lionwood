/**
 * Value Section — scroll-driven bar expansion
 * File: blocks/value-section/value-section.js
 *
 * Same bar animation logic as Our Partners (our-partners.js).
 * No marquee — bars only.
 */

(function () {
  'use strict';

  function clamp01(x) { return Math.max(0, Math.min(1, x)); }
  function easeOut(t)  { return 1 - Math.pow(1 - t, 3); }

  // ── Bar entrance (Phase 1) ───────────────────────────────────────────────────
  // Bars start collapsed (CSS: transform: scaleX(0)). Once the cluster enters
  // the viewport, each bar transitions to scaleX(1) with a short stagger so
  // the short center bars settle first and the rest "build outward" after —
  // matching Our Partners' entrance (our-partners.js).

  function initEntrance(section) {
    var wrap = section.querySelector('.cvs-bars');
    if (!wrap) return;

    var bars = Array.prototype.slice.call(wrap.querySelectorAll('.cvs-bar'));
    if (!bars.length) return;

    var reduceMotion = window.matchMedia &&
      window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) return; // CSS already forces scaleX(1) in this case

    function play() {
      bars.forEach(function (bar, i) {
        var dur   = 460 + i * 40;
        var delay = i * 85;
        bar.style.transition = 'transform ' + dur + 'ms cubic-bezier(0.16,1,0.3,1) ' + delay + 'ms';
        bar.style.transform = 'scaleX(1)';
      });
    }

    if (!('IntersectionObserver' in window)) {
      play();
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          play();
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });

    observer.observe(wrap);
  }

  function initCvsSection(section) {
    var bars = Array.prototype.slice.call(section.querySelectorAll('.cvs-bar'));
    if (!bars.length) return;

    var rafId = null;
    var bases = [];

    function readBases() {
      var sectionW = section.offsetWidth;
      bases = bars.map(function (bar) {
        return parseFloat(window.getComputedStyle(bar).width) / sectionW * 100;
      });
    }

    readBases();

    function getScrollQ() {
      var rect    = section.getBoundingClientRect();
      var vh      = window.innerHeight;
      var startAt = vh * 0.75;
      return clamp01((startAt - rect.top) / (startAt + section.offsetHeight));
    }

    function render() {
      var eW = easeOut(clamp01(getScrollQ() / 0.5));
      bars.forEach(function (bar, i) {
        var base = bases[i];
        bar.style.width = (base + (100 - base) * eW).toFixed(2) + '%';
      });
      rafId = null;
    }

    function onScroll() {
      if (!rafId) rafId = requestAnimationFrame(render);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function () {
      bars.forEach(function (bar) { bar.style.width = ''; });
      readBases();
      onScroll();
    }, { passive: true });

    onScroll();
  }

  function init() {
    document.querySelectorAll('.cvs-section').forEach(function (section) {
      initEntrance(section);
      initCvsSection(section);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
