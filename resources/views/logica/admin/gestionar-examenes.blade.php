<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Gestionar Exámenes
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

    <div class="max-w-screen-2xl mx-auto px-3 sm:px-4 py-3 sm:py-4">

        {{-- Flash con SweetAlert --}}
        @if (session('success') || session('error') || $errors->any())
            <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    @if (session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: @json(session('success')),
                            toast: true,
                            position: 'top-end',
                            timer: 2200,
                            showConfirmButton: false,
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
                });
            </script>
        @endif

        {{-- Registrar nuevo examen (solo Admin) --}}
        @php $esAdmin = (auth()->user()->role ?? null) === 'admin'; @endphp
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-4 sm:p-5 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Registrar nuevo examen
                </h3>
                @if ($esAdmin)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Rol: Admin
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200 self-start sm:self-auto">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        Solo lectura
                    </span>
                @endif
            </div>

            @if ($esAdmin)
                <form method="POST" action="{{ route('admin.examenes.store') }}"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3 sm:gap-4">
                    @csrf
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Categoría *</label>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            <select name="categoria_id"
                                class="flex-1 border border-slate-300 rounded-lg px-3 sm:px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 js-categoria-select"
                                required>
                                <option value="">Seleccione una categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre_categoria }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button"
                                class="inline-flex items-center justify-center px-3 sm:px-4 py-2.5 rounded-lg border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 shadow-sm transition-all duration-200 js-btn-crear-categoria text-sm sm:text-base"
                                title="Crear nueva categoría">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="ml-1 sm:ml-2 text-sm">Nueva</span>
                            </button>
                        </div>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre del Examen *</label>
                        <input type="text" name="nombre_examen"
                            class="w-full border border-slate-300 rounded-lg px-3 sm:px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                            required>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-1 flex items-end">
                        <button type="submit"
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 font-medium">
                            Guardar Examen
                        </button>
                    </div>
                </form>
            @else
                <div class="rounded-xl bg-amber-50/80 border border-amber-200 p-3 sm:p-4 text-amber-800 text-sm">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div>
                            <span class="font-medium">Solo lectura:</span> Solo un administrador puede registrar nuevos exámenes. Puedes consultar los existentes.
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Exámenes por categoría --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-6">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exámenes Registrados
                </h3>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <span class="font-medium">Categorías:</span>
                    <span class="bg-indigo-50 text-indigo-700 rounded-md px-2 py-1 text-sm font-medium">{{ $categorias->count() }}</span>
                </div>
            </div>

            @forelse ($categorias as $categoria)
                <div class="mb-6 sm:mb-8 last:mb-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 px-1">
                        <h4 class="font-semibold text-indigo-700 flex items-center gap-2 text-sm sm:text-base">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            {{ $categoria->nombre_categoria }}
                        </h4>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <span>Exámenes: <span class="font-semibold text-indigo-600">{{ $categoria->examenes->count() }}</span></span>
                        </div>
                    </div>

                    @if ($categoria->examenes->isEmpty())
                        <div class="rounded-xl border border-dashed border-slate-200 p-4 sm:p-6 text-center text-slate-500">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            No hay exámenes registrados en esta categoría.
                        </div>
                    @else
                        <!-- Tarjeta para móvil -->
                        <div class="sm:hidden space-y-3">
                            @foreach ($categoria->examenes as $examen)
                                <div class="bg-slate-50/50 rounded-xl border border-slate-200 p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 text-slate-700 rounded-full text-sm font-medium">
                                                {{ $examen->id_examen }}
                                            </span>
                                            <div class="font-medium text-slate-800">{{ $examen->nombre_examen }}</div>
                                        </div>
                                        @if ($esAdmin)
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 shadow-sm transition-all duration-200 js-btn-edit"
                                                data-id="{{ $examen->id_examen }}" title="Editar"
                                                aria-label="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="bg-indigo-50 text-indigo-700 rounded-md px-2 py-1 text-xs inline-block">
                                            {{ $categoria->nombre_categoria }}
                                        </span>
                                    </div>
                                    
                                    {{-- Modo edición móvil --}}
                                    <div class="hidden js-row-edit-mobile mt-3 pt-3 border-t border-slate-200"
                                         data-row="{{ $examen->id_examen }}">
                                        <form method="POST"
                                            action="{{ route('admin.examenes.update', $examen->id_examen) }}"
                                            class="space-y-3">
                                            @csrf
                                            @method('PUT')

                                            <div class="space-y-2">
                                                <label class="block text-xs font-medium text-slate-700">Categoría *</label>
                                                <select name="categoria_id"
                                                    class="w-full border border-slate-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 text-sm"
                                                    required>
                                                    @foreach ($categorias as $cat)
                                                        <option value="{{ $cat->id_categoria }}"
                                                            @selected($cat->id_categoria == $examen->categoria_id)>
                                                            {{ $cat->nombre_categoria }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-medium text-slate-700">Nombre *</label>
                                                <input type="text" name="nombre_examen"
                                                    value="{{ $examen->nombre_examen }}"
                                                    class="w-full border border-slate-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 text-sm js-input-name"
                                                    required>
                                            </div>

                                            <div class="flex gap-2 pt-2">
                                                <button type="button"
                                                    class="flex-1 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-300 px-3 py-2 text-sm font-medium transition-all duration-200 js-btn-cancel-mobile"
                                                    data-id="{{ $examen->id_examen }}">
                                                    Cancelar
                                                </button>
                                                <button type="submit"
                                                    class="flex-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-3 py-2 text-sm font-medium text-white shadow-sm transition-all duration-200">
                                                    Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tabla para tablet/desktop -->
                        <div class="hidden sm:block overflow-x-auto rounded-xl border border-slate-200"
                            style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain; scrollbar-gutter:stable;">
                            <table class="w-full text-sm table-auto min-w-[640px] lg:min-w-[720px] border-collapse">
                                <colgroup>
                                    <col class="w-20">
                                    <col class="w-48 lg:w-64">
                                    <col>
                                    <col class="w-32">
                                </colgroup>

                                <thead class="bg-indigo-600 text-white">
                                    <tr>
                                        <th class="px-3 sm:px-4 py-3 text-left font-medium first:pl-6 first:rounded-tl-xl text-xs sm:text-sm">ID</th>
                                        <th class="px-3 sm:px-4 py-3 text-left font-medium text-xs sm:text-sm">Categoría</th>
                                        <th class="px-3 sm:px-4 py-3 text-left font-medium text-xs sm:text-sm">Nombre del Examen</th>
                                        <th class="px-3 sm:px-4 py-3 text-center font-medium last:pr-6 last:rounded-tr-xl text-xs sm:text-sm">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($categoria->examenes as $examen)
                                        {{-- Fila en modo lectura --}}
                                        <tr class="hover:bg-slate-50/80 transition-colors duration-150 js-row-view"
                                            data-row="{{ $examen->id_examen }}">
                                            <td class="px-3 sm:px-4 py-3 text-center first:pl-6">
                                                <span class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-slate-100 text-slate-700 rounded-full text-xs sm:text-sm font-medium">
                                                    {{ $examen->id_examen }}
                                                </span>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3">
                                                <span class="bg-indigo-50 text-indigo-700 rounded-md px-2 py-1 text-xs sm:text-sm inline-block">
                                                    {{ $categoria->nombre_categoria }}
                                                </span>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3">
                                                <div class="font-medium text-slate-800 text-sm sm:text-base">{{ $examen->nombre_examen }}</div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3 text-center last:pr-6">
                                                @if ($esAdmin)
                                                    <div class="flex justify-center gap-1.5">
                                                        <button type="button"
                                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 shadow-sm transition-all duration-200 js-btn-edit"
                                                            data-id="{{ $examen->id_examen }}" title="Editar">
                                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">Solo lectura</span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Fila en modo edición --}}
                                        <tr class="hidden js-row-edit bg-slate-50/60"
                                            data-row="{{ $examen->id_examen }}">
                                            <td colspan="4" class="p-3 sm:p-4">
                                                <form method="POST"
                                                    action="{{ route('admin.examenes.update', $examen->id_examen) }}"
                                                    class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                                    @csrf
                                                    @method('PUT')

                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">ID</label>
                                                        <div class="px-3 py-2 bg-slate-100 rounded-lg text-slate-600 text-xs sm:text-sm">
                                                            {{ $examen->id_examen }}
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Categoría *</label>
                                                        <select name="categoria_id"
                                                            class="w-full border border-slate-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 text-xs sm:text-sm"
                                                            required>
                                                            @foreach ($categorias as $cat)
                                                                <option value="{{ $cat->id_categoria }}"
                                                                    @selected($cat->id_categoria == $examen->categoria_id)>
                                                                    {{ $cat->nombre_categoria }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Nombre *</label>
                                                        <input type="text" name="nombre_examen"
                                                            value="{{ $examen->nombre_examen }}"
                                                            class="w-full border border-slate-300 rounded-lg px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 text-xs sm:text-sm js-input-name"
                                                            required>
                                                    </div>

                                                    <div class="flex gap-2 justify-end">
                                                        <button type="button"
                                                            class="rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-300 px-3 py-2 text-xs sm:text-sm font-medium transition-all duration-200 js-btn-cancel"
                                                            data-id="{{ $examen->id_examen }}">
                                                            Cancelar
                                                        </button>
                                                        <button type="submit"
                                                            class="rounded-lg bg-indigo-600 hover:bg-indigo-700 px-3 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition-all duration-200">
                                                            Guardar
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot class="bg-slate-50/50 border-t border-slate-200">
                                    <tr>
                                        <td colspan="4" class="px-4 sm:px-6 py-3 text-slate-500 text-xs sm:text-sm rounded-b-xl text-center">
                                            <span class="text-slate-600">Total: {{ $categoria->examenes->count() }} exámenes</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-200 p-6 sm:p-8 text-center text-slate-500">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-600">No hay categorías registradas aún.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal para crear nueva categoría --}}
    @if($esAdmin)
    <div id="modalCrearCategoria" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-3 sm:px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Fondo oscuro -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/50" aria-hidden="true"></div>

            <!-- Centrar modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Contenido del modal -->
            <div
                class="inline-block w-full max-w-md p-4 sm:p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl sm:rounded-2xl sm:my-8 sm:align-middle">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Nueva Categoría
                    </h3>
                    <button type="button" class="text-slate-400 hover:text-slate-500 transition-colors js-modal-close">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form id="formCrearCategoria" method="POST" action="{{ route('admin.categorias.store') }}">
                    @csrf
                    <div class="mb-4 sm:mb-6">
                        <label for="nombre_categoria" class="block text-sm font-medium text-slate-700 mb-2">
                            Nombre de la categoría *
                        </label>
                        <input type="text" id="nombre_categoria" name="nombre_categoria"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-200"
                            placeholder="Ej: Hematología, Radiología, etc."
                            required
                            autofocus>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500">
                            La categoría se agregará inmediatamente y podrás seleccionarla para nuevos exámenes.
                        </p>
                    </div>

                    <div class="flex justify-end gap-2 sm:gap-3">
                        <button type="button"
                            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all duration-200 js-modal-close">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-all duration-200 js-btn-crear"
                            disabled>
                            <span id="btnText">Crear Categoría</span>
                            <span id="btnLoading" class="hidden">
                                <svg class="inline w-4 h-4 ml-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- SweetAlert + JS --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        // Alternar a modo edición (desktop)
        document.addEventListener('click', (e) => {
            const editBtn = e.target.closest('.js-btn-edit');
            if (!editBtn) return;

            const id = editBtn.dataset.id;
            
            // Determinar si estamos en móvil o desktop
            const isMobile = window.innerWidth < 640;
            
            if (isMobile) {
                // Modo móvil - mostrar/ocultar formulario en tarjeta
                const editMobile = document.querySelector(`.js-row-edit-mobile[data-row="${id}"]`);
                const card = editBtn.closest('.bg-slate-50\\/50');
                
                if (editMobile) {
                    // Cerrar otros formularios móviles
                    document.querySelectorAll('.js-row-edit-mobile').forEach(r => r.classList.add('hidden'));
                    
                    // Alternar visibilidad
                    if (editMobile.classList.contains('hidden')) {
                        editMobile.classList.remove('hidden');
                    } else {
                        editMobile.classList.add('hidden');
                    }
                }
            } else {
                // Modo desktop - mostrar fila de edición en tabla
                document.querySelectorAll('.js-row-edit').forEach(r => r.classList.add('hidden'));
                document.querySelectorAll('.js-row-view').forEach(r => r.classList.remove('hidden'));

                const rowEdit = document.querySelector(`.js-row-edit[data-row="${id}"]`);
                const rowView = document.querySelector(`.js-row-view[data-row="${id}"]`);
                if (rowEdit && rowView) {
                    rowView.classList.add('hidden');
                    rowEdit.classList.remove('hidden');
                    const input = rowEdit.querySelector('.js-input-name');
                    if (input) input.focus();
                }
            }
        });

        // Cancelar edición (desktop)
        document.addEventListener('click', (e) => {
            const cancelBtn = e.target.closest('.js-btn-cancel');
            if (!cancelBtn) return;

            const id = cancelBtn.dataset.id;
            const rowEdit = document.querySelector(`.js-row-edit[data-row="${id}"]`);
            const rowView = document.querySelector(`.js-row-view[data-row="${id}"]`);
            if (rowEdit && rowView) {
                rowEdit.classList.add('hidden');
                rowView.classList.remove('hidden');
            }
        });

        // Cancelar edición (móvil)
        document.addEventListener('click', (e) => {
            const cancelBtn = e.target.closest('.js-btn-cancel-mobile');
            if (!cancelBtn) return;

            const id = cancelBtn.dataset.id;
            const editMobile = document.querySelector(`.js-row-edit-mobile[data-row="${id}"]`);
            if (editMobile) {
                editMobile.classList.add('hidden');
            }
        });

        // ===== FUNCIONALIDAD PARA CREAR CATEGORÍA =====
        @if($esAdmin)
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalCrearCategoria');
            const form = document.getElementById('formCrearCategoria');
            const nombreInput = document.getElementById('nombre_categoria');
            const crearBtn = form.querySelector('.js-btn-crear');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const categoriaSelect = document.querySelector('.js-categoria-select');

            // Abrir modal
            document.addEventListener('click', function(e) {
                if (e.target.closest('.js-btn-crear-categoria')) {
                    modal.classList.remove('hidden');
                    nombreInput.focus();
                }
            });

            // Cerrar modal
            document.addEventListener('click', function(e) {
                if (e.target.closest('.js-modal-close') || e.target === modal) {
                    modal.classList.add('hidden');
                    form.reset();
                    crearBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                }
            });

            // Validación en tiempo real
            nombreInput.addEventListener('input', function() {
                crearBtn.disabled = this.value.trim().length === 0;
            });

            // Enviar formulario con AJAX
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const nombre = nombreInput.value.trim();
                if (!nombre) return;

                // Mostrar loading
                crearBtn.disabled = true;
                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');

                try {
                    const response = await fetch('{{ route("admin.categorias.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ nombre_categoria: nombre })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Cerrar modal
                        modal.classList.add('hidden');
                        form.reset();

                        // Agregar nueva opción al select
                        const option = document.createElement('option');
                        option.value = data.categoria.id;
                        option.textContent = data.categoria.nombre;
                        option.selected = true;
                        categoriaSelect.appendChild(option);

                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Categoría creada!',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            timer: 2000,
                            showConfirmButton: false,
                            heightAuto: false
                        });

                        // También agregar al select de edición si existe
                        document.querySelectorAll('select[name="categoria_id"]').forEach(select => {
                            if (select !== categoriaSelect) {
                                const newOption = document.createElement('option');
                                newOption.value = data.categoria.id;
                                newOption.textContent = data.categoria.nombre;
                                select.appendChild(newOption);
                            }
                        });

                        // Recargar la página para ver la nueva categoría en la lista
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonText: 'Entendido',
                            heightAuto: false
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor.',
                        confirmButtonText: 'Entendido',
                        heightAuto: false
                    });
                } finally {
                    // Restaurar botón
                    crearBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                }
            });

            // Cerrar con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    form.reset();
                }
            });
        });
        @endif
    </script>
</x-app-layout>