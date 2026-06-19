/**
 * Author Archive — scroll-driven bar expansion for .auh-highlights
 * Mirrors career_values.js logic, targeting .auh-highlights / .auh-bar.
 */

(function () {
  'use strict';

  function clamp01(x) { return Math.max(0, Math.min(1, x)); }
  function easeOut(t)  { return 1 - Math.pow(1 - t, 3); }

  function initHighlights(section) {
    var bars = Array.prototype.slice.call(section.querySelectorAll('.auh-bar'));
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

  function initLoadMore() {
    var grid  = document.getElementById('auh-posts-grid');
    var btn   = document.getElementById('auh-load-more');
    if (!grid || !btn) return;

    var STEP  = 3;
    var shown = 6;
    var wraps = Array.prototype.slice.call(grid.querySelectorAll('.apc-card-wrap'));
    var total = wraps.length;

    if (shown >= total) {
      btn.style.display = 'none';
      return;
    }

    btn.addEventListener('click', function () {
      var limit = Math.min(shown + STEP, total);
      for (var i = shown; i < limit; i++) {
        wraps[i].classList.remove('apc-card-wrap--hidden');
      }
      shown = limit;
      if (shown >= total) {
        btn.style.display = 'none';
      }
    });
  }

  function initExtLoadMore() {
    var list  = document.getElementById('auh-ext-list');
    var btn   = document.getElementById('auh-ext-load-more');
    if (!list || !btn) return;

    var items = Array.prototype.slice.call(list.querySelectorAll('.auh-ext__item'));
    var total = items.length;
    var shown = Math.min(3, total);

    btn.addEventListener('click', function () {
      if (shown < total) {
        items[shown].classList.remove('auh-ext__item--hidden');
        shown++;
        var remaining = total - shown;
        if (remaining <= 0) {
          btn.style.display = 'none';
        } else {
          btn.textContent = 'Load more (+' + remaining + ')';
        }
      }
    });
  }

  function initLoadAnim() {
    var section = document.querySelector('.auh-section');
    if (!section) return;

    section.querySelectorAll('.auh-anim').forEach(function (el) {
      var delay = el.getAttribute('data-delay') || '0';
      el.style.setProperty('--anim-delay', delay + 'ms');
    });

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        section.classList.add('is-loaded');
      });
    });
  }

  function init() {
    initLoadAnim();
    document.querySelectorAll('.auh-highlights').forEach(initHighlights);
    initLoadMore();
    initExtLoadMore();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
