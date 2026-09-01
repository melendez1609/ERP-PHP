export function initPosOptions() {
    const modal = document.getElementById('modal-alert');
    if (!modal) return;

    const autoOpen = modal.dataset.autoOpen === 'true';
    if (!autoOpen) return;

    const titleEl = modal.querySelector('.modal-title');
    const messageEl = modal.querySelector('.modal-message');
    const confirmBtn = modal.querySelector('.btn-alert-confirm');
    const cancelBtn = modal.querySelector('.modal-footer .btn-cancel');
    const isDiscrepancy = modal.dataset.isDiscrepancy === 'true';

    if (titleEl && modal.dataset.title) {
        titleEl.textContent = modal.dataset.title;
    }

    if (messageEl && modal.dataset.message) {
        messageEl.textContent = modal.dataset.message;
    }

    if (isDiscrepancy) {
        if (confirmBtn) {
            confirmBtn.style.display = 'inline-block';
            confirmBtn.textContent = 'Confirmar Cierre';
        }
        if (cancelBtn) {
            cancelBtn.textContent = 'Cancelar';
        }
    } else {
        if (confirmBtn) {
            confirmBtn.style.display = 'none';
        }
        if (cancelBtn) {
            cancelBtn.textContent = 'Entendido';
        }
    }

    modal.style.display = 'flex';
    modal.classList.add('active', 'show');
}