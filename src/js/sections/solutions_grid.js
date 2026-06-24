(function () {
    'use strict';

    var section = document.querySelector('[data-sg-section]');
    if (!section) return;

    var grid         = section.querySelector('[data-sg-grid]');
    var loadmoreWrap = section.querySelector('[data-sg-loadmore-wrap]');
    var loadmoreBtn  = section.querySelector('[data-sg-loadmore]');
    var pills        = Array.prototype.slice.call(section.querySelectorAll('[data-sg-pills] .ccg-pill'));

    if (!grid) return;

    var ajaxUrl = section.getAttribute('data-ajax-url') || '/wp-admin/admin-ajax.php';
    var nonce   = section.getAttribute('data-nonce')    || '';
    var perPage = parseInt(grid.getAttribute('data-per-page') || '6', 10);

    var state = {
        offset:  parseInt(grid.getAttribute('data-offset') || '6', 10),
        total:   parseInt(grid.getAttribute('data-total')  || '0', 10),
        termIds: (function () {
            var tid = parseInt(grid.getAttribute('data-active-term-id') || '0', 10);
            return tid ? [tid] : [];
        }()),
    };

    // ── Helpers ───────────────────────────────────────────────────────────────

    function setLoading(on) {
        grid.classList.toggle('is-loading', on);
        if (loadmoreBtn) loadmoreBtn.disabled = on;
    }

    function updateLoadMore(hasMore) {
        if (!loadmoreWrap) return;
        loadmoreWrap.style.display = hasMore ? '' : 'none';
    }

    function request(params, callback) {
        var data = new FormData();
        data.append('action', 'sg_ajax');
        data.append('nonce',  nonce);
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

    // ── Pill click: filter ────────────────────────────────────────────────────

    pills.forEach(function (pill) {
        pill.addEventListener('click', function (e) {
            e.preventDefault();

            var termId = parseInt(pill.getAttribute('data-term-id') || '0', 10);
            var isActive = pill.classList.contains('is-active');

            // Toggle: click active pill → clear filter
            if (isActive) {
                termId = 0;
            }

            // Update pill UI
            pills.forEach(function (p) {
                p.classList.remove('is-active');
                p.removeAttribute('aria-current');
            });
            if (termId) {
                pill.classList.add('is-active');
                pill.setAttribute('aria-current', 'true');
            }

            state.termIds = termId ? [termId] : [];
            setLoading(true);

            request({
                action_type: 'filter',
                term_ids:    state.termIds,
                offset:      0,
            }, function (data) {
                grid.innerHTML = data.html;
                state.offset   = data.offset;
                state.total    = data.total;
                updateLoadMore(data.has_more);
                setLoading(false);
            });
        });
    });

    // ── Load More ─────────────────────────────────────────────────────────────

    if (loadmoreBtn) {
        loadmoreBtn.addEventListener('click', function () {
            setLoading(true);
            request({
                action_type: 'load_more',
                term_ids:    state.termIds,
                offset:      state.offset,
            }, function (data) {
                grid.insertAdjacentHTML('beforeend', data.html);
                state.offset = data.offset;
                state.total  = data.total;
                updateLoadMore(data.has_more);
                setLoading(false);
            });
        });
    }

}());
