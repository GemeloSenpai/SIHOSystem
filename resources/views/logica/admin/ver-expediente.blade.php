<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Detalle del Expediente
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="mx-auto max-w-6xl">
            <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-slate-200 text-sm space-y-6">

                {{-- Encabezado / Código / Estado / Fecha --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-slate-800">
                        🗂️ Código: <span class="text-indigo-700">{{ $expediente->codigo }}</span>
                    </h3>
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <span class="rounded-full bg-slate-100 px-3 py-1">
                            <b>Estado:</b> {{ ucfirst($expediente->estado) }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-3 py-1">
                            <b>Creado:</b> {{ \Carbon\Carbon::parse($expediente->fecha_creacion)->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>

                {{-- Paciente --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <h4 class="mb-3 text-base font-semibold text-blue-800">🧍 Datos del Paciente</h4>
                    <div class="grid gap-3 md:grid-cols-3">
                        <div><span class="text-slate-500">Nombre</span><div class="font-medium text-slate-800">{{ $expediente->paciente->persona->nombre }} {{ $expediente->paciente->persona->apellido }}</div></div>
                        <div><span class="text-slate-500">Edad</span><div class="font-medium text-slate-800">{{ $expediente->paciente->persona->edad }}</div></div>
                        <div><span class="text-slate-500">DNI</span><div class="font-medium text-slate-800">{{ $expediente->paciente->persona->dni }}</div></div>
                        <div><span class="text-slate-500">Sexo</span><div class="font-medium text-slate-800">{{ $expediente->paciente->persona->sexo }}</div></div>
                        <div class="md:col-span-2"><span class="text-slate-500">Dirección</span><div class="font-medium text-slate-800">{{ $expediente->paciente->persona->direccion }}</div></div>
                        <div><span class="text-slate-500">Teléfono</span><div class="font-medium text-slate-800">{{ $expediente->paciente->persona->telefono }}</div></div>
                    </div>
                </div>

                {{-- Encargado (si existe) --}}
                @if ($expediente->encargado)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <h4 class="mb-3 text-base font-semibold text-blue-800">👥 Encargado</h4>
                        <div class="grid gap-3 md:grid-cols-3">
                            <div><span class="text-slate-500">Nombre</span><div class="font-medium text-slate-800">{{ $expediente->encargado->persona->nombre }} {{ $expediente->encargado->persona->apellido }}</div></div>
                            <div><span class="text-slate-500">Edad</span><div class="font-medium text-slate-800">{{ $expediente->encargado->persona->edad }}</div></div>
                            <div><span class="text-slate-500">DNI</span><div class="font-medium text-slate-800">{{ $expediente->encargado->persona->dni }}</div></div>
                            <div><span class="text-slate-500">Sexo</span><div class="font-medium text-slate-800">{{ $expediente->encargado->persona->sexo }}</div></div>
                            <div class="md:col-span-2"><span class="text-slate-500">Dirección</span><div class="font-medium text-slate-800">{{ $expediente->encargado->persona->direccion }}</div></div>
                        </div>
                    </div>
                @endif

                {{-- Enfermera (si existe) --}}
                @if ($expediente->enfermera)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <h4 class="mb-3 text-base font-semibold text-blue-800">👩‍⚕️ Enfermera</h4>
                        <div class="grid gap-3 md:grid-cols-3">
                            <div><span class="text-slate-500">Nombre</span><div class="font-medium text-slate-800">{{ $expediente->enfermera->nombre }} {{ $expediente->enfermera->apellido }}</div></div>
                            <div><span class="text-slate-500">Edad</span><div class="font-medium text-slate-800">{{ $expediente->enfermera->edad }}</div></div>
                            <div><span class="text-slate-500">DNI</span><div class="font-medium text-slate-800">{{ $expediente->enfermera->dni }}</div></div>
                            <div><span class="text-slate-500">Sexo</span><div class="font-medium text-slate-800">{{ $expediente->enfermera->sexo }}</div></div>
                            <div class="md:col-span-2"><span class="text-slate-500">Dirección</span><div class="font-medium text-slate-800">{{ $expediente->enfermera->direccion }}</div></div>
                        </div>
                    </div>
                @endif

                {{-- Signos Vitales --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <h4 class="mb-3 text-base font-semibold text-blue-800">🩺 Signos Vitales</h4>
                    <div class="grid gap-3 md:grid-cols-4">
                        <div><span class="text-slate-500">Presión</span><div class="font-medium text-slate-800">{{ $expediente->signosVitales->presion_arterial }}</div></div>
                        <div><span class="text-slate-500">FC</span><div class="font-medium text-slate-800">{{ $expediente->signosVitales->fc }}</div></div>
                        <div><span class="text-slate-500">FR</span><div class="font-medium text-slate-800">{{ $expediente->signosVitales->fr }}</div></div>
                        <div><span class="text-slate-500">Temperatura</span><div class="font-medium text-slate-800">{{ $expediente->signosVitales->temperatura }} °C</div></div>
                        <div><span class="text-slate-500">SO₂</span><div class="font-medium text-slate-800">{{ $expediente->signosVitales->so2 }}%</div></div>
                        <div><span class="text-slate-500">Peso</span><div class="font-medium text-slate-800">{{ $expediente->signosVitales->peso }} kg</div></div>
                        <div><span class="text-slate-500">Glucosa</span><div class="font-medium text-slate-800">{{ $expediente->signosVitales->glucosa }}</div></div>
                        <div><span class="text-slate-500">Fecha</span><div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($expediente->signosVitales->fecha_registro)->format('d/m/Y H:i') }}</div></div>
                    </div>
                </div>

                {{-- Doctor --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <h4 class="mb-3 text-base font-semibold text-blue-800">👨‍⚕️ Doctor</h4>
                    <div class="grid gap-3 md:grid-cols-3">
                        <div><span class="text-slate-500">Nombre</span><div class="font-medium text-slate-800">{{ $expediente->doctor->nombre }} {{ $expediente->doctor->apellido }}</div></div>
                        <div><span class="text-slate-500">Edad</span><div class="font-medium text-slate-800">{{ $expediente->doctor->edad }}</div></div>
                        <div><span class="text-slate-500">DNI</span><div class="font-medium text-slate-800">{{ $expediente->doctor->dni }}</div></div>
                        <div><span class="text-slate-500">Sexo</span><div class="font-medium text-slate-800">{{ $expediente->doctor->sexo }}</div></div>
                        <div class="md:col-span-2"><span class="text-slate-500">Dirección</span><div class="font-medium text-slate-800">{{ $expediente->doctor->direccion }}</div></div>
                    </div>
                </div>

                {{-- Consulta --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <h4 class="mb-3 text-base font-semibold text-blue-800">📝 Consulta Médica</h4>
                    <div class="grid gap-3 md:grid-cols-2">
                        <div><span class="text-slate-500">Fecha</span><div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($expediente->consulta->fecha_creacion)->format('d/m/Y H:i') }}</div></div>
                        <div><span class="text-slate-500">Motivo de Ingreso</span><div class="font-medium text-slate-800">{{ $expediente->motivo_ingreso }}</div></div>
                        <div class="md:col-span-2"><span class="text-slate-500">Diagnóstico</span><div class="font-medium text-slate-800">{{ $expediente->diagnostico }}</div></div>
                        <div class="md:col-span-2"><span class="text-slate-500">Observaciones</span><div class="font-medium text-slate-800">{{ $expediente->observaciones ?: '—' }}</div></div>
                    </div>
                </div>

                {{-- Exámenes --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <h4 class="mb-3 text-base font-semibold text-blue-800">🧪 Exámenes Recetados</h4>
                    @if ($expediente->consulta->examenesMedicos->isEmpty())
                        <p class="text-slate-500 text-sm">No hay exámenes asignados.</p>
                    @else
                        <ul class="list-disc list-inside space-y-1 text-slate-800">
                            @foreach ($expediente->consulta->examenesMedicos as $ex)
                                <li>
                                    <span class="font-medium">{{ $ex->examen->nombre_examen }}</span>
                                    <span class="text-slate-500">({{ $ex->examen->categoria->nombre_categoria }})</span>
                                    <span class="text-slate-500">- Asignado el {{ \Carbon\Carbon::parse($ex->fecha_asignacion)->format('d/m/Y H:i') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="mt-4 text-right">
                    <a href="{{ route('expedientes.index') }}"
                       class="inline-flex items-center rounded-xl bg-slate-600 px-4 py-2 text-sm font-semibold text-black shadow-sm hover:bg-slate-700">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
