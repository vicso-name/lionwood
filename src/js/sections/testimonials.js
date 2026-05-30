/**
 * Testimonials Section — Swiper slider + accordion
 * File: blocks/testimonials-section/testimonials-section.js
 */

(function () {
  'use strict';

  // ── Slider ─────────────────────────────────────────────────────────────────
  function initTestimonialsSwiper() {
    var swiperEl = document.querySelector('.ts-swiper');
    if (!swiperEl || typeof Swiper === 'undefined') return;

    var section = swiperEl.closest('.ts-section__slider-wrap');
    var prevBtn = section ? section.querySelector('.ts-nav-btn--prev') : null;
    var nextBtn = section ? section.querySelector('.ts-nav-btn--next') : null;

    new Swiper('.ts-swiper', {
      slidesPerView: 'auto',
      spaceBetween: 24,
      grabCursor: true,
      loop: true,           // infinite loop
      speed: 600,           // smooth 600ms transition
      navigation: {
        prevEl: prevBtn,
        nextEl: nextBtn,
        disabledClass: 'swiper-button-disabled',
      },
      a11y: {
        prevSlideMessage: 'Previous testimonial',
        nextSlideMessage: 'Next testimonial',
      },
      breakpoints: {
        0:    { spaceBetween: 16 },
        1024: { spaceBetween: 24 },
      },
    });
  }

  // ── Accordion ──────────────────────────────────────────────────────────────
  // Uses transform-based slide-up — body is always in DOM (display:block),
  // visibility controlled by .is-open class + CSS transition.
  function initAccordions() {
    document.querySelectorAll('.ts-card__accordion-trigger').forEach(function (btn) {
      var bodyId = btn.getAttribute('aria-controls');
      var body   = document.getElementById(bodyId);
      if (!body) return;

      // Remove the HTML [hidden] attr — CSS transform handles visibility
      body.removeAttribute('hidden');

      btn.addEventListener('click', function () {
        var isOpen = btn.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
          body.classList.remove('is-open');
          btn.setAttribute('aria-expanded', 'false');
        } else {
          body.classList.add('is-open');
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });
  }

  // ── Init ───────────────────────────────────────────────────────────────────
  function init() {
    initTestimonialsSwiper();
    initAccordions();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
