/**
 * Case Core Capabilities
 * - Accordion open/close with max-height animation
 * - Left panel syncs features list when accordion item changes
 * - Smooth cross-fade animation on panel content change
 *
 * File: blocks/case-core-capabilities/case-core-capabilities.js
 */

(function () {
  'use strict';

  var CHECK_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">'
    + '<path d="M12.0784 3.67079C12.1878 3.78018 12.2492 3.92853 12.2492 4.08321C12.2492 4.23789 12.1878 4.38623 12.0784 4.49563L6.24509 10.329C6.1357 10.4383 5.98736 10.4998 5.83268 10.4998C5.678 10.4998 5.52965 10.4383 5.42026 10.329L2.50359 7.41229C2.39733 7.30227 2.33854 7.15492 2.33987 7.00197C2.34119 6.84903 2.40254 6.70272 2.5107 6.59456C2.61885 6.48641 2.76516 6.42506 2.91811 6.42373C3.07106 6.4224 3.21841 6.4812 3.32843 6.58746L5.83268 9.09171L11.2536 3.67079C11.363 3.56143 11.5113 3.5 11.666 3.5C11.8207 3.5 11.969 3.56143 12.0784 3.67079Z" fill="#F7F7F7"/>'
    + '</svg>';

  function initSection(section) {
    var accordion  = section.querySelector('[data-ccc-accordion]');
    var panelList  = section.querySelector('[data-panel-list]');
    var items      = Array.prototype.slice.call(section.querySelectorAll('[data-ccc-item]'));
    var itemsData  = JSON.parse(section.getAttribute('data-ccc-items') || '[]');

    if (!accordion || !panelList || !items.length) return;

    var activeIndex = 0;

    // ── Render panel features for given index ─────────────────────────────
    function renderPanel(index) {
      var features = (itemsData[index] && itemsData[index].features) || [];

      // Fade out
      panelList.style.opacity = '0';
      panelList.style.transform = 'translateY(8px)';
      panelList.style.transition = 'opacity 0.2s ease, transform 0.2s ease';

      setTimeout(function () {
        // Build new content
        var html = '';
        features.forEach(function (text) {
          html += '<li class="ccc-panel__item">'
            + '<span class="ccc-panel__icon">' + CHECK_SVG + '</span>'
            + '<span class="ccc-panel__text">' + escapeHtml(text) + '</span>'
            + '</li>';
        });
        panelList.innerHTML = html;

        // Fade in
        panelList.style.opacity = '1';
        panelList.style.transform = 'translateY(0)';
      }, 200);
    }

    // ── Open / close accordion item ───────────────────────────────────────
    function openItem(item) {
      var body    = item.querySelector('.ccc-item__body');
      var trigger = item.querySelector('.ccc-item__trigger');

      item.classList.add('is-active');
      trigger.setAttribute('aria-expanded', 'true');
      body.setAttribute('aria-hidden', 'false');
      body.classList.add('is-open');
      body.style.maxHeight = body.scrollHeight + 'px';

      // Set to none after transition so content can grow
      body.addEventListener('transitionend', function onEnd(e) {
        if (e.propertyName !== 'max-height') return;
        if (item.classList.contains('is-active')) {
          body.style.maxHeight = 'none';
        }
        body.removeEventListener('transitionend', onEnd);
      });
    }

    function closeItem(item) {
      var body    = item.querySelector('.ccc-item__body');
      var trigger = item.querySelector('.ccc-item__trigger');

      // Set explicit height before animating to 0
      body.style.maxHeight = body.scrollHeight + 'px';
      body.offsetHeight; // force reflow

      item.classList.remove('is-active');
      trigger.setAttribute('aria-expanded', 'false');
      body.setAttribute('aria-hidden', 'true');
      body.classList.remove('is-open');
      body.style.maxHeight = '0';
    }

    // ── Init first item open ──────────────────────────────────────────────
    if (items[0]) {
      var firstBody = items[0].querySelector('.ccc-item__body');
      if (firstBody) {
        firstBody.classList.add('is-open');
        firstBody.style.maxHeight = 'none';
      }
    }

    // ── Click handlers ────────────────────────────────────────────────────
    items.forEach(function (item) {
      var trigger = item.querySelector('.ccc-item__trigger');
      if (!trigger) return;

      trigger.addEventListener('click', function () {
        var index   = parseInt(item.getAttribute('data-index') || '0', 10);
        var isActive = item.classList.contains('is-active');

        if (isActive) return; // already open — don't close (always one open)

        // Close current
        if (items[activeIndex]) closeItem(items[activeIndex]);

        // Open new
        openItem(item);
        activeIndex = index;

        // Sync left panel
        renderPanel(index);
      });

      // Keyboard: Escape
      trigger.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') trigger.blur();
      });
    });
  }

  function escapeHtml(str) {
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function init() {
    document.querySelectorAll('.ccc-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
