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

    /* ---- Contact form — prevent double-submit --------------- */
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

    /* ---- Multi-step signup form ----------------------------- */
    (function () {
        const form = document.querySelector('.js-signup-form');
        if (!form) return;

        const steps      = Array.from(form.querySelectorAll('.signup-step'));
        const indicators = Array.from(document.querySelectorAll('.step-indicator__step'));
        let current = 0;

        function scrollToForm() {
            const card = form.closest('.signup-card');
            if (card) {
                const top = card.getBoundingClientRect().top + window.scrollY - 100;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        }

        function showStep(index) {
            steps.forEach(function (s, i) { s.classList.toggle('active', i === index); });
            indicators.forEach(function (ind, i) {
                ind.classList.remove('active', 'completed');
                if (i < index) ind.classList.add('completed');
                if (i === index) ind.classList.add('active');
            });
            current = index;
            scrollToForm();
        }

        function clearErrors(step) {
            step.querySelectorAll('.field-error').forEach(function (e) { e.remove(); });
            step.querySelectorAll('.field-invalid').forEach(function (e) { e.classList.remove('field-invalid'); });
            var ge = step.querySelector('.services-checkboxes .field-error');
            if (ge) ge.remove();
        }

        function addError(field, msg) {
            field.classList.add('field-invalid');
            var err = document.createElement('p');
            err.className = 'field-error';
            err.textContent = msg;
            field.parentNode.appendChild(err);
        }

        function validateStep(index) {
            var step = steps[index];
            clearErrors(step);
            var valid = true;

            step.querySelectorAll('input[required], select[required], textarea[required]').forEach(function (field) {
                var ok;
                if (field.type === 'checkbox') {
                    ok = field.checked;
                } else if (field.type === 'email') {
                    ok = field.value.trim() !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value.trim());
                } else {
                    ok = field.value.trim() !== '';
                }
                if (!ok) {
                    valid = false;
                    addError(field, field.dataset.error || 'This field is required.');
                }
            });

            // Services checkboxes — at least one required
            var checkboxGroup = step.querySelector('.services-checkboxes');
            if (checkboxGroup) {
                var checked = checkboxGroup.querySelectorAll('input[type="checkbox"]:checked');
                if (checked.length === 0) {
                    valid = false;
                    var err = document.createElement('p');
                    err.className = 'field-error';
                    err.textContent = 'Please select at least one service.';
                    checkboxGroup.appendChild(err);
                }
            }

            return valid;
        }

        function val(name) {
            var el = form.querySelector('[name="' + name + '"]');
            return el ? el.value.trim() : '';
        }

        function populateSummary() {
            var summary = document.getElementById('signup-summary');
            if (!summary) return;

            var services = Array.from(form.querySelectorAll('[name="services[]"]:checked'))
                .map(function (cb) { return cb.value; }).join(', ') || '—';

            var sections = [
                { heading: 'Contact Details', rows: [
                    ['Name', (val('first_name') + ' ' + val('last_name')).trim()],
                    ['Job Title', val('job_title') || '—'],
                    ['Company', val('company')],
                    ['Email', val('email')],
                    ['Phone', val('phone')]
                ]},
                { heading: 'Business Address', rows: [
                    ['Address', [val('address1'), val('address2')].filter(Boolean).join(', ')],
                    ['City', val('city')],
                    ['County', val('county') || '—'],
                    ['Postcode', val('postcode')],
                    ['Country', val('country')]
                ]},
                { heading: 'Requirements', rows: [
                    ['Services', services],
                    ['No. of Sites', val('num_sites')],
                    ['Current Provider', val('current_prov') || '—'],
                    ['Timeline', val('timeline') || '—'],
                    ['Notes', val('notes') || '—']
                ]}
            ];

            summary.innerHTML = sections.map(function (section) {
                var rows = section.rows.map(function (row) {
                    return '<tr><td class="summary-label">' + row[0] + '</td><td>' + row[1] + '</td></tr>';
                }).join('');
                return '<div class="summary-section"><h4 class="summary-heading">' + section.heading + '</h4>'
                     + '<table class="summary-table">' + rows + '</table></div>';
            }).join('');
        }

        // Next buttons
        form.querySelectorAll('.signup-next').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!validateStep(current)) return;
                if (current === steps.length - 2) populateSummary();
                showStep(current + 1);
            });
        });

        // Back buttons
        form.querySelectorAll('.signup-back').forEach(function (btn) {
            btn.addEventListener('click', function () { showStep(current - 1); });
        });

        // Prevent double-submit
        form.addEventListener('submit', function () {
            var btn = form.querySelector('[type="submit"]');
            if (btn) { btn.disabled = true; btn.textContent = 'Submitting…'; }
        });
    }());

})();
