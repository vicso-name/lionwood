/**
 * Choose Service Grid — subservices toggle + AJAX load more
 * File: blocks/choose-service-grid/choose-service-grid.js
 */

(function () {
  'use strict';

  // ── Subservices toggle ────────────────────────────────────────────────────
  function initSubservices(card) {
    var toggle = card.querySelector('[data-subs-toggle]');
    var panel  = card.querySelector('[data-subs-panel]');
    var header = panel ? panel.querySelector('.csg-card__subs-panel-header') : null;

    if (!toggle || !panel) return;

    function openPanel() {
      panel.classList.add('is-open');
      panel.setAttribute('aria-hidden', 'false');
      toggle.setAttribute('aria-expanded', 'true');
    }

    function closePanel() {
      panel.classList.remove('is-open');
      panel.setAttribute('aria-hidden', 'true');
      toggle.setAttribute('aria-expanded', 'false');
    }

    // Plank opens panel
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      openPanel();
    });

    // × in panel header closes panel
    if (header) {
      header.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closePanel();
      });
    }
  }

  function initAllCards(container) {
    container.querySelectorAll('.csg-card--has-subs').forEach(initSubservices);
  }


  // ── AJAX Load More ────────────────────────────────────────────────────────
  function initLoadMore(section) {
    var btn  = section.querySelector('[data-loadmore]');
    var grid = section.querySelector('[data-grid]');
    if (!btn || !grid) return;

    var mode      = section.getAttribute('data-mode') || 'auto';
    var manualIds = section.getAttribute('data-manual-ids') || '';
    var perPage   = parseInt(section.getAttribute('data-per-page') || '3', 10);
    var offset    = parseInt(section.getAttribute('data-offset')   || '9', 10);
    var total     = parseInt(section.getAttribute('data-total')    || '0', 10);
    var nonce     = btn.getAttribute('data-nonce') || '';

    var btnOriginalText = btn.textContent;

    btn.addEventListener('click', function () {
      if (btn.classList.contains('is-loading')) return;
      btn.classList.add('is-loading');
      btn.textContent = 'Loading...';

      var data = new FormData();
      data.append('action',     'csg_load_more');
      data.append('nonce',      nonce);
      data.append('mode',       mode);
      data.append('manual_ids', manualIds);
      data.append('per_page',   perPage);
      data.append('offset',     offset);

      fetch(window.csgAjax ? window.csgAjax.url : '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data,
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          btn.classList.remove('is-loading');
          btn.textContent = btnOriginalText;

          if (!res.success || !res.data.html) return;

          // Inject new cards
          var tmp = document.createElement('div');
          tmp.innerHTML = res.data.html;
          var newCards = Array.prototype.slice.call(tmp.children);
          newCards.forEach(function (card) {
            grid.appendChild(card);
            initSubservices(card);
          });

          // Update offset
          offset += res.data.count;
          section.setAttribute('data-offset', offset);

          // Hide button if no more
          if (offset >= total) {
            btn.closest('.csg-loadmore-wrap').style.display = 'none';
          }
        })
        .catch(function () {
          btn.classList.remove('is-loading');
          btn.textContent = btnOriginalText;
        });
    });
  }


  // ── Init ──────────────────────────────────────────────────────────────────
  function init() {
    document.querySelectorAll('.csg-section').forEach(function (section) {
      initAllCards(section);
      initLoadMore(section);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
