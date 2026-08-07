/**
 * Career Possibilities — mobile Swiper (no pagination, no nav)
 * File: blocks/career-possibilities/career-possibilities.js
 */

(function () {
    'use strict';

    function init() {
        var sliders = document.querySelectorAll('[data-cp-slider]');
        if ( !sliders.length || typeof Swiper === 'undefined' ) return;

        sliders.forEach( function ( el ) {
            new Swiper( el, {
                slidesPerView:  'auto',
                spaceBetween:   16,
                grabCursor:     true,
                loop:           false,
                pagination:     false,
                navigation:     false,
            } );
        } );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }

})();
