<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Exámenes - Laboratorio Clínico Emmanuel</title>
    <style>
        /* =========================================================
         * Variables y configuración base
         * =======================================================*/
        :root {
            --font: 11.5pt;
            --block-gutter: 12px;
            --vspace: 10px;
            --radius: 10px;
            --border-color: #000;
            --header-bg: #f8fafc;
        }

        /* =========================================================
         * Control del tamaño de página
         * =======================================================*/
        @page {
            size: A4 portrait;
            margin: 1.2cm;
        }

        /* Contenedor principal */
        .boleta-container {
            max-width: 18cm;
            margin: 0 auto;
            font-size: var(--font);
            line-height: 1.24;
            background: #fff;
            padding: 10px;
            box-sizing: border-box;
        }

        body {
            background: #f3f4f6;
            line-height: 1.24;
            margin: 0;
            font-family: "DejaVu Sans", "Segoe UI", Arial, sans-serif;
        }

        @media print {
            body {
                background: #fff !important;
            }

            .boleta-container {
                box-shadow: none !important;
            }

            .no-print {
                display: none !important;
            }
        }

        /* =========================================================
         * Encabezado con logos y datos
         * =======================================================*/
        .header-boleta {
            width: 100%;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            padding: 10px 15px;
            box-sizing: border-box;
            margin-bottom: var(--vspace);
            background: var(--header-bg);
        }

        .header-title {
            text-align: center;
            margin-bottom: 8px;
        }

        .header-title h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            color: #000;
        }

        .header-subtitle {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 5px;
        }

        .header-contact {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* =========================================================
         * Tablas compactas para datos
         * =======================================================*/
        .compact-table {
            width: 100%;
            font-size: 10.2px;
            table-layout: fixed;
            border-collapse: collapse !important;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            overflow: hidden;
            margin: var(--vspace) 0;
        }

        .compact-table th,
        .compact-table td {
            padding: .25rem .35rem;
            vertical-align: top;
            overflow-wrap: anywhere;
            border: 1px solid var(--border-color);
        }

        .compact-table .block-head th {
            background: #e5e7eb;
            color: #111;
            font-weight: 700;
            text-align: center;
            padding: .35rem;
            font-size: 10.5px;
        }

        .compact-table .sub-head th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
            text-align: left;
            padding: .3rem .35rem;
            font-size: 10px;
        }

        /* =========================================================
         * Paneles de categorías de exámenes
         * =======================================================*/
        .categorias-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin: 12px 0;
        }

        .categoria-panel {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 10px;
            min-height: 140px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .categoria-panel h3 {
            margin: 0 0 6px 0;
            font-size: 10.5px;
            text-transform: uppercase;
            font-weight: 700;
            color: #111;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 3px;
        }

        .examenes-list {
            margin: 0;
            padding-left: 16px;
            font-size: 10px;
            line-height: 1.3;
        }

        .examenes-list li {
            margin-bottom: 2px;
        }

        /* =========================================================
         * Información de paciente y médico
         * =======================================================*/
        .info-box {
            background: #f8fafc;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 10px;
            margin: 5px 0;
        }

        .info-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .info-value {
            font-size: 11px;
            font-weight: 600;
            color: #111;
        }

        /* =========================================================
         * Sección de observaciones
         * =======================================================*/
        .observaciones-section {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 12px;
            margin: 15px 0;
            min-height: 100px;
        }

        .observaciones-title {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #111;
            margin-bottom: 8px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
        }

        /* =========================================================
         * Pie de página
         * =======================================================*/
        .footer-boleta {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #9ca3af;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }

        /* =========================================================
         * Botones para pantalla
         * =======================================================*/
        .no-print {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f0f0f0;
            border-bottom: 2px solid #ccc;
        }

        .print-button {
            padding: 10px 25px;
            background: #2c5282;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
            font-weight: 600;
        }

        .back-button {
            padding: 10px 25px;
            background: #666;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
            font-weight: 600;
        }

        /* =========================================================
         * Grid responsive
         * =======================================================*/
        @media (max-width: 768px) {
            .categorias-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .categorias-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    @php
        // Datos del expediente (vienen del controlador)
        $idExpediente = $expediente->id_expediente ?? 'N/A';
        $codigoExpediente = $expediente->codigo ?? 'N/A';
        
        // Datos del paciente
        $nombrePaciente = $paciente->nombre ?? '';
        $apellidoPaciente = $paciente->apellido ?? '';
        $telefono = $paciente->telefono ?? '';
        
        // Código del paciente
        $codigoPaciente = $expediente->paciente->codigo_paciente ?? 'N/A';
        
        // Validar datos
        $nombreCompleto = trim($nombrePaciente . ' ' . $apellidoPaciente);
        if (empty($nombreCompleto)) {
            $nombreCompleto = '—';
        }
        
        if (empty($telefono) || $telefono == 'No registrado') {
            $telefono = '—';
        }
        
        if (empty($edad) || $edad == 'N/A') {
            $edad = '—';
        }
        
        // Función para formatear fecha
        function formatearFecha($fecha) {
            if (empty($fecha) || $fecha == 'N/A') {
                return '—';
            }
            try {
                return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
            } catch (\Exception $e) {
                return '—';
            }
        }
        
        $fechaNacimientoFormateada = formatearFecha($fecha_nacimiento ?? null);
        
        // Médico
        $medicoNombre = $medicoNombre ?? '—';
        if (empty(trim($medicoNombre)) || $medicoNombre == 'No asignado') {
            $medicoNombre = '—';
        }
        
        // Fecha de solicitud
        $fechaPrimera = $fechaPrimera ?? now()->format('d/m/Y');
    @endphp

    <div class="no-print">
        <button onclick="window.print()" class="print-button">🖨️ Imprimir Boleta</button>
        <a href="{{ url()->previous() }}" class="back-button">⬅️ Volver</a>
    </div>

    <div class="boleta-container">
        <div class="header-boleta">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo 1" style="height: 45px; object-fit: contain;">
                <div style="text-align: center; flex: 1;">
                    <h1 style="margin: 0; font-size: 16px; font-weight: 800; text-transform: uppercase;">
                        LABORATORIO CLÍNICO EMMANUEL
                    </h1>
                    <div style="font-size: 11px; color: #555; margin-top: 2px;">Servicios de Diagnóstico Clínico</div>
                    <div style="font-size: 10px; color: #666; font-style: italic; margin-top: 2px;">
                        "Precisión y Confianza en Cada Resultado"
                    </div>
                </div>
                <img src="{{ asset('images/logo2.png') }}" alt="Logo 2" style="height: 45px; object-fit: contain;">
            </div>

            <div style="text-align: center; font-size: 9.5px; color: #555; margin-bottom: 8px;">
                <strong>📍 Dirección:</strong> 1ra. Calle, Barrio El Centro, San Manuel, Cortés ·
                <strong>📞 Teléfonos:</strong> 2650-1311 / 1290 ·
                <strong>⏰ Horario:</strong> Lunes a Sábado 7:00 a.m. – 12:00 m.
            </div>

            <!-- Información básica del paciente -->
            <table class="compact-table">
                <tr class="block-head">
                    <th colspan="5">📋 INFORMACIÓN DEL PACIENTE</th>
                </tr>
                <tr>
                    <td style="width: 25%;">
                        <div class="info-label">Paciente</div>
                        <div class="info-value">{{ $nombreCompleto }}</div>
                    </td>
                    <td style="width: 15%;">
                        <div class="info-label">Edad</div>
                        <div class="info-value">{{ $edad }} años</div>
                    </td>
                    <td style="width: 15%;">
                        <div class="info-label">Teléfono</div>
                        <div class="info-value">{{ $telefono }}</div>
                    </td>
                    <td style="width: 15%;">
                        <div class="info-label">Fecha Solicitud</div>
                        <div class="info-value">{{ $fechaPrimera }}</div>
                    </td>
                    <td style="width: 15%;">
                        <div class="info-label">Cod. Expediente</div>
                        <div class="info-value">{{ $codigoExpediente }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">
                        <div class="info-label">Médico Solicitante</div>
                        <div class="info-value">{{ $medicoNombre }}</div>
                    </td>
                    <td style="width: 20%;">
                        <div class="info-label">Fecha Nacimiento</div>
                        <div class="info-value">{{ $fechaNacimientoFormateada }}</div>
                    </td>
                    <td style="width: 20%;">
                        <div class="info-label">Cod. Paciente</div>
                        <div class="info-value">{{ $codigoPaciente }}</div>
                    </td>
                    <td colspan="2">
                        <!-- Espacio adicional -->
                    </td>
                </tr>
            </table>
        </div>

        <!-- Exámenes solicitados -->
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th>🔬 EXÁMENES SOLICITADOS</th>
                </tr>
            </table>
        </div>

        <!-- Categorías de exámenes -->
        @php
            // Orden visual de categorías
            $orden = [
                'Hematología y Coagulación',
                'Química Clínica',
                'Uroanálisis Parasitología',
                'Microbiología',
                'Inmunología',
                'Pruebas Hormonales',
                'Pruebas Especiales',
                'Toxicología y Fármacos',
                'Pruebas de Orina 24 horas',
                'Misceláneas',
            ];

            // Reconstruir arreglo en el orden deseado
            $categoriasOrdenadas = [];
            if (isset($porCategorias) && !empty($porCategorias)) {
                foreach ($orden as $cat) {
                    if (!empty($porCategorias[$cat] ?? [])) {
                        $categoriasOrdenadas[$cat] = $porCategorias[$cat];
                    }
                }

                // Agregar cualquier categoría no contemplada
                foreach ($porCategorias as $cat => $items) {
                    if (!isset($categoriasOrdenadas[$cat])) {
                        $categoriasOrdenadas[$cat] = $items;
                    }
                }
            }

            // Solo mostrar si hay categorías con exámenes
            $categoriasConExamenes = array_filter($categoriasOrdenadas, function($items) {
                return !empty($items);
            });
        @endphp

        @if (!empty($categoriasConExamenes))
            @php
                $chunks = array_chunk($categoriasConExamenes, 3, true);
            @endphp
            
            @foreach ($chunks as $fila)
                <div class="categorias-grid">
                    @foreach ($fila as $cat => $items)
                        @if (!empty($items))
                            <div class="categoria-panel">
                                <h3>{{ $cat }}</h3>
                                <ul class="examenes-list">
                                    @foreach ($items as $it)
                                        <li>{{ $it }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach

                    {{-- Completar columnas si la última fila trae menos de 3 --}}
                    @php
                        $countValid = 0;
                        foreach ($fila as $items) {
                            if (!empty($items)) {
                                $countValid++;
                            }
                        }
                    @endphp
                    
                    @for ($i = $countValid; $i < 3; $i++)
                        <div class="categoria-panel" style="border-style: dashed; opacity: 0.5;">
                            <h3>—</h3>
                            <div style="font-size: 9px; color: #9ca3af; text-align: center; margin-top: 20px;">
                                Sin exámenes
                            </div>
                        </div>
                    @endfor
                </div>
            @endforeach
        @else
            <div class="categorias-grid">
                <div class="categoria-panel" style="border-style: dashed; opacity: 0.5;">
                    <h3>Sin exámenes</h3>
                    <div style="font-size: 9px; color: #9ca3af; text-align: center; margin-top: 20px;">
                        No hay exámenes solicitados
                    </div>
                </div>
                @for ($i = 1; $i < 3; $i++)
                    <div class="categoria-panel" style="border-style: dashed; opacity: 0.5;">
                        <h3>—</h3>
                        <div style="font-size: 9px; color: #9ca3af; text-align: center; margin-top: 20px;">
                            Sin exámenes
                        </div>
                    </div>
                @endfor
            </div>
        @endif

        <!-- Observaciones
        <div class="observaciones-section">
            <div class="observaciones-title">OBSERVACIONES / INDICACIONES ESPECIALES</div>
            <div style="min-height: 80px; font-size: 10px; color: #6b7280;">
                <!-- Espacio para observaciones escritas a mano
            </div>
        </div>

         -->

        <div class="footer-boleta">
            <div style="margin-top: 5px;">
                Boleta generada el {{ now()->format('d/m/Y H:i') }} · Código: 
                BOL-{{ str_pad($idExpediente, 6, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    </div>

    <script>
        // Ajustar altura de los paneles para mejor presentación
        document.addEventListener('DOMContentLoaded', function() {
            const panels = document.querySelectorAll('.categoria-panel:not([style*="dashed"])');
            if (panels.length > 0) {
                let maxHeight = 0;
                panels.forEach(panel => {
                    const height = panel.offsetHeight;
                    if (height > maxHeight) {
                        maxHeight = height;
                    }
                });
                panels.forEach(panel => {
                    panel.style.minHeight = (maxHeight + 20) + 'px';
                });
            }
        });
    </script>
</body>
</html>