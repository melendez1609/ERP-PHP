<div id="modal-purchase-order" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Nueva Orden de Compra</h3>

        <form action="{{ route('purchase-orders.store') }}" method="POST" id="form-purchase-order">
            @csrf

            <div style="margin-bottom: 15px;">
                <label for="po-supplier-id">Proveedor:</label>
                <select name="supplier_id" id="po-supplier-id" required style="width: 100%; margin-top: 5px; padding: 8px 12px; box-sizing: border-box;">
                    <option value="">-- Selecciona un proveedor --</option>
                    @foreach($suppliers ?? [] as $supplier)
                        <option value="{{ $supplier->id }}">
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="po-product-search">Seleccionar Producto:</label>
                
                <input type="text" 
                       id="po-product-search" 
                       placeholder="Escribe para buscar producto..." 
                       style="width: 100%; margin-top: 5px; margin-bottom: 8px; padding: 8px 12px; box-sizing: border-box;" 
                       disabled>

                <div style="display: flex; gap: 10px;">
                    <select id="po-select-product" style="flex: 1; padding: 8px 12px; box-sizing: border-box;" disabled>
                        <option value="">-- Selecciona un proveedor primero --</option>
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}" 
                                    data-supplier-id="{{ $product->supplier_id }}" 
                                    data-name="{{ $product->name }}" 
                                    data-cost="{{ $product->cost }}">
                                {{ $product->name }} - ${{ number_format($product->cost, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" id="po-input-quantity" value="1" min="1" style="width: 80px; padding: 8px; box-sizing: border-box;" placeholder="Cant.">
                    <button type="button" class="btn-save" id="po-btn-add-product" style="padding: 8px 12px;">+ Agregar</button>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid #ccc; text-align: left;">
                            <th style="padding: 8px 0;">Producto</th>
                            <th style="padding: 8px 0; text-align: center;">Cantidad</th>
                            <th style="padding: 8px 0; text-align: right;">Costo Unit.</th>
                            <th style="padding: 8px 0; text-align: right;">Subtotal</th>
                            <th style="padding: 8px 0; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="po-items-body">
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 15px; text-align: right; font-weight: bold; font-size: 1.1em;">
                Total: $<span id="po-total">0.00</span>
            </div>

            <div class="modal-actions" style="margin-top: 25px;">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save" id="btn-save-po">Guardar Orden de Compra</button>
            </div>
        </form>
    </div>
</div>