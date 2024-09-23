document.getElementById("menu-toggle").addEventListener("click", function() {
    var menu = document.getElementById("menu");
    var toggleButton = document.getElementById("menu-toggle");
    menu.classList.toggle("show");
    toggleButton.classList.toggle("show");
});


document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkmode-toggle');
    const body = document.body;

    darkModeToggle.addEventListener('change', function () {
        if (darkModeToggle.checked) {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
    });

    // Set initial state based on saved preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        darkModeToggle.checked = true;
        body.classList.add('dark-mode');
    }

    darkModeToggle.addEventListener('change', function () {
        if (darkModeToggle.checked) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });
});


const dynamicText = document.querySelector("h3 span");
const words = ["Drive Saviour"];

let wordIndex = 0;
let charIndex = 0;
let isDeleting = false;

const typeEffect = () => {
    const currentWord = words[wordIndex];
    const currentChar = currentWord.substring(0, charIndex);
    dynamicText.textContent = currentChar;
    dynamicText.classList.add("stop-blinking");

    if (!isDeleting && charIndex < currentWord.length) {
        charIndex++;
        setTimeout(typeEffect, 200);
    } else if (isDeleting && charIndex > 0) {
        charIndex--;
        setTimeout(typeEffect, 100);
    } else {
        isDeleting = !isDeleting;
        dynamicText.classList.remove("stop-blinking");
        wordIndex = !isDeleting ? (wordIndex + 1) % words.length : wordIndex;
        setTimeout(typeEffect, 1200);
    }
}

typeEffect();

// Initialize ScrollReveal
const sr = ScrollReveal({
    origin: 'top',
    distance: '85px',
    duration: 2500,
    reset: true
});

sr.reveal('.text-center', { delay: 300 });
sr.reveal('.services-boxes', { delay: 500 });
sr.reveal('.title-text h2', { delay: 300 });

const pBoxes = document.querySelectorAll('.p-box');
pBoxes.forEach((box, index) => {
    sr.reveal(box, { delay: 300 + index * 150 }); 
});


