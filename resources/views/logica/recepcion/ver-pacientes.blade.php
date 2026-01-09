<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 leading-tight text-center">
            Lista de Pacientes y Visitas
        </h2>
    </x-slot>

    {{-- Estilos para forzar ancho completo en esta vista --}}
    <style>
        /* Sobreescribe los estilos del layout principal */
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
                padding-left: 0rem !important;
                padding-right: 0rem !important;
            }
        }
    </style>

    <div class="max-w-screen-2xl mx-auto px-4">

        <!-- Buscador - MODIFICADO -->
        <div class="w-full flex justify-center mb-6">
            <form method="GET" action="{{ route('recepcion.verPacientes') }}" class="w-full max-w-2xl px-4 sm:px-0">
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <input type="text" name="busqueda" value="{{ old('busqueda', $busqueda) }}"
                        placeholder="Buscar por código, nombre, apellido o DNI..."
                        class="w-full border border-slate-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200">

                    <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                        <button type="submit"
                            class="flex-1 sm:flex-none bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            Buscar
                        </button>
                        <a href="{{ route('recepcion.verPacientes') }}"
                            class="flex-1 sm:flex-none bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 text-center">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Botón Encargados -> abre la vista "buscar" ya en la pestaña Encargados --}}
        <div class="mb-3 flex items-center justify-end">
            <a href="{{ route('recepcion.buscar', ['tab' => 'encargados']) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm hover:bg-slate-50 shadow-sm hover:shadow-md transition-all duration-200"
                title="Buscar/Editar Encargados">
                👥 Encargados
            </a>
        </div>

        <!-- Tabla -->
        @if ($pacientes->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center">
                <p class="text-gray-500 text-lg">No se encontraron resultados.</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200/50">
                <div class="relative w-full rounded-xl overflow-hidden" style="width: 102%"
                    style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain; scrollbar-gutter:stable;">
                    <div id="wrap-pacientes" class="overflow-x-auto">
                        <table class="w-full text-sm table-auto min-w-[1200px] border-collapse">
                            <thead class="bg-indigo-600 text-white">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium first:pl-6 first:rounded-tl-xl">ID</th>
                                    <th class="px-3 py-2 text-left font-medium first:pl-6">Codigo</th>
                                    <th class="px-3 py-2 text-left font-medium">Nombre</th>
                                    <th class="px-3 py-2 text-left font-medium">Apellido</th>
                                    <th class="px-3 py-2 text-left font-medium">Edad</th>
                                    <th class="px-3 py-2 text-left font-medium">F. Nac</th>
                                    <th class="px-3 py-2 text-left font-medium">DNI</th>
                                    <th class="px-3 py-2 text-left font-medium">Teléfono</th>
                                    <th class="px-3 py-2 text-left font-medium">Últ. Visita</th>
                                    <th class="px-3 py-2 text-left font-medium">Tipo</th>
                                    <th
                                        class="px-3 py-2 text-center font-medium last:pr-6 last:rounded-tr-xl bg-indigo-600">
                                        Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @foreach ($pacientes as $paciente)
                                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                        <td class="px-3 py-2 text-center first:pl-6">
                                            <span
                                                class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                                {{ $paciente->id_paciente }}
                                            </span>
                                        </td>

                                        <td class="px-3 py-2 text-center first:pl-6">
                                            <span
                                                class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 rounded-full text-xs font-medium">
                                                {{ $paciente->codigo_paciente }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 font-medium text-slate-800">
                                            {{ $paciente->persona->nombre }}</td>
                                        <td class="px-3 py-2 font-medium text-slate-800">
                                            {{ $paciente->persona->apellido }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[1.5rem] bg-slate-100 text-slate-700 rounded px-2 py-1 text-xs">
                                                {{ $paciente->persona->edad }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">
                                            @if ($paciente->persona->fecha_nacimiento)
                                                <span class="bg-blue-50 text-blue-700 rounded px-2 py-1 text-xs">
                                                    {{ $paciente->persona->fecha_nacimiento->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <span class="bg-slate-100 text-slate-700 rounded px-2 py-1 text-xs">
                                                {{ $paciente->persona->dni }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">
                                            @if ($paciente->persona->telefono)
                                                <span class="bg-emerald-50 text-emerald-700 rounded px-2 py-1 text-xs">
                                                    {{ $paciente->persona->telefono }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            @if ($paciente->relacionPacienteEncargado->isNotEmpty())
                                                <span class="bg-purple-50 text-purple-700 rounded px-2 py-1 text-xs">
                                                    {{ $paciente->relacionPacienteEncargado->first()->fecha_visita_formateada }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-xs">Sin visitas</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            @if ($paciente->relacionPacienteEncargado->isNotEmpty())
                                                @php
                                                    $tipoConsulta = $paciente->relacionPacienteEncargado->first()
                                                        ->tipo_consulta;
                                                    $colorClass = match ($tipoConsulta) {
                                                        'consulta' => 'bg-amber-50 text-amber-700',
                                                        'urgencia' => 'bg-red-50 text-red-700',
                                                        'control' => 'bg-green-50 text-green-700',
                                                        default => 'bg-slate-100 text-slate-700',
                                                    };
                                                @endphp
                                                <span class="{{ $colorClass }} rounded px-2 py-1 text-xs">
                                                    {{ $tipoConsulta }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 italic text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-center last:pr-6 bg-white">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('recepcion.pacientes.agregarEncargado', ['id' => $paciente->id_paciente]) }}"
                                                    class="inline-flex items-center gap-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-2 py-1 rounded text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Visita
                                                </a>
                                                <a href="{{ route('recepcion.paciente.detalles', $paciente->id_paciente) }}"
                                                    class="inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 px-2 py-1 rounded text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    Ver
                                                </a>
                                                <a href="{{ route('recepcion.pacientes.editar', $paciente->id_paciente) }}"
                                                    class="inline-flex items-center gap-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 px-2 py-1 rounded text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot class="bg-slate-50/50 border-t border-slate-200/50">
                                <tr>
                                    <td colspan="10" class="px-6 py-3 text-slate-500 text-sm">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-slate-600 font-medium">Total:</span>
                                                <span
                                                    class="bg-white border border-slate-200 rounded-lg px-3 py-1 text-sm">{{ $pacientes->total() }}
                                                    pacientes</span>
                                            </div>
                                            <div class="text-slate-600 text-sm">
                                                Mostrando {{ $pacientes->firstItem() }} - {{ $pacientes->lastItem() }}
                                                de {{ $pacientes->total() }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-center">
                {{ $pacientes->links() }}
            </div>
        @endif
    </div>

    <style>
        /* Estilos suaves para la tabla */
        #wrap-pacientes {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            scrollbar-gutter: stable;
        }

        /* Scrollbar suave y elegante */
        #wrap-pacientes::-webkit-scrollbar {
            height: 6px;
        }

        #wrap-pacientes::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
        }

        #wrap-pacientes::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        #wrap-pacientes::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Efectos suaves para la tabla */
        table {
            border-spacing: 0;
        }

        /* Bordes redondeados para el contenedor */
        .rounded-xl {
            border-radius: 0.75rem;
        }

        /* Bordes redondeados específicos para las esquinas del header */
        th.first\:rounded-tl-xl {
            border-top-left-radius: 0.75rem;
        }

        th.last\:rounded-tr-xl {
            border-top-right-radius: 0.75rem;
        }

        /* Efecto hover suave para filas */
        tr {
            transition: background-color 0.2s ease;
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

        /* Asegurar que la última celda del header mantenga el color */
        thead tr th:last-child {
            background-color: #4f46e5 !important;
            /* bg-indigo-600 */
        }
    </style>

    <script>
        window.Confirm = window.Confirm || Swal.mixin({
            customClass: {
                popup: 'rounded-xl shadow-lg'
            },
            buttonsStyling: false,
            reverseButtons: true,
        });

        window.toast = window.toast || ((title = 'Listo', icon = 'success') =>
            Swal.fire({
                title,
                icon,
                toast: true,
                position: 'top-end',
                timer: 2200,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-lg'
                }
            }));

        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () => toast(@json(session('success')), 'success'));
        @endif

        @if (session('error'))
            document.addEventListener('DOMContentLoaded', () =>
                Swal.fire({
                    title: @json(session('error')),
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 2600,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-lg'
                    }
                })
            );
        @endif
    </script>
</x-app-layout>
