<div id="modal-barcodes" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0;">Códigos de Barras</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="setting-option-row" style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                <div class="setting-info" style="flex: 1;">
                    <h5 style="margin: 0 0 4px 0; font-size: 1rem;">Generar Etiquetas</h5>
                    <p style="margin: 0; font-size: 0.85rem; color: #666;">Genera el PDF con los códigos EAN-13 pertenecientes al ID de lote especificado.</p>
                </div>
                <div class="setting-action" style="width: 260px;">
                    <form id="form-generate-barcodes" action="{{ route('barcodes.generate') }}" method="POST" target="_blank" style="display: flex; flex-direction: column; gap: 8px;">
                        @csrf
                        <select name="product_code" class="product-select" required style="width: 100%; box-sizing: border-box; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; outline: none; background-color: #fff; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            <option value="" disabled selected>Seleccionar Producto</option>
                            @foreach($products as $product)
                                @php
                                    $batchesData = $product->batches->map(function($b) use ($product) {
                                        $path = storage_path("app/private/barcodes/{$product->code}_lote_{$b->id}.pdf");
                                        $arr = $b->toArray();
                                        $arr['has_pdf'] = file_exists($path);
                                        return $arr;
                                    });
                                @endphp
                                <option value="{{ $product->code }}" data-batches="{{ json_encode($batchesData) }}">
                                    {{ $product->code }} - {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="batch_id" class="batch-select" required disabled style="width: 100%; box-sizing: border-box; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; outline: none; background-color: #fff;">
                            <option value="" disabled selected>Seleccionar Lote</option>
                        </select>

                        <button type="submit" class="btn btn-save" style="width: 100%; cursor: pointer; border: none; padding: 8px 12px;">Generar</button>
                    </form>
                </div>
            </div>

            <hr class="setting-divider" style="border: 0; border-top: 1px solid #e5e7eb; margin: 15px 0;">

            <div class="setting-option-row" style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
                <div class="setting-info" style="flex: 1;">
                    <h5 style="margin: 0 0 4px 0; font-size: 1rem;">Buscar Códigos</h5>
                    <p style="margin: 0; font-size: 0.85rem; color: #666;">Verifica si ya existe el PDF generado para este lote en el servidor.</p>
                </div>
                <div class="setting-action" style="width: 260px;">
                    <form id="form-search-barcodes" action="{{ route('barcodes.search') }}" method="GET" target="_blank" style="display: flex; flex-direction: column; gap: 8px;">
                        <select name="product_code" class="product-select" data-filter-pdf="true" required style="width: 100%; box-sizing: border-box; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; outline: none; background-color: #fff; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            <option value="" disabled selected>Seleccionar Producto</option>
                            @foreach($products as $product)
                                @php
                                    $batchesData = $product->batches->map(function($b) use ($product) {
                                        $path = storage_path("app/private/barcodes/{$product->code}_lote_{$b->id}.pdf");
                                        $arr = $b->toArray();
                                        $arr['has_pdf'] = file_exists($path);
                                        return $arr;
                                    });
                                @endphp
                                <option value="{{ $product->code }}" data-batches="{{ json_encode($batchesData) }}">
                                    {{ $product->code }} - {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="batch_id" class="batch-select" required disabled style="width: 100%; box-sizing: border-box; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; outline: none; background-color: #fff;">
                            <option value="" disabled selected>Seleccionar Lote</option>
                        </select>

                        <button type="submit" class="btn btn-save" style="width: 100%; cursor: pointer; border: none; padding: 8px 12px;">Buscar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
            <button type="button" class="btn btn-cancel" data-modal-close>Cerrar</button>
        </div>
    </div>
</div>