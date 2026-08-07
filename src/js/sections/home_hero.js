/**
 * Home Hero — load animation + industry cycler + sticky button
 * File: blocks/home-hero/home-hero.js
 */

(function () {
  'use strict';

  // ── Load-in animation ─────────────────────────────────────────────────────
  function initLoadAnim(section) {
    // Set CSS variable --anim-delay from data-delay on each .hh-anim element
    section.querySelectorAll('.hh-anim').forEach(function (el) {
      var delay = el.getAttribute('data-delay') || '0';
      el.style.setProperty('--anim-delay', delay + 'ms');
    });

    // Trigger on next frame to ensure CSS is applied
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        section.classList.add('is-loaded');
      });
    });
  }


  // ── Industry typewriter cycler ───────────────────────────────────────────
  function initIndustryCycler(section) {
    var typedEl = section.querySelector('.hh-heading__typed');
    var imgEl   = section.querySelector('.hh-heading__img');
    if (!typedEl) return;

    var rawData    = typedEl.getAttribute('data-industries') || '[]';
    var industries = [];
    try { industries = JSON.parse(rawData); } catch (e) {}
    if (!industries.length) return;

    var currentIndex = 0;
    var TYPE_SPEED   = 130;  // ms per character while typing
    var ERASE_SPEED  = 35;   // ms per character while erasing
    var PAUSE_AFTER  = 2500; // ms to show full word before erasing
    var IMG_DELAY    = 200;  // ms after word fully typed before image appears

    // ── Type a word character by character ─────────────────────────────────
    function typeWord(word, callback) {
      typedEl.classList.remove('is-pausing', 'is-erasing');
      var i = 0;
      typedEl.textContent = '';

      function step() {
        if (i <= word.length) {
          typedEl.textContent = word.slice(0, i);
          i++;
          setTimeout(step, TYPE_SPEED);
        } else {
          typedEl.classList.remove('is-typing');
          typedEl.classList.add('is-pausing');
          if (callback) callback();
        }
      }
      step();
    }

    // ── Erase character by character ────────────────────────────────────────
    function eraseWord(callback) {
      typedEl.classList.remove('is-pausing');
      var word = typedEl.textContent;
      var i    = word.length;

      function step() {
        if (i >= 0) {
          typedEl.textContent = word.slice(0, i);
          i--;
          setTimeout(step, ERASE_SPEED);
        } else {
          typedEl.classList.remove('is-erasing', 'is-typing', 'is-pausing');
          if (callback) callback();
        }
      }
      step();
    }

    // ── Show / hide image wrap ───────────────────────────────────────────────
    var imgWrapEl = section.querySelector('.hh-heading__img-wrap');

    function showImage(industry) {
      if (!imgWrapEl) return;
      if (imgEl && industry.image_url) {
        imgEl.src = industry.image_url;
        imgEl.alt = industry.image_alt || industry.name;
        if (imgEl.complete) {
          imgWrapEl.classList.add('is-visible');
        } else {
          imgEl.onload = function () {
            imgEl.onload = null;
            imgWrapEl.classList.add('is-visible');
          };
        }
      } else {
        imgWrapEl.classList.add('is-visible');
      }
    }

    function hideImage(callback) {
      if (imgWrapEl) imgWrapEl.classList.remove('is-visible');
      setTimeout(callback || function () {}, 600); // match CSS transition 0.6s
    }

    // ── Main cycle loop ──────────────────────────────────────────────────────
    function runCycle() {
      var industry = industries[currentIndex];

      // 1. Type the word
      typeWord(industry.name, function () {

        // 2. Show image after slight delay
        setTimeout(function () {
          showImage(industry);

          // 3. Pause while showing full word + image
          setTimeout(function () {

            // 4. Hide image
            hideImage(function () {

              // 5. Erase the word
              eraseWord(function () {

                // 6. Advance to next industry
                currentIndex = (currentIndex + 1) % industries.length;

                // 7. Small gap before typing next word
                setTimeout(runCycle, 300);
              });
            });

          }, PAUSE_AFTER);
        }, IMG_DELAY);
      });
    }

    // Start after load animation completes (last element at 700ms + 700ms transition)
    setTimeout(runCycle, 1400);
  }


  // ── Sticky Book a Meeting button ──────────────────────────────────────────
  function initStickyButton(section) {
    var stickyBtn = document.querySelector('.js-sticky-meeting');
    if (!stickyBtn) return;

    var heroBottom = 0;
    var ticking    = false;

    function measure() {
      var rect = section.getBoundingClientRect();
      heroBottom = rect.bottom + window.scrollY;
    }

    function onScroll() {
      if (!ticking) {
        requestAnimationFrame(function () {
          var scrolled = window.scrollY + window.innerHeight * 0.5;
          if (scrolled > heroBottom) {
            stickyBtn.classList.add('is-visible');
          } else {
            stickyBtn.classList.remove('is-visible');
          }
          ticking = false;
        });
        ticking = true;
      }
    }

    measure();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function () { measure(); onScroll(); }, { passive: true });
    onScroll();
  }


  // ── Init ──────────────────────────────────────────────────────────────────
  function init() {
    var section = document.querySelector('.hh-section');
    if (!section) return;

    initLoadAnim(section);
    initIndustryCycler(section);
    initStickyButton(section);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
