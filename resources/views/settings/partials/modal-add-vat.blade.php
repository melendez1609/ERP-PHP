<div id="modal-create-vat" class="modal">
    <div class="modal-content" style="max-width: 480px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.15rem;">Agregar Impuesto</h3>
            <button type="button" class="modal-close" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</button>
        </div>

        <form action="{{ route('settings.vat.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 15px;">
                <div class="form-group">
                    <label for="vat_name" style="display: block; font-weight: 600; margin-bottom: 5px;">Nombre del Impuesto / Tasa</label>
                    <input type="text" 
                           id="vat_name" 
                           name="name" 
                           class="form-control" 
                           placeholder="Ej. IVA General, Retención ISR" 
                           required 
                           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div class="form-group">
                    <label for="vat_rate" style="display: block; font-weight: 600; margin-bottom: 5px;">Tasa (%)</label>
                    <input type="number" 
                           id="vat_rate" 
                           name="rate" 
                           step="0.01" 
                           min="0" 
                           max="100" 
                           placeholder="Ej. 13.00" 
                           required 
                           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 20px;">
                <button type="button" class="btn btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>