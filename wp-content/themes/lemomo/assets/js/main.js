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

// ─── Features Tab Switching ───────────────────────────────────────────────
(function () {
    const tabs = document.querySelectorAll('[data-features-tab]');
    if (!tabs.length) return;

    const imgs = document.querySelectorAll('[data-features-img]');
    const texts = document.querySelectorAll('[data-features-text]');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const idx = tab.dataset.featuresTab;

            tabs.forEach(t => {
                t.classList.remove('features__tab--active');
                const accent = t.querySelector('.features__tab-accent');
                if (accent) accent.remove();
            });
            tab.classList.add('features__tab--active');
            if (!tab.querySelector('.features__tab-accent')) {
                const accent = document.createElement('span');
                accent.className = 'features__tab-accent';
                tab.prepend(accent);
            }

            imgs.forEach(img => {
                img.classList.toggle('features__media-img--active', img.dataset.featuresImg === idx);
            });
            texts.forEach(text => {
                text.classList.toggle('features__text-content--active', text.dataset.featuresText === idx);
            });
        });
    });
})();

// ─── Explore Page: Main Player + Episode Cards ─────────────────────────────
(function () {
    const player = document.getElementById('explorePlayer');
    if (player) {
        const container = player.querySelector('.explore-player__container');

        function playVideo(url, thumb) {
            const video = document.createElement('video');
            video.src = url;
            video.autoplay = true;
            video.muted = true;
            video.controls = true;
            video.playsInline = true;
            video.className = 'explore-player__video';
            container.innerHTML = '';
            container.appendChild(video);

            player.dataset.video = url;
            player.dataset.thumb = thumb;

            video.addEventListener('ended', showThumb);
        }

        function showThumb() {
            container.innerHTML =
                '<img src="' + player.dataset.thumb + '" alt="Explore Lemomo" class="explore-player__thumb">' +
                '<span class="explore-player__play-btn" aria-hidden="true">&#9654; PLAY</span>';
        }

        container.addEventListener('click', () => {
            if (container.querySelector('video')) return;
            const url = player.dataset.video;
            if (url && url !== '#') playVideo(url, player.dataset.thumb);
        });

        document.querySelectorAll('.episode-card').forEach(card => {
            card.addEventListener('click', () => {
                const url = card.dataset.video;
                const thumb = card.querySelector('.episode-card__thumb')?.src || player.dataset.thumb;
                if (!url || url === '#') return;
                playVideo(url, thumb);
                player.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
            card.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') card.click();
            });
        });

        // Auto-play first episode on page load
        const firstUrl = player.dataset.video;
        if (firstUrl && firstUrl !== '#') {
            playVideo(firstUrl, player.dataset.thumb);
        }

        // Pause/resume based on viewport visibility
        let wasPlaying = false;

        new IntersectionObserver(entries => {
            const video = container.querySelector('video');
            if (!video) return;
            if (entries[0].isIntersecting) {
                if (wasPlaying) video.play();
            } else {
                wasPlaying = !video.paused;
                video.pause();
            }
        }, { threshold: 0.3 }).observe(player);

        return;
    }

    // Fallback: other pages with .video-player or .event-card--has-video
    document.querySelectorAll('.video-player, .event-card--has-video').forEach((el) => {
        el.addEventListener('click', () => {
            const videoUrl = el.dataset.video;
            if (!videoUrl) return;

            const wrap = el.querySelector('.event-card__thumb-wrap') || el;

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
})();

// ─── Blog Featured Carousel (infinite seamless scroll) ────────────────────
(function () {
    const wrapper = document.querySelector('.blog-featured__track-wrapper');
    if (!wrapper) return;

    const track = wrapper.querySelector('.blog-featured__track');
    const cards = Array.from(track.querySelectorAll('.blog-featured__card'));
    if (cards.length === 0) return;

    cards.forEach(c => track.appendChild(c.cloneNode(true)));

    function calcDuration() {
        const totalWidth = track.scrollWidth / 2;
        return totalWidth / 60;
    }

    track.style.animationDuration = calcDuration() + 's';
    track.classList.add('blog-featured__track--scrolling');

    wrapper.addEventListener('mouseenter', () => track.style.animationPlayState = 'paused');
    wrapper.addEventListener('mouseleave', () => track.style.animationPlayState = 'running');

    window.addEventListener('resize', () => {
        track.style.animationDuration = calcDuration() + 's';
    });
})();

// ─── Testimonials Carousel (infinite seamless scroll) ───────────────────────
(function () {
    const wrapper = document.querySelector('.testimonials__track-wrapper');
    if (!wrapper) return;

    const track = wrapper.querySelector('.testimonials__track');
    const slides = Array.from(track.querySelectorAll('.testimonials__slide'));
    if (slides.length === 0) return;

    slides.forEach(s => track.appendChild(s.cloneNode(true)));

    function calcDuration() {
        const totalWidth = track.scrollWidth / 2;
        return totalWidth / 80;
    }

    track.style.animationDuration = calcDuration() + 's';
    track.classList.add('testimonials__track--scrolling');

    wrapper.addEventListener('mouseenter', () => track.style.animationPlayState = 'paused');
    wrapper.addEventListener('mouseleave', () => track.style.animationPlayState = 'running');

    window.addEventListener('resize', () => {
        track.style.animationDuration = calcDuration() + 's';
    });
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

// ─── Download Modal ─────────────────────────────────────────────────────────
(function () {
    const modal = document.getElementById('downloadModal');
    if (!modal) return;

    const backdrop = modal.querySelector('.download-modal__backdrop');
    const closeBtn = modal.querySelector('.download-modal__close');

    function open() {
        modal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function close() {
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.js-open-download').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            open();
        });
    });

    if (closeBtn) closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
    });
})();

// ─── Contact Form (About page) ──────────────────────────────────────────────
(function () {
    const form = document.getElementById('contactForm');
    if (!form) return;

    const msg = document.getElementById('contactMsg');
    const btn = form.querySelector('.about-contact__submit');

    form.addEventListener('submit', e => {
        e.preventDefault();
        btn.disabled = true;
        btn.textContent = 'Mengirim...';
        msg.hidden = true;

        const data = new FormData(form);
        data.append('action', 'lemomo_contact_submit');
        data.append('nonce', form.querySelector('[name="contact_nonce"]').value);

        fetch(window.lemomo_ajax.url, { method: 'POST', body: data })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    form.reset();
                    alert('Berhasil terkirim!');
                } else {
                    alert(res.data || 'Gagal mengirim, coba lagi.');
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan jaringan.');
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Kirim';
            });
    });
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
