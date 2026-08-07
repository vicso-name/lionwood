/**
 * About Our Talent — accordion + left panel switcher
 * File: blocks/about-talent/about-talent.js
 *
 * Same pattern as solutions-showcase.js:
 * - Accordion trigger click → update left panel data
 * - Stagger fade animation on panel elements
 * - Counter animation on stat value (0 → target)
 */

(function () {
    'use strict';

    var COUNTER_DURATION = 1800;
    var COUNTER_EASING   = function (t) { return 1 - Math.pow(1 - t, 4); };

    // ── Stagger CSS (injected once) ──────────────────────────────────────────
    var style = document.createElement('style');
    style.textContent = [
        '.abt-anim { transition: opacity 0.3s ease, transform 0.35s ease; will-change: opacity, transform; }',
        '.abt-anim--hidden  { opacity: 0; transform: translateY(10px); }',
        '.abt-anim--visible { opacity: 1; transform: translateY(0); }',
    ].join('');
    document.head.appendChild(style);

    // ── Counter ───────────────────────────────────────────────────────────────
    function animateCounter(el, targetStr, suffix) {
        var target  = parseFloat(targetStr) || 0;
        var isFloat = target % 1 !== 0;
        var start   = null;

        function step(ts) {
            if (!start) start = ts;
            var elapsed  = Math.min((ts - start) / COUNTER_DURATION, 1);
            var eased    = COUNTER_EASING(elapsed);
            var current  = target * eased;
            el.textContent = (isFloat ? current.toFixed(1) : Math.round(current)) + suffix;
            if (elapsed < 1) requestAnimationFrame(step);
            else el.textContent = (isFloat ? target.toFixed(1) : target) + suffix;
        }
        requestAnimationFrame(step);
    }

    // ── Stagger helpers (same as ss-) ─────────────────────────────────────────
    function getEls(panel) {
        var els = [];
        ['funnel_label','funnel_row_1','funnel_row_2','funnel_row_3',
         'stat_label','stat_value','stat_desc',
         'badge_1_text','badge_2_text'].forEach(function (f) {
            var el = panel.querySelector('[data-field="' + f + '"]');
            if (el) els.push(el);
        });
        return els;
    }

    function prepEls(els) {
        els.forEach(function (el) { el.classList.add('abt-anim'); });
    }

    function hideEls(els) {
        els.forEach(function (el) {
            el.style.transition = 'none';
            el.classList.remove('abt-anim--visible');
            el.classList.add('abt-anim--hidden');
            void el.offsetHeight;
            el.style.transition = '';
        });
    }

    function revealEls(els) {
        els.forEach(function (el, i) {
            setTimeout(function () {
                el.classList.remove('abt-anim--hidden');
                el.classList.add('abt-anim--visible');
            }, i * 60);
        });
    }

    // ── Update panel DOM ─────────────────────────────────────────────────────
    function updatePanel(panel, data) {
        var fields = ['funnel_label','funnel_row_1','funnel_row_2','funnel_row_3',
                      'stat_label','stat_desc','badge_1_text','badge_2_text'];
        fields.forEach(function (f) {
            var el = panel.querySelector('[data-field="' + f + '"]');
            if (el) el.textContent = data[f] || '';
        });

        // Stat value — update data attr then animate counter
        var statEl = panel.querySelector('[data-field="stat_value"]');
        if (statEl) {
            var target = data.stat_value ? data.stat_value.replace(/[^0-9.]/g, '') : '0';
            var suffix = data.stat_suffix || '';
            statEl.setAttribute('data-target', target);
            statEl.setAttribute('data-suffix', suffix);
            animateCounter(statEl, target, suffix);
        }
    }

    // ── Init ──────────────────────────────────────────────────────────────────
    function initSection(section) {
        var panel    = section.querySelector('.abt-panel');
        var items    = section.querySelectorAll('.abt-accordion__item');
        var triggers = section.querySelectorAll('.abt-accordion__trigger');

        if (!panel || !triggers.length) return;

        // Initial state
        var initEls = getEls(panel);
        prepEls(initEls);
        initEls.forEach(function (el) { el.classList.add('abt-anim--visible'); });

        // Trigger counter on initial stat value
        var initStat = panel.querySelector('[data-field="stat_value"]');
        if (initStat) {
            var observed = false;
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting && !observed) {
                        observed = true;
                        animateCounter(initStat,
                            initStat.getAttribute('data-target') || '0',
                            initStat.getAttribute('data-suffix') || '');
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(section);
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                if (trigger.getAttribute('aria-expanded') === 'true') return;

                var item = trigger.closest('.abt-accordion__item');
                var body = section.querySelector('#' + trigger.getAttribute('aria-controls'));
                var data = {};
                try { data = JSON.parse(item.getAttribute('data-panel') || '{}'); } catch (e) {}

                // 1. Hide current elements
                var curEls = getEls(panel);
                prepEls(curEls);
                hideEls(curEls);

                // 2. Close all accordion items
                items.forEach(function (it) {
                    it.classList.remove('is-active');
                    var t = it.querySelector('.abt-accordion__trigger');
                    var b = it.querySelector('.abt-accordion__body');
                    if (t) t.setAttribute('aria-expanded', 'false');
                    if (b) b.hidden = true;
                });

                // 3. Open clicked item
                item.classList.add('is-active');
                trigger.setAttribute('aria-expanded', 'true');
                if (body) body.hidden = false;

                // 4. Swap content + stagger reveal
                requestAnimationFrame(function () {
                    updatePanel(panel, data);
                    var newEls = getEls(panel);
                    prepEls(newEls);
                    newEls.forEach(function (el) {
                        el.classList.add('abt-anim--hidden');
                        el.classList.remove('abt-anim--visible');
                    });
                    setTimeout(function () { revealEls(newEls); }, 30);
                });
            });
        });
    }

    function init() {
        document.querySelectorAll('.abt-section').forEach(initSection);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
