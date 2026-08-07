document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.sps-section');
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

    revealOnScroll(section.querySelector('.sps-heading'), 0.15);
    revealOnScroll(section.querySelector('.sps-banner'), 0.25);
});
