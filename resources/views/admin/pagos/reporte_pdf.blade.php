<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pagos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .titulo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitulo {
            font-size: 14px;
            color: #666;
        }
        .filtros {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .tabla {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }
        .tabla th, .tabla td {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .tabla th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .estado {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .estado-completado { background-color: #dff0d8; color: #3c763d; }
        .estado-pendiente { background-color: #fcf8e3; color: #8a6d3b; }
        .estado-cancelado { background-color: #f2dede; color: #a94442; }
        .estado-anulado { background-color: #f5f5f5; color: #777; }
        .resumen {
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($configuracion && $configuracion->logotipo)
            <img src="{{ public_path('storage/' . $configuracion->logotipo) }}" class="logo" alt="Logo">
        @endif
        <div class="titulo">{{ $configuracion->nombre ?? 'Sistema de Gestión Escolar' }}</div>
        <div class="subtitulo">Reporte de Pagos</div>
    </div>

    <div class="filtros">
        <strong>Filtros aplicados:</strong><br>
        @if($request->fecha_inicio)
            <span>Desde: {{ $request->fecha_inicio }}</span>
        @endif
        @if($request->fecha_fin)
            <span>Hasta: {{ $request->fecha_fin }}</span>
        @endif
        @if($request->estado)
            <span>Estado: {{ ucfirst($request->estado) }}</span>
        @endif
        @if($request->metodo_pago)
            <span>Método de pago: {{ ucfirst($request->metodo_pago) }}</span>
        @endif
    </div>

    <div class="resumen">
        <strong>Resumen:</strong><br>
        Total de pagos: {{ $pagos->count() }}<br>
        Monto total: {{ $configuracion->divisa ?? 'Bs.' }} {{ number_format($pagos->sum('monto'), 2) }}<br>
        Completados: {{ $pagos->where('estado', 'completado')->count() }}<br>
        Pendientes: {{ $pagos->where('estado', 'pendiente')->count() }}<br>
        Cancelados: {{ $pagos->where('estado', 'cancelado')->count() }}<br>
        Anulados: {{ $pagos->where('estado', 'anulado')->count() }}
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Estudiante</th>
                <th>Grado</th>
                <th>Gestión</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Descripción</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagos as $pago)
            <tr>
                <td>{{ str_pad($pago->id, 8, '0', STR_PAD_LEFT) }}</td>
                <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $pago->matriculacion->estudiante->nombre_completo }}</td>
                <td>{{ $pago->matriculacion->grado->nombre }} - {{ $pago->matriculacion->paralelo->nombre }}</td>
                <td>{{ $pago->matriculacion->gestion->nombre }}</td>
                <td>{{ $configuracion->divisa ?? 'Bs.' }} {{ number_format($pago->monto, 2) }}</td>
                <td>{{ ucfirst($pago->metodo_pago) }}</td>
                <td>{{ $pago->descripcion }}</td>
                <td><span class="estado estado-{{ $pago->estado }}">{{ ucfirst($pago->estado) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ $configuracion->nombre ?? 'Sistema de Gestión Escolar' }}</p>
        <p>{{ $configuracion->direccion ?? '' }}</p>
        <p>{{ $configuracion->telefono ?? '' }} - {{ $configuracion->correo_electronico ?? '' }}</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
