document.addEventListener("DOMContentLoaded", function () {
    const titleEl = document.querySelector("h1.hero-title");
    if (titleEl) {
        blurTypingEffect(titleEl);
    }
});

document.addEventListener('DOMContentLoaded', () => {
  const isWCUSection = !!document.querySelector('#why-choose-us');
  if (isWCUSection) {
    setTimeout(() => animateIn('.content .image, .title-section, .subtitle-section, .paragraph, ul li, .cta'), 500);
  } else {
    animateIn('.content .image');
  }
});