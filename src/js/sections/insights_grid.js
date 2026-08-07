/**
 * Insights Grid
 *
 * Tabs   → switch post type, show correct pill group, AJAX reload grid
 * Pills  → filter by taxonomy term, AJAX reload, pushState URL
 * Load More → AJAX append, respects active type + term
 * popstate   → location.reload() for back/forward
 *
 * URL state: ?type=news&cat=5
 */

(function () {
  'use strict';

  function init() {
    var section     = document.querySelector('[data-ig-section]');
    if (!section) return;

    var ajaxUrl = section.getAttribute('data-ajax-url')
               || (window.igAjax && window.igAjax.url)
               || '/wp-admin/admin-ajax.php';
    var nonce   = section.getAttribute('data-nonce')
               || (window.igAjax && window.igAjax.nonce)
               || '';

    var grid        = section.querySelector('[data-ig-grid]');
    var loadmoreWrap = section.querySelector('[data-ig-loadmore-wrap]');
    var loadmoreBtn  = section.querySelector('[data-ig-loadmore]');
    var tabs         = Array.prototype.slice.call(section.querySelectorAll('.ccg-tab'));
    var pillsWraps   = Array.prototype.slice.call(section.querySelectorAll('.ccg-pills'));

    if (!grid) return;

    var perPage = parseInt(grid.getAttribute('data-per-page') || '6', 10);

    var state = {
      type:    grid.getAttribute('data-type')          || 'articles',
      termIds: (function () {
        var id = parseInt(grid.getAttribute('data-active-term-id') || '0', 10);
        return id ? [id] : [];
      })(),
      offset:  parseInt(grid.getAttribute('data-offset') || '6', 10),
      total:   parseInt(grid.getAttribute('data-total')  || '0', 10),
    };

    // ── Helpers ────────────────────────────────────────────────────────────────

    function setLoading(on) {
      grid.classList.toggle('is-loading', on);
      if (loadmoreBtn) loadmoreBtn.classList.toggle('is-loading', on);
    }

    function updateLoadMore(hasMore) {
      if (!loadmoreWrap) return;
      loadmoreWrap.style.display = hasMore ? '' : 'none';
    }

    function request(params, callback) {
      var data = new FormData();
      data.append('action',   'ig_ajax');
      data.append('nonce',    nonce);
      data.append('per_page', perPage);
      Object.keys(params).forEach(function (k) {
        var v = params[k];
        data.append(k, Array.isArray(v) ? JSON.stringify(v) : v);
      });
      fetch(ajaxUrl, { method: 'POST', body: data })
        .then(function (r) { return r.json(); })
        .then(function (res) { if (res.success) callback(res.data); })
        .catch(function () { setLoading(false); });
    }

    function buildUrl(type, catId) {
      var params = new URLSearchParams(window.location.search);
      params.set('type', type);
      if (catId) {
        params.set('cat', catId);
      } else {
        params.delete('cat');
      }
      return window.location.pathname + '?' + params.toString();
    }

    // ── AJAX filter (replace grid) ─────────────────────────────────────────────

    function doFilter(type, termIds, pushUrl) {
      setLoading(true);

      request({
        action_type: 'filter',
        type:        type,
        term_ids:    termIds,
      }, function (data) {
        grid.innerHTML = data.html || '';

        state.type    = type;
        state.termIds = termIds;
        state.offset  = data.offset;
        state.total   = data.total;

        grid.setAttribute('data-type',           type);
        grid.setAttribute('data-active-term-id', termIds.length ? termIds[0] : '0');

        setLoading(false);
        updateLoadMore(data.has_more);

        if (pushUrl) {
          history.pushState(null, '', pushUrl);
        }
      });
    }

    // ── Active pill helper ─────────────────────────────────────────────────────

    function setActivePill(activePill) {
      section.querySelectorAll('.ccg-pill').forEach(function (p) {
        p.classList.remove('is-active');
        p.removeAttribute('aria-current');
      });
      if (activePill) {
        activePill.classList.add('is-active');
        activePill.setAttribute('aria-current', 'true');
      }
    }

    // ── Tab clicks ─────────────────────────────────────────────────────────────

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var type = tab.getAttribute('data-tab');
        if (type === state.type) return;

        // Update tab UI
        tabs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');

        // Show correct pill group
        pillsWraps.forEach(function (wrap) {
          wrap.classList.toggle('ccg-pills--hidden', wrap.getAttribute('data-pills') !== type);
        });

        // Activate "Explore All" pill for the new tab, reset term filter
        setActivePill(section.querySelector('.ccg-pill--all[data-type="' + type + '"]'));

        doFilter(type, [], buildUrl(type, 0));
      });
    });

    // ── Pill clicks ────────────────────────────────────────────────────────────

    section.querySelectorAll('.ccg-pill').forEach(function (pill) {
      pill.addEventListener('click', function (e) {
        e.preventDefault();

        var termId = parseInt(pill.getAttribute('data-term-id') || '0', 10);
        var type   = pill.getAttribute('data-type') || state.type;

        // "Explore All" pill — always active, clears the term filter
        if (!termId) {
          if (pill.classList.contains('is-active')) return;
          setActivePill(pill);
          doFilter(type, [], buildUrl(type, 0));
          return;
        }

        // Toggle off if already active → falls back to "Explore All"
        if (pill.classList.contains('is-active')) {
          setActivePill(section.querySelector('.ccg-pill--all[data-type="' + type + '"]'));
          doFilter(type, [], buildUrl(type, 0));
          return;
        }

        setActivePill(pill);
        doFilter(type, [termId], buildUrl(type, termId));
      });
    });

    // ── Load More ──────────────────────────────────────────────────────────────

    if (loadmoreBtn) {
      var origLabel = loadmoreBtn.textContent;

      loadmoreBtn.addEventListener('click', function () {
        if (loadmoreBtn.classList.contains('is-loading')) return;

        setLoading(true);
        loadmoreBtn.textContent = 'Loading...';

        request({
          action_type: 'load_more',
          type:        state.type,
          term_ids:    state.termIds,
          offset:      state.offset,
        }, function (data) {
          if (data.html) {
            var tmp = document.createElement('div');
            tmp.innerHTML = data.html;
            Array.prototype.slice.call(tmp.children).forEach(function (card) {
              grid.appendChild(card);
            });
          }
          state.offset = data.offset;
          state.total  = data.total;
          setLoading(false);
          loadmoreBtn.textContent = origLabel;
          updateLoadMore(data.has_more);
        });
      });
    }

    // ── popstate — browser back/forward ───────────────────────────────────────
    window.addEventListener('popstate', function () {
      location.reload();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
