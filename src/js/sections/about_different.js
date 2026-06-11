/**
 * About Different Slider — custom card stack
 * File: blocks/get-started/get-started.js
 *
 * Logic inspired by koby.dev card stack pattern.
 * Active card is on top, inactive cards are offset behind it.
 * Clicking next/prev rotates the stack.
 */

(function () {
  'use strict';

  function initAboutDifferent(section) {
    var wrapper   = section.querySelector('.adf-cards-wrapper');
    var indicator = section.querySelector('[data-adf-indicator]');
    var prevBtn   = section.querySelector('.adf-nav__btn--prev');
    var nextBtn   = section.querySelector('.adf-nav__btn--next');

    if (!wrapper) return;

    var cards = Array.prototype.slice.call(wrapper.querySelectorAll('.adf-slide'));
    if (!cards.length) return;

    var total = cards.length;

    // Stack offsets for inactive cards (index 0 = just behind active)
    var isMobile = window.innerWidth < 1280;
    var offsets = isMobile ? [
      { x: 20, y: -10, scale: 0.97, opacity: 0.9,  zIndex: 4 },
      { x: 40, y: -20, scale: 0.93, opacity: 0.8,  zIndex: 3 },
      { x: 55, y: -28, scale: 0.89, opacity: 0.6,  zIndex: 2 },
      { x: 66, y: -33, scale: 0.86, opacity: 0.4,  zIndex: 1 },
      { x: 74, y: -37, scale: 0.83, opacity: 0.25, zIndex: 0 },
    ] : [
      { x: 40,  y: -20, scale: 0.97, opacity: 0.9,  zIndex: 4 },
      { x: 80,  y: -40, scale: 0.93, opacity: 0.8,  zIndex: 3 },
      { x: 110, y: -55, scale: 0.89, opacity: 0.6,  zIndex: 2 },
      { x: 132, y: -66, scale: 0.86, opacity: 0.4,  zIndex: 1 },
      { x: 148, y: -74, scale: 0.83, opacity: 0.25, zIndex: 0 },
    ];

    // currentIndex = index of the active (front) card
    var currentIndex = 0;

    function applyStack() {
      cards.forEach(function (card, i) {
        // Position relative to active: 0 = active, 1 = one behind, etc.
        var pos = (i - currentIndex + total) % total;

        if (pos === 0) {
          // Active card
          card.style.transform  = 'translate(0, 0) scale(1)';
          card.style.opacity    = '1';
          card.style.zIndex     = '5';
          card.style.pointerEvents = 'auto';
          card.classList.add('is-active');
        } else {
          var off = offsets[Math.min(pos - 1, offsets.length - 1)];
          card.style.transform  = 'translate(' + off.x + 'px, ' + off.y + 'px) scale(' + off.scale + ')';
          card.style.opacity    = off.opacity;
          card.style.zIndex     = off.zIndex;
          card.style.pointerEvents = 'none';
          card.classList.remove('is-active');
        }
      });

      // Update indicator
      if (indicator) {
        var activeCard = cards[currentIndex];
        var title = activeCard ? (activeCard.getAttribute('data-slide-title') || '') : '';
        indicator.style.opacity = '0';
        setTimeout(function () {
          indicator.textContent = '[ ' + title + ' ]';
          indicator.style.opacity = '0.2';
        }, 150);
      }
    }

    function goNext() {
      currentIndex = (currentIndex + 1) % total;
      applyStack();
    }

    function goPrev() {
      currentIndex = (currentIndex - 1 + total) % total;
      applyStack();
    }

    if (nextBtn) nextBtn.addEventListener('click', goNext);
    if (prevBtn) prevBtn.addEventListener('click', goPrev);

    // Swipe support
    var touchStartX = 0;
    var touchStartY = 0;

    wrapper.addEventListener('touchstart', function (e) {
      touchStartX = e.touches[0].clientX;
      touchStartY = e.touches[0].clientY;
    }, { passive: true });

    wrapper.addEventListener('touchend', function (e) {
      var dx = e.changedTouches[0].clientX - touchStartX;
      var dy = e.changedTouches[0].clientY - touchStartY;
      // Ignore if primarily a vertical scroll
      if (Math.abs(dx) < 40 || Math.abs(dx) < Math.abs(dy)) return;
      if (dx < 0) goNext(); else goPrev();
    }, { passive: true });

    // Click on any inactive card to bring it forward
    cards.forEach(function (card) {
      card.addEventListener('click', function () {
        var idx = cards.indexOf(card);
        if (idx !== currentIndex) {
          currentIndex = idx;
          applyStack();
        }
      });
    });

    // Initial state
    applyStack();

    // Equalize all cards to the tallest one.
    // Deferred to document.fonts.ready so the real typeface is loaded before
    // we measure — otherwise a fallback font gives a shorter height and the
    // card tears when the custom font reflows the text.
    function syncHeights() {
      var maxH = 0;
      cards.forEach(function (card) {
        card.style.height = ''; // clear previous measurement
        var h = card.offsetHeight;
        if (h > maxH) maxH = h;
      });
      if (maxH > 0) {
        cards.forEach(function (card) { card.style.height = maxH + 'px'; });
        wrapper.style.height = maxH + 'px';
      }
    }

    (document.fonts ? document.fonts.ready : Promise.resolve()).then(function () {
      requestAnimationFrame(syncHeights);
    });
  }

  function init() {
    document.querySelectorAll('.adf-section').forEach(initAboutDifferent);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
