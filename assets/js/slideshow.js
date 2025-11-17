document.addEventListener("DOMContentLoaded", () => {

  const AUTO_TIME = 10000;
  const GAP = 16;

  const wrapper = document.querySelector(".slideshow-wrapper");
  const slideshow = wrapper.querySelector(".slideshow");
  const navigation = wrapper.querySelector(".slideshow-navigation");
  const navPrev = wrapper.querySelector(".slide-prev");
  const navNext = wrapper.querySelector(".slide-next");
  const bulletsContainer = wrapper.querySelector(".bullets");

  if (!slideshow) return;

  wrapper.style.overflow = "hidden";
  wrapper.style.display = "grid";
  wrapper.style.gap = "1rem";
  slideshow.style.display = "flex";
  slideshow.style.padding = "0.2rem 0";
  navigation.style.display = "flex";

  // =============================
  // CLONES PERMANENTES (∞ real)
  // =============================
  const originalSlides = Array.from(slideshow.children);
  const totalOriginal = originalSlides.length;

  const firstClone = originalSlides[0].cloneNode(true);
  const lastClone = originalSlides[totalOriginal - 1].cloneNode(true);

  slideshow.appendChild(firstClone);
  slideshow.insertBefore(lastClone, originalSlides[0]);

  let slides = Array.from(slideshow.children);

  let currentIndex = 1; // Inicia en el primer slide real (por el clon inicial)
  let itemsPerView = 1;
  let slideWidth = 0;
  let autoInterval = null;


  // =============================
  // BULLETS
  // =============================
  function createBullets() {
    bulletsContainer.innerHTML = "";
    for (let i = 0; i < totalOriginal; i++) {
      const b = document.createElement("button");
      b.className = "bullet";
      b.dataset.index = i;
      bulletsContainer.appendChild(b);
    }
  }
  createBullets();

  function updateBullets() {
    bulletsContainer.querySelectorAll(".bullet").forEach(b => b.classList.remove("active"));
    bulletsContainer.children[currentIndex - 1].classList.add("active");
  }


  // =============================
  // RESPONSIVE
  // =============================
function updateItemsPerView() {
  const w = wrapper.clientWidth; // 👈 ahora siempre mide el contenedor real

  if (w < 600) itemsPerView = 1;
  else if (w < 983) itemsPerView = 2;
  else if (w < 1366) itemsPerView = 3;
  else if (w < 1440) itemsPerView = 4;
  else itemsPerView = 5;

  updateSlideWidth();
  goToSlide(currentIndex, true);
}

function updateSlideWidth() {
  const containerWidth = wrapper.getBoundingClientRect().width; 
  // 👆 mucho más exacto que clientWidth, incluye tamaños reales sin scroll

  slideWidth =
    (containerWidth - (itemsPerView > 1 ? (itemsPerView - 1) * GAP : 0)) /
    itemsPerView;

  slideshow.style.gap = itemsPerView > 1 ? `${GAP}px` : "0px";

  slides.forEach(s => {
    s.style.minWidth = slideWidth + "px";
    s.style.maxWidth = slideWidth + "px"; // opcional pero recomendable
  });
}


  // =============================
  // ANIMACIONES EN VISTA
  // =============================
  function updateAnimations() {
    slides.forEach(s => s.classList.remove("animate-in"));

    for (let i = 0; i < itemsPerView; i++) {
      const visible = slides[currentIndex + i];
      if (visible) visible.classList.add("animate-in");
    }
  }


  // =============================
  // MOVIMIENTO
  // =============================
  function goToSlide(index, instant = false) {
    slideshow.style.transition = instant ? "none" : "transform 0.5s ease";

    const offset = -(slideWidth + GAP) * index;
    slideshow.style.transform = `translateX(${offset}px)`;

    currentIndex = index;

    if (index === 0) {
      setTimeout(() => {
        slideshow.style.transition = "none";
        currentIndex = totalOriginal;
        goToSlide(currentIndex, true);
      }, 510);
    }

    if (index === totalOriginal + 1) {
      setTimeout(() => {
        slideshow.style.transition = "none";
        currentIndex = 1;
        goToSlide(currentIndex, true);
      }, 510);
    }

    updateBullets();
    updateAnimations();
  }

  function next() {
    goToSlide(currentIndex + 1);
  }

  function prev() {
    goToSlide(currentIndex - 1);
  }

  navNext.addEventListener("click", next);
  navPrev.addEventListener("click", prev);


  // =============================
  // BULLETS
  // =============================
  bulletsContainer.querySelectorAll(".bullet").forEach(b =>
    b.addEventListener("click", () => {
      const realIndex = parseInt(b.dataset.index, 10) + 1;
      goToSlide(realIndex);
    })
  );


  // =============================
  // AUTO-SLIDE
  // =============================
  function startAuto() {
    stopAuto();
    autoInterval = setInterval(next, AUTO_TIME);
  }
  function stopAuto() {
    if (autoInterval) clearInterval(autoInterval);
  }

  wrapper.addEventListener("mouseenter", stopAuto);
  wrapper.addEventListener("mouseleave", startAuto);


  // =============================
  // RESIZE
  // =============================
  window.addEventListener("resize", updateItemsPerView);
  window.addEventListener("orientationchange", updateItemsPerView);


  // =============================
  // INICIALIZACIÓN
  // =============================
  updateItemsPerView();
  goToSlide(1, true);
  startAuto();
});