/**
 * About Map Section — counter animation
 * File: blocks/about-map/about-map.js
 *
 * Animates .ams-stats__value elements from 0 to their data-target value
 * when the section enters the viewport. Fires once per page load.
 */

(function () {
    'use strict';

    var DURATION = 2800; // ms — slow and smooth
    var EASING   = function (t) {
        // ease-out quint — starts fast, decelerates very gently at the end
        return 1 - Math.pow(1 - t, 5);
    };

    function animateCounter(el) {
        var target  = parseFloat(el.getAttribute('data-target')) || 0;
        var suffix  = el.getAttribute('data-suffix') || '';
        var isFloat = target % 1 !== 0;
        var start   = null;

        function step(ts) {
            if (!start) start = ts;
            var elapsed  = ts - start;
            var progress = Math.min(elapsed / DURATION, 1);
            var eased    = EASING(progress);
            var current  = target * eased;
            el.textContent = (isFloat ? current.toFixed(1) : Math.round(current)) + suffix;
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = (isFloat ? target.toFixed(1) : target) + suffix;
            }
        }

        requestAnimationFrame(step);
    }

    function initSection(section) {
        var counters = Array.prototype.slice.call(
            section.querySelectorAll('.ams-stats__value[data-target]')
        );
        if (!counters.length) return;

        var triggered = false;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting && !triggered) {
                    triggered = true;
                    counters.forEach(animateCounter);
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        observer.observe(section);
    }

    function init() {
        document.querySelectorAll('.ams-section').forEach(initSection);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
