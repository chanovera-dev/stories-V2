// Usamos WeakSet para marcar wrappers ya inicializados (no se clona)
const initializedGalleries = new WeakSet();

function initGallery(wrapper) {
    if (initializedGalleries.has(wrapper)) return; // ya inicializada
    initializedGalleries.add(wrapper);

    const gallery = wrapper.querySelector(".gallery");
    const originalSlides = Array.from(wrapper.querySelectorAll(".gallery > *"));
    const bulletsWrapper = wrapper.querySelector(".gallery-bullets");

    if (!gallery || originalSlides.length === 0 || !bulletsWrapper) return;

    gallery.style.display = "flex";
    gallery.style.height = "calc(100% - 48px)";

    // Clonar para efecto infinito
    const firstClone = originalSlides[0].cloneNode(true);
    const lastClone = originalSlides[originalSlides.length - 1].cloneNode(true);
    gallery.prepend(lastClone);
    gallery.appendChild(firstClone);

    const slides = gallery.querySelectorAll(".gallery > *");
    const totalSlides = slides.length;
    const visibleSlides = originalSlides.length;

    let currentSlide = 1;
    let animationFrame;
    let isAnimating = false;

    // Ajustar anchos
    gallery.style.width = `${100 * totalSlides}%`;
    slides.forEach(slide => {
        slide.style.width = `${100 / totalSlides}%`;
        slide.style.transition = "transform 0.5s ease, opacity 0.5s ease";
        slide.style.transform = "scale(0.5)";
        slide.style.opacity = "0.25";
    });

    gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

    // Crear bullets
    bulletsWrapper.innerHTML = "";
    originalSlides.forEach((_, index) => {
        const bullet = document.createElement("li");
        bullet.classList.add("gallery-bullet");
        if (index === 0) bullet.classList.add("active");
        bullet.dataset.index = index;
        bulletsWrapper.appendChild(bullet);
    });

    const bullets = bulletsWrapper.querySelectorAll(".gallery-bullet");

    function updateActiveClasses(index = currentSlide, shouldGrow = true) {
        slides.forEach(slide => {
            slide.classList.remove("active");
            slide.style.transform = "scale(0.5)";
            slide.style.opacity = "0.25";
        });

        if (shouldGrow && slides[index]) {
            slides[index].classList.add("active");
            slides[index].style.transform = "scale(1)";
            slides[index].style.opacity = "1";
        }

        let realIndex = index - 1;
        if (realIndex < 0) realIndex = visibleSlides - 1;
        if (realIndex >= visibleSlides) realIndex = 0;

        bullets.forEach((btn, i) => {
            btn.classList.toggle("active", i === realIndex);
        });
    }

    function goToSlide(targetIndex) {
        if (isAnimating) return;
        isAnimating = true;

        updateActiveClasses(targetIndex, false);

        setTimeout(() => {
            const from = (100 / totalSlides) * currentSlide;
            const to = (100 / totalSlides) * targetIndex;
            const distance = to - from;
            const duration = 400;
            const startTime = performance.now();

            function animate(time) {
                const elapsed = time - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = from + distance * progress;
                gallery.style.transform = `translateX(-${current}%)`;

                if (progress < 1) {
                    animationFrame = requestAnimationFrame(animate);
                } else {
                    cancelAnimationFrame(animationFrame);
                    currentSlide = targetIndex;

                    if (currentSlide === 0) {
                        currentSlide = visibleSlides;
                        gallery.style.transition = "none";
                        slides.forEach(s => s.style.transition = "none");
                        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;
                        requestAnimationFrame(() => {
                            gallery.style.transition = "";
                            slides.forEach(s => s.style.transition = "transform 0.5s ease, opacity 0.5s ease");
                            updateActiveClasses();
                            isAnimating = false;
                        });
                    } else if (currentSlide === totalSlides - 1) {
                        currentSlide = 1;
                        gallery.style.transition = "none";
                        slides.forEach(s => s.style.transition = "none");
                        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;
                        requestAnimationFrame(() => {
                            gallery.style.transition = "";
                            slides.forEach(s => s.style.transition = "transform 0.5s ease, opacity 0.5s ease");
                            updateActiveClasses();
                            isAnimating = false;
                        });
                    } else {
                        updateActiveClasses();
                        isAnimating = false;
                    }
                }
            }

            cancelAnimationFrame(animationFrame);
            animationFrame = requestAnimationFrame(animate);
        }, 500);
    }

    bulletsWrapper.addEventListener("click", e => {
        if (e.target.classList.contains("gallery-bullet")) {
            const index = parseInt(e.target.dataset.index, 10);
            goToSlide(index + 1);
            resetAutoSlide();
        }
    });

    // Swipe
    let startX = 0;
    let endX = 0;
    const threshold = 50;

    gallery.addEventListener("touchstart", e => startX = e.touches[0].clientX, { passive: true });
    gallery.addEventListener("touchmove", e => endX = e.touches[0].clientX, { passive: true });
    gallery.addEventListener("touchend", () => {
        const deltaX = endX - startX;
        if (Math.abs(deltaX) > threshold) {
            if (deltaX < 0) goToSlide(currentSlide + 1);
            else goToSlide(currentSlide - 1);
        }
        startX = 0;
        endX = 0;
    });

    // Botones prev/next
    const prevBtn = wrapper.querySelector(".gallery-prev");
    const nextBtn = wrapper.querySelector(".gallery-next");

    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            goToSlide(currentSlide - 1);
            resetAutoSlide();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            goToSlide(currentSlide + 1);
            resetAutoSlide();
        });
    }

    updateActiveClasses();

    let autoSlide = setInterval(() => {
        goToSlide(currentSlide + 1);
    }, 14000);

    function resetAutoSlide() {
        clearInterval(autoSlide);
        autoSlide = setInterval(() => {
            goToSlide(currentSlide + 1);
        }, 10000);
    }

    wrapper.addEventListener("mouseenter", () => clearInterval(autoSlide));
    wrapper.addEventListener("mouseleave", () => resetAutoSlide());
}

function initAllGalleries() {
    document.querySelectorAll(".gallery-wrapper").forEach(initGallery);
}

// Observador: cuando aparezcan nodos nuevos, re-intenta inicializar
const observer = new MutationObserver((mutations) => {
    // Llamamos a initAllGalleries — initGallery ignora ya inicializadas por WeakSet
    initAllGalleries();
});

observer.observe(document.body, { childList: true, subtree: true });

// Inicial al cargar
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAllGalleries);
} else {
    initAllGalleries();
}