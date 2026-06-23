/**
 * Achievements by Years — tab switching, year pills, grid cell selection.
 *
 * State: activeMainTab → activeYear[panelId] → activeItem[panelId][year]
 * All transitions use CSS animation class (--entering) for smooth fades.
 */

(function () {
  'use strict';

  // ── Helpers ────────────────────────────────────────────────────────────────

  function addClass(el, cls) { el && el.classList.add(cls); }
  function removeClass(el, cls) { el && el.classList.remove(cls); }

  function animateIn(el, cls) {
    if (!el) return;
    removeClass(el, cls);
    // Force reflow so animation restarts if element was already visible
    void el.offsetWidth;
    addClass(el, cls);
    el.addEventListener('animationend', function handler() {
      removeClass(el, cls);
      el.removeEventListener('animationend', handler);
    });
  }


  // ── Main tab switching ─────────────────────────────────────────────────────

  function initMainTabs() {
    var tabs = Array.prototype.slice.call(
      document.querySelectorAll('.aby-tab')
    );

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        if (tab.classList.contains('aby-tab--active')) return;

        var targetId = tab.getAttribute('data-target');
        var targetPanel = document.getElementById(targetId);
        if (!targetPanel) return;

        var activeTab   = document.querySelector('.aby-tab--active');
        var activePanel = document.querySelector('.aby-panel:not(.aby-panel--hidden):not(.aby-panel--leaving)');

        // Update tab buttons immediately
        if (activeTab) { removeClass(activeTab, 'aby-tab--active'); activeTab.setAttribute('aria-selected', 'false'); }
        addClass(tab, 'aby-tab--active');
        tab.setAttribute('aria-selected', 'true');

        if (activePanel) {
          // Fade OUT current panel
          addClass(activePanel, 'aby-panel--leaving');
          setTimeout(function () {
            removeClass(activePanel, 'aby-panel--leaving');
            addClass(activePanel, 'aby-panel--hidden');
            activePanel.setAttribute('aria-hidden', 'true');
            // Fade IN new panel
            removeClass(targetPanel, 'aby-panel--hidden');
            targetPanel.setAttribute('aria-hidden', 'false');
            animateIn(targetPanel, 'aby-panel--entering');
          }, 200);
        } else {
          removeClass(targetPanel, 'aby-panel--hidden');
          targetPanel.setAttribute('aria-hidden', 'false');
          animateIn(targetPanel, 'aby-panel--entering');
        }
      });
    });
  }


  // ── Year pills within a panel ──────────────────────────────────────────────

  function initYearPills(panel) {
    panel.querySelectorAll('.aby-year-pill').forEach(function (pill) {
      pill.addEventListener('click', function () {
        if (pill.classList.contains('aby-year-pill--active')) return;

        var year = pill.getAttribute('data-year');

        // Deactivate current year pill immediately
        var activePill  = panel.querySelector('.aby-year-pill--active');
        var activeBlock = panel.querySelector('.aby-year-block:not(.aby-year-block--hidden):not(.aby-year-block--leaving)');

        if (activePill) { removeClass(activePill, 'aby-year-pill--active'); activePill.setAttribute('aria-selected', 'false'); }
        addClass(pill, 'aby-year-pill--active');
        pill.setAttribute('aria-selected', 'true');

        var newBlock = panel.querySelector('[data-year-block="' + year + '"]');

        if (activeBlock) {
          addClass(activeBlock, 'aby-year-block--leaving');
          setTimeout(function () {
            removeClass(activeBlock, 'aby-year-block--leaving');
            addClass(activeBlock, 'aby-year-block--hidden');
            if (newBlock) {
              removeClass(newBlock, 'aby-year-block--hidden');
              animateIn(newBlock, 'aby-year-block--entering');
              resetYearBlock(newBlock);
            }
          }, 150);
        } else if (newBlock) {
          removeClass(newBlock, 'aby-year-block--hidden');
          animateIn(newBlock, 'aby-year-block--entering');
          resetYearBlock(newBlock);
        }
      });
    });
  }


  // ── Grid cell → item detail switching ─────────────────────────────────────

  function initGrid(panel) {
    panel.querySelectorAll('.aby-year-block').forEach(function (block) {
      block.addEventListener('click', function (e) {
        var cell = e.target.closest('.aby-grid__cell');
        if (!cell) return;

        var idx = parseInt(cell.getAttribute('data-item'), 10);

        // Update active grid cell
        block.querySelectorAll('.aby-grid__cell').forEach(function (c) {
          c.classList.toggle('aby-grid__cell--active', c === cell);
        });

        // Show corresponding item detail
        var newItem = null;
        block.querySelectorAll('.aby-item').forEach(function (item) {
          var isTarget = parseInt(item.getAttribute('data-item'), 10) === idx;
          item.classList.toggle('aby-item--hidden', !isTarget);
          if (isTarget) newItem = item;
        });

        if (newItem) animateIn(newItem, 'aby-item--entering');
      });
    });
  }


  // ── Reset a year block to first item ──────────────────────────────────────

  function resetYearBlock(block) {
    block.querySelectorAll('.aby-item').forEach(function (item, i) {
      item.classList.toggle('aby-item--hidden', i !== 0);
    });
    block.querySelectorAll('.aby-grid__cell').forEach(function (cell, i) {
      cell.classList.toggle('aby-grid__cell--active', i === 0);
    });
  }


  // ── Init ───────────────────────────────────────────────────────────────────

  function init() {
    var section = document.querySelector('.aby-section');
    if (!section) return;

    initMainTabs();
    section.querySelectorAll('.aby-panel').forEach(function (panel) {
      initYearPills(panel);
      initGrid(panel);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
