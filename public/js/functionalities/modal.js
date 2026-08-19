export function initModals() {
    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const targetId = trigger.getAttribute('data-modal-target');
            const modal = document.getElementById(targetId);
            
            if (!modal) return;

            if (targetId === 'modal-alert') {
                const titleElement = modal.querySelector('.modal-title');
                const messageElement = modal.querySelector('.modal-message');
                const submitBtn = modal.querySelector('.btn-alert-confirm');
                const form = modal.querySelector('form');
                
                let methodInput = modal.querySelector('input[name="_method"]');
                if (form && !methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }

                if (submitBtn) {
                    submitBtn.style.display = '';
                    submitBtn.textContent = trigger.dataset.btnText || 'Confirmar';
                    submitBtn.className = `btn-alert-confirm ${trigger.dataset.btnClass || 'btn-primary'}`;
                }

                const isSelf = trigger.dataset.isSelf === 'true';
                const isActive = trigger.dataset.isActive === 'true';
                const isDelete = trigger.classList.contains('delete');
                const isDisable = trigger.classList.contains('disable');

                if (titleElement) titleElement.textContent = trigger.dataset.title;
                if (messageElement) messageElement.textContent = trigger.dataset.message;
                if (form) form.action = trigger.dataset.action;
                if (methodInput) methodInput.value = trigger.dataset.method || 'POST';

                if (isSelf && (isDelete || (isDisable && isActive))) {
                    if (form) form.action = '#';
                    if (submitBtn) {
                        submitBtn.style.display = 'none'; 
                    }
                }
            }

            modal.style.display = 'flex';
        });
    });

    document.querySelectorAll('.modal').forEach(modal => {
        const closeTriggers = modal.querySelectorAll('[data-modal-close]');
        
        closeTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                modal.style.display = 'none';
                resetModalState(modal);
            });
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                resetModalState(modal);
            }
        });
    });
}

function resetModalState(modal) {
    const submitBtn = modal.querySelector('.btn-alert-confirm');
    if (submitBtn) {
        submitBtn.style.display = '';
    }
}