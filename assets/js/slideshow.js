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
  navigation.style.display = "flex";

  // SLIDES
  let slides = Array.from(slideshow.children);
  const totalOriginal = slides.length;
  let itemsPerView = 1;
  let slideWidth = 0;
  let autoInterval = null;
  let firstLoad = true;


  // BULLETS
  function createBullets() {
    bulletsContainer.innerHTML = "";
    for (let i = 0; i < totalOriginal; i++) {
      const b = document.createElement("button");
      b.className = "bullet";
      b.dataset.id = slides[i].dataset.id; // id único por slide
      bulletsContainer.appendChild(b);
    }
  }
  createBullets();


  function updateBullets() {
    const bullets = bulletsContainer.querySelectorAll(".bullet");
    bullets.forEach(b => b.classList.remove("active"));

    const activeId = slides[0].dataset.id;
    bullets.forEach(b => {
      if (b.dataset.id === activeId) b.classList.add("active");
    });
  }


  // RESPONSIVE
  function updateItemsPerView() {
    const w = wrapper.clientWidth;

    if (w < 600) itemsPerView = 1;
    else if (w < 983) itemsPerView = 2;
    else if (w < 1326) itemsPerView = 3;
    else if (w < 1440) itemsPerView = 4;
    else itemsPerView = 5;

    updateSlideWidth();
    updateBullets();
  }

  function updateSlideWidth() {
    const containerWidth = wrapper.getBoundingClientRect().width;

    slideWidth =
      (containerWidth - (itemsPerView > 1 ? (itemsPerView - 1) * GAP : 0)) /
      itemsPerView;

    slideshow.style.gap = itemsPerView > 1 ? `${GAP}px` : "0px";

    slides.forEach(s => {
      s.style.minWidth = slideWidth + "px";
      s.style.maxWidth = slideWidth + "px";
    });
  }


  // ANIMACIONES
  function updateAnimations() {
    if (firstLoad) return;

    slides.forEach(s => s.classList.remove("animate-in"));

    for (let i = 0; i < itemsPerView; i++) {
      const visible = slides[i];
      if (!visible) continue;
      visible.classList.add("animate-in");
    }
  }

  // MOVIMIENTO CIRCULAR REAL
  function next() {
    slideshow.style.transition = "all .5s ease-in-out";
    slideshow.style.transform = `translateX(-${slideWidth}px)`;

    setTimeout(function () {
      slideshow.style.transition = "none";

      const first = slides[0];
      const clone = first.cloneNode(true);

      slideshow.appendChild(clone);
      slideshow.removeChild(first);

      slideshow.style.transform = `translateX(0)`;

      slides = Array.from(slideshow.children);

      requestAnimationFrame(() => {
        slideshow.style.transition = "all .5s ease-in-out";
      });

      updateSlideWidth();
      updateBullets();
      updateAnimations();
    }, 500);
  }

  function prev() {
    slideshow.style.transition = "none";

    const last = slides[slides.length - 1];
    const clone = last.cloneNode(true);

    slideshow.insertBefore(clone, slides[0]);
    slideshow.removeChild(last);

    slides = Array.from(slideshow.children);

    slideshow.style.transform = `translateX(-${slideWidth}px)`;

    requestAnimationFrame(() => {
      slideshow.style.transition = "all .5s ease-in-out";
      slideshow.style.transform = `translateX(0)`;
    });

    updateSlideWidth();
    updateBullets();
    updateAnimations();
  }


  // NAVEGACIÓN
  navNext.addEventListener("click", next);
  navPrev.addEventListener("click", prev);


  // BULLETS (SALTO PROGRAMADO)
  let bulletJumping = false;

  function goToSlideById(targetId) {
    if (bulletJumping) return;
    bulletJumping = true;

    function step() {
      if (slides[0].dataset.id === targetId) {
        bulletJumping = false;
        return;
      }

      next();
      setTimeout(step, 520); // esperar animación
    }

    step();
  }

  bulletsContainer.querySelectorAll(".bullet").forEach(b =>
    b.addEventListener("click", () => {
      const targetId = b.dataset.id;
      goToSlideById(targetId);
    })
  );


  // AUTO-SLIDE
  function startAuto() {
    stopAuto();
    autoInterval = setInterval(next, AUTO_TIME);
  }
  function stopAuto() {
    if (autoInterval) clearInterval(autoInterval);
  }

  wrapper.addEventListener("mouseenter", stopAuto);
  wrapper.addEventListener("mouseleave", startAuto);


  // RESIZE
  window.addEventListener("resize", updateItemsPerView);
  window.addEventListener("orientationchange", updateItemsPerView);


  // INICIALIZACIÓN
  updateItemsPerView();

  requestAnimationFrame(() => {
    // animateInitialStagger();
    updateBullets();
    startAuto();
  });

});