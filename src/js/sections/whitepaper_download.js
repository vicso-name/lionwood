/**
 * Whitepaper Download — HubSpot lead-gate form
 *
 * Flow:
 *  1. Button disabled until both fields are filled
 *  2. Submit → POST to HubSpot Forms API
 *  3. On success → auto-trigger PDF download → show success message
 *
 * HubSpot Forms API:
 *  POST https://api.hsforms.com/submissions/v3/integration/submit/{portalId}/{formGuid}
 *  Content-Type: application/json
 *  Body: { fields: [{name, value}], context: {pageUri, pageName} }
 */

(function () {
  'use strict';

  function init() {
    var section   = document.querySelector('.wpd-section');
    if (!section) return;

    var form      = section.querySelector('[data-wpd-form]');
    var success   = section.querySelector('[data-wpd-success]');
    var submitBtn = section.querySelector('[data-wpd-submit]');
    var btnLabel  = section.querySelector('[data-wpd-btn-label]');
    var errorEl   = section.querySelector('[data-wpd-error]');

    if (!form) return;

    var portalId  = form.getAttribute('data-hs-portal') || '';
    var formId    = form.getAttribute('data-hs-form')   || '';
    var pdfUrl    = section.getAttribute('data-pdf-url') || '';
    var origLabel = btnLabel ? btnLabel.textContent : '';

    var inputName  = form.querySelector('[name="full_name"]');
    var inputEmail = form.querySelector('[name="email"]');

    // ── Button enabled state ───────────────────────────────────────────────────
    // Enabled only when both fields have non-empty values.

    function updateButtonState() {
      var filled = inputName.value.trim() !== '' && inputEmail.value.trim() !== '';
      submitBtn.disabled = !filled;
    }

    [inputName, inputEmail].forEach(function (input) {
      input.addEventListener('input', function () {
        input.classList.remove('is-invalid');
        hideError();
        updateButtonState();
      });
    });

    // ── Validation ─────────────────────────────────────────────────────────────

    function isEmail(val) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    }

    function validate() {
      var errors = [];

      inputName.classList.remove('is-invalid');
      inputEmail.classList.remove('is-invalid');

      if (!inputName.value.trim()) {
        inputName.classList.add('is-invalid');
        errors.push('Please enter your full name.');
      }
      if (!inputEmail.value.trim() || !isEmail(inputEmail.value.trim())) {
        inputEmail.classList.add('is-invalid');
        errors.push('Please enter a valid business email.');
      }

      return errors;
    }

    // ── UI helpers ─────────────────────────────────────────────────────────────

    function setLoading(on) {
      submitBtn.classList.toggle('is-loading', on);
      submitBtn.disabled = on;
      if (btnLabel) btnLabel.textContent = on ? 'Sending…' : origLabel;
    }

    function showError(msg) {
      if (!errorEl) return;
      errorEl.textContent = msg;
      errorEl.hidden = false;
    }

    function hideError() {
      if (!errorEl) return;
      errorEl.hidden = true;
      errorEl.textContent = '';
    }

    function showSuccess() {
      form.hidden = true;
      if (success) success.hidden = false;
    }

    // ── Auto-download PDF ──────────────────────────────────────────────────────

    function triggerDownload(url) {
      if (!url) return;
      var a = document.createElement('a');
      a.href = url;
      a.download = '';
      a.style.display = 'none';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    // ── HubSpot Forms API submission ───────────────────────────────────────────

    function submitToHubspot(name, email) {
      if (!portalId || !formId) {
        // Dev/demo mode: no HubSpot credentials configured — skip API call
        return Promise.resolve({ success: true });
      }

      var endpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/'
                   + portalId + '/' + formId;

      var payload = {
        fields: [
          { name: 'firstname', value: name },
          { name: 'email',     value: email },
        ],
        context: {
          pageUri:  window.location.href,
          pageName: document.title,
        },
      };

      return fetch(endpoint, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(payload),
      }).then(function (r) {
        // HubSpot returns 200 on success; non-2xx = error
        if (!r.ok) return r.json().then(function (d) { return { success: false, message: d.message || 'HubSpot error.' }; });
        return { success: true };
      });
    }

    // ── Submit handler ─────────────────────────────────────────────────────────

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      hideError();

      var errors = validate();
      if (errors.length) {
        showError(errors[0]);
        return;
      }

      setLoading(true);

      var name  = inputName.value.trim();
      var email = inputEmail.value.trim();

      submitToHubspot(name, email)
        .then(function (res) {
          setLoading(false);
          if (res && res.success) {
            triggerDownload(pdfUrl); // auto-download PDF
            showSuccess();
          } else {
            showError(res.message || 'Something went wrong. Please try again.');
          }
        })
        .catch(function () {
          setLoading(false);
          showError('Network error. Please try again.');
        });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
