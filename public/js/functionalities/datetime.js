export function initDateTime() {
    const datetimeDiv = document.querySelector('.footer-datetime');
    if (!datetimeDiv) return;

    const spans = datetimeDiv.querySelectorAll('span');
    if (spans.length < 2) return;

    const dateSpan = spans[0];
    const timeSpan = spans[1];

    function updateClock() {
        const now = new Date();

        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();

        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        dateSpan.textContent = `${day}/${month}/${year}`;
        timeSpan.textContent = `${hours}:${minutes}:${seconds}`;
    }

    updateClock();
    setInterval(updateClock, 1000);
}