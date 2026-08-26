export function initStatusUser() {
    const statusToggles = document.querySelectorAll('.user-status-toggle');
    const alertModal = document.getElementById('modal-alert');

    if (!statusToggles.length || !alertModal) return;

    const resetAlertModal = () => {
        const submitBtn = alertModal.querySelector('.modal-submit-button') || alertModal.querySelector('button[type="submit"]');
        const cancelBtn = alertModal.querySelector('.btn-cancel') || alertModal.querySelector('.modal-cancel-button') || alertModal.querySelector('button[type="button"]:not(.close-modal)');

        if (submitBtn) {
            submitBtn.style.display = '';
            submitBtn.onclick = null;
        }
        if (cancelBtn) {
            cancelBtn.style.display = '';
        }
    };

    alertModal.addEventListener('click', (e) => {
        if (e.target.classList.contains('close-modal') || e.target === alertModal) {
            alertModal.style.display = 'none';
            resetAlertModal();
        }
    });

    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', async (e) => {
            const isSelf = toggle.dataset.isSelf === 'true';

            if (isSelf) {
                e.preventDefault();
                toggle.checked = true; 

                resetAlertModal();

                const titleEl = alertModal.querySelector('.alert-title') || alertModal.querySelector('h3');
                const messageEl = alertModal.querySelector('.alert-message') || alertModal.querySelector('p');
                const submitBtn = alertModal.querySelector('.modal-submit-button') || alertModal.querySelector('button[type="submit"]');
                const cancelBtn = alertModal.querySelector('.btn-cancel') || alertModal.querySelector('.modal-cancel-button') || alertModal.querySelector('button[type="button"]:not(.close-modal)');
                const form = alertModal.querySelector('form');

                if (titleEl) titleEl.textContent = 'Acción no permitida';
                if (messageEl) messageEl.textContent = 'No puedes desactivar tu propio usuario en sesión.';
                if (form) form.removeAttribute('action');

                if (submitBtn) {
                    submitBtn.style.display = '';
                    submitBtn.textContent = 'Entendido';
                    submitBtn.className = 'modal-submit-button btn-save';
                    
                    submitBtn.onclick = (btnEvent) => {
                        btnEvent.preventDefault();
                        alertModal.style.display = 'none';
                        resetAlertModal();
                    };
                }

                if (cancelBtn) {
                    cancelBtn.style.display = 'none';
                }

                alertModal.style.display = 'flex';
                return;
            }

            const userId = toggle.dataset.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch(`/users/${userId}/disable`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const statusCell = document.querySelector(`.status-text-${userId}`);
                    if (statusCell) {
                        statusCell.textContent = data.is_active ? 'Activo' : 'Inactivo';
                    }
                } else {
                    toggle.checked = !toggle.checked;
                }
            } catch (error) {
                toggle.checked = !toggle.checked;
                console.error('Error cambiando el estado:', error);
            }
        });
    });
}