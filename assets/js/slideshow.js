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
  wrapper.style.display = "flex";
  wrapper.style.flexDirection = "column";
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
    // iniciamos movimiento visual
    slideshow.style.transition = "all .5s ease-in-out";
    slideshow.style.transform = `translateX(-${slideWidth}px)`;

    setTimeout(function () {
      // detener transición momentáneamente para reordenar DOM
      slideshow.style.transition = "none";

      const first = slides[0];
      const clone = first.cloneNode(true);
      clone.classList.remove("animate-in"); // evitar heredar animación

      slideshow.appendChild(clone);
      slideshow.removeChild(first);

      // resetear transform a 0 (ya hicimos el desplazamiento visual)
      slideshow.style.transform = `translateX(0)`;

      // actualizar lista de slides y tamaños
      slides = Array.from(slideshow.children);
      updateSlideWidth();
      updateBullets();

      // ------ control manual de animate-in (evita race con updateAnimations) ------
      // limpiar clases previas
      slides.forEach(s => s.classList.remove("animate-in"));

      // forzar reflow para que el navegador registre el cambio de DOM/estilos
      void slideshow.offsetWidth;

      // añadir animate-in a los visibles (primeros itemsPerView)
      for (let i = 0; i < Math.min(itemsPerView, slides.length); i++) {
        const el = slides[i];
        if (el) el.classList.add("animate-in");
      }
      // ---------------------------------------------------------------------------

      // reactivar transición si necesitas (aquí lo dejamos por compatibilidad)
      requestAnimationFrame(() => {
        slideshow.style.transition = "all .5s ease-in-out";
      });
    }, 500);
  }

  function prev() {
    slideshow.style.transition = "none";

    const last = slides[slides.length - 1];
    const clone = last.cloneNode(true);

    slideshow.insertBefore(clone, slides[0]);
    clone.classList.remove("animate-in");
    slideshow.removeChild(last);

    slides = Array.from(slideshow.children);

    slideshow.style.transform = `translateX(-${slideWidth}px)`;

    requestAnimationFrame(() => {
      slideshow.style.transition = "all .5s ease-in-out";
      slideshow.style.transform = `translateX(0)`;

      // 🔥 Delay de 0.3 segundos antes de agregar animate-in
      setTimeout(() => {
        clone.classList.add("animate-in");
      }, 500);
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
    updateBullets();
    startAuto();
  });

  // ----------------------------------------
  // TOUCH / SWIPE SUPPORT
  // ----------------------------------------

  let touchStartX = 0;
  let touchEndX = 0;
  const SWIPE_THRESHOLD = 50; // mínimo px para considerar un swipe

  function handleTouchStart(e) {
    stopAuto(); // detener autoSlide mientras el usuario interactúa
    touchStartX = e.touches[0].clientX;
    touchEndX = touchStartX;
  }

  function handleTouchMove(e) {
    touchEndX = e.touches[0].clientX;
  }

  function handleTouchEnd() {
    const dx = touchEndX - touchStartX;

    if (Math.abs(dx) > SWIPE_THRESHOLD) {
      if (dx < 0) next(); // swipe hacia la izquierda
      else prev();        // swipe hacia la derecha
    }

    startAuto(); // reanudar autoSlide
  }

  // Añadir listeners directamente al wrapper
  wrapper.addEventListener("touchstart", handleTouchStart, { passive: true });
  wrapper.addEventListener("touchmove", handleTouchMove, { passive: true });
  wrapper.addEventListener("touchend", handleTouchEnd, { passive: true });

});