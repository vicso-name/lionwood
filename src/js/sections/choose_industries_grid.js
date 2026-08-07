/**
 * Choose Industries Grid — AJAX load more
 */

(function () {
  'use strict';

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
      data.append('action',     'cig_load_more');
      data.append('nonce',      nonce);
      data.append('mode',       mode);
      data.append('manual_ids', manualIds);
      data.append('per_page',   perPage);
      data.append('offset',     offset);

      fetch(window.cigAjax ? window.cigAjax.url : '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data,
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          btn.classList.remove('is-loading');
          btn.textContent = btnOriginalText;

          if (!res.success || !res.data.html) return;

          var tmp = document.createElement('div');
          tmp.innerHTML = res.data.html;
          Array.prototype.slice.call(tmp.children).forEach(function (card) {
            grid.appendChild(card);
          });

          offset += res.data.count;
          section.setAttribute('data-offset', offset);

          if (offset >= total) {
            btn.closest('.cig-loadmore-wrap').style.display = 'none';
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
    document.querySelectorAll('.cig-section').forEach(function (section) {
      initLoadMore(section);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
