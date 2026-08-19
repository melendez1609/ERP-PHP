<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Inventario</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-inventory-container">
        <section class="inventory-section-top">
            <div class="inventory-section-top-tittle">
                <h3>Inventario</h3>
            </div>
            <div>
                <button class="inventory-create-button" data-modal-target="modal-create">Crear</button>
            </div>
        </section>
        <section class="inventory-section-table">
            <table class="inventory-table">
                <tr>
                    <th>Código</th>
                    <th>Proveedor</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Costo</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Stock Mín.</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>
                        <span>{{ $product->supplier?->name ?? 'Sin proveedor' }}</span>
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>${{ number_format($product->cost, 2) }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->min_stock }}</td>
                    <td>
                        <span>{{ $product->status?->name ?? 'Sin estado' }}</span>
                    </td>
                    <td>
                        <button class="inventory-table-button edit" 
                                type="button" 
                                data-modal-target="modal-edit"
                                data-id="{{ $product->id }}"
                                data-code="{{ $product->code }}"
                                data-name="{{ $product->name }}"
                                data-description="{{ $product->description }}"
                                data-cost="{{ $product->cost }}"
                                data-price="{{ $product->price }}"
                                data-stock="{{ $product->stock }}"
                                data-min-stock="{{ $product->min_stock }}"
                                data-supplier-id="{{ $product->supplier_id }}"
                                data-status-id="{{ $product->product_status_id }}">
                            Editar
                        </button>
<button class="inventory-table-button delete" 
        type="button"
        data-modal-target="modal-alert"
        data-action="{{ route('inventory.destroy', $product->id) }}"
        data-method="DELETE"
        data-title="Eliminar Producto"
        data-message="¿Estás seguro de que deseas eliminar el producto '{{ $product->name }}'?"
        data-btn-text="Eliminar"
        data-btn-class="btn-danger">
    Eliminar
</button>
                        <button class="inventory-table-button disable {{ $product->product_status_id != 1 ? 'enable' : '' }}" 
                                type="button"
                                data-modal-target="modal-alert"
                                data-action="{{ route('inventory.disable', $product->id) }}"
                                data-method="PATCH"
                                data-title="{{ $product->product_status_id == 1 ? 'Inactivar Producto' : 'Activar Producto' }}"
                                data-message="¿Estás seguro de que deseas {{ $product->product_status_id == 1 ? 'inactivar' : 'activar' }} el producto '{{ $product->name }}'?"
                                data-btn-text="{{ $product->product_status_id == 1 ? 'Inactivar' : 'Activar' }}"
                                data-btn-class="btn-save">
                            {{ $product->product_status_id == 1 ? 'Inactivar' : 'Activar' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </table>
        </section>
            <div class="pagination-container">
                {{ $products->links('partials.pagination') }}
            </div>
    </main>

    @include('inventory.partials.modal-create')
    @include('inventory.partials.modal-edit')
    @include('partials.alert') {{-- <-- Modal reutilizable de alertas --}}

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>