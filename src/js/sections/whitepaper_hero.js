/**
 * Whitepaper Hero — load-in animation
 */

(function () {
  'use strict';

  function initLoadAnim(section) {
    section.querySelectorAll('.wph-anim').forEach(function (el) {
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
    var section = document.querySelector('.wph-section');
    if (!section) return;
    initLoadAnim(section);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
