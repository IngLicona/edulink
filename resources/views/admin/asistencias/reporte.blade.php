<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            body { font-size: 12px; }
        }
        
        .header-logo {
            max-width: 80px;
            max-height: 80px;
        }
        
        .report-header {
            border-bottom: 3px solid #007bff;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        
        .stats-card {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .badge-custom {
            font-size: 0.85em;
            padding: 0.375rem 0.75rem;
        }
        
        .table-condensed {
            font-size: 0.9em;
        }
        
        .signature-section {
            margin-top: 50px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        
        .signature-box {
            text-align: center;
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header del Reporte -->
        <div class="report-header">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @php
                        $configuracion = \App\Models\Configuracion::first();
                    @endphp
                    @if($configuracion && $configuracion->logotipo)
                        <img src="{{ public_path('uploads/logos/' . $configuracion->logotipo) }}" 
                             alt="Logo" class="header-logo">
                    @else
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; font-size: 24px;">
                            <i class="fas fa-school"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8 text-center">
                    <h3 class="mb-1">{{ $configuracion->nombre ?? 'INSTITUCIÓN EDUCATIVA' }}</h3>
                    <p class="mb-1">{{ $configuracion->direccion ?? 'Dirección no registrada' }}</p>
                    <p class="mb-0">Teléfono: {{ $configuracion->telefono ?? 'N/A' }} | Email: {{ $configuracion->correo_electronico ?? 'N/A' }}</p>
                </div>
                <div class="col-md-2 text-center">
                    <div class="badge badge-primary badge-custom">
                        Reporte Generado
                    </div>
                    <br>
                    <small>{{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>

        <!-- Título del Reporte -->
        <div class="text-center mb-4">
            <h2 class="text-primary">
                <i class="fas fa-chart-bar mr-2"></i>
                REPORTE DE ASISTENCIAS
            </h2>
        </div>

        <!-- Información de la Asignación -->
        <div class="stats-card">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Información de la Asignación
                    </h5>
                    <p><strong>Docente:</strong> {{ $asignacion->docente ? $asignacion->docente->nombre . ' ' . $asignacion->docente->paterno . ' ' . $asignacion->docente->materno : 'Sin docente' }}</p>
                    <p><strong>Gestión:</strong> {{ $asignacion->gestion->nombre }}</p>
                    <p><strong>Nivel:</strong> {{ $asignacion->nivel->nombre }}</p>
                    <p><strong>Grado y Paralelo:</strong> {{ $asignacion->grado->nombre }} "{{ $asignacion->paralelo->nombre }}"</p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-calendar-alt mr-2"></i>Período del Reporte
                    </h5>
                    <p><strong>Materia:</strong> {{ $asignacion->materia->nombre }}</p>
                    <p><strong>Turno:</strong> {{ $asignacion->turno->nombre }}</p>
                    <p><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</p>
                    <p><strong>Fecha de Fin:</strong> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Resumen Estadístico General -->
        @if($estadisticas->count() > 0)
            @php
                $totalRegistros = $estadisticas->sum('total');
                $totalPresentes = $estadisticas->sum('presente');
                $totalAusentes = $estadisticas->sum('ausente');
                $totalTardes = $estadisticas->sum('tarde');
                $totalJustificados = $estadisticas->sum('justificado');
                $promedioAsistencia = $estadisticas->avg('porcentaje_asistencia');
            @endphp

            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-chart-pie mr-2"></i>Resumen Estadístico General
                    </h5>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h4 class="text-primary">{{ $estadisticas->count() }}</h4>
                            <small>Estudiantes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4>{{ $totalPresentes + $totalTardes }}</h4>
                            <small>Total Asistencias</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h4>{{ $totalAusentes }}</h4>
                            <small>Total Ausencias</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4>{{ number_format($promedioAsistencia, 1) }}%</h4>
                            <small>Promedio Asistencia</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabla Detallada de Asistencias -->
        <div class="mb-4">
            <h5 class="text-primary mb-3">
                <i class="fas fa-table mr-2"></i>Detalle por Estudiante
            </h5>
            
            @if($estadisticas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">Nro</th>
                                <th style="width: 35%">Estudiante</th>
                                <th class="text-center" style="width: 10%">CI</th>
                                <th class="text-center" style="width: 8%">Total</th>
                                <th class="text-center" style="width: 8%">Presentes</th>
                                <th class="text-center" style="width: 8%">Ausentes</th>
                                <th class="text-center" style="width: 8%">Tardanzas</th>
                                <th class="text-center" style="width: 8%">Justificadas</th>
                                <th class="text-center" style="width: 10%">% Asistencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estadisticas->sortBy('estudiante.paterno') as $index => $estadistica)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $estadistica['estudiante']->paterno . ' ' . $estadistica['estudiante']->materno . ', ' . $estadistica['estudiante']->nombre }}</strong>
                                    </td>
                                    <td class="text-center">{{ $estadistica['estudiante']->ci }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary badge-custom">{{ $estadistica['total'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success badge-custom">{{ $estadistica['presente'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-danger badge-custom">{{ $estadistica['ausente'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning badge-custom">{{ $estadistica['tarde'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info badge-custom">{{ $estadistica['justificado'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $estadistica['porcentaje_asistencia'] >= 80 ? 'success' : ($estadistica['porcentaje_asistencia'] >= 60 ? 'warning' : 'danger') }} badge-custom">
                                            {{ $estadistica['porcentaje_asistencia'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Análisis de Rendimiento -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-analytics mr-2"></i>Análisis de Rendimiento
                        </h5>
                    </div>
                    
                    @php
                        $excelente = $estadisticas->where('porcentaje_asistencia', '>=', 90)->count();
                        $bueno = $estadisticas->whereBetween('porcentaje_asistencia', [80, 89.99])->count();
                        $regular = $estadisticas->whereBetween('porcentaje_asistencia', [60, 79.99])->count();
                        $deficiente = $estadisticas->where('porcentaje_asistencia', '<', 60)->count();
                    @endphp
                    
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h3 class="text-success">{{ $excelente }}</h3>
                                <p class="mb-0">Excelente (≥90%)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary">{{ $bueno }}</h3>
                                <p class="mb-0">Bueno (80-89%)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h3 class="text-warning">{{ $regular }}</h3>
                                <p class="mb-0">Regular (60-79%)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h3 class="text-danger">{{ $deficiente }}</h3>
                                <p class="mb-0">Deficiente (<60%)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recomendaciones -->
                <div class="mt-4">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-lightbulb mr-2"></i>Observaciones y Recomendaciones
                    </h5>
                    <div class="alert alert-info">
                        @if($promedioAsistencia >= 85)
                            <p><strong>Excelente:</strong> El grupo mantiene un muy buen nivel de asistencia ({{ number_format($promedioAsistencia, 1) }}%). Se recomienda continuar con las estrategias actuales.</p>
                        @elseif($promedioAsistencia >= 70)
                            <p><strong>Bueno:</strong> El grupo tiene un nivel aceptable de asistencia ({{ number_format($promedioAsistencia, 1) }}%). Se sugiere implementar estrategias para mejorar la puntualidad.</p>
                        @else
                            <p><strong>Requiere Atención:</strong> El nivel de asistencia del grupo es bajo ({{ number_format($promedioAsistencia, 1) }}%). Se recomienda implementar un plan de seguimiento individualizado.</p>
                        @endif
                        
                        @if($deficiente > 0)
                            <p><strong>Atención Especial:</strong> {{ $deficiente }} estudiante(s) requieren seguimiento especial por asistencia deficiente.</p>
                        @endif
                        
                        @if($totalTardes > $totalPresentes * 0.1)
                            <p><strong>Puntualidad:</strong> Se observa un número significativo de tardanzas. Se recomienda reforzar la importancia de la puntualidad.</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    <h4><i class="fas fa-exclamation-triangle mr-2"></i>Sin Datos</h4>
                    <p>No se encontraron registros de asistencia para el período seleccionado.</p>
                </div>
            @endif
        </div>

        <!-- Sección de Firmas -->
        <div class="signature-section no-print">
            <div class="row">
                <div class="col-md-4">
                    <div class="signature-box">
                        <p class="mb-0"><strong>DOCENTE</strong></p>
                        <p class="mb-0">{{ $asignacion->docente ? $asignacion->docente->nombre . ' ' . $asignacion->docente->paterno . ' ' . $asignacion->docente->materno : 'Sin docente' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="signature-box">
                        <p class="mb-0"><strong>COORDINADOR ACADÉMICO</strong></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="signature-box">
                        <p class="mb-0"><strong>DIRECTOR/A GENERAL</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-3 text-center text-muted no-print">
            <small>
                Reporte generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }} hrs. | 
                Sistema de Gestión Escolar
            </small>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="fixed-bottom p-3 no-print" style="background: rgba(248, 249, 250, 0.9);">
        <div class="text-center">
            <button onclick="window.print()" class="btn btn-primary btn-lg mr-2">
                <i class="fas fa-print mr-2"></i>Imprimir Reporte
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg">
                <i class="fas fa-times mr-2"></i>Cerrar
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-print cuando se carga la página (opcional)
        // window.onload = function() { window.print(); }
        
        // Cerrar ventana después de imprimir
        window.onafterprint = function() {
            setTimeout(function() {
                if (confirm('¿Desea cerrar esta ventana?')) {
                    window.close();
                }
            }, 1000);
        }
    </script>
</body>
</html>