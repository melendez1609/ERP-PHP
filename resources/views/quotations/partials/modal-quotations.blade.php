<div id="modal-quotations" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Nueva Cotización</h3>

        <form action="{{ route('quotations.store') }}" method="POST" id="form-quotation" target="_blank">
            @csrf

            <div style="margin-bottom: 15px;">
                <label for="customer_name">Nombre del Cliente:</label>
                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="select-product">Seleccionar Producto:</label>
                
                <!-- Buscador rápido en tiempo real -->
                <input type="text" id="product-search" placeholder="Escribe para buscar un producto..." style="width: 100%; margin-top: 5px; margin-bottom: 8px; padding: 8px 12px; box-sizing: border-box;">

                <div style="display: flex; gap: 10px; margin-top: 5px;">
                    <select id="select-product" style="flex: 1;">
                        <option value="">-- Selecciona un producto --</option>
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" id="input-quantity" value="1" min="1" style="width: 80px;" placeholder="Cant.">
                    <button type="button" class="btn-save" id="btn-add-product" style="padding: 8px 12px;">+ Agregar</button>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid #ccc; text-align: left;">
                            <th style="padding: 8px 0;">Producto</th>
                            <th style="padding: 8px 0; text-align: center;">Cantidad</th>
                            <th style="padding: 8px 0; text-align: right;">Precio</th>
                            <th style="padding: 8px 0; text-align: right;">Subtotal</th>
                            <th style="padding: 8px 0; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="quotation-items-body">
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 15px; text-align: right; font-weight: bold; font-size: 1.1em;">
                Total: $<span id="quotation-total">0.00</span>
            </div>

            <div class="modal-actions" style="margin-top: 25px;">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Guardar Cotización</button>
            </div>
        </form>
    </div>
</div>