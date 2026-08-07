/**
 * Solutions Showcase — accordion + left panel stagger switcher
 * File: blocks/solutions-showcase/solutions-showcase.js
 *
 * On accordion trigger click:
 *  1. Fade out each panel element (simultaneous, actually animated —
 *     FADE_OUT_MS below), while the accordion itself (arrow, active item)
 *     responds immediately
 *  2. Swap content only once that fade-out has actually finished
 *  3. Stagger fade in: stat value → stat desc → image → includes label →
 *     list items — each waits for its own <img> to finish loading, if it
 *     has one, before its turn in the stagger fires
 */

(function () {
  'use strict';

  var checkSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M14.1084 3.47703C14.0237 3.389 13.9229 3.31913 13.812 3.27145C13.701 3.22377 13.5819 3.19922 13.4617 3.19922C13.3415 3.19922 13.2224 3.22377 13.1114 3.27145C13.0004 3.31913 12.8997 3.389 12.815 3.47703L6.02971 10.4834L3.17897 7.53432C3.09106 7.44675 2.98728 7.3779 2.87356 7.33168C2.75985 7.28547 2.63842 7.26281 2.51621 7.26499C2.394 7.26717 2.27341 7.29415 2.16131 7.34439C2.04921 7.39463 1.94781 7.46715 1.86289 7.5578C1.77797 7.64845 1.71119 7.75546 1.66638 7.87273C1.62157 7.98999 1.59959 8.11521 1.6017 8.24123C1.60382 8.36725 1.62998 8.4916 1.6787 8.6072C1.72742 8.72279 1.79775 8.82736 1.88566 8.91493L5.38306 12.5214C5.46773 12.6094 5.56846 12.6793 5.67945 12.727C5.79044 12.7747 5.90948 12.7992 6.02971 12.7992C6.14995 12.7992 6.26899 12.7747 6.37998 12.727C6.49097 12.6793 6.5917 12.6094 6.67637 12.5214L14.1084 4.85764C14.2008 4.76969 14.2746 4.66295 14.325 4.54414C14.3755 4.42533 14.4016 4.29704 14.4016 4.16733C14.4016 4.03763 14.3755 3.90933 14.325 3.79052C14.2746 3.67172 14.2008 3.56498 14.1084 3.47703Z" fill="white"/></svg>';

  // Matches .ss-anim's opacity transition below — the fade-out is given this
  // long to actually finish before content gets swapped underneath it.
  var FADE_OUT_MS = 300;

  // ── CSS for stagger elements ───────────────────────────────────────────────
  // Each animated element gets .ss-anim — opacity + translateY transition
  // JS toggles .ss-anim--hidden (out) and .ss-anim--visible (in)
  var style = document.createElement('style');
  style.textContent = [
    '.ss-anim {',
    '  transition: opacity ' + (FADE_OUT_MS / 1000) + 's ease, transform 0.35s ease;',
    '  will-change: opacity, transform;',
    '}',
    '.ss-anim--hidden {',
    '  opacity: 0;',
    '  transform: translateY(10px);',
    '}',
    '.ss-anim--visible {',
    '  opacity: 1;',
    '  transform: translateY(0);',
    '}',
  ].join('');
  document.head.appendChild(style);

  // ── Helpers ────────────────────────────────────────────────────────────────

  /**
   * Get all stagger-able elements in order from a panel.
   * Returns array of elements with their stagger delay index.
   */
  function getStaggerEls(panel) {
    var els = [];

    var statValue = panel.querySelector('[data-field="stat_value"]');
    var statDesc  = panel.querySelector('[data-field="stat_desc"]');
    var statIcon  = panel.querySelector('[data-field="stat_icon"]');
    var image     = panel.querySelector('[data-field="image"]');
    var label     = panel.querySelector('[data-field="includes_label"]');
    var listItems = panel.querySelectorAll('.ss-panel__includes-item');

    if (statValue) els.push(statValue);
    if (statDesc)  els.push(statDesc);
    if (statIcon)  els.push(statIcon);
    if (image)     els.push(image);
    if (label)     els.push(label);
    listItems.forEach(function (li) { els.push(li); });

    return els;
  }

  /** Mark all stagger elements as animated */
  function prepareEls(els) {
    els.forEach(function (el) {
      el.classList.add('ss-anim');
    });
  }

  /**
   * Fade out all elements — actually animated (matches .ss-anim's own
   * transition), not an instant cut. The click handler waits FADE_OUT_MS
   * for this to finish before swapping content, so the old content visibly
   * fades away instead of vanishing in a single frame right before the new
   * content fades in — the "hide instantly, then fade in" version of this
   * read as an abrupt blink no matter how smooth the reveal itself was.
   */
  function hideEls(els) {
    els.forEach(function (el) {
      el.classList.remove('ss-anim--visible');
      el.classList.add('ss-anim--hidden');
    });
  }

  /**
   * Stagger reveal: each element fades in with increasing delay.
   *
   * An element wrapping a freshly-inserted <img> (the service image, the
   * stat icon) waits for that image to actually finish loading before its
   * turn in the stagger fires. Without this, the wrapper's opacity
   * transition runs and finishes on schedule while the image is still
   * downloading, so the image itself just pops in whenever the network
   * delivers it — well after the fade has already completed. Only visible
   * with real, non-cached uploads (small/cached placeholder images loaded
   * fast enough that this never mattered during development).
   */
  function revealEls(els) {
    els.forEach(function (el, i) {
      var delay = i * 60; // 60ms between each element
      var img = el.querySelector('img');

      function show() {
        el.classList.remove('ss-anim--hidden');
        el.classList.add('ss-anim--visible');
      }

      if (img && !img.complete) {
        img.addEventListener('load', function () { setTimeout(show, delay); }, { once: true });
        img.addEventListener('error', function () { setTimeout(show, delay); }, { once: true });
      } else {
        setTimeout(show, delay);
      }
    });
  }

  /** Update panel DOM content from data object */
  function updatePanel(panel, data) {
    var statValue = panel.querySelector('[data-field="stat_value"]');
    if (statValue) statValue.textContent = data.stat_value || '';

    var statDesc = panel.querySelector('[data-field="stat_desc"]');
    if (statDesc) statDesc.textContent = data.stat_desc || '';

    var statIcon = panel.querySelector('[data-field="stat_icon"]');
    if (statIcon) {
      statIcon.innerHTML = data.stat_icon_url
        ? '<img src="' + data.stat_icon_url + '" alt="' + (data.stat_icon_alt || '') + '" width="56" height="56" loading="lazy">'
        : '';
    }

    var imageWrap = panel.querySelector('[data-field="image"]');
    if (imageWrap) {
      // eager, not lazy: this image is already in the viewport the instant
      // it's inserted (the user just clicked the accordion item to reveal
      // it), so there's nothing to gain by deferring the fetch.
      imageWrap.innerHTML = data.image_url
        ? '<img src="' + data.image_url + '" alt="' + (data.image_alt || '') + '" loading="eager">'
        : '';
    }

    var labelEl = panel.querySelector('[data-field="includes_label"]');
    if (labelEl) labelEl.textContent = data.includes_label || '';

    var list = panel.querySelector('[data-field="includes_items"]');
    if (list) {
      list.innerHTML = '';
      (data.includes_items || []).forEach(function (text) {
        var li = document.createElement('li');
        li.className = 'ss-panel__includes-item ss-anim ss-anim--hidden';
        li.innerHTML =
          '<span class="ss-panel__includes-icon" aria-hidden="true">' + checkSvg + '</span>' +
          '<span>' + text + '</span>';
        list.appendChild(li);
      });
    }
  }

  // ── Init ───────────────────────────────────────────────────────────────────

  function init() {
    document.querySelectorAll('.ss-section').forEach(function (section) {
      var panel    = section.querySelector('.ss-panel');
      var items    = section.querySelectorAll('.ss-accordion__item');
      var triggers = section.querySelectorAll('.ss-accordion__trigger');

      if (!panel || !triggers.length) return;

      // Bumped on every click; a delayed swap only runs if it's still the
      // most recent one by the time its FADE_OUT_MS timer fires — otherwise
      // a fast second click (within the fade-out window) could have its
      // content briefly overwritten by an earlier, now-stale click's swap.
      var latestClickToken = 0;

      // Prepare initial elements
      var initialEls = getStaggerEls(panel);
      prepareEls(initialEls);
      // Show initial state immediately (no animation on load)
      initialEls.forEach(function (el) {
        el.classList.add('ss-anim--visible');
      });

      triggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
          // Already open — do nothing
          if (trigger.getAttribute('aria-expanded') === 'true') return;

          var item = trigger.closest('.ss-accordion__item');
          var body = section.querySelector('#' + trigger.getAttribute('aria-controls'));

          // Parse panel data
          var data = {};
          try { data = JSON.parse(item.getAttribute('data-panel') || '{}'); } catch (e) {}

          var clickToken = ++latestClickToken;

          // 1. Fade out current elements
          var currentEls = getStaggerEls(panel);
          prepareEls(currentEls);
          hideEls(currentEls);

          // 2. Accordion state (arrow rotation, active item, expanded body)
          // updates immediately — the trigger should respond to the click
          // right away. Only the left panel's content waits for the fade.
          items.forEach(function (it) {
            it.classList.remove('is-active');
            var t = it.querySelector('.ss-accordion__trigger');
            var b = it.querySelector('.ss-accordion__body');
            if (t) t.setAttribute('aria-expanded', 'false');
            if (b) b.hidden = true;
          });
          item.classList.add('is-active');
          trigger.setAttribute('aria-expanded', 'true');
          if (body) body.hidden = false;

          // 3. Only swap content once the fade-out has actually finished —
          // swapping after a single frame (like before) meant the new
          // content's stagger-in started while the old content was still
          // visibly mid-fade, which is what made the whole thing look like
          // an abrupt cut rather than a smooth cross-fade.
          setTimeout(function () {
            if (clickToken !== latestClickToken) return; // superseded by a later click

            updatePanel(panel, data);

            // 4. Get new elements (list items just created) and stagger reveal
            var newEls = getStaggerEls(panel);
            prepareEls(newEls);
            // Ensure they start hidden
            newEls.forEach(function (el) {
              if (!el.classList.contains('ss-anim--hidden')) {
                el.classList.add('ss-anim--hidden');
              }
              el.classList.remove('ss-anim--visible');
            });

            // One frame so the browser registers the hidden state before animating
            requestAnimationFrame(function () {
              revealEls(newEls);
            });
          }, FADE_OUT_MS);
        });
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
