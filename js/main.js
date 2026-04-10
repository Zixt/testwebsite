/* ============================================================
   Matrix Technical Services — main.js
   ============================================================ */

(function () {
    'use strict';

    /* ---- Mobile nav toggle --------------------------------- */
    const toggle = document.querySelector('.navbar__toggle');
    const nav    = document.querySelector('.navbar__nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            const isOpen = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen);

            // Animate hamburger → X
            const spans = toggle.querySelectorAll('span');
            if (isOpen) {
                spans[0].style.transform = 'translateY(7px) rotate(45deg)';
                spans[1].style.opacity   = '0';
                spans[2].style.transform = 'translateY(-7px) rotate(-45deg)';
            } else {
                spans[0].style.transform = '';
                spans[1].style.opacity   = '';
                spans[2].style.transform = '';
                // Close any open dropdown
                document.querySelectorAll('.navbar__dropdown.open')
                    .forEach(function (d) { d.classList.remove('open'); });
            }
        });

        // Close nav when clicking outside
        document.addEventListener('click', function (e) {
            if (!toggle.contains(e.target) && !nav.contains(e.target)) {
                nav.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
                const spans = toggle.querySelectorAll('span');
                spans[0].style.transform = '';
                spans[1].style.opacity   = '';
                spans[2].style.transform = '';
            }
        });
    }

    /* ---- Mobile dropdown toggle ---------------------------- */
    const dropdownToggles = document.querySelectorAll('.navbar__dropdown-toggle');

    dropdownToggles.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            // On mobile the CSS :hover doesn't apply, so we toggle .open
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const dropdown = btn.closest('.navbar__dropdown');
                const isOpen   = dropdown.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen);
            }
        });
    });

    /* ---- Services sub-nav scroll spy ----------------------- */
    const servicesSections = document.querySelectorAll('.service-section[id]');
    const servicesNavItems = document.querySelectorAll('.services-nav__item[href]');

    if (servicesSections.length && servicesNavItems.length) {
        const OFFSET = 140; // navbar + services-nav height

        function updateActiveNavItem() {
            let currentId = '';
            servicesSections.forEach(function (section) {
                const top = section.getBoundingClientRect().top;
                if (top <= OFFSET) {
                    currentId = section.id;
                }
            });

            servicesNavItems.forEach(function (item) {
                const href = item.getAttribute('href');
                if (href && href.endsWith('#' + currentId)) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }

        window.addEventListener('scroll', updateActiveNavItem, { passive: true });
        updateActiveNavItem();
    }

    /* ---- Smooth scroll for anchor links ------------------- */
    document.querySelectorAll('a[href*="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const href = link.getAttribute('href');
            // Only handle same-page anchors
            const url = new URL(href, window.location.href);
            if (url.pathname === window.location.pathname && url.hash) {
                const target = document.querySelector(url.hash);
                if (target) {
                    e.preventDefault();
                    const offset = 140; // sticky navbars
                    const top = target.getBoundingClientRect().top + window.scrollY - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });

                    // Close mobile nav if open
                    if (nav) {
                        nav.classList.remove('open');
                        if (toggle) toggle.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        });
    });

    /* ---- Fade-in on scroll --------------------------------- */
    const fadeEls = document.querySelectorAll(
        '.feature-card, .service-card, .service-section__inner, .contact-info-item'
    );

    if ('IntersectionObserver' in window && fadeEls.length) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity    = '1';
                    entry.target.style.transform  = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        fadeEls.forEach(function (el) {
            el.style.opacity   = '0';
            el.style.transform = 'translateY(24px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });
    }

    /* ---- Contact form AJAX (optional enhancement) ---------- */
    // The form submits normally via PHP POST — no JS override needed.
    // This just prevents double-submit on slow connections.
    const contactForm = document.querySelector('.js-contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function () {
            const submitBtn = contactForm.querySelector('[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending…';
            }
        });
    }

})();
