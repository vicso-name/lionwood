document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.cta-section');
    if (!section) return;

    const revealOnScroll = (el, threshold = 0.2) => {
        if (!el) return;
        const io = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    el.classList.add('is-visible');
                    io.unobserve(el);
                }
            },
            { threshold }
        );
        io.observe(el);
    };

    revealOnScroll(section.querySelector('.cta-heading'), 0.15);
    revealOnScroll(section.querySelector('.cta-card'), 0.25);
});
