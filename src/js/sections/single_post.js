(function () {
    'use strict';

    var DOT_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none"><circle cx="3" cy="3" r="3" fill="#C83030"/></svg>';

    var PLAY_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M15.8462 8.07527C17.3846 8.9307 17.3846 11.0693 15.8462 11.9247L5.46154 17.6989C3.92308 18.5544 2 17.4851 2 15.7742L2 4.22581C2 2.51494 3.92308 1.44564 5.46154 2.30107L15.8462 8.07527Z" fill="#111319"/></svg>';

    function initVideos() {
        var videos = document.querySelectorAll('.sp-content .wp-block-video video');
        videos.forEach( function ( video ) {
            var wrap = video.closest('.wp-block-video');
            if ( !wrap ) return;

            var btn = document.createElement('button');
            btn.className   = 'as-play-btn';
            btn.innerHTML   = PLAY_SVG;
            btn.setAttribute('aria-label', 'Play video');
            wrap.appendChild(btn);

            video.removeAttribute('controls');

            btn.addEventListener('click', function () {
                video.setAttribute('controls', '');
                video.play();
                btn.classList.add('is-hidden');
            });

            video.addEventListener('pause', function () {
                if ( video.currentTime > 0 ) {
                    btn.classList.remove('is-hidden');
                }
            });

            video.addEventListener('ended', function () {
                btn.classList.remove('is-hidden');
            });
        });
    }

    function init() {
        initVideos();

        var tocWrap   = document.getElementById('sp-toc');
        var tocToggle = document.querySelector('.sp-toc__toggle');
        if (tocWrap && tocToggle) {
            tocToggle.addEventListener('click', function () {
                var isOpen = tocWrap.classList.toggle('is-open');
                tocToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        var content = document.getElementById('sp-content');
        var tocNav  = document.getElementById('sp-toc-nav');
        if (!content || !tocNav || !tocWrap) return;

        var headings = Array.prototype.slice.call(
            content.querySelectorAll('h2')
        );

        if (!headings.length) {
            tocWrap.style.display = 'none';
            return;
        }

        headings.forEach(function (h, i) {
            if (!h.id) {
                h.id = 'section-' + i + '-' + h.textContent
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '')
                    .substring(0, 40);
            }
        });

        var links = headings.map(function (h) {
            var a = document.createElement('a');
            a.href      = '#' + h.id;
            a.className = 'sp-toc__item';
            a.setAttribute('data-target', h.id);
            a.innerHTML = '<span class="sp-toc__dot">' + DOT_SVG + '</span>' + h.textContent;

            a.addEventListener('click', function (e) {
                e.preventDefault();
                var target = document.getElementById(h.id);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });

            tocNav.appendChild(a);
            return a;
        });

        var thumb = tocWrap.querySelector('.sp-toc__scrollbar-thumb');
        var navEl = tocNav;

        function updateScrollbar() {
            if (!thumb || !navEl) return;
            var ratio  = navEl.scrollTop / (navEl.scrollHeight - navEl.clientHeight || 1);
            var maxTop = navEl.clientHeight - 80;
            thumb.style.top = Math.round(ratio * maxTop) + 'px';
        }

        navEl.addEventListener('scroll', updateScrollbar);

        var OFFSET = 120;

        function onScroll() {
            var scrollY = window.scrollY || window.pageYOffset;
            var active  = -1;

            headings.forEach(function (h, i) {
                if (h.getBoundingClientRect().top + scrollY - OFFSET <= scrollY) {
                    active = i;
                }
            });

            links.forEach(function (link, i) {
                link.classList.toggle('is-active', i === active);
            });

            if (active >= 0 && links[active]) {
                var linkEl    = links[active];
                var linkTop   = linkEl.offsetTop;
                var linkH     = linkEl.offsetHeight;
                var navH      = navEl.clientHeight;
                var navScroll = navEl.scrollTop;

                if (linkTop < navScroll || linkTop + linkH > navScroll + navH) {
                    navEl.scrollTo({ top: linkTop - navH / 2, behavior: 'smooth' });
                }
            }

            updateScrollbar();
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
