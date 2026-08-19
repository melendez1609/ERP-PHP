<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cotización #{{ $quotation->id }}</title>
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
        <img class="logo" src="{{ $logoBase64 }}" alt="Logo">
        <div class="company-info">
          <strong>G-ERP: </strong>Sistema de Gestión de Negocios y Punto de Venta
        </div>
      </td>
      <td class="invoice-meta">
        <div class="invoice-title">Cotización</div>
        <div><strong>Cotización No.:</strong> COT-{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}</div>
        <div><strong>Fecha:</strong> {{ $date ?? date('Y-m-d') }}</div>
      </td>
    </tr>
  </table>

  <div class="recipient">
    <strong>Cliente:</strong><br>
    {{ $quotation->customer_name }}
  </div>

  <table class="items-table">
    <thead>
      <tr>
        <th style="width:5%">#</th>
        <th>Descripción</th>
        <th style="width:10%; text-align: right;">Cant.</th>
        <th style="width:15%; text-align: right;">Precio Unit.</th>
        <th style="width:15%; text-align: right;">Monto</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $item['name'] }}</td>
          <td style="text-align: right;">{{ $item['quantity'] }}</td>
          <td style="text-align: right;">${{ number_format($item['price'], 2) }}</td>
          <td style="text-align: right;">${{ number_format($item['subtotal'], 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="totals">
    <table>
      <tr>
        <td><strong>Total:</strong></td>
        <td style="text-align:right;"><strong>${{ number_format($quotation->total, 2) }}</strong></td>
      </tr>
    </table>
  </div>

  <div class="notes">
    Los precios indicados en la presente cotización no incluyen IVA.
  </div>

  <table class="layout-table" style="margin-top: 1.5cm; page-break-inside: avoid;">
    <tr>
      <td>
        <strong class="business-name">DVariedad</strong><br>
        <strong>Contacto:</strong> 2200-2500<br>
        <strong>Correo Electrónico:</strong> 2200-2500
      </td>
    </tr>
  </table>

</body>
</html>