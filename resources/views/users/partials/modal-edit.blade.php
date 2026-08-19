<div id="modal-edit" class="modal">
    <div class="modal-content">
        <span class="modal-close" data-modal-close>&times;</span>
        <h3>Editar Usuario</h3>
        
        <form id="form-edit-user" method="POST" action="">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="id" id="edit_user_id">

            <div>
                <label for="edit_name">Nombre:</label>
                <input type="text" name="name" id="edit_name" required>
            </div>

            <div>
                <label for="edit_email">Correo Electrónico:</label>
                <input type="email" name="email" id="edit_email" required>
            </div>

            <div>
                <label for="edit_password">Nueva Contraseña (Opcional):</label>
                <input type="password" name="password" id="edit_password" placeholder="Dejar en blanco para mantener la actual">
            </div>

            <div>
                <label for="edit_role_id">Rol:</label>
                <select name="role_id" id="edit_role_id" required>
                    <option value="">-- Seleccionar Rol --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Actualizar</button>
            </div>
        </form>
    </div>
</div>