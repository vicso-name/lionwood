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
    document.querySelectorAll('.cvs-section').forEach(initCvsSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
