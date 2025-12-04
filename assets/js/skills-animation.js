document.addEventListener('DOMContentLoaded', function () {
  const target = document.querySelector('#skills');
  if (!target) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        target.classList.add('rotate');
      } else {
        target.classList.remove('rotate');
      }
    });
  }, {
    threshold: 1
  });

  observer.observe(target);
});