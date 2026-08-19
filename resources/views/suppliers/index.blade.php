<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Proveedores</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-suppliers-container">
        <section class="suppliers-section-top">
            <div class="suppliers-section-top-tittle">
                <h3>Proveedores</h3>
            </div>
            <div>
                <button class="suppliers-create-button" data-modal-target="modal-create">Crear</button>
            </div>
        </section>
        <section class="suppliers-section-table">
            <table class="suppliers-table">
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
                @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_name }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>
                        <button class="suppliers-table-button edit" 
                                type="button" 
                                data-modal-target="modal-edit"
                                data-id="{{ $supplier->id }}"
                                data-name="{{ $supplier->name }}"
                                data-contact_name="{{ $supplier->contact_name }}"
                                data-phone="{{ $supplier->phone }}"
                                data-email="{{ $supplier->email }}"
                                data-address="{{ $supplier->address }}">
                            Editar
                        </button>
                        <button class="suppliers-table-button delete" 
                                type="button"
                                data-modal-target="modal-alert"
                                data-action="{{ route('suppliers.destroy', $supplier->id) }}"
                                data-method="DELETE"
                                data-title="Eliminar Proveedor"
                                data-message="¿Estás seguro de que deseas eliminar al proveedor '{{ $supplier->name }}'?"
                                data-btn-text="Eliminar"
                                data-btn-class="btn-danger">
                            Eliminar
                        </button>
                    </td>
                </tr>
                @endforeach
            </table>
        </section>
        <div class="pagination-container">
            {{ $suppliers->links('partials.pagination') }}
        </div>
    </main>

    @include('suppliers.partials.modal-create')
    @include('suppliers.partials.modal-edit')
    @include('partials.alert')

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>