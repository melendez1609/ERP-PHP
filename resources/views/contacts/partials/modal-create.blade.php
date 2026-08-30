<div id="modal-create" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Nuevo Contacto</h3>

        <form action="{{ route('contacts.store') }}" method="POST">
            @csrf

            <div>
                <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" required placeholder="Nombre del contacto o cliente">
            </div>

            <div>
                <label for="contact_type">Tipo de Contacto:</label>
                <select name="contact_type" id="contact_type">
                    <option value="" disabled selected hidden>Seleccione un tipo...</option>
                    <option value="WhatsApp">WhatsApp</option>
                    <option value="Teléfono Fijo">Teléfono Fijo</option>
                    <option value="Celular Trabajo">Celular Trabajo</option>
                    <option value="Celular Personal">Celular Personal</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div>
                <label for="phone">Teléfono:</label>
                <input type="text" name="phone" id="phone" placeholder="Número telefónico">
            </div>

            <div>
                <label for="email">Correo:</label>
                <input type="email" name="email" id="email" placeholder="correo@ejemplo.com">
            </div>

            <div>
                <label for="address">Dirección:</label>
                <textarea name="address" id="address" placeholder="Dirección o notas adicionales"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>