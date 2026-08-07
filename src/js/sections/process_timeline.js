/**
 * Process Timeline — Dynamic snake SVG + GSAP ScrollTrigger
 *
 * Path geometry derived from Figma SVG:
 *  - Right U-turn radius = fixed R_RIGHT (W * 0.0375)
 *  - Connector Y after right turn = rowY + 2 * R_RIGHT
 *  - Left U-turn radius = dynamic: (nextRowY - connectorY) / 2
 *    so the exit lands exactly on next row pill Y
 *  - Left turn entry at W * 0.111 from left
 *  - Tail drops at W * 0.916
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

    // Fixed proportions from Figma (1360px reference)
    var R_RIGHT       = W * 0.0375; // right U-turn radius ~51px
    var R_RIGHT_ENTRY = W * 0.962;  // x where line enters right turn
    var L_TURN_ENTRY  = W * 0.111;  // x where connector ends on left side
    var TAIL_X        = W * 0.916;  // x of vertical tail

    // Group points by row
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
        // Start at left edge, row 0 pill Y
        d = 'M 0 ' + rowY;
        row.forEach(function (pt) { d += ' L ' + pt.x + ' ' + pt.y; });
      } else {
        // Path arrives here from U-turn; draw through row pills
        row.forEach(function (pt) { d += ' L ' + pt.x + ' ' + pt.y; });
      }

      if (isLast) {
        // Tail: quarter-circle arc right-down, then vertical drop
        var R_TAIL  = R_RIGHT; // same radius as right U-turn (~51px)
        var KT      = K * R_TAIL;
        var arcEntryX = TAIL_X - R_TAIL;

        // Horizontal to arc entry
        d += ' L ' + arcEntryX + ' ' + rowY;

        // Quarter-circle: horiz→vert (going right then down)
        d += ' C ' + (arcEntryX + KT)    + ' ' + rowY
           + ', '  + TAIL_X              + ' ' + (rowY + R_TAIL - KT)
           + ', '  + TAIL_X              + ' ' + (rowY + R_TAIL);

        // Vertical tail down
        d += ' L ' + TAIL_X + ' ' + (rowY + tailLen);

      } else {
        var nextRow  = rows[ri + 1];
        var nextRowY = nextRow[0].y;
        var goRight  = (ri % 2 === 0);

        if (goRight) {
          // ── Right U-turn ─────────────────────────────────────────────────
          var rr  = R_RIGHT;
          var krr = K * rr;

          // Connector sits at this Y after the right turn
          var connY = rowY + 2 * rr;

          // Left U-turn radius: dynamic so exit lands exactly at nextRowY
          // Minimum = half of R_RIGHT to avoid overly sharp corners with many rows
          var rl  = Math.max((nextRowY - connY) / 2, R_RIGHT * 0.5);
          var krl = K * rl;

          // 1. Extend to right turn entry
          d += ' L ' + R_RIGHT_ENTRY + ' ' + rowY;

          // 2. Right U-turn: horiz→vert
          d += ' C ' + (R_RIGHT_ENTRY + krr) + ' ' + rowY
             + ', '  + (R_RIGHT_ENTRY + rr)  + ' ' + (rowY + rr - krr)
             + ', '  + (R_RIGHT_ENTRY + rr)  + ' ' + (rowY + rr);

          // 3. Right U-turn: vert→horiz
          d += ' C ' + (R_RIGHT_ENTRY + rr)  + ' ' + (rowY + rr + krr)
             + ', '  + (R_RIGHT_ENTRY + krr) + ' ' + connY
             + ', '  + R_RIGHT_ENTRY          + ' ' + connY;

          // 4. Horizontal connector going left
          d += ' L ' + L_TURN_ENTRY + ' ' + connY;

          // 5. Left U-turn: horiz→vert (going left, turning down)
          d += ' C ' + (L_TURN_ENTRY - krl) + ' ' + connY
             + ', '  + (L_TURN_ENTRY - rl)  + ' ' + (connY + rl - krl)
             + ', '  + (L_TURN_ENTRY - rl)  + ' ' + (connY + rl);

          // 6. Left U-turn: vert→horiz (going down, turning right)
          d += ' C ' + (L_TURN_ENTRY - rl)  + ' ' + (connY + rl + krl)
             + ', '  + (L_TURN_ENTRY - krl) + ' ' + nextRowY
             + ', '  + L_TURN_ENTRY          + ' ' + nextRowY;

          // 7. Continue to first pill of next row
          d += ' L ' + nextRow[0].x + ' ' + nextRowY;

        } else {
          // ── Left U-turn (odd→even rows) ───────────────────────────────────
          var rr2  = R_RIGHT;
          var krr2 = K * rr2;
          var connY2 = rowY + 2 * rr2;
          var rl2  = Math.max((nextRowY - connY2) / 2, R_RIGHT * 0.5);
          var krl2 = K * rl2;
          var leftEdge  = W - R_RIGHT_ENTRY; // mirror of R_RIGHT_ENTRY
          var rightStop = W - L_TURN_ENTRY;  // mirror of L_TURN_ENTRY

          // 1. Extend to left turn entry
          d += ' L ' + leftEdge + ' ' + rowY;

          // 2. Left U-turn: horiz→vert
          d += ' C ' + (leftEdge - krr2) + ' ' + rowY
             + ', '  + (leftEdge - rr2)  + ' ' + (rowY + rr2 - krr2)
             + ', '  + (leftEdge - rr2)  + ' ' + (rowY + rr2);

          // 3. Left U-turn: vert→horiz
          d += ' C ' + (leftEdge - rr2)  + ' ' + (rowY + rr2 + krr2)
             + ', '  + (leftEdge - krr2) + ' ' + connY2
             + ', '  + leftEdge           + ' ' + connY2;

          // 4. Horizontal connector going right
          d += ' L ' + rightStop + ' ' + connY2;

          // 5. Right U-turn: horiz→vert
          d += ' C ' + (rightStop + krl2) + ' ' + connY2
             + ', '  + (rightStop + rl2)  + ' ' + (connY2 + rl2 - krl2)
             + ', '  + (rightStop + rl2)  + ' ' + (connY2 + rl2);

          // 6. Right U-turn: vert→horiz
          d += ' C ' + (rightStop + rl2)  + ' ' + (connY2 + rl2 + krl2)
             + ', '  + (rightStop + krl2) + ' ' + nextRowY
             + ', '  + rightStop           + ' ' + nextRowY;

          // 7. Continue to first pill of next row
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
      console.warn('[ProcessTimeline] GSAP or ScrollTrigger not loaded.');
      section.querySelectorAll('.pt-milestone__pill, .pt-milestone__desc').forEach(function (el) {
        el.style.opacity   = '1';
        el.style.transform = 'none';
      });
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    var timelineEl = section.querySelector('[data-pt-timeline]');
    var svgEl      = section.querySelector('.pt-svg');
    var pathBase   = section.querySelector('.pt-path--base');
    var pathProg   = section.querySelector('.pt-path--progress');
    var rows       = Array.prototype.slice.call(section.querySelectorAll('.pt-row'));

    if (!timelineEl || !svgEl || !pathBase || !pathProg || !rows.length) return;

    var orderedMilestones = [];
    rows.forEach(function (rowEl, rowIdx) {
      Array.prototype.slice.call(rowEl.querySelectorAll('[data-pt-milestone]'))
        .forEach(function (m) {
          orderedMilestones.push({ el: m, rowIndex: rowIdx });
        });
    });

    function buildAndAnimate() {
      var W = timelineEl.offsetWidth;
      var H = timelineEl.offsetHeight;

      // Tail: reach bottom of section content (minus paddingBottom)
      var sectionPb = parseFloat(getComputedStyle(section).paddingBottom) || 100;
      var TAIL_LEN  = section.offsetHeight - sectionPb - timelineEl.offsetTop - H + 20;
      TAIL_LEN      = Math.max(TAIL_LEN, 400);

      svgEl.setAttribute('width',  W);
      svgEl.setAttribute('height', H + TAIL_LEN);

      // Clip SVG to section content area so tail doesn't bleed into next section
      var clipH = section.offsetHeight - timelineEl.offsetTop;
      var clipId = 'pt-clip-' + section.id;
      var existingClip = svgEl.querySelector('clipPath');
      if (existingClip) existingClip.parentNode.removeChild(existingClip);
      var ns = 'http://www.w3.org/2000/svg';
      var clipEl = document.createElementNS(ns, 'clipPath');
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
        var pill   = item.el.querySelector('.pt-milestone__pill');
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
        var pill = item.el.querySelector('.pt-milestone__pill');
        var desc = item.el.querySelector('.pt-milestone__desc');
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

  function initMobile(section) {
    var timelineEl  = section.querySelector('[data-pt-timeline]');
    var milestones  = Array.prototype.slice.call(section.querySelectorAll('[data-pt-milestone]'));
    if (!timelineEl || !milestones.length) return;

    // Inject progress line element
    var lineProgress = document.createElement('div');
    lineProgress.className = 'pt-timeline__line-progress';
    timelineEl.insertBefore(lineProgress, timelineEl.firstChild);

    // Inject mobile dot SVG into each milestone
    milestones.forEach(function (ms) {
      // Wrap pill + desc in .pt-milestone__content
      var pill = ms.querySelector('.pt-milestone__pill');
      var desc = ms.querySelector('.pt-milestone__desc');

      var content = document.createElement('div');
      content.className = 'pt-milestone__content';
      if (pill) content.appendChild(pill);
      if (desc) content.appendChild(desc);

      // Create dot
      var dotWrap = document.createElement('div');
      dotWrap.className = 'pt-milestone__mob-dot';
      dotWrap.innerHTML =
        '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">' +
          '<circle cx="6" cy="6" r="6" fill="#C83030"/>' +
          '<circle cx="6" cy="6" r="5.5" stroke="white" stroke-opacity="0.2"/>' +
        '</svg>';

      ms.innerHTML = '';
      ms.appendChild(dotWrap);
      ms.appendChild(content);
    });

    // GSAP timeline — scroll-triggered
    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start:   'top 80%',
        once:    true,
      },
    });

    var total    = milestones.length;
    var lineDur  = total * 0.6; // total line animation duration

    // Animate progress line scaleY 0→1 over full duration
    tl.to(lineProgress, {
      scaleY:   1,
      duration: lineDur,
      ease:     'power1.inOut',
    }, 0);

    // Activate each milestone exactly when line reaches its dot.
    // Line height = lineProgress.offsetHeight (set after insert).
    // Dot center Y relative to lineProgress top = dot.getBoundingClientRect().top
    //   - lineProgress.getBoundingClientRect().top + 6 (half dot size).
    // Fraction along line = dotCenterY / lineHeight.
    // Time = fraction * lineDur.
    var lineRect = lineProgress.getBoundingClientRect();
    var lineH    = lineProgress.offsetHeight;

    milestones.forEach(function (ms, idx) {
      var dot  = ms.querySelector('.pt-milestone__mob-dot svg');
      var pill = ms.querySelector('.pt-milestone__pill');
      var desc = ms.querySelector('.pt-milestone__desc');

      // Calculate fraction of line at this dot's center
      var dotEl   = ms.querySelector('.pt-milestone__mob-dot');
      var dotRect = dotEl.getBoundingClientRect();
      var dotCenterY = dotRect.top + 6 - lineRect.top; // 6 = half of 12px dot
      var frac = lineH > 0 ? Math.min(Math.max(dotCenterY / lineH, 0), 1) : idx / total;
      var at   = frac * lineDur;

      // Dot: fill white
      tl.to(dot.querySelector('circle:first-child'), {
        attr: { fill: '#ffffff' },
        duration: 0.25,
        ease: 'power2.out',
      }, at);

      // Pill appear
      tl.to(pill, {
        opacity:  1,
        scale:    1,
        duration: 0.35,
        ease:     'power2.out',
        onStart: function () { pill.classList.add('is-active'); },
      }, at);

      // Desc fade
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
    document.querySelectorAll('.pt-section').forEach(function (section) {
      if (isMobile()) {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
          // Fallback: show everything
          section.querySelectorAll('.pt-milestone__pill, .pt-milestone__desc').forEach(function (el) {
            el.style.opacity = '1';
            el.style.transform = 'none';
          });
          return;
        }
        gsap.registerPlugin(ScrollTrigger);
        initMobile(section);
      } else {
        initTimeline(section);
      }
    });
  }

  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(init);
  } else if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    setTimeout(init, 100);
  }

})();
