/**
 * Our Partners — marquee + scroll-driven bar expansion
 */

(function () {
  'use strict';

  function clamp01(x) { return Math.max(0, Math.min(1, x)); }
  function easeOut(t)  { return 1 - Math.pow(1 - t, 3); }

  // ── Marquee ──────────────────────────────────────────────────────────────────
  // PHP renders logos twice as a no-JS fallback (CSS animates -50%).
  // JS measures one set's actual pixel width, clones more copies until the track
  // spans at least 3× the viewport, then sets --op-single-w so the animation
  // shifts by exactly one set — seamless regardless of how many logos exist.

  function initMarquee(section) {
    var track = section.querySelector('.op-track');
    if (!track) return;

    var logoCount   = parseInt(track.getAttribute('data-logo-count') || '0', 10);
    if (!logoCount) return;

    // PHP renders logo+divider pairs; one set = logoCount × 2 child elements.
    var itemsPerSet = logoCount * 2;
    var oneSet      = null; // captured after images load
    var singleW     = 0;

    function cloneUntilWide() {
      var needed = window.innerWidth * 3;
      while (track.scrollWidth < needed) {
        oneSet.forEach(function (el) { track.appendChild(el.cloneNode(true)); });
      }
    }

    function setup() {
      var children = Array.prototype.slice.call(track.children);
      oneSet = children.slice(0, itemsPerSet);

      singleW = oneSet.reduce(function (sum, el) {
        return sum + el.getBoundingClientRect().width;
      }, 0);

      if (singleW === 0) return; // images still not laid out — skip

      cloneUntilWide();
      track.style.setProperty('--op-single-w', '-' + Math.round(singleW) + 'px');
    }

    // Run setup after all images have layout (complete or errored).
    var imgs    = Array.prototype.slice.call(track.querySelectorAll('img'));
    var pending = imgs.filter(function (img) { return !img.complete; }).length;

    if (pending === 0) {
      setup();
    } else {
      function onLoad() { if (--pending <= 0) setup(); }
      imgs.forEach(function (img) {
        if (!img.complete) {
          img.addEventListener('load',  onLoad);
          img.addEventListener('error', onLoad);
        }
      });
    }

    // Clone more on resize in case the viewport grew.
    window.addEventListener('resize', function () {
      if (oneSet) cloneUntilWide();
    }, { passive: true });
  }

  // ── Bar animation ─────────────────────────────────────────────────────────────

  function initSection(section) {
    var bars = Array.prototype.slice.call(section.querySelectorAll('.op-bar'));
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

    // Scroll progress (q):
    //   0 = section top at viewport bottom (just entered)
    //   1 = section bottom at viewport top (just left)
    // Width expansion completes at q = 0.5 so bars reach 100% before the section
    // exits, enabling the seamless blend into the white next section.
    function getScrollQ() {
      var rect    = section.getBoundingClientRect();
      var vh      = window.innerHeight;
      // Start animating when the section top crosses the 75% mark from the top
      // of the viewport (i.e. appears about a quarter of the screen up from the bottom).
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

    // Apply correct state for the current scroll position on load
    onScroll();
  }

  function init() {
    document.querySelectorAll('.op-section').forEach(function (section) {
      initMarquee(section);
      initSection(section);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
