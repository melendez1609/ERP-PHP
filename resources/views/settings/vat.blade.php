<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>G-ERP | Impuestos y Tasas</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>

    <main class="settings-container">
        <section class="settings-section-top" style="display: flex; align-items: center; gap: 15px;">
            <div class="settings-section-top-tittle">
                <h3 style="margin: 0;">Impuestos y Tasas</h3>
            </div>
            <div>
                <button class="settings-create-button" data-modal-target="modal-create-vat">Crear</button>
            </div>
        </section>

        <section class="settings-section-table">
            <table class="settings-table">
                <thead>
                    <tr>
                        <th>Impuesto / Descripción</th>
                        <th style="width: 180px; text-align: center;">Tasa (%)</th>
                        <th style="width: 140px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vats as $vat)
                    <tr>
                        <td>
                            <strong>{{ $vat->name }}</strong>
                            <small style="display: block; color: #666;">Aplicable a productos y ventas del sistema</small>
                        </td>
                        <td style="text-align: center; font-weight: 600;">
                            {{ number_format($vat->rate, 2) }}%
                        </td>
                        <td style="text-align: center;">
                            <button class="settings-table-button delete" 
                                    type="button"
                                    data-modal-target="modal-alert"
                                    data-action="{{ route('settings.vat.destroy', $vat->id) }}"
                                    data-method="DELETE"
                                    data-title="Eliminar Impuesto"
                                    data-message="¿Estás seguro de que deseas eliminar el impuesto '{{ $vat->name }}'?"
                                    data-btn-text="Eliminar"
                                    data-btn-class="btn-danger">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px; color: #666;">
                            No hay impuestos registrados en el sistema.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    @include('settings.partials.modal-add-vat')
    @include('partials.alert')
    @include('partials.footer')

    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>