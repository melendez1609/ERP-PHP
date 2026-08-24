export function initPurchaseOrderActions() {
    document.addEventListener('change', (event) => {
        if (!event.target.classList.contains('po-action-select')) return;

        const select = event.target;
        const actionType = select.value;
        if (!actionType) return;

        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;

        if (actionType === 'pdf') {
            const pdfUrl = selectedOption.dataset.pdfUrl;
            if (pdfUrl && pdfUrl !== '#') {
                window.open(pdfUrl, '_blank');
            }
            select.selectedIndex = 0;
            select.value = '';
            select.blur();
            return;
        }

        const modalTarget = selectedOption.dataset.modalTarget || 'modal-alert';
        const action = selectedOption.dataset.action;
        const method = selectedOption.dataset.method || 'POST';
        const titleText = selectedOption.dataset.title;
        const messageText = selectedOption.dataset.message;
        const btnText = selectedOption.dataset.btnText;
        const btnClass = selectedOption.dataset.btnClass;

        select.selectedIndex = 0;
        select.value = '';
        select.blur();

        const modal = document.getElementById(modalTarget);
        if (!modal) {
            console.error(`No se encontró el modal: ${modalTarget}`);
            return;
        }

        const form = modal.querySelector('form');
        const title = modal.querySelector('.modal-title') || modal.querySelector('h3, h4, .title');
        const message = modal.querySelector('.modal-message') || modal.querySelector('p, .message');
        const btnSubmit = modal.querySelector('button[type="submit"]') || modal.querySelector('.btn-confirm');

        if (form && action && action !== '#') {
            form.action = action;

            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = method;
        }

        if (title && titleText) title.textContent = titleText;
        if (message && messageText) message.textContent = messageText;

        if (btnSubmit) {
            if (btnText) btnSubmit.textContent = btnText;
            if (btnClass) btnSubmit.className = `btn ${btnClass}`;
        }

        modal.style.removeProperty('display');
        modal.removeAttribute('aria-hidden');
        modal.classList.add('show');
    });

    document.addEventListener('click', (event) => {
        const closeBtn = event.target.closest('.btn-cancel, .close, [data-dismiss="modal"], [data-bs-dismiss="modal"]');
        if (!closeBtn) return;

        const modal = closeBtn.closest('.modal, #modal-alert');
        if (modal) {
            modal.classList.remove('show');
            modal.style.removeProperty('display');
            modal.removeAttribute('aria-hidden');
        }

        document.querySelectorAll('.po-action-select').forEach(s => {
            s.selectedIndex = 0;
            s.value = '';
            s.blur();
        });
    });
}