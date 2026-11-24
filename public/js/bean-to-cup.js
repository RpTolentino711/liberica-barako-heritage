(() => {
    document.querySelectorAll('[data-journey-carousel]').forEach((carousel) => {
        const slides = [...carousel.querySelectorAll('.journey-slide')];
        const track = carousel.querySelector('.journey-carousel-track');
        const previous = carousel.querySelector('[data-journey-prev]');
        const next = carousel.querySelector('[data-journey-next]');
        const dots = [...carousel.querySelectorAll('[data-journey-dot]')];
        const current = carousel.querySelector('[data-journey-current]');
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        const pauseReasons = new Set();
        let activeIndex = 0;
        let autoplayEnabled = slides.length > 1 && !reducedMotion.matches;
        let timer = null;

        if (!track || slides.length === 0) return;

        const scheduleAutoplay = () => {
            window.clearInterval(timer);
            timer = null;
            if (!autoplayEnabled || pauseReasons.size || slides.length < 2) return;
            timer = window.setInterval(() => showStep(activeIndex + 1, true), 6500);
        };

        const showStep = (index, automatic = false) => {
            const nextIndex = ((index % slides.length) + slides.length) % slides.length;
            if (slides[activeIndex]) slides[activeIndex].querySelector('video')?.pause();
            activeIndex = nextIndex;
            track.style.transform = `translateX(-${activeIndex * 100}%)`;
            slides.forEach((slide, slideIndex) => {
                const active = slideIndex === activeIndex;
                slide.setAttribute('aria-hidden', String(!active));
                slide.inert = !active;
            });
            dots.forEach((dot, dotIndex) => {
                if (dotIndex === activeIndex) dot.setAttribute('aria-current', 'step');
                else dot.removeAttribute('aria-current');
            });
            if (current) current.textContent = String(activeIndex + 1).padStart(2, '0');
            if (!automatic) scheduleAutoplay();
        };

        previous?.addEventListener('click', () => showStep(activeIndex - 1));
        next?.addEventListener('click', () => showStep(activeIndex + 1));
        dots.forEach((dot) => dot.addEventListener('click', () => showStep(Number(dot.dataset.journeyDot))));
        carousel.addEventListener('pointerenter', () => { pauseReasons.add('pointer'); scheduleAutoplay(); });
        carousel.addEventListener('pointerleave', () => { pauseReasons.delete('pointer'); scheduleAutoplay(); });
        carousel.addEventListener('focusin', () => { pauseReasons.add('focus'); scheduleAutoplay(); });
        carousel.addEventListener('focusout', (event) => {
            if (!carousel.contains(event.relatedTarget)) {
                pauseReasons.delete('focus');
                scheduleAutoplay();
            }
        });
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) pauseReasons.add('hidden');
            else pauseReasons.delete('hidden');
            scheduleAutoplay();
        });
        slides.forEach((slide) => {
            const video = slide.querySelector('video');
            video?.addEventListener('play', () => { pauseReasons.add('video'); scheduleAutoplay(); });
            video?.addEventListener('pause', () => { pauseReasons.delete('video'); scheduleAutoplay(); });
            video?.addEventListener('ended', () => { pauseReasons.delete('video'); scheduleAutoplay(); });
        });
        carousel.querySelector('.journey-carousel-window')?.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') { event.preventDefault(); showStep(activeIndex - 1); }
            if (event.key === 'ArrowRight') { event.preventDefault(); showStep(activeIndex + 1); }
        });

        showStep(0);
    });
})();
