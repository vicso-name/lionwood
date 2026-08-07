document.addEventListener('DOMContentLoaded', () => {
    const MOBILE = () => window.innerWidth <= 992;
    const PILL_H = 56;

    function closeCard(card) {
        card.style.height = card.offsetHeight + 'px';
        requestAnimationFrame(() => {
            card.style.height = PILL_H + 'px';
        });
        card.classList.remove('is-open');
        card.querySelector('.footer-section__office-toggle')
            ?.setAttribute('aria-expanded', 'false');
    }

    function openCard(card) {
        const target = card.scrollHeight;
        card.classList.add('is-open');
        card.querySelector('.footer-section__office-toggle')
            ?.setAttribute('aria-expanded', 'true');
        card.style.height = PILL_H + 'px';
        requestAnimationFrame(() => {
            card.style.height = target + 'px';
            card.addEventListener('transitionend', () => {
                if (card.classList.contains('is-open')) {
                    card.style.height = 'auto';
                }
            }, { once: true });
        });
    }

    // Click the whole pill header (not just the toggle button)
    document.querySelectorAll('.footer-section__office').forEach(card => {
        const header = card.querySelector('.footer-section__office-header');
        if (!header) return;

        header.addEventListener('click', () => {
            if (!MOBILE()) return;

            const isOpen = card.classList.contains('is-open');
            document.querySelectorAll('.footer-section__office').forEach(c => closeCard(c));
            if (!isOpen) openCard(card);
        });
    });

    window.addEventListener('resize', () => {
        if (!MOBILE()) {
            document.querySelectorAll('.footer-section__office').forEach(c => {
                c.style.height = '';
                c.classList.remove('is-open');
                c.querySelector('.footer-section__office-toggle')
                    ?.setAttribute('aria-expanded', 'false');
            });
        }
    });
});
