<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Orden de Compra #{{ $purchaseOrder->order_number ?? $purchaseOrder->id }}</title>
  <style>
    @page {
      margin: 2cm 2cm 3cm 2cm;
    }

    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      font-size: 13px;
      color: #333;
      line-height: 1.5;
      margin: 0;
      padding: 0;
    }

    .layout-table {
      width: 100%;
      border-collapse: collapse;
      border: none !important;
      margin-bottom: 1cm;
    }

    .layout-table td {
      border: none !important;
      padding: 0 !important;
      vertical-align: top; 
    }

    .header-left {
      width: 55%;
      text-align: left;
    }

    .invoice-meta {
      width: 45%;
      text-align: right;
    }

    .logo {
      height: 120px;
      display: block;
      margin-bottom: 8px;
    }

    .company-info {
      line-height: 1.4;
      font-size: 12px;
    }

    .invoice-title {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 8px;
      line-height: 1;
      color: #000000
    }

    .recipient {
      margin-bottom: 1cm;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      border-bottom: 1px solid #ddd;
      margin-bottom: 1.5cm;
    }

    .items-table th,
    .items-table td {
      border-top: 1px solid #ddd;
      padding: 6px 4px;
      text-align: left;
      vertical-align: top;
    }

    .items-table th {
      background: #f5f5f5;
      font-weight: 600;
    }

    .totals {
      width: 40%;
      float: right;
      margin-bottom: 0.5cm;
    }

    .totals table {
      width: 100%;
      border-collapse: collapse;
    }

    .totals td {
      padding: 4px 0;
      border: none;
    }

    .notes {
      clear: both;
      margin-bottom: 1.5cm;
      text-align: center;
      font-style: italic;
      color: #666;
    }

    .business-name {
      display: inline-block;
      margin-bottom: 4px;
    }
  </style>
</head>
<body>

  <table class="layout-table">
    <tr>
      <td class="header-left">
        @if(!empty($logoBase64))
          <img class="logo" src="{{ $logoBase64 }}" alt="Logo">
        @endif
        <div class="company-info">
          <strong>G-ERP: </strong>Sistema de Gestión de Negocios y Punto de Venta
        </div>
      </td>
      <td class="invoice-meta">
        <div class="invoice-title">Orden de Compra</div>
        <div><strong>Orden No.:</strong> {{ $purchaseOrder->order_number ?? 'OC-' . str_pad($purchaseOrder->id, 5, '0', STR_PAD_LEFT) }}</div>
        <div><strong>Fecha:</strong> {{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('Y-m-d') : date('Y-m-d') }}</div>
        <div><strong>Estado:</strong> {{ ucfirst($purchaseOrder->status ?? 'Pendiente') }}</div>
      </td>
    </tr>
  </table>

  <div class="recipient">
    <strong>Proveedor:</strong><br>
    <strong>{{ $purchaseOrder->supplier?->name ?? 'N/A' }}</strong><br>
    @if($purchaseOrder->supplier?->email)
      Correo: {{ $purchaseOrder->supplier->email }}<br>
    @endif
    @if($purchaseOrder->supplier?->phone)
      Teléfono: {{ $purchaseOrder->supplier->phone }}
    @endif
  </div>

  <table class="items-table">
    <thead>
      <tr>
        <th style="width:5%">#</th>
        <th>Descripción del Producto</th>
        <th style="width:12%; text-align: right;">Cantidad</th>
        <th style="width:18%; text-align: right;">Costo Unit.</th>
        <th style="width:18%; text-align: right;">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @php
        $products = is_array($purchaseOrder->products) 
          ? $purchaseOrder->products 
          : json_decode($purchaseOrder->products ?? '[]', true);
      @endphp

      @forelse($products as $index => $item)
        @php
          $cost = $item['cost'] ?? $item['price'] ?? 0;
          $quantity = $item['quantity'] ?? 1;
          $itemSubtotal = $cost * $quantity;
        @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $item['name'] ?? 'Producto #' . ($index + 1) }}</td>
          <td style="text-align: right;">{{ $quantity }}</td>
          <td style="text-align: right;">${{ number_format($cost, 2) }}</td>
          <td style="text-align: right;">${{ number_format($itemSubtotal, 2) }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align: center;">No hay productos registrados en esta orden.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="totals">
    <table>
      @if(isset($purchaseOrder->subtotal))
      <tr>
        <td>Subtotal:</td>
        <td style="text-align:right;">${{ number_format($purchaseOrder->subtotal, 2) }}</td>
      </tr>
      @endif
      <tr>
        <td><strong>Total:</strong></td>
        <td style="text-align:right;"><strong>${{ number_format($purchaseOrder->total ?? 0, 2) }}</strong></td>
      </tr>
    </table>
  </div>

  <div class="notes">
    Agradecemos confirmar la recepción de esta orden de compra y la fecha estimada de entrega de los productos.
  </div>

  <table class="layout-table" style="margin-top: 1.5cm; page-break-inside: avoid;">
    <tr>
      <td>
        <strong class="business-name">DVariedad ERP</strong><br>
        <strong>Departamento de Compras</strong><br>
        <strong>Contacto:</strong> 2200-2500
      </td>
    </tr>
  </table>

</body>
</html>