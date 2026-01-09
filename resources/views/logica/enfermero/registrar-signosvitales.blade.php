<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 leading-tight">
            Registrar Signos Vitales
        </h2>
    </x-slot>

    <div id="informe-expediente">
        <div class="bg-white p-6 rounded-2xl shadow ring-1 ring-slate-200">
            <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

                {{-- Toasts (SweetAlert) --}}
                @if (session('success') || session('error'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2200,
                                timerProgressBar: true,
                            });
                            @if (session('success'))
                                toast.fire({
                                    title: @json(session('success')),
                                    icon: 'success'
                                });
                            @endif
                            @if (session('error'))
                                toast.fire({
                                    title: @json(session('error')),
                                    icon: 'error'
                                });
                            @endif
                        });
                    </script>
                @endif

                {{-- Buscador --}}
                <div class="bg-white p-5 rounded-xl shadow-sm ring-1 ring-slate-200 mb-6">
                    <h3 class="text-lg font-semibold mb-4">🔍 Buscar Paciente por nombre o DNI</h3>

                    <form id="formBuscarPaciente" method="POST" action="{{ route('enfermeria.paciente.buscar') }}"
                        data-home="{{ route('enfermero.signosvitales.form') }}" class="flex gap-3 flex-col sm:flex-row">
                        @csrf
                        <input id="buscarInput" type="text" name="buscar" placeholder="Escribe un nombre o DNI..."
                            class="flex-1 border border-slate-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                            autocomplete="off">

                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                Buscar
                            </button>

                            <button type="button" id="btnLimpiarBuscar"
                                data-home="{{ route('enfermero.signosvitales.form') }}"
                                class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                Limpiar
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabla: Pacientes registrados recientemente (cuando aún no se ha buscado) --}}
                @if (!isset($pacientes) && isset($recientes) && $recientes->count())
                    <h3 class="text-md font-semibold text-gray-700 mb-2">🕒 Visitas Mas Recientes de los Pacientes.</h3>
                    <div class="bg-white rounded-xl shadow-sm  border border-slate-200/50">
                        <div class="relative w-full rounded-xl "
                            style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain; scrollbar-gutter:stable;">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm table-auto min-w-[1000px] border-collapse">
                                    <thead class="bg-indigo-600 text-white">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-medium first:pl-6 first:rounded-tl-xl">
                                                ID</th>
                                            <th class="px-3 py-2 text-left font-medium first:pl-6">Codigo</th>
                                            <th class="px-5 py-2 text-left font-medium">Nombre</th>
                                            <th class="px-3 py-2 text-left font-medium">Apellido</th>
                                            <th class="px-3 py-2 text-left font-medium">Edad</th>
                                            <th class="px-3 py-2 text-left font-medium">F. Nac</th>
                                            <th class="px-3 py-2 text-left font-medium">DNI</th>
                                            <th class="px-3 py-2 text-left font-medium">Teléfono</th>
                                            <th class="px-3 py-2 text-left font-medium">Fecha Visita</th>
                                            <th class="px-3 py-2 text-center font-medium">Tipo</th>
                                            <th
                                                class="px-3 py-2 text-center font-medium last:pr-6 last:rounded-tr-xl bg-indigo-600">
                                                Acción</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($recientes as $relacion)
                                            <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                                <td class="px-3 py-2 text-center first:pl-6">
                                                    <span
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                                        {{ $relacion->paciente->id_paciente }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2 text-center first:pl-6">
                                                    <span
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                                        {{ $relacion->paciente->codigo_paciente }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2 font-medium text-slate-800">
                                                    {{ $relacion->paciente->persona->nombre }}
                                                </td>
                                                <td class="px-3 py-2 font-medium text-slate-800">
                                                    {{ $relacion->paciente->persona->apellido }}
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    <span
                                                        class="inline-flex items-center justify-center min-w-[1.5rem] bg-slate-100 text-slate-700 rounded px-2 py-1 text-xs">
                                                        {{ $relacion->paciente->persona->edad }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2">
                                                    @if ($relacion->paciente->persona->fecha_nacimiento)
                                                        <span
                                                            class="bg-blue-50 text-blue-700 rounded px-2 py-1 text-xs">
                                                            {{ \Carbon\Carbon::parse($relacion->paciente->persona->fecha_nacimiento)->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400 italic text-xs">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2">
                                                    <span class="bg-slate-100 text-slate-700 rounded px-2 py-1 text-xs">
                                                        {{ $relacion->paciente->persona->dni }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2">
                                                    <span
                                                        class="bg-emerald-50 text-emerald-700 rounded px-2 py-1 text-xs">
                                                        {{ $relacion->paciente->persona->telefono }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2">
                                                    @if ($relacion->fecha_visita)
                                                        <span
                                                            class="bg-purple-50 text-purple-700 rounded px-2 py-1 text-xs">
                                                            {{ \Carbon\Carbon::parse($relacion->fecha_visita)->format('d/m/Y H:i') }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400 italic text-xs">N/D</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    @php
                                                        $tipoConsulta = $relacion->tipo_consulta;
                                                        $colorClass = match ($tipoConsulta) {
                                                            'consulta' => 'bg-amber-50 text-amber-700',
                                                            'urgencia' => 'bg-red-50 text-red-700',
                                                            'control' => 'bg-green-50 text-green-700',
                                                            default => 'bg-indigo-50 text-indigo-700',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="{{ $colorClass }} rounded px-2 py-1 text-xs capitalize">
                                                        {{ $tipoConsulta }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2 text-center last:pr-6 bg-white">
                                                    <div class="flex justify-center gap-1">
                                                        <a href="{{ route('enfermeria.signos.historial', ['paciente' => $relacion->paciente->id_paciente]) }}"
                                                            class="inline-flex items-center gap-1 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 px-2 py-1 rounded text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            Ver
                                                        </a>
                                                        <a href="{{ route('enfermero.signosvitales.form', ['id' => $relacion->paciente->id_paciente]) }}"
                                                            class="inline-flex items-center gap-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-2 py-1 rounded text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                            Registrar
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot class="bg-slate-50/50 border-t border-slate-200/50">
                                        <tr>
                                            <td colspan="10" class="px-6 py-3 text-slate-500 text-sm rounded-b-xl">
                                                <div class="text-center">
                                                    <span class="text-slate-600">Mostrando {{ $recientes->count() }}
                                                        visitas recientes</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tabla: Resultados de búsqueda --}}
                @isset($pacientes)
                    @if ($pacientes->isEmpty())
                        <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-slate-500 mb-6">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <p class="text-slate-600">No se encontraron pacientes.</p>
                            </div>
                        </div>
                    @else
                        <h3 class="text-md font-semibold text-gray-700 mb-2">🔎 Resultados de búsqueda</h3>
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200/50 mb-6">
                            <div class="relative w-full rounded-xl overflow-hidden"
                                style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain; scrollbar-gutter:stable;">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm table-auto min-w-[1100px] border-collapse">
                                        <thead class="bg-indigo-600 text-white">
                                            <tr>
                                                <th class="px-3 py-2 text-left font-medium first:pl-6 first:rounded-tl-xl">
                                                    ID</th>
                                                <th class="px-3 py-2 text-left font-medium">Nombre</th>
                                                <th class="px-3 py-2 text-left font-medium first:pl-6">Codigo</th>
                                                <th class="px-3 py-2 text-left font-medium">Apellido</th>
                                                <th class="px-3 py-2 text-left font-medium">Edad</th>
                                                <th class="px-3 py-2 text-left font-medium">F. Nac</th>
                                                <th class="px-3 py-2 text-left font-medium">DNI</th>
                                                <th class="px-3 py-2 text-left font-medium">Teléfono</th>
                                                <th class="px-3 py-2 text-left font-medium">Fecha Visita</th>
                                                <th class="px-3 py-2 text-center font-medium">Tipo</th>
                                                <th
                                                    class="px-3 py-2 text-center font-medium last:pr-6 last:rounded-tr-xl bg-indigo-600">
                                                    Acción</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-slate-100">
                                            @foreach ($pacientes as $p)
                                                <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                                    <td class="px-3 py-2 text-center first:pl-6">
                                                        <span
                                                            class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                                            {{ $p->id_paciente }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-2 text-center first:pl-6">
                                                        <span
                                                            class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                                            {{ $relacion->paciente->codigo_paciente }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-2 font-medium text-slate-800">
                                                        {{ $p->persona->nombre }}</td>
                                                    <td class="px-3 py-2 font-medium text-slate-800">
                                                        {{ $p->persona->apellido }}</td>
                                                    <td class="px-3 py-2 text-center">
                                                        <span
                                                            class="inline-flex items-center justify-center min-w-[1.5rem] bg-slate-100 text-slate-700 rounded px-2 py-1 text-xs">
                                                            {{ $p->persona->edad }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        @if ($p->persona->fecha_nacimiento)
                                                            <span
                                                                class="bg-blue-50 text-blue-700 rounded px-2 py-1 text-xs">
                                                                {{ \Carbon\Carbon::parse($p->persona->fecha_nacimiento)->format('d/m/Y') }}
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400 italic text-xs">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <span
                                                            class="bg-slate-100 text-slate-700 rounded px-2 py-1 text-xs">
                                                            {{ $p->persona->dni }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <span
                                                            class="bg-emerald-50 text-emerald-700 rounded px-2 py-1 text-xs">
                                                            {{ $p->persona->telefono }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        @if ($p->relacionConEncargado && $p->relacionConEncargado->fecha_visita)
                                                            <span
                                                                class="bg-purple-50 text-purple-700 rounded px-2 py-1 text-xs">
                                                                {{ \Carbon\Carbon::parse($p->relacionConEncargado->fecha_visita)->format('d/m/Y H:i') }}
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400 italic text-xs">N/D</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        @php
                                                            $tipo = optional($p->relacionConEncargado)->tipo_consulta;
                                                            $colorClass = match ($tipo) {
                                                                'consulta' => 'bg-amber-50 text-amber-700',
                                                                'urgencia' => 'bg-red-50 text-red-700',
                                                                'control' => 'bg-green-50 text-green-700',
                                                                default => 'bg-indigo-50 text-indigo-700',
                                                            };
                                                        @endphp
                                                        @if ($tipo)
                                                            <span
                                                                class="{{ $colorClass }} rounded px-2 py-1 text-xs capitalize">
                                                                {{ $tipo }}
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400 italic text-xs">N/D</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2 text-center last:pr-6 bg-white">
                                                        <div class="flex justify-center gap-1">
                                                            <a href="{{ route('enfermeria.signos.historial', ['paciente' => $p->id_paciente]) }}"
                                                                class="inline-flex items-center gap-1 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 px-2 py-1 rounded text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                                <svg class="w-3.5 h-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                    </path>
                                                                </svg>
                                                                Ver
                                                            </a>
                                                            <a href="{{ route('enfermero.signosvitales.form', ['id' => $p->id_paciente]) }}"
                                                                class="inline-flex items-center gap-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-2 py-1 rounded text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                                <svg class="w-3.5 h-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                                Seleccionar
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot class="bg-slate-50/50 border-t border-slate-200/50">
                                            <tr>
                                                <td colspan="10" class="px-6 py-3 text-slate-500 text-sm rounded-b-xl">
                                                    <div class="text-center">
                                                        <span class="text-slate-600">Mostrando {{ $pacientes->count() }}
                                                            resultados</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endisset

                {{-- Datos del paciente seleccionado + Formulario de signos --}}
                @isset($paciente)
                    <div x-data="{ open: true }"
                        class="mb-8 rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        {{-- Header del acordeón --}}
                        <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between gap-5 px-6 py-5 bg-gradient-to-r from-indigo-50 to-white hover:from-indigo-100 hover:to-white">
                            <div class="flex items-center gap-4 text-left">
                                <div
                                    class="h-11 w-11 rounded-2xl bg-indigo-100 ring-1 ring-indigo-200 grid place-items-center text-indigo-700 text-lg">
                                    🩺
                                </div>
                                <div>
                                    <div class="text-sm text-slate-500">Paciente seleccionado</div>
                                    <div class="font-semibold text-slate-800 text-xl">
                                        {{ $paciente->persona->nombre }} {{ $paciente->persona->apellido }}
                                        <span
                                            class="ml-2 align-middle inline-flex items-center gap-1 px-2.5 py-1 rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 text-xs">
                                            ID: <strong class="ml-0.5">#{{ $paciente->id_paciente }}</strong>
                                        </span>
                                        <span
                                            class="ml-2 align-middle inline-flex items-center gap-1 px-2.5 py-1 rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 text-xs">
                                            Codigo del Paciente: <strong class="ml-0.5">#{{ $paciente->codigo_paciente }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('enfermeria.signos.historial', ['paciente' => $paciente->id_paciente]) }}"
                                    class="hidden sm:inline-flex items-center rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium hover:bg-slate-50">
                                    Ver historial
                                </a>
                                <a href="{{ route('enfermero.signosvitales.form') }}"
                                    class="hidden sm:inline-flex items-center rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium hover:bg-slate-50">
                                    Cambiar paciente
                                </a>
                                <svg :class="open ? 'rotate-180' : ''"
                                    class="h-5.5 w-5.5 text-slate-500 transition-transform" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        {{-- Cuerpo del acordeón --}}
                        <div x-show="open" x-collapse x-cloak class="px-6 pt-6 pb-7 bg-white">
                            {{-- Chips con datos rápidos del paciente --}}
                            <div class="flex flex-wrap gap-2.5 mb-5 text-sm">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    🪪 DNI: <strong class="ml-0.5">{{ $paciente->persona->dni }}</strong>
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    🎂 Edad: <strong class="ml-0.5">{{ $paciente->persona->edad }}</strong>
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    📅 F. Nac.: <strong class="ml-0.5">
                                        @if ($paciente->persona->fecha_nacimiento)
                                            {{ \Carbon\Carbon::parse($paciente->persona->fecha_nacimiento)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </strong>
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    ⚧ Sexo: <strong class="ml-0.5">{{ $paciente->persona->sexo }}</strong>
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    📞 Tel: <strong class="ml-0.5">{{ $paciente->persona->telefono }}</strong>
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    📍 {{ $paciente->persona->direccion }}
                                </span>
                            </div>

                            @if (
                                $paciente->relacionConEncargado &&
                                    $paciente->relacionConEncargado->encargado &&
                                    $paciente->relacionConEncargado->encargado->persona)
                                <div class="mb-5 text-sm">
                                    <span class="text-slate-500">Encargado:</span>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 ml-1">
                                        {{ $paciente->relacionConEncargado->encargado->persona->nombre }}
                                        {{ $paciente->relacionConEncargado->encargado->persona->apellido }}
                                        ({{ $paciente->relacionConEncargado->encargado->persona->telefono }})
                                    </span>
                                </div>
                            @endif

                            {{-- Errores de validación (si los hay) --}}
                            @if ($errors->any())
                                <div class="mb-6 rounded-xl bg-red-50 p-4.5 border border-red-200">
                                    <h4 class="text-[15px] font-semibold text-red-800 mb-2">Revisa los siguientes campos:
                                    </h4>
                                    <ul class="list-disc pl-5 space-y-1 text-[15px] text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Formulario de signos --}}
                            <form method="POST" action="{{ route('enfermeria.signos.store') }}"
                                class="bg-white rounded-xl ring-1 ring-slate-200 p-6" x-data="{ submitting: false }"
                                @submit="submitting = true">
                                @csrf
                                <input type="hidden" name="paciente_id" value="{{ $paciente->id_paciente }}">
                                <input type="hidden" name="enfermera_id" value="{{ $enfermeraEmpleadoId }}">

                                <h3 class="text-lg font-semibold text-slate-800 mb-5">💉 Registrar Signos Vitales</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    {{-- Presión Arterial --}}
                                    <div>
                                        <label class="block text-[15px] font-medium text-slate-700">
                                            Presión arterial <span class="text-slate-500">(mmHg)</span>
                                        </label>
                                        <div class="relative mt-1.5">
                                            <input type="text" name="presion_arterial" pattern="\d{2,3}/\d{2,3}"
                                                title="Ejemplo: 120/80"
                                                class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 pr-16 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                                                >
                                        </div>
                                        <p class="mt-1 text-xs text-slate-500">Formato: sistólica/diastólica (p. ej.
                                            118/76).</p>
                                    </div>

                                    {{-- Frecuencia cardiaca --}}
                                    <div>
                                        <label class="block text-[15px] font-medium text-slate-700">
                                            Frecuencia cardíaca <span class="text-slate-500">(lpm)</span>
                                        </label>
                                        <div class="relative mt-1.5">
                                            <input type="number" name="fc" min="10" max="300"
                                                class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 pr-16 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                                                >
                                        </div>
                                    </div>

                                    {{-- Frecuencia respiratoria --}}
                                    <div>
                                        <label class="block text-[15px] font-medium text-slate-700">
                                            Frecuencia respiratoria <span class="text-slate-500">(rpm)</span>
                                        </label>
                                        <div class="relative mt-1.5">
                                            <input type="number" name="fr" min="10" max="100"
                                                class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 pr-16 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                                                >
                                        </div>
                                    </div>

                                    {{-- Temperatura --}}
                                    <div>
                                        <label class="block text-[15px] font-medium text-slate-700">
                                            Temperatura <span class="text-slate-500">(°C)</span>
                                        </label>
                                        <div class="relative mt-1.5">
                                            <input type="number" step="0.1" name="temperatura" min="20"
                                                max="70"
                                                class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 pr-14 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                                                >
                                        </div>
                                    </div>

                                    {{-- SpO2 --}}
                                    <div>
                                        <label class="block text-[15px] font-medium text-slate-700">
                                            SO₂ <span class="text-slate-500">(%)</span>
                                        </label>
                                        <div class="relative mt-1.5">
                                            <input type="number" name="so2" min="10" max="200"
                                                class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 pr-12 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                                                >
                                        </div>
                                    </div>

                                    {{-- Peso --}}
                                    <div>
                                        <label class="block text-[15px] font-medium text-slate-700">
                                            Peso <span class="text-slate-500">(kg)</span>
                                        </label>
                                        <div class="relative mt-1.5">
                                            <input type="number" step="0.1" name="peso" min="1"
                                                max="1500"
                                                class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 pr-14 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                                                placeholder="Solo Kilogramos" >
                                        </div>
                                    </div>

                                    {{-- Glucosa --}}
                                    <div>
                                        <label class="block text-[15px] font-medium text-slate-700">
                                            Glucosa <span class="text-slate-500">(mg/dL)</span>
                                        </label>
                                        <div class="relative mt-1.5">
                                            <input type="number" step="0.1" name="glucosa" min="10"
                                                max="800"
                                                class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 pr-20 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200"
                                                >
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-7 flex items-center justify-end ">
                                    <!-- Cancelar -->
                                    <a href="{{ route('enfermero.signosvitales.form') }}"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700
                                  hover:bg-slate-50 hover:border-slate-400
                                    focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-300
                                    active:translate-y-[1px] transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path
                                                d="M11 19a1 1 0 0 1-.7-.29l-7-7a1 1 0 0 1 0-1.42l7-7A1 1 0 0 1 11 4v4h9a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-9v4a1 1 0 0 1-1 1Z" />
                                        </svg>
                                        Cancelar
                                    </a>

                                    <!-- Guardar -->
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm
                 hover:bg-emerald-700
                 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-emerald-500
                 active:translate-y-[1px] disabled:opacity-60 disabled:cursor-not-allowed transition"
                                        :disabled="submitting" :aria-busy="submitting ? 'true' : 'false'">
                                        <svg x-show="submitting" class="h-5 w-5 animate-spin" viewBox="0 0 24 24"
                                            fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4A4 4 0 008 12H4z" />
                                        </svg>
                                        
                                        <span x-text="submitting ? 'Guardando…' : 'Guardar'"></span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    <style>
        /* Estilos suaves para las tablas */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            scrollbar-gutter: stable;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Bordes redondeados para las esquinas del header */
        th.first\:rounded-tl-xl {
            border-top-left-radius: 0.75rem;
        }

        th.last\:rounded-tr-xl {
            border-top-right-radius: 0.75rem;
        }

        /* Bordes redondeados para el footer */
        tfoot tr td.rounded-b-xl {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        /* Asegurar que la última celda del header mantenga el color */
        thead tr th:last-child {
            background-color: #4f46e5 !important;
            /* bg-indigo-600 */
        }

        /* Transiciones suaves */
        tr,
        button,
        input,
        select {
            transition: all 0.2s ease;
        }

        /* Estilos para los badges */
        span.bg-indigo-50,
        span.bg-slate-100,
        span.bg-blue-50,
        span.bg-emerald-50,
        span.bg-purple-50,
        span.bg-amber-50,
        span.bg-red-50,
        span.bg-green-50 {
            transition: all 0.2s ease;
        }

        /* Efecto hover para badges */
        tr:hover span.bg-indigo-50 {
            background-color: #e0e7ff;
        }

        tr:hover span.bg-slate-100 {
            background-color: #e2e8f0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formBuscarPaciente');
            const input = document.getElementById('buscarInput');
            const btnClr = document.getElementById('btnLimpiarBuscar');

            // Si el usuario intenta buscar con el campo vacío, mostramos "recientes"
            form.addEventListener('submit', (e) => {
                if (!input.value.trim()) {
                    e.preventDefault();
                    window.location.href = form.dataset.home; // ruta que muestra la tabla de recientes
                }
            });

            // Limpiar: borra el campo y vuelve a "recientes"
            btnClr.addEventListener('click', () => {
                input.value = '';
                window.location.href = btnClr.dataset.home;
            });
        });
    </script>
</x-app-layout>
