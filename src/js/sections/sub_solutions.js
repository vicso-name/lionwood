/**
 * Sub Solutions — mobile Swiper + numeric counter
 */

(function () {
  'use strict';

  function initSection(section) {
    var swiperEl  = section.querySelector('.ssol-swiper');
    var currentEl = section.querySelector('.ssol-counter__current');

    if (!swiperEl || typeof Swiper === 'undefined') return;

    new Swiper(swiperEl, {
      slidesPerView:  'auto',
      spaceBetween:   16,
      grabCursor:     true,
      speed:          400,
      on: {
        slideChange: function () {
          if (currentEl) {
            currentEl.textContent = String(this.realIndex + 1).padStart(2, '0');
          }
        },
      },
    });
  }

  function init() {
    document.querySelectorAll('.ssol-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
