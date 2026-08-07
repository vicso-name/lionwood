/**
 * About Hero — load animation + sticky button
 * Same as Home Hero but without the industry typewriter cycler.
 * File: blocks/about-hero/about-hero.js
 */

(function () {
  'use strict';

  function initLoadAnim(section) {
    section.querySelectorAll('.ah-anim').forEach(function (el) {
      var delay = el.getAttribute('data-delay') || '0';
      el.style.setProperty('--anim-delay', delay + 'ms');
    });

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        section.classList.add('is-loaded');
      });
    });
  }

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
          stickyBtn.classList.toggle('is-visible', scrolled > heroBottom);
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

  function init() {
    var section = document.querySelector('.ah-section');
    if (!section) return;
    initLoadAnim(section);
    initStickyButton(section);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
