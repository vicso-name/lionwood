/**
 * Article Slider — Swiper init
 * File: src/js/sections/article_slider.js
 */

(function () {
    'use strict';

    function initSlider( wrap ) {
        var sliderEl = wrap.querySelector('.as-slider');
        var btnPrevDesktop = wrap.querySelectorAll('.as-nav--desktop .as-btn--prev');
        var btnNextDesktop = wrap.querySelectorAll('.as-nav--desktop .as-btn--next');
        var btnPrevMobile  = wrap.querySelectorAll('.as-nav--mobile .as-btn--prev');
        var btnNextMobile  = wrap.querySelectorAll('.as-nav--mobile .as-btn--next');

        if ( !sliderEl || typeof Swiper === 'undefined' ) return;

        var swiper = new Swiper( sliderEl, {
            slidesPerView:  1,
            spaceBetween:   0,
            loop:           true,
            grabCursor:     true,
            pagination:     false,
            navigation:     false,
        });

        // Hook both desktop and mobile arrows to the same swiper instance
        function bindBtn( btns, direction ) {
            btns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    direction === 'prev' ? swiper.slidePrev() : swiper.slideNext();
                });
            });
        }

        bindBtn( btnPrevDesktop, 'prev' );
        bindBtn( btnNextDesktop, 'next' );
        bindBtn( btnPrevMobile,  'prev' );
        bindBtn( btnNextMobile,  'next' );
    }

    function init() {
        document.querySelectorAll('[data-as-slider]').forEach( initSlider );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }

})();
