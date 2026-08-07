/**
 * Contact Section
 * - Select caret toggle
 * - CF7: button "Sending…" state on submit, restore on error
 * - CF7: inject + reveal success overlay with blur on sent
 */
(function () {
  'use strict';

  // ── Select caret ──────────────────────────────────────────────────────────────
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
      select.addEventListener('mousedown', function () {
        wrap.classList.toggle('is-open');
      });
    });
  }

  // ── Success overlay ───────────────────────────────────────────────────────────
  function injectSuccessOverlays() {
    document.querySelectorAll('.cs-form').forEach(function (csForm) {
      if (csForm.querySelector('.cs-form__success')) return;

      var overlay = document.createElement('div');
      overlay.className = 'cs-form__success';
      overlay.setAttribute('role', 'status');
      overlay.setAttribute('aria-live', 'polite');
      overlay.setAttribute('aria-hidden', 'true');
      overlay.innerHTML =
        '<div class="cs-form__success-inner">' +
          '<div class="cs-form__success-icon">' +
            '<svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' +
              '<circle cx="36" cy="36" r="35" stroke="currentColor" stroke-width="2"/>' +
              '<path d="M22 36L31 45L50 26" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>' +
            '</svg>' +
          '</div>' +
          '<p class="cs-form__success-title">Thank You!</p>' +
          '<p class="cs-form__success-text">Your request has been received.<br>We’ll be in touch shortly.</p>' +
        '</div>';

      csForm.appendChild(overlay);
    });
  }

  // ── CF7 events ────────────────────────────────────────────────────────────────
  function getSubmitBtn(form) {
    return form.querySelector('input[type="submit"]');
  }

  function initCF7() {
    // Native submit fires before AJAX — change button immediately on user action
    // (wpcf7submit fires AFTER the response, so using it would race with wpcf7invalid)
    document.addEventListener('submit', function (e) {
      if (!e.target.classList.contains('wpcf7-form')) return;
      var btn = getSubmitBtn(e.target);
      if (!btn || btn.disabled) return; // guard against double-submit
      btn.dataset.originalValue = btn.value;
      btn.value = 'Sending…';
      btn.disabled = true;
    });

    // Success — blur form content and reveal overlay, then auto-dismiss after 4s
    document.addEventListener('wpcf7mailsent', function (e) {
      var csForm = e.target.closest('.cs-form');
      if (!csForm) return;
      csForm.classList.add('is-sent');
      var overlay = csForm.querySelector('.cs-form__success');
      if (overlay) overlay.setAttribute('aria-hidden', 'false');

      setTimeout(function () {
        csForm.classList.remove('is-sent');
        if (overlay) overlay.setAttribute('aria-hidden', 'true');
        var btn = getSubmitBtn(e.target);
        if (btn) {
          btn.value = btn.dataset.originalValue || 'Send Request';
          btn.disabled = false;
        }
      }, 4000);
    });

    // Validation error — restore button
    document.addEventListener('wpcf7invalid', function (e) {
      var btn = getSubmitBtn(e.target);
      if (!btn) return;
      btn.value = btn.dataset.originalValue || 'Send Request';
      btn.disabled = false;
    });

    // Server-side send failure — restore button
    document.addEventListener('wpcf7mailfailed', function (e) {
      var btn = getSubmitBtn(e.target);
      if (!btn) return;
      btn.value = btn.dataset.originalValue || 'Send Request';
      btn.disabled = false;
    });

    // Spam — restore button (CF7 still blocks silently, but button must recover)
    document.addEventListener('wpcf7spam', function (e) {
      var btn = getSubmitBtn(e.target);
      if (!btn) return;
      btn.value = btn.dataset.originalValue || 'Send Request';
      btn.disabled = false;
    });
  }

  function init() {
    initSelectCarets();
    injectSuccessOverlays();
    initCF7();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
