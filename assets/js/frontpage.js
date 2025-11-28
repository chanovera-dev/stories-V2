document.addEventListener("DOMContentLoaded", function () {
    const titleEl = document.querySelector("h1.hero-title")
    const hero = document.querySelector('#hero')
    const isWCUSection = document.querySelector('#why-choose-us')

    if (titleEl) {
        blurTypingEffect(titleEl)
    }

    if (hero) {
      setTimeout(() => animateIn('.property-filter-form, .cta'), 500)
    }

    if (isWCUSection) {
      setTimeout(() => animateIn('.content .image, .title-section, .subtitle-section, .paragraph, ul li, .cta'), 500)
    } else {
      animateIn('.content .image');
    }
});