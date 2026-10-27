// Add more image paths here to extend the slideshow.
const slides = [
    'assets/pic1.jpg'
];

const slideImage = document.getElementById('slideImage');
const prevButton = document.getElementById('prevSlide');
const nextButton = document.getElementById('nextSlide');
const slideshow = document.getElementById('slidesaree');
let current = 0;
let timer = null;

function showSlide(index) {
    current = (index + slides.length) % slides.length;
    slideImage.src = slides[current];
}

function startAutoPlay() {
    if (slides.length < 2) return;
    stopAutoPlay();
    timer = setInterval(() => showSlide(current + 1), 5000);
}

function stopAutoPlay() {
    clearInterval(timer);
}

// Fall back to a placeholder if an image is missing.
slideImage.addEventListener('error', () => {
    slideImage.src = 'https://placehold.co/600x750/f2e6dc/5c3424?text=RR+Sarees';
}, { once: true });

if (slides.length < 2) {
    prevButton.hidden = true;
    nextButton.hidden = true;
} else {
    prevButton.addEventListener('click', () => { showSlide(current - 1); startAutoPlay(); });
    nextButton.addEventListener('click', () => { showSlide(current + 1); startAutoPlay(); });
    slideshow.addEventListener('mouseenter', stopAutoPlay);
    slideshow.addEventListener('mouseleave', startAutoPlay);
    startAutoPlay();
}
