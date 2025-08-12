<div class="logo-section">
    @if($configuracion && $configuracion->logotipo)
        <img src="{{ public_path('uploads/logos/' . $configuracion->logotipo) }}" alt="Logo" class="logo"><br>
    @endif
    <div class="header-info">
        <div class="institucion">{{ $configuracion->nombre ?? 'INSTITUCIÓN EDUCATIVA' }}</div>
        <div class="header-detalle">
            {{ $configuracion->direccion ?? 'Dirección no especificada' }}<br>
            Tel: {{ $configuracion->telefono ?? 'N/A' }} | 
            Email: {{ $configuracion->correo_electronico ?? 'N/A' }}
        </div>
    </div>
</div>

<div class="titulo-comprobante">
    COMPROBANTE DE PAGO N° {{ str_pad($pago->id, 8, '0', STR_PAD_LEFT) }}
</div>

<div class="info-grid">
    <div class="info-item">
        <div class="info-label">ESTUDIANTE</div>
        <div class="info-value">{{ $pago->matriculacion->estudiante->nombre_completo }}</div>
    </div>
    <div class="info-item">
        <div class="info-label">CI</div>
        <div class="info-value">{{ $pago->matriculacion->estudiante->ci }}</div>
    </div>
    <div class="info-item">
        <div class="info-label">GRADO Y PARALELO</div>
        <div class="info-value">{{ $pago->matriculacion->grado->nombre }} "{{ $pago->matriculacion->paralelo->nombre }}"</div>
    </div>
    <div class="info-item">
        <div class="info-label">GESTIÓN</div>
        <div class="info-value">{{ $pago->matriculacion->gestion->nombre }}</div>
    </div>
    <div class="info-item">
        <div class="info-label">FECHA DE PAGO</div>
        <div class="info-value">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</div>
    </div>
    <div class="info-item">
        <div class="info-label">MÉTODO DE PAGO</div>
        <div class="info-value">{{ ucfirst($pago->metodo_pago) }}</div>
    </div>
    <div class="info-item" style="grid-column: span 2;">
        <div class="info-label">DESCRIPCIÓN</div>
        <div class="info-value">{{ $pago->descripcion }}</div>
    </div>
</div>

<div class="monto-box">
    <div class="monto-titulo">MONTO PAGADO</div>
    <div class="monto-valor">{{ $configuracion->divisa ?? 'Bs.' }} {{ number_format($pago->monto, 2) }}</div>
</div>

<div style="text-align: center; margin: 10px 0;">
    <div class="info-label">ESTADO DEL PAGO</div>
    <div style="margin-top: 5px;">
        @switch($pago->estado)
            @case('completado')
                <span class="estado estado-completado">COMPLETADO</span>
                @break
            @case('pendiente')
                <span class="estado estado-pendiente">PENDIENTE</span>
                @break
            @case('cancelado')
                <span class="estado estado-cancelado">CANCELADO</span>
                @break
            @default
                <span class="estado estado-anulado">{{ strtoupper($pago->estado) }}</span>
        @endswitch
    </div>
</div>

<div class="firmas">
    <div class="firma">
        <div class="linea-firma"></div>
        <div style="font-weight: bold;">ESTUDIANTE/PPFF</div>
        <div style="font-size: 9px;">Nombre y CI</div>
    </div>
    <div class="firma">
        <div class="linea-firma"></div>
        <div style="font-weight: bold;">{{ $configuracion->nombre ?? 'ADMINISTRACIÓN' }}</div>
        <div style="font-size: 9px;">Sello y Firma</div>
    </div>
</div>

<div class="footer">
    <strong>{{ $tipo }}</strong> - Emitido el {{ now()->format('d/m/Y H:i:s') }}<br>
    Este comprobante es un documento válido para respaldo del pago realizado.
    @if($configuracion && $configuracion->web)
        <br>{{ $configuracion->web }}
    @endif
</div>
