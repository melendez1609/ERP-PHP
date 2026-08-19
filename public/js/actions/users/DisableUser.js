export function initDisableUser() {
    const disableButtons = document.querySelectorAll('.users-table-button.disable');
    const alertModal = document.getElementById('modal-alert');

    if (!disableButtons.length || !alertModal) return;

    const form = alertModal.querySelector('form');
    const titleElement = alertModal.querySelector('.modal-title');
    const messageElement = alertModal.querySelector('.modal-message');
    const submitBtn = alertModal.querySelector('.btn-alert-confirm');
    let methodInput = alertModal.querySelector('input[name="_method"]');

    if (form && !methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
    }

    disableButtons.forEach(button => {
        button.addEventListener('click', () => {
            const isSelf = button.dataset.isSelf === 'true';
            const isActive = button.dataset.isActive === 'true';

            if (submitBtn) {
                submitBtn.style.display = '';
                submitBtn.textContent = button.dataset.btnText || 'Confirmar';
                submitBtn.className = `btn-alert-confirm ${button.dataset.btnClass || 'btn-save'}`;
            }

            if (titleElement) titleElement.textContent = button.dataset.title;
            if (messageElement) messageElement.textContent = button.dataset.message;
            if (form) form.action = button.dataset.action;
            if (methodInput) methodInput.value = button.dataset.method || 'PATCH';

            if (isSelf && isActive) {
                if (form) form.action = '#';
                if (submitBtn) {
                    submitBtn.style.display = 'none';
                }
            }
        });
    });
}