function animateIn(selectors, animationClasses = ['animate-in'], options = {}) {
  const {
    threshold = 0.1,
    stagger = 300 // retraso por elemento
  } = options;

  const targets = document.querySelectorAll(selectors);
  if (!targets.length) return;

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting && entry.intersectionRatio >= threshold) {
        setTimeout(() => {
          animationClasses.forEach(cls => entry.target.classList.add(cls));
          observer.unobserve(entry.target);
        }, index * stagger);
      }
    });
  }, { threshold });

  targets.forEach(target => observer.observe(target));
}

document.addEventListener('DOMContentLoaded', () => {
  // Check if we're on the properties page (has .properties--list element)
  const isPropertiesPage = !!document.querySelector('.properties--list');
  
  // Add delay only on properties page to allow ajax-properties.js to set up
  // This prevents race condition between animate-in observers and AJAX loading
  if (isPropertiesPage) {
    setTimeout(() => animateIn('.post'), 700);
  } else {
    animateIn('.post');
  }
});