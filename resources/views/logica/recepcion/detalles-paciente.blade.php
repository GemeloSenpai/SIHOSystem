<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalles del Paciente
        </h2>
    </x-slot>

    <div class="py-2 max-w-7xl mx-auto">
        @php
            $visitas = $paciente->relacionPacienteEncargado ?? collect();
            $totalVisitas = $visitas->count();
            $ultimaVisita = $totalVisitas ? $visitas->sortByDesc('fecha_visita')->first() : null;
            $nombreEncargadoUltima = optional(optional($ultimaVisita)->encargado)->persona;
        @endphp

        {{-- 🧾 Resumen rápido --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Total de visitas</p>
                <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $totalVisitas }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Última visita</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">
                    {{ $ultimaVisita ? \Carbon\Carbon::parse($ultimaVisita->fecha_visita)->format('d/m/Y H:i') : '—' }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Tipo Consulta / Encargado</p>
                <p class="mt-1 font-semibold text-slate-900">
                    {{ $ultimaVisita ? ucfirst($ultimaVisita->tipo_consulta) : '—' }}
                    @if ($nombreEncargadoUltima)
                        <span class="text-slate-500"> / {{ $nombreEncargadoUltima->nombre }} {{ $nombreEncargadoUltima->apellido }}</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- 👤 Información del Paciente + Acciones --}}
        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100 mb-10">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="min-w-[240px]">
                    <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2 text-indigo-700">
                        🩺 Información del Paciente
                    </h3>

                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-10 text-gray-700 text-base">
                        <li><span class="font-medium text-gray-900">Codigo:</span> {{ $paciente->codigo_paciente }}</li>
                        <li><span class="font-medium text-gray-900">Nombre:</span> {{ $paciente->persona->nombre }}</li>
                        <li><span class="font-medium text-gray-900">Apellido:</span> {{ $paciente->persona->apellido }}</li>
                        <li><span class="font-medium text-gray-900">Fecha de nacimniento:</span> {{ $paciente->persona->fecha_nacimiento->format('d/m/Y') }}</li>
                        <li><span class="font-medium text-gray-900">Edad:</span> {{ $paciente->persona->edad }}</li>
                        <li><span class="font-medium text-gray-900">Sexo:</span> {{ ucfirst($paciente->persona->sexo) }}</li>
                        <li><span class="font-medium text-gray-900">DNI:</span> {{ $paciente->persona->dni }}</li>
                        <li><span class="font-medium text-gray-900">Teléfono:</span> {{ $paciente->persona->telefono }}</li>
                        <li class="sm:col-span-2">
                            <span class="font-medium text-gray-900">Dirección:</span> {{ $paciente->persona->direccion }}
                        </li>
                    </ul>
                </div>

                {{-- Acciones rápidas --}}
                <div class="flex flex-col gap-3 mt-6 sm:mt-0">
                    <a href="{{ route('recepcion.pacientes.agregarEncargado', ['id' => $paciente->id_paciente]) }}"
                       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-sm focus-visible:ring-2 focus-visible:ring-indigo-500">
                        ➕ Registrar visita
                    </a>

                    @if (Route::has('recepcion.paciente.editar'))
                        <a href="{{ route('recepcion.paciente.editar', $paciente->id_paciente) }}"
                           class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl shadow-sm focus-visible:ring-2 focus-visible:ring-amber-500">
                            ✏️ Editar paciente
                        </a>
                    @endif

                    <a href="{{ route('recepcion.verPacientes') }}"
                       class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-800 px-5 py-2.5 rounded-xl shadow-sm focus-visible:ring-2 focus-visible:ring-slate-400">
                        ← Volver al listado
                    </a>
                </div>
            </div>
        </div>

        {{-- 📚 Historial de Visitas --}}
        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2 text-indigo-700">
                📖 Historial de Visitas
            </h3>

            @if ($totalVisitas === 0)
                <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-slate-500">
                    Aún no hay visitas registradas para este paciente.
                </div>
            @else
                <div class="overflow-x-auto rounded-xl ring-1 ring-slate-200" style="-webkit-overflow-scrolling: touch;">
                    <table class="min-w-[820px] w-full text-sm border-collapse">
                        <thead class="bg-indigo-600 text-white sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3 text-left border border-indigo-700/40">#</th>
                                <th class="px-4 py-3 text-left border border-indigo-700/40">Fecha</th>
                                <th class="px-4 py-3 text-left border border-indigo-700/40">Tipo de Consulta</th>
                                <th class="px-4 py-3 text-left border border-indigo-700/40">Encargado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($visitas->sortByDesc('fecha_visita')->values() as $index => $visita)
                                @php $pEnc = optional($visita->encargado)->persona; @endphp
                                <tr class="hover:bg-slate-50 odd:bg-slate-50/40">
                                    <td class="px-4 py-2 border border-slate-200">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border border-slate-200">
                                        {{ \Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2 border border-slate-200">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                            {{ ucfirst($visita->tipo_consulta) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border border-slate-200">
                                        @if ($pEnc)
                                            {{ $pEnc->nombre }} {{ $pEnc->apellido }}
                                        @else
                                            <span class="text-slate-400 italic">Sin encargado</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-white">
                            <tr>
                                <td colspan="4" class="px-4 py-2 border border-slate-200 text-slate-500 text-xs">
                                    Desliza horizontalmente para ver más columnas →
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
