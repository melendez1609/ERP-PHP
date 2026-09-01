<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
        }
        body {
            width: 76mm;
            margin: 0 auto;
            background: #fff;
            padding: 4mm 2mm;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            font-size: 11px;
            padding: 3px 0;
            text-align: left;
        }
        th { border-bottom: 1px solid #000; }
        .totals td { font-size: 12px; }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body> 

    <div class="center bold" style="font-size: 14px;">G-ERP POS</div>
    <div class="center">Caja Principal</div>
    <div class="center">Atendido por: {{ $sale->user?->name ?? 'Cajero' }}</div>
    
    <div class="line"></div>
    
    <div><strong>Ticket:</strong> #{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</div>
    <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}</div>
    <div><strong>Cliente:</strong> Público General</div>
    
    <div class="line"></div>
    
    <table>
        <thead>
            <tr>
                <th>Cant/Desc</th>
                <th class="right">P.U.</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->quantity }}x {{ $item->product_name }}</td>
                <td class="right">${{ number_format($item->price, 2) }}</td>
                <td class="right">${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table class="totals">
        <tr>
            <td>SUBTOTAL:</td>
            <td class="right">${{ number_format($sale->subtotal, 2) }}</td>
        </tr>
        <tr class="bold">
            <td>TOTAL A PAGAR:</td>
            <td class="right">${{ number_format($sale->total, 2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center bold">¡Gracias por su compra!</div>
    <div class="center footer">Conserve este ticket para cualquier reclamo.</div>

</body>
</html>