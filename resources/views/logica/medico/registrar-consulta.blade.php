<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Registrar Consulta Médica
        </h2>
    </x-slot>

    {{-- Estilos para ancho completo --}}
    <style>
        div.max-w-7xl.mx-auto {
            max-width: 100% !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        @media (min-width: 640px) {
            div.max-w-7xl.mx-auto {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        @media (min-width: 1024px) {
            div.max-w-7xl.mx-auto {
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }
    </style>

    <div class="max-w-screen-2xl mx-auto px-4 py-4">

        {{-- Mensajes de error --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50/80 p-5 text-rose-800">
                <div class="flex items-center gap-2 font-semibold mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Revisa los siguientes puntos:
                </div>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            <script>
                if (window.Swal) {
                    Swal.fire({
                        title: 'Hay errores',
                        html: @json(implode('<br>', $errors->all())),
                        icon: 'error',
                        heightAuto: false
                    });
                }
            </script>
        @endif

        {{-- Buscador --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-5 mb-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Buscar Paciente por nombre o DNI
            </h3>
            <form id="formBuscarConsulta" method="GET" action="{{ route('medico.consulta.form') }}"
                class="flex gap-3 flex-col sm:flex-row">
                <input id="buscarConsultaInput" type="text" name="buscar" value="{{ request('buscar') }}"
                    placeholder="Escribe un nombre o DNI…"
                    class="flex-1 border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                    autocomplete="off" />

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 font-medium">
                        Buscar
                    </button>

                    <button type="button" id="btnLimpiarConsulta" data-home="{{ route('medico.consulta.form') }}"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 font-medium">
                        Limpiar
                    </button>
                </div>
            </form>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50/80 border border-emerald-200 p-5 text-emerald-800">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @php $hayResultados = isset($pacientes) && $pacientes->count() > 0; @endphp

        @isset($pacienteSeleccionado)

            {{-- ========= FICHA DEL PACIENTE (Diseño mejorado) ========= --}}
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-5 mb-8">
                {{-- Encabezado --}}
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-indigo-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </span>
                        Ficha del Paciente
                    </h3>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Codigo de Paciente: {{ $pacienteSeleccionado->codigo_paciente }}
                    </span>

                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Tipo de Consulta: {{ ucfirst($tipoConsulta ?? 'Consulta') }}
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-700 border border-slate-200">
                            Últimos signos: {{ $ultimaFechaSignos }}
                        </span>
                    </div>
                </div>

                {{-- Contenido en fila --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    {{-- Datos del Paciente --}}
                    <div class="lg:col-span-1 bg-slate-50/50 rounded-xl p-4 border border-slate-200/50">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-indigo-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <h4 class="font-semibold text-slate-800">Datos del Paciente</h4>
                        </div>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Codigo</dt>
                                <dd class="font-medium text-slate-900 break-words">
                                   {{ $pacienteSeleccionado->codigo_paciente }}  
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Nombre Completo</dt>
                                <dd class="font-medium text-slate-900 break-words">
                                    {{ $pacienteSeleccionado->persona->nombre }}
                                    {{ $pacienteSeleccionado->persona->apellido }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">DNI</dt>
                                <dd class="font-medium text-slate-900">{{ $pacienteSeleccionado->persona->dni }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Teléfono</dt>
                                <dd class="font-medium text-slate-900">{{ $pacienteSeleccionado->persona->telefono }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Sexo</dt>
                                <dd class="font-medium text-slate-900">
                                    @if ($pacienteSeleccionado->persona->sexo === 'M')
                                        <span
                                            class="bg-cyan-50 text-cyan-700 rounded-md px-2 py-1 text-sm inline-block">Masculino</span>
                                    @elseif($pacienteSeleccionado->persona->sexo === 'F')
                                        <span
                                            class="bg-pink-50 text-pink-700 rounded-md px-2 py-1 text-sm inline-block">Femenino</span>
                                    @else
                                        {{ $pacienteSeleccionado->persona->sexo }}
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Edad</dt>
                                <dd class="font-medium text-slate-900">
                                    <span class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm inline-block">
                                        {{ $pacienteSeleccionado->persona->edad }} años
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Fecha Nacimiento</dt>
                                <dd class="font-medium text-slate-900">
                                    <span class="bg-blue-50 text-blue-700 rounded-md px-2 py-1 text-sm inline-block">
                                        {{ $pacienteSeleccionado->persona->fecha_nacimiento->format('d/m/Y') }}
                                    </span>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Dirección</dt>
                                <dd class="font-medium text-slate-900 break-words">
                                    {{ $pacienteSeleccionado->persona->direccion }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Encargado (opcional) --}}
                    @if ($encargado)
                        <div class="lg:col-span-1 bg-amber-50/30 rounded-xl p-4 border border-amber-200/50">
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </span>
                                <h4 class="font-semibold text-slate-800">Encargado</h4>
                            </div>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Nombre</dt>
                                    <dd class="font-medium text-slate-900">{{ $encargado->nombre }}
                                        {{ $encargado->apellido }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Teléfono</dt>
                                    <dd class="font-medium text-slate-900">{{ $encargado->telefono }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-xs uppercase tracking-wide text-slate-500 mb-1">Dirección</dt>
                                    <dd class="font-medium text-slate-900 break-words">
                                        @if ($encargado->direccion == null || '')
                                            <span class="text-slate-400 italic">No especificada</span>
                                        @else
                                            {{ $encargado->direccion }}
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    @endif

                    {{-- Signos Vitales --}}
                    <div class="lg:col-span-1 bg-rose-50/30 rounded-xl p-4 border border-rose-200/50">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </span>
                            <h4 class="font-semibold text-slate-800">Signos Vitales</h4>
                        </div>

                        @if ($signos)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                @php
                                    $pesoKg = optional($signos)->peso;
                                    $pesoLb = is_numeric($pesoKg) ? round($pesoKg * 2.2046226218, 2) : null;
                                @endphp

                                @php
                                    $sv = [
                                        ['label' => 'Presión', 'val' => $signos->presion_arterial, 'icon' => '📊'],
                                        ['label' => 'FC', 'val' => $signos->fc, 'icon' => '💓'],
                                        ['label' => 'FR', 'val' => $signos->fr, 'icon' => '🌬️'],
                                        ['label' => 'Temp (°C)', 'val' => $signos->temperatura, 'icon' => '🌡️'],
                                        ['label' => 'SO₂ (%)', 'val' => $signos->so2, 'icon' => '💨'],
                                        ['label' => 'Peso (kg)', 'val' => $signos->peso, 'icon' => '⚖️'],
                                        ['label' => 'Glucosa', 'val' => $signos->glucosa, 'icon' => '🍬'],
                                        [
                                            'label' => 'Fecha registro',
                                            'val' => \Carbon\Carbon::parse($signos->fecha_registro)->format(
                                                'd/m/Y H:i',
                                            ),
                                            'icon' => '🕒',
                                        ],
                                    ];
                                @endphp
                                @foreach ($sv as $item)
                                    <div class="rounded-lg border border-slate-200 bg-white p-3">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="text-sm">{{ $item['icon'] }}</span>
                                            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                {{ $item['label'] }}</div>
                                        </div>
                                        <div class="text-sm font-semibold text-slate-900">{{ $item['val'] ?? '—' }}</div>
                                    </div>
                                @endforeach

                                @if (!is_null($pesoLb))
                                    <div
                                        class="col-span-2 sm:col-span-3 rounded-lg border border-emerald-200 bg-emerald-50/50 p-3">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="text-sm">⚖️</span>
                                            <div class="text-xs font-medium uppercase tracking-wide text-emerald-600">Peso
                                                (lb)</div>
                                        </div>
                                        <div class="text-sm font-semibold text-emerald-700">
                                            {{ rtrim(rtrim(number_format($pesoLb, 2, '.', ''), '0'), '.') }}</div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="rounded-lg bg-amber-50 border border-amber-200 p-4 text-amber-800 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Este paciente aún no tiene signos vitales registrados.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ====== FORMULARIO DE CONSULTA ====== --}}
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-6 mb-8">
                <form id="megaForm" method="POST">
                    @csrf

                    {{-- Campos ocultos --}}
                    <input type="hidden" id="paciente_id" name="paciente_id"
                        value="{{ $pacienteSeleccionado->id_paciente }}">
                    <input type="hidden" id="signos_vitales_id" name="signos_vitales_id"
                        value="{{ $signos?->id_signos_vitales }}">
                    <input type="hidden" id="doctor_id" name="doctor_id" value="">
                    <input type="hidden" id="consulta_id" name="consulta_id" value="">
                    <input type="hidden" id="enfermera_id" name="enfermera_id" value="{{ $signos?->enfermera_id }}">
                    {{-- IMPORTANTE: Ahora sí se establece el valor del encargado --}}
                    @php
                        $relacion = $pacienteSeleccionado->relacionesConEncargado->sortByDesc('fecha_visita')->first();
                        $encargadoId = $relacion?->encargado_id ?? '';
                    @endphp
                    <input type="hidden" id="encargado_id" name="encargado_id" value="{{ $encargadoId }}">

                    {{-- EXPEDIENTE --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Expediente
                        </h3>
                        <span id="badgeExpediente"
                            class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">Incompleto</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Fecha creación</label>
                            <input type="text" value="{{ now()->format('d/m/Y H:i') }}"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 bg-slate-50 text-slate-600 shadow-sm"
                                disabled>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Código expediente</label>
                            <input type="text" id="codigo_expediente"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 bg-slate-50 text-slate-600 shadow-sm"
                                placeholder="Se generará al guardar" disabled>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Motivo Ingreso (Opcional) </label>
                            <textarea id="motivo_ingreso" name="motivo_ingreso"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                rows="4"></textarea>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Diagnóstico(Opcional)</label>
                            <textarea id="diagnostico" name="diagnostico"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                rows="4"></textarea>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones (Opcional)</label>
                            <textarea id="observaciones" name="observaciones"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                rows="4"></textarea>
                        </div>
                    </div>

                    <input type="hidden" id="expediente_id" value="">
                    <input type="hidden" id="expediente_guardado" value="0">
                    <input type="hidden" id="consulta_guardada" value="0">

                    <div class="border-t border-slate-200 my-8"></div>

                    {{-- CONSULTA --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Consulta
                        </h3>
                        <span id="badgeConsulta"
                            class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">Incompleto</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Resumen Clínico * (Obligatorio)</label>
                            <textarea id="resumen_clinico" name="resumen_clinico"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                rows="5"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Impresión Diagnóstica * (Obligatorio)</label>
                            <textarea id="impresion_diagnostica" name="impresion_diagnostica"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                rows="5"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Indicaciones * (Obligatorio)</label>
                            <textarea id="indicaciones" name="indicaciones"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                rows="4"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Urgencia *</label>
                            <select id="urgencia" name="urgencia"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200">
                                <option value="">Seleccionar</option>
                                <option value="si">Sí</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Urgencia *</label>
                            <select id="tipo_urgencia" name="tipo_urgencia"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200">
                                <option value="null">Seleccionar</option>
                                <option value="medica">Médica</option>
                                <option value="pediatrica">Pediátrica</option>
                                <option value="quirurgico">Quirúrgica</option>
                                <option value="gineco obstetrica">Gineco Obstétrica</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Resultado *</label>
                            <select id="resultado" name="resultado"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200">
                                <option value="">Seleccionar</option>
                                <option value="alta">Alta</option>
                                <option value="seguimiento">Seguimiento</option>
                                <option value="referido">Referido</option>
                            </select>
                        </div>

                        {{-- Fila final --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:col-span-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Citado (Opcional)</label>
                                <input id="citado" type="date" name="citado"
                                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Firma/Sello *</label>
                                <select id="firma_sello" name="firma_sello"
                                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200">
                                    <option value="">Seleccionar</option>
                                    <option value="si">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Estado *</label>
                                <select id="estado" name="estado"
                                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200">
                                    <option value="">Seleccionar</option>
                                    <option value="abierto">Abierto</option>
                                    <option value="cerrado">Cerrado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 my-8"></div>

                    {{-- EXÁMENES --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                            Exámenes
                        </h3>
                        <span id="badgeExamenes"
                            class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">Pendientes</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Buscar examen</label>
                            <div class="relative mt-1">
                                <input type="text" id="buscar_examen"
                                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 pr-10"
                                    placeholder="Escribe para filtrar… (min. 2 letras)">
                                <button type="button" id="btnClearExamen"
                                    class="hidden absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700 transition-colors"
                                    aria-label="Limpiar búsqueda" title="Limpiar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">
                                Escribe para filtrar <b>o</b> selecciona una categoría para ver la lista completa.
                            </p>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Categoría (opcional)</label>
                            <select id="categoria_examen"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200">
                                <option value="">Todas las categorías</option>
                                @isset($categorias)
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat->id_categoria }}">{{ $cat->nombre_categoria }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>

                    <div id="lista_examenes" class="rounded-xl border border-slate-200 p-0 max-h-72 overflow-auto">
                        <div class="p-4 text-center text-slate-500 text-sm">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Escribe al menos <b>2 letras</b> o elige una categoría para ver resultados.
                        </div>
                    </div>

                    <div id="seleccionados_box" class="mt-4 hidden">
                        <h4 class="text-sm font-semibold text-slate-700 mb-3">
                            Exámenes seleccionados (<span id="sel_count">0</span>):
                        </h4>
                        <div id="seleccionados_chips" class="flex flex-wrap gap-2"></div>
                    </div>

                    {{-- Botón único --}}
                    <div class="mt-8 flex justify-end">
                        <button type="button" id="btnGuardarExpediente"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 font-medium text-base flex items-center gap-3 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Guardar Expediente Completo
                            <span class="text-xs bg-white/20 px-2 py-1 rounded">Receta + Exámenes</span>
                        </button>
                    </div>
                </form>
            </div>
        @else
            {{-- LISTA DE PACIENTES (ESTILO MEJORADO) --}}
            @if ($hayResultados)
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-4 mb-4">
                    <div class="flex items-center justify-between text-sm text-slate-600" style="display: contents;">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">Resultados:</span>
                            <span
                                class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm">{{ $pacientes->total() }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Página</span>
                            <span class="font-semibold text-indigo-600">{{ $pacientes->currentPage() }}</span>
                            <span>de</span>
                            <span class="font-semibold text-indigo-600">{{ $pacientes->lastPage() }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 overflow-hidden">
                    <div class="overflow-x-auto"
                        style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain; scrollbar-gutter:stable;">
                        <table class="w-full text-sm table-auto min-w-[1000px] border-collapse">
                            <colgroup>
                                <col class="w-25">
                                <col class="w-25">
                                <col class="w-48">
                                <col class="w-20">
                                <col class="w-20">
                                <col class="w-35">
                                <col class="w-20">
                                <col class="w-32">
                                <col class="w-38">
                                <col class="w-24">
                            </colgroup>
                            <thead class="bg-indigo-600 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium first:pl-6 first:rounded-tl-xl">ID</th>
                                    <th class="px-4 py-3 text-left font-medium">Codigo</th>
                                    <th class="px-4 py-3 text-left font-medium">Nombre Completo</th>
                                    <th class="px-4 py-3 text-left font-medium">DNI</th>
                                    <th class="px-4 py-3 text-left font-medium">Edad</th>
                                    <th class="px-4 py-3 text-left font-medium">F. Nacimiento</th>
                                    <th class="px-4 py-3 text-left font-medium">Teléfono</th>
                                    <th class="px-4 py-3 text-left font-medium">Tipo Consulta</th>
                                    <th class="px-4 py-3 text-left font-medium">Últimos registros</th>
                                    <th
                                        class="px-4 py-3 text-center font-medium last:pr-6 last:rounded-tr-xl bg-indigo-600">
                                        Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($pacientes as $p)
                                    @php $relacion = $p->relacionesConEncargado->sortByDesc('fecha_visita')->first(); @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                                        <td class="px-4 py-3 text-center first:pl-6">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium">
                                                {{ $p->id_paciente }}
                                            </span>
                                        </td>

                                        <td>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                {{ $p->codigo_paciente ?? 'NO HAY CÓDIGO' }}
                                                <!-- Para depurar: -->
                                                <!-- <pre>{{ print_r($p->toArray(), true) }}</pre> -->
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="font-medium text-slate-800">
                                                {{ $p->persona->nombre }} {{ $p->persona->apellido }}
                                            </div>
                                            <div class="text-xs text-slate-500 mt-1">{{ $p->persona->direccion }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm inline-block">
                                                {{ $p->persona->dni }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm inline-block">
                                                {{ $p->persona->edad }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm inline-block">
                                                {{ $p->persona->fecha_nacimiento->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm inline-block">
                                                {{ $p->persona->telefono }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($relacion?->tipo_consulta)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    {{ ucfirst($relacion->tipo_consulta) }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-sm">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($p->ultima_fecha_registro)
                                                <span
                                                    class="bg-purple-50 text-purple-700 rounded-md px-2 py-1 text-sm inline-block">
                                                    {{ \Carbon\Carbon::parse($p->ultima_fecha_registro)->format('d/m/Y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-sm">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center last:pr-6 bg-white">
                                            <a href="{{ route('medico.consulta.form', ['select' => $p->id_paciente]) }}"
                                                class="inline-flex items-center gap-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-3 py-1.5 rounded-lg text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tomar Consulta
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-slate-50/50 border-t border-slate-200/50">
                                <tr>
                                    <td colspan="7" class="px-6 py-3 text-slate-500 text-sm rounded-b-xl">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-slate-600 font-medium">Mostrando:</span>
                                                <span
                                                    class="bg-white border border-slate-200 rounded-lg px-3 py-1 text-sm">
                                                    {{ $pacientes->firstItem() }} - {{ $pacientes->lastItem() }} de
                                                    {{ $pacientes->total() }}
                                                </span>
                                            </div>
                                            <div class="text-slate-600 text-sm">
                                                {{ $pacientes->count() }} pacientes en esta página
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    {{ $pacientes->appends(request()->query())->links() }}
                </div>
            @else
                <div class="rounded-xl border border-dashed border-slate-200 p-12 text-center text-slate-500">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <p class="text-slate-600">No se encontraron pacientes.</p>
                    </div>
                </div>
            @endif
        @endisset
    </div>

    {{-- SweetAlert2 --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- Toast helpers --}}
    <script>
        (function() {
            const ok = !!window.Swal;
            window.toastOk = (msg = 'Listo') => ok ? Swal.fire({
                icon: 'success',
                title: msg,
                timer: 1700,
                showConfirmButton: false,
                heightAuto: false
            }) : alert(msg);
            window.toastWarn = (msg) => ok ? Swal.fire({
                icon: 'warning',
                title: msg,
                heightAuto: false
            }) : alert(msg);
            window.toastErr = (html = 'Ocurrió un error') => ok ? Swal.fire({
                icon: 'error',
                title: 'Hay errores',
                html,
                heightAuto: false
            }) : alert(html.replace(/<br>/g, '\n'));
        })();
    </script>

    {{-- ... (todo el código anterior se mantiene igual hasta el script) ... }}

    {{-- Lógica UI mejorada --}}
    <script>
        const csrf =
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('#megaForm input[name="_token"]')?.value;

        const wantsJson = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrf ? {
                'X-CSRF-TOKEN': csrf
            } : {})
        };

        document.addEventListener('DOMContentLoaded', () => {
            // Limpiar buscador pacientes
            const inputSearch = document.getElementById('buscarConsultaInput');
            const btnClr = document.getElementById('btnLimpiarConsulta');

            btnClr?.addEventListener('click', () => {
                inputSearch.value = '';
                window.location.href = btnClr.dataset.home;
            });

            const form = document.getElementById('megaForm');
            if (!form) return;

            const routes = {
                consulta: @json(route('medico.consulta.soloConsulta')),
                expediente: @json(route('medico.expediente.guardar')),
                examenesBase: @json(route('medico.examenes.guardar', ['id' => 'ID_PLACEHOLDER'])),
                buscarExamenes: @json(route('medico.examenes.buscar')),
                home: @json(route('medico.consulta.form')),
            };

            // Hidden
            const pacienteIdEl = document.getElementById('paciente_id');
            const signosIdEl = document.getElementById('signos_vitales_id');
            const doctorIdEl = document.getElementById('doctor_id');
            const consultaIdEl = document.getElementById('consulta_id');
            const enfermeraIdEl = document.getElementById('enfermera_id');
            const encargadoIdEl = document.getElementById('encargado_id');
            const expedienteIdEl = document.getElementById('expediente_id');

            // Badges
            const badgeConsulta = document.getElementById('badgeConsulta');
            const badgeExpediente = document.getElementById('badgeExpediente');
            const badgeExamenes = document.getElementById('badgeExamenes');

            // Consulta
            const fResumen = document.getElementById('resumen_clinico');
            const fImpresion = document.getElementById('impresion_diagnostica');
            const fIndicaciones = document.getElementById('indicaciones');
            const fUrgencia = document.getElementById('urgencia');
            const fTipoUrgencia = document.getElementById('tipo_urgencia');
            const fResultado = document.getElementById('resultado');
            const fCitado = document.getElementById('citado');
            const fFirmaSello = document.getElementById('firma_sello');

            // Expediente
            const fEstado = document.getElementById('estado');
            const fMotivo = document.getElementById('motivo_ingreso');
            const fDiag = document.getElementById('diagnostico');
            const fObs = document.getElementById('observaciones');
            const fCodigo = document.getElementById('codigo_expediente');

            // Botón único
            const btnGuardarExpediente = document.getElementById('btnGuardarExpediente');

            // Exámenes
            const listaExamenes = document.getElementById('lista_examenes');
            const buscarExamenInp = document.getElementById('buscar_examen');
            const btnClearExamen = document.getElementById('btnClearExamen');
            const catExamenSel = document.getElementById('categoria_examen');
            const selBox = document.getElementById('seleccionados_box');
            const selChips = document.getElementById('seleccionados_chips');
            const selCount = document.getElementById('sel_count');

            // Estado
            let consultaGuardada = false;
            let expedienteGuardado = false;
            let seleccionExamenes = new Set();
            let cacheExamenes = new Map();

            // Variables para control de clics múltiples - SIMPLIFICADO
            let isProcessing = false;
            let consultaSaveCount = 0; // Contador específico para consultas

            // Helpers
            const debounce = (fn, t = 600) => {
                let id;
                return (...a) => {
                    clearTimeout(id);
                    id = setTimeout(() => fn(...a), t);
                };
            };

            const setBadge = (el, estado) => {
                if (!el) return;
                const map = {
                    'incompleto': ['bg-slate-100 text-slate-600', 'Incompleto'],
                    'guardando': ['bg-amber-100 text-amber-700', 'Guardando…'],
                    'ok': ['bg-emerald-100 text-emerald-700', 'Guardado ✓'],
                    'pendiente': ['bg-slate-100 text-slate-600', 'Pendientes'],
                };
                const [cls, txt] = map[estado] || map['incompleto'];
                el.className = 'text-xs px-3 py-1 rounded-full font-medium ' + cls;
                el.textContent = txt;
            };

            const goHome = () => setTimeout(() => {
                window.location.href = routes.home;
            }, 900);

            function validConsulta() {
                return fResumen.value.trim() && fImpresion.value.trim() && fIndicaciones.value.trim() &&
                    fUrgencia.value && fTipoUrgencia.value && fResultado.value && fFirmaSello.value;
            }

            function validExpediente() {
                return consultaIdEl.value &&
                    (enfermeraIdEl.value || enfermeraIdEl.value === '0' || enfermeraIdEl.value === 0) &&
                    (doctorIdEl.value || doctorIdEl.value === '0' || doctorIdEl.value === 0) &&
                    fEstado.value;
                // Se quita la validación de fMotivo y fDiag
            }

            async function postForm(url, extra = {}) {
                const fd = new FormData();
                if (csrf) fd.append('_token', csrf);
                fd.append('paciente_id', pacienteIdEl.value);
                if (signosIdEl.value) fd.append('signos_vitales_id', signosIdEl.value);
                for (const [k, v] of Object.entries(extra))
                    if (v !== undefined && v !== null) fd.append(k, v);

                const res = await fetch(url, {
                    method: 'POST',
                    headers: wantsJson,
                    body: fd,
                    credentials: 'same-origin'
                });
                if (!res.ok) {
                    let txt = await res.text();
                    try {
                        const j = JSON.parse(txt);
                        const html = j.errors ? Object.values(j.errors).flat().map(e => `<div>${e}</div>`).join(
                            '') : (j.message || txt);
                        throw new Error(html);
                    } catch {
                        throw new Error(txt);
                    }
                }
                return res.json();
            }

            // Guardado inmediato de CONSULTA - VERSIÓN SIMPLIFICADA
            async function saveConsultaNow(redirect = false) {
                // Si ya se guardó, no volver a guardar
                if (consultaGuardada) {
                    console.log('Consulta ya guardada, omitiendo guardado...');
                    return true;
                }

                if (!validConsulta()) {
                    setBadge(badgeConsulta, 'incompleto');
                    return false;
                }

                setBadge(badgeConsulta, 'guardando');
                try {
                    const payload = {
                        resumen_clinico: fResumen.value.trim(),
                        impresion_diagnostica: fImpresion.value.trim(),
                        indicaciones: fIndicaciones.value.trim(),
                        urgencia: fUrgencia.value,
                        tipo_urgencia: fTipoUrgencia.value,
                        resultado: fResultado.value,
                        citado: fCitado.value || '',
                        firma_sello: fFirmaSello.value
                    };

                    consultaSaveCount++;
                    console.log(`Guardando consulta (intento #${consultaSaveCount})...`);
                    const j = await postForm(routes.consulta, payload);

                    if (j.consulta_id) {
                        consultaIdEl.value = j.consulta_id;
                        console.log(`Consulta guardada con ID: ${j.consulta_id}`);
                    }
                    if (j.doctor_id) doctorIdEl.value = j.doctor_id;
                    if (!enfermeraIdEl.value && j.enfermera_id) enfermeraIdEl.value = j.enfermera_id;

                    consultaGuardada = true;
                    setBadge(badgeConsulta, 'ok');

                    if (redirect) {
                        toastOk(j.message || 'Consulta guardada.');
                        goHome();
                    }
                    return true;
                } catch (err) {
                    setBadge(badgeConsulta, 'incompleto');
                    console.error('Error guardando consulta:', err);
                    toastErr(String(err.message || err));
                    return false;
                }
            }

            // Auto-guardar CONSULTA - MODIFICADO
            const trySaveConsulta = debounce(() => {
                if (window.autoSaveDisabled) return;
                if (consultaGuardada) return; // No auto-guardar si ya está guardada
                saveConsultaNow(false);
            }, 1000); // Aumentado a 1 segundo

            // Auto-guardar EXPEDIENTE - MODIFICADO
            const trySaveExpediente = debounce(async () => {
                if (window.autoSaveDisabled) return;
                if (expedienteGuardado) return;
                if (!validExpediente()) {
                    setBadge(badgeExpediente, 'incompleto');
                    return;
                }

                setBadge(badgeExpediente, 'guardando');
                try {
                    const j = await postForm(routes.expediente, {
                        doctor_id: doctorIdEl.value,
                        consulta_id: consultaIdEl.value,
                        enfermera_id: enfermeraIdEl.value,
                        encargado_id: encargadoIdEl.value || '',
                        estado: fEstado.value,
                        motivo_ingreso: fMotivo.value.trim(),
                        diagnostico: fDiag.value.trim(),
                        observaciones: fObs.value.trim()
                    });

                    if (j.expediente_id) expedienteIdEl.value = j.expediente_id;
                    if (j.codigo) fCodigo.value = j.codigo;

                    expedienteGuardado = true;
                    setBadge(badgeExpediente, 'ok');
                } catch (err) {
                    setBadge(badgeExpediente, 'incompleto');
                    toastErr(String(err.message || err));
                }
            }, 1000); // Aumentado a 1 segundo

            // Agregar listeners de auto-guardado (pero solo si no están ya agregados)
            const addAutoSaveListeners = () => {
                // Consulta
                const consultaFields = [fResumen, fImpresion, fIndicaciones, fUrgencia, fTipoUrgencia,
                    fResultado, fCitado, fFirmaSello
                ];
                consultaFields.forEach(field => {
                    if (field && !field._hasAutoSaveListener) {
                        field.addEventListener('input', trySaveConsulta);
                        field._hasAutoSaveListener = true;
                    }
                });

                // Expediente
                const expedienteFields = [fEstado, fMotivo, fDiag, fObs];
                expedienteFields.forEach(field => {
                    if (field && !field._hasAutoSaveListener) {
                        field.addEventListener('input', trySaveExpediente);
                        field._hasAutoSaveListener = true;
                    }
                });

                if (consultaIdEl && !consultaIdEl._hasAutoSaveListener) {
                    consultaIdEl.addEventListener('change', trySaveExpediente);
                    consultaIdEl._hasAutoSaveListener = true;
                }
            };

            // Inicializar listeners
            addAutoSaveListeners();

            // ====== EXÁMENES ======
            function updateSeleccionadosUI() {
                const size = seleccionExamenes.size;
                selCount.textContent = size;
                if (!size) {
                    selChips.innerHTML = '';
                    selBox.classList.add('hidden');
                    return;
                }
                const items = [...seleccionExamenes].map(id => {
                    const ex = cacheExamenes.get(id) || {};
                    const nombre = ex.nombre_examen || `#${id}`;
                    const cat = ex.nombre_categoria || 'Sin categoría';
                    return `
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                            <span>${nombre} <span class="text-slate-500 text-xs">(${cat})</span></span>
                            <button type="button" class="rm-ex chip-x rounded-full px-1.5 py-0.5 hover:bg-emerald-100 transition-colors" data-id="${id}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                }).join('');
                selChips.innerHTML = items;
                selChips.querySelectorAll('.rm-ex').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        seleccionExamenes.delete(String(id));
                        listaExamenes.querySelectorAll(`.ex-check[value="${CSS.escape(id)}"]`)
                            .forEach(cb => {
                                cb.checked = false;
                            });
                        updateSeleccionadosUI();
                        setBadge(badgeExamenes, seleccionExamenes.size ? 'pendiente' :
                            'incompleto');
                    });
                });
                selBox.classList.remove('hidden');
            }

            async function loadExamenes() {
                try {
                    const q = buscarExamenInp.value.trim();
                    const cat = catExamenSel.value;

                    // Si no hay categoría y no hay 2+ letras => ayuda
                    if (!cat && q.length < 2) {
                        listaExamenes.innerHTML =
                            '<div class="p-4 text-center text-slate-500 text-sm">' +
                            '<svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>' +
                            '</svg>' +
                            'Escribe al menos <b>2 letras</b> o elige una categoría para ver resultados.' +
                            '</div>';
                        return;
                    }

                    const url = new URL(routes.buscarExamenes);
                    if (q) url.searchParams.set('buscar', q);
                    if (cat) url.searchParams.set('categoria_id', cat);

                    const res = await fetch(url.toString(), {
                        headers: wantsJson
                    });
                    const data = await res.json();
                    if (!Array.isArray(data) || !data.length) {
                        listaExamenes.innerHTML =
                            '<div class="p-4 text-center text-slate-500 text-sm">' +
                            '<svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>' +
                            '</svg>' +
                            'No hay exámenes para mostrar.' +
                            '</div>';
                        return;
                    }

                    data.forEach(ex => cacheExamenes.set(String(ex.id_examen), ex));

                    const rows = data.map(ex => {
                        const id = String(ex.id_examen);
                        const checked = seleccionExamenes.has(id) ? 'checked' : '';
                        return `
                          <label class="flex items-center gap-3 py-3 px-4 border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                            <input type="checkbox" class="ex-check rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" value="${id}" ${checked}>
                            <div class="flex-1">
                              <div class="font-medium text-slate-800 text-sm">${ex.nombre_examen}</div>
                              <div class="text-xs text-slate-500 mt-0.5">${ex.nombre_categoria || 'Sin categoría'}</div>
                            </div>
                          </label>
                        `;
                    }).join('');

                    listaExamenes.innerHTML = rows;

                    listaExamenes.querySelectorAll('.ex-check').forEach(cb => {
                        cb.addEventListener('change', (e) => {
                            const id = String(e.target.value);
                            if (e.target.checked) seleccionExamenes.add(id);
                            else seleccionExamenes.delete(id);
                            updateSeleccionadosUI();
                            setBadge(badgeExamenes, seleccionExamenes.size ? 'pendiente' :
                                'incompleto');
                        });
                    });

                    updateSeleccionadosUI();
                } catch (err) {
                    listaExamenes.innerHTML =
                        '<div class="p-4 text-center text-rose-600 text-sm">' +
                        '<svg class="w-8 h-8 mx-auto mb-2 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>' +
                        '</svg>' +
                        'Error cargando exámenes.' +
                        '</div>';
                }
            }

            function toggleClearBtn() {
                btnClearExamen?.classList[buscarExamenInp.value.trim() ? 'remove' : 'add']('hidden');
            }

            if (buscarExamenInp && !buscarExamenInp._hasListener) {
                buscarExamenInp.addEventListener('input', debounce(() => {
                    toggleClearBtn();
                    loadExamenes();
                }, 400));
                buscarExamenInp._hasListener = true;
            }

            if (btnClearExamen && !btnClearExamen._hasListener) {
                btnClearExamen.addEventListener('click', () => {
                    buscarExamenInp.value = '';
                    toggleClearBtn();
                    loadExamenes();
                });
                btnClearExamen._hasListener = true;
            }

            if (catExamenSel && !catExamenSel._hasListener) {
                catExamenSel.addEventListener('change', loadExamenes);
                catExamenSel._hasListener = true;
            }

            // ================================================
            // BOTÓN ÚNICO: GUARDAR EXPEDIENTE COMPLETO
            // ================================================
            // SOLUCIÓN DEFINITIVA: Control manual de clics múltiples
            if (btnGuardarExpediente) {
                // Verificar si ya tiene un listener
                if (!btnGuardarExpediente._hasClickListener) {
                    btnGuardarExpediente._hasClickListener = true;

                    btnGuardarExpediente.addEventListener('click', async function(e) {
                        console.log('=== INICIANDO GUARDADO COMPLETO ===');

                        // 1. Verificar si ya está procesando
                        if (isProcessing) {
                            console.log('⚠️ Ya se está procesando, ignorando click...');
                            return;
                        }

                        // 2. Marcar como procesando inmediatamente
                        isProcessing = true;

                        // 3. Deshabilitar botón inmediatamente
                        this.disabled = true;
                        this.style.opacity = '0.7';
                        this.style.cursor = 'not-allowed';

                        // 4. Guardar estado original del botón
                        const originalHTML = this.innerHTML;
                        this.innerHTML = `
                        <svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Procesando...
                    `;

                        // 5. Deshabilitar auto-guardado temporalmente
                        window.autoSaveDisabled = true;

                        try {
                            // ================================================
                            // PASO 1: VERIFICAR Y GUARDAR CONSULTA (UNA SOLA VEZ)
                            // ================================================
                            console.log('Paso 1/3: Verificando consulta...');

                            // Verificar si ya está guardada
                            if (consultaGuardada) {
                                console.log('✅ Consulta ya guardada anteriormente');
                            } else {
                                if (!validConsulta()) {
                                    throw new Error(
                                        '❌ Completa todos los campos obligatorios de la Consulta Médica'
                                    );
                                }

                                setBadge(badgeConsulta, 'guardando');
                                console.log('Guardando consulta (una sola vez)...');

                                // Guardar consulta UNA SOLA VEZ
                                const consultaOk = await saveConsultaNow(false);

                                if (!consultaOk) {
                                    throw new Error(
                                        '❌ No se pudo guardar la consulta. Revisa los datos.');
                                }

                                console.log('✅ Consulta guardada correctamente (intentos totales: ' +
                                    consultaSaveCount + ')');
                            }

                            // ================================================
                            // PASO 2: VERIFICAR Y GUARDAR EXPEDIENTE (UNA SOLA VEZ)
                            // ================================================
                            console.log('Paso 2/3: Verificando expediente...');

                            if (!expedienteGuardado) {
                                if (!validExpediente()) {
                                    throw new Error(
                                        '❌ Completa todos los campos obligatorios del Expediente');
                                }

                                setBadge(badgeExpediente, 'guardando');
                                console.log('Guardando expediente...');

                                // Preparar datos del expediente
                                const expedienteData = {
                                    doctor_id: doctorIdEl.value,
                                    consulta_id: consultaIdEl.value,
                                    enfermera_id: enfermeraIdEl.value,
                                    encargado_id: encargadoIdEl.value || '',
                                    estado: fEstado.value,
                                    motivo_ingreso: fMotivo.value.trim(),
                                    diagnostico: fDiag.value.trim(),
                                    observaciones: fObs.value.trim(),
                                    crear_receta: '1'
                                };

                                const expedienteResult = await postForm(routes.expediente,
                                    expedienteData);

                                if (expedienteResult.expediente_id) {
                                    expedienteIdEl.value = expedienteResult.expediente_id;
                                    expedienteGuardado = true;

                                    if (expedienteResult.codigo) {
                                        fCodigo.value = expedienteResult.codigo;
                                    }

                                    setBadge(badgeExpediente, 'ok');
                                    console.log('✅ Expediente guardado. ID:', expedienteResult
                                        .expediente_id);
                                } else {
                                    throw new Error('❌ No se recibió ID del expediente');
                                }
                            } else {
                                console.log('✅ Expediente ya guardado anteriormente');
                            }

                            // ================================================
                            // PASO 3: MANEJAR EXÁMENES
                            // ================================================
                            console.log('Paso 3/3: Manejando exámenes...');

                            const expedienteId = expedienteIdEl.value;
                            if (!expedienteId) {
                                throw new Error('❌ No hay ID de expediente para asignar exámenes');
                            }

                            // Preparar URL para exámenes
                            const examenesUrl = routes.examenesBase.replace('ID_PLACEHOLDER',
                                expedienteId);

                            if (seleccionExamenes.size > 0) {
                                console.log(`Asignando ${seleccionExamenes.size} examen(es)...`);
                                setBadge(badgeExamenes, 'guardando');

                                // Crear FormData para exámenes
                                const examenesFormData = new FormData();
                                if (csrf) examenesFormData.append('_token', csrf);
                                examenesFormData.append('paciente_id', pacienteIdEl.value);
                                examenesFormData.append('consulta_id', consultaIdEl.value);

                                // Agregar cada examen seleccionado
                                [...seleccionExamenes].forEach(id => {
                                    examenesFormData.append('examenes[]', id);
                                });

                                // Enviar exámenes
                                const examenesResponse = await fetch(examenesUrl, {
                                    method: 'POST',
                                    headers: wantsJson,
                                    body: examenesFormData,
                                    credentials: 'same-origin'
                                });

                                if (!examenesResponse.ok) {
                                    console.warn(
                                        '⚠️ Los exámenes podrían no haberse guardado, pero continuamos...'
                                    );
                                } else {
                                    const examenesResult = await examenesResponse.json();
                                    if (examenesResult.ok) {
                                        setBadge(badgeExamenes, 'ok');
                                        console.log('✅ Exámenes asignados');
                                    }
                                }
                            } else {
                                console.log('No hay exámenes seleccionados...');

                                // Marcar como "sin exámenes"
                                const sinExamenesFormData = new FormData();
                                if (csrf) sinExamenesFormData.append('_token', csrf);
                                sinExamenesFormData.append('accion', 'no_asignar');

                                await fetch(examenesUrl, {
                                    method: 'POST',
                                    headers: wantsJson,
                                    body: sinExamenesFormData,
                                    credentials: 'same-origin'
                                });

                                setBadge(badgeExamenes, 'ok');
                                console.log('✅ Marcado como sin exámenes');
                            }

                            // ================================================
                            // ✅ ÉXITO: MOSTRAR RESULTADO Y REDIRIGIR
                            // ================================================
                            console.log('=== GUARDADO COMPLETADO CON ÉXITO ===');
                            console.log('Total de intentos de guardar consulta:', consultaSaveCount);

                            // Mostrar mensaje de éxito
                            if (window.Swal) {
                                await Swal.fire({
                                    title: '¡Éxito!',
                                    html: `Expediente ${expedienteIdEl.value} guardado correctamente.<br>Consulta guardada ${consultaSaveCount} vez(es).<br>Receta creada automáticamente.<br>Redirigiendo a gestión de expedientes...`,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }

                            // Redirigir a gestión de expedientes (lista general)
                            window.location.href = '/expedientes';

                        } catch (error) {
                            console.error('❌ ERROR:', error);

                            // Restaurar botón
                            this.innerHTML = originalHTML;
                            this.disabled = false;
                            this.style.opacity = '1';
                            this.style.cursor = 'pointer';

                            // Mostrar error
                            const errorMessage = error.message || 'Ocurrió un error al guardar';

                            if (window.Swal) {
                                Swal.fire({
                                    title: 'Error',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonText: 'Entendido',
                                    heightAuto: false
                                });
                            } else {
                                alert(errorMessage);
                            }
                        } finally {
                            // Restaurar estado de procesamiento
                            isProcessing = false;

                            // Rehabilitar auto-guardado
                            window.autoSaveDisabled = false;
                        }
                    });
                }
            }

            // DEBUG: Mostrar estado inicial
            console.log('Estado inicial:');
            console.log('- consultaGuardada:', consultaGuardada);
            console.log('- expedienteGuardado:', expedienteGuardado);
            console.log('- isProcessing:', isProcessing);

        });
    </script>
</x-app-layout>
