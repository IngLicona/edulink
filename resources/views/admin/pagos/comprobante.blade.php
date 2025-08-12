<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4 portrait;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.4;
        }
        .comprobante-container {
            position: relative;
            height: 130mm;
            padding: 8mm;
            box-sizing: border-box;
            font-size: 9px;
        }
        .comprobante-container:first-child::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1mm;
            border-bottom: 1px dashed #000;
        }
        .tipo-comprobante {
            position: absolute;
            top: 15mm;
            right: 15mm;
            font-weight: bold;
            font-size: 14px;
        }
        .logo-section {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            width: 40px;
            height: auto;
            margin-bottom: 2px;
        }
        .header-info {
            text-align: center;
            font-size: 8px;
            line-height: 1.1;
            margin-bottom: 8px;
        }
        .institucion {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .header-detalle {
            font-size: 10px;
            color: #333;
        }
        .titulo-comprobante {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 15px 0;
            text-decoration: underline;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
            margin-bottom: 10px;
        }
        .info-item {
            padding: 3px;
        }
        .info-label {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 1px;
        }
        .info-value {
            font-size: 9px;
        }
        .monto-box {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            margin: 8px 0;
        }
        .monto-titulo {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .monto-valor {
            font-size: 14px;
            font-weight: bold;
        }
        .estado {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .estado-completado { background: #d4edda; }
        .estado-pendiente { background: #fff3cd; }
        .estado-cancelado { background: #f8d7da; }
        .estado-anulado { background: #f8d7da; }
        .firmas {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            padding: 0 10px;
        }
        .firma {
            text-align: center;
            width: 45%;
            font-size: 8px;
        }
        .linea-firma {
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }
        .footer {
            position: absolute;
            bottom: 8px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            padding: 0 8mm;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .comprobante-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Comprobante Original -->
    <div class="comprobante-container">
        <div class="tipo-comprobante">ORIGINAL</div>
        @include('admin.pagos.partials.contenido-comprobante', ['tipo' => 'ORIGINAL'])
    </div>

    <!-- Comprobante Copia -->
    <div class="comprobante-container">
        <div class="tipo-comprobante">COPIA</div>
        @include('admin.pagos.partials.contenido-comprobante', ['tipo' => 'COPIA'])
    </div>
</body>
</html>