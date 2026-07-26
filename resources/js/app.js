import './bootstrap';
import Lenis from 'lenis';

document.addEventListener("DOMContentLoaded", () => {

    // 0. INITIALIZE LENIS (Smooth Scroll)
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        direction: 'vertical',
        gestureDirection: 'vertical',
        smooth: true,
        mouseMultiplier: 1,
        smoothTouch: false,
        touchMultiplier: 2,
        infinite: false,
    });

    // Dynamic navbar height calculation for perfect scroll offset
    const updateNavbarHeight = () => {
        const navbar = document.querySelector('.custom-navbar');
        if (navbar) {
            const h = navbar.offsetHeight;
            document.documentElement.style.setProperty('--navbar-height', `${h}px`);
            document.documentElement.style.scrollPaddingTop = `${h}px`;
        }
    };
    updateNavbarHeight();
    setTimeout(updateNavbarHeight, 150); // Fallback for transition rendering delay
    window.addEventListener('resize', updateNavbarHeight);

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    // 0.1 TEXT REVEAL LOGIC (Split Characters - Fixed Word Breaking)
    const splitChars = (el) => {
        const walk = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null, false);
        const textNodes = [];
        let node;
        while (node = walk.nextNode()) textNodes.push(node);

        let charIndex = 0;
        textNodes.forEach(textNode => {
            const text = textNode.textContent;
            const fragment = document.createDocumentFragment();
            
            // Pecah berdasarkan kata (dan simpan spasi)
            const words = text.split(/(\s+)/);
            
            words.forEach(word => {
                if (word.trim() === '') {
                    // Jika hanya spasi, biarkan sebagai node teks biasa
                    fragment.appendChild(document.createTextNode(word));
                } else {
                    // Bungkus kata dalam span agar tidak terpotong (nowrap)
                    const wordSpan = document.createElement('span');
                    wordSpan.style.display = 'inline-block';
                    wordSpan.style.whiteSpace = 'nowrap';
                    
                    word.split('').forEach(char => {
                        const span = document.createElement('span');
                        span.textContent = char;
                        span.classList.add('char');
                        span.style.transitionDelay = `${charIndex * 35}ms`;
                        wordSpan.appendChild(span);
                        charIndex++;
                    });
                    fragment.appendChild(wordSpan);
                }
            });
            textNode.parentNode.replaceChild(fragment, textNode);
        });
    };

    // Terapkan splitChars ke elemen yang ditandai
    document.querySelectorAll('.text-reveal').forEach(el => splitChars(el));

    // 3. UNIFIED ANCHOR SCROLL (Desktop & Mobile)
    const navbarCollapse = document.getElementById('mainNavbar');
    const body = document.body;

    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const hashIndex = href.indexOf('#');
            if (hashIndex === -1) return;

            const targetId = href.substring(hashIndex);
            if (!targetId || targetId === '#') return;

            // Resolve full URL to check pathname
            const targetUrl = new URL(this.href, window.location.href);
            if (targetUrl.pathname !== window.location.pathname) {
                // Allow default navigation to redirect to the home page
                return;
            }

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();

                // Cek jika menu mobile sedang terbuka
                const isMobileMenuOpen = navbarCollapse && navbarCollapse.classList.contains('show');

                const doScroll = () => {
                    const navbar = document.querySelector('.custom-navbar');
                    const navbarH = navbar ? navbar.offsetHeight : 68;
                    
                    // Calculate EXACT absolute scroll position — no ambiguity with Lenis offset behavior
                    const elementAbsoluteTop = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const scrollTarget = elementAbsoluteTop - navbarH;

                    lenis.scrollTo(scrollTarget, {
                        duration: 1.4,
                        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t))
                    });
                };

                if (isMobileMenuOpen && navbarCollapse.contains(this)) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse) || new bootstrap.Collapse(navbarCollapse, { toggle: false });
                    if (bsCollapse) bsCollapse.hide();
                    setTimeout(doScroll, 200);
                } else {
                    doScroll();
                }

                // Update URL tanpa refresh (opsional)
                history.pushState(null, null, targetId);
            }
        });
    });


    // 1. FUNGSI JAM (Live Clock)
    const updateClock = () => {
        const clockElement = document.getElementById('live-clock');
        if (clockElement) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            clockElement.textContent = `${hours}:${minutes}`;
        }
    };

    updateClock(); // Jalankan pertama kali saat halaman load
    setInterval(updateClock, 60000); // Update setiap 60 detik


    // 2. LOGIKA ANIMASI (Intersection Observer) - CSS Transition Based
    const observerOptions = {
        threshold: 0, 
        rootMargin: "100px 0px 100px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                const el = entry.target;
                if (el.classList.contains('reveal-active')) return;

                // Cap the stagger index to avoid very long delays
                const staggerIndex = index % 8;
                el.style.transitionDelay = `${staggerIndex * 100}ms`;
                
                el.classList.add('reveal-active');

                // Menambahkan kelas animation-finished setelah transisi selesai (biasanya 800ms - 1000ms)
                // Ini penting untuk mengaktifkan efek hover di SASS
                const transitionDuration = parseFloat(getComputedStyle(el).transitionDuration) * 1000 || 800;
                const transitionDelay = parseFloat(getComputedStyle(el).transitionDelay) * 1000 || 0;
                
                setTimeout(() => {
                    el.classList.add('animation-finished');
                }, transitionDuration + transitionDelay + 50);

                observer.unobserve(el);
            }
        });
    }, observerOptions);

    // Selektor elemen yang akan dianimasikan - Fokus pada blok utama
    const animationSelectors = [
        '.reveal-ready',
        '.animate-on-scroll', 
        '.image-sweep', 
        '.profile-card-horizontal', 
        '.stack-animated',
        '.card-skill-v2',
        '.hero-location-badge',
        '.animate-text',
        '.animate-buttons',
        '.form-group',
        '.btn-send-contact',
        '.project-card',
        'section h1', 'section h2'
    ].join(', ');

    // Mulai mengamati
    document.querySelectorAll(animationSelectors).forEach(el => {
        observer.observe(el);
    });

    // 4. MOBILE MENU SCROLL LOCK (Lenis Integration)
    if (navbarCollapse) {
        navbarCollapse.addEventListener('show.bs.collapse', () => {
            body.classList.add('mobile-menu-open');
            lenis.stop(); // Kunci scroll Lenis saat menu terbuka
        });

        navbarCollapse.addEventListener('hide.bs.collapse', () => {
            body.classList.remove('mobile-menu-open');
            lenis.start(); // Aktifkan kembali scroll Lenis saat menu tertutup
        });
    }

    // 5. AUTO-SCROLL TO CONTACT ON ERROR/SUCCESS
    const hasErrors = document.querySelector('.is-invalid');
    const hasSuccess = document.querySelector('.alert-success');
    
    if (hasErrors || hasSuccess) {
        setTimeout(() => {
            const contactSection = document.getElementById('contact');
            if (contactSection) {
                lenis.scrollTo(contactSection); // Gunakan Lenis untuk konsistensi
            }
        }, 500);
    }

    // 6. THEME TOGGLE (Dark Mode) - Optimized
    const themeToggle = document.getElementById('theme-toggle');
    
    // Inject style to force transition on all elements during theme switching
    (function() {
        if (document.getElementById('theme-force-style')) return;
        const style = document.createElement('style');
        style.id = 'theme-force-style';
        style.textContent = `
            html.theme-switching,
            html.theme-switching *,
            html.theme-switching *::before,
            html.theme-switching *::after {
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease !important;
                transition-delay: 0s !important;
                animation: none !important;
                animation-delay: 0s !important;
            }
        `;
        document.head.appendChild(style);
    })();

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            // Enable transition styling
            document.documentElement.classList.add('theme-switching');
            
            // Force browser reflow to apply transition styles immediately
            document.documentElement.offsetHeight;
            
            // Toggle the theme attribute
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Disable transition styling once transition completes (300ms)
            setTimeout(() => {
                document.documentElement.classList.remove('theme-switching');
            }, 300);
        });
    }

    // 7. AUTO-EXPAND TEXTAREA (Pesan)
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        // Atur tinggi awal jika ada isi (misal saat edit/error)
        if(textarea.value) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }

        textarea.addEventListener('input', function() {
            // Reset height sementara untuk mendapatkan scrollHeight yang sebenarnya
            this.style.height = 'auto';
            // Setel tinggi sesuai dengan konten di dalamnya
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    // 8. HYBRID NO-RELOAD I18N TOGGLE ENGINE
    let currentLang = document.documentElement.getAttribute('lang') || 'id';

    function applyLanguage(lang) {
        document.querySelectorAll('[data-i18n-id]').forEach(el => {
            const attr = lang === 'en' ? 'data-i18n-en' : 'data-i18n-id';
            const val = el.getAttribute(attr);
            if (val !== null) {
                el.innerHTML = val;
            }
        });
        document.documentElement.setAttribute('lang', lang);
        
        const langToggle = document.getElementById('lang-toggle');
        const langLabel = langToggle ? langToggle.querySelector('.lang-label') : null;
        if (langLabel) {
            langLabel.textContent = lang === 'en' ? 'ID' : 'EN';
        }

        if (lenis && typeof lenis.resize === 'function') {
            lenis.resize();
        }
    }

    const langToggle = document.getElementById('lang-toggle');
    if (langToggle) {
        langToggle.addEventListener('click', () => {
            const newLang = currentLang === 'id' ? 'en' : 'id';
            applyLanguage(newLang);
            currentLang = newLang;

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfMeta ? csrfMeta.content : '';

            fetch(`/lang/${newLang}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            }).catch(() => {});
        });
    }

});