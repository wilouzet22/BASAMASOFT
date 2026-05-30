```javascript
// Scripts principales para EduSaft
document.addEventListener('DOMContentLoaded', () => {
    console.log('EduSaft cargado');

    // Mobile Menu Toggle
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }

    // Slider Logic
    const slides = document.querySelectorAll('.slide');
    const nextBtn = document.getElementById('next-slide');
    const prevBtn = document.getElementById('prev-slide');
    let currentSlide = 0;
    const slideInterval = 5000; // 5 seconds

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0');
                slide.classList.add('opacity-100');
            } else {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    if (slides.length > 0) {
        // Initial state
        showSlide(currentSlide);

        // Event listeners
        if (nextBtn) nextBtn.addEventListener('click', () => {
            nextSlide();
            resetTimer();
        });
        
        if (prevBtn) prevBtn.addEventListener('click', () => {
            prevSlide();
            resetTimer();
        });

        // Auto play
        let slideTimer = setInterval(nextSlide, slideInterval);

        function resetTimer() {
            clearInterval(slideTimer);
            slideTimer = setInterval(nextSlide, slideInterval);
        }
    }
});
```
