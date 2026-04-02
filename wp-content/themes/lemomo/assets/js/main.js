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
    const slides = Array.from(track.querySelectorAll('.testimonials__slide'));
    const prevBtn = document.querySelector('.testimonials__nav-btn--prev');
    const nextBtn = document.querySelector('.testimonials__nav-btn--next');

    if (slides.length === 0) return;

    let current = 0;

    function isMobile() {
        return window.innerWidth < 768;
    }

    function getVisible() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 768) return 2;
        return 1;
    }

    function maxIndex() {
        return Math.max(0, slides.length - getVisible());
    }

    function scrollMobile(direction) {
        const card = slides[0];
        if (!card) return;
        const distance = card.getBoundingClientRect().width + 20;
        wrapper.scrollBy({ left: direction * distance, behavior: 'smooth' });
    }

    function update() {
        if (isMobile()) {
            track.style.transform = '';
            return;
        }
        const cardWidth = slides[0].getBoundingClientRect().width;
        const gap = 20;
        const offset = current * (cardWidth + gap);
        track.style.transform = `translateX(-${offset}px)`;

        if (prevBtn) prevBtn.disabled = current === 0;
        if (nextBtn) nextBtn.disabled = current >= maxIndex();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (isMobile()) { scrollMobile(-1); return; }
            if (current > 0) { current--; update(); }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (isMobile()) { scrollMobile(1); return; }
            if (current < maxIndex()) { current++; update(); }
        });
    }

    window.addEventListener('resize', () => {
        current = Math.min(current, maxIndex());
        update();
    });

    update();
})();

// ─── Event Countdown Timer ──────────────────────────────────────────────────
(function () {
    const el = document.querySelector('.event-upcoming__countdown');
    if (!el) return;

    const target = el.dataset.target;
    if (!target) return;

    const deadline = new Date(target).getTime();
    if (isNaN(deadline)) return;

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        const now = Date.now();
        const diff = Math.max(0, deadline - now);

        const totalSec = Math.floor(diff / 1000);
        const hours = Math.floor(totalSec / 3600);
        const minutes = Math.floor((totalSec % 3600) / 60);
        const seconds = totalSec % 60;

        const h = el.querySelector('[data-unit="hours"]');
        const m = el.querySelector('[data-unit="minutes"]');
        const s = el.querySelector('[data-unit="seconds"]');

        if (h) h.textContent = pad(hours);
        if (m) m.textContent = pad(minutes);
        if (s) s.textContent = pad(seconds);

        if (diff > 0) requestAnimationFrame(tick);
    }

    tick();
    setInterval(tick, 1000);
})();
