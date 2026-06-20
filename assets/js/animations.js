// Initialisation de Lenis pour le smooth scrolling
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // easeOutExpo
    direction: 'vertical',
    gestureDirection: 'vertical',
    smooth: true,
    mouseMultiplier: 1,
    smoothTouch: false,
    touchMultiplier: 2,
    infinite: false,
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

// Initialisation de AOS pour les animations au scroll
AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true, // L'animation ne se joue qu'une fois
    offset: 50, // Déclenchement 50px avant l'élément
});

// Exposer lenis globalement pour pouvoir l'utiliser ailleurs (ex: scroll jusqu'à une ancre)
window.lenis = lenis;
