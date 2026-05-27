document.addEventListener('DOMContentLoaded', function () {
    var offices = document.querySelectorAll('.footer-section__office');
    if (!offices.length) return;

    function isMobile() {
        return window.innerWidth <= 768;
    }

    function closeAll() {
        offices.forEach(function (office) {
            office.classList.remove('is-open');
        });
    }

    offices.forEach(function (office) {
        var header = office.querySelector('.footer-section__office-header');
        if (!header) return;

        header.addEventListener('click', function () {
            if (!isMobile()) return;

            var isOpen = office.classList.contains('is-open');
            closeAll();

            if (!isOpen) {
                office.classList.add('is-open');
            }
        });
    });

    window.addEventListener('resize', function () {
        if (!isMobile()) {
            closeAll();
        }
    });
});
