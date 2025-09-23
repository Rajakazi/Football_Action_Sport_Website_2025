
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
  // Show search bar when ready
document.addEventListener('DOMContentLoaded', function(){
  document.getElementById('searchBar').style.display = 'block';
});


  const mobileWrapper = document.getElementById("mobileSliderWrapper");
let mobileIndex = 0;

function slideMobile() {
  mobileIndex = (mobileIndex + 1) % slides;
  mobileWrapper.style.transform = `translateX(-${mobileIndex * 100}%)`;
}
setInterval(slideMobile, 3000);
document.querySelectorAll('.match-card').forEach(card=>{
  card.classList.add('ready'); // card visible after JS processed
});

function openModal(img) {
  const modal = document.getElementById("imgModal");
  const modalImg = document.getElementById("modalImg");
  modal.style.display = "flex"; // flex makes centering work
  modalImg.src = img.src;
}
function closeModal() {
  document.getElementById("imgModal").style.display = "none";
}

document.querySelectorAll('.read-more').forEach(link=>{
    link.addEventListener('click', function(e){
        e.preventDefault();
        let newsId = this.dataset.id;
        fetch('increment_views.php?id='+newsId)
            .then(r=>r.json())
            .then(data=>{
                // Update views instantly on index page
                let desktopView = document.getElementById('views-'+newsId);
                let mobileView = document.getElementById('views-mobile-'+newsId);
                if(desktopView) desktopView.innerText = data.views;
                if(mobileView) mobileView.innerText = data.views;
                // Redirect to news.php after update
                window.location.href = this.href;
            });
    });
});
