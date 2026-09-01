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
            <a href="javascript:void(0)" class="option" data-modal-target="modal-search-invoice">
                <img class="icon" src="{{ asset('icons/invoice.png') }}" alt="invoice">
                <h4 class="icon-name">Facturas</h4>
            </a>
            @if(isset($activeSession) && $activeSession)
                <a class="option" href="{{ route('cash-register.index') }}">
                    <img class="icon" src="{{ asset('icons/cash-register.png') }}" alt="cash-register">
                    <h4 class="icon-name">Caja Registradora</h4>
                </a>
            @else
                <a href="javascript:void(0)" class="option" data-modal-target="modal-cash-opening">
                    <img class="icon" src="{{ asset('icons/cash-register.png') }}" alt="cash-register">
                    <h4 class="icon-name">Caja Registradora</h4>
                </a>
            @endif
            <a class="option" href="{{ route('inventory.index') }}">
                <img class="icon" src="{{ asset('icons/in-inventory.png') }}" alt="in-inventory">
                <h4 class="icon-name">Inventario</h4>
            </a>
            <a href="{{ route('sales.reports') }}" class="option">
                <img class="icon" src="{{ asset('icons/sales-report.png') }}" alt="sales-report">
                <h4 class="icon-name">Reporte de Ventas</h4>
            </a>
            <a href="javascript:void(0)" class="option" data-modal-target="modal-schedule">
                <img class="icon" src="{{ asset('icons/schedule.png') }}" alt="schedule">
                <h4 class="icon-name">Agenda</h4>
            </a>
            <a class="option" href="{{ route('lockscreen.lock') }}">
                <img class="icon" src="{{ asset('icons/lock.png') }}" alt="lock">
                <h4 class="icon-name">Bloquear Sesión</h4>
            </a>
            <a class="option" data-modal-target="modal-password" style="cursor: pointer;">
                <img class="icon" src="{{ asset('icons/password.png') }}" alt="password">
                <h4 class="icon-name">Cambiar Contraseña</h4>
            </a>
            @if (auth()->user()?->role_id === 1)
                <a href="{{ route('users.index') }}" class="option">
                    <img class="icon" src="{{ asset('icons/group.png') }}" alt="group">
                    <h4 class="icon-name">Usuarios</h4>
                </a>
                <a href="javascript:void(0)" class="option" data-modal-target="modal-purchase-options">
                    <img class="icon" src="{{ asset('icons/purchase-order.png') }}" alt="purchase-order">
                    <h4 class="icon-name">Orden de Compra</h4>
                </a>
                <a href="{{ route('reports.index') }}" class="option">
                    <img class="icon" src="{{ asset('icons/report-file.png') }}" alt="report-file">
                    <h4 class="icon-name">Reporte del Sistema</h4>
                </a>
                <a href="javascript:void(0);" class="option" data-modal-target="modal-settings">
                    <img class="icon" src="{{ asset('icons/settings.png') }}" alt="settings">
                    <h4 class="icon-name">Configuración</h4>
                </a>
                <a class="option" href="{{ route('contacts.index') }}">
                    <img class="icon" src="{{ asset('icons/contacts.png') }}" alt="contacts">
                    <h4 class="icon-name">Contactos</h4>
                </a>
                <a href="{{ route('suppliers.index') }}" class="option">
                    <img class="icon" src="{{ asset('icons/supplier.png') }}" alt="supplier">
                    <h4 class="icon-name">Proveedor</h4>
                </a>
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
    @include('users.partials.modal-password')
    @include('settings.partials.modal-volume')
    @include('cash-register.partials.authorization')
    @include('invoice.partials.modal-search')
    @include('invoice.partials.preview-invoice')

    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

</html>