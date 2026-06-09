/**
 * Choose Cases Grid
 * - Tab switching (Industries / Services)
 * - Filter pills — multi-select AJAX filter
 * - Load More — AJAX pagination (respects active filters)
 *
 * File: blocks/choose-cases-grid/choose-cases-grid.js
 */

(function () {
  'use strict';

  var ajaxUrl = (window.ccgAjax && window.ccgAjax.url) || '/wp-admin/admin-ajax.php';

  function initSection(section) {
    var grid         = section.querySelector('[data-grid]');
    var loadmoreWrap = section.querySelector('[data-loadmore-wrap]');
    var loadmoreBtn  = section.querySelector('[data-loadmore]');
    var tabs         = Array.prototype.slice.call(section.querySelectorAll('.ccg-tab'));
    var pillsWraps   = Array.prototype.slice.call(section.querySelectorAll('.ccg-pills'));

    if (!grid) return;

    var nonce   = loadmoreBtn ? loadmoreBtn.getAttribute('data-nonce') : '';
    var perPage = parseInt(grid.getAttribute('data-per-page') || '6', 10);

    // State — termIds is now an array for multi-select
    var state = {
      offset:   parseInt(grid.getAttribute('data-offset') || '6', 10),
      total:    parseInt(grid.getAttribute('data-total')  || '0', 10),
      taxonomy: '',
      termIds:  [],   // array of selected term IDs
    };

    // ── Helpers ──────────────────────────────────────────────────────────────

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
      data.append('action',   'ccg_ajax');
      data.append('nonce',    nonce);
      data.append('per_page', perPage);
      Object.keys(params).forEach(function (k) {
        var v = params[k];
        // Send arrays as JSON string
        data.append(k, Array.isArray(v) ? JSON.stringify(v) : v);
      });

      fetch(ajaxUrl, { method: 'POST', body: data })
        .then(function (r) { return r.json(); })
        .then(function (res) { if (res.success) callback(res.data); })
        .catch(function () { setLoading(false); });
    }

    // ── Tab switching ─────────────────────────────────────────────────────────

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');

        var activeGroup = tab.getAttribute('data-tab');
        var taxonomy    = tab.getAttribute('data-taxonomy');

        pillsWraps.forEach(function (wrap) {
          wrap.classList.toggle('ccg-pills--hidden', wrap.getAttribute('data-pills') !== activeGroup);
        });

        // Reset all pills and filter state
        section.querySelectorAll('.ccg-pill').forEach(function (p) {
          p.classList.remove('is-active');
        });

        state.taxonomy = taxonomy || '';
        state.termIds  = [];
        doFilter();
      });
    });

    // ── Filter pills — multi-select ───────────────────────────────────────────

    section.querySelectorAll('.ccg-pill').forEach(function (pill) {
      pill.addEventListener('click', function () {
        var termId   = parseInt(pill.getAttribute('data-term-id') || '0', 10);
        var taxonomy = pill.getAttribute('data-taxonomy') || '';
        var isActive = pill.classList.contains('is-active');

        // Set taxonomy from the pill (all pills in same group share taxonomy)
        state.taxonomy = taxonomy;

        if (isActive) {
          // Deselect
          pill.classList.remove('is-active');
          state.termIds = state.termIds.filter(function (id) { return id !== termId; });
        } else {
          // Select
          pill.classList.add('is-active');
          if (state.termIds.indexOf(termId) === -1) {
            state.termIds.push(termId);
          }
        }

        // If no terms selected, clear taxonomy too
        if (state.termIds.length === 0) {
          state.taxonomy = '';
        }

        doFilter();
      });
    });

    // ── Filter: replace grid ──────────────────────────────────────────────────

    function doFilter() {
      setLoading(true);

      request({
        action_type: 'filter',
        taxonomy:    state.taxonomy,
        term_ids:    state.termIds,
      }, function (data) {
        grid.innerHTML = data.html || '';
        state.offset   = data.offset;
        state.total    = data.total;
        setLoading(false);
        updateLoadMore(data.has_more);
      });
    }

    // ── Load More ─────────────────────────────────────────────────────────────

    if (loadmoreBtn) {
      var origLabel = loadmoreBtn.textContent;

      loadmoreBtn.addEventListener('click', function () {
        if (loadmoreBtn.classList.contains('is-loading')) return;

        setLoading(true);
        loadmoreBtn.textContent = 'Loading...';

        request({
          action_type: 'load_more',
          taxonomy:    state.taxonomy,
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
  }

  function init() {
    document.querySelectorAll('.ccg-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
