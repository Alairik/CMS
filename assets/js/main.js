/**
 * CMS Showcase — Frontend JavaScript
 * Mobile navigation, sticky header, scroll animations
 */

document.addEventListener('DOMContentLoaded', () => {
    // ---- Mobile Navigation Toggle ----
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.site-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            toggle.classList.toggle('active');
            nav.classList.toggle('active');
            document.body.classList.toggle('nav-open');
        });

        // Close nav when clicking a link
        nav.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                toggle.classList.remove('active');
                nav.classList.remove('active');
                document.body.classList.remove('nav-open');
            });
        });
    }

    // ---- Sticky Header Shadow on Scroll ----
    const header = document.querySelector('.site-header');
    if (header) {
        const onScroll = () => {
            header.classList.toggle('scrolled', window.scrollY > 50);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // initial check
    }

    // ---- Scroll Reveal Animations ----
    const revealElements = document.querySelectorAll('.fade-in-up');
    if (revealElements.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -40px 0px'
        });

        revealElements.forEach(el => observer.observe(el));
    } else {
        // Fallback: show all elements immediately
        revealElements.forEach(el => el.classList.add('visible'));
    }
});
