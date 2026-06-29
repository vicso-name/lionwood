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

    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }
        // execCommand fallback — must run before window.open (needs page focus)
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta);
        return Promise.resolve();
    }

    function initAiChips() {
        var chips = document.querySelectorAll('.sp-ai__chip[data-url]');
        if (!chips.length) return;

        Array.prototype.forEach.call(chips, function (btn) {
            btn.addEventListener('click', function () {
                var url    = btn.dataset.url;
                var prompt = btn.dataset.prompt;
                if (!url) return;

                // Copy first (execCommand needs page focus, before window.open)
                copyToClipboard(prompt).then(function () {
                    btn.classList.add('sp-ai__chip--copied');
                    setTimeout(function () {
                        btn.classList.remove('sp-ai__chip--copied');
                    }, 2000);
                });

                // Open with ?q= param: works natively in Perplexity, ChatGPT, and Google AI Mode (udm=50).
                var sep     = url.indexOf('?') === -1 ? '?' : '&';
                var fullUrl = url + sep + 'q=' + encodeURIComponent(prompt);
                window.open(fullUrl, '_blank', 'noopener,noreferrer');
            });
        });
    }

    // ── Subscribe popup ───────────────────────────────────────────────────────────

    function initSubscribePopup() {
        var popup     = document.getElementById('sp-popup');
        if (!popup) return;

        var form      = popup.querySelector('[data-sp-popup-form]');
        var success   = popup.querySelector('[data-sp-popup-success]');
        var submitBtn = popup.querySelector('[data-sp-popup-submit]');
        var errorEl   = popup.querySelector('[data-sp-popup-error]');
        var openBtns  = document.querySelectorAll('[data-sp-subscribe-open]');
        var closeBtns = popup.querySelectorAll('[data-sp-popup-close]');

        var portalId  = form ? form.getAttribute('data-hs-portal') || '' : '';
        var formId    = form ? form.getAttribute('data-hs-form')   || '' : '';

        var inputName  = form ? form.querySelector('[name="full_name"]') : null;
        var inputEmail = form ? form.querySelector('[name="email"]')     : null;

        // ── Open / close ──────────────────────────────────────────────────────

        function openPopup() {
            popup.hidden = false;
            document.body.classList.add('has-popup');
            if (inputName) setTimeout(function () { inputName.focus(); }, 80);
        }

        function closePopup() {
            popup.hidden = true;
            document.body.classList.remove('has-popup');
        }

        Array.prototype.forEach.call(openBtns, function (btn) {
            btn.addEventListener('click', openPopup);
        });

        Array.prototype.forEach.call(closeBtns, function (btn) {
            btn.addEventListener('click', closePopup);
        });

        document.addEventListener('keydown', function (e) {
            if (!popup.hidden && e.key === 'Escape') closePopup();
        });

        // ── Validation ────────────────────────────────────────────────────────

        function isEmail(v) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
        }

        function validate() {
            var errors = [];
            if (inputName) inputName.classList.remove('is-invalid');
            if (inputEmail) inputEmail.classList.remove('is-invalid');

            if (!inputName || !inputName.value.trim()) {
                if (inputName) inputName.classList.add('is-invalid');
                errors.push('Please enter your full name.');
            }
            if (!inputEmail || !inputEmail.value.trim() || !isEmail(inputEmail.value.trim())) {
                if (inputEmail) inputEmail.classList.add('is-invalid');
                errors.push('Please enter a valid business email.');
            }
            return errors;
        }

        // ── UI helpers ────────────────────────────────────────────────────────

        var origLabel = submitBtn ? submitBtn.textContent : '';

        function setLoading(on) {
            if (!submitBtn) return;
            submitBtn.classList.toggle('is-loading', on);
            submitBtn.disabled = on;
            submitBtn.textContent = on ? 'Sending…' : origLabel;
        }

        function showError(msg) {
            if (!errorEl) return;
            errorEl.textContent = msg;
            errorEl.hidden = false;
        }

        function hideError() {
            if (!errorEl) return;
            errorEl.hidden = true;
            errorEl.textContent = '';
        }

        function showSuccess() {
            if (form) form.hidden = true;
            if (success) success.hidden = false;
        }

        if (inputName)  inputName.addEventListener('input',  function () { inputName.classList.remove('is-invalid');  hideError(); });
        if (inputEmail) inputEmail.addEventListener('input', function () { inputEmail.classList.remove('is-invalid'); hideError(); });

        // ── HubSpot submission ────────────────────────────────────────────────

        function submitToHubspot(name, email) {
            if (!portalId || !formId) {
                return Promise.resolve({ success: true });
            }
            var endpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/'
                         + portalId + '/' + formId;
            var payload = {
                fields: [
                    { name: 'firstname', value: name },
                    { name: 'email',     value: email },
                ],
                context: {
                    pageUri:  window.location.href,
                    pageName: document.title,
                },
            };
            return fetch(endpoint, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(payload),
            }).then(function (r) {
                if (!r.ok) return r.json().then(function (d) {
                    return { success: false, message: d.message || 'HubSpot error.' };
                });
                return { success: true };
            });
        }

        // ── Submit ────────────────────────────────────────────────────────────

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                hideError();

                var errors = validate();
                if (errors.length) { showError(errors[0]); return; }

                setLoading(true);

                var name  = inputName.value.trim();
                var email = inputEmail.value.trim();

                submitToHubspot(name, email)
                    .then(function (res) {
                        setLoading(false);
                        if (res && res.success) {
                            showSuccess();
                        } else {
                            showError(res.message || 'Something went wrong. Please try again.');
                        }
                    })
                    .catch(function () {
                        setLoading(false);
                        showError('Network error. Please try again.');
                    });
            });
        }
    }

    function init() {
        initVideos();
        initAiChips();
        initSubscribePopup();

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
