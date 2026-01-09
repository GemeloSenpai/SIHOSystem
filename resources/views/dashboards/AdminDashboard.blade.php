<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Dashboard del Administrador') }}
            </h2>

            <div class="flex flex-wrap items-center justify-center gap-3 header-buttons">
                {{-- Ver licencia --}}
                <a href="{{ route('licencia.form') }}"
                    class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-200 text-sm w-full sm:w-auto min-h-[44px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M16.5 10.5V7.5a4.5 4.5 0 10-9 0v3m-.75 2.25h10.5a1.5 1.5 0 011.5 1.5v5.25a1.5 1.5 0 01-1.5 1.5H6.75a1.5 1.5 0 01-1.5-1.5V14.25a1.5 1.5 0 011.5-1.5z" />
                    </svg>
                    Ver licencia
                </a>

                {{-- Botón de EXPORTAR EXPEDIENTES --}}
                <button id="btnAbrirModalExportar"
                    class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-200 text-sm w-full sm:w-auto min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar a Excel
                </button>

                <button id="btnAbrirModalCerrarSesiones"
                    class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-200 text-sm w-full sm:w-auto min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar todas las sesiones
                </button>

                {{-- Botón para ver conectados --}}
                <button onclick="document.getElementById('tablaUsuarios').scrollIntoView({behavior: 'smooth'})"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-200 text-sm w-full sm:w-auto min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Ver conectados
                </button>
            </div>
        </div>
    </x-slot>

    {{-- Estilos responsivos optimizados para móviles pequeños --}}
    <style>
        /* Estilos base para móviles (hasta 640px) */
        @media (max-width: 640px) {

            /* Contenedor principal */
            .dashboard-container {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                padding-top: 0.5rem !important;
            }

            /* Grid de estadísticas - 1 columna en móviles */
            .stats-grid {
                grid-template-columns: 1fr !important;
                gap: 0.5rem !important;
            }

            /* Tarjetas de estadísticas */
            .stat-card {
                padding: 0.75rem !important;
                min-height: 90px !important;
            }

            .stat-card-inner {
                padding: 0.25rem !important;
            }

            /* Textos en tarjetas */
            .stat-card h3 {
                font-size: 0.7rem !important;
                white-space: nowrap;
            }

            .stat-number {
                font-size: 1.25rem !important;
                margin-top: 0.25rem !important;
            }

            /* Contenedor de iconos en tarjetas */
            .stat-card .inline-flex {
                min-width: 28px !important;
                min-height: 28px !important;
            }

            .stat-card .inline-flex svg {
                width: 0.875rem !important;
                height: 0.875rem !important;
            }

            /* Distribución de roles */
            .stat-card .text-xs span {
                display: inline-block;
                margin-bottom: 0.125rem;
                font-size: 0.65rem !important;
            }

            /* Grid de gráficos - 1 columna */
            .charts-grid {
                grid-template-columns: 1fr !important;
                gap: 0.5rem !important;
            }

            /* Contenedores de gráficos */
            .chart-container {
                height: 180px !important;
            }

            /* Títulos de secciones */
            h3.text-sm {
                font-size: 0.8rem !important;
                margin-bottom: 0.5rem !important;
            }

            /* Tablas pequeñas (Top 5, Exámenes) */
            .overflow-x-auto table {
                min-width: 100% !important;
                font-size: 0.7rem !important;
            }

            .overflow-x-auto td,
            .overflow-x-auto th {
                padding: 0.375rem 0.25rem !important;
            }

            /* Ajustes específicos para pantallas muy pequeñas (460px para abajo) */
            @media (max-width: 460px) {
                .dashboard-container {
                    padding-left: 0.375rem !important;
                    padding-right: 0.375rem !important;
                }

                .stat-card {
                    padding: 0.625rem !important;
                }

                .stat-number {
                    font-size: 1.125rem !important;
                }

                .chart-container {
                    height: 160px !important;
                }

                /* Header buttons más compactos */
                .header-buttons {
                    gap: 0.375rem;
                }

                .header-buttons a,
                .header-buttons button {
                    font-size: 0.8rem !important;
                    padding: 0.5rem 0.75rem !important;
                }

                /* Tablas más compactas */
                .overflow-x-auto table {
                    font-size: 0.65rem !important;
                }

                .overflow-x-auto td,
                .overflow-x-auto th {
                    padding: 0.25rem 0.125rem !important;
                }
            }

            /* Ajustes para 360px */
            @media (max-width: 360px) {
                .dashboard-container {
                    padding-left: 0.25rem !important;
                    padding-right: 0.25rem !important;
                }

                .stat-card {
                    padding: 0.5rem !important;
                    min-height: 80px !important;
                }

                .stat-number {
                    font-size: 1rem !important;
                }

                .chart-container {
                    height: 140px !important;
                }

                h3.text-sm {
                    font-size: 0.75rem !important;
                }

                .header-buttons a,
                .header-buttons button {
                    font-size: 0.75rem !important;
                    padding: 0.375rem 0.5rem !important;
                }
            }
        }

        /* Tablet optimizations (641px - 768px) */
        @media (min-width: 641px) and (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.75rem !important;
            }

            .charts-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.75rem !important;
            }

            .stat-card {
                min-height: 110px;
            }

            .chart-container {
                height: 200px !important;
            }
        }

        /* ===== ESTILOS ESPECÍFICOS PARA LA TABLA SIMPLE QUE FUNCIONA ===== */
        /* Contenedor principal de tabla */
        .table-wrapper {
            width: 100%;
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Contenedor de scroll horizontal */
        .table-scroll-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Ajustes para el scroll en móviles */
        @media (max-width: 640px) {
            .table-scroll-container {
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
        }

        /* Tabla simple y funcional */
        .simple-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        /* Cabecera */
        .simple-table thead {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .simple-table thead th {
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 12px 8px;
            border-bottom: 2px solid #4f46e5;
            white-space: nowrap;
        }

        /* Filas */
        .simple-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }

        .simple-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .simple-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Celdas */
        .simple-table td {
            padding: 10px 8px;
            vertical-align: middle;
        }

        /* Estados visuales */
        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .status-online {
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .status-offline {
            background-color: #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        /* Badges de rol */
        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .role-admin { background: #8b5cf6; color: white; }
        .role-medico { background: #3b82f6; color: white; }
        .role-enfermera { background: #06b6d4; color: white; }
        .role-recepcion { background: #f59e0b; color: white; }

        /* Botones de acción */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            min-width: 80px;
            min-height: 32px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .btn-cerrar-todas {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .btn-cerrar-todas:hover {
            background: #fee2e2;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
        }

        .btn-cerrar-sesion {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            margin-top: 4px;
        }

        .btn-cerrar-sesion:hover {
            background: #bbf7d0;
            box-shadow: 0 2px 8px rgba(21, 128, 61, 0.2);
        }

        /* Contenedor de botones */
        .actions-container {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: center;
        }

        /* Ajustes para móviles pequeños */
        @media (max-width: 640px) {
            .simple-table {
                min-width: 900px;
                font-size: 0.75rem;
            }
            
            .simple-table th,
            .simple-table td {
                padding: 8px 6px;
            }
            
            .role-badge {
                font-size: 0.7rem;
                padding: 3px 8px;
            }
            
            .action-btn {
                padding: 5px 10px;
                font-size: 0.7rem;
                min-width: 70px;
                min-height: 28px;
            }
        }

        @media (max-width: 460px) {
            .simple-table {
                min-width: 850px;
                font-size: 0.7rem;
            }
            
            .simple-table th,
            .simple-table td {
                padding: 6px 4px;
            }
            
            .role-badge {
                font-size: 0.65rem;
                padding: 2px 6px;
            }
            
            .action-btn {
                padding: 4px 8px;
                font-size: 0.65rem;
                min-width: 65px;
                min-height: 26px;
            }
        }

        @media (max-width: 360px) {
            .simple-table {
                min-width: 800px;
                font-size: 0.65rem;
            }
            
            .simple-table th,
            .simple-table td {
                padding: 5px 3px;
            }
            
            .role-badge {
                font-size: 0.6rem;
                padding: 2px 5px;
            }
            
            .action-btn {
                padding: 3px 6px;
                font-size: 0.6rem;
                min-width: 60px;
                min-height: 24px;
            }
        }

        /* Header de la tabla */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-header .total-badge {
            background: #6366f1;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Footer informativo */
        .table-footer {
            padding: 12px 16px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 0.75rem;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats-summary {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .stat-item {
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }

        .stat-online {
            background: #d1fae5;
            color: #065f46;
        }

        .stat-offline {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>

    <div class="max-w-screen-2xl mx-auto px-4 py-4 dashboard-container">

        {{-- Estadísticas Principales --}}
        <div id="estadisticas" class="grid gap-2 mb-8" style="display: contents">
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-2 stats-grid">
                {{-- Tarjeta 1: Total Empleados --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 stat-card">
                    <div class="flex items-center gap-2 mb-2 stat-card-inner">
                        <div
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.943a8.25 8.25 0 00-13.668-5.108" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-slate-500 truncate">Total Empleados</h3>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 stat-number">
                        {{ number_format($totalEmpleados) }}
                    </p>
                    <div class="mt-2 text-xs text-slate-500 flex flex-wrap gap-1">
                        @foreach ($distribucionRoles as $role => $total)
                            @if ($total > 0)
                                <span class="inline-block px-1.5 py-0.5 bg-slate-100 rounded text-slate-700">
                                    {{ ucfirst($role) }}: {{ $total }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Tarjeta 2: Pacientes Registrados --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 stat-card">
                    <div class="flex items-center gap-2 mb-2 stat-card-inner">
                        <div
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-slate-500 truncate">Pacientes Registrados</h3>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 stat-number">
                        {{ number_format($totalPacientes) }}
                    </p>
                    <div class="mt-2">
                        <div class="flex items-center flex-wrap gap-1 text-xs">
                            <span class="text-slate-600">Distribución:</span>
                            @if (isset($pacientesPorSexo['M']))
                                <span class="bg-blue-50 text-blue-700 rounded px-2 py-0.5">♂
                                    {{ $pacientesPorSexo['M'] }}</span>
                            @endif
                            @if (isset($pacientesPorSexo['F']))
                                <span class="bg-pink-50 text-pink-700 rounded px-2 py-0.5">♀
                                    {{ $pacientesPorSexo['F'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 3: Consultas Hoy --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 stat-card">
                    <div class="flex items-center gap-2 mb-2 stat-card-inner">
                        <div
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-cyan-100 text-cyan-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-slate-500 truncate">Consultas Hoy</h3>
                    </div>
                    <p class="text-2xl font-bold text-slate-800 stat-number">
                        {{ number_format($consultasHoy) }}
                    </p>
                    @if (isset($estadisticasUrgencias['si']))
                        <div class="mt-2">
                            <span class="inline-flex items-center text-xs">
                                <span class="w-2 h-2 bg-rose-500 rounded-full mr-1"></span>
                                {{ $estadisticasUrgencias['si'] }} urgentes
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Gráficos y Tablas --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 charts-grid" style="display: contents">
                {{-- Gráfico 1: Consultas últimos 7 días --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4 truncate">Consultas (Últimos 7 días)</h3>
                    <div class="h-64 chart-container">
                        <canvas id="chartConsultas"></canvas>
                    </div>
                </div>

                {{-- Gráfico 2: Distribución de urgencias --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4 truncate">Tipos de Urgencia</h3>
                    <div class="h-64 chart-container">
                        <canvas id="chartUrgencias"></canvas>
                    </div>
                </div>

                {{-- Tabla: Top 5 Médicos --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4 truncate">Top 5 Médicos por Consultas</h3>
                    <div class="overflow-x-auto scroll-touch">
                        <table class="w-full text-sm min-w-full">
                            <thead>
                                <tr class="text-xs text-slate-500 border-b">
                                    <th class="pb-2 text-left pr-2">Médico</th>
                                    <th class="pb-2 text-right pl-2">Consultas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($empleadosTop as $medico)
                                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50">
                                        <td class="py-3 pr-2">
                                            <div class="font-medium text-slate-800 truncate text-xs"
                                                title="{{ $medico->doctor->user->name ?? 'N/A' }}">
                                                {{ $medico->doctor->user->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="py-3 text-right pl-2">
                                            <span
                                                class="bg-indigo-50 text-indigo-700 rounded px-2 py-1 text-xs font-medium inline-block min-w-[2.5rem]">
                                                {{ $medico->total_consultas }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-4 text-center text-slate-400 text-sm">
                                            No hay datos disponibles
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabla: Exámenes Populares --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4 truncate">Exámenes Más Solicitados</h3>
                    <div class="overflow-x-auto scroll-touch">
                        <table class="w-full text-sm min-w-full">
                            <thead>
                                <tr class="text-xs text-slate-500 border-b">
                                    <th class="pb-2 text-left pr-2">Examen</th>
                                    <th class="pb-2 text-right pl-2">Solicitudes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($examenesPopulares as $examen)
                                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50">
                                        <td class="py-3 pr-2">
                                            <div class="text-slate-800 truncate text-xs"
                                                title="{{ $examen->examen->nombre_examen ?? '' }}">
                                                {{ Str::limit($examen->examen->nombre_examen ?? 'N/A', 40) }}
                                            </div>
                                        </td>
                                        <td class="py-3 text-right pl-2">
                                            <span
                                                class="bg-emerald-50 text-emerald-700 rounded px-2 py-1 text-xs font-medium inline-block min-w-[2.5rem]">
                                                {{ $examen->total }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-4 text-center text-slate-400 text-sm">
                                            No hay datos disponibles
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Segunda fila de gráficos --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 charts-grid" style="display: contents">
                {{-- Gráfico 3: Consultas por hora del día --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4 truncate">Consultas por Hora (Hoy)</h3>
                    <div class="h-64 chart-container">
                        <canvas id="chartConsultasPorHora"></canvas>
                    </div>
                </div>

                {{-- Tabla: Recetas Recientes --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h3 class="text-sm font-semibold text-slate-800 mb-4 truncate">Recetas Recientes</h3>
                    <div class="overflow-x-auto scroll-touch">
                        <table class="w-full text-sm min-w-full">
                            <thead>
                                <tr class="text-xs text-slate-500 border-b">
                                    <th class="pb-2 text-left pr-2">Fecha</th>
                                    <th class="pb-2 text-left pr-2">Paciente</th>
                                    <th class="pb-2 text-left pr-2">Médico</th>
                                    <th class="pb-2 text-left">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recetasRecientes as $receta)
                                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50">
                                        <td class="py-3 pr-2">
                                            <span class="text-slate-600 text-xs">
                                                {{ \Carbon\Carbon::parse($receta->fecha_prescripcion)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-2">
                                            <div class="font-medium text-slate-800 text-sm truncate"
                                                title="{{ $receta->paciente->persona->nombre ?? 'N/A' }} {{ $receta->paciente->persona->apellido ?? '' }}">
                                                {{ $receta->paciente->persona->nombre ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="py-3 pr-2">
                                            <span class="text-slate-600 text-sm truncate"
                                                title="{{ $receta->doctor->user->name ?? 'N/A' }}">
                                                {{ Str::limit($receta->doctor->user->name ?? 'N/A', 15) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $estadoColors = [
                                                    'activa' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'completada' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'suspendida' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'cancelada' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                ];
                                                $color =
                                                    $estadoColors[$receta->estado] ??
                                                    'bg-slate-100 text-slate-700 border-slate-200';
                                            @endphp
                                            <span
                                                class="px-2 py-1 rounded text-xs font-medium border {{ $color }} truncate inline-block max-w-[100px]">
                                                {{ ucfirst($receta->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-slate-400 text-sm">
                                            No hay recetas recientes
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de usuarios SIMPLE Y FUNCIONAL --}}
        <div id="tablaUsuarios" class="mt-8">
            <div class="table-wrapper">
                {{-- Header de la tabla --}}
                <div class="table-header">
                    <h3>
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.943a8.25 8.25 0 00-13.668-5.108" />
                        </svg>
                        Gestión de Usuarios
                    </h3>
                    <span class="total-badge">Total: {{ $usuarios->count() }}</span>
                </div>

                {{-- Tabla con scroll horizontal --}}
                <div class="table-scroll-container">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Rol</th>
                                <th>Email</th>
                                <th>IP</th>
                                <th>Última Actividad</th>
                                <th># Sesiones</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $user)
                                <tr>
                                    {{-- Estado --}}
                                    <td class="text-center">
                                        <span class="status-dot {{ $user->online ? 'status-online' : 'status-offline' }}"
                                            title="{{ $user->online ? 'Conectado' : 'Desconectado' }}"></span>
                                    </td>

                                    {{-- ID --}}
                                    <td>{{ $user->id }}</td>

                                    {{-- Nombre --}}
                                    <td class="font-medium">{{ $user->name }}</td>

                                    {{-- Rol --}}
                                    <td>
                                        <span class="role-badge role-{{ $user->role }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>

                                    {{-- Email --}}
                                    <td class="truncate" style="max-width: 200px;" title="{{ $user->email }}">
                                        {{ $user->email }}
                                    </td>

                                    {{-- IP --}}
                                    <td>{{ $user->ip ?? '—' }}</td>

                                    {{-- Última Actividad --}}
                                    <td>{{ $user->ultima_actividad }}</td>

                                    {{-- Sesiones --}}
                                    <td class="text-center">{{ $user->num_sesiones }}</td>

                                    {{-- Acciones --}}
                                    <td>
                                        <div class="actions-container">
                                            @if ($user->num_sesiones > 0)
                                                <button onclick="cerrarTodas('{{ $user->id }}', '{{ $user->name }}')"
                                                    class="action-btn btn-cerrar-todas">
                                                    Cerrar todas
                                                </button>
                                            @endif
                                            @if (isset($user->sesion_ids) && count($user->sesion_ids) > 0)
                                                @foreach ($user->sesion_ids as $idSesion)
                                                    <button onclick="cerrarSesion('{{ $idSesion }}')"
                                                        class="action-btn btn-cerrar-sesion">
                                                        Cerrar sesión
                                                    </button>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer informativo --}}
                <div class="table-footer">
                    <div class="stats-summary">
                        @php
                            $online = $usuarios->where('online', true)->count();
                            $offline = $usuarios->count() - $online;
                        @endphp
                        <span class="stat-item stat-online">🟢 {{ $online }} online</span>
                        <span class="stat-item stat-offline">🔴 {{ $offline }} offline</span>
                    </div>
                    <span class="hidden sm:inline text-slate-500 text-xs">
                        ← Desliza horizontalmente para ver más columnas →
                    </span>
                </div>
            </div>
        </div>

        {{-- Formularios ocultos --}}
        <form id="form-exportar" action="{{ route('exportar.expedientes') }}" method="GET" class="hidden"></form>
        <form id="form-cerrar-sesiones" action="{{ route('admin.cerrar.sesiones') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Configuración de SweetAlert
        window.Confirm = Swal.mixin({
            customClass: {
                popup: 'rounded-xl shadow-lg',
                confirmButton: 'bg-emerald-600 hover:bg-emerald-700 text-white rounded px-3 py-1.5 text-sm font-medium transition-all duration-200',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-800 rounded px-3 py-1.5 text-sm font-medium transition-all duration-200',
            },
            buttonsStyling: false,
            reverseButtons: true,
        });

        window.toast = (title = 'Listo', icon = 'success') =>
            Swal.fire({
                title,
                icon,
                toast: true,
                position: 'top-end',
                timer: 2200,
                showConfirmButton: false,
                timerProgressBar: true,
                heightAuto: false
            });

        const ask = (opts) => window.Confirm.fire(opts);

        // ========== FUNCIONES DE LA TABLA SIMPLE ==========
        function cerrarSesion(sessionId) {
            ask({
                title: '¿Cerrar esta sesión?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/sesiones/${sessionId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (res.ok) {
                            toast('✅ Sesión cerrada exitosamente');
                            setTimeout(() => location.reload(), 800);
                        } else {
                            Swal.fire('Error', 'No se pudo cerrar la sesión', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Error de conexión', 'error');
                    });
                }
            });
        }

        function cerrarTodas(userId, userName) {
            ask({
                title: '¿Cerrar todas las sesiones?',
                html: `Se cerrarán todas las sesiones de <b>${userName}</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar todas',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/sesiones-usuario/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (res.ok) {
                            toast('✅ Todas las sesiones cerradas');
                            setTimeout(() => location.reload(), 800);
                        } else {
                            Swal.fire('Error', 'No se pudieron cerrar las sesiones', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Error de conexión', 'error');
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Inicializar gráficos
            initCharts();

            // ========== EXPORTAR EXPEDIENTES ==========
            const btnExportar = document.getElementById('btnAbrirModalExportar');
            if (btnExportar) {
                btnExportar.addEventListener('click', async () => {
                    const res = await ask({
                        title: '¿Exportar expedientes a Excel?',
                        text: 'Se exportarán todos los expedientes.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, exportar',
                        cancelButtonText: 'Cancelar',
                    });
                    if (res.isConfirmed) {
                        document.getElementById('form-exportar').submit();
                    }
                });
            }

            // ========== CERRAR TODAS LAS SESIONES (GLOBAL) ==========
            const btnCerrarSesiones = document.getElementById('btnAbrirModalCerrarSesiones');
            if (btnCerrarSesiones) {
                btnCerrarSesiones.addEventListener('click', async () => {
                    const res = await ask({
                        title: '¿Cerrar todas las sesiones?',
                        text: 'Se cerrarán todas las sesiones activas de los usuarios.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, cerrar',
                        cancelButtonText: 'Cancelar',
                    });
                    if (res.isConfirmed) {
                        document.getElementById('form-cerrar-sesiones').submit();
                    }
                });
            }

            // ========== MEJORAR EXPERIENCIA DE SCROLL EN MÓVIL ==========
            const tableContainer = document.querySelector('.table-scroll-container');
            if (tableContainer && window.innerWidth <= 640) {
                let isDown = false;
                let startX;
                let scrollLeft;

                tableContainer.addEventListener('mousedown', (e) => {
                    isDown = true;
                    tableContainer.style.cursor = 'grabbing';
                    startX = e.pageX - tableContainer.offsetLeft;
                    scrollLeft = tableContainer.scrollLeft;
                });

                tableContainer.addEventListener('mouseleave', () => {
                    isDown = false;
                    tableContainer.style.cursor = 'default';
                });

                tableContainer.addEventListener('mouseup', () => {
                    isDown = false;
                    tableContainer.style.cursor = 'default';
                });

                tableContainer.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - tableContainer.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    tableContainer.scrollLeft = scrollLeft - walk;
                });

                // Para touch en móviles
                tableContainer.addEventListener('touchstart', (e) => {
                    isDown = true;
                    startX = e.touches[0].pageX - tableContainer.offsetLeft;
                    scrollLeft = tableContainer.scrollLeft;
                });

                tableContainer.addEventListener('touchend', () => {
                    isDown = false;
                });

                tableContainer.addEventListener('touchmove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.touches[0].pageX - tableContainer.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    tableContainer.scrollLeft = scrollLeft - walk;
                });
            }
        });

        function initCharts() {
            // Gráfico 1: Consultas últimos 7 días
            const ctxConsultas = document.getElementById('chartConsultas');
            if (ctxConsultas) {
                const datosConsultas = @json($consultasUltimos7Dias);

                const labels = datosConsultas.map(item => {
                    const fecha = new Date(item.fecha);
                    return fecha.toLocaleDateString('es-ES', {
                        weekday: 'short',
                        day: 'numeric'
                    });
                });

                const data = datosConsultas.map(item => item.total);

                new Chart(ctxConsultas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Consultas',
                            data: data,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Gráfico 2: Tipos de urgencia
            const ctxUrgencias = document.getElementById('chartUrgencias');
            if (ctxUrgencias) {
                const datosUrgencias = @json($tiposUrgencia);

                const labels = datosUrgencias.map(item => {
                    const tipos = {
                        'medica': 'Médica',
                        'pediatrica': 'Pediatría',
                        'quirurgico': 'Quirúrgico',
                        'gineco obstetrica': 'Gineco Obst.',
                    };
                    return tipos[item.tipo_urgencia] || item.tipo_urgencia;
                });

                const data = datosUrgencias.map(item => item.total);
                const colors = ['#ef4444', '#f97316', '#3b82f6', '#8b5cf6'];

                new Chart(ctxUrgencias, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Gráfico 3: Consultas por hora
            const ctxConsultasHora = document.getElementById('chartConsultasPorHora');
            if (ctxConsultasHora) {
                const datosHora = @json($consultasPorHora);

                // Crear array de 24 horas
                const horas = Array.from({
                    length: 24
                }, (_, i) => i);
                const data = horas.map(hora => datosHora[hora] || 0);
                const labels = horas.map(hora => `${hora}:00`);

                new Chart(ctxConsultasHora, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Consultas',
                            data: data,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: '#3b82f6',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        }
    </script>
</x-app-layout>