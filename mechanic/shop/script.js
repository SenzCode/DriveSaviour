const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');
const carouselItems = document.querySelector('.carousel-items');

let scrollAmount = 0;

function scrollToNext() {
    const itemWidth = carouselItems.querySelector('.carousel-item').clientWidth;
    if (scrollAmount <= carouselItems.scrollWidth - carouselItems.clientWidth - itemWidth) {
        scrollAmount += itemWidth + 10;  // Adjusted to include margin
    } else {
        scrollAmount = 0; // Loop back to the start if at the end
    }
    carouselItems.scrollTo({
        top: 0,
        left: scrollAmount,
        behavior: 'smooth'
    });
}

function scrollToPrev() {
    const itemWidth = carouselItems.querySelector('.carousel-item').clientWidth;
    if (scrollAmount > 0) {
        scrollAmount -= itemWidth + 10;  // Adjusted to include margin
    } else {
        scrollAmount = carouselItems.scrollWidth - carouselItems.clientWidth; // Loop to the end if at the start
    }
    carouselItems.scrollTo({
        top: 0,
        left: scrollAmount,
        behavior: 'smooth'
    });
}

nextBtn.addEventListener('click', scrollToNext);
prevBtn.addEventListener('click', scrollToPrev);