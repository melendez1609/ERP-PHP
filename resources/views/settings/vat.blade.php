<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Impuesto (IVA)</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="settings-container">
        <section class="settings-section-top">
            <div class="settings-section-top-tittle">
                <h3>Impuesto (IVA)</h3>
            </div>
        </section>
        <section class="settings-section-table">
            <table class="settings-table">
                <thead>
                    <tr>
                        <th>Impuesto / Descripción</th>
                        <th style="width: 280px; text-align: center;">Tasa (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vats as $vat)
                    <tr>
                        <td>
                            <strong>{{ $vat->name }}</strong>
                            <small style="display: block; color: #666;">Aplicable a productos y ventas del sistema</small>
                        </td>
                        <td>
                            <form id="vat-form-{{ $vat->id }}" action="{{ route('settings.vat.update', $vat->id) }}" method="POST" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="vat_id" value="{{ $vat->id }}">
                                
                                <input type="number" 
                                    name="rate" 
                                    value="{{ $vat->rate }}" 
                                    step="0.01" 
                                    min="0" 
                                    max="100"
                                    style="width: 90px; text-align: center; padding: 6px; border: 1px solid #ccc; border-radius: 4px;" 
                                    required>

                                <button type="button" 
                                        class="settings-table-button edit" 
                                        style="padding: 6px 12px; white-space: nowrap;"
                                        data-modal-target="modal-alert"
                                        data-title="Confirmar Actualización"
                                        data-message="¿Deseas actualizar la tasa de {{ $vat->name }}?"
                                        data-action-form="vat-form-{{ $vat->id }}"
                                        data-btn-text="Actualizar"
                                        data-btn-class="btn-save">
                                    Aplicar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="text-align: center; padding: 15px; color: #666;">
                            No hay impuestos registrados en el sistema.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    @include('partials.alert')
    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>