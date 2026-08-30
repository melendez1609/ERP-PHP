<div id="modal-create-event" class="modal">
    <div class="modal-content" style="max-width: 500px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0;">Nueva Actividad</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>

        <form action="{{ route('schedules.store') }}" method="POST" id="form-create-event">
            @csrf
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <label for="create_title" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Nombre de la Actividad:</label>
                    <input type="text" name="title" id="create_title" required placeholder="Ej. Reunión de Inventario" style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;">
                </div>

                <div style="display: flex; gap: 12px;">
                    <div style="flex: 1;">
                        <label for="create_event_date" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Fecha:</label>
                        <input type="date" name="event_date" id="create_event_date" required style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;">
                    </div>
                    <div style="flex: 1;">
                        <label for="create_event_time" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Hora:</label>
                        <input type="time" name="event_time" id="create_event_time" required style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none;">
                    </div>
                </div>

                <div>
                    <label for="create_color" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Etiqueta de Color:</label>
                    <select name="color" id="create_color" required style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; background-color: #fff;">
                        <option value="#3b82f6">🔵 Azul (General)</option>
                        <option value="#e07a5f">🟠 Naranja (Urgente)</option>
                        <option value="#f59e0b">🟡 Amarillo (Pendiente)</option>
                        <option value="#8b5cf6">🟣 Púrpura (En curso)</option>
                        <option value="#10b981">🟢 Verde (Completado)</option>
                    </select>
                </div>

                <div>
                    <label for="create_description" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">Descripción:</label>
                    <textarea name="description" id="create_description" rows="3" placeholder="Detalles adicionales del evento..." style="width: 100%; box-sizing: border-box; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; outline: none; resize: vertical;"></textarea>
                </div>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
                <button type="button" class="btn btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn-save">Guardar Actividad</button>
            </div>
        </form>
    </div>
</div>