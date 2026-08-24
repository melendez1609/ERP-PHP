<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orden de Compra</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #E3E3E3;
      font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #333333;
      -webkit-font-smoothing: antialiased;
    }
    .email-container {
      max-width: 600px;
      margin: 30px auto;
      background-color: #ffffff;
      border-top: 5px solid #132873;
      border-radius: 4px;
      overflow: hidden;
    }
    .content {
      padding: 35px 40px;
    }
    .logo-container {
      text-align: center;
      margin-bottom: 25px;
    }
    .logo-container img {
      max-width: 180px;
      width: 100%;
      height: auto;
      display: inline-block;
    }
    .greeting {
      font-size: 18px;
      font-weight: bold;
      color: #132873;
      margin-bottom: 20px;
    }
    p {
      font-size: 15px;
      line-height: 1.6;
      margin: 0 0 16px 0;
    }
    .btn-container {
      margin: 25px 0;
    }
    .btn {
      display: inline-block;
      background-color: #132873;
      color: #ffffff !important;
      padding: 12px 24px;
      font-weight: bold;
      font-size: 15px;
      text-decoration: none;
      border-radius: 6px;
    }
    .sign-off {
      margin-top: 25px;
      font-size: 15px;
    }
    .footer {
      background-color: #F5F5F5;
      padding: 20px 40px;
      text-align: center;
      font-size: 12px;
      color: #888888;
    }
  </style>
</head>
<body>

  <div class="email-container">
    <div class="content">

      <div class="logo-container">
        <img src="cid:company_logo" alt="Logo DVariedades">
      </div>

      <div class="greeting">
        Estimado(a) {{ $purchaseOrder->supplier?->name ?? 'Proveedor' }},
      </div>

      <p>
        Por medio de la presente, emitimos la Orden de Compra <strong>{{ $purchaseOrder->order_number ?? '#' . str_pad($purchaseOrder->id, 5, '0', STR_PAD_LEFT) }}</strong> para la adquisición de productos a su representada.
      </p>

      <p>
        Adjunto a este mensaje y mediante el botón inferior, encontrará el documento detallado con las cantidades y especificaciones solicitadas. Le agradecemos confirmar la recepción de este pedido y coordinar la fecha estimada de despacho.
      </p>

      <div class="btn-container">
        <a href="{{ $pdfUrl ?? '#' }}" class="btn" target="_blank">Ver Orden de Compra</a>
      </div>

      <p>
        Quedamos atentos a sus comentarios para dar seguimiento al pedido.
      </p>

      <div class="sign-off">
        <span style="color: #132873; font-weight: bold;">Atentamente,</span><br>
        <span style="color: #132873; font-weight: bold;">Departamento de Compras</span>
      </div>
    </div>

    <div class="footer">
      Este es un mensaje automático generado por el sistema {{ config('app.name', 'G-ERP') }}.
    </div>
  </div>

</body>
</html>