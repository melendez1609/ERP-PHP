<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Cotizaciones</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-quotations-container">
        <section class="quotations-section-top">
            <div class="quotations-section-top-tittle">
                <h3>Cotizaciones</h3>
            </div>
        </section>
        <section class="quotations-section-table">
            <table class="quotations-table">
                <thead>
                    <tr>
                        <th># Cotización</th>
                        <th>Cliente</th>
                        <th>Atendido por</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotations as $quotation)
                    <tr>
                        <td>COT-{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $quotation->customer_name }}</td>
                        <td>{{ $quotation->user?->name ?? 'N/A' }}</td>
                        <td>${{ number_format($quotation->total, 2) }}</td>
                        <td>{{ $quotation->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('quotations.download', $quotation->id) }}" 
                               target="_blank" 
                               class="quotations-table-button download" 
                               style="text-decoration: none; display: inline-block;">
                                PDF
                            </a>
                            <button class="quotations-table-button delete" 
                                    type="button"
                                    data-modal-target="modal-alert"
                                    data-action="{{ route('quotations.destroy', $quotation->id) }}"
                                    data-method="DELETE"
                                    data-title="Eliminar Cotización"
                                    data-message="¿Estás seguro de que deseas eliminar la cotización 'COT-{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}'?"
                                    data-btn-text="Eliminar"
                                    data-btn-class="btn-danger">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #666; background-color: #fff;">
                            No hay cotizaciones registradas en el sistema.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
        <div class="pagination-container">
            {{ $quotations->links('partials.pagination') }}
        </div>
    </main>

    @include('quotations.partials.modal-quotations')
    @include('partials.alert')

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>