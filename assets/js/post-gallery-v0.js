// Encapsulamos todo en una función para no contaminar otros scripts
function initializePostGalleries() {
    // WeakSet para marcar las galerías ya inicializadas
    const initializedPostGalleries = new WeakSet();

    function initPostGallery(wpGalleryBlock) {
        if (initializedPostGalleries.has(wpGalleryBlock)) return;
        initializedPostGalleries.add(wpGalleryBlock);

        // ==============================
        // 2. Extracción de imágenes desde el bloque de WordPress
        // ==============================
        const wpFigures = Array.from(wpGalleryBlock.querySelectorAll("figure.wp-block-image"));
        if (wpFigures.length === 0) return;

        const images = wpFigures.map(fig => {
            const img = fig.querySelector("img");
            return {
                src: img.src,
                alt: img.alt || ""
            };
        });

        // ==============================
        // 3. Creación de la estructura de galería
        // ==============================
        const wrapper = document.createElement("div");
        wrapper.className = "post-gallery-wrapper";

        const gallery = document.createElement("div");
        gallery.className = "post-gallery";

        images.forEach(image => {
            const slide = document.createElement("div");
            slide.className = "post-gallery-slide";

            const img = document.createElement("img");
            img.src = image.src;
            img.alt = image.alt;
            img.loading = "lazy";

            slide.appendChild(img);
            gallery.appendChild(slide);
        });

        wrapper.appendChild(gallery);

        // Contenedor de thumbnails y botones
        const thumbsContainer = document.createElement("div");
        thumbsContainer.className = "post-gallery-thumbs-container";

        const prevBtn = document.createElement("button");
        prevBtn.className = "btn-pagination small-pagination";
        prevBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg>`; // SVG de flecha izquierda
        prevBtn.setAttribute("aria-label", "Anterior");

        const thumbsWrapper = document.createElement("ul");
        thumbsWrapper.className = "post-gallery-thumbs";

        const nextBtn = document.createElement("button");
        nextBtn.className = "btn-pagination small-pagination";
        nextBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg>`; // SVG de flecha derecha
        nextBtn.setAttribute("aria-label", "Siguiente");

        thumbsContainer.appendChild(prevBtn);
        thumbsContainer.appendChild(thumbsWrapper);
        thumbsContainer.appendChild(nextBtn);
        wrapper.appendChild(thumbsContainer);

        // ==============================
        // 4. Reemplazo del bloque original
        // ==============================
        wpGalleryBlock.parentNode.replaceChild(wrapper, wpGalleryBlock);

        // ==============================
        // 5. Configuración de slides y clonación para loop infinito
        // ==============================
        const originalSlides = Array.from(gallery.querySelectorAll(".post-gallery-slide"));
        const firstClone = originalSlides[0].cloneNode(true);
        const lastClone = originalSlides[originalSlides.length - 1].cloneNode(true);
        gallery.prepend(lastClone);
        gallery.appendChild(firstClone);

        const slides = gallery.querySelectorAll(".post-gallery-slide");
        const totalSlides = slides.length;
        const visibleSlides = originalSlides.length;
        let currentSlide = 1;
        let animationFrame;
        let isAnimating = false;

        // ==============================
        // 5.b Mostrar total de imágenes
        // ==============================
        const totalImages = document.createElement("div");
        totalImages.className = "total-images"; // Clase para tu div
        const bigGalleryIcon = `<svg width="16" height="16" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 1H12.5C13.3284 1 14 1.67157 14 2.5V12.5C14 13.3284 13.3284 14 12.5 14H2.5C1.67157 14 1 13.3284 1 12.5V2.5C1 1.67157 1.67157 1 2.5 1ZM2.5 2C2.22386 2 2 2.22386 2 2.5V8.3636L3.6818 6.6818C3.76809 6.59551 3.88572 6.54797 4.00774 6.55007C4.12975 6.55216 4.24568 6.60372 4.32895 6.69293L7.87355 10.4901L10.6818 7.6818C10.8575 7.50607 11.1425 7.50607 11.3182 7.6818L13 9.3636V2.5C13 2.22386 12.7761 2 12.5 2H2.5ZM2 12.5V9.6364L3.98887 7.64753L7.5311 11.4421L8.94113 13H2.5C2.22386 13 2 12.7761 2 12.5ZM12.5 13H10.155L8.48336 11.153L11 8.6364L13 10.6364V12.5C13 12.7761 12.7761 13 12.5 13ZM6.64922 5.5C6.64922 5.03013 7.03013 4.64922 7.5 4.64922C7.96987 4.64922 8.35078 5.03013 8.35078 5.5C8.35078 5.96987 7.96987 6.35078 7.5 6.35078C7.03013 6.35078 6.64922 5.96987 6.64922 5.5ZM7.5 3.74922C6.53307 3.74922 5.74922 4.53307 5.74922 5.5C5.74922 6.46693 6.53307 7.25078 7.5 7.25078C8.46693 7.25078 9.25078 6.46693 9.25078 5.5C9.25078 4.53307 8.46693 3.74922 7.5 3.74922Z" fill="currentColor"></path></svg>`;
        totalImages.innerHTML = `${bigGalleryIcon} ${totalSlides}`;

        // Lo añadimos al contenedor principal de la galería
        wrapper.appendChild(totalImages);


        // Estilos iniciales de la galería
        gallery.style.width = `${100 * totalSlides}%`;
        slides.forEach(slide => {
            slide.style.width = `${100 / totalSlides}%`;
            slide.style.transition = "transform 0.5s ease, opacity 0.5s ease";
            slide.style.transform = "scale(0.5)";
            slide.style.opacity = "0.5";
        });
        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

        // ==============================
        // 6. Creación y clonación de thumbnails
        // ==============================
        thumbsWrapper.innerHTML = "";
        images.forEach((image, index) => {
            const thumb = document.createElement("li");
            thumb.className = "post-gallery-thumb";
            if (index === 0) thumb.classList.add("active");
            thumb.dataset.index = index;

            const img = document.createElement("img");
            img.src = image.src;
            img.alt = image.alt;
            img.loading = "lazy";

            thumb.appendChild(img);
            thumbsWrapper.appendChild(thumb);
        });

        let originalThumbs = Array.from(thumbsWrapper.querySelectorAll(".post-gallery-thumb"));
        const ORIGINAL_COUNT = originalThumbs.length;
        const VISIBLE = 3;
        const GAP_PX = 8;

        thumbsWrapper.style.gap = GAP_PX + "px";
        thumbsWrapper.style.overflow = "hidden";
        thumbsWrapper.style.display = "flex";
        thumbsWrapper.style.alignItems = "center";
        thumbsWrapper.style.padding = "0";
        thumbsWrapper.style.margin = "0";
        thumbsWrapper.style.listStyle = "none";
        thumbsWrapper.style.boxSizing = "border-box";

        // Función para crear clones y permitir scroll infinito
        function buildThumbClones() {
            const clonesToPrepend = [];
            originalThumbs.forEach(t => clonesToPrepend.push(t.cloneNode(true)));
            originalThumbs.forEach(t => clonesToPrepend.push(t.cloneNode(true)));
            clonesToPrepend.reverse().forEach(c => thumbsWrapper.prepend(c));

            originalThumbs.forEach(t => thumbsWrapper.appendChild(t.cloneNode(true)));
            originalThumbs.forEach(t => thumbsWrapper.appendChild(t.cloneNode(true)));
        }
        buildThumbClones();

        let allThumbs = Array.from(thumbsWrapper.querySelectorAll(".post-gallery-thumb"));

        function updateThumbWidths() {
            const containerWidth = thumbsWrapper.clientWidth;
            const totalGaps = GAP_PX * (VISIBLE - 1);
            const itemWidth = Math.floor((containerWidth - totalGaps) / VISIBLE);

            allThumbs.forEach(t => {
                t.style.flex = `0 0 ${itemWidth}px`;
                t.style.width = `${itemWidth}px`;
                t.style.boxSizing = "border-box";
            });
        }
        updateThumbWidths();
        window.addEventListener("resize", () => {
            updateThumbWidths();
            requestAnimationFrame(() => centerActiveThumb());
        });

        // ==============================
        // 7. Funciones de centrado y scroll de thumbnails
        // ==============================
        function containerCenterX() {
            return thumbsWrapper.getBoundingClientRect().left + thumbsWrapper.clientWidth / 2;
        }

        function initialThumbScrollPosition() {
            const first = allThumbs[0];
            if (!first) return;
            const itemFull = first.getBoundingClientRect().width + GAP_PX;
            const clonesBefore = ORIGINAL_COUNT * 2;
            thumbsWrapper.scrollLeft = itemFull * clonesBefore;
        }
        initialThumbScrollPosition();

        function getRealActiveIndex(indexParam) {
            const idx = typeof indexParam === "number" ? indexParam : currentSlide;
            return ((idx - 1) % visibleSlides + visibleSlides) % visibleSlides;
        }

        function centerActiveThumb() {
            const activeDataIndex = getRealActiveIndex();
            const candidates = Array.from(thumbsWrapper.querySelectorAll(`.post-gallery-thumb[data-index="${activeDataIndex}"]`));
            if (!candidates.length) return;

            const containerCenter = containerCenterX();
            let best = candidates[0];
            let bestDist = Infinity;
            candidates.forEach(c => {
                const rect = c.getBoundingClientRect();
                const cx = rect.left + rect.width / 2;
                const dist = Math.abs(cx - containerCenter);
                if (dist < bestDist) { bestDist = dist; best = c; }
            });

            const bestRect = best.getBoundingClientRect();
            const wrapperRect = thumbsWrapper.getBoundingClientRect();
            const currentScroll = thumbsWrapper.scrollLeft;
            const bestCenterRelative = bestRect.left - wrapperRect.left + bestRect.width / 2;
            const desiredScroll = currentScroll + (bestCenterRelative - wrapperRect.width / 2);

            thumbsWrapper.scrollTo({ left: desiredScroll, behavior: "smooth" });
            allThumbs.forEach(t => t.classList.toggle("active", t.dataset.index === String(activeDataIndex)));
        }

        // ==============================
        // 8. Drag y touch en thumbnails
        // ==============================
        (function enableThumbDrag() {
            let isDown = false, startX = 0, scrollStart = 0, isDragging = false;

            thumbsWrapper.addEventListener("mousedown", e => {
                isDown = true;
                startX = e.pageX - thumbsWrapper.getBoundingClientRect().left;
                scrollStart = thumbsWrapper.scrollLeft;
                thumbsWrapper.classList.add("is-dragging");
            });
            document.addEventListener("mousemove", e => {
                if (!isDown) return;
                const x = e.pageX - thumbsWrapper.getBoundingClientRect().left;
                const walk = startX - x;
                if (Math.abs(walk) > 3) isDragging = true;
                thumbsWrapper.scrollLeft = scrollStart + walk;
            });
            document.addEventListener("mouseup", () => {
                if (!isDown) return;
                isDown = false;
                thumbsWrapper.classList.remove("is-dragging");
                setTimeout(() => isDragging = false, 50);
            });

            // Touch
            let tStartX = 0, tScrollStart = 0;
            thumbsWrapper.addEventListener("touchstart", e => {
                tStartX = e.touches[0].pageX - thumbsWrapper.getBoundingClientRect().left;
                tScrollStart = thumbsWrapper.scrollLeft;
            }, { passive: true });
            thumbsWrapper.addEventListener("touchmove", e => {
                const x = e.touches[0].pageX - thumbsWrapper.getBoundingClientRect().left;
                const walk = tStartX - x;
                thumbsWrapper.scrollLeft = tScrollStart + walk;
            }, { passive: true });

            thumbsWrapper.addEventListener("click", e => {
                if (isDragging) { e.stopPropagation(); e.preventDefault(); }
            }, true);
        })();

        // ==============================
        // 9. Gestión de slides: navegación y animación
        // ==============================
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
            centerActiveThumb();
        }

        function handleInfiniteLoop() {
            if (currentSlide === 0) currentSlide = visibleSlides;
            else if (currentSlide === totalSlides - 1) currentSlide = 1;
            else return false;

            gallery.style.transition = "none";
            slides.forEach(s => s.style.transition = "none");
            gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

            requestAnimationFrame(() => {
                gallery.style.transition = "";
                slides.forEach(s => s.style.transition = "transform 0.5s ease, opacity 0.5s ease");
                updateActiveClasses();
                isAnimating = false;
            });

            return true;
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

                    if (progress < 1) requestAnimationFrame(animate);
                    else {
                        currentSlide = targetIndex;
                        if (!handleInfiniteLoop()) {
                            updateActiveClasses();
                            isAnimating = false;
                        }
                    }
                }
                requestAnimationFrame(animate);
            }, 500);
        }

        // ==============================
        // 10. Eventos de interacción
        // ==============================
        thumbsWrapper.addEventListener("click", e => {
            const t = e.target.closest(".post-gallery-thumb");
            if (!t) return;
            goToSlide(parseInt(t.dataset.index, 10) + 1);
            resetAutoSlide();
        });

        let startXTouch = 0, endXTouch = 0, threshold = 50;
        gallery.addEventListener("touchstart", e => startXTouch = e.touches[0].clientX, { passive: true });
        gallery.addEventListener("touchmove", e => endXTouch = e.touches[0].clientX, { passive: true });
        gallery.addEventListener("touchend", () => {
            const deltaX = endXTouch - startXTouch;
            if (Math.abs(deltaX) > threshold) goToSlide(deltaX < 0 ? currentSlide + 1 : currentSlide - 1);
            startXTouch = endXTouch = 0;
            resetAutoSlide();
        });

        prevBtn.addEventListener("click", () => { goToSlide(currentSlide - 1); resetAutoSlide(); });
        nextBtn.addEventListener("click", () => { goToSlide(currentSlide + 1); resetAutoSlide(); });

        let autoSlide = setInterval(() => goToSlide(currentSlide + 1), 10000);
        function resetAutoSlide() {
            clearInterval(autoSlide);
            autoSlide = setInterval(() => goToSlide(currentSlide + 1), 10000);
        }
        wrapper.addEventListener("mouseenter", () => clearInterval(autoSlide));
        wrapper.addEventListener("mouseleave", resetAutoSlide);

        updateActiveClasses();
        setTimeout(() => { initialThumbScrollPosition(); centerActiveThumb(); }, 60);

        // ==============================
        // 12. Lightbox
        // ==============================
        (function setupLightbox() {
            const lightbox = document.createElement("div");
            lightbox.className = "pg-lightbox";
            lightbox.style.cssText = `
                position: fixed; inset: 0; background: rgba(0,0,0,.85);
                display: none; align-items: center; justify-content: center;
                z-index: 999999; cursor: zoom-out;
            `;

            const img = document.createElement("img");
            img.className = "pg-lightbox-img";
            img.style.cssText = "max-width:95%; max-height:95%; object-fit:contain; cursor:default;";

            const closeBtn = document.createElement("button");
            closeBtn.innerHTML = "✕";
            closeBtn.setAttribute("aria-label", "Cerrar");
            closeBtn.style.cssText = "position: fixed; top:20px; right:20px; font-size:28px; background:transparent; color:white; border:none; cursor:pointer;";

            const prevL = document.createElement("div");
            prevL.textContent = "‹";
            prevL.style.cssText = "position:fixed; left:30px; font-size:60px; color:white; cursor:pointer; user-select:none;";

            const nextL = document.createElement("div");
            nextL.textContent = "›";
            nextL.style.cssText = "position:fixed; right:30px; font-size:60px; color:white; cursor:pointer; user-select:none;";

            lightbox.append(img, closeBtn, prevL, nextL);
            document.body.appendChild(lightbox);

            let lbIndex = 0;
            function openLightbox(index) {
                lbIndex = index;
                img.src = slides[index].querySelector("img").src;
                lightbox.style.display = "flex";
            }
            function closeLightbox() { lightbox.style.display = "none"; }
            function lbGo(delta) {
                lbIndex += delta;
                if (lbIndex <= 0) lbIndex = visibleSlides;
                else if (lbIndex >= totalSlides - 1) lbIndex = 1;
                goToSlide(lbIndex);
                img.src = slides[lbIndex].querySelector("img").src;
            }

            slides.forEach((slide, i) => {
                slide.querySelector("img").addEventListener("click", e => { e.stopPropagation(); openLightbox(i); });
            });
            closeBtn.addEventListener("click", closeLightbox);
            lightbox.addEventListener("click", e => { if (e.target === lightbox) closeLightbox(); });
            prevL.addEventListener("click", e => { e.stopPropagation(); lbGo(-1); });
            nextL.addEventListener("click", e => { e.stopPropagation(); lbGo(1); });

            document.addEventListener("keydown", e => {
                if (lightbox.style.display !== "flex") return;
                if (e.key === "Escape") closeLightbox();
                if (e.key === "ArrowRight") lbGo(1);
                if (e.key === "ArrowLeft") lbGo(-1);
            });

            let lx = 0;
            lightbox.addEventListener("touchstart", e => lx = e.touches[0].clientX, { passive: true });
            lightbox.addEventListener("touchend", e => {
                const dx = e.changedTouches[0].clientX - lx;
                if (Math.abs(dx) > 50) lbGo(dx < 0 ? 1 : -1);
            });
        })();
    }

    // ==============================
    // 13. Detección de nuevos bloques
    // ==============================
    function initAllPostGalleries() {
        document.querySelectorAll(".wp-block-gallery").forEach(initPostGallery);
    }

    const observer = new MutationObserver(() => initAllPostGalleries());
    observer.observe(document.body, { childList: true, subtree: true });

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initAllPostGalleries);
    } else {
        initAllPostGalleries();
    }
}

// Solo llamamos a la función cuando la necesitemos
initializePostGalleries();