/**
 * Single Deliver Solutions — mobile Swiper + numeric counter
 * File: blocks/single-deliver-solutions/single-deliver-solutions.js
 */

(function () {
  'use strict';

  function initAnimations(section) {
    if (!('IntersectionObserver' in window)) return;

    // ── Heading: fade + slide-up, bottom line delayed ──────────────────────
    var heading = section.querySelector('.sds-heading');
    if (heading) {
      heading.classList.add('sds-anim');
      new IntersectionObserver(function (entries, obs) {
        if (entries[0].isIntersecting) {
          heading.classList.add('is-visible');
          obs.unobserve(heading);
        }
      }, { threshold: 0.25 }).observe(heading);
    }

    // ── Cards: staggered fade + slide-up, desktop only ─────────────────────
    var grid = section.querySelector('.sds-grid');
    if (!grid || window.innerWidth <= 1023) return;

    var cards = grid.querySelectorAll('.sds-card');
    cards.forEach(function (card, i) {
      card.classList.add('sds-anim');
      card.style.transitionDelay = (i * 0.07) + 's';
    });

    new IntersectionObserver(function (entries, obs) {
      if (entries[0].isIntersecting) {
        cards.forEach(function (card) { card.classList.add('is-visible'); });
        obs.unobserve(grid);
      }
    }, { threshold: 0.05 }).observe(grid);
  }

  function initSection(section) {
    initAnimations(section);

    var swiperEl  = section.querySelector('.sds-swiper');
    var currentEl = section.querySelector('.sds-counter__current');

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
    document.querySelectorAll('.sds-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
