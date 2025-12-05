function initHeroParallax() {
    const parallaxImages = document.querySelectorAll(".background-hero") 
    if (!parallaxImages.length) return 

    window.addEventListener("scroll", () => {
        const scrollY = window.scrollY 

        parallaxImages.forEach(img => {
            const speed = parseFloat(img.dataset.speed) || 0.25 
            img.style.transform = `translateY(${scrollY * speed}px)` 
        }) 
    }) 
}
document.addEventListener("DOMContentLoaded", initHeroParallax) 