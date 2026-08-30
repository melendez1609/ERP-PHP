<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Caja Registradora</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>

<main class="erp-cash-register-container">
    <!-- PANEL IZQUIERDO -->
    <div class="pos-left-panel">
        
        <!-- 1.1 Sección del Carrito / Ticket -->
        <div class="pos-cart-section">
            <div class="pos-ticket-header">
                <span>Cant.</span>
                <span>Producto</span>
                <span>Precio</span>
                <span>Total</span>
            </div>
            <div class="pos-ticket-items">
                <!-- Ejemplo de ítem agregado -->
                <div class="ticket-item">
                    <span class="col-qty">1.00</span>
                    <span class="col-name">Coca Cola 600ml</span>
                    <span class="col-price">$1.25</span>
                    <span class="col-total">$1.25</span>
                </div>
                <div class="ticket-item">
                    <span class="col-qty">2.00</span>
                    <span class="col-name">Agua Purificada</span>
                    <span class="col-price">$0.80</span>
                    <span class="col-total">$1.60</span>
                </div>
            </div>
            <div class="pos-ticket-footer">
                <h3 class="pos-total-text">Total: <span class="total-amount">$2.85</span></h3>
            </div>
        </div>

        <!-- 1.2 Sección del Teclado Numérico y Acciones -->
        <div class="pos-keypad-section">
            <div class="pos-keypad-grid">
                <button class="key-btn">7</button>
                <button class="key-btn">8</button>
                <button class="key-btn">9</button>
                <button class="key-btn action-key">Del</button>
                
                <button class="key-btn">4</button>
                <button class="key-btn">5</button>
                <button class="key-btn">6</button>
                <button class="key-btn action-key">Clr</button>
                
                <button class="key-btn">1</button>
                <button class="key-btn">2</button>
                <button class="key-btn">3</button>
                <button class="key-btn action-key pay-key">Pay</button>
                
                <button class="key-btn zero-btn">0</button>
                <button class="key-btn">.</button>
                <button class="key-btn action-key print-key">Print</button>
            </div>
        </div>

    </div>

    <!-- PANEL DERECHO -->
    <div class="pos-right-panel">
        
        <!-- 2.1 Buscador (Finder) superior -->
        <div class="pos-finder-section">
            <input type="text" class="pos-search-input" placeholder="🔍 Buscar producto por nombre o escanear SKU...">
        </div>

        <!-- 2.2 Catálogo de Productos (Grid) -->
        <div class="pos-catalog-section">
            <div class="pos-product-card">
                <div class="product-icon">🥤</div>
                <span class="product-name">Coca Cola 600ml</span>
                <span class="product-price">$1.25</span>
            </div>
            <div class="pos-product-card">
                <div class="product-icon">💧</div>
                <span class="product-name">Agua Purificada 1L</span>
                <span class="product-price">$0.80</span>
            </div>
            <div class="pos-product-card">
                <div class="product-icon">💻</div>
                <span class="product-name">Laptop HP ProBook</span>
                <span class="product-price">$650.00</span>
            </div>
            <!-- Puedes duplicar tarjetas para simular el catálogo -->
        </div>

    </div>
</main>


    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>