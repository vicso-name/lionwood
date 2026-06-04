/**
 * Single Service Definition — mobile Swiper + numeric counter
 * File: blocks/single-service-definition/single-service-definition.js
 */

(function () {
  'use strict';

  function initSection(section) {
    var swiperEl  = section.querySelector('.ssd-swiper');
    var currentEl = section.querySelector('.ssd-counter__current');

    if (!swiperEl || typeof Swiper === 'undefined') return;

    var swiper = new Swiper(swiperEl, {
      slidesPerView:  'auto',
      spaceBetween:   16,
      grabCursor:     true,
      speed:          400,
      on: {
        slideChange: function () {
          if (currentEl) {
            var idx = this.realIndex + 1;
            currentEl.textContent = String(idx).padStart(2, '0');
          }
        },
      },
    });
  }

  function init() {
    document.querySelectorAll('.ssd-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
