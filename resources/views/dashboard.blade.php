<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Panel</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
</head>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-menu-container">
        <div class="erp-menu">
            <div class="option">
                <img class="icon" src="{{ asset('icons/estimates.png') }}" alt="estimates">
                <h4 class="icon-name">Cotización</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/group.png') }}" alt="group">
                <h4 class="icon-name">Usuarios</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/in-inventory.png') }}" alt="in-inventory">
                <h4 class="icon-name">Inventario</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/invoice.png') }}" alt="invoice">
                <h4 class="icon-name">Factura</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/purchase-order.png') }}" alt="purchase-order">
                <h4 class="icon-name">Orden de Compra</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/report-file.png') }}" alt="report-file">
                <h4 class="icon-name">Reporte del Sistema</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/sales-report.png') }}" alt="sales-report">
                <h4 class="icon-name">Reporte de Ventas</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/settings.png') }}" alt="settings">
                <h4 class="icon-name">Configuracion</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/share.png') }}" alt="share">
                <h4 class="icon-name">Compartir</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/supplier.png') }}" alt="supplier">
                <h4 class="icon-name">Proveedor</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/support.png') }}" alt="support">
                <h4 class="icon-name">Soporte</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/cash-register.png') }}" alt="cash-register">
                <h4 class="icon-name">Caja Registradora</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/email.png') }}" alt="email">
                <h4 class="icon-name">Correo</h4>
            </div>
            <div class="option">
                <img class="icon" src="{{ asset('icons/clock.png') }}" alt="clock">
                <h4 class="icon-name">Reloj</h4>
            </div>
        </div>
    </main>
    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>