export function initAlertModal() {
    const modal = document.getElementById('modal-alert');
    if (!modal) return;

    const titleEl = modal.querySelector('.modal-title');
    const messageEl = modal.querySelector('.modal-message');
    const form = modal.querySelector('form');
    const confirmBtn = modal.querySelector('.btn-alert-confirm');
    const cancelBtn = modal.querySelector('.btn-cancel');

    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-modal-target="modal-alert"]');
        if (!trigger) return;

        const isInfoMode = trigger.dataset.mode === 'info';

        if (titleEl) titleEl.textContent = trigger.dataset.title || 'Confirmar acción';
        if (messageEl) messageEl.textContent = trigger.dataset.message || '¿Estás seguro de realizar esta acción?';

        if (isInfoMode) {
            if (confirmBtn) confirmBtn.style.display = 'none';
            if (cancelBtn) cancelBtn.textContent = 'Entendido';
            if (form) form.removeAttribute('action');
        } else {
            if (confirmBtn) {
                confirmBtn.style.display = 'inline-block';
                confirmBtn.textContent = trigger.dataset.btnText || 'Confirmar';
                
                confirmBtn.className = 'btn btn-alert-confirm';
                if (trigger.dataset.btnClass) {
                    confirmBtn.classList.add(trigger.dataset.btnClass);
                }
            }

            if (cancelBtn) cancelBtn.textContent = 'Cancelar';

            if (form) {
                form.action = trigger.dataset.action || '';
                
                let methodInput = form.querySelector('input[name="_method"]');
                const reqMethod = (trigger.dataset.method || 'POST').toUpperCase();

                if (reqMethod !== 'POST') {
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = reqMethod;
                } else if (methodInput) {
                    methodInput.remove();
                }
            }
        }

        modal.classList.add('active');
    });

    modal.addEventListener('click', (e) => {
        if (e.target.hasAttribute('data-modal-close') || e.target === modal) {
            modal.classList.remove('active');
        }
    });
}