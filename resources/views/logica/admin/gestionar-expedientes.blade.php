<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Gestión de Expedientes
        </h2>
    </x-slot>

    {{-- Estilos responsivos optimizados para móviles --}}
    <style>
        /* Contenedor principal responsive */
        .container-expedientes {
            width: 100%;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        @media (min-width: 640px) {
            .container-expedientes {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        @media (min-width: 1024px) {
            .container-expedientes {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        /* Header responsive */
        .header-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .header-buttons a,
        .header-buttons button {
            min-height: 44px;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            flex: 1 0 auto;
        }

        @media (min-width: 640px) {

            .header-buttons a,
            .header-buttons button {
                flex: none;
                width: auto;
            }
        }

        /* ===== TABLA RESPONSIVE ===== */
        /* Contenedor de tabla */
        .table-wrapper-expedientes {
            width: 100%;
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        /* Contenedor de scroll horizontal */
        .table-scroll-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #6366f1 #f1f5f9;
        }

        .table-scroll-container::-webkit-scrollbar {
            height: 6px;
            background: #f1f5f9;
        }

        .table-scroll-container::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 4px;
        }

        .table-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #4f46e5;
        }

        /* Indicador visual de scroll horizontal (solo móvil) */
        .scroll-hint-expedientes {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(99, 102, 241, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            z-index: 20;
            pointer-events: none;
            animation: pulse 2s infinite;
            box-shadow: 0 2px 10px rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        @media (min-width: 768px) {
            .scroll-hint-expedientes {
                display: none;
            }
        }

        /* Tabla responsive */
        .responsive-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        /* Cabecera */
        .responsive-table thead {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .responsive-table thead th {
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 12px 8px;
            border-bottom: 2px solid #4f46e5;
            white-space: nowrap;
        }

        /* Filas */
        .responsive-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }

        .responsive-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .responsive-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Celdas */
        .responsive-table td {
            padding: 10px 8px;
            vertical-align: middle;
        }

        /* Badges y estados */
        .id-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #e0e7ff;
            color: #4f46e5;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .sexo-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .sexo-masculino {
            background: #dbeafe;
            color: #1e40af;
        }

        .sexo-femenino {
            background: #fce7f3;
            color: #be185d;
        }

        .count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            background: #e0e7ff;
            color: #4f46e5;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Botones de acción */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            min-width: 70px;
            min-height: 32px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .btn-ver {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .btn-imprimir {
            background: #cffafe;
            color: #155e75;
            border: 1px solid #a5f3fc;
        }

        .btn-editar {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Botones específicos para laboratorio */
        .btn-examenes-lab {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            color: white;
            border: none;
        }

        .btn-imprimir-lab {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
        }

        /* Header de tabla */
        .table-header-expedientes {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-header-expedientes h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-header-expedientes .total-badge {
            background: #6366f1;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Footer informativo */
        .table-footer-expedientes {
            padding: 12px 16px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 0.75rem;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination-info .page-badge {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 4px 12px;
            font-weight: 600;
            color: #475569;
        }

        /* Buscador responsive */
        .search-container {
            max-width: 42rem;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 1rem;
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
            width: 100%;
        }

        @media (max-width: 640px) {
            .search-form {
                flex-direction: column;
            }

            .search-form input {
                width: 100%;
            }

            .search-form button,
            .search-form a {
                width: 100%;
            }
        }

        .search-input {
            flex: 1;
            border-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            background: white;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .search-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            background: #e2e8f0;
            color: #475569;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .search-btn:hover {
            background: #cbd5e1;
        }

        .search-clear {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .search-clear:hover {
            background: #e2e8f0;
        }

        /* Resultados summary */
        .results-summary {
            max-width: 42rem;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            border-radius: 0.75rem;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 1rem;
            font-size: 0.875rem;
            color: #475569;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .summary-card .total-results {
            font-weight: 600;
            color: #1e293b;
        }

        .summary-card .pagination-stats {
            font-weight: 600;
            color: #6366f1;
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 640px) {
            .container-expedientes {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            .responsive-table {
                min-width: 850px;
                font-size: 0.75rem;
            }

            .responsive-table th,
            .responsive-table td {
                padding: 8px 6px;
            }

            .id-badge {
                width: 28px;
                height: 28px;
                font-size: 0.7rem;
            }

            .action-btn {
                padding: 5px 10px;
                font-size: 0.7rem;
                min-width: 65px;
                min-height: 28px;
            }

            .table-header-expedientes {
                padding: 12px;
            }

            .table-header-expedientes h2 {
                font-size: 1rem;
            }

            .table-footer-expedientes {
                padding: 10px;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .pagination-info {
                justify-content: space-between;
            }

            .summary-card {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
                padding: 0.75rem;
            }
        }

        @media (max-width: 460px) {
            .responsive-table {
                min-width: 800px;
                font-size: 0.7rem;
            }

            .responsive-table th,
            .responsive-table td {
                padding: 6px 4px;
            }

            .id-badge {
                width: 24px;
                height: 24px;
                font-size: 0.65rem;
            }

            .action-btn {
                padding: 4px 8px;
                font-size: 0.65rem;
                min-width: 60px;
                min-height: 26px;
            }

            .table-header-expedientes h2 {
                font-size: 0.875rem;
            }

            .table-header-expedientes .total-badge {
                font-size: 0.75rem;
                padding: 3px 10px;
            }
        }

        @media (max-width: 360px) {
            .responsive-table {
                min-width: 750px;
                font-size: 0.65rem;
            }

            .responsive-table th,
            .responsive-table td {
                padding: 5px 3px;
            }

            .id-badge {
                width: 22px;
                height: 22px;
                font-size: 0.6rem;
            }

            .action-btn {
                padding: 3px 6px;
                font-size: 0.6rem;
                min-width: 55px;
                min-height: 24px;
            }

            .search-input {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }
        }

        /* Mensaje de vacío */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
        }

        .empty-state svg {
            width: 3rem;
            height: 3rem;
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        .empty-state p {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Modal responsive */
        @media (max-width: 768px) {
            .modal-content {
                max-height: 80vh;
                margin: 0.5rem;
            }
        }
    </style>

    @php
        // Obtener el rol del usuario actual
        $user = auth()->user();
        $rol = $user->role;
        $esLaboratorio = $rol === 'laboratorio';
        $esAdmin = $rol === 'admin';
        $esMedico = $rol === 'medico';
        $esRecepcionista = $rol === 'recepcionista';
        $esEnfermero = $rol === 'enfermero';
    @endphp

    <div class="container-expedientes">
        {{-- Buscador (GET, preserva paginación) --}}
        <div class="search-container">
            <form method="GET" action="{{ route('expedientes.index') }}" id="formBuscar" class="search-form">
                <input type="text" name="buscar" id="buscar" value="{{ $buscar ?? request('buscar') }}"
                    placeholder="Buscar por nombre, apellido, DNI o código de expediente..." class="search-input"
                    autocomplete="off" autocapitalize="none" spellcheck="false" autofocus />
                @if (request('buscar'))
                    <a href="{{ route('expedientes.index') }}" class="search-clear">
                        Limpiar
                    </a>
                @else
                    <button type="submit" class="search-btn">
                        Buscar
                    </button>
                @endif
            </form>
            <p class="mt-2 text-[12px] text-slate-500 text-center">
                Escribe y presiona <span class="font-semibold">Enter</span> o haz clic en <span
                    class="font-semibold">Buscar</span>.
            </p>
        </div>

        {{-- Resumen de resultados --}}
        <div class="results-summary">
            <div class="summary-card">
                <div class="total-results">
                    Resultados: {{ $pacientes->total() }}
                    @if ($buscar)
                        <span class="ml-2 text-slate-500">para " <span class="italic">{{ $buscar }}</span>
                            "</span>
                    @endif
                </div>
                <div class="pagination-stats">
                    Página <span class="font-semibold">{{ $pacientes->currentPage() }}</span>
                    de <span class="font-semibold">{{ $pacientes->lastPage() }}</span>
                </div>
            </div>
        </div>

        {{-- Tabla Pacientes --}}
        <div class="table-wrapper-expedientes">
            {{-- Header de la tabla --}}
            <div class="table-header-expedientes">
                <h2>
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Lista de Pacientes
                </h2>
                <span class="total-badge">Total: {{ $pacientes->total() }}</span>
            </div>

            {{-- Indicador de scroll (solo móvil) --}}
            <div class="scroll-hint-expedientes">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
                Desliza
            </div>

            {{-- Tabla con scroll horizontal --}}
            <div class="table-scroll-container">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>F. Nacimiento</th>
                            <th>DNI</th>
                            <th>Sexo</th>
                            <th># Expedientes</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pacientes as $paciente)
                            <tr>
                                <td class="text-center">
                                    <span class="id-badge">{{ $paciente->id_paciente }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="id-badge">{{ $paciente->codigo_paciente }}</span>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-800">
                                        {{ $paciente->persona->nombre }} {{ $paciente->persona->apellido }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1 truncate" style="max-width: 200px;">
                                        {{ $paciente->persona->direccion }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="count-badge">{{ $paciente->persona->edad }}</span>
                                </td>
                                <td>
                                    @if ($paciente->persona->fecha_nacimiento)
                                        <span
                                            class="bg-blue-50 text-blue-700 rounded-md px-2 py-1 text-sm inline-block">
                                            {{ \Carbon\Carbon::parse($paciente->persona->fecha_nacimiento)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic text-sm">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="count-badge">{{ $paciente->persona->dni }}</span>
                                </td>
                                <td>
                                    @if ($paciente->persona->sexo === 'M')
                                        <span class="sexo-badge sexo-masculino">Masculino</span>
                                    @elseif($paciente->persona->sexo === 'F')
                                        <span class="sexo-badge sexo-femenino">Femenino</span>
                                    @else
                                        <span class="count-badge">{{ $paciente->persona->sexo }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="count-badge">{{ $paciente->expedientes->count() }}</span>
                                </td>


                                <td class="text-center">

                                    <div class="action-buttons">
                                        {{-- LABORATORIO: Solo puede ver exámenes e imprimir --}}
                                        @if($esLaboratorio)
                                            {{-- Botón para ver exámenes (laboratorio) --}}
                                            <button onclick="openPatientModal('{{ $paciente->id_paciente }}','view')"
                                                class="action-btn btn-examenes-lab">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                Ver Exámenes
                                            </button>

                                            {{-- Botón para imprimir exámenes (laboratorio) --}}
                                            <button onclick="openPatientModal('{{ $paciente->id_paciente }}','print')"
                                                class="action-btn btn-imprimir-lab">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                    </path>
                                                </svg>
                                                Imprimir Exámenes
                                            </button>
                                        
                                        {{-- ADMIN y MÉDICO: Pueden ver todo --}}
                                        @elseif($esAdmin || $esMedico)
                                            {{-- Abre modal de VER --}}
                                            <button onclick="openPatientModal('{{ $paciente->id_paciente }}','view')"
                                                class="action-btn btn-ver">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Ver
                                            </button>

                                            {{-- Abre modal de IMPRIMIR --}}
                                            <button onclick="openPatientModal('{{ $paciente->id_paciente }}','print')"
                                                class="action-btn btn-imprimir">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                    </path>
                                                </svg>
                                                Imprimir
                                            </button>

                                            {{-- Abre modal para seleccionar expediente a editar --}}
                                            <button onclick="openPatientModal('{{ $paciente->id_paciente }}','pick-edit')"
                                                class="action-btn btn-editar">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Editar
                                            </button>
                                        
                                        {{-- RECEPCIONISTA y ENFERMERO: Solo pueden ver --}}
                                        @elseif($esRecepcionista || $esEnfermero)
                                            {{-- Solo pueden ver --}}
                                            <button onclick="openPatientModal('{{ $paciente->id_paciente }}','view')"
                                                class="action-btn btn-ver">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Ver
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        <p class="text-slate-600">No se encontraron pacientes.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer informativo --}}
            <div class="table-footer-expedientes">
                <div class="pagination-info">
                    <span class="text-slate-600 font-medium">Total:</span>
                    <span class="page-badge">{{ $pacientes->total() }} pacientes</span>
                </div>
                <div class="text-slate-600 text-sm">
                    Mostrando {{ $pacientes->firstItem() }} - {{ $pacientes->lastItem() }} de
                    {{ $pacientes->total() }}
                </div>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $pacientes->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- MODALES --}}
    @foreach ($pacientes as $paciente)
        @php
            $expedientesOrdenados = $paciente->expedientes->sortByDesc('fecha_creacion');
        @endphp

        {{-- (1) Modal: Ver --}}
        <div id="modal-list-{{ $paciente->id_paciente }}" class="js-modal fixed inset-0 z-50 hidden" role="dialog"
            aria-modal="true">
            <div class="js-modal-overlay absolute inset-0 bg-slate-900/0 transition-opacity duration-300"></div>

            <div class="flex items-center justify-center min-h-full p-4">
                <div
                    class="js-modal-panel relative w-full max-w-7xl bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 max-h-[85vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0">
                    <div class="p-4 sm:p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-slate-800">
                                @if($esLaboratorio)
                                    🧪 Exámenes de Laboratorio de:
                                @else
                                    🗂️ Expedientes de:
                                @endif
                                {{ $paciente->persona->nombre }}
                                {{ $paciente->persona->apellido }}
                            </h2>
                            <button
                                class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm hover:bg-slate-200 transition-colors"
                                onclick="closeAllModals()">Cerrar</button>
                        </div>

                        @if ($expedientesOrdenados->isEmpty())
                            <div class="empty-state">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="text-slate-600">No hay expedientes registrados.</p>
                            </div>
                        @else
                            <div class="table-wrapper-expedientes">
                                <div class="table-scroll-container">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                                <th>Doctor</th>
                                                <th class="text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expedientesOrdenados as $exp)
                                                @php
                                                    $receta = $exp->receta;
                                                    $user = auth()->user();
                                                    $crearRoute =
                                                        $user->role === 'admin'
                                                            ? route('admin.recetas.crear', $exp->id_expediente)
                                                            : route('medico.recetas.crear', $exp->id_expediente);
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <span class="count-badge">{{ $exp->codigo }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="bg-purple-50 text-purple-700 rounded-md px-2 py-1 text-sm inline-block">
                                                            {{ \Carbon\Carbon::parse($exp->fecha_creacion)->format('d/m/Y H:i') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $estadoColor = match ($exp->estado) {
                                                                'activo' => 'bg-green-50 text-green-700',
                                                                'inactivo' => 'bg-red-50 text-red-700',
                                                                'pendiente' => 'bg-amber-50 text-amber-700',
                                                                'cerrado' => 'bg-slate-50 text-slate-700',
                                                                default => 'bg-slate-100 text-slate-700',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="{{ $estadoColor }} rounded-md px-2 py-1 text-sm inline-block capitalize">
                                                            {{ ucfirst($exp->estado) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="bg-blue-50 text-blue-700 rounded-md px-2 py-1 text-sm inline-block">
                                                            {{ $exp->doctor->nombre ?? '—' }}
                                                            {{ $exp->doctor->apellido ?? '' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="action-buttons">
                                                            {{-- Ver Expediente (NO para laboratorio) --}}
                                                            @if(!$esLaboratorio)
                                                            <a href="{{ route('expedientes.leer', $exp->id_expediente) }}"
                                                                class="action-btn btn-ver">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                    </path>
                                                                </svg>
                                                                Expediente
                                                            </a>
                                                            @endif

                                                            {{-- Ver Exámenes (para todos los roles que pueden ver expedientes) --}}
                                                            @if($esAdmin || $esMedico || $esLaboratorio)
                                                                @php
                                                                    if ($esAdmin || $esMedico || $esLaboratorio) {
                                                                        $verExamenesRoute = route(
                                                                            'admin.vista-ver-examenes',
                                                                            $exp->id_expediente,
                                                                        );
                                                                    } else {
                                                                        $verExamenesRoute = null;
                                                                    }
                                                                @endphp

                                                                @if ($verExamenesRoute)
                                                                    <a href="{{ $verExamenesRoute }}"
                                                                        class="action-btn {{ $esLaboratorio ? 'btn-examenes-lab' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                                                        <svg class="w-3 h-3" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                            </path>
                                                                        </svg>
                                                                        @if($esLaboratorio)
                                                                            Ver Exámenes
                                                                        @else
                                                                            Exámenes
                                                                        @endif
                                                                    </a>
                                                                @endif
                                                            @endif

                                                            {{-- Acciones de Receta (NO para laboratorio) --}}
                                                            @if(!$esLaboratorio)
                                                                @if ($receta)
                                                                    @php
                                                                        if ($esAdmin) {
                                                                            $verRoute = route(
                                                                                'admin.recetas.ver',
                                                                                $receta->id_receta,
                                                                            );
                                                                        } elseif ($esMedico) {
                                                                            $verRoute = route(
                                                                                'medico.recetas.ver',
                                                                                $receta->id_receta,
                                                                            );
                                                                        }
                                                                    @endphp

                                                                    {{-- Ver Receta --}}
                                                                    <a href="{{ $verRoute }}"
                                                                        class="action-btn bg-blue-50 text-blue-700 border-blue-200">
                                                                        <svg class="w-3 h-3" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                            </path>
                                                                        </svg>
                                                                        Receta
                                                                    </a>
                                                                @else
                                                                    {{-- Crear Receta --}}
                                                                    <a href="{{ $crearRoute }}"
                                                                        class="action-btn bg-emerald-50 text-emerald-700 border-emerald-200">
                                                                        <svg class="w-3 h-3" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                                            </path>
                                                                        </svg>
                                                                        Crear Receta
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- (2) Modal: Elegir expediente a editar (SOLO para admin y médico) --}}
        @if(!$esLaboratorio && ($esAdmin || $esMedico))
        <div id="modal-pick-{{ $paciente->id_paciente }}" class="js-modal fixed inset-0 z-50 hidden p-4"
            role="dialog" aria-modal="true">
            <div class="js-modal-overlay absolute inset-0 bg-slate-900/0 transition-opacity duration-300"></div>

            <div class="flex items-center justify-center min-h-full p-4">
                <div
                    class="js-modal-panel relative w-full max-w-7xl bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 max-h-[85vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0">
                    <div class="p-4 sm:p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-slate-800">
                                ✏️ Selecciona un expediente de <em>"{{ $paciente->persona->nombre }}
                                    {{ $paciente->persona->apellido }}" </em>
                            </h2>
                            <button
                                class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm hover:bg-slate-200 transition-colors"
                                onclick="closeAllModals()">Cerrar</button>
                        </div>

                        @if ($expedientesOrdenados->isEmpty())
                            <div class="empty-state">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="text-slate-600">No hay expedientes registrados.</p>
                            </div>
                        @else
                            <div class="table-wrapper-expedientes">
                                <div class="table-scroll-container">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                                <th>Doctor</th>
                                                <th class="text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expedientesOrdenados as $exp)
                                                <tr>
                                                    <td>
                                                        <span class="count-badge">{{ $exp->codigo }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="bg-purple-50 text-purple-700 rounded-md px-2 py-1 text-sm inline-block">
                                                            {{ \Carbon\Carbon::parse($exp->fecha_creacion)->format('d/m/Y H:i') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $estadoColor = match ($exp->estado) {
                                                                'activo' => 'bg-green-50 text-green-700',
                                                                'inactivo' => 'bg-red-50 text-red-700',
                                                                'pendiente' => 'bg-amber-50 text-amber-700',
                                                                'cerrado' => 'bg-slate-50 text-slate-700',
                                                                default => 'bg-slate-100 text-slate-700',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="{{ $estadoColor }} rounded-md px-2 py-1 text-sm inline-block capitalize">
                                                            {{ ucfirst($exp->estado) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="bg-blue-50 text-blue-700 rounded-md px-2 py-1 text-sm inline-block">
                                                            {{ $exp->doctor->nombre ?? '—' }}
                                                            {{ $exp->doctor->apellido ?? '' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('expedientes.editar', $exp->id_expediente) }}"
                                                            class="action-btn btn-editar">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                            Editar
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- (3) Modal: Imprimir / Descargar PDF --}}
        <div id="modal-print-{{ $paciente->id_paciente }}" class="js-modal fixed inset-0 z-50 hidden p-4"
            role="dialog" aria-modal="true">
            <div class="js-modal-overlay absolute inset-0 bg-slate-900/0 transition-opacity duration-300"></div>

            <div class="flex items-center justify-center min-h-full p-4">
                <div
                    class="js-modal-panel relative w-full max-w-7xl bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 max-h-[85vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0">
                    <div class="p-4 sm:p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-slate-800">
                                @if($esLaboratorio)
                                    🖨️ Imprimir exámenes de laboratorio de:
                                @else
                                    🖨️ Imprimir o descargar datos de:
                                @endif
                                {{ $paciente->persona->nombre }}
                                {{ $paciente->persona->apellido }}
                            </h2>
                            <button
                                class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm hover:bg-slate-200 transition-colors"
                                onclick="closeAllModals()">Cerrar</button>
                        </div>

                        @if ($expedientesOrdenados->isEmpty())
                            <div class="empty-state">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="text-slate-600">No hay expedientes registrados.</p>
                            </div>
                        @else
                            <div class="table-wrapper-expedientes">
                                <div class="table-scroll-container">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                                <th>Doctor</th>
                                                <th class="text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expedientesOrdenados as $exp)
                                                <tr>
                                                    <td>
                                                        <span class="count-badge">{{ $exp->codigo }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="bg-purple-50 text-purple-700 rounded-md px-2 py-1 text-sm inline-block">
                                                            {{ \Carbon\Carbon::parse($exp->fecha_creacion)->format('d/m/Y H:i') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $estadoColor = match ($exp->estado) {
                                                                'activo' => 'bg-green-50 text-green-700',
                                                                'inactivo' => 'bg-red-50 text-red-700',
                                                                'pendiente' => 'bg-amber-50 text-amber-700',
                                                                'cerrado' => 'bg-slate-50 text-slate-700',
                                                                default => 'bg-slate-100 text-slate-700',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="{{ $estadoColor }} rounded-md px-2 py-1 text-sm inline-block capitalize">
                                                            {{ ucfirst($exp->estado) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="bg-blue-50 text-blue-700 rounded-md px-2 py-1 text-sm inline-block">
                                                            {{ $exp->doctor->nombre ?? '—' }}
                                                            {{ $exp->doctor->apellido ?? '' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="action-buttons">
                                                            <style>
                                                                .action-btn {
                                                                    display: inline-flex;
                                                                    align-items: center;
                                                                    justify-content: center;
                                                                    gap: 0.35rem;
                                                                    padding: 0.4rem 0.75rem;
                                                                    border-radius: 0.375rem;
                                                                    font-size: 0.75rem;
                                                                    font-weight: 500;
                                                                    transition: all 0.2s ease-in-out;
                                                                    border-width: 1px;
                                                                    text-decoration: none;
                                                                    margin-right: 0.25rem;
                                                                    margin-bottom: 0.25rem;
                                                                }

                                                                .action-btn:hover {
                                                                    transform: translateY(-1px);
                                                                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                                                                }

                                                                .action-btn svg {
                                                                    flex-shrink: 0;
                                                                }
                                                            </style>

                                                            {{-- Expediente completo (NO para laboratorio) --}}
                                                            @if(!$esLaboratorio)
                                                            <a href="{{ route('expedientes.completo', $exp->id_expediente) }}"
                                                                target="_blank" class="action-btn"
                                                                style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-color: #1d4ed8;">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                    </path>
                                                                </svg>
                                                                Expediente
                                                            </a>
                                                            @endif

                                                            {{-- Exámenes (PARA TODOS) --}}
                                                            <a href="{{ route('expedientes.examenes.imprimir', $exp->id_expediente) }}"
                                                                target="_blank" class="action-btn"
                                                                style="background: {{ $esLaboratorio ? 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)' : 'linear-gradient(135deg, #10b981 0%, #059669 100%)' }}; color: white; border-color: #047857;">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                                    </path>
                                                                </svg>
                                                                @if($esLaboratorio)
                                                                    Imprimir Exámenes
                                                                @else
                                                                    Exámenes
                                                                @endif
                                                            </a>

                                                            {{-- Receta (NO para laboratorio) --}}
                                                            @if(!$esLaboratorio)
                                                            <a href="{{ route('admin.recetas.imprimir', $exp->id_expediente) }}"
                                                                target="_blank" class="action-btn"
                                                                style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-color: #6d28d9;">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                                    </path>
                                                                </svg>
                                                                Receta
                                                            </a>
                                                            @endif

                                                            {{-- PDF (NO para laboratorio) --}}
                                                            @if(!$esLaboratorio)
                                                            <a href="{{ route('expedientes.completo.pdf', $exp->id_expediente) }}"
                                                                class="action-btn"
                                                                style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-color: #b91c1c;">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                    </path>
                                                                </svg>
                                                                PDF
                                                            </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Scripts --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
        // Helpers modales con animaciones suaves
        let currentModal = null;

        function hideAllModals() {
            if (currentModal) {
                const overlay = currentModal.querySelector('.js-modal-overlay');
                const panel = currentModal.querySelector('.js-modal-panel');

                // Animar salida
                overlay.style.opacity = '0';
                panel.classList.remove('scale-100', 'opacity-100');
                panel.classList.add('scale-95', 'opacity-0');

                // Esperar a que termine la animación antes de ocultar
                setTimeout(() => {
                    currentModal.classList.add('hidden');
                    currentModal = null;
                    document.documentElement.classList.remove('overflow-hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 300);
            }
        }

        function openModal(el) {
            // Cerrar modal actual si hay uno abierto
            if (currentModal) {
                hideAllModals();
                setTimeout(() => {
                    showModal(el);
                }, 350);
            } else {
                showModal(el);
            }
        }

        function showModal(el) {
            // Mostrar modal
            el.classList.remove('hidden');
            currentModal = el;

            // Forzar reflow para que la animación funcione
            void el.offsetWidth;

            // Animar entrada
            setTimeout(() => {
                const overlay = el.querySelector('.js-modal-overlay');
                const panel = el.querySelector('.js-modal-panel');

                overlay.style.opacity = '0.6';
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            }, 10);

            document.documentElement.classList.add('overflow-hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAllModals() {
            hideAllModals();
        }

        function openPatientModal(pacienteId, mode) {
            // Si es laboratorio y quiere editar, no hacer nada
            @if($esLaboratorio)
                if (mode === 'pick-edit') {
                    return;
                }
            @endif
            
            let id;
            if (mode === 'view') id = `modal-list-${pacienteId}`;
            else if (mode === 'print') id = `modal-print-${pacienteId}`;
            else if (mode === 'pick-edit') id = `modal-pick-${pacienteId}`;

            const m = document.getElementById(id);
            if (m) openModal(m);
        }

        // Cerrar modal al click fuera (en el overlay) / ESC
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('js-modal-overlay')) {
                hideAllModals();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && currentModal) {
                hideAllModals();
            }
        });

        // Mejorar experiencia de scroll en móvil
        document.addEventListener('DOMContentLoaded', () => {
            const tableContainer = document.querySelector('.table-scroll-container');
            const scrollHint = document.querySelector('.scroll-hint-expedientes');

            if (tableContainer && window.innerWidth <= 768 && scrollHint) {
                // Ocultar indicador después de 5 segundos o al hacer scroll
                setTimeout(() => {
                    scrollHint.style.opacity = '0';
                }, 5000);

                tableContainer.addEventListener('scroll', () => {
                    scrollHint.style.display = 'none';
                });

                // Mejorar experiencia táctil
                let isDown = false;
                let startX;
                let scrollLeft;

                tableContainer.addEventListener('mousedown', (e) => {
                    isDown = true;
                    tableContainer.style.cursor = 'grabbing';
                    startX = e.pageX - tableContainer.offsetLeft;
                    scrollLeft = tableContainer.scrollLeft;
                });

                tableContainer.addEventListener('touchstart', (e) => {
                    isDown = true;
                    startX = e.touches[0].pageX - tableContainer.offsetLeft;
                    scrollLeft = tableContainer.scrollLeft;
                });

                tableContainer.addEventListener('mouseleave', () => {
                    isDown = false;
                    tableContainer.style.cursor = 'grab';
                });

                tableContainer.addEventListener('mouseup', () => {
                    isDown = false;
                    tableContainer.style.cursor = 'grab';
                });

                tableContainer.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - tableContainer.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    tableContainer.scrollLeft = scrollLeft - walk;
                });

                tableContainer.addEventListener('touchmove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.touches[0].pageX - tableContainer.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    tableContainer.scrollLeft = scrollLeft - walk;
                });

                // Inicializar cursor
                tableContainer.style.cursor = 'grab';
            }
        });

        // SweetAlert: flash global
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: @json(session('success')),
                position: 'center',
                showConfirmButton: false,
                timer: 1600,
                heightAuto: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: @json(session('error')),
                position: 'center',
                confirmButtonText: 'Entendido',
                heightAuto: false
            });
        @endif
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Hay errores',
                html: @json(implode('<br>', $errors->all())),
                position: 'center',
                confirmButtonText: 'Revisar',
                heightAuto: false
            });
        @endif
    </script>
</x-app-layout>