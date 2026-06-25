/**
 * Solution Timeline — Dynamic snake SVG + GSAP ScrollTrigger
 * Adapted from process_timeline.js — uses slt- prefix, dark (#111319) bg.
 *
 * Mobile fix: DOM restructuring always runs; GSAP animation is optional.
 * Without GSAP the layout is still correct (dot | pill + desc column).
 */

(function () {
  'use strict';

  function getCenter(el, container) {
    var elRect        = el.getBoundingClientRect();
    var containerRect = container.getBoundingClientRect();
    return {
      x: elRect.left + elRect.width  / 2 - containerRect.left,
      y: elRect.top  + elRect.height / 2 - containerRect.top,
    };
  }

  function buildSnakePath(points, W, tailLen) {
    if (!points.length) return '';

    var K   = 0.5523;
    tailLen = tailLen || 150;

    var R_RIGHT       = W * 0.0375;
    var R_RIGHT_ENTRY = W * 0.962;
    var L_TURN_ENTRY  = W * 0.111;
    var TAIL_X        = W * 0.916;

    var rows = [];
    points.forEach(function (pt) {
      if (!rows[pt.rowIndex]) rows[pt.rowIndex] = [];
      rows[pt.rowIndex].push(pt);
    });

    var numRows = rows.length;
    var d = '';

    for (var ri = 0; ri < numRows; ri++) {
      var row    = rows[ri];
      var rowY   = row[0].y;
      var isLast = (ri === numRows - 1);

      if (ri === 0) {
        d = 'M 0 ' + rowY;
        row.forEach(function (pt) { d += ' L ' + pt.x + ' ' + pt.y; });
      } else {
        row.forEach(function (pt) { d += ' L ' + pt.x + ' ' + pt.y; });
      }

      if (isLast) {
        var R_TAIL    = R_RIGHT;
        var KT        = K * R_TAIL;
        var arcEntryX = TAIL_X - R_TAIL;

        d += ' L ' + arcEntryX + ' ' + rowY;
        d += ' C ' + (arcEntryX + KT)    + ' ' + rowY
           + ', '  + TAIL_X              + ' ' + (rowY + R_TAIL - KT)
           + ', '  + TAIL_X              + ' ' + (rowY + R_TAIL);
        d += ' L ' + TAIL_X + ' ' + (rowY + tailLen);

      } else {
        var nextRow  = rows[ri + 1];
        var nextRowY = nextRow[0].y;
        var goRight  = (ri % 2 === 0);

        if (goRight) {
          var rr  = R_RIGHT;
          var krr = K * rr;
          var connY = rowY + 2 * rr;
          var rl  = Math.max((nextRowY - connY) / 2, R_RIGHT * 0.5);
          var krl = K * rl;

          d += ' L ' + R_RIGHT_ENTRY + ' ' + rowY;
          d += ' C ' + (R_RIGHT_ENTRY + krr) + ' ' + rowY
             + ', '  + (R_RIGHT_ENTRY + rr)  + ' ' + (rowY + rr - krr)
             + ', '  + (R_RIGHT_ENTRY + rr)  + ' ' + (rowY + rr);
          d += ' C ' + (R_RIGHT_ENTRY + rr)  + ' ' + (rowY + rr + krr)
             + ', '  + (R_RIGHT_ENTRY + krr) + ' ' + connY
             + ', '  + R_RIGHT_ENTRY          + ' ' + connY;
          d += ' L ' + L_TURN_ENTRY + ' ' + connY;
          d += ' C ' + (L_TURN_ENTRY - krl) + ' ' + connY
             + ', '  + (L_TURN_ENTRY - rl)  + ' ' + (connY + rl - krl)
             + ', '  + (L_TURN_ENTRY - rl)  + ' ' + (connY + rl);
          d += ' C ' + (L_TURN_ENTRY - rl)  + ' ' + (connY + rl + krl)
             + ', '  + (L_TURN_ENTRY - krl) + ' ' + nextRowY
             + ', '  + L_TURN_ENTRY          + ' ' + nextRowY;
          d += ' L ' + nextRow[0].x + ' ' + nextRowY;

        } else {
          var rr2    = R_RIGHT;
          var krr2   = K * rr2;
          var connY2 = rowY + 2 * rr2;
          var rl2    = Math.max((nextRowY - connY2) / 2, R_RIGHT * 0.5);
          var krl2   = K * rl2;
          var leftEdge  = W - R_RIGHT_ENTRY;
          var rightStop = W - L_TURN_ENTRY;

          d += ' L ' + leftEdge + ' ' + rowY;
          d += ' C ' + (leftEdge - krr2) + ' ' + rowY
             + ', '  + (leftEdge - rr2)  + ' ' + (rowY + rr2 - krr2)
             + ', '  + (leftEdge - rr2)  + ' ' + (rowY + rr2);
          d += ' C ' + (leftEdge - rr2)  + ' ' + (rowY + rr2 + krr2)
             + ', '  + (leftEdge - krr2) + ' ' + connY2
             + ', '  + leftEdge           + ' ' + connY2;
          d += ' L ' + rightStop + ' ' + connY2;
          d += ' C ' + (rightStop + krl2) + ' ' + connY2
             + ', '  + (rightStop + rl2)  + ' ' + (connY2 + rl2 - krl2)
             + ', '  + (rightStop + rl2)  + ' ' + (connY2 + rl2);
          d += ' C ' + (rightStop + rl2)  + ' ' + (connY2 + rl2 + krl2)
             + ', '  + (rightStop + krl2) + ' ' + nextRowY
             + ', '  + rightStop           + ' ' + nextRowY;
          d += ' L ' + nextRow[0].x + ' ' + nextRowY;
        }
      }
    }

    return d;
  }

  function findProgressForPoint(pathEl, point) {
    var totalLength = pathEl.getTotalLength();
    var bestT = 0, bestDist = Infinity;
    var steps = 300;
    for (var i = 0; i <= steps; i++) {
      var t    = (i / steps) * totalLength;
      var p    = pathEl.getPointAtLength(t);
      var dist = Math.hypot(p.x - point.x, p.y - point.y);
      if (dist < bestDist) { bestDist = dist; bestT = t; }
    }
    var lo = Math.max(0, bestT - totalLength / steps);
    var hi = Math.min(totalLength, bestT + totalLength / steps);
    for (var j = 0; j < 20; j++) {
      var mid = (lo + hi) / 2;
      var pLo = pathEl.getPointAtLength(lo);
      var pHi = pathEl.getPointAtLength(hi);
      if (Math.hypot(pLo.x - point.x, pLo.y - point.y) <
          Math.hypot(pHi.x - point.x, pHi.y - point.y)) {
        hi = mid;
      } else {
        lo = mid;
      }
    }
    return ((lo + hi) / 2) / totalLength;
  }

  function initTimeline(section) {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      console.warn('[SolutionTimeline] GSAP or ScrollTrigger not loaded.');
      section.querySelectorAll('.slt-milestone__pill, .slt-milestone__desc').forEach(function (el) {
        el.style.opacity   = '1';
        el.style.transform = 'none';
      });
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    var timelineEl = section.querySelector('[data-slt-timeline]');
    var svgEl      = section.querySelector('.slt-svg');
    var pathBase   = section.querySelector('.slt-path--base');
    var pathProg   = section.querySelector('.slt-path--progress');
    var rows       = Array.prototype.slice.call(section.querySelectorAll('.slt-row'));

    if (!timelineEl || !svgEl || !pathBase || !pathProg || !rows.length) return;

    var orderedMilestones = [];
    rows.forEach(function (rowEl, rowIdx) {
      Array.prototype.slice.call(rowEl.querySelectorAll('[data-slt-milestone]'))
        .forEach(function (m) {
          orderedMilestones.push({ el: m, rowIndex: rowIdx });
        });
    });

    function buildAndAnimate() {
      var W = timelineEl.offsetWidth;
      var H = timelineEl.offsetHeight;

      var sectionPb = parseFloat(getComputedStyle(section).paddingBottom) || 100;
      var TAIL_LEN  = section.offsetHeight - sectionPb - timelineEl.offsetTop - H + 20;
      TAIL_LEN      = Math.max(TAIL_LEN, 400);

      svgEl.setAttribute('width',  W);
      svgEl.setAttribute('height', H + TAIL_LEN);

      var clipH  = section.offsetHeight - timelineEl.offsetTop;
      var clipId = 'slt-clip-' + section.id;
      var existingClip = svgEl.querySelector('clipPath');
      if (existingClip) existingClip.parentNode.removeChild(existingClip);
      var ns      = 'http://www.w3.org/2000/svg';
      var clipEl  = document.createElementNS(ns, 'clipPath');
      clipEl.setAttribute('id', clipId);
      var clipRect = document.createElementNS(ns, 'rect');
      clipRect.setAttribute('x', 0);
      clipRect.setAttribute('y', 0);
      clipRect.setAttribute('width', W);
      clipRect.setAttribute('height', clipH);
      clipEl.appendChild(clipRect);
      svgEl.insertBefore(clipEl, svgEl.firstChild);
      pathBase.setAttribute('clip-path', 'url(#' + clipId + ')');
      pathProg.setAttribute('clip-path', 'url(#' + clipId + ')');

      var points = orderedMilestones.map(function (item) {
        var pill   = item.el.querySelector('.slt-milestone__pill');
        var center = getCenter(pill, timelineEl);
        return { x: center.x, y: center.y, rowIndex: item.rowIndex };
      });

      var d = buildSnakePath(points, W, TAIL_LEN);
      pathBase.setAttribute('d', d);
      pathProg.setAttribute('d', d);

      var totalLength = pathProg.getTotalLength();
      pathProg.style.strokeDasharray  = totalLength;
      pathProg.style.strokeDashoffset = totalLength;

      var milestoneProgressions = points.map(function (pt) {
        return findProgressForPoint(pathProg, pt);
      });

      var tl = gsap.timeline({
        scrollTrigger: {
          trigger: section,
          start:   'top 80%',
          once:    true,
        },
      });

      tl.to(pathProg, {
        strokeDashoffset: 0,
        duration: 3.5,
        ease:     'power1.inOut',
      }, 0);

      orderedMilestones.forEach(function (item, idx) {
        var pill = item.el.querySelector('.slt-milestone__pill');
        var desc = item.el.querySelector('.slt-milestone__desc');
        var at   = milestoneProgressions[idx] * 3.5;

        tl.to(pill, {
          opacity:  1,
          scale:    1,
          duration: 0.5,
          ease:     'power2.out',
          onStart: function () { pill.classList.add('is-active'); },
        }, at);

        if (desc) {
          tl.to(desc, {
            opacity:  1,
            y:        0,
            duration: 0.5,
            ease:     'power2.out',
          }, at + 0.1);
        }
      });
    }

    buildAndAnimate();

    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        ScrollTrigger.getAll().forEach(function (st) {
          if (st.vars && st.vars.trigger === section) st.kill();
        });
        buildAndAnimate();
      }, 250);
    }, { passive: true });
  }


  // ── Mobile init ───────────────────────────────────────────────────────────
  // IMPORTANT: DOM restructuring always runs first (regardless of GSAP).
  // This ensures correct layout even without GSAP.

  function initMobile(section) {
    var timelineEl = section.querySelector('[data-slt-timeline]');
    var milestones = Array.prototype.slice.call(section.querySelectorAll('[data-slt-milestone]'));
    if (!timelineEl || !milestones.length) return;

    // ── Step 1: Always inject progress line ──────────────────────────────────
    var lineProgress = document.createElement('div');
    lineProgress.className = 'slt-timeline__line-progress';
    timelineEl.insertBefore(lineProgress, timelineEl.firstChild);

    // ── Step 2: Always restructure DOM → [mob-dot | content(pill + desc)] ───
    milestones.forEach(function (ms) {
      var pill = ms.querySelector('.slt-milestone__pill');
      var desc = ms.querySelector('.slt-milestone__desc');

      var content = document.createElement('div');
      content.className = 'slt-milestone__content';
      if (pill) content.appendChild(pill);
      if (desc) content.appendChild(desc);

      var dotWrap = document.createElement('div');
      dotWrap.className = 'slt-milestone__mob-dot';
      dotWrap.innerHTML =
        '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">' +
          '<circle cx="6" cy="6" r="6" fill="#111319"/>' +
          '<circle cx="6" cy="6" r="5.5" stroke="white" stroke-opacity="0.2"/>' +
        '</svg>';

      ms.innerHTML = '';
      ms.appendChild(dotWrap);
      ms.appendChild(content);
    });

    // ── Step 3: No GSAP → show everything immediately and exit ───────────────
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      section.querySelectorAll('.slt-milestone__pill, .slt-milestone__desc').forEach(function (el) {
        el.style.opacity   = '1';
        el.style.transform = 'none';
      });
      lineProgress.style.transform = 'scaleY(1)';
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    // ── Step 4: GSAP timeline animation ──────────────────────────────────────
    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start:   'top 80%',
        once:    true,
      },
    });

    var total   = milestones.length;
    var lineDur = total * 0.6;

    tl.to(lineProgress, {
      scaleY:   1,
      duration: lineDur,
      ease:     'power1.inOut',
    }, 0);

    var lineRect = lineProgress.getBoundingClientRect();
    var lineH    = lineProgress.offsetHeight;

    milestones.forEach(function (ms) {
      var dot  = ms.querySelector('.slt-milestone__mob-dot svg');
      var pill = ms.querySelector('.slt-milestone__pill');
      var desc = ms.querySelector('.slt-milestone__desc');

      var dotEl      = ms.querySelector('.slt-milestone__mob-dot');
      var dotRect    = dotEl.getBoundingClientRect();
      var dotCenterY = dotRect.top + 6 - lineRect.top;
      var frac = lineH > 0 ? Math.min(Math.max(dotCenterY / lineH, 0), 1) : 0;
      var at   = frac * lineDur;

      tl.to(dot.querySelector('circle:first-child'), {
        attr: { fill: '#ffffff' },
        duration: 0.25,
        ease: 'power2.out',
      }, at);

      tl.to(pill, {
        opacity:  1,
        scale:    1,
        duration: 0.35,
        ease:     'power2.out',
        onStart: function () { pill.classList.add('is-active'); },
      }, at);

      if (desc) {
        tl.to(desc, {
          opacity:  1,
          y:        0,
          duration: 0.4,
          ease:     'power2.out',
        }, at + 0.1);
      }
    });
  }

  function isMobile() {
    return window.innerWidth < 1024;
  }

  function init() {
    document.querySelectorAll('.slt-section').forEach(function (section) {
      if (isMobile()) {
        initMobile(section);
      } else {
        initTimeline(section);
      }
    });
  }

  // Reload when crossing the mobile/desktop breakpoint (DevTools resize testing).
  var wasMob = isMobile();
  var bpTimer;
  window.addEventListener('resize', function () {
    clearTimeout(bpTimer);
    bpTimer = setTimeout(function () {
      var nowMob = isMobile();
      if (nowMob !== wasMob) {
        wasMob = nowMob;
        window.location.reload();
      }
    }, 300);
  }, { passive: true });

  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(init);
  } else if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    setTimeout(init, 100);
  }

})();
