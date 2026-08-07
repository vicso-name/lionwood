/**
 * Career Our Life — Swiper gallery + progress bar + mobile counter
 */

(function () {
    'use strict';

    function pad(n) {
        return n < 10 ? '0' + n : '' + n;
    }

    function initSection(section) {
        var sliderEl   = section.querySelector('[data-cl-slider]');
        var progressEl = section.querySelector('[data-cl-progress]');
        var prevBtn    = section.querySelector('[data-cl-prev]');
        var nextBtn    = section.querySelector('[data-cl-next]');
        var currentEl  = section.querySelector('[data-cl-current]');
        var totalEl    = section.querySelector('[data-cl-total]');

        if (!sliderEl || typeof Swiper === 'undefined') return;

        var swiper = new Swiper(sliderEl, {
            slidesPerView:  'auto',
            spaceBetween:   12,
            loop:           false,
            grabCursor:     true,
            centeredSlides: false,
        });

        // Wire nav buttons
        if (prevBtn) prevBtn.addEventListener('click', function () { swiper.slidePrev(); });
        if (nextBtn) nextBtn.addEventListener('click', function () { swiper.slideNext(); });

        function updateProgress() {
            var current = swiper.activeIndex + 1;

            if (progressEl) {
                // isBeginning/isEnd snap to exact 0/100 — swiper.progress can fall
                // slightly short of 1.0 at the end with slidesPerView:'auto'
                var pct = swiper.isEnd ? 100 : swiper.isBeginning ? 0 : swiper.progress * 100;
                progressEl.style.width = pct.toFixed(2) + '%';
            }

            // Counter (mobile)
            if (currentEl) currentEl.textContent = pad(current);

            // Button states
            if (prevBtn) prevBtn.disabled = swiper.isBeginning;
            if (nextBtn) nextBtn.disabled = swiper.isEnd;
        }

        // Set total once — it never changes
        if (totalEl) totalEl.textContent = pad(swiper.slides.length);

        swiper.on('slideChange', updateProgress);
        updateProgress(); // initial state — swiper is now assigned

        // Keyboard support
        section.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft')  swiper.slidePrev();
            if (e.key === 'ArrowRight') swiper.slideNext();
        });
    }

    function init() {
        document.querySelectorAll('.cl-section').forEach(initSection);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
