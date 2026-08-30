<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Contactos</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-contacts-container">
        <section class="contacts-section-top" style="display: flex; align-items: center; gap: 15px;">
            <div class="contacts-section-top-tittle">
                <h3 style="margin: 0;">Contactos</h3>
            </div>
            <div>
                <button class="contacts-create-button" data-modal-target="modal-create">Crear</button>
            </div>
        </section>
        <section class="contacts-section-table">
            <table class="contacts-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Medio de Contacto</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->contact_type ?? 'N/A' }}</td>
                        <td>{{ $contact->phone ?? 'N/A' }}</td>
                        <td>{{ $contact->email ?? 'N/A' }}</td>
                        <td>{{ $contact->address ?? 'N/A' }}</td>
                        <td class="no-wrap">
                            <button class="contacts-table-button edit" 
                                    type="button" 
                                    data-modal-target="modal-edit"
                                    data-id="{{ $contact->id }}"
                                    data-name="{{ $contact->name }}"
                                    data-contact_type="{{ $contact->contact_type }}"
                                    data-phone="{{ $contact->phone }}"
                                    data-email="{{ $contact->email }}"
                                    data-address="{{ $contact->address }}">
                                Editar
                            </button>
                            <button class="contacts-table-button delete" 
                                    type="button"
                                    data-modal-target="modal-alert"
                                    data-action="{{ route('contacts.destroy', $contact->id) }}"
                                    data-method="DELETE"
                                    data-title="Eliminar Contacto"
                                    data-message="¿Estás seguro de que deseas eliminar el contacto '{{ $contact->name }}'?"
                                    data-btn-text="Eliminar"
                                    data-btn-class="btn-danger">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #666; background-color: #fff;">
                            No hay contactos registrados en el sistema.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
        <div class="pagination-container">
            {{ $contacts->links('partials.pagination') }}
        </div>
    </main>

    @include('contacts.partials.modal-create')
    @include('contacts.partials.modal-edit')
    @include('partials.alert')

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>