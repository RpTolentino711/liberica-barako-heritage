(() => {
    document.querySelectorAll('[data-journey-carousel]').forEach((carousel) => {
        const slides = [...carousel.querySelectorAll('.journey-slide')];
        const track = carousel.querySelector('.journey-carousel-track');
        const previous = carousel.querySelector('[data-journey-prev]');
        const next = carousel.querySelector('[data-journey-next]');
        const dots = [...carousel.querySelectorAll('[data-journey-dot]')];
        const current = carousel.querySelector('[data-journey-current]');
        let activeIndex = 0;

        if (!track || slides.length === 0) return;

        const showStep = (index) => {
            const nextIndex = Math.max(0, Math.min(index, slides.length - 1));
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
            if (previous) previous.disabled = activeIndex === 0;
            if (next) next.disabled = activeIndex === slides.length - 1;
        };

        previous?.addEventListener('click', () => showStep(activeIndex - 1));
        next?.addEventListener('click', () => showStep(activeIndex + 1));
        dots.forEach((dot) => dot.addEventListener('click', () => showStep(Number(dot.dataset.journeyDot))));
        carousel.querySelector('.journey-carousel-window')?.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') showStep(activeIndex - 1);
            if (event.key === 'ArrowRight') showStep(activeIndex + 1);
        });
        showStep(0);
    });
})();
