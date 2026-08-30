// public/js/actions/users/DeleteAlert.js

export function initDeleteUser() {
    const modal = document.getElementById('modal-alert');
    if (!modal) return;

    const form = modal.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        const actionUrl = this.action;
        const methodInput = this.querySelector('input[name="_method"]');
        const isUserDelete = actionUrl.includes('/users/') && methodInput?.value?.toUpperCase() === 'DELETE';

        if (!isUserDelete) return;

        e.preventDefault();
        e.stopPropagation();

        const token = this.querySelector('input[name="_token"]')?.value || 
                      document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const response = await fetch(actionUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.reload();
            } else {
                const titleEl = modal.querySelector('.modal-title');
                const messageEl = modal.querySelector('.modal-message');
                const confirmBtn = modal.querySelector('.btn-alert-confirm');
                const cancelBtn = modal.querySelector('.btn-cancel');

                if (titleEl) titleEl.textContent = 'Acción no permitida';
                if (messageEl) messageEl.textContent = data.message || 'El usuario no puede ser eliminado porque se encuentra asignado a registros en el sistema.';
                if (confirmBtn) confirmBtn.style.display = 'none';
                if (cancelBtn) cancelBtn.textContent = 'Entendido';

                modal.classList.add('active');
            }
        } catch (error) {
            console.error('Error al intentar eliminar el usuario:', error);

            const titleEl = modal.querySelector('.modal-title');
            const messageEl = modal.querySelector('.modal-message');
            const confirmBtn = modal.querySelector('.btn-alert-confirm');
            const cancelBtn = modal.querySelector('.btn-cancel');

            if (titleEl) titleEl.textContent = 'Error de servidor';
            if (messageEl) messageEl.textContent = 'Ocurrió un error inesperado al procesar la solicitud.';
            if (confirmBtn) confirmBtn.style.display = 'none';
            if (cancelBtn) cancelBtn.textContent = 'Entendido';

            modal.classList.add('active');
        }
    });
}