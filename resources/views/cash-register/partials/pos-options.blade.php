<div id="modal-pos-options" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.25rem; color: #132873;">Gestión de Caja</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>

        <div class="modal-body">
            <form action="{{ route('cash-register.movement') }}" method="POST" style="margin-bottom: 20px;">
                @csrf
                <h5 style="margin: 0 0 10px 0; font-size: 1rem; color: #132873;">Ingreso / Retiro de Efectivo</h5>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600;">Tipo de Operación</label>
                        <select name="type" class="form-control" required style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #d1d5db;">
                            <option value="in">Ingreso de Efectivo (+)</option>
                            <option value="out">Retiro de Efectivo (-)</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600;">Monto ($)</label>
                        <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00" style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #d1d5db;">
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Motivo / Concepto</label>
                    <input type="text" name="description" required placeholder="Ej. Exceso de efectivo / Refuerzo de cambio" style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Contraseña Autorización Admin</label>
                    <input type="password" name="admin_password" required placeholder="Ingrese contraseña de admin" style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>

                <button type="submit" class="btn btn-save" style="width: 100%; padding: 8px; background: #0284c7; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Registrar Movimiento</button>
            </form>

            <hr class="setting-divider" style="border: 0; border-top: 1px solid #e5e7eb; margin: 15px 0;">

            <form action="{{ route('cash-register.close') }}" method="POST">
                @csrf
                <h5 style="margin: 0 0 10px 0; font-size: 1rem; color: #d84130;">Cierre de Caja (Corte)</h5>

                <div style="margin-bottom: 10px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Monto Total Contado ($)</label>
                    <input type="number" step="0.01" min="0" name="closing_amount" required placeholder="0.00" style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Contraseña Autorización Admin</label>
                    <input type="password" name="admin_password" required placeholder="Ingrese contraseña de admin" style="width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>

                <button type="submit" class="btn btn-danger" style="width: 100%; padding: 8px; background: #d84130; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Realizar Cierre de Caja</button>
            </form>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
            <button type="button" class="btn btn-cancel" data-modal-close style="padding: 6px 14px; border-radius: 6px; border: none; cursor: pointer;">Cerrar</button>
        </div>
    </div>
</div>