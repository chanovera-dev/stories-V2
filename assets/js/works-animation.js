document.addEventListener('DOMContentLoaded', function () {
  let animationsInitialized = false;

  function initAnimations() {
    if (animationsInitialized || window.innerWidth <= 1024) return;
    animationsInitialized = true;

    const items = document.querySelectorAll('.card-timeline .card-item');

    items.forEach(item => {
      const figure = item.querySelector('figure');

      // Observar cambios en atributos (como class)
      const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
          if (mutation.attributeName === 'class') {
            // Reiniciar la animación del figure
            figure.style.animation = 'none';
            void figure.offsetHeight; // Forzar reflow
            if (item.classList.contains('animate')) {
              figure.style.animation = 'site-card-bounce 1s ease-in-out forwards';
            } else if (item.classList.contains('animate-back')) {
              figure.style.animation = 'site-card-bounce-reverse 1s ease-in-out forwards';
            } else {
              figure.style.animation = '';
            }
          }
        });
      });

      observer.observe(item, { attributes: true });

      // Eventos para alternar clases
      item.addEventListener('mouseenter', () => {
        item.classList.remove('animate-back');
        item.classList.add('animate');
      });

      item.addEventListener('mouseleave', () => {
        item.classList.remove('animate');
        item.classList.add('animate-back');
      });
    });
  }

  // Ejecutar en carga inicial si es mayor a 1024px
  initAnimations();

  // Escuchar redimensionamiento para activar si se supera 1024px
  window.addEventListener('resize', () => {
    if (!animationsInitialized && window.innerWidth > 1024) {
      initAnimations();
    }
  });
});