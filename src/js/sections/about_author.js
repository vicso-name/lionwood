(function () {
    'use strict';

    var LS_PREFIX = 'aa_rated_';

    function formatCount(count, average) {
        return count + ' ratings, average: ' + average.toFixed(1) + ' out of 5';
    }

    function setActiveStars(stars, n) {
        stars.forEach(function (btn, i) {
            btn.classList.toggle('is-active', i < n);
        });
    }

    function initRating(widget) {
        var postId  = widget.dataset.postId;
        var restUrl = widget.dataset.restUrl;
        var nonce   = widget.dataset.nonce;
        var lsKey   = LS_PREFIX + postId;

        var starBtns = Array.prototype.slice.call( widget.querySelectorAll('.aa-star') );
        var countEl  = widget.querySelector('.aa-rating__count');
        var starsWrap = widget.querySelector('.aa-rating__stars');

        if (!starBtns.length) return;

        // Already voted — restore state from localStorage, lock widget
        var savedVote = localStorage.getItem(lsKey);
        if (savedVote) {
            setActiveStars(starBtns, parseInt(savedVote, 10));
            widget.classList.add('is-voted');
            return;
        }

        // ── Hover ──────────────────────────────────────────────────────────────
        starBtns.forEach(function (btn, i) {
            btn.addEventListener('mouseenter', function () {
                if (widget.classList.contains('is-voted')) return;
                setActiveStars(starBtns, i + 1);
            });
        });

        starsWrap.addEventListener('mouseleave', function () {
            if (widget.classList.contains('is-voted')) return;
            setActiveStars(starBtns, 0);
        });

        // ── Click / submit ──────────────────────────────────────────────────────
        starBtns.forEach(function (btn, i) {
            btn.addEventListener('click', function () {
                if (widget.classList.contains('is-voted')) return;

                var starValue = i + 1;

                // Optimistic UI — lock immediately
                widget.classList.add('is-voted');
                setActiveStars(starBtns, starValue);

                fetch(restUrl, {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce':   nonce,
                    },
                    body: JSON.stringify({
                        post_id: parseInt(postId, 10),
                        stars:   starValue,
                    }),
                })
                .then(function (res) {
                    if (res.status === 429) {
                        // Server says already voted — lock permanently without rollback
                        localStorage.setItem(lsKey, starValue);
                        return null;
                    }
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(function (data) {
                    if (!data) return;
                    localStorage.setItem(lsKey, starValue);
                    if (countEl && data.count && data.average !== undefined) {
                        countEl.textContent = formatCount(data.count, data.average);
                    }
                })
                .catch(function (err) {
                    // Roll back on network/server error
                    widget.classList.remove('is-voted');
                    setActiveStars(starBtns, 0);
                    console.error('Rating submit failed:', err);
                });
            });
        });
    }

    function init() {
        var widgets = document.querySelectorAll('.aa-rating[data-post-id]');
        widgets.forEach(initRating);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
