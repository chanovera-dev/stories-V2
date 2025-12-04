document.addEventListener('DOMContentLoaded', () => {
    const titleEl = document.querySelector("h1.page-title")

    if (titleEl) {
        blurTypingEffect(titleEl)
    }
  
    animateIn('#about .content .title-section, #about .content .about__content p, #works .content .title-section, #works .content .card-timeline .card-item, #contact');
});