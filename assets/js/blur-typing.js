document.addEventListener("DOMContentLoaded", function () {
  let firstRun = true; // ✅ bandera para saber si es la primera animación

  /**
   * Aplica el efecto de aparición con blur por palabra
   * al heading dentro del quote-item activo.
   */
  function animateQuote(quoteItem) {
    const titleEl = quoteItem.querySelector(".quote-content .wp-block-heading");
    if (!titleEl) return;

    const text = titleEl.textContent.trim();
    if (!text) return;

    // Limpiar contenido previo si ya se animó antes
    titleEl.textContent = "";
    titleEl.setAttribute("aria-label", text);

    const words = text.split(/\s+/);

    words.forEach((word, index) => {
      const wordSpan = document.createElement("span");
      wordSpan.classList.add("word");
      wordSpan.textContent = word;
      wordSpan.setAttribute("aria-hidden", "false");

      wordSpan.style.display = "inline-block";
      wordSpan.style.whiteSpace = "nowrap";
      wordSpan.style.opacity = "0";
      wordSpan.style.filter = "blur(20px)";
      wordSpan.style.transition = "filter 0.6s ease-out, opacity 0.6s ease-out";

      // ⏱️ Añadimos retardo adicional solo en la primera ejecución
      const baseDelay = index * 0.25;
      const extraDelay = firstRun ? 2.5 : 0;
      wordSpan.style.transitionDelay = `${baseDelay + extraDelay}s`;

      titleEl.appendChild(wordSpan);

      if (index < words.length - 1) {
        const spaceSpan = document.createElement("span");
        spaceSpan.setAttribute("aria-hidden", "true");
        spaceSpan.textContent = "\u00A0";
        spaceSpan.style.display = "inline-block";
        spaceSpan.style.opacity = "0";
        spaceSpan.style.filter = "blur(20px)";
        spaceSpan.style.transition = "filter 0.6s ease-out, opacity 0.6s ease-out";
        spaceSpan.style.transitionDelay = `${baseDelay + extraDelay}s`;
        titleEl.appendChild(spaceSpan);
      }
    });

    // Activar animación
    requestAnimationFrame(() => {
      const spans = titleEl.querySelectorAll(".word, span[aria-hidden='true']");
      spans.forEach((span) => {
        span.style.opacity = "1";
        span.style.filter = "blur(0)";
      });
    });

    // Después de la primera ejecución, desactivar el retardo adicional
    if (firstRun) firstRun = false;
  }

  /**
   * Observa los cambios de clase en los quote-item
   */
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (
        mutation.type === "attributes" &&
        mutation.attributeName === "class" &&
        mutation.target.classList.contains("quote-item")
      ) {
        const el = mutation.target;

        if (el.classList.contains("active")) {
          // ligera pausa para evitar que se corte al cambiar rápido
          setTimeout(() => animateQuote(el), 100);
        }
      }
    });
  });

  // Observar todos los quote-item
  document.querySelectorAll(".quote-item").forEach((item) => {
    observer.observe(item, { attributes: true });
  });

  // Animar el que esté activo al cargar la página
  const activeOnLoad = document.querySelector(".quote-item.active");
  if (activeOnLoad) animateQuote(activeOnLoad);
});