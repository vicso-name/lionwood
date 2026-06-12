/**
 * Career Hero Section
 * File: blocks/career-hero/career-hero.js
 *
 * 1. Load-in animation — mirrors chs- pattern
 * 2. Social icons expand: click "···" → show hidden items, hide button
 */

(function () {
    'use strict';

    function initLoadAnim(section) {
        section.querySelectorAll('.crh-anim').forEach(function (el) {
            var delay = el.getAttribute('data-delay') || '0';
            el.style.setProperty('--anim-delay', delay + 'ms');
        });

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                section.classList.add('is-loaded');
            });
        });
    }

    function initSocials(section) {
        var socialsWrap = section.querySelector('[data-crh-socials]');
        var moreBtn     = section.querySelector('[data-crh-more]');
        var closeBtn    = section.querySelector('[data-crh-close]');

        if (!socialsWrap || !moreBtn) return;

        moreBtn.addEventListener('click', function () {
            socialsWrap.classList.add('is-expanded');
            var firstHidden = socialsWrap.querySelector('.crh-socials__item--hidden');
            if (firstHidden) firstHidden.focus();
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                socialsWrap.classList.remove('is-expanded');
                moreBtn.focus();
            });
        }
    }

    function init() {
        var section = document.querySelector('.crh-section');
        if (!section) return;
        initLoadAnim(section);
        initSocials(section);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
