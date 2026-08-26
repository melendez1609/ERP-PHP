<div id="modal-edit" class="modal">
    <div class="modal-content">
        <span class="modal-close" data-modal-close>&times;</span>
        <h3>Editar Producto</h3>
        <form id="form-edit" method="POST" action="">
            @csrf
            @method('PUT')
            
            <input type="hidden" id="edit-id" name="id">

            <div class="form-group">
                <label for="edit-code">Código</label>
                <input type="text" id="edit-code" name="code" required>
            </div>

            <div class="form-group">
                <label for="edit-name">Nombre</label>
                <input type="text" id="edit-name" name="name" required>
            </div>

            <div class="form-group">
                <label for="edit-description">Descripción</label>
                <textarea id="edit-description" name="description"></textarea>
            </div>

            <div class="form-group" style="background-color: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                <label style="font-weight: bold; margin-bottom: 12px; display: block; color: #333;">Alcance del Ajuste de Precio y Stock</label>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    
                    <label for="scope_none" style="font-weight: normal; cursor: pointer; display: flex; flex-direction: row; align-items: flex-start; gap: 10px; margin: 0;">
                        <input type="radio" id="scope_none" name="price_update_scope" value="none" checked style="margin: 2px 0 0 0; flex-shrink: 0; width: auto; cursor: pointer;">
                        <span style="line-height: 1.4;">
                            <strong style="display: block; color: #222;">Sin cambios en precios ni lotes</strong>
                            <span style="color: #666; font-size: 12px;">Mantiene los precios, IVA y stock intactos; solo edita datos generales</span>
                        </span>
                    </label>

                    <label for="scope_all" style="font-weight: normal; cursor: pointer; display: flex; flex-direction: row; align-items: flex-start; gap: 10px; margin: 0;">
                        <input type="radio" id="scope_all" name="price_update_scope" value="all_batches" style="margin: 2px 0 0 0; flex-shrink: 0; width: auto; cursor: pointer;">
                        <span style="line-height: 1.4;">
                            <strong style="display: block; color: #222;">A todo el inventario</strong>
                            <span style="color: #666; font-size: 12px;">Actualiza el precio y datos en todos los lotes activos</span>
                        </span>
                    </label>

                    <label for="scope_specific" style="font-weight: normal; cursor: pointer; display: flex; flex-direction: row; align-items: flex-start; gap: 10px; margin: 0;">
                        <input type="radio" id="scope_specific" name="price_update_scope" value="specific_batch" style="margin: 2px 0 0 0; flex-shrink: 0; width: auto; cursor: pointer;">
                        <span style="line-height: 1.4;">
                            <strong style="display: block; color: #222;">Seleccionar un lote específico</strong>
                        </span>
                    </label>

                </div>

                <div id="specific-batch-container" style="display: none; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ddd;">
                    <label for="edit-batch-id" style="font-weight: 500; font-size: 13px; display: block; margin-bottom: 5px; color: #444;">Lote a modificar:</label>
                    <select id="edit-batch-id" name="batch_id" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; background-color: #fff;">
                        <option value="">-- Seleccionar Lote --</option>
                    </select>
                </div>
            </div>

            <div id="pricing-stock-container">
                <div class="form-group">
                    <label for="edit-cost">Costo</label>
                    <input class="dynamic-field-input" type="number" step="0.01" id="edit-cost" name="cost" required>
                </div>

                <div class="form-group">
                    <label for="edit-vat-id">IVA</label>
                    <select class="dynamic-field-select" id="edit-vat-id" name="vat_id" required>
                        <option value="">-- Seleccionar IVA --</option>
                        @foreach($vats as $vat)
                            <option value="{{ $vat->id }}">{{ $vat->name }} ({{ $vat->rate }}%)</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit-profit-percentage">Porcentaje de Ganancia (%)</label>
                    <input class="dynamic-field-input" type="number" step="0.01" id="edit-profit-percentage" name="profit_percentage" min="0" required>
                </div>

                <div class="form-group">
                    <label for="edit-price">Precio</label>
                    <input type="number" step="0.01" id="edit-price" name="price" required>
                </div>

                <div class="form-group" id="edit-stock-group">
                    <label class="dynamic-field-label" for="edit-stock">Stock</label>
                    <input type="number" id="edit-stock" name="stock" required>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-min-stock">Stock Mín.</label>
                <input type="number" id="edit-min-stock" name="min_stock" required>
            </div>

            <div class="form-group">
                <label for="edit-supplier-id">Proveedor</label>
                <select id="edit-supplier-id" name="supplier_id">
                    <option value="">-- Sin Proveedor --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="edit-status-id">Estado</label>
                <select id="edit-status-id" name="product_status_id" required>
                    <option value="">-- Seleccionar Estado --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="modal-submit-button">Actualizar</button>
        </form>
    </div>
</div>