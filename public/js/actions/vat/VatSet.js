export function initVatSet() {
    document.addEventListener('click', (e) => {
        const triggerBtn = e.target.closest('button[data-action-form]');
        if (!triggerBtn) return;

        e.preventDefault();

        const formId = triggerBtn.getAttribute('data-action-form');
        const targetForm = document.getElementById(formId);
        const modal = document.getElementById('modal-alert');

        if (!targetForm || !modal) return;

        const title = triggerBtn.getAttribute('data-title') || 'Confirmación';
        const message = triggerBtn.getAttribute('data-message') || '¿Deseas guardar los cambios?';

        const modalTitle = modal.querySelector('h3, .modal-title');
        const modalMessage = modal.querySelector('p, .modal-message');
        const confirmBtn = modal.querySelector('.btn-save, #modal-confirm-btn, button[type="submit"]');

        if (modalTitle) modalTitle.textContent = title;
        if (modalMessage) modalMessage.textContent = message;

        modal.classList.add('active');

        if (confirmBtn) {
            confirmBtn.setAttribute('type', 'button');

            confirmBtn.onclick = (evt) => {
                evt.preventDefault();
                targetForm.submit(); 
            };
        }
    });
}