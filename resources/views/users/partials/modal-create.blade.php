<div id="modal-create" class="modal">
    <div class="modal-content">
        <span class="modal-close" data-modal-close>&times;</span>
        <h3>Crear Usuario</h3>
        
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div>
                <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div>
                <label for="email">Correo Electrónico:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div>
                <label for="role_id">Rol:</label>
                <select name="role_id" id="role_id" required>
                    <option value="">-- Seleccionar Rol --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="image">Fotografía:</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>