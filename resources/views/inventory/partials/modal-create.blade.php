<div id="modal-create" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Nuevo Producto</h3>

        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf

            <div>
                <label for="code">Código:</label>
                <input type="text" name="code" id="code" required>
            </div>

            <div>
                <label for="supplier_id">Proveedor:</label>
                <!-- Buscador rápido de proveedor -->
                <input type="text" id="supplier-search" placeholder="Escribe para buscar proveedor..." style="width: 100%; margin-top: 5px; margin-bottom: 8px; padding: 8px 12px; box-sizing: border-box;">
                
                <select name="supplier_id" id="supplier_id">
                    <option value="">-- Seleccionar Proveedor --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div>
                <label for="description">Descripción:</label>
                <textarea name="description" id="description"></textarea>
            </div>

            <div>
                <label for="cost">Costo ($):</label>
                <input type="number" step="0.01" name="cost" id="cost" min="0" required>
            </div>

            <div>
                <label for="price">Precio ($):</label>
                <input type="number" step="0.01" name="price" id="price" min="0" required>
            </div>

            <div>
                <label for="stock">Stock Inicial:</label>
                <input type="number" name="stock" id="stock" min="0" required>
            </div>

            <div>
                <label for="min_stock">Stock Mínimo:</label>
                <input type="number" name="min_stock" id="min_stock" min="0" required>
            </div>

            <div>
                <label for="product_status_id">Estado:</label>
                <select name="product_status_id" id="product_status_id" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>