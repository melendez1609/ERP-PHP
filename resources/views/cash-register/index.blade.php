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

    <main class="erp-cash-register-container">
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
                    <button class="key-btn print-key" id="btn-print-last">🖨️</button>
                    
                    <button class="key-btn num-key zero-btn" data-key="0">0</button>
                    <button class="key-btn num-key" data-key=".">.</button>
                    <button class="key-btn pay-key" id="btn-pay">Pagar ↵</button>
                </div>
            </div>
        </section>

        <section class="pos-right-panel">
            <div class="pos-finder-section">
                <input type="text" id="pos-search-input" class="pos-search-input" placeholder="Buscar por código o nombre (Presiona Enter para agregar)..." autofocus>
            </div>

            <div class="pos-catalog-section" id="pos-catalog-section">
                @forelse($products as $product)
                <div class="pos-product-card" 
                     data-id="{{ $product->id }}" 
                     data-code="{{ $product->code }}"
                     data-name="{{ $product->name }}" 
                     data-price="{{ $product->price }}"
                     data-stock="{{ $product->stock }}">
                    
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
    </main>

    <iframe id="ticket-print-frame" style="display: none;"></iframe>

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>
</html>