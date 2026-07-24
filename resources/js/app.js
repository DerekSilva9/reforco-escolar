import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const initHeroCarousel = () => {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) {
        return;
    }

    const slides = Array.from(carousel.querySelectorAll('[data-slide-index]'));
    const indicators = Array.from(carousel.querySelectorAll('[data-carousel-target]'));
    const prevButton = document.getElementById('heroCarouselPrev');
    const nextButton = document.getElementById('heroCarouselNext');
    let activeIndex = 0;

    const updateSlide = (index) => {
        activeIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === activeIndex;
            slide.style.opacity = isActive ? '1' : '0';
            slide.style.pointerEvents = isActive ? 'auto' : 'none';
            slide.setAttribute('aria-hidden', String(!isActive));
        });

        indicators.forEach((indicator, indicatorIndex) => {
            const isCurrent = indicatorIndex === activeIndex;
            indicator.classList.toggle('bg-white', isCurrent);
            indicator.classList.toggle('bg-white/60', !isCurrent);
            indicator.setAttribute('aria-current', isCurrent ? 'true' : 'false');
        });
    };

    const goToNext = () => updateSlide(activeIndex + 1);
    const goToPrev = () => updateSlide(activeIndex - 1);

    prevButton?.addEventListener('click', goToPrev);
    nextButton?.addEventListener('click', goToNext);

    indicators.forEach((indicator) => {
        const target = Number(indicator.getAttribute('data-carousel-target'));
        if (Number.isFinite(target)) {
            indicator.addEventListener('click', () => updateSlide(target));
        }
    });

    updateSlide(activeIndex);
};

document.addEventListener('DOMContentLoaded', initHeroCarousel);
