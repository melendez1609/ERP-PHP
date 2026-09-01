<div id="modal-cash-opening" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.25rem; color: #132873;">Apertura de Caja</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>

        <form action="{{ route('cash-register.open') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="cashier_id" style="font-weight: 600; margin-bottom: 5px; display: block;">Cajero Asignado</label>
                    <select name="user_id" id="cashier_id" class="form-control" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #d1d5db;">
                        <option value="" disabled selected>Seleccione un cajero...</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="opening_amount" style="font-weight: 600; margin-bottom: 5px; display: block;">Fondo Inicial ($)</label>
                    <input type="number" step="0.01" min="0" name="opening_amount" id="opening_amount" placeholder="0.00" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>

                <hr class="setting-divider" style="border: 0; border-top: 1px solid #e5e7eb; margin: 20px 0 15px 0;">

                <div class="setting-info" style="margin-bottom: 12px;">
                    <h5 style="margin: 0 0 4px 0; font-size: 0.95rem; color: #132873;">Autorización de Administrador</h5>
                    <p style="margin: 0; font-size: 0.85rem; color: #666;">Ingrese credenciales de administrador para autorizar la apertura.</p>
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="admin_email" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 4px; display: block;">Usuario / Correo Admin</label>
                    <input type="email" name="admin_email" id="admin_email" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="admin_password" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 4px; display: block;">Contraseña Admin</label>
                    <input type="password" name="admin_password" id="admin_password" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
                <button type="button" class="btn btn-cancel" data-modal-close style="padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer;">Cancelar</button>
                <button type="submit" class="btn btn-save" style="padding: 8px 16px; background-color: #132873; color: #fff; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">Aperturar Caja</button>
            </div>
        </form>
    </div>
</div>