// Detect Chromium-based browsers
const ua = navigator.userAgent.toLowerCase();
const isChromium =
  !!window.chrome && /chrome|crios|crmo|edg|brave|opera|opr|vivaldi/i.test(ua);

if (isChromium) {
  document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('is-chromium');
  });
}

const body = document.body;

function scrollActions() {
    let last = 0, ticking = false;

    function onScroll() {
        const y = window.scrollY;
        if (y <= 0) {
            body.classList.remove('scroll-up', 'scroll-down');
        } else if (y > last) {
            body.classList.add('scroll-down');
            body.classList.remove('scroll-up');
        } else {
            body.classList.add('scroll-up');
            body.classList.remove('scroll-down');
        }
        last = y;
        ticking = false;
    }

    function handleScroll() {
        if (!ticking) {
            requestAnimationFrame(onScroll);
            ticking = true;
        }
    }

    window.addEventListener('scroll', handleScroll, { passive: true });

    return () => window.removeEventListener('scroll', handleScroll);
}
scrollActions();