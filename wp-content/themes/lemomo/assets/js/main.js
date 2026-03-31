// ─── Hamburger Menu ──────────────────────────────────────────────────────────
(function () {
    const btn = document.querySelector('.hamburger');
    const nav = document.querySelector('.site-nav');
    if (!btn || !nav) return;

    btn.addEventListener('click', () => {
        const open = nav.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', open);
    });
})();

// ─── Video Player ────────────────────────────────────────────────────────────
document.querySelectorAll('.video-player, .episode-card').forEach((el) => {
    el.addEventListener('click', () => {
        const videoUrl = el.dataset.video;
        if (!videoUrl) return;

        const iframe = document.createElement('iframe');
        iframe.src = videoUrl;
        iframe.allow = 'autoplay; fullscreen';
        iframe.allowFullscreen = true;
        iframe.style.cssText =
            'position:absolute;top:0;left:0;width:100%;height:100%;border:0;';

        el.style.position = 'relative';
        el.style.paddingBottom = '56.25%';
        el.innerHTML = '';
        el.appendChild(iframe);
    });

    el.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') el.click();
    });
});

// ─── Testimonials Carousel ───────────────────────────────────────────────────
(function () {
    const wrapper = document.querySelector('.testimonials__track-wrapper');
    if (!wrapper) return;

    const track = wrapper.querySelector('.testimonials__track');
    const cards = Array.from(track.querySelectorAll('.testimonials__slide'));
    const prevBtn = document.querySelector('.testimonials__nav-btn--prev');
    const nextBtn = document.querySelector('.testimonials__nav-btn--next');

    if (cards.length === 0) return;

    let current = 0;

    function getVisible() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 640) return 2;
        return 1;
    }

    function maxIndex() {
        return Math.max(0, cards.length - getVisible());
    }

    function update() {
        const cardWidth = cards[0].getBoundingClientRect().width;
        const gap = 20;
        const offset = current * (cardWidth + gap);
        track.style.transform = `translateX(-${offset}px)`;

        if (prevBtn) prevBtn.disabled = current === 0;
        if (nextBtn) nextBtn.disabled = current >= maxIndex();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (current > 0) { current--; update(); }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (current < maxIndex()) { current++; update(); }
        });
    }

    window.addEventListener('resize', () => {
        current = Math.min(current, maxIndex());
        update();
    });

    update();
})();
