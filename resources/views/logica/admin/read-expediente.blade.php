{{-- resources/views/logica/admin/read-expediente.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Ver Expediente — {{ $expediente->codigo }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        {{-- Acciones superiores --}}
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('expedientes.index') }}"
                class="rounded-lg bg-slate-200 px-4 py-2 text-sm text-slate-800 hover:bg-slate-300">
                ← Volver a Gestión
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('expedientes.completo.pdf', $expediente->id_expediente) }}"
                    class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black">
                    ⬇️ Descargar PDF
                </a>

                {{-- Ver boleta de exámenes --}}
                <a href="{{ route('admin.ver-examenes', $expediente->id_expediente) }}"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                    📄 Ver boleta de exámenes
                </a>

                {{-- Imprimir expediente (vista lista para imprimir) --}}
                <a href="{{ route('expedientes.completo', $expediente->id_expediente) }}" target="_blank" rel="noopener"
                    class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black">
                    🖨️ Imprimir expediente
                </a>
            </div>
        </div>

        {{-- Resumen principal (RO) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm text-slate-600">Código de Paciente</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->codigo }}" disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Código de Paciente</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->paciente?->codigo_paciente }}" disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Fecha creación</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ \Carbon\Carbon::parse($expediente->fecha_creacion)->format('d/m/Y H:i') }}" disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Paciente</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->paciente?->persona?->nombre ?? '—' }} {{ $expediente->paciente?->persona?->apellido ?? '' }}"
                        disabled>
                </div>

                <div>
                    <label class="text-sm text-slate-600">Fecha Nacimiento</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->paciente?->persona?->fecha_nacimiento ? \Carbon\Carbon::parse($expediente->paciente->persona->fecha_nacimiento)->format('d/m/Y') : '—' }}"
                        disabled>
                </div>

                <div>
                    <label class="text-sm text-slate-600">Edad</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->paciente?->persona?->edad ? $expediente->paciente->persona->edad : '—' }}"
                        disabled>
                </div>
                
                <div>
                    <label class="text-sm text-slate-600">Identificador de la Consulta (ID)</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->consulta_id ?? '—' }}" disabled>
                </div>
            </div>
        </div>

        {{-- Relaciones (RO) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Informacion Relacionada con el Expediente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                @php $enc = $expediente->encargado_derivado; @endphp

                @if ($enc && $enc->persona)
                    <div>
                        <label class="text-sm text-slate-600">Encargado</label>
                        <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                            value="{{ $enc->persona->nombre }} {{ $enc->persona->apellido }}" disabled>
                    </div>
                @endif

                <div>
                    <label class="text-sm text-slate-600">Enfermera que lo atendio</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->enfermera?->nombre ?? '—' }} {{ $expediente->enfermera?->apellido ?? '' }}"
                        disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Doctor que lo atendio</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->doctor?->nombre ?? '—' }} {{ $expediente->doctor?->apellido ?? '' }}"
                        disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Signos vitales (últ. registro)</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->signosVitales ? \Carbon\Carbon::parse($expediente->signosVitales->fecha_registro)->format('d/m/Y H:i') : '—' }}"
                        disabled>
                </div>
            </div>
        </div>

        {{-- Signos Vitales (RO) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 my-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">❤️ Últimos Signos Vitales</h3>
            @if ($expediente->signosVitales)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div><span class="text-xs text-slate-500">Presión</span>
                        <div class="font-semibold">{{ $expediente->signosVitales->presion_arterial }}</div>
                    </div>
                    <div><span class="text-xs text-slate-500">FC</span>
                        <div class="font-semibold">{{ $expediente->signosVitales->fc }}</div>
                    </div>
                    <div><span class="text-xs text-slate-500">FR</span>
                        <div class="font-semibold">{{ $expediente->signosVitales->fr }}</div>
                    </div>
                    <div><span class="text-xs text-slate-500">Temp (°C)</span>
                        <div class="font-semibold">{{ $expediente->signosVitales->temperatura }}</div>
                    </div>
                    <div><span class="text-xs text-slate-500">SpO₂ (%)</span>
                        <div class="font-semibold">{{ $expediente->signosVitales->so2 }}</div>
                    </div>

                    @php
                        $pesoKg = optional($expediente->signosVitales)->peso;
                        $pesoLb = is_numeric($pesoKg) ? round($pesoKg * 2.2046226218, 2) : null; // kg -> lb
                    @endphp

                    <div>
                        <span class="text-xs text-slate-500">Peso (kg)</span>
                        <div class="font-semibold">
                            @if (!is_null($pesoLb))
                                {{ rtrim(rtrim(number_format($pesoKg, 2, '.', ''), '0'), '.') }} kg
                                ({{ rtrim(rtrim(number_format($pesoLb, 2, '.', ''), '0'), '.') }} lb)
                            @else
                                —
                            @endif
                        </div>
                    </div>


                    <div><span class="text-xs text-slate-500">Glucosa</span>
                        <div class="font-semibold">{{ $expediente->signosVitales->glucosa }}</div>
                    </div>
                    <div><span class="text-xs text-slate-500">Fecha registro</span>
                        <div class="font-semibold">
                            {{ \Carbon\Carbon::parse($expediente->signosVitales->fecha_registro)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-slate-500 text-sm">No hay signos vitales registrados.</div>
            @endif
        </div>

        {{-- Datos de ingreso (solo lectura) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 my-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">📁 Datos de ingreso</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-slate-600">Motivo de ingreso</label>
                    <div class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50 min-h-[84px] whitespace-pre-wrap">
                        {{ $expediente->motivo_ingreso ?? '—' }}
                    </div>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Diagnóstico</label>
                    <div class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50 min-h-[84px] whitespace-pre-wrap">
                        {{ $expediente->diagnostico ?? '—' }}
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-slate-600">Observaciones</label>
                    <div class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50 min-h-[84px] whitespace-pre-wrap">
                        {{ $expediente->observaciones ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Consulta (solo lectura) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 my-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">📝 Consulta</h3>
            @if ($expediente->consulta)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-600">Resumen Clínico</label>
                        <div
                            class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50 min-h-[84px] whitespace-pre-wrap">
                            {{ $expediente->consulta->resumen_clinico ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Impresión Diagnóstica</label>
                        <div
                            class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50 min-h-[84px] whitespace-pre-wrap">
                            {{ $expediente->consulta->impresion_diagnostica ?? '—' }}
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-slate-600">Indicaciones</label>
                        <div
                            class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50 min-h-[84px] whitespace-pre-wrap">
                            {{ $expediente->consulta->indicaciones ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Urgencia</label>
                        <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                            value="{{ $expediente->consulta->urgencia ?? '—' }}" disabled>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Tipo de Urgencia</label>
                        <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                            value="{{ $expediente->consulta->tipo_urgencia ?? '—' }}" disabled>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Resultado</label>
                        <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                            value="{{ $expediente->consulta->resultado ?? '—' }}" disabled>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Citado</label>
                        <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                            value="{{ $expediente->consulta->citado ? \Carbon\Carbon::parse($expediente->consulta->citado)->format('d/m/Y') : '—' }}"
                            disabled>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Firma/Sello</label>
                        <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                            value="{{ $expediente->consulta->firma_sello ?? '—' }}" disabled>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Estado del expediente</label>
                        <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                            value="{{ ucfirst($expediente->estado) }}" disabled>
                    </div>
                </div>
            @else
                <div class="text-slate-500 text-sm">No hay consulta asociada.</div>
            @endif
        </div>

        {{-- Exámenes (agrupados por categoría) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 my-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">🔬 Exámenes recetados</h3>

            @php
                $examenes = $expediente->consulta?->examenesMedicos ?? collect();
                $grouped = $examenes
                    ->groupBy(function ($ex) {
                        return optional($ex->examen->categoria)->nombre_categoria ?? 'Sin categoría';
                    })
                    ->sortKeys();
            @endphp

            @if ($grouped->isEmpty())
                <div class="text-slate-500 text-sm">No hay exámenes registrados para este expediente.</div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($grouped as $categoria => $items)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="font-semibold text-slate-800 mb-2">
                                📂 {{ $categoria }}
                                <span class="ml-2 text-xs text-slate-500">({{ $items->count() }})</span>
                            </div>
                            <ul class="space-y-2">
                                @foreach ($items as $ex)
                                    <li class="text-sm">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="font-medium text-slate-900 leading-tight">
                                                    {{ $ex->examen->nombre_examen }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    Asignado:
                                                    {{ \Carbon\Carbon::parse($ex->fecha_asignacion)->format('d/m/Y H:i') }}
                                                </div>
                                                {{-- Si el pivot u objeto trae más datos (estado/resultado), muéstralos de forma segura --}}
                                                @if (!empty($ex->resultado ?? null))
                                                    <div class="text-xs text-slate-600">
                                                        Resultado: {{ $ex->resultado }}
                                                    </div>
                                                @endif
                                                @if (!empty($ex->estado ?? null))
                                                    <div class="text-xs text-slate-600">
                                                        Estado: {{ ucfirst($ex->estado) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif

            @php
                if(Auth::user('admin'))
            
            
            @endphp

            <div class="mt-6 flex items-center justify-end gap-2">
                {{-- Imprimir expediente (vista lista para imprimir) --}}
                <a href="{{ route('expedientes.completo', $expediente->id_expediente) }}" target="_blank"
                    rel="noopener"
                    class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black">
                    🖨️ Imprimir expediente
                </a>

                <a href="{{ route('admin.ver-examenes', $expediente->id_expediente) }}"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                    📄 Ver boleta de exámenes
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
