/**
 * Products Hero — load-in animation.
 * Mirrors career_hero.js without the socials expand logic.
 */

(function () {
  'use strict';

  function init() {
    var section = document.querySelector('.prh-section');
    if (!section) return;

    section.querySelectorAll('.prh-anim').forEach(function (el) {
      var delay = el.getAttribute('data-delay') || '0';
      el.style.setProperty('--anim-delay', delay + 'ms');
    });

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        section.classList.add('is-loaded');
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
