export function initCashRegisterSession() {
    const container = document.querySelector('.erp-cash-register-container');
    if (!container) return;

    const hasSession = container.dataset.hasSession === 'true';

    if (!hasSession) {
        const modalOpening = document.getElementById('modal-cash-opening');
        if (modalOpening) {
            modalOpening.style.display = 'flex';
        }
    }
}