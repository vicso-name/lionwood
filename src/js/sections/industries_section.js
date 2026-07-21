/**
 * Industries Section
 *
 * Desktop (>=1024px): full-screen, scroll-driven. GSAP ScrollTrigger pins
 * the section for `total * 100vh` of scroll and scrubs the active industry
 * from scroll progress. Thumbnail clicks / arrow keys still work and also
 * move the page's scroll position to match, so manual navigation stays in
 * sync with the next scroll-driven update instead of being overridden by it.
 *
 * Mobile (<1024px): unchanged — thumbnail click, arrow keys, and a 4s
 * auto-advance timer that pauses on focus. No scroll-jacking.
 *
 * Reloads on resize across the 1024px breakpoint (same approach as
 * solution_timeline.js) since tearing down/rebuilding the ScrollTrigger
 * pin vs. the plain mobile handlers isn't worth the complexity.
 */

(function () {
  'use strict';

  var DESKTOP_BREAKPOINT = 1024;

  function isMobile() {
    return window.innerWidth < DESKTOP_BREAKPOINT;
  }

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

    // ── Desktop: GSAP ScrollTrigger pin + scrub ───────────────────────────────
    function initDesktop() {
      if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        // Graceful fallback — no scroll-jacking available, but manual nav
        // (thumbnails/arrow keys) still works without a scroll sync target.
        initManualNav(null);
        update();
        return;
      }

      gsap.registerPlugin(ScrollTrigger);

      var st = ScrollTrigger.create({
        trigger: section,
        start:   'top top',
        end:     function () { return '+=' + (total * window.innerHeight); },
        pin:     true,
        scrub:   true,
        onUpdate: function (self) {
          var index = Math.min(Math.floor(self.progress * total), total - 1);
          goTo(index);
        },
      });

      initManualNav(st);
      update();
    }

    // Thumbnail clicks + arrow keys. On desktop with an active ScrollTrigger,
    // also scrolls the page to the matching position so the choice isn't
    // immediately overridden by the next scroll-driven onUpdate.
    function initManualNav(st) {
      function goToAndScroll(index) {
        index = Math.max(0, Math.min(index, total - 1));
        goTo(index);

        if (st) {
          var progress = index / total;
          var y = st.start + progress * (st.end - st.start);
          // +2px nudge past the exact boundary so the scrub's floor() math
          // lands back on this same index, not the previous one.
          window.scrollTo({ top: y + 2, behavior: 'smooth' });
        }
      }

      thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
          goToAndScroll(parseInt(thumb.getAttribute('data-thumb-index'), 10));
        });
      });

      section.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
          goToAndScroll(activeIndex + 1);
        }
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
          goToAndScroll(activeIndex - 1);
        }
      });
    }

    // ── Mobile: click nav + auto-advance (unchanged from previous behaviour) ──
    function initMobile() {
      thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
          goTo(parseInt(thumb.getAttribute('data-thumb-index'), 10));
          startAuto();
        });
      });

      section.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
          goTo(Math.min(activeIndex + 1, total - 1));
        }
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
          goTo(Math.max(activeIndex - 1, 0));
        }
      });

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

      update();
      startAuto();
    }

    if (isMobile()) {
      initMobile();
    } else {
      initDesktop();
    }
  }

  function init() {
    document.querySelectorAll('.ind-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Reload when crossing the mobile/desktop breakpoint — mirrors
  // solution_timeline.js, since desktop uses a GSAP ScrollTrigger pin and
  // mobile doesn't; tearing down/rebuilding in place isn't worth it.
  var wasMob = isMobile();
  var bpTimer;
  window.addEventListener('resize', function () {
    clearTimeout(bpTimer);
    bpTimer = setTimeout(function () {
      var nowMob = isMobile();
      if (nowMob !== wasMob) {
        wasMob = nowMob;
        window.location.reload();
      }
    }, 300);
  }, { passive: true });

})();
