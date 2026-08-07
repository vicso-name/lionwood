/**
 * Choose Cases Grid — hybrid rendering
 *
 * Direct URL access  → server-side rendered (PHP, no JS involvement)
 * Pill click         → AJAX filter + history.pushState (no full page reload)
 * Browser back/fwd   → location.reload() (re-runs server-side render)
 * Load More          → AJAX pagination, always reads current state
 */

(function () {
  'use strict';

  var ajaxUrl    = (window.ccgAjax && window.ccgAjax.url)        || '/wp-admin/admin-ajax.php';
  var archiveUrl = (window.ccgAjax && window.ccgAjax.archiveUrl) || '/case-study/';

  function initSection(section) {
    var grid         = section.querySelector('[data-grid]');
    var loadmoreWrap = section.querySelector('[data-loadmore-wrap]');
    var loadmoreBtn  = section.querySelector('[data-loadmore]');
    var tabs         = Array.prototype.slice.call(section.querySelectorAll('.ccg-tab'));
    var pillsWraps   = Array.prototype.slice.call(section.querySelectorAll('.ccg-pills'));

    if (!grid) return;

    // Nonce: globally localized (always available) or from load-more button attribute
    var nonce   = (window.ccgAjax && window.ccgAjax.nonce)
                || (loadmoreBtn ? loadmoreBtn.getAttribute('data-nonce') : '');
    var perPage = parseInt(grid.getAttribute('data-per-page') || '6', 10);

    // State seeded from PHP-rendered DOM so server-side filtered pages
    // (direct URL access) are correctly reflected for subsequent Load More calls.
    var activeTermId = parseInt(grid.getAttribute('data-active-term-id') || '0', 10);

    var state = {
      offset:   parseInt(grid.getAttribute('data-offset') || '6', 10),
      total:    parseInt(grid.getAttribute('data-total')  || '0', 10),
      taxonomy: grid.getAttribute('data-active-taxonomy') || '',
      termIds:  activeTermId ? [activeTermId] : [],
    };

    // ── Helpers ───────────────────────────────────────────────────────────────

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
        data.append(k, Array.isArray(v) ? JSON.stringify(v) : v);
      });

      fetch(ajaxUrl, { method: 'POST', body: data })
        .then(function (r) { return r.json(); })
        .then(function (res) { if (res.success) callback(res.data); })
        .catch(function () { setLoading(false); });
    }

    // ── AJAX filter ───────────────────────────────────────────────────────────
    // Called on pill click. Replaces grid content without a page reload,
    // then pushes the pill's canonical URL into the browser history.

    function doFilter(taxonomy, termId, pushUrl) {
      setLoading(true);

      var termIds = termId ? [termId] : [];

      request({
        action_type: 'filter',
        taxonomy:    taxonomy,
        term_ids:    termIds,
      }, function (data) {
        grid.innerHTML = data.html || '';

        // Update state for subsequent Load More calls
        state.taxonomy = taxonomy;
        state.termIds  = termIds;
        state.offset   = data.offset;
        state.total    = data.total;

        // Mirror state onto DOM attributes
        grid.setAttribute('data-active-taxonomy', taxonomy);
        grid.setAttribute('data-active-term-id',  termId || '0');

        setLoading(false);
        updateLoadMore(data.has_more);

        if (pushUrl) {
          history.pushState(null, '', pushUrl);
        }
      });
    }

    // ── Pill active state ─────────────────────────────────────────────────────

    function setActivePill(activePill) {
      section.querySelectorAll('.ccg-pill').forEach(function (p) {
        p.classList.remove('is-active');
        p.removeAttribute('aria-current');
      });
      if (activePill) {
        activePill.classList.add('is-active');
        activePill.setAttribute('aria-current', 'page');
      }
    }

    // ── Pills — intercept href, run AJAX filter, pushState ───────────────────

    section.querySelectorAll('.ccg-pill').forEach(function (pill) {
      pill.addEventListener('click', function (e) {
        e.preventDefault();

        var termId   = parseInt(pill.getAttribute('data-term-id') || '0', 10);
        var taxonomy = pill.getAttribute('data-taxonomy') || '';
        var url      = pill.getAttribute('href') || archiveUrl;

        // Clicking the already-active pill resets to the unfiltered archive
        if (pill.classList.contains('is-active')) {
          setActivePill(null);
          doFilter('', 0, archiveUrl);
          return;
        }

        setActivePill(pill);
        doFilter(taxonomy, termId, url);
      });
    });

    // ── Tab switching — show/hide pill groups, no AJAX ───────────────────────

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');

        var activeGroup = tab.getAttribute('data-tab');
        pillsWraps.forEach(function (wrap) {
          wrap.classList.toggle('ccg-pills--hidden', wrap.getAttribute('data-pills') !== activeGroup);
        });
      });
    });

    // ── popstate — browser back / forward ────────────────────────────────────
    // Reload lets the server render the correct filtered state for the URL
    // the browser navigated back to — simpler and more reliable than
    // re-parsing location.pathname in JS.

    window.addEventListener('popstate', function () {
      location.reload();
    });

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
