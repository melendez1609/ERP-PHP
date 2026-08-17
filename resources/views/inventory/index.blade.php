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
                <button class="inventory-create-button">Crear</button>
            </div>
        </section>
        <section class="inventory-section-table">
            <table class="inventory-table">
                <tr>
                    <th>Código</th>
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
                        <button class="inventory-table-button edit" type="button">Editar</button>
                        <button class="inventory-table-button delete" type="button">Eliminar</button>
                        <button class="inventory-table-button disable" type="button">Inactivar</button>
                    </td>
                </tr>
                @endforeach
            </table>
        </section>
    </main>

    @include('inventory.partials.modal-create')
    @include('inventory.partials.modal-edit')

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>