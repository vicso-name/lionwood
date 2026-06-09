/**
 * Case Hero Section — load-in animation
 * Mirrors the hh-anim pattern from home_hero.js
 */

(function () {
  'use strict';

  function initLoadAnim(section) {
    section.querySelectorAll('.chs-anim').forEach(function (el) {
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
    var section = document.querySelector('.chs-section');
    if (!section) return;
    initLoadAnim(section);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
