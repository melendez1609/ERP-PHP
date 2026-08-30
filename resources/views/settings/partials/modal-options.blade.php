<div id="modal-settings" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0;">Configuración del Sistema</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>
        
        <div class="modal-body">
            <hr class="setting-divider" style="border: 0; border-top: 1px solid #e5e7eb; margin: 15px 0;">

            <div class="setting-option-row" style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                <div class="setting-info">
                    <h5 style="margin: 0 0 4px 0; font-size: 1rem;">Impuestos y Tasas</h5>
                    <p style="margin: 0; font-size: 0.85rem; color: #666;">Gestión de tasa de impuesto aplicable al sistema.</p>
                </div>
                <div class="setting-action">
                    <a href="{{ route('settings.vat') }}" class="btn btn-save" style="white-space: nowrap; text-decoration: none;">Configurar</a>
                </div>
            </div>

            <hr class="setting-divider" style="border: 0; border-top: 1px solid #e5e7eb; margin: 15px 0;">

            <div class="setting-option-row" style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                <div class="setting-info">
                    <h5 style="margin: 0 0 4px 0; font-size: 1rem;">Volumen</h5>
                    <p style="margin: 0; font-size: 0.85rem; color: #666;">Ajustar el nivel del sistema.</p>
                </div>
                <div class="setting-action">
                    <button type="button" class="btn btn-save" data-modal-target="modal-volume" style="white-space: nowrap;">Configurar</button>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
            <button type="button" class="btn btn-cancel" data-modal-close>Cerrar</button>
        </div>
    </div>
</div>