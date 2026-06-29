'use strict';

const js = require('@eslint/js');

module.exports = [
    js.configs.recommended,
    {
        languageOptions: {
            ecmaVersion: 2020,
            sourceType: 'script',
            globals: {
                document: 'readonly',
                window: 'readonly',
                navigator: 'readonly',
                console: 'readonly',
                fetch: 'readonly',
                MutationObserver: 'readonly',
                DOMParser: 'readonly',
                IntersectionObserver: 'readonly',
                requestAnimationFrame: 'readonly',
                cancelAnimationFrame: 'readonly',
                setTimeout: 'readonly',
                clearTimeout: 'readonly',
                setInterval: 'readonly',
                clearInterval: 'readonly',
                getComputedStyle: 'readonly',
                localStorage: 'readonly',
                sessionStorage: 'readonly',
                FormData: 'readonly',
                history: 'readonly',
                location: 'readonly',
                URLSearchParams: 'readonly',
                Swiper: 'readonly',
                gsap: 'readonly',
                ScrollTrigger: 'readonly',
            },
        },
        rules: {
            // console.error() is used intentionally in replaceImagesWithInlineSVGs()
            // to report SVG fetch failures — not a debugging leftover
            'no-console': 'off',
            // catch (e) {} is an intentional silent-fail pattern throughout
            'no-unused-vars': ['error', { caughtErrors: 'none' }],
            'no-empty': ['error', { allowEmptyCatch: true }],
        },
    },
];
