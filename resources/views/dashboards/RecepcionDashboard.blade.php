<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Dashboard de Recepción') }}
            </h2>

            <div class="flex flex-balanced items-center justify-center gap-3 header-buttons">
                {{-- Registrar Paciente --}}
                <a href="{{ route('recepcion.pacientes.form') }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-200 text-sm w-full sm:w-auto justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span class="hidden xs:inline">Registrar Paciente</span>
                    <span class="xs:hidden">Nuevo Paciente</span>
                </a>

                {{-- Gestionar Pacientes --}}
                <a href="{{ route('recepcion.verPacientes') }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-200 text-sm w-full sm:w-auto justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.943a8.25 8.25 0 00-13.668-5.108" />
                    </svg>
                    <span class="hidden xs:inline">Gestionar Pacientes</span>
                    <span class="xs:hidden">Ver Pacientes</span>
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Estilos responsivos manteniendo diseño original --}}
    <style>
        /* Mantener estilos originales y agregar responsividad */
        @media (max-width: 768px) {
            .dashboard-container {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            
            /* Ajustar grid de tarjetas KPI para móviles */
            .kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.75rem !important;
            }
            
            /* Reducir padding en tarjetas KPI */
            .kpi-card {
                padding: 1rem !important;
                min-height: 120px;
            }
            
            /* Ajustar tamaño de texto en KPI */
            .kpi-title {
                font-size: 0.75rem !important;
            }
            
            .kpi-value {
                font-size: 1.5rem !important;
            }
            
            /* Ajustar tamaño de iconos */
            .kpi-icon {
                height: 2rem !important;
                width: 2rem !important;
            }
            
            .kpi-icon svg {
                height: 1rem !important;
                width: 1rem !important;
            }
            
            /* Grid de dos columnas para móviles */
            .responsive-grid {
                grid-template-columns: 1fr !important;
            }
            
            /* Ajustar cards principales */
            .main-card {
                padding: 1rem !important;
                margin-bottom: 0.75rem !important;
            }
            
            /* Acciones rápidas ajustadas */
            .quick-actions-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
            
            .quick-action-card {
                padding: 1rem !important;
            }
            
            /* Emojis más pequeños en móviles */
            .emoji-large {
                font-size: 2.5rem !important;
            }
        }
        
        @media (max-width: 640px) {
            .kpi-grid {
                grid-template-columns: 1fr !important;
            }
            
            .quick-actions-grid {
                grid-template-columns: 1fr !important;
            }
            
            /* Ocultar algunos elementos en móviles pequeños */
            .mobile-hidden {
                display: none !important;
            }
            
            /* Texto más compacto */
            .compact-text {
                font-size: 0.875rem !important;
            }
        }
        
        @media (min-width: 641px) and (max-width: 1024px) {
            /* Optimizaciones para tablet */
            .kpi-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            }
            
            .responsive-grid {
                grid-template-columns: 1fr !important;
            }
            
            .kpi-card {
                padding: 1.25rem !important;
            }
            
            .main-card {
                padding: 1.5rem !important;
            }
            
            .quick-actions-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }
        
        /* Mejorar experiencia táctil */
        .touch-optimized {
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Scroll suave para tablas largas */
        .scroll-touch {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        
        /* Mantener los estilos bonitos del diseño original */
        .scroll-touch::-webkit-scrollbar {
            height: 4px;
            background: #f8fafc;
        }
        
        .scroll-touch::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .scroll-touch::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="max-w-screen-2xl mx-auto px-2 sm:px-4 py-4 dashboard-container">

        {{-- ========== FLUJO DE PACIENTES (4 KPIs) ========== --}}
        <div id="flujoPacientes" class="grid gap-2 mb-3">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">📊 Flujo de Pacientes</h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 kpi-grid" style="/*grid-template-columns: repeat(7, minmax(0, 1fr));*/">
                {{-- Pacientes Nuevos Hoy --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 kpi-card">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 kpi-icon">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500 kpi-title">Nuevos Hoy</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 kpi-value">
                        {{ number_format($pacientesNuevosHoy) }}
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500">
                        @if(isset($pacientesPorSexoHoy['M']) || isset($pacientesPorSexoHoy['F']))
                            <div class="flex gap-1 flex-wrap">
                                @if(isset($pacientesPorSexoHoy['M']))
                                    <span class="bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded text-xs">♂ {{ $pacientesPorSexoHoy['M'] }}</span>
                                @endif
                                @if(isset($pacientesPorSexoHoy['F']))
                                    <span class="bg-pink-50 text-pink-700 px-1.5 py-0.5 rounded text-xs">♀ {{ $pacientesPorSexoHoy['F'] }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pacientes Atendidos Hoy --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 kpi-card">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 kpi-icon">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500 kpi-title">Atendidos Hoy</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 kpi-value">
                        {{ number_format($pacientesAtendidosHoy) }}
                    </p>
                    @if($citasHoy > 0)
                    <div class="mt-1 sm:mt-2 text-xs text-emerald-600">
                        {{ $citasCumplidasHoy }}/{{ $citasHoy }} citas
                    </div>
                    @endif
                </div>

                {{-- Pacientes en Espera 
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 kpi-card">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-amber-100 text-amber-600 kpi-icon">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500 kpi-title">En Espera</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 kpi-value">
                        {{ number_format($pacientesEnEspera) }}
                    </p>
                    @if($pacientesEsperaLarga > 0)
                    <div class="mt-1 sm:mt-2">
                        <span class="inline-flex items-center text-xs text-rose-600">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full mr-1"></span>
                            {{ $pacientesEsperaLarga }} larga
                        </span>
                    </div>
                    @endif
                </div>--}}

                {{-- Médicos Activos --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 kpi-card">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-cyan-100 text-cyan-600 kpi-icon">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500 kpi-title">Médicos Activos</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 kpi-value">
                        {{ number_format($medicosActivosHoy) }}
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500">
                        Disponibles
                    </div>
                </div>

                {{-- En Desarrollo 1 
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 kpi-card">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600 kpi-icon">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500 kpi-title">En Desarrollo</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 kpi-value">
                        N/A
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500">
                        Próximamente
                    </div>
                </div>

                {{-- En Desarrollo 2 
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 kpi-card">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600 kpi-icon">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500 kpi-title">En Desarrollo</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 kpi-value">
                        N/A
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500">
                        Próximamente
                    </div>
                </div>

                {{-- En Desarrollo 3 
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 kpi-card">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600 kpi-icon">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500 kpi-title">En Desarrollo</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800 kpi-value">
                        N/A
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500">
                        Próximamente
                    </div>
                </div>
                --}}
            </div>
        </div>

        {{-- ========== AGENDA Y CITAS (2 columnas) ========== --}}
        <div id="agendaCitas" class="grid gap-2 mb-3">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 responsive-grid">
                {{-- Citas de Hoy --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 main-card">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">📅 Citas de Hoy</h3>
                    
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-800">{{ number_format($citasHoy) }}</p>
                            <div class="text-xs text-slate-500 mt-1">
                                @if($citasHoy > 0)
                                    <span class="text-emerald-600">{{ $citasCumplidasHoy }} cumplidas</span>
                                    <span class="mx-1">•</span>
                                    <span class="text-amber-600">{{ $citasHoy - $citasCumplidasHoy }} pendientes</span>
                                @else
                                    Sin citas programadas
                                @endif
                            </div>
                        </div>
                        <div class="text-3xl sm:text-4xl emoji-large">📋</div>
                    </div>
                    
                    @if($proximasCitas->isNotEmpty())
                    <div class="border-t border-slate-100 pt-3 sm:pt-4">
                        <h4 class="text-xs font-semibold text-slate-600 mb-3">Próximas citas</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto scroll-touch">
                            @foreach($proximasCitas->take(5) as $cita)
                            <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded touch-optimized">
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-slate-800 truncate compact-text">
                                        {{ $cita->paciente->persona->nombre ?? 'N/A' }} 
                                        {{ $cita->paciente->persona->apellido ?? '' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($cita->citado)->format('d/m') }}
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs font-medium whitespace-nowrap ml-2">
                                    {{ \Carbon\Carbon::parse($cita->citado)->diffForHumans(['parts' => 1]) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- URGENCIAS HOY --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 main-card">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">🚨 Urgencias Hoy</h3>
                    
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-2xl sm:text-3xl font-bold text-slate-800">{{ number_format($urgenciasHoy) }}</p>
                            <div class="text-xs text-slate-500 mt-1">
                                @if($urgenciasHoy > 0)
                                    Atención prioritaria
                                @else
                                    Sin urgencias
                                @endif
                            </div>
                        </div>
                        <div class="text-3xl sm:text-4xl emoji-large">⚠️</div>
                    </div>
                    
                    @if($tiposUrgenciaHoy->isNotEmpty())
                    <div class="border-t border-slate-100 pt-3 sm:pt-4">
                        <h4 class="text-xs font-semibold text-slate-600 mb-3">Distribución por tipo</h4>
                        <div class="space-y-2">
                            @foreach($tiposUrgenciaHoy as $urgencia)
                            @php
                                $tipos = [
                                    'medica' => ['color' => 'bg-rose-100 text-rose-700', 'icon' => '🩺'],
                                    'pediatrica' => ['color' => 'bg-blue-100 text-blue-700', 'icon' => '👶'],
                                    'quirurgico' => ['color' => 'bg-purple-100 text-purple-700', 'icon' => '🔪'],
                                    'gineco obstetrica' => ['color' => 'bg-pink-100 text-pink-700', 'icon' => '🤰'],
                                ];
                                $tipo = $tipos[$urgencia->tipo_urgencia] ?? ['color' => 'bg-slate-100 text-slate-700', 'icon' => '❗'];
                            @endphp
                            <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded touch-optimized">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <span class="text-sm">{{ $tipo['icon'] }}</span>
                                    <span class="text-sm text-slate-800 truncate capitalize compact-text">
                                        {{ str_replace(['gineco obstetrica', 'quirurgico'], ['Gineco', 'Quirúrgico'], $urgencia->tipo_urgencia) }}
                                    </span>
                                </div>
                                <span class="px-2 py-0.5 {{ $tipo['color'] }} rounded text-xs font-medium whitespace-nowrap ml-2">
                                    {{ $urgencia->total }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========== ESTADO DE ESPERA Y TIPOS DE CONSULTA ========== --}}
        <div id="estadoEspera" class="grid gap-2 mb-3">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 responsive-grid">
                {{-- Tipos de Consulta Hoy --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 main-card">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3 sm:mb-4">🏥 Tipos de Consulta Hoy</h3>
                    
                    @if($tiposConsultaHoy->isNotEmpty())
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($tiposConsultaHoy as $tipo)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-slate-700 capitalize truncate compact-text">
                                    {{ $tipo->tipo_consulta == 'general' ? 'Consulta General' : 'Consulta Especializada' }}
                                </span>
                                <span class="text-sm font-bold text-slate-800 whitespace-nowrap ml-2">{{ $tipo->total }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                @php
                                    $totalConsultas = $tiposConsultaHoy->sum('total');
                                    $porcentaje = $totalConsultas > 0 ? ($tipo->total / $totalConsultas) * 100 : 0;
                                    $color = $tipo->tipo_consulta == 'general' ? 'bg-indigo-500' : 'bg-emerald-500';
                                @endphp
                                <div class="{{ $color }} h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                            </div>
                            <div class="text-xs text-slate-500 mt-1 text-right">
                                {{ number_format($porcentaje, 1) }}%
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6 sm:py-8">
                        <div class="text-3xl sm:text-4xl mb-2">📊</div>
                        <p class="text-sm text-slate-500">No hay consultas hoy</p>
                    </div>
                    @endif
                </div>

                {{-- Resumen del Día --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 main-card">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3 sm:mb-4">📈 Resumen del Día</h3>
                    
                    <div class="space-y-3 sm:space-y-4">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <div class="text-xs text-slate-500 mb-1 truncate">Tasa de Atención</div>
                                @php
                                    $totalPacientesDia = $pacientesNuevosHoy + $pacientesEnEspera;
                                    $tasaAtencion = $totalPacientesDia > 0 ? ($pacientesAtendidosHoy / $totalPacientesDia) * 100 : 0;
                                @endphp
                                <div class="text-lg sm:text-xl font-bold text-slate-800">{{ number_format($tasaAtencion, 1) }}%</div>
                            </div>
                            
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <div class="text-xs text-slate-500 mb-1 truncate">Citas/Paciente</div>
                                @php
                                    $citasPorPaciente = $pacientesAtendidosHoy > 0 ? $citasHoy / $pacientesAtendidosHoy : 0;
                                @endphp
                                <div class="text-lg sm:text-xl font-bold text-slate-800">{{ number_format($citasPorPaciente, 1) }}</div>
                            </div>
                        </div>
                        
                        <div class="border-t border-slate-100 pt-3 sm:pt-4">
                            <h4 class="text-xs font-semibold text-slate-600 mb-2">Recomendaciones</h4>
                            <div class="space-y-2">
                                @if($pacientesEsperaLarga > 0)
                                <div class="flex items-start gap-2 text-xs sm:text-sm">
                                    <span class="text-rose-500 flex-shrink-0">⚠️</span>
                                    <span class="text-slate-700">{{ $pacientesEsperaLarga }} pacientes con espera > 30 min</span>
                                </div>
                                @endif
                                
                                @if($urgenciasHoy > 0)
                                <div class="flex items-start gap-2 text-xs sm:text-sm">
                                    <span class="text-amber-500 flex-shrink-0">🚨</span>
                                    <span class="text-slate-700">{{ $urgenciasHoy }} urgencias requieren atención prioritaria</span>
                                </div>
                                @endif
                                
                                @if($citasHoy - $citasCumplidasHoy > 0)
                                <div class="flex items-start gap-2 text-xs sm:text-sm">
                                    <span class="text-blue-500 flex-shrink-0">📅</span>
                                    <span class="text-slate-700">{{ $citasHoy - $citasCumplidasHoy }} citas pendientes de hoy</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== ACCIONES RÁPIDAS ========== --}}
        <div id="accionesRapidas" class="bg-white p-4 sm:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
            <h3 class="text-sm font-semibold text-slate-800 mb-3 sm:mb-4">⚡ Acciones Rápidas</h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 quick-actions-grid">
                <a href="{{ route('recepcion.pacientes.form') }}"
                    class="flex flex-col items-center justify-center p-3 sm:p-4 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-all duration-200 group touch-optimized quick-action-card">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">➕</div>
                    <div class="text-xs sm:text-sm font-medium text-center">Registrar Paciente</div>
                </a>
                
                <a href="{{ route('recepcion.verPacientes') }}"
                    class="flex flex-col items-center justify-center p-3 sm:p-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition-all duration-200 group touch-optimized quick-action-card">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">👥</div>
                    <div class="text-xs sm:text-sm font-medium text-center">Gestionar Pacientes</div>
                </a>
                
                <a href="#"
                    class="flex flex-col items-center justify-center p-3 sm:p-4 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition-all duration-200 group touch-optimized quick-action-card">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">📋</div>
                    <div class="text-xs sm:text-sm font-medium text-center">Ver Expedientes</div>
                </a>
                
                <!-- Opcional: Cuarta acción
                <a href="#"
                    class="flex flex-col items-center justify-center p-3 sm:p-4 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 rounded-lg transition-all duration-200 group touch-optimized quick-action-card">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">🖨️</div>
                    <div class="text-xs sm:text-sm font-medium text-center">Imprimir Reporte</div>
                </a>
                -->
            </div>
        </div>

    </div>

    {{-- Scripts para gráficos (opcional) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mejorar experiencia táctil en móviles
            const style = document.createElement('style');
            style.textContent = `
                /* Mejoras específicas para móviles */
                @media (max-width: 640px) {
                    /* Aumentar áreas de toque */
                    a, button {
                        min-height: 44px;
                        min-width: 44px;
                    }
                    
                    /* Mejorar scroll en listas */
                    .max-h-48 {
                        max-height: 200px;
                        -webkit-overflow-scrolling: touch;
                    }
                    
                    /* Espaciado más compacto */
                    .space-y-2 > * + * {
                        margin-top: 0.375rem;
                    }
                }
                
                /* Mejorar visibilidad en tablets */
                @media (min-width: 641px) and (max-width: 1024px) {
                    .main-card {
                        min-height: 320px;
                    }
                    
                    .quick-action-card {
                        min-height: 100px;
                    }
                }
                
                /* Evitar zoom en inputs en iOS */
                input, select, textarea {
                    font-size: 16px;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</x-app-layout>