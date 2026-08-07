/**
 * Single Service Explore — JS-only Load More
 * File: blocks/single-service-explore/single-service-explore.js
 *
 * No AJAX — hidden items are already in DOM with display:none.
 * Click reveals them with a fade-in animation.
 */

(function () {
  'use strict';

  function initSection(section) {
    var btn      = section.querySelector('[data-loadmore]');
    var list     = section.querySelector('.sse-list');
    if (!btn || !list) return;

    var hidden = Array.prototype.slice.call(
      list.querySelectorAll('.sse-item--hidden')
    );
    if (!hidden.length) return;

    btn.addEventListener('click', function () {
      // Show all hidden items with stagger
      hidden.forEach(function (item, i) {
        item.style.opacity = '0';
        item.classList.remove('sse-item--hidden');

        // Stagger fade-in
        setTimeout(function () {
          item.style.transition = 'opacity 0.4s ease';
          item.style.opacity    = '1';
        }, i * 60);
      });

      // Hide button
      btn.setAttribute('aria-expanded', 'true');

      // Hide wrap after transition
      var wrap = btn.closest('.sse-loadmore-wrap');
      if (wrap) {
        setTimeout(function () {
          wrap.style.display = 'none';
        }, hidden.length * 60 + 400);
      }
    });
  }

  function init() {
    document.querySelectorAll('.sse-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
