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