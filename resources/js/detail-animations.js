/**
 * detail-animations.js
 * GSAP + ScrollTrigger powered animations for the Detail Project Page.
 * Loaded only on the detail page via @push('scripts').
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {

    // Guard: only run on detail page
    if (!document.querySelector('.dp-overview')) return;

    // ═══════════════════════════════════
    // 1. HEADER ENTRANCE — Cinematic Reveal
    // ═══════════════════════════════════
    const headerTl = gsap.timeline({ defaults: { ease: 'power4.out' } });

    // Back link
    headerTl.fromTo('.dp-back',
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.7 },
        0.15
    );

    // Meta badges
    headerTl.fromTo('.dp-header__meta',
        { opacity: 0, y: 16 },
        { opacity: 1, y: 0, duration: 0.6 },
        0.3
    );

    // Title — clip reveal
    headerTl.fromTo('.dp-header__title',
        { opacity: 0, y: 40, clipPath: 'inset(0 0 100% 0)' },
        { opacity: 1, y: 0, clipPath: 'inset(0 0 0% 0)', duration: 1, ease: 'power3.out' },
        0.4
    );

    // ═══════════════════════════════════
    // 3. OVERVIEW — Scroll Reveals
    // ═══════════════════════════════════

    // Image slide-in from left
    const imageCol = document.querySelector('.dp-overview__image-col');
    if (imageCol) {
        gsap.fromTo(imageCol,
            { opacity: 0, x: -50 },
            {
                opacity: 1, x: 0, duration: 0.9, ease: 'power3.out',
                scrollTrigger: {
                    trigger: imageCol,
                    start: 'top 82%',
                    toggleActions: 'play none none none',
                }
            }
        );
    }

    // Content sections stagger
    const overviewItems = document.querySelectorAll('.dp-overview__content-col [data-dp-anim="fade-up"]');
    overviewItems.forEach((item, i) => {
        gsap.fromTo(item,
            { opacity: 0, y: 30 },
            {
                opacity: 1, y: 0, duration: 0.7, ease: 'power2.out',
                delay: i * 0.12,
                scrollTrigger: {
                    trigger: item,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                }
            }
        );
    });

    // Tech badges — sequential pop
    const techPills = document.querySelectorAll('.dp-tech-pill');
    if (techPills.length) {
        gsap.fromTo(techPills,
            { opacity: 0, scale: 0.85, y: 10 },
            {
                opacity: 1, scale: 1, y: 0,
                duration: 0.45,
                ease: 'back.out(1.4)',
                stagger: 0.07,
                scrollTrigger: {
                    trigger: techPills[0].closest('.dp-overview__tech'),
                    start: 'top 82%',
                    toggleActions: 'play none none none',
                }
            }
        );
    }

    // ═══════════════════════════════════
    // 4. NARRATIVE — Timeline Animation
    // ═══════════════════════════════════
    const narrativeTrack = document.querySelector('.dp-narrative__timeline-track');
    const narrativeDot = document.querySelector('.dp-narrative__timeline-dot');

    if (narrativeTrack) {
        // Timeline line draws on scroll (scrub)
        gsap.to(narrativeTrack, {
            scaleY: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: '.dp-narrative__body',
                start: 'top 75%',
                end: 'bottom 50%',
                scrub: 1.2,
            }
        });

        // Dot appears
        gsap.to(narrativeDot, {
            opacity: 1,
            duration: 0.4,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.dp-narrative__body',
                start: 'top 75%',
                toggleActions: 'play none none none',
            }
        });
    }

    // Narrative header
    const narrativeHeader = document.querySelector('.dp-narrative__header');
    if (narrativeHeader) {
        gsap.fromTo(narrativeHeader,
            { opacity: 0, y: 24 },
            {
                opacity: 1, y: 0, duration: 0.7, ease: 'power2.out',
                scrollTrigger: {
                    trigger: narrativeHeader,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                }
            }
        );
    }

    // Narrative text
    const narrativeText = document.querySelector('.dp-narrative__text');
    if (narrativeText) {
        gsap.fromTo(narrativeText,
            { opacity: 0, y: 20 },
            {
                opacity: 1, y: 0, duration: 0.8, ease: 'power2.out',
                scrollTrigger: {
                    trigger: narrativeText,
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                }
            }
        );
    }

    // ═══════════════════════════════════
    // 5. CTA — Slide Up Entrance
    // ═══════════════════════════════════
    const ctaButtons = document.querySelectorAll('.dp-cta__btn');
    if (ctaButtons.length) {
        gsap.fromTo(ctaButtons,
            { opacity: 0, y: 32 },
            {
                opacity: 1, y: 0,
                duration: 0.7,
                ease: 'power3.out',
                stagger: 0.15,
                scrollTrigger: {
                    trigger: '.dp-cta__wrap',
                    start: 'top 88%',
                    toggleActions: 'play none none none',
                }
            }
        );
    }

    // ═══════════════════════════════════
    // 6. REFRESH ScrollTrigger on resize
    // ═══════════════════════════════════
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            ScrollTrigger.refresh();
        }, 250);
    });

});
