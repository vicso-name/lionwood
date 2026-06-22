/**
 * Industry Associations — Load More
 * Shows 3 items initially, reveals 1 more per click.
 */

(function () {
  'use strict';

  function init() {
    var list  = document.getElementById('ina-list');
    var btn   = document.getElementById('ina-load-more');
    if (!list || !btn) return;

    var items = Array.prototype.slice.call(list.querySelectorAll('.ina-item'));
    var total = items.length;
    var shown = Math.min(3, total);

    btn.addEventListener('click', function () {
      if (shown >= total) return;
      items[shown].classList.remove('ina-item--hidden');
      shown++;
      var remaining = total - shown;
      if (remaining <= 0) {
        btn.style.display = 'none';
      } else {
        btn.textContent = 'Load more (+' + remaining + ')';
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
