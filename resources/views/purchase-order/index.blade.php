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
                        <th>Orden de Compra</th>
                        <th>Proveedor</th>
                        <th>Creado por</th>
                        <th>Total</th>
                        <th>Creado</th>
                        <th>Actualizado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $order)
                    @php
                        $orderCode = $order->order_number ?? '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                        $status = strtolower($order->status ?? 'pendiente');
                        $isCancelled = in_array($status, ['cancelado', 'cancelada']);
                        $isPending = ($status === 'pendiente');
                    @endphp
                    <tr>
                        <td>{{ $orderCode }}</td>
                        <td>{{ $order->supplier?->name ?? 'Sin proveedor' }}</td>
                        <td>{{ $order->user?->name ?? 'Sistema' }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <span>{{ ucfirst($order->status ?? 'Pendiente') }}</span>
                        </td>
                        <td>
                            <select class="table-action-select po-action-select" 
                                    data-order-id="{{ $order->id }}"
                                    data-order-number="{{ $orderCode }}">
                                <option value="">Opciones</option>

                                {{-- Acciones para órdenes NO canceladas --}}
                                @if(!$isCancelled)
                                    {{-- Opción PDF --}}
                                    <option value="pdf" data-pdf-url="{{ route('purchase-orders.pdf', $order->id) }}">
                                        PDF
                                    </option>
                                    
                                    {{-- Opciones exclusivas para estado PENDIENTE --}}
                                    @if($isPending)
                                        {{-- Ruta Recibir --}}
                                        <option value="receive"
                                                data-modal-target="modal-alert"
                                                data-action="{{ route('purchase-orders.receive', $order->id) }}"
                                                data-method="PATCH"
                                                data-title="Recibir Orden de Compra"
                                                data-message="¿Deseas marcar la orden {{ $orderCode }} como RECIBIDA? Esto registrará los lotes, el stock y los SKUs en el inventario."
                                                data-btn-text="Recibir"
                                                data-btn-class="btn-save">
                                            Recibir
                                        </option>

                                        {{-- Ruta Editar (CORREGIDA CON COMILLAS SIMPLES Y @json) --}}
                                        <option value="edit"
                                                data-modal-target="modal-edit-purchase-order"
                                                data-url="{{ route('purchase-orders.update', $order->id) }}"
                                                data-supplier-id="{{ $order->supplier_id }}"
                                                data-products='@json($order->products)'>
                                            Editar
                                        </option>

                                        {{-- Ruta Cancelar --}}
                                        <option value="cancel"
                                                data-modal-target="modal-alert"
                                                data-action="{{ route('purchase-orders.cancel', $order->id) }}"
                                                data-method="PATCH"
                                                data-title="Cancelar Orden de Compra"
                                                data-message="¿Estás seguro de que deseas CANCELAR la orden {{ $orderCode }}?"
                                                data-btn-text="Cancelar Orden"
                                                data-btn-class="btn-danger">
                                            Cancelar
                                        </option>
                                    @endif
                                @endif

                                {{-- Ruta Eliminar (Siempre disponible) --}}
                                <option value="delete"
                                        data-modal-target="modal-alert"
                                        data-action="{{ route('purchase-orders.destroy', $order->id) }}"
                                        data-method="DELETE"
                                        data-title="Eliminar Orden de Compra"
                                        data-message="¿Estás seguro de que deseas ELIMINAR la orden {{ $orderCode }}? Esta acción borrará el registro y el archivo PDF asociado."
                                        data-btn-text="Eliminar"
                                        data-btn-class="btn-danger">
                                    Eliminar
                                </option>
                            </select>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #666; background-color: #fff;">
                            No hay órdenes de compra registradas en el sistema.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
        <div class="pagination-container">
            {{ $purchaseOrders->links('partials.pagination') }}
        </div>
    </main>

    @include('purchase-order.partials.purchase-order-edit')
    @include('partials.alert')
    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>