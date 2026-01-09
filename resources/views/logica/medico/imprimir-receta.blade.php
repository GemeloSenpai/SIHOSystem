<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receta Médica</title>
    <style>
        /* =========================================================
         * Variables y configuración base
         * =======================================================*/
        :root {
            --font: 11.5pt; /* Tamaño de fuente base */
            --block-gutter: 12px; /* Margen lateral para centrar bloques */
            --vspace: 10px; /* Separación vertical entre secciones */
            --radius: 10px; /* Radio de bordes */
        }

        /* =========================================================
         * Control del tamaño de página y contenedor principal[citation:5][citation:7]
         * =======================================================*/
        @page {
            size: A4 portrait; /* Define el tamaño de la hoja como A4 vertical[citation:5] */
            margin: 1.2cm; /* Margen general de la página[citation:7] */
        }

        /* Contenedor principal para hacer la receta más pequeña */
        .receta-container {
            max-width: 16cm; /* Ancho máximo de ~16 cm para no usar toda la hoja */
            margin: 0 auto; /* Centra el contenedor en la página */
            font-size: var(--font);
            line-height: 1.24;
            background: #fff;
            padding: 10px;
            box-sizing: border-box;
        }

        body {
            background: #f3f4f6; /* Fondo gris claro solo para vista en pantalla */
            line-height: 1.24;
            margin: 0;
            font-family: "DejaVu Sans", "Segoe UI", Arial, sans-serif;
        }

        @media print {
            body {
                background: #fff !important; /* Fondo blanco al imprimir */
            }
            .receta-container {
                box-shadow: none !important; /* Sin sombras al imprimir */
            }
            .no-print {
                display: none !important; /* Oculta botones al imprimir */
            }
        }

        /* =========================================================
         * Encabezado con logos, nombre y teléfonos
         * =======================================================*/
        .header-print {
            width: 100%;
            border: 1px solid #000;
            border-radius: var(--radius);
            padding: 6px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
            margin-bottom: var(--vspace);
        }
        .header-print img {
            height: 42px;
            object-fit: contain;
        }
        .header-center {
            text-align: center;
            flex: 1;
        }
        .header-center h1 {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .header-center .sub {
            font-size: 12px;
            font-weight: 700;
            margin-top: 1px;
        }
        .header-center .code {
            font-size: 10.5px;
            margin-top: 2px;
        }

        /* =========================================================
         * Secciones de contenido uniforme
         * =======================================================*/
        .section {
            margin: var(--vspace) auto;
            page-break-inside: avoid; /* Evita que una sección se parta entre páginas[citation:7] */
            break-inside: avoid;
        }

        /* =========================================================
         * Tablas compactas para alinear etiquetas y valores
         * =======================================================*/
        .compact-table {
            width: 100%;
            font-size: 10.2px;
            table-layout: fixed;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border: 1px solid #000;
            border-radius: var(--radius);
            overflow: hidden;
        }
        .compact-table th,
        .compact-table td {
            padding: .18rem .30rem;
            vertical-align: top;
            overflow-wrap: anywhere;
        }
        .compact-table tr+tr td {
            border-top: 1px solid #ececec;
        }
        .compact-table .block-head th {
            background: #f5f5f5;
            color: #111;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 700;
            text-align: left;
        }

        /* =========================================================
         * Formato para texto de prescripción y observaciones
         * =======================================================*/
        .pre {
            white-space: pre-wrap;
        }
        .expand-2x {
            min-height: 5em;
            white-space: pre-wrap;
            overflow: visible;
            line-height: 1.24;
        }

        /* =========================================================
         * Pie de página con firma, sello y datos finales
         * =======================================================*/
        .footer-data {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #9ca3af;
            font-size: 10px;
            color: #6b7280;
        }

        /* =========================================================
         * Estilos para la vista en pantalla (no imprimir)
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
        }

        .close-button {
            padding: 10px 25px;
            background: #666;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-button">🖨 Imprimir Receta</button>
        <button onclick="window.close()" class="close-button">❌ Cerrar</button>
    </div>

    <!-- ======================================================
         Contenedor principal de la receta (más pequeño)
       ==================================================== -->
    <div class="receta-container">

        <!-- Encabezado con logos, nombre y teléfonos -->
        <div class="header-print section">
            <!-- Logo izquierdo -->
            <img src="{{ asset('images/logo1.png') }}" alt="Logo 1">
            <!-- Centro con nombre del dispensario y versículo -->
            <div class="header-center">
                <h1>DISPENSARIO MÉDICO "PARROQUIA EMMANUEL"</h1>
                <div class="sub">(DIOS CON NOSOTROS)</div>
                <div class="code" style="font-style: italic; font-weight: normal;">"Estuve enfermo y me visitaron. Mt. 25-36"</div>
                <div class="section" style="text-align: center; font-size: 10px; color: #555; margin-bottom: 5px;">
                    <strong>Contacto:</strong> Cel: 9260-8444 / 8759-4676
                </div>
            </div>
            <!-- Logo derecho -->
            <img src="{{ asset('images/logo2.png') }}" alt="Logo 2">
        </div>

        <!-- Ubicación y Fecha (en la misma línea) -->
        <div class="section">
            <table class="compact-table">
                <tr>
                    <td style="width: 50%;"><strong>Lugar:</strong> San Manuel, Cortes.</td>
                    <td style="width: 50%;"><strong>Fecha de prescripción:</strong> {{ \Carbon\Carbon::parse($receta->fecha_prescripcion)->format('d/m/Y') }}</td>
                    <td style="width: 50%;"><strong>Codigo de Expediente:</strong> {{$receta->expediente->codigo}}</td>
                </tr>
            </table>
        </div>

        <!-- Datos del Paciente: Nombre, Edad, Sexo -->
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th colspan="2">📋 Datos del Paciente</th>
                </tr>
                <tr>
                    <td style="width: 60%;"><strong>Nombre:</strong> {{ $receta->expediente->paciente->persona->nombre }} {{ $receta->expediente->paciente->persona->apellido }}</td>
                    <td style="width: 40%;">
                        <strong>Edad:</strong> {{ $receta->edad_paciente_en_receta }} años &nbsp;|&nbsp;
                        <strong>Sexo:</strong> {{ $receta->expediente->paciente->persona->genero == 'M' ? 'M' : 'F' }} &nbsp;|&nbsp;
                        <strong>Codigo de Paciente:</strong> {{ $receta->expediente->paciente->codigo_paciente}}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Diagnóstico 
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th>🩺 Diagnóstico</th>
                </tr>
                <tr>
                    <td><div class="pre">{{ $receta->diagnostico }}</div></td>
                </tr>
            </table>
        </div>
        -->

        <!-- Alergias (si existen) -->
        @if($receta->alergias_conocidas)
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th>⚠️ Alergias Conocidas</th>
                </tr>
                <tr>
                    <td><div class="pre">{{ $receta->alergias_conocidas }}</div></td>
                </tr>
            </table>
        </div>
        @endif

        <!-- Prescripción Médica -->
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th>💊 Prescripción Médica</th>
                </tr>
                <tr>
                    <td><div class="expand-2x pre">{{ $receta->receta }}</div></td>
                </tr>
            </table>
        </div>

        <!-- Observaciones (si existen) -->
        @if($receta->observaciones)
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th>📝 Observaciones Adicionales</th>
                </tr>
                <tr>
                    <td><div class="pre">{{ $receta->observaciones }}</div></td>
                </tr>
            </table>
        </div>
        @endif

        <!-- Doctor que atendió y espacio para firma/sello -->
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th>👨‍⚕️ Médico Tratante</th>
                </tr>
                <tr>
                    <td>
                        <strong>Doctor(a):</strong> Dr. {{ $receta->doctor->nombre }} {{ $receta->doctor->apellido }}<br>
                        <strong>Especialidad:</strong> {{ $receta->doctor->especialidad ?? 'Médico General' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 25px; text-align: center;">
                        <div style="border-top: 1px solid #000; width: 250px; margin: 0 auto; padding-top: 5px;">
                            <strong>Firma y Sello del Médico</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Datos finales de la receta -->
        <div class="footer-data">
            <div style="text-align: center;">
                <strong>Estado de la receta:</strong> {{ ucfirst($receta->estado) }} &nbsp;|&nbsp;
                <strong>Válida por 30 días</strong> &nbsp;|&nbsp;
                <strong>Código:</strong> REC-{{ str_pad($receta->id_receta, 6, '0', STR_PAD_LEFT) }}
            </div>
            <div style="text-align: right; margin-top: 5px;">
                Documento generado el: {{ date('d/m/Y H:i') }}
            </div>
        </div>

    </div>
    <!-- Fin del contenedor receta-container -->
</body>
</html>