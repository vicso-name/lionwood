/**
 * Technologies Section — tabs + constrained random grid
 * Unified grid builder: labels shown on all screen sizes, fluid cell sizes.
 * CSS breakpoints in technologies_section.scss must mirror getMobileCols().
 */

(function () {
  'use strict';

  var COLS_DESKTOP = 14;
  var ROWS_DESKTOP = 5;
  var GAP_MS       = 25;

  // ── PRNG ──────────────────────────────────────────────────────────────────
  function makePRNG(seed) {
    return function () {
      seed |= 0; seed = seed + 0x6D2B79F5 | 0;
      var t = Math.imul(seed ^ (seed >>> 15), 1 | seed);
      t = t + Math.imul(t ^ (t >>> 7), 61 | t) ^ t;
      return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };
  }

  function shuffle(arr, rand) {
    var a = arr.slice();
    for (var i = a.length - 1; i > 0; i--) {
      var j = Math.floor(rand() * (i + 1));
      var tmp = a[i]; a[i] = a[j]; a[j] = tmp;
    }
    return a;
  }

  // ── Placement (parameterized cols × rows) ─────────────────────────────────
  function placeTechnologies(techs, rand, cols, rows) {
    var occupied = [];
    for (var i = 0; i < rows; i++) occupied.push(new Array(cols).fill(false));

    function inBounds(r, c) { return r >= 0 && r < rows && c >= 0 && c < cols; }

    function blockCell(r, c) {
      occupied[r][c] = true;
      if (inBounds(r - 1, c)) occupied[r - 1][c] = true;
      if (inBounds(r + 1, c)) occupied[r + 1][c] = true;
      if (inBounds(r, c - 1)) occupied[r][c - 1] = true;
      if (inBounds(r, c + 1)) occupied[r][c + 1] = true;
    }

    var candidates = shuffle(
      Array.from({ length: rows * cols }, function (_, i) { return i; }),
      rand
    );

    var placed = [];

    techs.forEach(function (tech) {
      for (var ci = 0; ci < candidates.length; ci++) {
        var idx = candidates[ci];
        var r   = Math.floor(idx / cols);
        var c   = idx % cols;

        if (occupied[r][c]) continue;

        var canRight = (c + 2 < cols) && !occupied[r][c + 1] && !occupied[r][c + 2];
        var canLeft  = (c - 2 >= 0)   && !occupied[r][c - 2] && !occupied[r][c - 1];

        var dir;
        if (canRight && canLeft) dir = rand() > 0.5 ? 'right' : 'left';
        else if (canRight)       dir = 'right';
        else if (canLeft)        dir = 'left';
        else                     continue;

        var labelStartCol = dir === 'right' ? c + 1 : c - 2;

        blockCell(r, c);
        blockCell(r, labelStartCol);
        blockCell(r, labelStartCol + 1);

        placed.push({
          tech:          tech,
          row:           r,
          col:           c,
          dir:           dir,
          labelStartCol: labelStartCol,
        });

        candidates.splice(ci, 1);
        break;
      }
    });

    return placed;
  }

  // ── Mobile config — mirrors CSS breakpoints ───────────────────────────────
  function getMobileCols() {
    var w = window.innerWidth;
    if (w < 600) return 4;
    if (w < 768) return 6;
    if (w < 900) return 8;
    return 10; // 900–1023 px
  }

  // More rows needed in narrow grids so all techs can be placed
  function getMobileRows(cols, techCount) {
    if (cols <= 4) return Math.max(8,  Math.ceil(techCount * 2.0)); // ~1 tech per 2 rows due to buffer blocking
    if (cols <= 6) return Math.max(5,  Math.ceil(techCount * 1.2));
    if (cols <= 8) return Math.max(5,  Math.ceil(techCount * 0.9));
    return 5;
  }

  // ── Build grid DOM (unified — labels on all screen sizes) ─────────────────
  function buildGrid(gridEl, technologies, tabIndex) {
    var isDesktop = window.innerWidth >= 1024;
    var cols, rows;

    if (isDesktop) {
      cols = COLS_DESKTOP;
      rows = ROWS_DESKTOP;
    } else {
      cols = getMobileCols();
      rows = getMobileRows(cols, technologies.length);
    }

    var seed   = (tabIndex * 13337) ^ (Math.floor(window.ts2Seed || 0) & 0xFFFF);
    var rand   = makePRNG(seed);
    var placed = placeTechnologies(technologies, rand, cols, rows);

    var iconAt  = {};
    var labelAt = {};
    var skipAt  = {};

    placed.forEach(function (p) {
      if (!iconAt[p.row])  iconAt[p.row]  = {};
      if (!labelAt[p.row]) labelAt[p.row] = {};
      if (!skipAt[p.row])  skipAt[p.row]  = {};

      iconAt[p.row][p.col]            = p;
      labelAt[p.row][p.labelStartCol] = p;
      skipAt[p.row][p.labelStartCol + 1] = true;
    });

    var frag = document.createDocumentFragment();

    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var delay = Math.floor(rand() * rows * cols * GAP_MS);

        if (skipAt[r] && skipAt[r][c]) continue;

        var techIcon  = iconAt[r]  && iconAt[r][c];
        var techLabel = labelAt[r] && labelAt[r][c];

        var cell = document.createElement('div');
        cell.style.setProperty('--cell-delay', delay + 'ms');
        cell.style.gridColumn = String(c + 1);
        cell.style.gridRow    = String(r + 1);

        if (techIcon) {
          cell.className = 'ts2-cell ts2-cell--tech is-entering';
          if (techIcon.tech.icon_url) {
            var iw  = document.createElement('div');
            iw.className = 'ts2-cell__icon';
            var img = document.createElement('img');
            img.src     = techIcon.tech.icon_url;
            img.alt     = techIcon.tech.icon_alt || techIcon.tech.name;
            img.loading = 'lazy';
            iw.appendChild(img);
            cell.appendChild(iw);
          }

        } else if (techLabel) {
          cell.className = 'ts2-cell ts2-cell--label is-entering';
          cell.style.gridColumn = (c + 1) + ' / span 2';
          var span = document.createElement('span');
          span.className   = 'ts2-cell__name-inline';
          span.textContent = '/ ' + techLabel.tech.name;
          cell.appendChild(span);

        } else {
          cell.className = 'ts2-cell is-entering';
        }

        frag.appendChild(cell);
      }
    }

    gridEl.innerHTML = '';
    gridEl.appendChild(frag);

    // Return timer ID so caller can cancel it if grid is rebuilt before it fires
    return setTimeout(function () {
      gridEl.querySelectorAll('.is-entering').forEach(function (el) {
        el.classList.remove('is-entering');
      });
    }, rows * cols * GAP_MS + 500);
  }

  // ── Section init ──────────────────────────────────────────────────────────
  function initSection(section) {
    var gridEl  = section.querySelector('.ts2-grid');
    var tabBtns = Array.prototype.slice.call(section.querySelectorAll('.ts2-tab'));
    if (!gridEl || !tabBtns.length) return;

    var tabsData = [];
    try { tabsData = JSON.parse(gridEl.getAttribute('data-technologies') || '[]'); } catch (e) {}
    if (!tabsData.length) return;

    var activeIndex   = -1;
    var lastColCount  = 0;
    var switchTimer   = null; // pending fade → build timeout
    var enteringTimer = null; // pending is-entering cleanup timeout

    function getColCount() {
      return window.innerWidth >= 1024 ? COLS_DESKTOP : getMobileCols();
    }

    function switchTab(index) {
      if (index === activeIndex) return;

      // Cancel any in-flight switch so only the last click wins
      clearTimeout(switchTimer);

      activeIndex = index;
      tabBtns.forEach(function (btn, i) {
        btn.classList.toggle('is-active', i === index);
        btn.setAttribute('aria-selected', i === index ? 'true' : 'false');
      });

      gridEl.style.opacity    = '0';
      gridEl.style.transition = 'opacity 0.2s ease';

      switchTimer = setTimeout(function () {
        // Stale-closure guard: another switchTab may have run during the 200ms
        if (index !== activeIndex) return;

        clearTimeout(enteringTimer); // cancel cleanup from previous grid
        lastColCount  = getColCount();
        enteringTimer = buildGrid(gridEl, tabsData[index].technologies || [], index);
        gridEl.style.opacity = '1';
      }, 200);
    }

    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        var cols = getColCount();
        if (cols !== lastColCount && activeIndex >= 0) {
          lastColCount  = cols;
          clearTimeout(enteringTimer);
          enteringTimer = buildGrid(gridEl, tabsData[activeIndex].technologies || [], activeIndex);
        }
      }, 150);
    });

    tabBtns.forEach(function (btn, i) {
      btn.addEventListener('click', function () { switchTab(i); });
    });

    switchTab(0);
  }

  function init() {
    window.ts2Seed = Math.random() * 0xFFFFFF;
    document.querySelectorAll('.ts2-section').forEach(initSection);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
