/**
 * Solutions Section — pinned, center-focused card carousel (desktop),
 * scrollspy fallback (mobile / no GSAP)
 * File: blocks/solutions-section/solutions-section.js
 *
 * Client ask: at normal scroll speed, the right column could blow past the
 * whole card list so fast that the first and last cards barely
 * registered. Pinning the section (GSAP ScrollTrigger, pin: true) fixes
 * that — the section holds still for a fixed scroll distance per card, so
 * however fast the user's actual scroll gesture is, the same amount of
 * extra scrolling is required to get through every card. The client then
 * asked specifically for a carousel feel: each card scrolls into the
 * center of the window in turn, and as soon as the last card has had its
 * turn, the pin releases and the page continues scrolling normally.
 *
 * Desktop with GSAP available: pins .sol-section and scrub-translates
 * .sol-cards inside a clipped window (.sol-cards-viewport), keeping
 * whichever card is "current" centered in that window. The pin distance,
 * per-card scroll step, and card-to-card spacing are all fixed constants
 * (SLOT/STEP_PER_CARD below) — nothing here is measured from the live DOM
 * (no cardsWrap.scrollHeight, no offsetTop). That's deliberate: earlier
 * versions measured the rendered list height and both a slow font swap
 * (fallback font -> Formular, different line-height/wrapping) and normal
 * scrollHeight timing could make the list taller than what was measured,
 * ending the pin before the last card(s) had actually scrolled into view.
 * .sol-card now has a fixed height with clamped title/desc (see
 * solutions_section.scss) specifically so this math can be constants
 * instead of measurements and still always match what's on screen.
 *
 * Mobile, or desktop without GSAP: falls back to the previous scrollspy
 * behavior — a card stays active until its own top edge scrolls up past
 * the sticky header line, recomputed fresh on every scroll frame
 * (rAF-throttled, gated by a section-level IntersectionObserver) so it
 * can't get stuck dimmed on a fast scroll.
 */

(function () {
  'use strict';

  var DESKTOP_BREAKPOINT = 1024;

  // .hdr__inner's height in _header.scss (72px) plus a bit of early lead —
  // only used by the non-pinned scrollspy fallback below.
  var HEADER_OFFSET = 180;

  function isMobile() {
    return window.innerWidth < DESKTOP_BREAKPOINT;
  }

  function initSection(section) {
    var cardsWrap  = section.querySelector('.sol-cards');
    var viewportEl = section.querySelector('.sol-cards-viewport');
    var cards      = Array.prototype.slice.call(section.querySelectorAll('.sol-card'));
    if (!cardsWrap || !cards.length) return;

    cardsWrap.classList.add('sol-cards--js');

    function setActive(current) {
      cards.forEach(function (card) {
        card.classList.toggle('is-active', card === current);
      });
    }

    // ── Desktop: GSAP ScrollTrigger pin + centered carousel scrub ───────────
    // Returns false (nothing pinned) if there's only one card — nothing to
    // carousel through — so the plain-scroll fallback below takes over.
    function initPinned() {
      if (cards.length <= 1) return false;

      // .hdr__inner's height in _header.scss — the sticky site header sits
      // on top of everything (z-index: 1000), so the pin has to settle
      // *below* it, not flush against the literal viewport top, or the
      // header covers the first ~72px of the section's own content.
      var HEADER_H = 72;

      var bottomMargin  = 40;

      // Fixed design constants — must match .sol-card's height and
      // .sol-cards' gap in solutions_section.scss. Not measured from the
      // DOM on purpose (see file header comment).
      var CARD_HEIGHT   = 236;
      var GAP           = 40;
      var SLOT          = CARD_HEIGHT + GAP; // distance from one card's top to the next's
      var STEP_PER_CARD = 500;               // px of scroll needed to advance one card

      // Window tall enough to peek at the previous/next card above and
      // below the centered one, but never taller than the space actually
      // available below the header. Measured from HEADER_H, not
      // HEADER_H + the section's own padding-top: .sol-cards-viewport--pinned
      // cancels that padding out with a negative margin (see
      // solutions_section.scss) so cards visually enter/exit right under
      // the header instead of along a line further down the screen.
      var windowHeight = Math.min(
        SLOT * 2.4,
        Math.max(300, window.innerHeight - HEADER_H - bottomMargin)
      );
      viewportEl.style.height = windowHeight + 'px';
      viewportEl.classList.add('sol-cards-viewport--pinned');

      // .sol-col--left's own CSS position: sticky is redundant once the
      // whole section is pinned — worse than redundant, actually: two
      // independent position mechanisms (GSAP's pin + the child's sticky)
      // fighting over the same element made it visibly jump as the pin
      // engaged/released. This class turns the sticky off for the
      // duration; the CSS default stays in place for the no-GSAP/mobile
      // fallback below.
      section.classList.add('sol-section--pinned');

      var lastIndex = cards.length - 1;
      var extra     = lastIndex * STEP_PER_CARD;

      // Centers whichever point `pos` (continuous 0..lastIndex — the
      // fractional part is how far between two cards the carousel
      // currently sits) refers to within the window, instead of anchoring
      // the list to the window's top — this is what gives the "carousel,
      // focused card in the center" feel.
      function updatePosition(pos) {
        var translate = pos * SLOT + CARD_HEIGHT / 2 - windowHeight / 2;
        gsap.set(cardsWrap, { y: -translate });
        setActive(cards[Math.round(pos)]);
      }

      // Apply the pos = 0 transform immediately, synchronously, before any
      // scrolling happens — otherwise cardsWrap briefly renders at its
      // untransformed default position (first card flush against the
      // window's top edge, not centered) for a frame right as the pin
      // engages, then visibly snaps down to the real, centered position
      // once the first onUpdate call runs.
      updatePosition(0);

      ScrollTrigger.create({
        trigger: section,
        start:   'top ' + HEADER_H + 'px',
        end:     '+=' + extra,
        pin:     true,
        scrub:   true,
        onUpdate: function (self) {
          updatePosition(self.progress * lastIndex);
        },
      });

      return true;
    }

    // ── Mobile / no-GSAP fallback: scrollspy via native scroll ──────────────
    function initPlainScroll() {
      if (!('IntersectionObserver' in window)) return;

      var active = false;
      var ticking = false;

      function resync() {
        // Cards are in top-to-bottom DOM order, so the last one whose top
        // has already crossed the header line is the current one — once a
        // card hasn't reached it yet, none of the ones after it have either.
        var current = cards[0];

        for (var i = 0; i < cards.length; i++) {
          if (cards[i].getBoundingClientRect().top <= HEADER_OFFSET) {
            current = cards[i];
          } else {
            break;
          }
        }

        setActive(current);
      }

      function onScroll() {
        if (!active || ticking) return;
        ticking = true;
        requestAnimationFrame(function () {
          ticking = false;
          resync();
        });
      }

      var sectionObserver = new IntersectionObserver(function (entries) {
        active = entries[0].isIntersecting;
        if (active) resync(); // sync immediately on entering view, don't wait for a scroll event
      }, { root: null, rootMargin: '0px', threshold: 0 });

      sectionObserver.observe(section);
      window.addEventListener('scroll', onScroll, { passive: true });
      window.addEventListener('resize', onScroll, { passive: true });
    }

    var pinned = false;
    if (
      !isMobile() &&
      viewportEl &&
      typeof gsap !== 'undefined' &&
      typeof ScrollTrigger !== 'undefined'
    ) {
      gsap.registerPlugin(ScrollTrigger);
      pinned = initPinned();
    }

    if (!pinned) {
      initPlainScroll();
    }
  }

  function init() {
    document.querySelectorAll('.sol-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Reload across the pin/no-pin breakpoint — mirrors industries_section.js:
  // tearing down a GSAP ScrollTrigger pin vs. the plain-scroll fallback in
  // place isn't worth the complexity for a resize edge case.
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
