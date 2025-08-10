<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrícula del Estudiante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 11px;
            line-height: 1.3;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            position: relative;
            min-height: 100px;
        }
        
        .logo {
            width: 70px;
            height: 70px;
            float: left;
            margin-right: 15px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #2c5aa0;
        }
        
        .logo-placeholder {
            width: 70px;
            height: 70px;
            float: left;
            margin-right: 15px;
            border: 2px solid #2c5aa0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #2c5aa0;
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        
        .school-info {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .school-details {
            font-size: 9px;
            color: #666;
            line-height: 1.1;
        }
        
        .document-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            text-transform: uppercase;
            clear: both;
        }
        
        .photo-placeholder {
            width: 80px;
            height: 100px;
            border: 2px solid #333;
            float: right;
            margin-left: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            text-align: center;
            background-color: #f9f9f9;
            object-fit: cover;
        }
        
        .student-photo {
            width: 80px;
            height: 100px;
            border: 2px solid #333;
            float: right;
            margin-left: 15px;
            margin-bottom: 15px;
            object-fit: cover;
        }
        
        .student-info {
            margin-bottom: 15px;
            overflow: hidden;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        
        .info-label {
            display: table-cell;
            width: 120px;
            font-weight: bold;
            padding-right: 10px;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            border-bottom: 1px solid #333;
            padding-bottom: 2px;
            vertical-align: top;
        }
        
        .section-title {
            font-weight: bold;
            color: #2c5aa0;
            margin: 15px 0 8px 0;
            font-size: 12px;
        }
        
        .terms-section {
            margin-top: 20px;
            font-size: 10px;
            text-align: justify;
            line-height: 1.2;
        }
        
        .terms-title {
            font-weight: bold;
            color: #2c5aa0;
        }
        
        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }
        
        .signature-left, .signature-right {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: bottom;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 3px;
            font-size: 9px;
        }
        
        .date-footer {
            text-align: right;
            margin-top: 15px;
            font-size: 10px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        @page {
            margin: 1.5cm;
        }

        /* Estilos adicionales para mejor presentación */
        .highlight {
            color: #2c5aa0;
            font-weight: bold;
        }

        .badge {
            background-color: #2c5aa0;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header clearfix">
        {{-- Logo de la institución --}}
        @if($logoPath)
            <img src="{{ $logoPath }}" alt="Logo Institución" class="logo">
        @else
            <div class="logo-placeholder">
                {{ strtoupper(substr($configuracion->nombre ?? 'EPO', 0, 3)) }}
            </div>
        @endif
        
        {{-- Información de la institución --}}
        <div class="school-info">
            <div class="school-name">
                {{ $configuracion->nombre ?? 'INSTITUCIÓN EDUCATIVA' }}
            </div>
            <div class="school-details">
                {{ $configuracion->direccion ?? 'ZONA VILLA NUEVA - EL VALLE' }}<br>
                {{ $configuracion->telefono ? 'TEL/FAX: ' . $configuracion->telefono : 'TEL/FAX: 2-460477' }}<br>
                {{ $configuracion->web ?? 'BOLIVIA - LA PAZ' }}
                @if($configuracion->correo_electronico)
                    <br>{{ $configuracion->correo_electronico }}
                @endif
            </div>
        </div>
        
        {{-- Foto del estudiante --}}
        @if($fotoEstudiantePath)
            <img src="{{ $fotoEstudiantePath }}" alt="Foto estudiante" class="student-photo">
        @else
            <div class="photo-placeholder">
                Fotografía<br>del<br>Estudiante
            </div>
        @endif
    </div>
    
    <div class="document-title">
        Matrícula del estudiante
    </div>
    
    <div class="student-info clearfix">
        <div class="info-row">
            <div class="info-label">Gestión:</div>
            <div class="info-value highlight">{{ $matriculacion->gestion->nombre }}</div>
            <div style="display: table-cell; width: 50px;"></div>
            <div class="info-label">Nombres:</div>
            <div class="info-value">{{ $matriculacion->estudiante->nombre }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Turno:</div>
            <div class="info-value">{{ $matriculacion->turno->nombre }}</div>
            <div style="display: table-cell; width: 50px;"></div>
            <div class="info-label">Apellidos:</div>
            <div class="info-value">{{ $matriculacion->estudiante->paterno }} {{ $matriculacion->estudiante->materno }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Nivel:</div>
            <div class="info-value">{{ $matriculacion->nivel->nombre }}</div>
            <div style="display: table-cell; width: 50px;"></div>
            <div class="info-label">C.I.:</div>
            <div class="info-value">{{ $matriculacion->estudiante->ci }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Grado:</div>
            <div class="info-value">{{ $matriculacion->grado->nombre }}</div>
            <div style="display: table-cell; width: 50px;"></div>
            <div class="info-label">Género:</div>
            <div class="info-value">{{ ucfirst($matriculacion->estudiante->genero) }}</div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Paralelo:</div>
            <div class="info-value">{{ $matriculacion->paralelo->nombre }}</div>
            <div style="display: table-cell; width: 50px;"></div>
            <div class="info-label">Teléfono:</div>
            <div class="info-value">{{ $matriculacion->estudiante->telefono ?? 'No registrado' }}</div>
        </div>

        @if($matriculacion->estudiante->fecha_nacimiento)
        <div class="info-row">
            <div class="info-label">Fecha Matrícula:</div>
            <div class="info-value">{{ $matriculacion->fecha_matriculacion->format('d/m/Y') }}</div>
            <div style="display: table-cell; width: 50px;"></div>
            <div class="info-label">Fecha Nacimiento:</div>
            <div class="info-value">{{ $matriculacion->estudiante->fecha_nacimiento->format('d/m/Y') }}</div>
        </div>
        @endif

        @if($matriculacion->estudiante->direccion)
        <div class="info-row">
            <div class="info-label">Dirección:</div>
            <div class="info-value" style="width: 70%;" colspan="3">{{ $matriculacion->estudiante->direccion }}</div>
        </div>
        @endif
    </div>
    
    <div class="terms-section">
        <p><span class="terms-title">Partes Contratantes:</span> La Institución <strong>{{ $configuracion->nombre ?? 'EPO' }}</strong>, en adelante denominado "La Institución Educativa", representado por <strong>_____________________________</strong>, con domicilio en {{ $configuracion->direccion ?? 'av. del maestro s/n' }}. Padres/Tutores legales del estudiante <strong class="highlight">{{ $matriculacion->estudiante->nombre }} {{ $matriculacion->estudiante->paterno }} {{ $matriculacion->estudiante->materno }}</strong>, en adelante denominado "El Estudiante", representados por <strong class="highlight">{{ $matriculacion->estudiante->ppff ? $matriculacion->estudiante->ppff->nombre_completo : 'Padre/Tutor' }}</strong>, con domicilio en <strong>{{ $matriculacion->estudiante->direccion ?? 'Av. Las Palmas #456' }}</strong>.</p>
        
        <p><span class="terms-title">Términos y Condiciones:</span> <span class="terms-title">Matrícula:</span> Los Padres/Tutores legales matriculan al Estudiante en La Institución Educativa para el año escolar <strong>{{ $matriculacion->gestion->nombre }}</strong> en el nivel <strong>{{ $matriculacion->nivel->nombre }}</strong>, grado <strong>{{ $matriculacion->grado->nombre }}</strong>, paralelo <strong>{{ $matriculacion->paralelo->nombre }}</strong>, turno <strong>{{ $matriculacion->turno->nombre }}</strong>. <span class="terms-title">Compromisos del Estudiante:</span> El Estudiante se compromete a asistir puntualmente a clases, participar activamente en las actividades escolares y seguir las normas y reglamentos establecidos por La Institución Educativa.</p>
        
        <p><span class="terms-title">Compromisos de los Padres/Tutores:</span> Los Padres/Tutores se comprometen a apoyar activamente la educación del Estudiante, mantener una comunicación regular con los maestros y cumplir con las obligaciones financieras relacionadas con la matrícula y las tarifas escolares. <span class="terms-title">Duración del Contrato:</span> Este contrato tiene una duración de un año escolar y se renovará automáticamente para cada año escolar subsiguiente, a menos que cualquiera de las partes notifique lo contrario con al menos 10 días de antelación al inicio del nuevo año escolar.</p>

        <p><span class="terms-title">Disposiciones Generales:</span> Ambas partes acuerdan cumplir con los reglamentos internos de la institución, respetar los horarios establecidos, y mantener una comunicación fluida para el beneficio educativo del estudiante. Cualquier modificación a este contrato deberá ser realizada por escrito y con el consentimiento de ambas partes.</p>
    </div>
    
    <div class="date-footer">
        <p class="text-center mb-10">
            <strong>Fecha de Matrícula:</strong> 
            {{ $matriculacion->fecha_matriculacion->format('d') }} de 
            {{ \Carbon\Carbon::create()->month((int)$matriculacion->fecha_matriculacion->format('m'))->locale('es')->monthName }} de 
            {{ $matriculacion->fecha_matriculacion->format('Y') }}
        </p>
        @if(isset($fecha_generacion))
        <p style="font-size: 8px; color: #666;">
        </p>
        @endif
    </div>
    
    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-line">
                La Institución Educativa<br>
                <strong>_____________________________</strong><br>
                <span style="font-size: 8px;">Representante Legal</span>
            </div>
        </div>
        <div style="display: table-cell; width: 10%;"></div>
        <div class="signature-right">
            <div class="signature-line">
                Padres/Tutores<br>
                <strong>{{ $matriculacion->estudiante->ppff ? $matriculacion->estudiante->ppff->nombre_completo : '_____________________________' }}</strong><br>
                <span style="font-size: 8px;">
                    @if($matriculacion->estudiante->ppff)
                        {{ ucfirst($matriculacion->estudiante->ppff->parentesco) }}
                        @if($matriculacion->estudiante->ppff->ci)
                            - CI: {{ $matriculacion->estudiante->ppff->ci }}
                        @endif
                    @else
                        Padre/Madre/Tutor
                    @endif
                </span>
            </div>
        </div>
    </div>
</body>
</html>