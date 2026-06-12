/**
 * Career Grid — Load More
 * File: blocks/career-grid/career-grid.js
 *
 * All cards are rendered in HTML.
 * Cards beyond index 5 have .cg-card--hidden (display:none).
 * Load More reveals the next batch and hides the button when all shown.
 */

(function () {
    'use strict';

    var PER_PAGE = 6; // cards shown initially and per click

    function initGrid(section) {
        var grid    = section.querySelector('[data-cg-grid]');
        var btn     = section.querySelector('[data-cg-more]');

        if (!grid || !btn) return;

        var perPage = parseInt(btn.getAttribute('data-per-page'), 10) || PER_PAGE;
        var shown   = perPage; // already visible on load

        btn.addEventListener('click', function () {
            var hidden = grid.querySelectorAll('.cg-card--hidden');
            var toShow = Array.prototype.slice.call(hidden, 0, 3);

            toShow.forEach(function (card) {
                card.classList.remove('cg-card--hidden');
            });

            shown += toShow.length;

            // Hide button if all cards are now visible
            var remaining = grid.querySelectorAll('.cg-card--hidden').length;
            if (remaining === 0) {
                btn.parentElement.hidden = true;
            }
        });
    }

    function init() {
        document.querySelectorAll('.cg-section').forEach(initGrid);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
