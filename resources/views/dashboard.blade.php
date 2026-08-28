<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Panel</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-menu-container">
        <div class="erp-menu">
            <a href="javascript:void(0)" class="option" data-modal-target="modal-options">
                <img class="icon" src="{{ asset('icons/estimates.png') }}" alt="estimates">
                <h4 class="icon-name">Cotización</h4>
            </a>
            <div class="option">
                <img class="icon" src="{{ asset('icons/invoice.png') }}" alt="invoice">
                <h4 class="icon-name">Factura</h4>
            </div>
            <a class="option" href="{{ route('cash-register.index') }}">
                <img class="icon" src="{{ asset('icons/cash-register.png') }}" alt="cash-register">
                <h4 class="icon-name">Caja Registradora</h4>
            </a>
            <a class="option" href="{{ route('inventory.index') }}">
                <img class="icon" src="{{ asset('icons/in-inventory.png') }}" alt="in-inventory">
                <h4 class="icon-name">Inventario</h4>
            </a>
            <div class="option">
                <img class="icon" src="{{ asset('icons/sales-report.png') }}" alt="sales-report">
                <h4 class="icon-name">Reporte de Ventas</h4>
            </div>
            <a href="javascript:void(0)" class="option" data-modal-target="modal-schedule">
                <img class="icon" src="{{ asset('icons/schedule.png') }}" alt="schedule">
                <h4 class="icon-name">Agenda</h4>
            </a>
            <div class="option">
                <img class="icon" src="{{ asset('icons/support.png') }}" alt="support">
                <h4 class="icon-name">Soporte</h4>
            </div>
            @if (auth()->user()?->role_id === 1)
                <a href="{{ route('users.index') }}" class="option">
                    <img class="icon" src="{{ asset('icons/group.png') }}" alt="group">
                    <h4 class="icon-name">Usuarios</h4>
                </a>
                <a href="javascript:void(0)" class="option" data-modal-target="modal-purchase-options">
                    <img class="icon" src="{{ asset('icons/purchase-order.png') }}" alt="purchase-order">
                    <h4 class="icon-name">Orden de Compra</h4>
                </a>
                <div class="option">
                    <img class="icon" src="{{ asset('icons/report-file.png') }}" alt="report-file">
                    <h4 class="icon-name">Reporte del Sistema</h4>
                </div>
                <a href="javascript:void(0);" class="option" data-modal-target="modal-settings">
                    <img class="icon" src="{{ asset('icons/settings.png') }}" alt="settings">
                    <h4 class="icon-name">Configuración</h4>
                </a>
                <div class="option">
                    <img class="icon" src="{{ asset('icons/share.png') }}" alt="share">
                    <h4 class="icon-name">Compartir</h4>
                </div>
                <a href="{{ route('suppliers.index') }}" class="option">
                    <img class="icon" src="{{ asset('icons/supplier.png') }}" alt="supplier">
                    <h4 class="icon-name">Proveedor</h4>
                </a>
                <div class="option">
                    <img class="icon" src="{{ asset('icons/email.png') }}" alt="email">
                    <h4 class="icon-name">Correo</h4>
                </div>
                <a href="#" class="option" data-modal-target="modal-barcodes">
                    <img class="icon" src="{{ asset('icons/barcode.png') }}" alt="barcode">
                    <h4 class="icon-name">Códigos de Barras</h4>
                </a>
            @endif
        </div>
    </main>

    @include('partials.footer')
    @include('partials.alert')
    @include('quotations.partials.modal-options')
    @include('quotations.partials.modal-quotations')
    @include('purchase-order.partials.modal-options')
    @include('purchase-order.partials.modal-purchase-order')
    @include('settings.partials.modal-options')
    @include('barcodes.partials.modal-options')
    @include('schedule.partials.modal-schedule')
    @include('schedule.partials.modal-create') 
    @include('schedule.partials.modal-edit')  
    @include('schedule.partials.modal-content')

    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>

