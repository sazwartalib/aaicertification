/**
 * Progressive enhancement for the marketing site.
 * Everything here is optional polish — the site works fully without JS,
 * with one exception: [data-qr] elements are rendered here (see initQrCodes).
 */

import QRCode from 'qrcode';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---- Reveal elements as they scroll into view -------------------------- */
function initReveal() {
    const targets = document.querySelectorAll('[data-reveal]');

    if (!targets.length) {
        return;
    }

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.1 }
    );

    targets.forEach((el) => observer.observe(el));
}

/* ---- Count-up numbers (hero stats) ----------------------------------- */
function initCounters() {
    const counters = document.querySelectorAll('[data-count-to]');

    if (!counters.length) {
        return;
    }

    const run = (el) => {
        const target = parseFloat(el.dataset.countTo);
        const suffix = el.dataset.countSuffix || '';
        const duration = 1400;

        if (prefersReducedMotion) {
            el.textContent = target.toLocaleString() + suffix;
            return;
        }

        const start = performance.now();
        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased).toLocaleString() + suffix;

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        };
        requestAnimationFrame(tick);
    };

    if (!('IntersectionObserver' in window)) {
        counters.forEach(run);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    run(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 }
    );

    counters.forEach((el) => observer.observe(el));
}

/* ---- Sticky nav: shadow on scroll + close mobile menu ---------------- */
function initNav() {
    const header = document.querySelector('[data-site-nav]');
    const toggle = document.getElementById('nav-toggle');

    if (header) {
        const onScroll = () => {
            header.classList.toggle('shadow-lg', window.scrollY > 8);
            header.classList.toggle('shadow-navy-950/20', window.scrollY > 8);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    if (toggle) {
        header?.querySelectorAll('[data-nav-link]').forEach((link) => {
            link.addEventListener('click', () => {
                toggle.checked = false;
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                toggle.checked = false;
            }
        });
    }
}

/* ---- Forms: disable + spinner on submit ----------------------------- */
function initFormSubmit() {
    document.querySelectorAll('form[data-loading-submit]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (!button || button.dataset.submitting) {
                return;
            }

            button.dataset.submitting = 'true';
            button.disabled = true;
            button.classList.add('cursor-wait', 'opacity-80');

            const label = button.dataset.loadingLabel || 'Please wait…';
            button.innerHTML =
                '<svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">' +
                '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.4 0 0 5.4 0 12h4z"></path>' +
                '</svg><span>' +
                label +
                '</span>';
        });
    });
}

/* ---- Copy-to-clipboard buttons ------------------------------------- */
function initCopy() {
    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(button.dataset.copy);
                const original = button.querySelector('[data-copy-label]');

                if (original) {
                    const previous = original.textContent;
                    original.textContent = 'Copied!';
                    setTimeout(() => {
                        original.textContent = previous;
                    }, 1600);
                }
            } catch (error) {
                /* Clipboard unavailable — silently ignore. */
            }
        });
    });
}

/* ---- "Try an example" chips fill a target input ------------------- */
function initFillInput() {
    document.querySelectorAll('[data-fill-target]').forEach((chip) => {
        chip.addEventListener('click', () => {
            const input = document.querySelector(chip.dataset.fillTarget);

            if (input) {
                input.value = chip.dataset.fillValue || chip.textContent.trim();
                input.focus();
            }
        });
    });
}

/* ---- Dismissible alerts ------------------------------------------- */
function initDismissible() {
    document.querySelectorAll('[data-dismiss]').forEach((button) => {
        button.addEventListener('click', () => {
            const box = button.closest('[data-dismissible]');

            if (!box) {
                return;
            }

            box.style.opacity = '0';
            box.style.transform = 'translateY(-6px)';
            setTimeout(() => box.remove(), 300);
        });
    });
}

/* ---- Back-to-top button ------------------------------------------- */
function initBackToTop() {
    const button = document.querySelector('[data-back-to-top]');

    if (!button) {
        return;
    }

    const onScroll = () => {
        button.classList.toggle('pointer-events-none', window.scrollY < 400);
        button.classList.toggle('opacity-0', window.scrollY < 400);
        button.classList.toggle('translate-y-2', window.scrollY < 400);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    button.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
    });
}

/* ---- QR codes -------------------------------------------------------- */
/**
 * Render every [data-qr] element as an inline SVG pointing at its data-qr URL.
 *
 * SVG keeps the code crisp at any print size. Rendering is awaited before the
 * print dialog opens so a certificate printed straight after load still has it.
 */
const qrReady = [];

function initQrCodes() {
    document.querySelectorAll('[data-qr]').forEach((element) => {
        const value = element.dataset.qr;

        if (!value) {
            return;
        }

        const task = QRCode.toString(value, {
            type: 'svg',
            errorCorrectionLevel: 'M',
            margin: 0,
            color: {
                dark: element.dataset.qrColor || '#142244',
                light: '#0000',
            },
        })
            .then((svg) => {
                element.innerHTML = svg;
                element.querySelector('svg')?.setAttribute('class', 'h-full w-full');
                element.removeAttribute('data-qr-pending');
            })
            .catch(() => {
                element.setAttribute('data-qr-failed', 'true');
            });

        qrReady.push(task);
    });
}

/** Resolves once every QR code on the page has been drawn. */
window.qrCodesReady = () => Promise.all(qrReady);

document.addEventListener('DOMContentLoaded', () => {
    initQrCodes();
    initReveal();
    initCounters();
    initNav();
    initFormSubmit();
    initCopy();
    initFillInput();
    initDismissible();
    initBackToTop();
});
