document.addEventListener('DOMContentLoaded', () => {
  const elements = document.querySelectorAll('.background-light');

  if (!elements.length) return;

  elements.forEach(el => {
    el.addEventListener('mousemove', (e) => {
      const rect = el.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      el.style.setProperty('--mouse-x', `${x}px`);
      el.style.setProperty('--mouse-y', `${y}px`);
      el.classList.add('active');
    });

    el.addEventListener('mouseleave', () => {
      el.classList.remove('active');
    });
  });
});