<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Órdenes de Compra</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-purchase-orders-container">
        <section class="purchase-orders-section-top">
            <div class="purchase-orders-section-top-tittle">
                <h3>Órdenes de Compra</h3>
            </div>
        </section>
        <section class="purchase-orders-section-table">
            <table class="purchase-orders-table">
                <thead>
                    <tr>
                        <th>N° Orden</th>
                        <th>Proveedor</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrders as $order)
                    <tr>
                        <td>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->supplier?->name ?? 'Sin proveedor' }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span>{{ $order->status?->name ?? 'Completada' }}</span>
                        </td>
                        <td>
                            {{-- {{ route('purchase-orders.pdf', $order->id) }} --}}
                            <button class="purchase-orders-table-button download" 
                                    type="button">
                                PDF
                            </button>

                            {{-- {{ route('purchase-orders.destroy', $order->id) }} --}}
                            <button class="purchase-orders-table-button delete" 
                                    type="button"
                                    data-modal-target="modal-alert"
                                    data-action="#"
                                    data-method="DELETE"
                                    data-title="Eliminar Órden"
                                    data-message="¿Estás seguro de que deseas eliminar la orden #{{ $order->id }}?"
                                    data-btn-text="Eliminar"
                                    data-btn-class="btn-danger">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        <div class="pagination-container">
            {{ $purchaseOrders->links('partials.pagination') }}
        </div>
    </main>

    @include('partials.alert')
    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>