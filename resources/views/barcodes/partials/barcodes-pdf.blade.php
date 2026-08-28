<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Códigos de Barras</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            size: letter portrait;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .page-container {
            width: 208.81mm;
            margin: 0 auto;
            padding-top: 4mm;
        }

        .page-break {
            page-break-after: always;
            clear: both;
        }

        .barcode-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .barcode-table tr {
            height: 20mm;
        }

        .barcode-table td {
            width: 29.83mm;
            height: 20mm;
            padding: 0.5mm;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
        }

        .barcode-wrapper {
            width: 100%;
            text-align: center;
        }

        .barcode-img {
            max-width: 90%;
            height: 10mm;
            display: block;
            margin: 0 auto;
        }

        .barcode-number {
            font-size: 5.5pt;
            font-weight: bold;
            color: #000;
            letter-spacing: 0.5px;
            margin-top: 0.3mm;
            line-height: 1;
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body>
@foreach(array_chunk($barcodes, 91) as $pageBarcodes)<div class="page-container">
        <table class="barcode-table">
            @foreach(array_chunk($pageBarcodes, 7) as $row)
                <tr>
                    @foreach($row as $barcode)
                        <td>
                            <div class="barcode-wrapper">
                                <img src="{{ $barcode['image'] }}" class="barcode-img" alt="EAN-13">
                                <div class="barcode-number">
                                    {{ substr($barcode['number'], 0, 1) }} {{ substr($barcode['number'], 1, 6) }} {{ substr($barcode['number'], 7, 6) }}
                                </div>
                            </div>
                        </td>
                    @endforeach

                    @for($i = count($row); $i < 7; $i++)
                        <td></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    </div>@if(!$loop->last)<div class="page-break"></div>@endif @endforeach
</body>
</html>