// Usamos WeakSet para marcar wrappers ya inicializados
const initializedGalleries = new WeakSet();

function initGallery(wrapper) {
    if (initializedGalleries.has(wrapper)) return;
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

    // Configurar dimensiones y estilos iniciales
    gallery.style.width = `${100 * totalSlides}%`;
    slides.forEach(slide => {
        slide.style.width = `${100 / totalSlides}%`;
        slide.style.transition = "transform 0.5s ease, opacity 0.5s ease";
        slide.style.transform = "scale(0.5)";
        slide.style.opacity = "0.5";
    });

    gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

    // Crear bullets
    bulletsWrapper.innerHTML = "";
    const bigGalleryIcon = `<svg width="16" height="16" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 1H12.5C13.3284 1 14 1.67157 14 2.5V12.5C14 13.3284 13.3284 14 12.5 14H2.5C1.67157 14 1 13.3284 1 12.5V2.5C1 1.67157 1.67157 1 2.5 1ZM2.5 2C2.22386 2 2 2.22386 2 2.5V8.3636L3.6818 6.6818C3.76809 6.59551 3.88572 6.54797 4.00774 6.55007C4.12975 6.55216 4.24568 6.60372 4.32895 6.69293L7.87355 10.4901L10.6818 7.6818C10.8575 7.50607 11.1425 7.50607 11.3182 7.6818L13 9.3636V2.5C13 2.22386 12.7761 2 12.5 2H2.5ZM2 12.5V9.6364L3.98887 7.64753L7.5311 11.4421L8.94113 13H2.5C2.22386 13 2 12.7761 2 12.5ZM12.5 13H10.155L8.48336 11.153L11 8.6364L13 10.6364V12.5C13 12.7761 12.7761 13 12.5 13ZM6.64922 5.5C6.64922 5.03013 7.03013 4.64922 7.5 4.64922C7.96987 4.64922 8.35078 5.03013 8.35078 5.5C8.35078 5.96987 7.96987 6.35078 7.5 6.35078C7.03013 6.35078 6.64922 5.96987 6.64922 5.5ZM7.5 3.74922C6.53307 3.74922 5.74922 4.53307 5.74922 5.5C5.74922 6.46693 6.53307 7.25078 7.5 7.25078C8.46693 7.25078 9.25078 6.46693 9.25078 5.5C9.25078 4.53307 8.46693 3.74922 7.5 3.74922Z" fill="currentColor"></path></svg>`;
    if (originalSlides.length > 5) {
        bulletsWrapper.style.display = "";
        bulletsWrapper.innerHTML = `${bigGalleryIcon} ${totalSlides - 2}`;

    } else {
        bulletsWrapper.style.display = ""; // Reset display just in case
        originalSlides.forEach((_, index) => {
            const bullet = document.createElement("li");
            bullet.classList.add("gallery-bullet");
            if (index === 0) bullet.classList.add("active");
            bullet.dataset.index = index;
            bulletsWrapper.appendChild(bullet);
        });
    }

    const bullets = bulletsWrapper.querySelectorAll(".gallery-bullet");

    // Actualizar clases activas y estilos
    function updateActiveClasses(index = currentSlide, shouldGrow = true) {
        slides.forEach(slide => {
            slide.classList.remove("active");
            slide.style.transform = "scale(0.5)";
            slide.style.opacity = "0.5";
        });

        if (shouldGrow && slides[index]) {
            slides[index].classList.add("active");
            slides[index].style.transform = "scale(1)";
            slides[index].style.opacity = "1";
        }

        // Calcular índice real para bullets
        const realIndex = ((index - 1) % visibleSlides + visibleSlides) % visibleSlides;
        bullets.forEach((btn, i) => btn.classList.toggle("active", i === realIndex));
    }

    // Manejar salto instantáneo en bucle infinito
    function handleInfiniteLoop() {
        if (currentSlide === 0) {
            currentSlide = visibleSlides;
        } else if (currentSlide === totalSlides - 1) {
            currentSlide = 1;
        } else {
            return false; // No hay salto
        }

        // Salto instantáneo sin transición
        gallery.style.transition = "none";
        slides.forEach(s => s.style.transition = "none");
        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

        requestAnimationFrame(() => {
            gallery.style.transition = "";
            slides.forEach(s => s.style.transition = "transform 0.5s ease, opacity 0.5s ease");
            updateActiveClasses();
            isAnimating = false;
        });

        return true; // Hubo salto
    }

    // Función principal de navegación
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

                    if (!handleInfiniteLoop()) {
                        updateActiveClasses();
                        isAnimating = false;
                    }
                }
            }

            cancelAnimationFrame(animationFrame);
            animationFrame = requestAnimationFrame(animate);
        }, 500);
    }

    // Event listeners para bullets
    bulletsWrapper.addEventListener("click", e => {
        if (e.target.classList.contains("gallery-bullet")) {
            const index = parseInt(e.target.dataset.index, 10);
            goToSlide(index + 1);
            resetAutoSlide();
        }
    });

    // Swipe gestures
    let startX = 0;
    let endX = 0;
    const threshold = 50;

    gallery.addEventListener("touchstart", e => startX = e.touches[0].clientX, { passive: true });
    gallery.addEventListener("touchmove", e => endX = e.touches[0].clientX, { passive: true });
    gallery.addEventListener("touchend", () => {
        const deltaX = endX - startX;
        if (Math.abs(deltaX) > threshold) {
            goToSlide(deltaX < 0 ? currentSlide + 1 : currentSlide - 1);
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

    // Auto-slide
    let autoSlide = setInterval(() => goToSlide(currentSlide + 1), 14000);

    function resetAutoSlide() {
        clearInterval(autoSlide);
        autoSlide = setInterval(() => goToSlide(currentSlide + 1), 10000);
    }

    wrapper.addEventListener("mouseenter", () => clearInterval(autoSlide));
    wrapper.addEventListener("mouseleave", resetAutoSlide);
}

function initAllGalleries() {
    document.querySelectorAll(".gallery-wrapper").forEach(initGallery);
}

// Observador: cuando aparezcan nodos nuevos, re-intenta inicializar
const observer = new MutationObserver(() => initAllGalleries());
observer.observe(document.body, { childList: true, subtree: true });

// Inicial al cargar
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAllGalleries);
} else {
    initAllGalleries();
}