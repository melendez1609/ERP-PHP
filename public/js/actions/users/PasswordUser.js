export function initPasswordUser() {
    const passwordModal = document.getElementById('modal-password');
    if (!passwordModal) return;

    const form = passwordModal.querySelector('form');
    const alertModal = document.getElementById('modal-alert');
    if (!form || !alertModal) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const actionUrl = this.action;
        const formData = new FormData(this);
        const token = this.querySelector('input[name="_token"]')?.value ||
                      document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            passwordModal.classList.remove('active');

            const titleEl = alertModal.querySelector('.modal-title');
            const messageEl = alertModal.querySelector('.modal-message');
            const confirmBtn = alertModal.querySelector('.btn-alert-confirm');
            const cancelBtn = alertModal.querySelector('.btn-cancel');

            if (response.ok && data.success) {
                form.reset();

                if (titleEl) titleEl.textContent = '¡Contraseña actualizada!';
                if (messageEl) messageEl.textContent = data.message || 'Contraseña actualizada correctamente.';
            } else {
                let errorMessage = data.message;
                if (data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    errorMessage = data.errors[firstKey][0];
                }

                if (titleEl) titleEl.textContent = 'Verifica tu contraseña';
                if (messageEl) messageEl.textContent = errorMessage || 'Las contraseñas no coinciden. Verifica que ambas sean iguales e inténtalo nuevamente.';
            }

            if (confirmBtn) confirmBtn.style.display = 'none';
            if (cancelBtn) cancelBtn.textContent = 'Entendido';

            alertModal.style.zIndex = '9999';
            alertModal.classList.add('active');

        } catch (error) {
            console.error('Error al cambiar la contraseña:', error);

            passwordModal.classList.remove('active');

            const titleEl = alertModal.querySelector('.modal-title');
            const messageEl = alertModal.querySelector('.modal-message');
            const confirmBtn = alertModal.querySelector('.btn-alert-confirm');
            const cancelBtn = alertModal.querySelector('.btn-cancel');

            if (titleEl) titleEl.textContent = 'Error de servidor';
            if (messageEl) messageEl.textContent = 'Ocurrió un error inesperado al procesar la solicitud.';
            if (confirmBtn) confirmBtn.style.display = 'none';
            if (cancelBtn) cancelBtn.textContent = 'Entendido';

            alertModal.style.zIndex = '9999';
            alertModal.classList.add('active');
        }
    });
}