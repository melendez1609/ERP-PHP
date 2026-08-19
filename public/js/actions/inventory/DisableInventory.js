export function initDisableInventory() {
    const disableButtons = document.querySelectorAll('.inventory-table-button.disable');
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
            const action = button.dataset.action;
            const title = button.dataset.title;
            const message = button.dataset.message;
            const btnText = button.dataset.btnText;
            const btnClass = button.dataset.btnClass;

            if (form) form.action = action;
            if (titleElement) titleElement.textContent = title;
            if (messageElement) messageElement.textContent = message;

            if (submitBtn) {
                submitBtn.textContent = btnText;
                submitBtn.className = `btn ${btnClass}`;
            }

            if (methodInput) {
                methodInput.value = 'PATCH';
            }
        });
    });
}