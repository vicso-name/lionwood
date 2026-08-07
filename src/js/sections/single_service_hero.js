(function () {
  'use strict';

  function adaptImageFit(img, wrap) {
    var containerAR = wrap.offsetWidth / wrap.offsetHeight;
    var imageAR     = img.naturalWidth  / img.naturalHeight;
    if (imageAR > containerAR) {
      // wider than container: contain to avoid side-crop,
      // anchor to bottom so empty space goes above (off-screen) not below
      img.style.objectFit       = 'contain';
      img.style.objectPosition  = 'center bottom';
    } else {
      img.style.objectFit       = 'cover';
      img.style.objectPosition  = 'center top';
    }
  }

  function init() {
    var section = document.querySelector('.ssh-section');
    if (!section) return;

    section.querySelectorAll('.ssh-anim').forEach(function (el) {
      var delay = el.getAttribute('data-delay') || '0';
      el.style.setProperty('--anim-delay', delay + 'ms');
    });

    // only adapt fit for overflow style — centered has its own CSS object-fit
    if (section.classList.contains('ssh-section--overflow')) {
      var img  = section.querySelector('.ssh-image');
      var wrap = section.querySelector('.ssh-image-wrap');
      if (img && wrap) {
        if (img.complete && img.naturalWidth) {
          adaptImageFit(img, wrap);
        } else {
          img.addEventListener('load', function () { adaptImageFit(img, wrap); });
        }
      }
    }

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        section.classList.add('is-loaded');
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
