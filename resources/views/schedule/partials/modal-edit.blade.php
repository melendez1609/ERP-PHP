<div id="modal-edit-event" class="modal">
    <div class="modal-content" style="max-width: 500px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0;">Editar Evento</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>

        <form action="" method="POST" id="form-edit-event">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="event_id" id="edit_event_id">

            <div class="modal-body" style="display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <label for="edit_title" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Nombre del Evento:</label>
                    <input type="text" name="title" id="edit_title" required style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;">
                </div>

                <div style="display: flex; gap: 12px;">
                    <div style="flex: 1;">
                        <label for="edit_event_date" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Fecha:</label>
                        <input type="date" name="event_date" id="edit_event_date" required style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;">
                    </div>
                    <div style="flex: 1;">
                        <label for="edit_event_time" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Hora:</label>
                        <input type="time" name="event_time" id="edit_event_time" required style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;">
                    </div>
                </div>

                <div>
                    <label for="edit_color" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Etiqueta de Color:</label>
                    <select name="color" id="edit_color" required style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; background-color: #fff;">
                        <option value="#3b82f6">🔵 Azul (General)</option>
                        <option value="#e07a5f">🟠 Naranja (Urgente / Cierre)</option>
                        <option value="#f59e0b">🟡 Amarillo (Pendiente)</option>
                        <option value="#8b5cf6">🟣 Púrpura (Personal)</option>
                        <option value="#10b981">🟢 Verde (Completado)</option>
                    </select>
                </div>

                <div>
                    <label for="edit_description" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Descripción:</label>
                    <textarea name="description" id="edit_description" rows="3" style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; resize: vertical;"></textarea>
                </div>
            </div>

            <!-- Contenedor alineado estrictamente en 1 sola fila sin salto de línea -->
            <div class="modal-footer" style="display: flex !important; flex-direction: row !important; justify-content: space-between !important; align-items: center !important; flex-wrap: nowrap !important; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
                <button type="button" class="btn btn-cancel" id="btn-delete-event" style="background-color: #ef4444; color: #fff; margin: 0; white-space: nowrap;">Eliminar Evento</button>
                
                <div style="display: flex !important; flex-direction: row !important; align-items: center !important; gap: 8px !important; margin: 0; white-space: nowrap;">
                    <button type="button" class="btn btn-cancel" data-modal-close style="margin: 0;">Cancelar</button>
                    <button type="submit" class="btn btn-save" style="margin: 0;">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>