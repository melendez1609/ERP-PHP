<div id="modal-restock" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Agregar Stock a Producto</h3>

        <form action="{{ route('inventory.addStock') }}" method="POST">
            @csrf

            <div>
                <label for="restock_product_id">Producto:</label>
                <input type="text" id="restock-product-search" placeholder="Escribe para buscar producto..." style="width: 100%; margin-top: 5px; margin-bottom: 8px; padding: 8px 12px; box-sizing: border-box;">
                
                <select name="product_id" id="restock_product_id" required>
                    <option value="">-- Seleccionar Producto --</option>
                </select>
            </div>

            <div>
                <label for="restock_stock">Cantidad a Agregar:</label>
                <input type="number" name="stock" id="restock_stock" min="1" required>
            </div>

            <div>
                <label for="restock_cost">Costo ($):</label>
                <input type="number" step="0.01" name="cost" id="restock_cost" min="0" required>
            </div>

            <div>
                <label for="restock_vat_id">IVA:</label>
                <select name="vat_id" id="restock_vat_id" required>
                    <option value="">-- Seleccionar IVA --</option>
                    @foreach($vats as $vat)
                        <option value="{{ $vat->id }}" {{ old('vat_id', 1) == $vat->id ? 'selected' : '' }}>
                            {{ $vat->name }} ({{ $vat->rate }}%)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="restock_profit_percentage">Porcentaje de Ganancia (%):</label>
                <input type="number" step="0.01" name="profit_percentage" id="restock_profit_percentage" min="0" required>
            </div>

            <div>
                <label for="restock_price">Precio Venta ($):</label>
                <input type="number" step="0.01" name="price" id="restock_price" min="0" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Agregar Stock</button>
            </div>
        </form>
    </div>
</div>