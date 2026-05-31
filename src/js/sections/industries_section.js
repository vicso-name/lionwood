/**
 * Industries Section — slider + progress circle
 *
 * Navigation:
 *   - Thumbnail click → go to that slide
 *   - Arrow keys on the section → previous / next
 *   - Auto-advance every AUTO_INTERVAL ms (pauses on focus for accessibility)
 *
 * NOTE: For true sticky scroll-driven behaviour (each industry = one scroll step),
 * wrap .ind-section in a tall div (height: N * 100vh) and set
 * .ind-section { position: sticky; top: 0; } — then drive activeIndex from
 * scroll position. Currently uses click + optional auto-advance.
 */

(function () {
  'use strict';

  function initSection(section) {
    var slides    = Array.prototype.slice.call(section.querySelectorAll('.ind-slide'));
    var thumbs    = Array.prototype.slice.call(section.querySelectorAll('.ind-thumb'));
    var fillEl    = section.querySelector('.ind-progress__fill');
    var currentEl = section.querySelector('[data-progress-current]');
    var total     = parseInt(section.getAttribute('data-total'), 10) || slides.length;

    if (!slides.length || !total) return;

    // Precompute circumference only when the SVG circle element is present.
    var circum = fillEl ? 2 * Math.PI * parseFloat(fillEl.getAttribute('r')) : 0;

    var activeIndex = 0;

    // ── Update UI to reflect activeIndex ──────────────────────────────────────
    function update() {
      slides.forEach(function (slide, i) {
        var active = i === activeIndex;
        slide.classList.toggle('is-active', active);
        slide.setAttribute('aria-hidden', active ? 'false' : 'true');
      });

      thumbs.forEach(function (thumb, i) {
        var active = i === activeIndex;
        thumb.classList.toggle('is-active', active);
        thumb.setAttribute('aria-selected', active ? 'true' : 'false');
      });

      if (fillEl) {
        var offset = circum * (1 - (activeIndex + 1) / total);

        if (activeIndex === 0) {
          // Reset to empty instantly, then animate to first-slide fill.
          fillEl.style.transition = 'none';
          fillEl.style.strokeDashoffset = circum.toFixed(2);
          void fillEl.getBoundingClientRect(); // force reflow
          fillEl.style.transition = 'stroke-dashoffset 0.4s ease';
        }

        fillEl.style.strokeDashoffset = offset.toFixed(2);
      }

      if (currentEl) {
        currentEl.textContent = activeIndex + 1;
      }
    }

    function goTo(index) {
      if (index === activeIndex) return;
      activeIndex = index;
      update();
    }

    // ── Thumbnail clicks — navigate and restart the auto-advance timer ────────
    thumbs.forEach(function (thumb) {
      thumb.addEventListener('click', function () {
        goTo(parseInt(thumb.getAttribute('data-thumb-index'), 10));
        startAuto();
      });
    });

    // ── Keyboard navigation ───────────────────────────────────────────────────
    section.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
        goTo(Math.min(activeIndex + 1, total - 1));
      }
      if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
        goTo(Math.max(activeIndex - 1, 0));
      }
    });

    // ── Auto-advance ──────────────────────────────────────────────────────────
    var AUTO_INTERVAL = 4000;
    var autoTimer     = null;

    function startAuto() {
      stopAuto();
      autoTimer = setInterval(function () {
        goTo((activeIndex + 1) % total);
      }, AUTO_INTERVAL);
    }

    function stopAuto() {
      if (autoTimer) { clearInterval(autoTimer); autoTimer = null; }
    }

    // Pause on focus (accessibility), resume on focusout.
    section.addEventListener('focusin',  stopAuto);
    section.addEventListener('focusout', startAuto);

    // ── Initial state ─────────────────────────────────────────────────────────
    update();
    startAuto();

    /*
     * ── STICKY SCROLL (future enhancement) ───────────────────────────────────
     * To enable scroll-driven navigation:
     *
     * 1. In SCSS add:
     *    .ind-section-sticky-wrap { height: calc(var(--ind-total) * 100vh); }
     *    .ind-section { position: sticky; top: 0; min-height: 100vh; }
     *
     * 2. In PHP wrap <section class="ind-section"> with:
     *    <div class="ind-section-sticky-wrap" style="--ind-total: N;">...</div>
     *
     * 3. Uncomment the scroll handler below:
     *
     * var wrap = section.closest('.ind-section-sticky-wrap');
     * if (wrap) {
     *   window.addEventListener('scroll', function () {
     *     var rect     = wrap.getBoundingClientRect();
     *     var progress = (-rect.top) / (rect.height - window.innerHeight);
     *     var index    = Math.min(Math.floor(progress * total), total - 1);
     *     if (index >= 0) goTo(index);
     *   }, { passive: true });
     * }
     */
  }

  function init() {
    document.querySelectorAll('.ind-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
