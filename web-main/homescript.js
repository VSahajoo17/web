let currentSlide = 0
let images = [
    'assets/pic1.jpg',
    'assets/men3.jpg',
    'assets/pic7.jpg',
    'assets/men4.jpg',
    'assets/pic8.jpg',
    'assets/pic9.jpg'];

function showSlide(index)
{
    let slide = document.getElementById("slideImage");
    slide.src = images[index];
}
function resetTimer() {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, 7000);
}

function nextSlide(){
    currentSlide=(currentSlide + 1) % images.length;
    showSlide(currentSlide);
}

function prevSlide(){
    currentSlide = (currentSlide - 1 + images.length) % images.length;
    showSlide(currentSlide);
}

//buttons function will reset timer as u click next or prev

function nextSlideMod() {
    nextSlide();
    resetTimer();
}

function prevSlideMod() {
    prevSlide();
    resetTimer();
}

let slideInterval = setInterval(nextSlide, 7000);