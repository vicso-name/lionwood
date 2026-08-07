document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.sri-section');
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

    revealOnScroll(section.querySelector('.sri-heading'), 0.15);
    revealOnScroll(section.querySelector('.sri-banner'), 0.25);
});
