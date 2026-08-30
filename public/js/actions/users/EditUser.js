export function initEditUser() {
    document.addEventListener('click', (e) => {
        const button = e.target.closest('.users-table-button.edit');
        if (!button) return;

        const form = document.getElementById('form-edit-user');
        if (!form) return;

        const id = button.dataset.id;
        const name = button.dataset.name;
        const email = button.dataset.email;
        const roleId = button.dataset.roleId;
        const updateUrl = button.dataset.url;

        form.action = updateUrl || `/users/${id}`;

        const inputId = document.getElementById('edit_user_id');
        const inputName = document.getElementById('edit_name');
        const inputEmail = document.getElementById('edit_email');
        const selectRole = document.getElementById('edit_role_id');
        const inputPassword = document.getElementById('edit_password');
        const imageInput = document.getElementById('edit_image');

        if (inputId) inputId.value = id || '';
        if (inputName) inputName.value = name || '';
        if (inputEmail) inputEmail.value = email || '';
        if (selectRole) selectRole.value = roleId || '';
        if (inputPassword) inputPassword.value = '';
        if (imageInput) imageInput.value = '';
    });
}