<div id="modal-edit-purchase-order" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 900px;">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Editar Orden de Compra</h3>

        <form id="edit-purchase-order-form" action="" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 15px;">
                <label for="edit_supplier_id">Proveedor:</label>
                <select name="supplier_id" id="edit_supplier_id" required>
                    <option value="">-- Seleccionar Proveedor --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <h4>Productos de la Orden</h4>
                <table class="inventory-table" id="edit-po-products-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Costo ($)</th>
                            <th>Cantidad</th>
                            <th>Subtotal ($)</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="edit-po-products-body">
                        <!-- Se llena dinámicamente vía JavaScript -->
                    </tbody>
                </table>
                <button type="button" id="btn-add-edit-po-product" class="btn-save" style="margin-top: 10px;">+ Agregar Producto</button>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 20px; font-weight: bold; margin-bottom: 15px;">
                <div>Subtotal: $<span id="edit-po-subtotal-text">0.00</span></div>
                <div>Total: $<span id="edit-po-total-text">0.00</span></div>
            </div>

            <input type="hidden" name="subtotal" id="edit_po_subtotal_input" value="0">
            <input type="hidden" name="total" id="edit_po_total_input" value="0">

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>