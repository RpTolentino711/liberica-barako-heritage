(() => {
    document.querySelectorAll('[data-journey-carousel]').forEach((carousel) => {
        const slides = [...carousel.querySelectorAll('.journey-slide')];
        const track = carousel.querySelector('.journey-carousel-track');
        const previous = carousel.querySelector('[data-journey-prev]');
        const next = carousel.querySelector('[data-journey-next]');
        const dots = [...carousel.querySelectorAll('[data-journey-dot]')];
        const current = carousel.querySelector('[data-journey-current]');
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        const pauseReasons = new Set(['offscreen']);
        if (document.hidden) pauseReasons.add('hidden');
        let activeIndex = 0;
        const nextTitle = next?.querySelector('[data-next-title]');
        let transitions = [];
        let timer = null;

        if (!track || slides.length === 0) return;

        const scheduleAutoplay = () => {
            window.clearTimeout(timer);
            const status = current?.parentElement;
            status?.setAttribute('aria-live', !reducedMotion.matches && !pauseReasons.size ? 'off' : 'polite');
            if (reducedMotion.matches || pauseReasons.size || slides.length < 2) return;
            timer = window.setTimeout(() => showStep(activeIndex + 1), 6500);
        };

        const showStep = (index) => {
            const nextIndex = ((index % slides.length) + slides.length) % slides.length;
            transitions.forEach(animation => animation.cancel());
            transitions = [];
            const outgoing = slides[activeIndex];
            const incoming = slides[nextIndex];
            if (outgoing !== incoming) {
                outgoing.querySelector('video')?.pause();
                if (!reducedMotion.matches) {
                    const direction = index > activeIndex ? 100 : -100;
                    const motion = { duration: 380, easing: 'cubic-bezier(.22,.7,.25,1)' };
                    transitions = [
                        outgoing.animate([
                            { transform: 'none', visibility: 'visible' },
                            { transform: 'translateX(' + -direction + '%)', visibility: 'visible' },
                        ], motion),
                        incoming.animate([
                            { transform: 'translateX(' + direction + '%)' },
                            { transform: 'none' },
                        ], motion),
                    ];
                }
            }
            activeIndex = nextIndex;
            if (nextTitle) nextTitle.textContent = 'Next: ' + slides[(activeIndex + 1) % slides.length].querySelector('h3').textContent;
            slides.forEach((slide, slideIndex) => {
                const active = slideIndex === activeIndex;
                slide.setAttribute('aria-hidden', String(!active));
                slide.inert = !active;
                if (!active) slide.querySelector('details[open]')?.removeAttribute('open');
            });
            dots.forEach((dot, dotIndex) => {
                if (dotIndex === activeIndex) dot.setAttribute('aria-current', 'step');
                else dot.removeAttribute('aria-current');
            });
            if (current) current.textContent = String(activeIndex + 1).padStart(2, '0');
            scheduleAutoplay();
        };

        previous?.addEventListener('click', () => showStep(activeIndex - 1));
        next?.addEventListener('click', () => showStep(activeIndex + 1));
        dots.forEach((dot) => dot.addEventListener('click', () => showStep(Number(dot.dataset.journeyDot))));
        reducedMotion.addEventListener('change', scheduleAutoplay);
        new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) pauseReasons.delete('offscreen');
            else pauseReasons.add('offscreen');
            scheduleAutoplay();
        }).observe(carousel);
        carousel.addEventListener('focusin', (event) => {
            if (event.target.matches(':focus-visible')) pauseReasons.add('focus');
            scheduleAutoplay();
        });
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
            slide.querySelector('details')?.addEventListener('toggle', () => {
                if (carousel.querySelector('.journey-slide[aria-hidden="false"] details[open]')) pauseReasons.add('credits');
                else pauseReasons.delete('credits');
                scheduleAutoplay();
            });
            const video = slide.querySelector('video');
            video?.addEventListener('play', () => { pauseReasons.add('video'); scheduleAutoplay(); });
            video?.addEventListener('pause', () => { pauseReasons.delete('video'); scheduleAutoplay(); });
            video?.addEventListener('ended', () => { pauseReasons.delete('video'); scheduleAutoplay(); });
        });
        carousel.querySelector('.journey-carousel-window')?.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') { event.preventDefault(); showStep(activeIndex - 1); }
            if (event.key === 'ArrowRight') { event.preventDefault(); showStep(activeIndex + 1); }
        });

        const viewport = carousel.querySelector('.journey-carousel-window');
        let start;
        viewport.addEventListener('touchstart', (event) => {
            start = event.touches.length === 1 ? event.touches[0] : null;
        }, { passive: true });
        viewport.addEventListener('touchcancel', () => { start = null; });
        viewport.addEventListener('touchend', (event) => {
            if (!start) return;
            const dx = event.changedTouches[0].clientX - start.clientX;
            const dy = event.changedTouches[0].clientY - start.clientY;
            start = null;
            if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy) * 1.5) {
                showStep(activeIndex + (dx < 0 ? 1 : -1));
            }
        }, { passive: true });
        showStep(0);
    });
})();
