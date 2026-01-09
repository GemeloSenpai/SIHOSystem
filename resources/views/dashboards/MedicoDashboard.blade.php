<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Dashboard Médico') }}
            </h2>

            <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                {{-- Tomar consulta --}}
                <a href="{{ url('/medico/registrar-consulta') }}"
                    class="inline-flex items-center gap-1 sm:gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 sm:py-2 sm:px-4 rounded-lg shadow-sm transition-all duration-200 text-xs sm:text-sm">
                    
                    <span class="hidden xs:inline">Tomar Consulta</span>
                    <span class="xs:hidden">Consulta</span>
                </a>

                {{-- Gestionar expedientes --}}
                <a href="{{ url('/expedientes') }}"
                    class="inline-flex items-center gap-1 sm:gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-3 sm:py-2 sm:px-4 rounded-lg shadow-sm transition-all duration-200 text-xs sm:text-sm">
                    
                    <span class="hidden xs:inline">Expedientes</span>
                    <span class="xs:hidden">Gestionar Expedientes</span>
                </a>

                {{-- Gestionar exámenes --}}
                <a href="{{ route('medico.examenes.index') }}"
                    class="inline-flex items-center gap-1 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-3 sm:py-2 sm:px-4 rounded-lg shadow-sm transition-all duration-200 text-xs sm:text-sm">
                    
                    <span class="hidden xs:inline">Exámenes</span>
                    <span class="xs:hidden">Ver Examnenes</span>
                </a>

                {{-- Ver recetas 
                <a href="#"
                    class="inline-flex items-center gap-1 sm:gap-2 bg-purple-600 hover:bg-purple-700 text-black font-semibold py-2 px-3 sm:py-2 sm:px-4 rounded-lg shadow-sm transition-all duration-200 text-xs sm:text-sm">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="hidden xs:inline">Recetas</span>
                    <span class="xs:hidden">Ver Recetas</span>
                </a>
                --}}
            </div>
        </div>
    </x-slot>

    {{-- Estilos para ancho completo --}}
    <style>
        /* Ajustes responsivos generales */
        div.max-w-7xl.mx-auto {
            max-width: 100% !important;
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }

        @media (min-width: 375px) {
            div.max-w-7xl.mx-auto {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }

        @media (min-width: 640px) {
            div.max-w-7xl.mx-auto {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        @media (min-width: 768px) {
            div.max-w-7xl.mx-auto {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }

        @media (min-width: 1024px) {
            div.max-w-7xl.mx-auto {
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }

        /* Scroll personalizado */
        .scroll-custom::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .scroll-custom::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .scroll-custom::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .scroll-custom::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @media (min-width: 640px) {
            .scroll-custom::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
        }

        /* Clases de utilidad responsivas personalizadas */
        .text-xs-responsive {
            font-size: 0.625rem;
        }

        @media (min-width: 375px) {
            .text-xs-responsive {
                font-size: 0.75rem;
            }
        }

        .text-sm-responsive {
            font-size: 0.75rem;
        }

        @media (min-width: 375px) {
            .text-sm-responsive {
                font-size: 0.875rem;
            }
        }

        /* Para ajustar el grid en móviles muy pequeños */
        @media (max-width: 374px) {
            .grid-cols-2-responsive {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important; 
            }
        }

        /* Mejorar visibilidad en móviles pequeños */
        @media (max-width: 374px) {
            .px-mobile-safe {
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
            }

            .text-lg {
                font-size: 1rem !important;
            }

            .text-2xl {
                font-size: 1.5rem !important;
            }

            .text-3xl {
                font-size: 1.75rem !important;
            }
        }
    </style>

    <div class="max-w-screen-2xl mx-auto px-2 sm:px-3 lg:px-4 py-3 sm:py-4">
        {{-- ========== ACTIVIDAD DEL DÍA (4 KPIs) ========== --}}
        <div id="actividadDia" class="grid gap-2 sm:gap-3 mb-6 sm:mb-8">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">📊 Tu Actividad Hoy</h3>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                {{-- Consultas Hoy --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                        <div
                            class="inline-flex h-7 w-7 sm:h-8 sm:w-8 md:h-9 md:w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500">Consultas</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800">
                        {{ number_format($consultasHoy) }}
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500 truncate">
                        Pacientes semana: {{ $pacientesSemana }}
                    </div>
                </div>

                {{-- Recetas Hoy --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                        <div
                            class="inline-flex h-7 w-7 sm:h-8 sm:w-8 md:h-9 md:w-9 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500">Recetas</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800">
                        {{ number_format($recetasHoy) }}
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500 truncate">
                        Exámenes: {{ $examenesHoy }}
                    </div>
                </div>

                {{-- Seguimiento Activo --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                        <div
                            class="inline-flex h-7 w-7 sm:h-8 sm:w-8 md:h-9 md:w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500">Seguimiento</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800">
                        {{ number_format($pacientesSeguimiento) }}
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500 truncate">
                        Referidos: {{ $pacientesReferidos }}
                    </div>
                </div>

                {{-- Urgencias --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                        <div
                            class="inline-flex h-7 w-7 sm:h-8 sm:w-8 md:h-9 md:w-9 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-medium text-slate-500">Urgencias</h3>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-800">
                        {{ number_format($urgenciasHoy) }}
                    </p>
                    <div class="mt-1 sm:mt-2 text-xs text-slate-500">
                        Atendidas hoy
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== AGENDA DEL MÉDICO ========== --}}
        <div id="agendaMedico" class="grid gap-3 sm:gap-4 mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">📅 Agenda Médica</h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
                {{-- Citas Programadas para Hoy --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-800">📋 Citas Hoy</h4>
                        <span
                            class="px-2 py-0.5 sm:px-2 sm:py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">
                            {{ $citasHoy->count() }} citas
                        </span>
                    </div>

                    @if ($citasHoy->isNotEmpty())
                        <div
                            class="space-y-2 sm:space-y-3 max-h-64 sm:max-h-72 md:max-h-80 overflow-y-auto pr-1 sm:pr-2 scroll-custom">
                            @foreach ($citasHoy as $cita)
                                <div
                                    class="p-2 sm:p-3 border border-slate-100 rounded-lg hover:bg-slate-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-slate-800 text-xs sm:text-sm truncate">
                                                {{ $cita->nombre }} {{ $cita->apellido }}
                                                <span class="text-xs text-slate-500 ml-1 sm:ml-2 whitespace-nowrap">
                                                    {{ $cita->edad }}a, {{ $cita->sexo == 'M' ? '♂' : '♀' }}
                                                </span>
                                            </div>
                                            <div class="mt-1 flex flex-wrap items-center gap-1 sm:gap-2">
                                                <span
                                                    class="text-xs bg-blue-50 text-blue-700 px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($cita->citado)->format('H:i') }}
                                                </span>
                                                @if ($cita->resultado)
                                                    <span
                                                        class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded whitespace-nowrap">
                                                        {{ ucfirst($cita->resultado) }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($cita->impresion_diagnostica)
                                                <div class="mt-1 sm:mt-2 text-xs text-slate-600 truncate"
                                                    title="{{ $cita->impresion_diagnostica }}">
                                                    <span class="font-medium hidden sm:inline">Dx:</span>
                                                    <span class="sm:hidden">📋</span>
                                                    {{ Str::limit($cita->impresion_diagnostica, 35) }}
                                                </div>
                                            @endif
                                        </div>
                                        <span
                                            class="ml-1 sm:ml-2 px-1.5 py-0.5 sm:px-2 sm:py-1 {{ $cita->urgencia == 'si' ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }} text-xs rounded whitespace-nowrap">
                                            {{ $cita->urgencia == 'si' ? 'Urgente' : 'Normal' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <div class="text-2xl sm:text-3xl mb-2">📅</div>
                            <p class="text-xs sm:text-sm text-slate-500">No hay citas programadas hoy</p>
                            <p class="text-xs text-slate-400 mt-1">
                                Consultas hoy: <span class="font-medium">{{ $consultasHoy }}</span>
                            </p>
                        </div>
                    @endif

                    @if ($citasAtrasadas > 0)
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-amber-600 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="hidden sm:inline">Citas atrasadas:</span>
                                    <span class="sm:hidden">Atrasadas:</span>
                                </span>
                                <span class="font-medium text-slate-800">{{ $citasAtrasadas }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Citas Próximas --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-800">📅 Próximas Citas</h4>
                        <span
                            class="px-2 py-0.5 sm:px-2 sm:py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">
                            7 días
                        </span>
                    </div>

                    @if ($citasProximas->isNotEmpty())
                        <div class="space-y-2 sm:space-y-3">
                            @foreach ($citasProximas as $cita)
                                @php
                                    $fechaCita = \Carbon\Carbon::parse($cita->citado);
                                    $diasDiferencia = $fechaCita->diffInDays($hoy);
                                @endphp
                                <div
                                    class="flex items-center justify-between p-2 sm:p-3 border border-slate-100 rounded-lg hover:bg-slate-50">
                                    <div class="min-w-0">
                                        <div class="font-medium text-slate-800 text-xs sm:text-sm truncate">
                                            {{ $cita->nombre }} {{ $cita->apellido }}
                                        </div>
                                        <div class="flex items-center gap-1 sm:gap-2 mt-0.5 sm:mt-1">
                                            <span
                                                class="text-xs font-medium {{ $diasDiferencia <= 2 ? 'text-amber-600' : 'text-slate-500' }}">
                                                {{ $fechaCita->format('d/m') }}
                                            </span>
                                            <span class="text-xs text-slate-500">
                                                {{ $fechaCita->format('H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end ml-2">
                                        <span
                                            class="text-xs {{ $diasDiferencia <= 2 ? 'text-amber-600 font-medium' : 'text-slate-500' }}">
                                            @if ($diasDiferencia == 1)
                                                Mañana
                                            @elseif($diasDiferencia == 0)
                                                Hoy
                                            @else
                                                {{ $diasDiferencia }}d
                                            @endif
                                        </span>
                                        @if ($cita->urgencia == 'si')
                                            <span class="text-xs text-rose-600 mt-0.5">
                                                Urgente
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <div class="text-2xl sm:text-3xl mb-2">📋</div>
                            <p class="text-xs sm:text-sm text-slate-500">No hay citas próximas</p>
                        </div>
                    @endif

                    {{-- Agenda próxima semana --}}
                    @if ($agendaProximaSemana->isNotEmpty())
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-100">
                            <h5 class="text-xs font-medium text-slate-700 mb-2">📊 Próxima semana</h5>
                            <div class="space-y-1 sm:space-y-2">
                                @foreach ($agendaProximaSemana as $dia)
                                    @php
                                        $fecha = \Carbon\Carbon::parse($dia->fecha);
                                        $nombreDia = $fecha->locale('es')->dayName;
                                        $esManana = $fecha->isTomorrow();
                                    @endphp
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-600 {{ $esManana ? 'font-medium' : '' }} truncate">
                                            <span class="hidden sm:inline">{{ ucfirst($nombreDia) }}</span>
                                            <span class="sm:hidden">{{ substr($nombreDia, 0, 3) }}</span>
                                            {{ $fecha->format('d/m') }}
                                        </span>
                                        <span
                                            class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 bg-slate-100 text-slate-700 rounded font-medium ml-1">
                                            {{ $dia->total }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Seguimiento Próximo --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-800">🔄 Seguimiento</h4>
                        <span
                            class="px-2 py-0.5 sm:px-2 sm:py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                            {{ $pacientesSeguimiento }} p
                        </span>
                    </div>

                    @if ($seguimientoProximo->isNotEmpty())
                        <div class="space-y-2 sm:space-y-3">
                            @foreach ($seguimientoProximo as $seguimiento)
                                @php
                                    $fechaCita = \Carbon\Carbon::parse($seguimiento->citado);
                                    $diasFaltantes = $fechaCita->diffInDays($hoy);
                                @endphp
                                <div class="p-2 sm:p-3 border border-purple-50 rounded-lg bg-purple-50/30">
                                    <div class="font-medium text-slate-800 text-xs sm:text-sm mb-0.5 sm:mb-1 truncate">
                                        {{ $seguimiento->nombre }} {{ $seguimiento->apellido }}
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-purple-600 font-medium">
                                            {{ $fechaCita->format('d/m H:i') }}
                                        </span>
                                        <span class="text-slate-500 whitespace-nowrap">
                                            @if ($diasFaltantes == 0)
                                                Hoy
                                            @elseif($diasFaltantes == 1)
                                                Mañana
                                            @else
                                                {{ $diasFaltantes }} días
                                            @endif
                                        </span>
                                    </div>
                                    @if ($seguimiento->impresion_diagnostica)
                                        <div class="mt-1 sm:mt-2 text-xs text-slate-600 truncate">
                                            {{ Str::limit($seguimiento->impresion_diagnostica, 35) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <div class="text-2xl sm:text-3xl mb-2">🔄</div>
                            <p class="text-xs sm:text-sm text-slate-500">No hay seguimientos próximos</p>
                        </div>
                    @endif

                    <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-100">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="text-center p-1.5 sm:p-2 bg-blue-50 rounded">
                                <div class="font-bold text-blue-700">{{ $pacientesReferidos }}</div>
                                <div class="text-slate-600">Referidos</div>
                            </div>
                            <div class="text-center p-1.5 sm:p-2 bg-emerald-50 rounded">
                                <div class="font-bold text-emerald-700">{{ $pacientesSeguimiento }}</div>
                                <div class="text-slate-600">Seguimiento</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== CONSULTAS RECIENTES Y DIAGNÓSTICOS ========== --}}
        <div id="consultasDiagnosticos" class="grid gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                {{-- Consultas Recientes --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-800">🩺 Consultas Recientes</h3>
                        <span
                            class="px-1.5 py-0.5 sm:px-2 sm:py-1 bg-slate-100 text-slate-600 text-xs rounded-full font-medium">
                            Últimas 5
                        </span>
                    </div>

                    @if ($consultasRecientes->isNotEmpty())
                        <div class="space-y-2 sm:space-y-3">
                            @foreach ($consultasRecientes as $consulta)
                                @php
                                    $fechaConsulta = \Carbon\Carbon::parse($consulta->created_at);
                                    $esHoy = $fechaConsulta->isToday();
                                @endphp
                                <div class="border-b border-slate-100 last:border-0 pb-2 sm:pb-3 last:pb-0">
                                    <div class="flex items-center justify-between mb-0.5 sm:mb-1">
                                        <div class="font-medium text-slate-800 text-xs sm:text-sm truncate">
                                            {{ $consulta->nombre }} {{ $consulta->apellido }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @if ($consulta->urgencia == 'si')
                                                <span
                                                    class="px-1 py-0.5 bg-rose-50 text-rose-700 text-xs rounded whitespace-nowrap">
                                                    Urgente
                                                </span>
                                            @endif
                                            <span class="text-xs text-slate-500 whitespace-nowrap">
                                                @if ($esHoy)
                                                    Hoy {{ $fechaConsulta->format('H:i') }}
                                                @else
                                                    {{ $fechaConsulta->format('d/m H:i') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    @if ($consulta->impresion_diagnostica)
                                        <div class="text-xs text-slate-600 truncate"
                                            title="{{ $consulta->impresion_diagnostica }}">
                                            📋 {{ Str::limit($consulta->impresion_diagnostica, 50) }}
                                        </div>
                                    @else
                                        <div class="text-xs text-slate-400 italic">Sin diagnóstico</div>
                                    @endif
                                    @if ($consulta->resultado)
                                        <div class="mt-1">
                                            <span
                                                class="text-xs px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded 
                                    {{ $consulta->resultado == 'seguimiento'
                                        ? 'bg-blue-50 text-blue-700'
                                        : ($consulta->resultado == 'alta'
                                            ? 'bg-emerald-50 text-emerald-700'
                                            : 'bg-amber-50 text-amber-700') }}">
                                                {{ ucfirst($consulta->resultado) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <div class="text-2xl sm:text-3xl mb-2">📝</div>
                            <p class="text-xs sm:text-sm text-slate-500">No hay consultas recientes</p>
                        </div>
                    @endif
                </div>

                {{-- Diagnósticos Comunes --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-800">📋 Diagnósticos Más Comunes</h3>
                        <span
                            class="px-1.5 py-0.5 sm:px-2 sm:py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">
                            Top 5
                        </span>
                    </div>

                    @if ($diagnosticosComunes->isNotEmpty())
                        <div class="space-y-2 sm:space-y-3">
                            @foreach ($diagnosticosComunes as $diagnostico)
                                <div class="flex items-center justify-between p-1.5 sm:p-2 hover:bg-slate-50 rounded">
                                    <div class="text-xs sm:text-sm text-slate-700 truncate flex-1 pr-2"
                                        title="{{ $diagnostico->impresion_diagnostica }}">
                                        <span class="hidden sm:inline">{{ $loop->iteration }}. </span>
                                        {{ Str::limit($diagnostico->impresion_diagnostica, 40) }}
                                    </div>
                                    <div class="flex items-center gap-1 sm:gap-2">
                                        <span
                                            class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 bg-indigo-50 text-indigo-700 rounded text-xs font-medium min-w-6 sm:min-w-8 text-center">
                                            {{ $diagnostico->total }}
                                        </span>
                                        <span class="text-xs text-slate-500 hidden sm:inline">
                                            {{ $diagnostico->total > 1 ? 'casos' : 'caso' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <div class="text-2xl sm:text-3xl mb-2">📊</div>
                            <p class="text-xs sm:text-sm text-slate-500">No hay datos diagnósticos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========== ESTADÍSTICAS DE CITAS ========== --}}
        <div id="estadisticasCitas" class="grid gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                {{-- Distribución de Resultados --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h4 class="text-xs sm:text-sm font-semibold text-slate-800 mb-2 sm:mb-3">📊 Resultados (30 días)
                    </h4>

                    @if ($citasPorResultado->isNotEmpty())
                        <div class="space-y-2 sm:space-y-3">
                            @foreach ($citasPorResultado as $resultado)
                                @php
                                    $total = $citasPorResultado->sum('total');
                                    $porcentaje = $total > 0 ? ($resultado->total / $total) * 100 : 0;
                                    $colores = [
                                        'seguimiento' => 'bg-blue-500',
                                        'alta' => 'bg-emerald-500',
                                        'referido' => 'bg-amber-500',
                                    ];
                                    $color = $colores[$resultado->resultado] ?? 'bg-slate-500';
                                    $textos = [
                                        'seguimiento' => 'Seguimiento',
                                        'alta' => 'Alta',
                                        'referido' => 'Referido',
                                    ];
                                    $texto = $textos[$resultado->resultado] ?? $resultado->resultado;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-0.5 sm:mb-1">
                                        <span
                                            class="text-xs sm:text-sm text-slate-700 truncate">{{ $texto }}</span>
                                        <div class="flex items-center gap-1 sm:gap-2">
                                            <span
                                                class="text-xs sm:text-sm font-bold text-slate-800">{{ $resultado->total }}</span>
                                            <span
                                                class="text-xs text-slate-500">{{ number_format($porcentaje, 1) }}%</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 sm:h-2">
                                        <div class="{{ $color }} h-1.5 sm:h-2 rounded-full"
                                            style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 sm:py-6">
                            <p class="text-xs sm:text-sm text-slate-500">No hay datos de resultados</p>
                        </div>
                    @endif
                </div>

                {{-- Demografía de Pacientes con Cita --}}
                <div class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50">
                    <h4 class="text-xs sm:text-sm font-semibold text-slate-800 mb-2 sm:mb-3">👥 Pacientes con Cita</h4>

                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                        {{-- Distribución por Sexo --}}
                        <div>
                            <h5 class="text-xs font-medium text-slate-700 mb-2">Distribución por sexo</h5>
                            @if ($distribucionSexo->isNotEmpty())
                                <div class="space-y-1.5 sm:space-y-2">
                                    @foreach ($distribucionSexo as $item)
                                        @php
                                            $total = $distribucionSexo->sum('total');
                                            $porcentaje = $total > 0 ? ($item->total / $total) * 100 : 0;
                                            $icono = $item->sexo == 'M' ? '♂' : '♀';
                                            $color = $item->sexo == 'M' ? 'text-blue-600' : 'text-pink-600';
                                        @endphp
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1.5 sm:gap-2">
                                                <span class="{{ $color }}">{{ $icono }}</span>
                                                <span class="text-xs sm:text-sm text-slate-700 truncate">
                                                    {{ $item->sexo == 'M' ? 'Hombres' : 'Mujeres' }}
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs sm:text-sm font-bold text-slate-800">
                                                    {{ $item->total }}</div>
                                                <div class="text-xs text-slate-500">
                                                    {{ number_format($porcentaje, 1) }}%</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-500">No hay datos</p>
                            @endif
                        </div>

                        {{-- Edad Promedio --}}
                        <div class="text-center">
                            <h5 class="text-xs font-medium text-slate-700 mb-2">Edad promedio</h5>
                            @if ($edadPromedio && $edadPromedio->promedio)
                                <div class="text-2xl sm:text-3xl font-bold text-slate-800 mb-0.5 sm:mb-1">
                                    {{ number_format($edadPromedio->promedio, 1) }}
                                </div>
                                <div class="text-xs text-slate-500">años</div>
                            @else
                                <p class="text-xs text-slate-500">No hay datos</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== TIPOS DE URGENCIA ========== --}}
        <div id="tiposUrgencia"
            class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 mb-6 sm:mb-8">
            <h3 class="text-xs sm:text-sm font-semibold text-slate-800 mb-3 sm:mb-4">🚨 Tipos de Urgencia (30 días)
            </h3>

            @if ($tiposUrgencia->isNotEmpty())
                <div class="space-y-2 sm:space-y-3">
                    @foreach ($tiposUrgencia as $urgencia)
                        <div>
                            <div class="flex items-center justify-between mb-0.5 sm:mb-1">
                                <span class="text-xs sm:text-sm text-slate-700 capitalize truncate">
                                    @php
                                        $tipos = [
                                            'medica' => 'Médica',
                                            'pediatrica' => 'Pediatría',
                                            'quirurgico' => 'Quirúrgico',
                                            'gineco obstetrica' => 'Gineco Obst.',
                                        ];
                                        echo $tipos[$urgencia->tipo_urgencia] ?? $urgencia->tipo_urgencia;
                                    @endphp
                                </span>
                                <span
                                    class="text-xs sm:text-sm font-bold text-slate-800 whitespace-nowrap">{{ $urgencia->total }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 sm:h-2">
                                @php
                                    $totalUrgencias = $tiposUrgencia->sum('total');
                                    $porcentaje = $totalUrgencias > 0 ? ($urgencia->total / $totalUrgencias) * 100 : 0;
                                    $colors = [
                                        'medica' => 'bg-rose-500',
                                        'pediatrica' => 'bg-blue-500',
                                        'quirurgico' => 'bg-purple-500',
                                        'gineco obstetrica' => 'bg-pink-500',
                                    ];
                                    $color = $colors[$urgencia->tipo_urgencia] ?? 'bg-slate-500';
                                @endphp
                                <div class="{{ $color }} h-1.5 sm:h-2 rounded-full"
                                    style="width: {{ $porcentaje }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 sm:py-6">
                    <div class="text-2xl sm:text-3xl mb-2">⚠️</div>
                    <p class="text-xs sm:text-sm text-slate-500">No hay urgencias registradas</p>
                </div>
            @endif
        </div>

        {{-- ========== HERRAMIENTAS MÉDICAS EXPANDIBLES ========== --}}
        <div id="herramientasMedicas"
            class="bg-white p-3 sm:p-4 md:p-5 rounded-xl shadow-sm ring-1 ring-slate-200/50 mb-6 sm:mb-8">
            <h3 class="text-xs sm:text-sm font-semibold text-slate-800 mb-3 sm:mb-4">⚡ Herramientas Médicas</h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 mb-4 sm:mb-6">
                {{-- Calculadora IMC --}}
                <button onclick="toggleHerramienta('imc')"
                    class="flex flex-col items-center justify-center p-2 sm:p-3 md:p-4 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-all duration-200 group">
                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2 group-hover:scale-110 transition-transform">⚖️
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-center">IMC</div>
                </button>

                {{-- Historial del Paciente --}}
                <button onclick="toggleHerramienta('historial')"
                    class="flex flex-col items-center justify-center p-2 sm:p-3 md:p-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition-all duration-200 group">
                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2 group-hover:scale-110 transition-transform">📊
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-center">Historial</div>
                </button>

                {{-- Comparativa --}}
                <button onclick="toggleHerramienta('comparativa')"
                    class="flex flex-col items-center justify-center p-2 sm:p-3 md:p-4 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition-all duration-200 group">
                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2 group-hover:scale-110 transition-transform">📈
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-center">Comparar</div>
                </button>

                {{-- Riesgos --}}
                <button onclick="toggleHerramienta('riesgos')"
                    class="flex flex-col items-center justify-center p-2 sm:p-3 md:p-4 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition-all duration-200 group">
                    <div class="text-xl sm:text-2xl mb-1 sm:mb-2 group-hover:scale-110 transition-transform">⚠️
                    </div>
                    <div class="text-xs sm:text-sm font-medium text-center">Riesgos</div>
                </button>
            </div>

            {{-- ==================== SECCIONES DESPLEGABLES ==================== --}}

            {{-- 1. CALCULADORA IMC --}}
            <div id="seccion-imc"
                class="hidden mt-3 sm:mt-4 p-3 sm:p-4 border border-blue-100 rounded-lg bg-blue-50/30">
                <div class="flex justify-between items-center mb-2 sm:mb-3">
                    <h4 class="text-xs sm:text-sm font-semibold text-blue-800 truncate">⚖️ Calculadora IMC</h4>
                    <button onclick="toggleHerramienta('imc')" class="text-blue-600 hover:text-blue-800 text-sm">
                        ✕
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                    {{-- Entrada de datos --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📏 Datos</h5>
                        <div class="space-y-2 sm:space-y-3">
                            <div>
                                <label class="block text-xs text-slate-600 mb-1">Peso (kg)</label>
                                <input type="number" id="peso-imc" placeholder="Ej: 70"
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-600 mb-1">Altura (cm)</label>
                                <input type="number" id="altura-imc" placeholder="Ej: 175"
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-600 mb-1">Edad (opcional)</label>
                                <input type="number" id="edad-imc" placeholder="Ej: 35"
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                            </div>
                            <button onclick="calcularIMC()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">
                                Calcular IMC
                            </button>
                        </div>
                    </div>

                    {{-- Resultados --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📊 Resultado</h5>
                        <div id="resultado-imc" class="text-center py-4 sm:py-6">
                            <div class="text-xl sm:text-2xl font-bold text-blue-600 mb-1">--</div>
                            <div class="text-xs sm:text-sm text-slate-600 mb-2 sm:mb-3">IMC</div>
                            <div id="categoria-imc"
                                class="px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm inline-block bg-slate-100 text-slate-600">
                                Ingrese datos
                            </div>
                            <div id="detalles-imc" class="mt-3 sm:mt-4 text-xs text-slate-500 space-y-0.5">
                                <div>Peso ideal: -- kg</div>
                                <div>Rango saludable: -- kg</div>
                            </div>
                        </div>
                    </div>

                    {{-- Clasificación OMS --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📋 Clasificación OMS</h5>
                        <div class="space-y-0.5 text-xs">
                            @foreach ([['color' => 'text-rose-600', 'text' => '▼ Bajo peso', 'rango' => '< 18.5'], ['color' => 'text-green-600', 'text' => '✓ Normal', 'rango' => '18.5 - 24.9', 'bg' => 'bg-green-50'], ['color' => 'text-amber-600', 'text' => '▲ Sobrepeso', 'rango' => '25 - 29.9'], ['color' => 'text-orange-600', 'text' => '● Obesidad I', 'rango' => '30 - 34.9'], ['color' => 'text-red-600', 'text' => '● Obesidad II', 'rango' => '35 - 39.9'], ['color' => 'text-red-800', 'text' => '● Obesidad III', 'rango' => '≥ 40']] as $item)
                                <div
                                    class="flex justify-between items-center p-0.5 sm:p-1 {{ $item['bg'] ?? '' }} rounded">
                                    <span class="{{ $item['color'] }} truncate">{{ $item['text'] }}</span>
                                    <span
                                        class="font-medium text-slate-700 whitespace-nowrap">{{ $item['rango'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. HISTORIAL DEL PACIENTE --}}
            <div id="seccion-historial"
                class="hidden mt-3 sm:mt-4 p-3 sm:p-4 border border-emerald-100 rounded-lg bg-emerald-50/30">
                <div class="flex justify-between items-center mb-2 sm:mb-3">
                    <h4 class="text-xs sm:text-sm font-semibold text-emerald-800 truncate">📊 Historial de
                        Mediciones</h4>
                    <button onclick="toggleHerramienta('historial')"
                        class="text-emerald-600 hover:text-emerald-800 text-sm">
                        ✕
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                    {{-- Buscar paciente --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">👤 Seleccionar paciente</h5>
                        <div class="space-y-2 sm:space-y-3">
                            <div>
                                <label class="block text-xs text-slate-600 mb-1">Buscar por nombre o DNI</label>
                                <input type="text" id="buscar-paciente" placeholder="Ej: Juan Pérez o 12345678"
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                            </div>
                            <div id="lista-pacientes"
                                class="max-h-32 sm:max-h-40 overflow-y-auto space-y-1.5 sm:space-y-2 scroll-custom">
                                {{-- Aquí se cargarían los pacientes dinámicamente --}}
                                <div
                                    class="p-1.5 sm:p-2 border border-slate-100 rounded hover:bg-slate-50 cursor-pointer">
                                    <div class="text-xs sm:text-sm font-medium truncate">Juan Pérez Rodríguez</div>
                                    <div class="text-xs text-slate-500 truncate">DNI: 12345678 • 35 años</div>
                                </div>
                                <div
                                    class="p-1.5 sm:p-2 border border-slate-100 rounded hover:bg-slate-50 cursor-pointer">
                                    <div class="text-xs sm:text-sm font-medium truncate">María González López</div>
                                    <div class="text-xs text-slate-500 truncate">DNI: 87654321 • 42 años</div>
                                </div>
                                <div
                                    class="p-1.5 sm:p-2 border border-slate-100 rounded hover:bg-slate-50 cursor-pointer">
                                    <div class="text-xs sm:text-sm font-medium truncate">Carlos Martínez Ruiz</div>
                                    <div class="text-xs text-slate-500 truncate">DNI: 45678912 • 28 años</div>
                                </div>
                            </div>
                            <button onclick="cargarHistorial()"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">
                                Ver Historial
                            </button>
                        </div>
                    </div>

                    {{-- Historial de mediciones --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📈 Evolución del IMC</h5>
                        <div id="grafico-evolucion"
                            class="h-40 sm:h-48 flex items-center justify-center text-slate-400">
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">📊</div>
                                <p class="text-xs sm:text-sm text-slate-500">Seleccione un paciente</p>
                            </div>
                        </div>
                        <div id="tabla-historial" class="mt-3 sm:mt-4">
                            <div class="text-xs text-slate-700 font-medium mb-1 sm:mb-2">Últimas mediciones</div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="text-left py-1.5 sm:py-2">Fecha</th>
                                            <th class="text-left py-1.5 sm:py-2">Peso</th>
                                            <th class="text-left py-1.5 sm:py-2">Altura</th>
                                            <th class="text-left py-1.5 sm:py-2">IMC</th>
                                        </tr>
                                    </thead>
                                    <tbody id="datos-historial">
                                        <!-- Datos cargados dinámicamente -->
                                        <tr>
                                            <td colspan="4" class="text-center py-3 sm:py-4 text-slate-400">
                                                Seleccione un paciente
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. COMPARATIVA --}}
            <div id="seccion-comparativa"
                class="hidden mt-3 sm:mt-4 p-3 sm:p-4 border border-amber-100 rounded-lg bg-amber-50/30">
                <div class="flex justify-between items-center mb-2 sm:mb-3">
                    <h4 class="text-xs sm:text-sm font-semibold text-amber-800 truncate">📈 Comparativa de
                        Mediciones</h4>
                    <button onclick="toggleHerramienta('comparativa')"
                        class="text-amber-600 hover:text-amber-800 text-sm">
                        ✕
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                    {{-- Selección de fechas --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📅 Comparar períodos</h5>
                        <div class="space-y-3 sm:space-y-4">
                            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">Desde</label>
                                    <input type="date"
                                        class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-600 mb-1">Hasta</label>
                                    <input type="date"
                                        class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                                </div>
                            </div>

                            <div>
                                <h6 class="text-xs font-medium text-slate-700 mb-1.5 sm:mb-2">Parámetros a comparar
                                </h6>
                                <div class="space-y-1.5 sm:space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-1.5 sm:mr-2 w-3 h-3 sm:w-4 sm:h-4">
                                        <span class="text-xs">Peso (kg)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-1.5 sm:mr-2 w-3 h-3 sm:w-4 sm:h-4">
                                        <span class="text-xs">IMC</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-1.5 sm:mr-2 w-3 h-3 sm:w-4 sm:h-4">
                                        <span class="text-xs">Circunferencia cintura</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-1.5 sm:mr-2 w-3 h-3 sm:w-4 sm:h-4">
                                        <span class="text-xs">Presión arterial</span>
                                    </label>
                                </div>
                            </div>

                            <button onclick="generarComparativa()"
                                class="w-full bg-amber-600 hover:bg-amber-700 text-white py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">
                                Generar Comparativa
                            </button>
                        </div>
                    </div>

                    {{-- Resultados comparativos --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📊 Resultados comparativos</h5>
                        <div id="comparativa-resultados" class="space-y-2 sm:space-y-3">
                            <div class="p-2.5 sm:p-3 bg-blue-50 rounded">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs sm:text-sm font-medium">Peso</span>
                                    <span class="text-xs sm:text-sm font-bold text-blue-700">-2.5 kg</span>
                                </div>
                                <div class="text-xs text-slate-600 mt-0.5 sm:mt-1">
                                    <span>De 75.0 kg a 72.5 kg</span>
                                </div>
                            </div>

                            <div class="p-2.5 sm:p-3 bg-emerald-50 rounded">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs sm:text-sm font-medium">IMC</span>
                                    <span class="text-xs sm:text-sm font-bold text-emerald-700">-0.9</span>
                                </div>
                                <div class="text-xs text-slate-600 mt-0.5 sm:mt-1">
                                    <span>De 24.8 a 23.9 (Mejora)</span>
                                </div>
                            </div>

                            <div class="p-2.5 sm:p-3 bg-slate-50 rounded">
                                <div class="text-center py-3 sm:py-4 text-slate-400">
                                    <p class="text-xs sm:text-sm">Seleccione períodos para comparar</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-100">
                            <h6 class="text-xs font-medium text-slate-700 mb-1.5 sm:mb-2">📝 Observaciones</h6>
                            <div class="text-xs text-slate-600">
                                <p>La comparativa muestra la evolución entre los períodos seleccionados. Valores
                                    positivos indican aumento, negativos indican reducción.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. RIESGOS ASOCIADOS --}}
            <div id="seccion-riesgos"
                class="hidden mt-3 sm:mt-4 p-3 sm:p-4 border border-rose-100 rounded-lg bg-rose-50/30">
                <div class="flex justify-between items-center mb-2 sm:mb-3">
                    <h4 class="text-xs sm:text-sm font-semibold text-rose-800 truncate">⚠️ Riesgos Asociados por
                        Categoría</h4>
                    <button onclick="toggleHerramienta('riesgos')" class="text-rose-600 hover:text-rose-800 text-sm">
                        ✕
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                    {{-- Calculadora de riesgo --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📝 Evaluación de Riesgos</h5>
                        <div class="space-y-2 sm:space-y-3">
                            <div>
                                <label class="block text-xs text-slate-600 mb-1">IMC Calculado</label>
                                <input type="number" id="imc-riesgo" placeholder="Ingrese IMC"
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                            </div>

                            <div>
                                <label class="block text-xs text-slate-600 mb-1">Edad</label>
                                <input type="number" id="edad-riesgo" placeholder="Años"
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                            </div>

                            <div>
                                <label class="block text-xs text-slate-600 mb-1">Historial médico</label>
                                <select id="historial-medico"
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                                    <option>Sin historial significativo</option>
                                    <option>Hipertensión</option>
                                    <option>Diabetes tipo 2</option>
                                    <option>Dislipidemia</option>
                                    <option>Enfermedad cardiovascular</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs text-slate-600 mb-1">Tabaco</label>
                                <select
                                    class="w-full px-2 py-1.5 sm:px-3 sm:py-2 border border-slate-200 rounded-lg text-sm">
                                    <option>No fumador</option>
                                    <option>Ex-fumador</option>
                                    <option>Fumador ocasional</option>
                                    <option>Fumador habitual</option>
                                </select>
                            </div>

                            <button onclick="calcularRiesgos()"
                                class="w-full bg-rose-600 hover:bg-rose-700 text-white py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition">
                                Evaluar Riesgos
                            </button>
                        </div>
                    </div>

                    {{-- Resultados de riesgos --}}
                    <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm">
                        <h5 class="text-xs font-medium text-slate-700 mb-2 sm:mb-3">📋 Riesgos Identificados</h5>
                        <div id="lista-riesgos" class="space-y-1.5 sm:space-y-2">
                            <div class="p-1.5 sm:p-2 border-l-4 border-green-500 bg-green-50">
                                <div class="text-xs font-medium text-green-800">✓ Riesgo bajo</div>
                                <div class="text-xs text-green-700">Enfermedad cardiovascular</div>
                            </div>

                            <div class="p-1.5 sm:p-2 border-l-4 border-amber-500 bg-amber-50">
                                <div class="text-xs font-medium text-amber-800">⚠️ Riesgo moderado</div>
                                <div class="text-xs text-amber-700">Diabetes tipo 2</div>
                            </div>

                            <div class="p-1.5 sm:p-2 border-l-4 border-rose-500 bg-rose-50">
                                <div class="text-xs font-medium text-rose-800">🔥 Riesgo alto</div>
                                <div class="text-xs text-rose-700">Hipertensión arterial</div>
                            </div>

                            <div class="p-2.5 sm:p-3 text-center text-slate-400">
                                <p class="text-xs sm:text-sm">Ingrese datos para evaluar riesgos</p>
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-100">
                            <h6 class="text-xs font-medium text-slate-700 mb-1.5 sm:mb-2">🎯 Recomendaciones</h6>
                            <ul id="recomendaciones-riesgo" class="text-xs text-slate-600 space-y-0.5 sm:space-y-1">
                                <li class="flex items-start">
                                    <span class="mr-1 sm:mr-2">•</span>
                                    <span>Control de peso cada 3 meses</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-1 sm:mr-2">•</span>
                                    <span>Monitoreo de presión arterial</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-1 sm:mr-2">•</span>
                                    <span>Exámenes de glucosa anuales</span>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-600">Nivel de riesgo general:</span>
                                <span id="nivel-riesgo-general" class="font-medium text-slate-800">No
                                    evaluado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Scripts --}}
    {{-- Scripts para herramientas médicas --}}
    <script>
        // Estado de herramientas abiertas
        let herramientaActiva = null;

        // Alternar entre herramientas
        function toggleHerramienta(herramienta) {
            // Cerrar todas las secciones primero
            document.querySelectorAll('[id^="seccion-"]').forEach(seccion => {
                seccion.classList.add('hidden');
            });

            // Si ya está abierta, cerrarla
            if (herramientaActiva === herramienta) {
                herramientaActiva = null;
                return;
            }

            // Abrir la herramienta seleccionada
            const seccion = document.getElementById(`seccion-${herramienta}`);
            if (seccion) {
                seccion.classList.remove('hidden');
                herramientaActiva = herramienta;

                // Scroll suave a la sección en móviles
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        seccion.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 100);
                }
            }
        }

        // Cálculo de IMC
        function calcularIMC() {
            const peso = parseFloat(document.getElementById('peso-imc').value);
            const alturaCm = parseFloat(document.getElementById('altura-imc').value);

            if (!peso || !alturaCm || alturaCm <= 0) {
                alert('Por favor ingrese peso y altura válidos');
                return;
            }

            const alturaM = alturaCm / 100;
            const imc = peso / (alturaM * alturaM);
            const imcRedondeado = imc.toFixed(1);

            // Determinar categoría
            let categoria = '';
            let color = '';
            let pesoIdealMin = (18.5 * (alturaM * alturaM)).toFixed(1);
            let pesoIdealMax = (24.9 * (alturaM * alturaM)).toFixed(1);

            if (imc < 18.5) {
                categoria = 'Bajo peso';
                color = 'bg-rose-100 text-rose-800';
            } else if (imc < 25) {
                categoria = 'Peso normal';
                color = 'bg-green-100 text-green-800';
            } else if (imc < 30) {
                categoria = 'Sobrepeso';
                color = 'bg-amber-100 text-amber-800';
            } else if (imc < 35) {
                categoria = 'Obesidad I';
                color = 'bg-orange-100 text-orange-800';
            } else if (imc < 40) {
                categoria = 'Obesidad II';
                color = 'bg-red-100 text-red-800';
            } else {
                categoria = 'Obesidad III';
                color = 'bg-red-200 text-red-900';
            }

            // Actualizar UI
            const resultadoElement = document.querySelector('#resultado-imc .text-xl, #resultado-imc .text-2xl');
            if (resultadoElement) resultadoElement.textContent = imcRedondeado;

            document.getElementById('categoria-imc').textContent = categoria;
            document.getElementById('categoria-imc').className =
                `px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm inline-block font-medium ${color}`;

            document.getElementById('detalles-imc').innerHTML = `
            <div>Peso ideal: ${pesoIdealMin} - ${pesoIdealMax} kg</div>
            <div>Variación: ${(peso - pesoIdealMax).toFixed(1)} kg</div>
        `;
        }

        // Cargar historial del paciente
        function cargarHistorial() {
            // Simulación de carga de datos
            const datosHistorial = [{
                    fecha: '2024-03-01',
                    peso: 78,
                    altura: 175,
                    imc: 25.5
                },
                {
                    fecha: '2024-02-01',
                    peso: 76,
                    altura: 175,
                    imc: 24.8
                },
                {
                    fecha: '2024-01-01',
                    peso: 80,
                    altura: 175,
                    imc: 26.1
                },
                {
                    fecha: '2023-12-01',
                    peso: 82,
                    altura: 175,
                    imc: 26.8
                }
            ];

            const tabla = document.getElementById('datos-historial');
            tabla.innerHTML = datosHistorial.map(dato => `
            <tr class="border-b border-slate-100">
                <td class="py-1.5 sm:py-2">${dato.fecha}</td>
                <td class="py-1.5 sm:py-2">${dato.peso} kg</td>
                <td class="py-1.5 sm:py-2">${dato.altura} cm</td>
                <td class="py-1.5 sm:py-2">
                    <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded text-xs 
                        ${dato.imc < 25 ? 'bg-green-100 text-green-800' : 
                          dato.imc < 30 ? 'bg-amber-100 text-amber-800' : 
                          'bg-red-100 text-red-800'}">
                        ${dato.imc.toFixed(1)}
                    </span>
                </td>
            </tr>
        `).join('');

            // Actualizar gráfico (simulado)
            document.getElementById('grafico-evolucion').innerHTML = `
            <div class="w-full h-full flex flex-col justify-center">
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">📈</div>
                    <p class="text-xs sm:text-sm font-medium text-slate-700">Evolución del peso</p>
                    <p class="text-xs text-slate-500">De 82 kg a 78 kg (-4 kg)</p>
                    <div class="mt-2 w-full bg-slate-100 h-2 rounded-full">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        `;
        }

        // Generar comparativa
        function generarComparativa() {
            const resultados = document.getElementById('comparativa-resultados');
            resultados.innerHTML = `
            <div class="p-2.5 sm:p-3 bg-blue-50 rounded">
                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm font-medium">Peso (kg)</span>
                    <span class="text-xs sm:text-sm font-bold text-blue-700">-3.2 kg</span>
                </div>
                <div class="text-xs text-slate-600 mt-0.5 sm:mt-1">
                    <span>De 78.5 kg a 75.3 kg</span>
                </div>
            </div>
            
            <div class="p-2.5 sm:p-3 bg-emerald-50 rounded">
                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm font-medium">IMC</span>
                    <span class="text-xs sm:text-sm font-bold text-emerald-700">-1.1</span>
                </div>
                <div class="text-xs text-slate-600 mt-0.5 sm:mt-1">
                    <span>De 26.1 a 25.0 (Mejora significativa)</span>
                </div>
            </div>
            
            <div class="p-2.5 sm:p-3 bg-amber-50 rounded">
                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm font-medium">Circunferencia cintura</span>
                    <span class="text-xs sm:text-sm font-bold text-amber-700">-4 cm</span>
                </div>
                <div class="text-xs text-slate-600 mt-0.5 sm:mt-1">
                    <span>De 94 cm a 90 cm</span>
                </div>
            </div>
            
            <div class="mt-2 p-2 bg-slate-100 rounded">
                <div class="text-xs text-slate-700 text-center font-medium">
                    📈 Evolución positiva en todos los parámetros
                </div>
            </div>
        `;

            // Mostrar alerta de éxito
            alert('Comparativa generada exitosamente');
        }

        // Calcular riesgos
        function calcularRiesgos() {
            const imc = parseFloat(document.getElementById('imc-riesgo').value);
            const edad = parseInt(document.getElementById('edad-riesgo').value);
            const historial = document.getElementById('historial-medico').value;

            if (!imc || !edad) {
                alert('Por favor ingrese IMC y edad');
                return;
            }

            let riesgos = [];
            let recomendaciones = [];
            let nivelGeneral = 'bajo';

            // Evaluar riesgos basados en IMC
            if (imc >= 30) {
                riesgos.push({
                    riesgo: 'alto',
                    nombre: 'Diabetes tipo 2',
                    descripcion: 'Riesgo 3 veces mayor que peso normal'
                });
                riesgos.push({
                    riesgo: 'alto',
                    nombre: 'Hipertensión arterial',
                    descripcion: 'Riesgo 2.5 veces mayor'
                });
                nivelGeneral = 'alto';
                recomendaciones.push('Control de peso inmediato');
                recomendaciones.push('Evaluación cardiológica anual');
                recomendaciones.push('Consulta con nutricionista');
            } else if (imc >= 25) {
                riesgos.push({
                    riesgo: 'moderado',
                    nombre: 'Síndrome metabólico',
                    descripcion: 'Riesgo moderado, requiere seguimiento'
                });
                nivelGeneral = 'moderado';
                recomendaciones.push('Dieta balanceada y ejercicio regular');
                recomendaciones.push('Monitoreo trimestral');
                recomendaciones.push('Control de lípidos en sangre');
            } else {
                riesgos.push({
                    riesgo: 'bajo',
                    nombre: 'Riesgo general',
                    descripcion: 'Riesgo dentro de parámetros normales'
                });
                recomendaciones.push('Mantener hábitos saludables');
                recomendaciones.push('Chequeo anual preventivo');
            }

            // Evaluar por edad
            if (edad > 45 && imc >= 25) {
                riesgos.push({
                    riesgo: 'alto',
                    nombre: 'Enfermedad cardiovascular',
                    descripcion: 'Riesgo aumentado por edad e IMC'
                });
                nivelGeneral = nivelGeneral === 'moderado' ? 'alto' : nivelGeneral;
                recomendaciones.push('Exámenes cardíacos preventivos');
            }

            // Evaluar por historial médico
            if (historial !== 'Sin historial significativo') {
                riesgos.push({
                    riesgo: 'alto',
                    nombre: historial,
                    descripcion: 'Condición preexistente requiere control especial'
                });
                nivelGeneral = 'alto';
                recomendaciones.push('Seguimiento con especialista');
            }

            // Actualizar UI
            const listaRiesgos = document.getElementById('lista-riesgos');
            const listaRecomendaciones = document.getElementById('recomendaciones-riesgo');
            const nivelRiesgoGeneral = document.getElementById('nivel-riesgo-general');

            listaRiesgos.innerHTML = riesgos.map(riesgo => `
            <div class="p-1.5 sm:p-2 border-l-4 ${
                riesgo.riesgo === 'alto' ? 'border-rose-500 bg-rose-50' :
                riesgo.riesgo === 'moderado' ? 'border-amber-500 bg-amber-50' :
                'border-green-500 bg-green-50'
            }">
                <div class="text-xs font-medium ${
                    riesgo.riesgo === 'alto' ? 'text-rose-800' :
                    riesgo.riesgo === 'moderado' ? 'text-amber-800' :
                    'text-green-800'
                }">${
                    riesgo.riesgo === 'alto' ? '🔥 Riesgo alto' : 
                    riesgo.riesgo === 'moderado' ? '⚠️ Riesgo moderado' : 
                    '✓ Riesgo bajo'
                }</div>
                <div class="text-xs ${
                    riesgo.riesgo === 'alto' ? 'text-rose-700' :
                    riesgo.riesgo === 'moderado' ? 'text-amber-700' :
                    'text-green-700'
                }">${riesgo.nombre}</div>
            </div>
        `).join('');

            listaRecomendaciones.innerHTML = recomendaciones.map(rec =>
                `<li class="flex items-start">
                <span class="mr-1 sm:mr-2">•</span>
                <span class="text-xs">${rec}</span>
             </li>`
            ).join('');

            // Actualizar nivel general
            const niveles = {
                'alto': {
                    text: 'Alto',
                    color: 'text-rose-600'
                },
                'moderado': {
                    text: 'Moderado',
                    color: 'text-amber-600'
                },
                'bajo': {
                    text: 'Bajo',
                    color: 'text-emerald-600'
                }
            };

            nivelRiesgoGeneral.textContent = niveles[nivelGeneral].text;
            nivelRiesgoGeneral.className = `font-medium ${niveles[nivelGeneral].color}`;
        }

        // Cerrar herramientas al hacer clic fuera (solo en móviles)
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768 && herramientaActiva &&
                !event.target.closest('#herramientasMedicas')) {
                toggleHerramienta(herramientaActiva);
            }
        });

        // Exponer funciones al ámbito global
        window.toggleHerramienta = toggleHerramienta;
        window.calcularIMC = calcularIMC;
        window.cargarHistorial = cargarHistorial;
        window.generarComparativa = generarComparativa;
        window.calcularRiesgos = calcularRiesgos;
    </script>
</x-app-layout>
