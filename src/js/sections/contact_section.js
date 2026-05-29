/**
 * Contact Section — select caret toggle
 * File: blocks/contact-section/contact-section.js
 *
 * Adds .is-open to .cs-form__select-wrap on focus/blur
 * so the SVG caret rotates when the native <select> is open.
 */
(function () {
  'use strict';

  function initSelectCarets() {
    document.querySelectorAll('.cs-form__select-wrap').forEach(function (wrap) {
      var select = wrap.querySelector('select');
      if (!select) return;

      select.addEventListener('focus', function () {
        wrap.classList.add('is-open');
      });

      select.addEventListener('blur', function () {
        wrap.classList.remove('is-open');
      });

      // mousedown fires before blur, so toggling on click feels snappier
      select.addEventListener('mousedown', function () {
        wrap.classList.toggle('is-open');
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSelectCarets);
  } else {
    initSelectCarets();
  }
})();
