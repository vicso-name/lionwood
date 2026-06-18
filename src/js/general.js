/**
 * ===========================================================
 * General Frontend Interactions
 * ===========================================================
 * Organized into small, readable functions
 * - Header toggle
 * - Smooth scroll
 * - Replace <img.svg> with inline <svg>
 * - AOS-style fade-up animations
 */

document.addEventListener("DOMContentLoaded", () => {
  initHeaderToggle();
  initSmoothScroll();
  replaceImagesWithInlineSVGs();
});

/* ===========================================================
 * 1. Header Contact Toggle
 * =========================================================== */
function initHeaderToggle() {
  const toggles = document.querySelectorAll(".menu-toggle");

  if (!toggles.length) return;

  toggles.forEach((toggle) => {
    toggle.addEventListener("click", () => {
      const parent = toggle.parentNode;
      parent.classList.toggle("activ");

      const navigationBox = parent.querySelector(".navigation-box");
      if (navigationBox) {
        const isVisible = navigationBox.style.display === "block";
        navigationBox.style.display = isVisible ? "none" : "block";
      }
    });
  });
}

/* ===========================================================
 * 2. Smooth Scroll to Section
 * =========================================================== */
function initSmoothScroll() {
  const links = document.querySelectorAll('a[href^="#"]');
  if (!links.length) return;

  links.forEach((link) => {
    link.addEventListener("click", (e) => {
      const targetId = link.getAttribute("href").slice(1);
      const targetEl = document.getElementById(targetId);

      if (!targetEl) return;
      e.preventDefault();
      targetEl.scrollIntoView({ behavior: "smooth" });
    });
  });
}

/* ===========================================================
 * 3. Convert <img class="svg"> → inline <svg>
 * =========================================================== */
function replaceImagesWithInlineSVGs() {
  const svgImages = document.querySelectorAll("img.svg");
  if (!svgImages.length) return;

  svgImages.forEach((img) => {
    const imgURL = img.src;

    fetch(imgURL)
      .then((res) => res.text())
      .then((data) => {
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(data, "image/svg+xml");
        const svg = xmlDoc.querySelector("svg");

        if (!svg) {
          console.error("SVG not found in:", imgURL);
          return;
        }

        // Copy ID and class
        if (img.id) svg.id = img.id;
        if (img.className) svg.classList.add(...img.classList, "replaced-svg");

        // Remove unnecessary attributes
        svg.removeAttribute("xmlns:a");

        // Add viewBox if missing
        if (
          !svg.hasAttribute("viewBox") &&
          svg.hasAttribute("height") &&
          svg.hasAttribute("width")
        ) {
          svg.setAttribute(
            "viewBox",
            `0 0 ${svg.getAttribute("width")} ${svg.getAttribute("height")}`
          );
        }

        img.replaceWith(svg);
      })
      .catch((err) => console.error("Error fetching SVG:", err));
  });
}

/**
 * Header — mega menu, language switcher, sticky
 * File: src/js/sections/header.js
 */

(function () {
    'use strict';

    var header = document.getElementById('header');
    if ( !header ) return;

    // ── Sticky scrolled class ─────────────────────────────────────────────────
    function onScroll() {
        header.classList.toggle('is-scrolled', window.scrollY > 10);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // ── Mega menu — open/close on trigger click ───────────────────────────────
    var triggers = header.querySelectorAll('[data-hdr-trigger]');

    function closeAll() {
        triggers.forEach(function (btn) {
            btn.setAttribute('aria-expanded', 'false');
            var li = btn.closest('.hdr__menu-item');
            if (li) {
                li.classList.remove('is-open');
                li.classList.remove('is-hovered');
            }
        });
    }

    triggers.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var li      = btn.closest('.hdr__menu-item');
            var isOpen  = li.classList.contains('is-open');
            closeAll();
            if (!isOpen) {
                li.classList.add('is-open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // ── Hover grace period — bridge the gap between link and dropdown ─────────
    var menuDropItems = header.querySelectorAll('.hdr__menu-item--has-drop');
    menuDropItems.forEach(function (item) {
        var leaveTimer;
        item.addEventListener('mouseenter', function () {
            clearTimeout(leaveTimer);
            item.classList.add('is-hovered');
        });
        item.addEventListener('mouseleave', function () {
            leaveTimer = setTimeout(function () {
                item.classList.remove('is-hovered');
            }, 300);
        });
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.hdr__menu-item--has-drop')) {
            closeAll();
        }
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAll();
    });

    // ── Language switcher ─────────────────────────────────────────────────────
    var langWrap   = header.querySelector('[data-hdr-lang]');
    var langToggle = header.querySelector('[data-hdr-lang-toggle]');
    var langDrop   = header.querySelector('.hdr__lang-drop');

    if (langToggle && langDrop) {
        langToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = langDrop.classList.toggle('is-open');
            langToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (langWrap && !langWrap.contains(e.target)) {
                langDrop.classList.remove('is-open');
                langToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ── Mobile menu ───────────────────────────────────────────────────────────
    var burger = header.querySelector('[data-hdr-burger]');
    var mob    = document.getElementById('mob-menu');

    function openMob() {
        if (!mob) return;
        mob.classList.add('is-open');
        mob.setAttribute('aria-hidden', 'false');
        if (burger) burger.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMob() {
        if (!mob) return;
        mob.classList.remove('is-open');
        mob.setAttribute('aria-hidden', 'true');
        if (burger) burger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    if (burger) {
        burger.addEventListener('click', function () {
            mob && mob.classList.contains('is-open') ? closeMob() : openMob();
        });
    }

    if (mob) {
        // Close via backdrop and X button
        mob.querySelectorAll('[data-mob-close]').forEach(function (el) {
            el.addEventListener('click', closeMob);
        });

        // Depth-0 accordion toggles
        mob.querySelectorAll('[data-mob-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var li     = btn.closest('.mob__item');
                var isOpen = li.classList.toggle('is-open');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });

        // Depth-1 sub-accordion toggles
        mob.querySelectorAll('[data-mob-sub-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var wrap   = btn.closest('.mob__sub-item');
                var isOpen = wrap.classList.toggle('is-open');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });

        // Mobile language switcher
        var mobLangToggle = mob.querySelector('[data-mob-lang-toggle]');
        var mobLangDrop   = mob.querySelector('.mob__lang-drop');
        var mobLangWrap   = mob.querySelector('[data-mob-lang]');

        if (mobLangToggle && mobLangDrop) {
            mobLangToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = mobLangDrop.classList.toggle('is-open');
                mobLangToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function (e) {
                if (mobLangWrap && !mobLangWrap.contains(e.target)) {
                    mobLangDrop.classList.remove('is-open');
                    mobLangToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    }

    // Close mob on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mob && mob.classList.contains('is-open')) {
            closeMob();
        }
    });

})();
