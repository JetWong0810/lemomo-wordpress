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
document.querySelectorAll('.video-player, .episode-card, .event-card--has-video').forEach((el) => {
    el.addEventListener('click', () => {
        const videoUrl = el.dataset.video;
        if (!videoUrl) return;

        // 找到封面容器（episode-card 用 __thumb-wrap，event-card 也一样）
        const wrap = el.querySelector('.episode-card__thumb-wrap, .event-card__thumb-wrap') || el;

        const iframe = document.createElement('iframe');
        iframe.src = videoUrl;
        iframe.allow = 'autoplay; fullscreen';
        iframe.allowFullscreen = true;
        iframe.style.cssText =
            'position:absolute;top:0;left:0;width:100%;height:100%;border:0;background:#000;';

        wrap.style.position = 'relative';
        wrap.style.paddingBottom = '56.25%';
        wrap.style.height = '0';
        wrap.innerHTML = '';
        wrap.appendChild(iframe);
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

// ─── FAQ Accordion & Categories ─────────────────────────────────────────────
(function () {
    const catBtns = document.querySelectorAll('.faq-categories__item');
    const panels = document.querySelectorAll('.faq-panel');
    if (!catBtns.length || !panels.length) return;

    catBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const idx = btn.dataset.category;

            catBtns.forEach((b) => {
                b.classList.remove('faq-categories__item--active');
                b.setAttribute('aria-pressed', 'false');
                const accent = b.querySelector('.faq-categories__accent');
                if (accent) accent.remove();
            });

            btn.classList.add('faq-categories__item--active');
            btn.setAttribute('aria-pressed', 'true');
            if (!btn.querySelector('.faq-categories__accent')) {
                const accent = document.createElement('span');
                accent.className = 'faq-categories__accent';
                accent.setAttribute('aria-hidden', 'true');
                btn.prepend(accent);
            }

            panels.forEach((p) => p.classList.remove('faq-panel--active'));
            const target = document.querySelector(
                `.faq-panel[data-panel="${idx}"]`,
            );
            if (target) target.classList.add('faq-panel--active');
        });
    });

    document.querySelectorAll('.faq-item__question').forEach((q) => {
        q.addEventListener('click', () => {
            const item = q.closest('.faq-item');
            const answer = item.querySelector('.faq-item__answer');
            const isOpen = item.classList.contains('faq-item--open');

            const panel = item.closest('.faq-panel');
            panel.querySelectorAll('.faq-item--open').forEach((openItem) => {
                if (openItem !== item) {
                    openItem.classList.remove('faq-item--open');
                    const a = openItem.querySelector('.faq-item__answer');
                    const b = openItem.querySelector('.faq-item__question');
                    if (a) a.hidden = true;
                    if (b) b.setAttribute('aria-expanded', 'false');
                }
            });

            if (isOpen) {
                item.classList.remove('faq-item--open');
                answer.hidden = true;
                q.setAttribute('aria-expanded', 'false');
            } else {
                item.classList.add('faq-item--open');
                answer.hidden = false;
                q.setAttribute('aria-expanded', 'true');
            }
        });
    });

    const searchInput = document.querySelector('.faq-search__input');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().trim();
            if (!query) {
                document.querySelectorAll('.faq-item').forEach((i) => {
                    i.style.display = '';
                });
                return;
            }

            panels.forEach((p) => p.classList.add('faq-panel--active'));
            document.querySelectorAll('.faq-item').forEach((item) => {
                const qText = item
                    .querySelector('.faq-item__question-text')
                    ?.textContent.toLowerCase();
                const aText = item
                    .querySelector('.faq-item__answer')
                    ?.textContent.toLowerCase();
                const match =
                    (qText && qText.includes(query)) ||
                    (aText && aText.includes(query));
                item.style.display = match ? '' : 'none';
            });
        });
    }
})();

// ─── Blog Detail: Copy Link ─────────────────────────────────────────────────
(function () {
    const copyBtn = document.querySelector('.blog-detail__share-icon--copy');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const url = this.dataset.url || window.location.href;
        navigator.clipboard.writeText(url).then(function () {
            const svg = copyBtn.querySelector('svg circle');
            if (svg) {
                svg.setAttribute('fill', '#EBAD1F');
                setTimeout(function () { svg.setAttribute('fill', 'none'); }, 1500);
            }
        });
    });
})();

// ─── Blog Detail: Auto TOC ─────────────────────────────────────────────────
(function () {
    const tocNav = document.getElementById('blog-toc-nav');
    const body = document.querySelector('.blog-detail__body');
    if (!tocNav || !body) return;

    const headings = body.querySelectorAll('h2, h3');
    if (headings.length === 0) {
        const tocBlock = tocNav.closest('.blog-detail__toc');
        if (tocBlock) tocBlock.style.display = 'none';
        return;
    }

    const line = document.createElement('span');
    line.className = 'blog-detail__toc-line';
    tocNav.appendChild(line);

    headings.forEach((h, i) => {
        const id = 'section-' + i;
        h.id = id;

        const link = document.createElement('a');
        link.href = '#' + id;
        link.className = 'blog-detail__toc-link';
        link.textContent = h.textContent;
        tocNav.appendChild(link);
    });

    const links = tocNav.querySelectorAll('.blog-detail__toc-link');

    function updateActive() {
        let current = 0;
        headings.forEach((h, i) => {
            if (h.getBoundingClientRect().top <= 120) current = i;
        });
        links.forEach((l, i) => {
            l.classList.toggle('blog-detail__toc-link--active', i === current);
        });
    }

    window.addEventListener('scroll', updateActive, { passive: true });
    updateActive();
})();
