function showGallery() {
    const wrappers = document.querySelectorAll(".gallery-wrapper");

    wrappers.forEach((wrapper) => {
        const gallery = wrapper.querySelector(".gallery");
        const originalSlides = Array.from(wrapper.querySelectorAll(".gallery > *"));
        const bulletsWrapper = wrapper.querySelector(".gallery-bullets");

        if (!gallery || originalSlides.length === 0 || !bulletsWrapper) return;

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

        function updateActiveClasses() {
            originalSlides.forEach(slide => slide.classList.remove("active"));

            const realIndex = currentSlide - 1;
            if (realIndex >= 0 && realIndex < visibleSlides) {
                originalSlides[realIndex].classList.add("active");
            }

            bullets.forEach((btn, i) => {
                btn.classList.toggle("active", i === realIndex);
            });
        }

        function goToSlide(targetIndex) {
            if (isAnimating) return;
            isAnimating = true;

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
                        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;
                        requestAnimationFrame(() => gallery.style.transition = "");
                    } else if (currentSlide === totalSlides - 1) {
                        currentSlide = 1;
                        gallery.style.transition = "none";
                        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;
                        requestAnimationFrame(() => gallery.style.transition = "");
                    }

                    updateActiveClasses();
                    isAnimating = false;
                }
            }

            cancelAnimationFrame(animationFrame);
            animationFrame = requestAnimationFrame(animate);
        }

        bulletsWrapper.addEventListener("click", function (e) {
            if (e.target.classList.contains("gallery-bullet")) {
                const index = parseInt(e.target.dataset.index);
                goToSlide(index + 1);
                resetAutoSlide();
            }
        });

        // Swipe
        let startX = 0;
        let endX = 0;
        const threshold = 50;

        gallery.addEventListener("touchstart", e => startX = e.touches[0].clientX);
        gallery.addEventListener("touchmove", e => endX = e.touches[0].clientX);
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
    });
}

// Ejecutar automáticamente
document.addEventListener("DOMContentLoaded", showGallery);