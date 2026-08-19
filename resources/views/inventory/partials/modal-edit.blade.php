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

            <div class="form-group">
                <label for="edit-cost">Costo</label>
                <input type="number" step="0.01" id="edit-cost" name="cost" required>
            </div>

            <div class="form-group">
                <label for="edit-price">Precio</label>
                <input type="number" step="0.01" id="edit-price" name="price" required>
            </div>

            <div class="form-group">
                <label for="edit-stock">Stock</label>
                <input type="number" id="edit-stock" name="stock" required>
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