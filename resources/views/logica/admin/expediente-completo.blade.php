<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Informe Médico - Hospital Emmanuel</title>

  {{-- Carga de assets sólo en pantalla --}}
  @if (empty($isPdf))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif

  <style>
    /* =========================================================
    * Variables base (pantalla)
    * =======================================================*/
    :root {
      --radius: 12px;
      --font: 11pt;
      /* control del “gutter” lateral y espacio vertical entre secciones */
      --block-gutter: 12px;
      /* ↑↓ puedes subir/bajar este valor */
      --vspace: 12px;
      /* separación entre secciones */
    }

    /* =========================================================
    * Tamaño de página (imprimir desde navegador)
    * =======================================================*/
    @page {
      size: letter portrait;
      /* también puedes usar: size: 8.5in 11in; */
      margin: 1.2cm;
    }

    /* =========================================================
    * Lienzo blanco centrado
    * =======================================================*/
    body {
      background: #f3f4f6;
      line-height: 1.24;
      margin: 0;
    }

    .print-container {
            max-width: 8in;
            width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: .30cm .30cm;
            box-sizing: border-box;
    }

    /* =========================================================
    * Secciones: separación vertical uniforme
    * =======================================================*/
    .section {
      margin: var(--vspace) auto;
      page-break-inside: auto;
      break-inside: auto;
    }

    .no-split {
      page-break-inside: avoid;
      break-inside: avoid;
    }

    .footer-note {
      text-align: right;
      font-size: 10px;
      color: #6b7280;
    }

    /* =========================================================
    * Encabezado (pantalla) – centrado y con gutter lateral
    * =======================================================*/
    .header-print {
      box-sizing: border-box;
      width: calc(100% - (var(--block-gutter) * 2));
      /* centrado como el resto */
      margin: 0 auto;
      /* ← centra el bloque */
      border: 1px solid #e5e7eb;
      border-radius: var(--radius);
      padding: 6px 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
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
    * Tablas compactas – centradas y con el mismo gutter del header
    * =======================================================*/
    .compact-table {
      width: calc(100% - (var(--block-gutter) * 2));
      /* mismo ancho que el header */
      margin: 0 auto;
      /* centra la tabla dentro del contenedor */
      font-size: 10.2px;
      table-layout: fixed;
      border-collapse: separate !important;
      border-spacing: 0 !important;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      overflow: hidden;
      page-break-inside: auto;
    }

    .compact-table th,
    .compact-table td {
      padding: .18rem .30rem;
      vertical-align: top;
      overflow-wrap: anywhere;
    }

    .compact-table tr+tr td {
      border-top: 1px dashed #ececec;
    }

    .compact-table .block-head th {
      background: #f5f5f5;
      color: #111;
      border-bottom: 1px solid #e5e7eb;
      font-weight: 700;
      text-align: left;
    }

    .compact-table tr {
      page-break-inside: avoid;
    }

    .page-break {
      page-break-before: always;
    }

    /* =========================================================
    * Alturas y utilidades
    * =======================================================*/
    .expand-3x {
      min-height: 9em;
      white-space: pre-wrap;
      overflow: visible;
      line-height: 1.24;
    }

    .pre {
      white-space: pre-wrap;
    }

    /* =========================================================
    * Exámenes en tarjetas (3 columnas)
    * =======================================================*/
    .cat-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      font-size: 0;
    }

    .cat-card {
      flex: 1 1 calc(33.333% - 6px);
      margin: 0;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      padding: 4px 6px;
      page-break-inside: avoid;
      break-inside: avoid;
    }

    .cat-title {
      font-size: 10px;
      font-weight: 700;
      margin: 0 0 3px;
      padding-bottom: 2px;
      border-bottom: 1px dashed #e5e7eb;
    }

    .exam-item {
      font-size: 9.6px;
      margin: 1px 0;
    }

    .exam-item small {
      color: #6b7280;
    }

    /* =========================================================
    * Cuadro de SELLO a la par del médico
    * =======================================================*/
    .seal-cell {
      width: 40%;
      text-align: right;
    }

    .seal-wrap {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .seal-label {
      font-size: 10px;
      font-weight: 700;
    }

    .seal-box {
      height: 1.8cm;
      min-height: 1.8cm;
      width: 6.6cm;
      min-width: 5.6cm;
      border: 1px dashed #9ca3af;
      border-radius: 8px;
    }

    /* =========================================================
    * Estilos al imprimir desde el navegador
    * (mantenemos el mismo centrado/ancho)
    * =======================================================*/
    @media print {
      .no-print, .no-print * {
        display: none !important;
        visibility: hidden !important;
      }

      body {
        background: #fff !important;
        font-size: var(--font);
      }

      .print-container {
        box-shadow: none !important;
      }

      .compact-table {
        width: calc(100% - (var(--block-gutter) * 2));
        margin: 0 auto;
        font-size: 10px;
      }

      .compact-table th, .compact-table td {
        padding: .16rem .26rem;
      }

      .expand-3x {
        min-height: 8.5em;
      }

      .cat-card {
        border-color: #000;
      }

      .cat-title {
        border-bottom: 1px solid #000;
      }

      .exam-item small {
        color: #000;
      }

      .header-print {
        width: calc(100% - (var(--block-gutter) * 2));
        margin: 0 auto;
        border-color: #000;
      }

      .seal-box {
        border-color: #000;
      }


    }

    @page {
      size: letter portrait;
    }

    /* refuerzo para Chrome/Edge */
    body {
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    /* Opcional: fija ancho "real" de la hoja para que no haga escala rara */
    .wrap {
      width: 7.6in;
      margin: 0 auto;
    }

    /* Evitar cortes de paneles entre páginas */
    .panel, .header, .obs {
      break-inside: avoid;
      page-break-inside: avoid;
    }

    /* ======================================================
    * Estilos sólo para PDF (Dompdf)
    * Nota: evitamos variables en width por compatibilidad,
    * usamos 98% + centrado para replicar el gutter.
    * =======================================================*/
    @if (!empty($isPdf))
      * {
        color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      body {
        background: #fff;
        font-size: 10.8pt;
        font-family: "DejaVu Sans", sans-serif;
      }

      /* Encabezado centrado en PDF */
      .header-print {
        display: table;
        width: 98%;
        margin: 0 auto;
        border: 1px solid #000;
        border-radius: 12px;
        box-sizing: border-box;
      }

      .header-cell {
        display: table-cell;
        vertical-align: middle;
      }

      .header-left,
      .header-right {
        width: 20%;
        text-align: center;
      }

            .header-center {
        width: 60%;
        text-align: center;
      }

      .header-print img {
        filter: grayscale(100%) contrast(1.05);
      }

            /* Tablas centradas en PDF */
      .compact-table {
        width: 98%;
        margin: 0 auto;
        border-color: #000;
      }

      .compact-table tr+tr td {
        border-top: 1px solid #000;
      }

      .compact-table .block-head th {
        background: transparent !important;
        border-bottom: 1px solid #000;
      }

      .cat-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
      }

      .cat-card {
        flex: 1 1 calc(33.333% - 6px);
        border-color: #000;
      }

      .cat-title {
        border-bottom: 1px solid #000;
      }

      .pdf-ico {
        height: 12px;
        width: 12px;
        vertical-align: -2px;
        margin-right: 4px;
      }

      .seal-box {
        border-color: #000;
      }
    @endif
    </style>
</head>

@php
    // ==========================================================
    // Recursos de imagen (logos): Data URI en PDF y asset() en pantalla
    // ==========================================================
    $logo1Src = !empty($isPdf) ? $logo1 ?? 'file://' . public_path('images/logo1.png') : asset('images/logo1.png');
    $logo2Src = !empty($isPdf) ? $logo2 ?? 'file://' . public_path('images/logo2.png') : asset('images/logo2.png');
@endphp

<body>
    @php
        // ======================================================
        // Cálculos/preparación de datos
        // ======================================================
        $examenes = $expediente->consulta->examenesMedicos ?? collect();
        $examsCount = $examenes->count();
        $printBackside = $examsCount > 11;

        // Iconos SVG en base64 (sólo para PDF)
        $icons = [];
        if (!empty($isPdf)) {
            $icons = [
                'user' =>
                    'data:image/svg+xml;base64,' .
                    base64_encode(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                    ),
                'vitals' =>
                    'data:image/svg+xml;base64,' .
                    base64_encode(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 12 7 12 10 3 14 21 17 12 21 12"/></svg>',
                    ),
                'note' =>
                    'data:image/svg+xml;base64,' .
                    base64_encode(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V4a2 2 0 0 1 2-2h10"/><path d="M19 5l-5 5"/><path d="M14 5h5v5"/></svg>',
                    ),
                'labs' =>
                    'data:image/svg+xml;base64,' .
                    base64_encode(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3v4L3 17a3 3 0 0 0 3 4h12a3 3 0 0 0 3-4l-6-10V3"/><path d="M9 7h6"/></svg>',
                    ),
                'folder' =>
                    'data:image/svg+xml;base64,' .
                    base64_encode(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h5l2 3h11v9a2 2 0 0 1-2 2H3z"/><path d="M3 7V5a2 2 0 0 1 2-2h4l2 2h6a2 2 0 0 1 2 2v3"/></svg>',
                    ),
                'doctor' =>
                    'data:image/svg+xml;base64,' .
                    base64_encode(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M5.5 22v-2a6.5 6.5 0 0 1 13 0v2"/></svg>',
                    ),
            ];
        }
    @endphp

    {{-- Barra superior (acción rápida) sólo en pantalla --}}
    @if (empty($isPdf))
        <div class="no-print max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">📄 Informe de Expediente Médico</h2>
                <div class="flex gap-2">
                    <button onclick="window.print()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg">🖨️
                        Imprimir Expediente</button>
                    <a href="{{ route('expedientes.examenes.imprimir', $expediente->id_expediente) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        🧪 Imprimir boleta de exámenes
                    </a>
                    <a href="{{ route('expedientes.completo.pdf', $expediente->id_expediente) }}"
                        class="bg-gray-800 hover:bg-black text-white font-semibold py-2 px-4 rounded-lg">⬇️ Descargar
                        PDF</a>
                    <a href="{{ (Auth::user()->role ?? '') === 'admin' ? route('admin.expedientes.index') : route('expedientes.index') }}"
                        class="bg-slate-600 hover:bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg">⬅️
                        Regresar</a>
                </div>
            </div>
        </div>
    @endif

    <div class="print-container">

        {{-- ======================================================
       Encabezado centrado
     ==================================================== --}}
        <div class="header-print section no-split">
            <div class="header-cell header-left"><img src="{{ $logo1Src }}" alt="Logo 1"></div>
            <div class="header-cell header-center">
                <h1>HOSPITAL EMMANUEL · DIOS CON NOSOTROS</h1>
                <div class="sub">Informe de Expediente Médico</div>
                <div class="code">Código de Expediente: <strong>{{ $expediente->codigo }}</strong></div>
                <div class="code">Código de Paciente: <strong>{{ $expediente->paciente->codigo_paciente }}</strong></div>
            </div>
            <div class="header-cell header-right"><img src="{{ $logo2Src }}" alt="Logo 2"></div>
        </div>

        {{-- ======================================================
       Datos del Paciente
     ==================================================== --}}
      <div class="section no-split">
        <table class="compact-table">
          <tr class="block-head">
            <th colspan="3">
              @if (empty($isPdf)) 👤 @else <img class="pdf-ico" src="{{ $icons['user'] }}" alt=""> @endif
              Datos del Paciente
            </th>
          </tr>

          <tr>
            <td><strong>Apellido:</strong> {{ $expediente->paciente->persona->apellido }}</td>
            <td><strong>Nombre:</strong> {{ $expediente->paciente->persona->nombre }}</td>
            <td><strong>Fecha de Nacimiento:</strong> {{ $expediente->paciente->persona->fecha_nacimiento->format('d/m/Y') }} </td>
          </tr>

          <tr>
            <td><strong>Edad:</strong> {{ $expediente->paciente->persona->edad }}</td>
            <td><strong>DNI:</strong> {{ $expediente->paciente->persona->dni }}</td>
            <td><strong>Sexo:</strong> {{ $expediente->paciente->persona->sexo }}</td>
          </tr>

          <tr>
            <td><strong>Dirección:</strong> {{ $expediente->paciente->persona->direccion }}</td>
          </tr>
        </table>
      </div>

        <div class="section no-split">
          <table class="compact-table">
                <tr class="block-head">
                    <th colspan="2">
                        @if (empty($isPdf))
                            👩‍👧‍👦
                        @else
                            <img class="pdf-ico" src="{{ $icons['user'] }}" alt="">
                        @endif
                        Datos del Encargado
                    </th>
                </tr>

                @php 
                  $enc = $expediente->encargado_derivado; 
                @endphp

                @if ($enc && $enc->persona)
                  <tr>
                    <td>
                      <strong>Teléfono del Encargado:</strong> {{ $enc->persona->telefono }}
                    </td>

                    <td>
                      <strong>NombreEncargado:</strong> {{ $enc->persona->nombre }} {{ $enc->persona->apellido }}
                @else
                      <strong>Encargado:</strong> —
                @endif
                    </td>
                  </tr>
            </table>
        </div>

        {{-- ======================================================
       Signos Vitales
     ==================================================== --}}
        <div class="section no-split">
            <table class="compact-table">
                <tr class="block-head">
                    <th colspan="3">
                        @if (empty($isPdf))
                            💓
                        @else
                            <img class="pdf-ico" src="{{ $icons['vitals'] }}" alt="">
                        @endif
                        Signos Vitales
                    </th>
                </tr>
                <tr>
                    <td><strong>Presión Arterial:</strong>
                        {{ optional($expediente->signosVitales)->presion_arterial ?? '—' }}</td>
                    <td><strong>FC:</strong> {{ optional($expediente->signosVitales)->fc ?? '—' }} lpm</td>
                    <td><strong>FR:</strong> {{ optional($expediente->signosVitales)->fr ?? '—' }}</td>
                </tr>
                <tr>
                    <td><strong>Temperatura:</strong> {{ optional($expediente->signosVitales)->temperatura ?? '—' }}
                        &deg;C</td>
                    <td><strong>SO<sub>2</sub>:</strong> {{ optional($expediente->signosVitales)->so2 ?? '—' }} %</td>

                    @php
                        $pesoKg = optional($expediente->signosVitales)->peso;
                        $pesoLb = is_numeric($pesoKg) ? round($pesoKg * 2.2046226218, 2) : null; // kg → lb
                    @endphp

                    <td>
                        <strong>Peso:</strong>
                        @if (!is_null($pesoLb))
                            {{ rtrim(rtrim(number_format($pesoKg, 2, '.', ''), '0'), '.') }} kg
                            ({{ rtrim(rtrim(number_format($pesoLb, 2, '.', ''), '0'), '.') }} lb)
                        @else
                            —
                        @endif
                    </td>

                </tr>
                <tr>
                    <td><strong>Glucosa:</strong> {{ optional($expediente->signosVitales)->glucosa ?? '—' }}</td>
                    <td>
                        <strong>Fecha Registro:</strong>
                        @if (optional($expediente->signosVitales)->fecha_registro)
                            {{ \Carbon\Carbon::parse($expediente->signosVitales->fecha_registro)->format('d/m/Y H:i') }}
                        @else
                            —
                        @endif
                    </td>
                    <td><strong>Enfermera:</strong> {{ $expediente->enfermera->nombre ?? '—' }}
                        {{ $expediente->enfermera->apellido ?? '' }}</td>
                </tr>
            </table>
        </div>

        {{-- ======================================================
       Datos de Ingreso
     ==================================================== --}}
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th colspan="2">
                        @if (empty($isPdf))
                            🗂️
                        @else
                            <img class="pdf-ico" src="{{ $icons['note'] ?? '' }}" alt="">
                        @endif
                        Datos de Ingreso
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>Motivo de Ingreso:</strong><br>
                        <div class="pre">{{ $expediente->motivo_ingreso ?: '—' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Diagnóstico:</strong><br>
                        <div class="pre">{{ $expediente->diagnostico ?: '—' }}</div>
                    </td>
                    <td>
                        <strong>Observaciones:</strong><br>
                        <div class="pre">{{ $expediente->observaciones ?: '—' }}</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ======================================================
       Consulta Médica
     ==================================================== --}}
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th colspan="2">
                        @if (empty($isPdf))
                            📝
                        @else
                            <img class="pdf-ico" src="{{ $icons['note'] }}" alt="">
                        @endif
                        Consulta Médica
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>Resumen Clínico:</strong><br>
                        <div class="expand-3x">{{ $expediente->consulta->resumen_clinico }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Impresión Diagnóstica:</strong><br>
                        <div class="expand-3x">{{ $expediente->consulta->impresion_diagnostica }}</div>
                    </td>
                    <td>
                        <strong>Indicaciones:</strong><br>
                        <div class="expand-3x">{{ $expediente->consulta->indicaciones }}</div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Urgencia:</strong> {{ $expediente->consulta->urgencia }}</td>
                    <td><strong>Tipo de Urgencia:</strong> {{ $expediente->consulta->tipo_urgencia ?: 'Ninguna' }}</td>
                </tr>
                <tr>
                    <td><strong>Resultado:</strong> {{ $expediente->consulta->resultado }}</td>
                    <td>
                        <strong>Citó para:</strong>
                        @if ($expediente->consulta->citado)
                            {{ \Carbon\Carbon::parse($expediente->consulta->citado)->format('d/m/Y') }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        {{-- ======================================================
       Exámenes (3 columnas)
     ==================================================== --}}
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th>
                        @if (empty($isPdf))
                            🔬
                        @else
                            <img class="pdf-ico" src="{{ $icons['labs'] }}" alt="">
                        @endif
                        Exámenes Recetados
                    </th>
                </tr>
                <tr>
                    <td>
                        @php
                            $groupedExams = $examenes
                                ->groupBy(function ($ex) {
                                    return optional($ex->examen->categoria)->nombre_categoria ?? 'Sin categoría';
                                })
                                ->sortKeys();
                        @endphp

                        @if ($groupedExams->isEmpty())
                            <em>No hay exámenes registrados.</em>
                        @else
                            <div class="cat-grid">
                                @foreach ($groupedExams as $cat => $items)
                                    <div class="cat-card">
                                        <div class="cat-title">
                                            @if (empty($isPdf))
                                                📂
                                            @else
                                                <img class="pdf-ico" src="{{ $icons['folder'] }}" alt="">
                                            @endif
                                            {{ $cat }}
                                        </div>
                                        @foreach ($items as $ex)
                                            <div class="exam-item">
                                                • {{ $ex->examen->nombre_examen }}
                                                <small>—
                                                    {{ \Carbon\Carbon::parse($ex->fecha_asignacion)->format('d/m/Y H:i') }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        {{-- ======================================================
       Médico Tratante + Sello (a la par)
     ==================================================== --}}
        <div class="section">
            <table class="compact-table">
                <tr class="block-head">
                    <th colspan="2">
                        @if (empty($isPdf))
                            👨‍⚕️
                        @else
                            <img class="pdf-ico" src="{{ $icons['doctor'] ?? '' }}" alt="">
                        @endif
                        Médico Tratante
                    </th>
                </tr>
                <tr>
                    <td>
                        <strong>Nombre:</strong> {{ $expediente->doctor->nombre ?? '—' }}
                        {{ $expediente->doctor->apellido ?? '' }}<br>
                        <strong>Firma y Sello:</strong> {{ $expediente->consulta->firma_sello ?? '—' }}
                    </td>
                    <td class="seal-cell">
                        <div class="seal-wrap">
                            <span class="seal-label">SELLO</span>
                            <div class="seal-box"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-note">Informe generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    {{-- ======================================================
     Reverso (si hay muchos exámenes)
   ==================================================== --}}
    @if ($examsCount > 11)
        <div class="print-container page-break">
            <div style="text-align:center; margin-bottom:6px;">
                <h3 style="font-size:12px; font-weight:700; margin:0 0 2px;">
                    @if (empty($isPdf))
                        🔬
                    @else
                        <img class="pdf-ico" src="{{ $icons['labs'] }}" alt="">
                    @endif
                    Exámenes Recetados — Reverso
                </h3>
                <div style="font-size:10px;">
                    Paciente: {{ $expediente->paciente->persona->apellido }},
                    {{ $expediente->paciente->persona->nombre }}
                    · Código: <span style="font-weight:600;">{{ $expediente->codigo }}</span>
                </div>
            </div>

            @php
                $groupedExamsAll = $examenes
                    ->groupBy(function ($ex) {
                        return optional($ex->examen->categoria)->nombre_categoria ?? 'Sin categoría';
                    })
                    ->sortKeys();
            @endphp

            @if ($groupedExamsAll->isEmpty())
                <em>No hay exámenes registrados.</em>
            @else
                <div class="cat-grid">
                    @foreach ($groupedExamsAll as $cat => $items)
                        <div class="cat-card">
                            <div class="cat-title">
                                @if (empty($isPdf))
                                    📂
                                @else
                                    <img class="pdf-ico" src="{{ $icons['folder'] }}" alt="">
                                @endif
                                {{ $cat }}
                            </div>
                            @foreach ($items as $ex)
                                <div class="exam-item">
                                    • {{ $ex->examen->nombre_examen }}
                                    <small>—
                                        {{ \Carbon\Carbon::parse($ex->fecha_asignacion)->format('d/m/Y H:i') }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="footer-note">Página reverso — {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    @endif
</body>

</html>
