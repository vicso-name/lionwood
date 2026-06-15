/**
 * Partnership Partners — marquee + scroll-driven bar expansion
 */

(function () {
  'use strict';

  function clamp01(x) { return Math.max(0, Math.min(1, x)); }
  function easeOut(t)  { return 1 - Math.pow(1 - t, 3); }

  function initMarquee(section) {
    var track = section.querySelector('.pp-track');
    if (!track) return;

    var logoCount = parseInt(track.getAttribute('data-logo-count') || '0', 10);
    if (!logoCount) return;

    // PHP renders 2 passes; each logo = .pp-logo + .pp-divider = 2 children
    var singleSetSize = logoCount * 2;

    function setup() {
      // Pause animation during DOM surgery so there's no mid-frame flash
      track.style.animation = 'none';

      // Trim back to exactly one original set
      while (track.children.length > singleSetSize) {
        track.removeChild(track.lastChild);
      }

      var singleW = track.scrollWidth;
      if (!singleW) return;

      var origNodes = Array.prototype.slice.call(track.children);
      // Need at least 2 full sets + one viewport width of buffer
      var minW = singleW * 2 + window.innerWidth;

      while (track.scrollWidth < minW) {
        origNodes.forEach(function (node) {
          track.appendChild(node.cloneNode(true));
        });
      }

      // Update the keyframe target to exactly one set width (px)
      section.style.setProperty('--pp-single-w', '-' + singleW + 'px');

      // Force layout so animation:none commits before we restore it;
      // without this the browser may coalesce both style writes and skip the reset.
      void track.offsetHeight;
      track.style.animation = '';
    }

    // Double rAF: first pass lets the browser finish initial layout,
    // second pass measures after that layout is committed.
    requestAnimationFrame(function () { requestAnimationFrame(setup); });

    var resizeTimer = null;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        requestAnimationFrame(function () { requestAnimationFrame(setup); });
      }, 200);
    }, { passive: true });
  }

  function initSection(section) {
    initMarquee(section);

    var bars = Array.prototype.slice.call(section.querySelectorAll('.pp-bar'));
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
    document.querySelectorAll('.pp-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
