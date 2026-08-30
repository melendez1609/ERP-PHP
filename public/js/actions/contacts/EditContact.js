export function initEditContact() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.contacts-table-button.edit');
        if (!btn) return;

        const modal = document.getElementById('modal-edit');
        if (!modal) return;

        const form = modal.querySelector('form');
        if (!form) return;

        const id = btn.dataset.id;
        const name = btn.dataset.name || '';
        const contactType = btn.dataset.contact_type || '';
        const phone = btn.dataset.phone || '';
        const email = btn.dataset.email || '';
        const address = btn.dataset.address || '';

        form.action = `/contacts/${id}`;

        const inputName = form.querySelector('[name="name"]');
        const inputContactType = form.querySelector('[name="contact_type"]');
        const inputPhone = form.querySelector('[name="phone"]');
        const inputEmail = form.querySelector('[name="email"]');
        const inputAddress = form.querySelector('[name="address"]');

        if (inputName) inputName.value = name;
        if (inputContactType) inputContactType.value = contactType;
        if (inputPhone) inputPhone.value = phone;
        if (inputEmail) inputEmail.value = email;
        if (inputAddress) inputAddress.value = address;
    });
}