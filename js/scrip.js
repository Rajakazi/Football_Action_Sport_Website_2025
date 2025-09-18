
// Slider JS here now part in js here lets go
const wrapper = document.getElementById('sliderWrapper');
const slides = document.querySelectorAll('.slide');
const dotsContainer = document.getElementById('dotsContainer');
let index = 0;

// Create dots
slides.forEach((_, i) => {
    const dot = document.createElement('span');
    dot.classList.add('dot');
    if(i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer.appendChild(dot);
});

function updateDots() {
    document.querySelectorAll('.dot').forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

function goToSlide(i) {
    index = i;
    wrapper.style.transform = `translateX(-${index * 100}%)`;
    updateDots();
}

// Auto slide
setInterval(() => {
    index = (index + 1) % slides.length;
    wrapper.style.transform = `translateX(-${index * 100}%)`;
    updateDots();
}, 4000);


//adv... here 
const slider = document.getElementById('advSlider');
let scrollPos = 0;

// Duplicate slides for seamless loop
slider.innerHTML += slider.innerHTML;

function autoScroll(){
    scrollPos += 1; // Scroll speed
    if(scrollPos >= slider.scrollWidth / 2){ // reset after half width
        scrollPos = 0;
    }
    slider.style.transform = `translateX(-${scrollPos}px)`;
    requestAnimationFrame(autoScroll);
}

autoScroll();


// mobile nav bar js command 
function toggleMobileMenu() {
    const sidebar = document.getElementById("mobileSidebar");
    sidebar.classList.toggle("open");
  }
  