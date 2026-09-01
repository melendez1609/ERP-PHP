<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>G-ERP | Caja Registradora</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')

    @php
        $currentBalance = 0;
        if (isset($activeSession) && $activeSession) {
            $inMovements = $activeSession->movements()->where('type', 'in')->sum('amount');
            $outMovements = $activeSession->movements()->where('type', 'out')->sum('amount');
            $salesTotal = \App\Models\Sale::where('cash_register_session_id', $activeSession->id)->sum('total');
            $currentBalance = $activeSession->opening_amount + $inMovements - $outMovements + $salesTotal;
        }
    @endphp

    <main class="erp-cash-register-container" data-has-session="{{ isset($activeSession) && $activeSession ? 'true' : 'false' }}">
        <section class="pos-left-panel">
            <div class="pos-cart-section">
                <div class="pos-ticket-header">
                    <span>Cant</span>
                    <span style="text-align: left;">Producto</span>
                    <span>Precio</span>
                    <span>Total</span>
                </div>
                <div class="pos-ticket-items" id="pos-ticket-items"></div>
                <div class="pos-ticket-footer">
                    <h4 class="pos-total-text">Total: <span class="total-amount" id="pos-total-amount">$0.00</span></h4>
                </div>
            </div>

            <div class="pos-keypad-section">
                <div class="pos-keypad-grid">
                    <button class="key-btn num-key" data-key="7">7</button>
                    <button class="key-btn num-key" data-key="8">8</button>
                    <button class="key-btn num-key" data-key="9">9</button>
                    <button class="key-btn action-key clear-key" id="btn-clear">C</button>
                    
                    <button class="key-btn num-key" data-key="4">4</button>
                    <button class="key-btn num-key" data-key="5">5</button>
                    <button class="key-btn num-key" data-key="6">6</button>
                    <button class="key-btn action-key back-key" id="btn-backspace">⌫</button>
                    
                    <button class="key-btn num-key" data-key="1">1</button>
                    <button class="key-btn num-key" data-key="2">2</button>
                    <button class="key-btn num-key" data-key="3">3</button>
                    <button class="key-btn print-key" id="btn-print-last">IMP</button>
                    
                    <button class="key-btn num-key zero-btn" data-key="0">0</button>
                    <button class="key-btn num-key" data-key=".">.</button>
                    <button class="key-btn pay-key" id="btn-pay">Pagar ↵</button>
                </div>
            </div>

            <div class="pos-balance-card" style="margin-top: 10px; padding: 8px 12px; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; font-weight: 600; color: #334155;">Efectivo Actual en Caja:</span>
                <span id="pos-current-balance" style="font-size: 1rem; font-weight: bold; color: #15803d;">${{ number_format($currentBalance, 2) }}</span>
            </div>

            <div style="margin-top: 8px;">
                <button type="button" class="btn btn-save" data-modal-target="modal-pos-options" style="width: 100%; padding: 10px; background-color: #132873; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                    Opciones de Caja (Cierre / Movimientos)
                </button>
            </div>
        </section>

        <section class="pos-right-panel">
            <div class="pos-finder-section">
                <input type="text" id="pos-search-input" class="pos-search-input" placeholder="Buscar por código o nombre (Presiona Enter para agregar)..." autofocus>
            </div>

            <div class="pos-catalog-section" id="pos-catalog-section">
                @forelse($products as $product)
                @php
                    $isAgotado = $product->stock <= 0;
                @endphp
                <div class="pos-product-card" 
                    data-id="{{ $product->id }}" 
                    data-code="{{ $product->code }}"
                    data-name="{{ $product->name }}" 
                    data-price="{{ $product->price }}"
                    data-stock="{{ $product->stock }}"
                    style="{{ $isAgotado ? 'opacity: 0.4; pointer-events: none; position: relative;' : 'position: relative;' }}">
                    
                    @if($isAgotado)
                        <span class="out-of-stock-badge" style="position:absolute; background:#dc2626; color:white; font-size:10px; padding:2px 6px; top:5px; right:5px; border-radius:4px; font-weight:bold; z-index:10;">AGOTADO</span>
                    @endif

                    @if($product->image && Storage::disk('public')->exists($product->image))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    @else
                        <img src="{{ asset('icons/picture.png') }}" alt="Sin imagen" style="width: 40px; height: 40px; object-fit: contain;">
                    @endif

                    <span class="product-name">{{ $product->name }}</span>
                    <span class="product-price">${{ number_format($product->price, 2) }}</span>
                </div>
                @empty
                <p style="grid-column: 1 / -1; text-align: center; color: #64748b;">No hay productos disponibles.</p>
                @endforelse
            </div>
        </section>

        @include('cash-register.partials.preview-invoice')
        @include('cash-register.partials.pos-options')
        @include('cash-register.partials.payment')
    </main>

    <iframe id="ticket-print-frame" style="display: none;"></iframe>

    @include('partials.footer')
    @include('cash-register.partials.alert')
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>
</html>