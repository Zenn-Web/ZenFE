import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.store('lang', {
    current: document.documentElement.getAttribute('lang') || 'id',
    toggle() {
        const newLang = this.current === 'id' ? 'en' : 'id';
        this.current = newLang;

        // Ganti teks semua elemen bertranslasi (tidak sentuh DOM structure)
        document.querySelectorAll('[data-i18n-id]').forEach(el => {
            const attr = newLang === 'en' ? 'data-i18n-en' : 'data-i18n-id';
            const val = el.getAttribute(attr);
            if (val !== null) {
                el.innerHTML = val;
            }
        });

        // Update lang attribute di <html>
        document.documentElement.setAttribute('lang', newLang);

        // Update label tombol toggle (ID ↔ EN)
        const langLabel = document.querySelector('#lang-toggle .lang-label');
        if (langLabel) {
            langLabel.textContent = newLang === 'en' ? 'ID' : 'EN';
        }

        // Paksa Lenis resize untuk re-sync scroll position setelah konten berubah
        if (window.lenis && typeof window.lenis.resize === 'function') {
            window.lenis.resize();
        }

        // Sync ke server (fire-and-forget, tidak menunggu response)
        const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        fetch(`/lang/${newLang}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        }).catch(() => {});
    }
});

Alpine.start();
