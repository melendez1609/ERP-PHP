<div id="modal-edit" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Editar Producto</h3>

        <form action="" method="POST" id="edit-inventory-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_code">Código:</label>
                <input type="text" name="code" id="edit_code" required>
            </div>

            <div>
                <label for="edit_name">Nombre:</label>
                <input type="text" name="name" id="edit_name" required>
            </div>

            <div>
                <label for="edit_description">Descripción:</label>
                <textarea name="description" id="edit_description"></textarea>
            </div>

            <div>
                <label for="edit_image">Actualizar Fotografía:</label>
                <input type="file" name="image" id="edit_image" accept="image/*">
            </div>

            <div>
                <label for="edit_supplier_id">Proveedor:</label>
                <select name="supplier_id" id="edit_supplier_id">
                    <option value="">-- Seleccionar Proveedor --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="price_update_scope">Ámbito de Actualización de Precio:</label>
                <select name="price_update_scope" id="price_update_scope" required>
                    <option value="none">Solo datos básicos (Mantener precios actuales)</option>
                    <option value="all_batches">Actualizar precio en TODOS los lotes activos</option>
                    <option value="specific_batch">Actualizar un lote específico</option>
                </select>
            </div>

            <div id="batch-selection-container" style="display: none;">
                <label for="edit_batch_id">Seleccionar Lote:</label>
                <select name="batch_id" id="edit_batch_id">
                    <option value="">-- Seleccione un lote --</option>
                </select>
            </div>

            <div id="pricing-fields-container">
                <div>
                    <label for="edit_cost">Costo ($):</label>
                    <input type="number" step="0.01" name="cost" id="edit_cost" min="0">
                </div>

                <div>
                    <label for="edit_vat_id">IVA:</label>
                    <select name="vat_id" id="edit_vat_id">
                        <option value="">-- Seleccionar IVA --</option>
                        @foreach($vats as $vat)
                            <option value="{{ $vat->id }}">{{ $vat->name }} ({{ $vat->rate }}%)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_profit_percentage">Porcentaje de Ganancia (%):</label>
                    <input type="number" step="0.01" name="profit_percentage" id="edit_profit_percentage" min="0">
                </div>

                <div>
                    <label for="edit_price">Precio Venta ($):</label>
                    <input type="number" step="0.01" name="price" id="edit_price" min="0">
                </div>

                <div id="edit-stock-container" style="display: none;">
                    <label for="edit_stock">Stock del Lote:</label>
                    <input type="number" name="stock" id="edit_stock" min="0">
                </div>
            </div>

            <div>
                <label for="edit_min_stock">Stock Mínimo:</label>
                <input type="number" name="min_stock" id="edit_min_stock" min="0" required>
            </div>

            <div>
                <label for="edit_product_status_id">Estado:</label>
                <select name="product_status_id" id="edit_product_status_id" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Actualizar</button>
            </div>
        </form>
    </div>
</div>