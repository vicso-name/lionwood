document.addEventListener('DOMContentLoaded', () => {
    const MOBILE = () => window.innerWidth <= 768;

    document.querySelectorAll('.footer-section__office-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!MOBILE()) return;

            const card = btn.closest('.footer-section__office');
            const isOpen = card.classList.contains('is-open');

            document.querySelectorAll('.footer-section__office').forEach(c => {
                c.classList.remove('is-open');
                c.querySelector('.footer-section__office-toggle')
                    ?.setAttribute('aria-expanded', 'false');
            });

            if (!isOpen) {
                card.classList.add('is-open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    window.addEventListener('resize', () => {
        if (!MOBILE()) {
            document.querySelectorAll('.footer-section__office').forEach(c => {
                c.classList.remove('is-open');
                c.querySelector('.footer-section__office-toggle')
                    ?.setAttribute('aria-expanded', 'false');
            });
        }
    });
});
