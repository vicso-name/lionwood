/**
 * FAQ Section — accessible accordion with smooth animation
 * File: blocks/faq-section/faq-section.js
 *
 * Best practices:
 * - aria-expanded on trigger button
 * - aria-hidden on panel
 * - max-height animation via JS (measured scrollHeight)
 * - Only one item open at a time per accordion
 * - Keyboard: Enter/Space to toggle, Escape to close
 * - Respects prefers-reduced-motion
 */

(function () {
  'use strict';

  var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function initAccordion(accordion) {
    var items    = Array.prototype.slice.call(accordion.querySelectorAll('[data-faq-item]'));
    var triggers = Array.prototype.slice.call(accordion.querySelectorAll('[data-faq-trigger]'));
    var panels   = Array.prototype.slice.call(accordion.querySelectorAll('[data-faq-panel]'));

    if (!items.length) return;

    // Close a specific item
    function closeItem(item) {
      var trigger = item.querySelector('[data-faq-trigger]');
      var panel   = item.querySelector('[data-faq-panel]');

      if (!trigger || !panel) return;

      item.classList.remove('is-open');
      panel.classList.remove('is-open');
      trigger.setAttribute('aria-expanded', 'false');
      panel.setAttribute('aria-hidden', 'true');

      if (prefersReduced) {
        panel.style.maxHeight = '0';
      } else {
        // Animate from current height to 0
        panel.style.maxHeight = panel.scrollHeight + 'px';
        // Force reflow
        panel.offsetHeight; // force reflow
        panel.style.maxHeight = '0';
      }
    }

    // Open a specific item
    function openItem(item) {
      var trigger = item.querySelector('[data-faq-trigger]');
      var panel   = item.querySelector('[data-faq-panel]');

      if (!trigger || !panel) return;

      item.classList.add('is-open');
      panel.classList.add('is-open');
      trigger.setAttribute('aria-expanded', 'true');
      panel.setAttribute('aria-hidden', 'false');

      if (prefersReduced) {
        panel.style.maxHeight = 'none';
      } else {
        panel.style.maxHeight = panel.scrollHeight + 'px';
      }
    }

    // Toggle item — close all others first
    function toggleItem(item) {
      var isOpen = item.classList.contains('is-open');

      // Close all items
      items.forEach(function (it) {
        if (it !== item && it.classList.contains('is-open')) {
          closeItem(it);
        }
      });

      if (isOpen) {
        closeItem(item);
      } else {
        openItem(item);
      }
    }

    // Click handler
    triggers.forEach(function (trigger) {
      trigger.addEventListener('click', function () {
        var item = trigger.closest('[data-faq-item]');
        if (item) toggleItem(item);
      });

      // Keyboard: Escape closes current item
      trigger.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          var item = trigger.closest('[data-faq-item]');
          if (item && item.classList.contains('is-open')) {
            closeItem(item);
            trigger.focus();
          }
        }
      });
    });

    // On transition end — set max-height to none for open items
    // (so content can grow if resized)
    panels.forEach(function (panel) {
      panel.addEventListener('transitionend', function (e) {
        if (e.propertyName !== 'max-height') return;
        var item = panel.closest('[data-faq-item]');
        if (item && item.classList.contains('is-open')) {
          panel.style.maxHeight = 'none';
        }
      });
    });

    // Initialise items that are pre-opened in HTML (first item)
    items.forEach(function (item) {
      if (item.classList.contains('is-open')) {
        var panel = item.querySelector('[data-faq-panel]');
        if (panel) panel.style.maxHeight = 'none';
      }
    });

    // Recalculate on resize for open items
    window.addEventListener('resize', function () {
      items.forEach(function (item) {
        if (item.classList.contains('is-open')) {
          var panel = item.querySelector('[data-faq-panel]');
          if (panel && !prefersReduced) {
            panel.style.maxHeight = panel.scrollHeight + 'px';
          }
        }
      });
    }, { passive: true });
  }

  function init() {
    document.querySelectorAll('[data-faq-accordion]').forEach(initAccordion);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
