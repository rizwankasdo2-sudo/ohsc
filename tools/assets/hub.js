(function () {
    'use strict';
    document.querySelectorAll('[data-hub-carousel]').forEach(function (carousel) {
        var track = carousel.querySelector('.ohsc-carousel-track');
        var prev = carousel.querySelector('.ohsc-carousel-prev');
        var next = carousel.querySelector('.ohsc-carousel-next');
        var dots = carousel.querySelector('.ohsc-carousel-dots');
        function update() {
            var max = track.scrollWidth - track.clientWidth;
            prev.hidden = next.hidden = max < 2;
            prev.disabled = track.scrollLeft < 2;
            next.disabled = track.scrollLeft >= max - 2;
            var pages = max < 2 ? 1 : Math.ceil(track.scrollWidth / (track.clientWidth + 18));
            var active = max < 2 ? 0 : Math.round(track.scrollLeft / max * (pages - 1));
            dots.replaceChildren();
            if (pages > 1) {
                for (var i = 0; i < pages; i++) {
                    var dot = document.createElement('span');
                    if (i === active) dot.className = 'is-active';
                    dots.appendChild(dot);
                }
            }
        }
        function move(direction) {
            track.scrollBy({ left: direction * (track.clientWidth + 18), behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
        }
        prev.addEventListener('click', function () { move(-1); });
        next.addEventListener('click', function () { move(1); });
        track.addEventListener('scroll', update, { passive: true });
        track.addEventListener('keydown', function (event) {
            if (event.target !== track) return;
            if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                event.preventDefault();
                move(event.key === 'ArrowRight' ? 1 : -1);
            }
        });
        window.addEventListener('resize', update);
        update();
    });
})();
