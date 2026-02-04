import './bootstrap';

const updateClock = () => {
    const clockElement = document.getElementById('live-clock');
    if (clockElement) {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        clockElement.textContent = `${hours}:${minutes}`;
    }
};

document.addEventListener('DOMContentLoaded', () => {
    updateClock();
    setInterval(updateClock, 60000); // Cukup update setiap 1 menit
});


document.addEventListener("DOMContentLoaded", function() {
    const observerOptions = {
        threshold: 0.15 // Sedikit dikurangi agar animasi mulai lebih awal saat scroll
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const elements = entry.target.querySelectorAll('.animate-text, .animate-buttons');
                
                elements.forEach((el, index) => {
                    // Cek apakah elemen sudah punya class reveal untuk mencegah animasi berulang
                    if (!el.classList.contains('reveal')) {
                        setTimeout(() => {
                            el.classList.add('reveal');
                        }, index * 200); // Jeda antar teks (200ms) agar terasa mengalir
                    }
                });
                
                // Opsional: Berhenti mengamati jika sudah muncul semua
                // observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    // Pastikan elemen .about-section benar-benar ada di HTML
    const target = document.querySelector('.about-section');
    if (target) {
        observer.observe(target);
    }
});
