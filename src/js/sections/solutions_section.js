/**
 * Solutions Section — active card on scroll
 * File: blocks/solutions-section/solutions-section.js
 *
 * As the right column scrolls past the viewport center, the card
 * crossing the center band gets .is-active (sharp/full opacity);
 * the rest stay dimmed via .sol-cards--js .sol-card CSS defaults.
 */

(function () {
  'use strict';

  function initSection(section) {
    var cardsWrap = section.querySelector('.sol-cards');
    var cards = section.querySelectorAll('.sol-card');
    if (!cardsWrap || !cards.length) return;

    if (!('IntersectionObserver' in window)) return;

    cardsWrap.classList.add('sol-cards--js');

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        entry.target.classList.toggle('is-active', entry.isIntersecting);
      });
    }, {
      root: null,
      rootMargin: '-45% 0px -45% 0px',
      threshold: 0,
    });

    cards.forEach(function (card) { observer.observe(card); });
  }

  function init() {
    document.querySelectorAll('.sol-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
