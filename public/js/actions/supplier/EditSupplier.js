export function initEditSupplier() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.suppliers-table-button.edit');
        if (!btn) return;

        const modal = document.getElementById('modal-edit');
        if (!modal) return;

        const form = modal.querySelector('form');
        if (!form) return;

        const id = btn.dataset.id;
        const name = btn.dataset.name || '';
        const contactName = btn.dataset.contact_name || btn.dataset.contact || '';
        const phone = btn.dataset.phone || '';
        const email = btn.dataset.email || '';
        const address = btn.dataset.address || '';

        form.action = `/suppliers/${id}`;

        const inputName = form.querySelector('[name="name"]');
        const inputContact = form.querySelector('[name="contact_name"]');
        const inputPhone = form.querySelector('[name="phone"]');
        const inputEmail = form.querySelector('[name="email"]');
        const inputAddress = form.querySelector('[name="address"]');

        if (inputName) inputName.value = name;
        if (inputContact) inputContact.value = contactName;
        if (inputPhone) inputPhone.value = phone;
        if (inputEmail) inputEmail.value = email;
        if (inputAddress) inputAddress.value = address;
    });
}