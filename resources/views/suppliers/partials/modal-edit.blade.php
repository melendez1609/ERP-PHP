<div id="modal-edit" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Editar Proveedor</h3>

        <form action="" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_name">Nombre:</label>
                <input type="text" name="name" id="edit_name" required>
            </div>

            <div>
                <label for="edit_contact_name">Contacto:</label>
                <input type="text" name="contact_name" id="edit_contact_name">
            </div>

            <div>
                <label for="edit_phone">Teléfono:</label>
                <input type="text" name="phone" id="edit_phone">
            </div>

            <div>
                <label for="edit_email">Correo:</label>
                <input type="email" name="email" id="edit_email">
            </div>

            <div>
                <label for="edit_address">Dirección:</label>
                <textarea name="address" id="edit_address"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Actualizar</button>
            </div>
        </form>
    </div>
</div>