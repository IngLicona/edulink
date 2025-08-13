<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Calificaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 100px;
            height: auto;
        }
        .header h1 {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }
        .header h2 {
            font-size: 16px;
            margin: 5px 0;
            color: #666;
        }
        .info-container {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .periodo-header {
            background-color: #eee;
            padding: 5px;
            margin: 10px 0;
            font-weight: bold;
        }
        .promedio-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
        }
        .firma {
            display: inline-block;
            margin: 0 50px;
            text-align: center;
        }
        .firma-linea {
            width: 200px;
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }
        .page-break {
            page-break-after: always;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(200, 200, 200, 0.1);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">CONFIDENCIAL</div>
    
    <div class="header">
        @if(isset($configuracion) && $configuracion->logotipo)
            <img src="{{ public_path('uploads/logos/' . $configuracion->logotipo) }}" alt="Logo">
        @endif
        <h1>{{ $configuracion->nombre ?? 'SISTEMA DE GESTIÓN ESCOLAR' }}</h1>
        <h2>REPORTE DE CALIFICACIONES</h2>
        <h3>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</h3>
    </div>

    <div class="info-container">
        <div class="info-row">
            <span class="info-label">Docente:</span>
            <span>{{ $asignacion->docente->nombre_completo }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Materia:</span>
            <span>{{ $asignacion->materia->nombre }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Gestión:</span>
            <span>{{ $asignacion->gestion->nombre }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nivel:</span>
            <span>{{ $asignacion->nivel->nombre }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Grado:</span>
            <span>{{ $asignacion->grado->nombre }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Paralelo:</span>
            <span>{{ $asignacion->paralelo->nombre }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Turno:</span>
            <span>{{ $asignacion->turno->nombre }}</span>
        </div>
    </div>

    @if($estudiantes->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Estudiante</th>
                    @foreach($periodos as $periodo)
                        <th class="text-center">{{ $periodo->nombre }}</th>
                    @endforeach
                    <th class="text-center">Promedio Final</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estudiantes as $index => $estudiante)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $estudiante->paterno }} {{ $estudiante->materno }} {{ $estudiante->nombre }}</td>
                        @php
                            $promedioFinal = 0;
                            $periodosConNotas = 0;
                        @endphp
                        @foreach($periodos as $periodo)
                            @php
                                $notasPeriodo = $calificaciones
                                    ->where('periodo_id', $periodo->id)
                                    ->flatMap(function($cal) use ($estudiante) {
                                        return $cal->detalleCalificaciones
                                            ->where('estudiante_id', $estudiante->id)
                                            ->pluck('nota');
                                    });
                                
                                $promedioPeriodo = $notasPeriodo->count() > 0 ? round($notasPeriodo->avg(), 2) : 0;
                                if($promedioPeriodo > 0) {
                                    $promedioFinal += $promedioPeriodo;
                                    $periodosConNotas++;
                                }
                            @endphp
                            <td class="text-center">
                                {{ $promedioPeriodo > 0 ? $promedioPeriodo : '-' }}
                            </td>
                        @endforeach
                        <td class="text-center">
                            {{ $periodosConNotas > 0 ? round($promedioFinal / $periodosConNotas, 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 30px;">
            <h3>Resumen Estadístico</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criterio</th>
                        @foreach($periodos as $periodo)
                            <th class="text-center">{{ $periodo->nombre }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Promedio General</td>
                        @foreach($periodos as $periodo)
                            @php
                                $promedioGeneral = $calificaciones
                                    ->where('periodo_id', $periodo->id)
                                    ->flatMap(function($cal) {
                                        return $cal->detalleCalificaciones->pluck('nota');
                                    })
                                    ->avg();
                            @endphp
                            <td class="text-center">
                                {{ $promedioGeneral ? round($promedioGeneral, 2) : '-' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Nota Más Alta</td>
                        @foreach($periodos as $periodo)
                            @php
                                $notaMaxima = $calificaciones
                                    ->where('periodo_id', $periodo->id)
                                    ->flatMap(function($cal) {
                                        return $cal->detalleCalificaciones->pluck('nota');
                                    })
                                    ->max();
                            @endphp
                            <td class="text-center">
                                {{ $notaMaxima ?: '-' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Nota Más Baja</td>
                        @foreach($periodos as $periodo)
                            @php
                                $notaMinima = $calificaciones
                                    ->where('periodo_id', $periodo->id)
                                    ->flatMap(function($cal) {
                                        return $cal->detalleCalificaciones->pluck('nota');
                                    })
                                    ->min();
                            @endphp
                            <td class="text-center">
                                {{ $notaMinima ?: '-' }}
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 20px;">
            <p>No hay estudiantes matriculados en esta asignación.</p>
        </div>
    @endif

    <div class="footer">
        <div class="firma">
            <div class="firma-linea"></div>
            <div>{{ $asignacion->docente->nombre_completo }}</div>
            <div>Docente</div>
        </div>
        <div class="firma">
            <div class="firma-linea"></div>
            <div>Director/a</div>
        </div>
    </div>
</body>
</html>
