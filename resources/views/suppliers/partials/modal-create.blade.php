<div id="modal-create" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Nuevo Proveedor</h3>

        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <div>
                <label for="name">Empresa:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div>
                <label for="contact_name">Contacto:</label>
                <input type="text" name="contact_name" id="contact_name">
            </div>

            <div>
                <label for="phone">Teléfono:</label>
                <input type="text" name="phone" id="phone">
            </div>

            <div>
                <label for="email">Correo:</label>
                <input type="email" name="email" id="email">
            </div>

            <div>
                <label for="address">Dirección:</label>
                <textarea name="address" id="address"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>