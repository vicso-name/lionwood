/**
 * Business Impact — mobile Swiper + numeric counter
 * File: blocks/business-impact/business-impact.js
 */

(function () {
  'use strict';

  function initSection(section) {
    var swiperEl  = section.querySelector('.bi-swiper');
    var currentEl = section.querySelector('.bi-counter__current');

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
    document.querySelectorAll('.bi-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
