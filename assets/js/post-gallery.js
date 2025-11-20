// post-gallery-full.js
// Galería de posts + thumbs infinito de 3 visibles con drag (lista para pegar).
(function () {
    // --- Conserva el mismo WeakSet que usabas ---
    const initializedPostGalleries = new WeakSet();

    function initPostGallery(wpGalleryBlock) {
        if (initializedPostGalleries.has(wpGalleryBlock)) return;
        initializedPostGalleries.add(wpGalleryBlock);

        // Extraer imágenes originales del bloque de WordPress
        const wpFigures = Array.from(wpGalleryBlock.querySelectorAll("figure.wp-block-image"));
        if (wpFigures.length === 0) return;

        const images = wpFigures.map(fig => {
            const img = fig.querySelector("img");
            return {
                src: img.src,
                alt: img.alt || ""
            };
        });

        // Crear estructura de galería
        const wrapper = document.createElement("div");
        wrapper.className = "post-gallery-wrapper";

        const gallery = document.createElement("div");
        gallery.className = "post-gallery";

        // Crear slides originales
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

        // Crear container de thumbnails
        const thumbsContainer = document.createElement("div");
        thumbsContainer.className = "post-gallery-thumbs-container";

        const prevBtn = document.createElement("button");
        prevBtn.className = "btn-pagination small-pagination";
        prevBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"></path></svg>`;
        prevBtn.setAttribute("aria-label", "Anterior");

        const thumbsWrapper = document.createElement("ul");
        thumbsWrapper.className = "post-gallery-thumbs";

        const nextBtn = document.createElement("button");
        nextBtn.className = "btn-pagination small-pagination";
        nextBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"></path></svg>`;
        nextBtn.setAttribute("aria-label", "Siguiente");

        thumbsContainer.appendChild(prevBtn);
        thumbsContainer.appendChild(thumbsWrapper);
        thumbsContainer.appendChild(nextBtn);
        wrapper.appendChild(thumbsContainer);

        // Reemplazar galería de WordPress
        wpGalleryBlock.parentNode.replaceChild(wrapper, wpGalleryBlock);

        // --- Iniciar lógica de galería (base de tu script) ---
        const originalSlides = Array.from(gallery.querySelectorAll(".post-gallery-slide"));

        thumbsWrapper.style.display = "flex";
        gallery.style.display = "flex";
        gallery.style.height = "auto";

        // Clonar para efecto infinito en la galería principal (tu lógica original)
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

        // Configurar dimensiones y estilos iniciales de la galería
        gallery.style.width = `${100 * totalSlides}%`;
        slides.forEach(slide => {
            slide.style.width = `${100 / totalSlides}%`;
            slide.style.transition = "transform 0.5s ease, opacity 0.5s ease";
            slide.style.transform = "scale(0.5)";
            slide.style.opacity = "0.5";
        });

        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

        // Crear thumbnails (ORIGINALES)
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

        // ---- Aquí empieza la lógica del carrusel infinito para thumbs (3 visibles) ----
        let originalThumbs = Array.from(thumbsWrapper.querySelectorAll(".post-gallery-thumb"));
        const ORIGINAL_COUNT = originalThumbs.length;

        // Configuraciones
        const VISIBLE = 3;
        const GAP_PX = 8; // gap configurable
        thumbsWrapper.style.gap = GAP_PX + "px";
        thumbsWrapper.style.overflow = "hidden";
        thumbsWrapper.style.padding = "0";
        thumbsWrapper.style.margin = "0";
        thumbsWrapper.style.listStyle = "none";
        thumbsWrapper.style.display = "flex";
        thumbsWrapper.style.alignItems = "center";
        thumbsWrapper.style.boxSizing = "border-box";

        // Creamos clones: una estrategia segura es tener 2 copias antes y 2 después (3 bloques totales)
        // para evitar saltos visuales cuando centramos el elemento central.
        function buildThumbClones() {
            // Guarda los originales por dataset.index
            originalThumbs = Array.from(thumbsWrapper.querySelectorAll(".post-gallery-thumb")).slice(0, ORIGINAL_COUNT);
            // Limpia cualquier clone previo (si alguna re-inicialización ocurre)
            // Para evitar borrar los originales, forzamos un contenedor temporal
            const temp = document.createDocumentFragment();

            // Copiamos 2 veces al principio y 2 veces al final: total 3 bloques (prev + orig + next)
            // Haremos: prepend(2x original), append(2x original)
            // Primero, appends
            originalThumbs.forEach(t => thumbsWrapper.appendChild(t.cloneNode(true)));
            originalThumbs.forEach(t => thumbsWrapper.appendChild(t.cloneNode(true)));
            // Ahora, prepends (en rueda) — agregamos en orden inverso para mantener secuencia
            const clonesToPrepend = [];
            originalThumbs.forEach(t => clonesToPrepend.push(t.cloneNode(true)));
            originalThumbs.forEach(t => clonesToPrepend.push(t.cloneNode(true)));
            // prepend en orden correcto
            clonesToPrepend.reverse().forEach(c => thumbsWrapper.prepend(c));

            // Actualizamos colección completa
        }
        buildThumbClones();

        let allThumbs = Array.from(thumbsWrapper.querySelectorAll(".post-gallery-thumb"));

        // Medir y aplicar anchura para 3 visibles
        function updateThumbWidths() {
            // ancho contenedor
            const containerWidth = thumbsWrapper.clientWidth;
            // cada uno ocupa (container - gaps totales) / VISIBLE
            const totalGaps = GAP_PX * (VISIBLE - 1);
            const itemWidth = Math.floor((containerWidth - totalGaps) / VISIBLE);

            allThumbs = Array.from(thumbsWrapper.querySelectorAll(".post-gallery-thumb"));
            allThumbs.forEach(t => {
                t.style.flex = `0 0 ${itemWidth}px`;
                t.style.width = `${itemWidth}px`;
                t.style.boxSizing = "border-box";
            });
        }

        // Inicializa tamaños
        updateThumbWidths();
        window.addEventListener("resize", () => {
            updateThumbWidths();
            // aseguramos realineación luego del resize
            requestAnimationFrame(() => centerActiveThumb());
        });

        // Posicionar scroll al bloque central (los originales)
        function initialThumbScrollPosition() {
            allThumbs = Array.from(thumbsWrapper.querySelectorAll(".post-gallery-thumb"));
            if (allThumbs.length === 0) return;

            const first = allThumbs[0];
            const itemFull = first.getBoundingClientRect().width + GAP_PX;
            // saltamos los clones iniciales que añadimos: son 2 * ORIGINAL_COUNT clones al principio
            const clonesBefore = ORIGINAL_COUNT * 2;
            thumbsWrapper.scrollLeft = itemFull * clonesBefore;
        }
        initialThumbScrollPosition();

        // Centro del contenedor (usado por centrar)
        function containerCenterX() {
            return thumbsWrapper.getBoundingClientRect().left + thumbsWrapper.clientWidth / 2;
        }

        // Centro la miniatura "mejor candidata" entre las que tienen data-index == realIndex
        function centerActiveThumb() {
            const activeDataIndex = getRealActiveIndex();
            if (activeDataIndex == null) return;

            // Encuentra todas las miniaturas en el wrapper con ese data-index
            const candidates = Array.from(thumbsWrapper.querySelectorAll(`.post-gallery-thumb[data-index="${activeDataIndex}"]`));
            if (candidates.length === 0) return;

            // Elige la candidata más cercana al centro visual (por si hay varias copias)
            const containerCenter = containerCenterX();
            let best = candidates[0];
            let bestDist = Infinity;
            candidates.forEach(c => {
                const rect = c.getBoundingClientRect();
                const cx = rect.left + rect.width / 2;
                const dist = Math.abs(cx - containerCenter);
                if (dist < bestDist) {
                    bestDist = dist;
                    best = c;
                }
            });

            // Calcula scrollLeft deseado para que "best" quede centrada
            const bestRect = best.getBoundingClientRect();
            const wrapperRect = thumbsWrapper.getBoundingClientRect();
            const currentScroll = thumbsWrapper.scrollLeft;
            const bestCenterRelative = bestRect.left - wrapperRect.left + bestRect.width / 2;
            const desiredScroll = currentScroll + (bestCenterRelative - wrapperRect.width / 2);

            thumbsWrapper.scrollTo({
                left: desiredScroll,
                behavior: "smooth"
            });

            // Actualizamos clases active en todos los thumbs (para todas las copias)
            allThumbs.forEach(t => {
                t.classList.toggle("active", t.dataset.index === String(activeDataIndex));
            });
        }

        // Obtiene el índice real correlacionado con currentSlide (el índice del original)
        function getRealActiveIndex(indexParam) {
            // Si se pasa un índice de slide (en la galería), calculamos el real
            const idx = typeof indexParam === "number" ? indexParam : currentSlide;
            // tus slides usan currentSlide con offset por clones. Real index:
            const realIndex = ((idx - 1) % visibleSlides + visibleSlides) % visibleSlides;
            return realIndex;
        }

        // Escuchar scroll para "rebasing" infinito
        thumbsWrapper.addEventListener("scroll", () => {
            // Evitamos recálculos intensos: ejecutamos dentro de rAF
            if (thumbsWrapper._scrollRaf) return;
            thumbsWrapper._scrollRaf = requestAnimationFrame(() => {
                thumbsWrapper._scrollRaf = null;
                const maxScroll = thumbsWrapper.scrollWidth - thumbsWrapper.clientWidth;
                const current = thumbsWrapper.scrollLeft;

                const firstThumb = allThumbs[0];
                if (!firstThumb) return;
                const itemFull = firstThumb.getBoundingClientRect().width + GAP_PX;
                const clonesBlock = ORIGINAL_COUNT * 2 * itemFull; // lo que desplazamos al construir clones

                // Si estamos muy al inicio, saltamos hacia el centro equivalente
                if (current <= itemFull) {
                    thumbsWrapper.scrollLeft = current + clonesBlock;
                }
                // Si estamos muy al final, saltamos hacia el centro equivalente
                else if (current >= maxScroll - itemFull) {
                    thumbsWrapper.scrollLeft = current - clonesBlock;
                }
            });
        }, { passive: true });

        // --- Drag (mouse) + touch para thumbs ---
        (function enableThumbDrag() {
            let isDown = false;
            let startX = 0;
            let scrollStart = 0;
            let isDragging = false; // para prevenir clicks no deseados

            // Mouse
            thumbsWrapper.addEventListener("mousedown", (e) => {
                isDown = true;
                startX = e.pageX - thumbsWrapper.getBoundingClientRect().left;
                scrollStart = thumbsWrapper.scrollLeft;
                thumbsWrapper.classList.add("is-dragging");
            });
            document.addEventListener("mousemove", (e) => {
                if (!isDown) return;
                const x = e.pageX - thumbsWrapper.getBoundingClientRect().left;
                const walk = (startX - x);
                if (Math.abs(walk) > 3) isDragging = true;
                thumbsWrapper.scrollLeft = scrollStart + walk;
            });
            document.addEventListener("mouseup", () => {
                if (!isDown) return;
                isDown = false;
                thumbsWrapper.classList.remove("is-dragging");
                // pequeño timeout para reactivar clicks nativos
                setTimeout(() => isDragging = false, 50);
            });

            // Touch
            let tStartX = 0;
            let tScrollStart = 0;
            thumbsWrapper.addEventListener("touchstart", (e) => {
                if (e.touches.length !== 1) return;
                tStartX = e.touches[0].pageX - thumbsWrapper.getBoundingClientRect().left;
                tScrollStart = thumbsWrapper.scrollLeft;
            }, { passive: true });
            thumbsWrapper.addEventListener("touchmove", (e) => {
                if (e.touches.length !== 1) return;
                const x = e.touches[0].pageX - thumbsWrapper.getBoundingClientRect().left;
                const walk = (tStartX - x);
                thumbsWrapper.scrollLeft = tScrollStart + walk;
            }, { passive: true });

            // Prevenir que clicks se disparen tras drag (en versiones donde drag fue activo)
            thumbsWrapper.addEventListener("click", (e) => {
                if (isDragging) {
                    e.stopPropagation();
                    e.preventDefault();
                }
            }, true);
        })();

        // --- Integración con tu lógica de slides: updateActiveClasses (adaptado) ---
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

            const realIndex = getRealActiveIndex(index);
            // marcamos active en TODOS los thumbs que compartan data-index = realIndex
            allThumbs.forEach((thumb) => {
                thumb.classList.toggle("active", thumb.dataset.index === String(realIndex));
            });

            // centrado suave
            centerActiveThumb();
        }

        // Manejar salto instantáneo en bucle infinito de la galería grande (tu código, sin cambios lógicos)
        function handleInfiniteLoop() {
            if (currentSlide === 0) {
                currentSlide = visibleSlides;
            } else if (currentSlide === totalSlides - 1) {
                currentSlide = 1;
            } else {
                return false;
            }

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

        // Función principal de navegación (tu código, intacto)
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

        // Event listeners para thumbnails (click)
        thumbsWrapper.addEventListener("click", e => {
            const t = e.target.closest(".post-gallery-thumb");
            if (!t) return;
            // Si fue drag, evitamos navegacion (el listener de click previene esto con isDragging)
            const index = parseInt(t.dataset.index, 10);
            // Convertimos index de thumbnails (0..N-1) a slide index en tu galería (se usa offset +1)
            goToSlide(index + 1);
            resetAutoSlide();
        });

        // Swipe gestures para la galería principal (tu implementación original)
        let startX = 0;
        let endX = 0;
        const threshold = 50;

        gallery.addEventListener("touchstart", e => startX = e.touches[0].clientX, { passive: true });
        gallery.addEventListener("touchmove", e => endX = e.touches[0].clientX, { passive: true });
        gallery.addEventListener("touchend", () => {
            const deltaX = endX - startX;
            if (Math.abs(deltaX) > threshold) {
                goToSlide(deltaX < 0 ? currentSlide + 1 : currentSlide - 1);
                resetAutoSlide();
            }
            startX = 0;
            endX = 0;
        });

        // Botones prev/next (tu código original)
        prevBtn.addEventListener("click", () => {
            goToSlide(currentSlide - 1);
            resetAutoSlide();
        });

        nextBtn.addEventListener("click", () => {
            goToSlide(currentSlide + 1);
            resetAutoSlide();
        });

        // Auto-slide (igual a tu original)
        let autoSlide = setInterval(() => goToSlide(currentSlide + 1), 10000);

        function resetAutoSlide() {
            clearInterval(autoSlide);
            autoSlide = setInterval(() => goToSlide(currentSlide + 1), 10000);
        }

        wrapper.addEventListener("mouseenter", () => clearInterval(autoSlide));
        wrapper.addEventListener("mouseleave", resetAutoSlide);

        // Inicializar clases y centrado al inicio
        updateActiveClasses();
        // Forzamos un pequeño delay para asegurar que tamaños fueron aplicados
        setTimeout(() => {
            initialThumbScrollPosition();
            centerActiveThumb();
        }, 60);

        // --- LIGHTBOX (A: solo imágenes grandes) --------------------------
        (function setupLightbox() {
            // Crear estructura del lightbox
            const lightbox = document.createElement("div");
            lightbox.className = "pg-lightbox";
            lightbox.style.cssText = `
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.85);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 999999;
                cursor: zoom-out;
            `;

            const img = document.createElement("img");
            img.className = "pg-lightbox-img";
            img.style.cssText = `
                max-width: 95%;
                max-height: 95%;
                object-fit: contain;
                cursor: default;
            `;

            const closeBtn = document.createElement("button");
            closeBtn.innerHTML = "✕";
            closeBtn.setAttribute("aria-label", "Cerrar");
            closeBtn.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                font-size: 28px;
                padding: 6px 10px;
                cursor: pointer;
                background: transparent;
                color: white;
                border: none;
            `;

            // Navegación (flechas internas)
            const prevL = document.createElement("div");
            prevL.textContent = "‹";
            prevL.style.cssText = `
                position: fixed;
                left: 30px;
                font-size: 60px;
                color: white;
                cursor: pointer;
                user-select: none;
    `;

            const nextL = document.createElement("div");
            nextL.textContent = "›";
            nextL.style.cssText = `
                position: fixed;
                right: 30px;
                font-size: 60px;
                color: white;
                cursor: pointer;
                user-select: none;
            `;

            lightbox.appendChild(img);
            lightbox.appendChild(closeBtn);
            lightbox.appendChild(prevL);
            lightbox.appendChild(nextL);
            document.body.appendChild(lightbox);

            let lbIndex = 0; // índice del slide en la galería

            function openLightbox(index) {
                lbIndex = index;
                img.src = slides[index].querySelector("img").src;
                lightbox.style.display = "flex";
            }

            function closeLightbox() {
                lightbox.style.display = "none";
            }

            function lbGo(delta) {
                lbIndex += delta;
                if (lbIndex <= 0) lbIndex = visibleSlides;
                else if (lbIndex >= totalSlides - 1) lbIndex = 1;

                goToSlide(lbIndex);
                img.src = slides[lbIndex].querySelector("img").src;
            }

            // Click en las imágenes grandes → lightbox
            slides.forEach((slide, i) => {
                slide.querySelector("img").addEventListener("click", (e) => {
                    e.stopPropagation();
                    openLightbox(i);
                });
            });

            // Eventos de lightbox
            closeBtn.addEventListener("click", closeLightbox);
            lightbox.addEventListener("click", (e) => {
                if (e.target === lightbox) closeLightbox();
            });

            prevL.addEventListener("click", (e) => {
                e.stopPropagation();
                lbGo(-1);
            });

            nextL.addEventListener("click", (e) => {
                e.stopPropagation();
                lbGo(1);
            });

            // Teclado
            document.addEventListener("keydown", (e) => {
                if (lightbox.style.display !== "flex") return;

                if (e.key === "Escape") closeLightbox();
                if (e.key === "ArrowRight") lbGo(1);
                if (e.key === "ArrowLeft") lbGo(-1);
            });

            // Swipe táctil en lightbox
            let lx = 0;
            lightbox.addEventListener("touchstart", (e) => lx = e.touches[0].clientX, { passive: true });
            lightbox.addEventListener("touchend", (e) => {
                const dx = e.changedTouches[0].clientX - lx;
                if (Math.abs(dx) > 50) {
                    lbGo(dx < 0 ? 1 : -1);
                }
            });
        })();
    }

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
})();
