export function initEditUser() {
    const editButtons = document.querySelectorAll('.users-table-button.edit');
    const form = document.getElementById('form-edit-user');

    if (!editButtons.length || !form) return;

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const email = button.dataset.email;
            const roleId = button.dataset.roleId;

            form.action = `/users/${id}`;

            const inputId = document.getElementById('edit_user_id');
            const inputName = document.getElementById('edit_name');
            const inputEmail = document.getElementById('edit_email');
            const selectRole = document.getElementById('edit_role_id');
            const inputPassword = document.getElementById('edit_password');

            if (inputId) inputId.value = id;
            if (inputName) inputName.value = name;
            if (inputEmail) inputEmail.value = email;
            if (selectRole) selectRole.value = roleId;
            if (inputPassword) inputPassword.value = '';
        });
    });
}