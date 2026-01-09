<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 leading-tight text-center">
            Buscar Encargados
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
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }

        /* Estilos para campos de edad */
        .readonly-field {
            background-color: #f9fafb;
            border-color: #d1d5db;
            color: #6b7280;
            cursor: not-allowed;
        }

        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

    <div class="max-w-screen-2xl mx-auto px-4">
        <div x-data="buscadorEncargados()" x-init="init()" class="max-w-screen-2xl mx-auto space-y-5">
            <div class="w-full flex justify-center mb-6">
                {{-- Buscador --}}
                <form method="GET" action="{{ route('recepcion.buscar') }}">
                    <div class="flex items-center gap-2">
                        <input type="text" name="q" x-model="q"
                            placeholder="Buscar por nombre, apellido o DNI..."
                            class="flex-1 border border-slate-300 px-3 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-400 transition-all duration-200">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            Buscar
                        </button>
                        <a href="{{ route('recepcion.buscar') }}"
                            class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Barra superior: Volver --}}
            <div class="mb-3 flex items-center justify-end">
                <a href="{{ route('recepcion.verPacientes') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm hover:bg-slate-50 shadow-sm hover:shadow-md transition-all duration-200">
                    ← Volver a Pacientes
                </a>
            </div>

            {{-- Tabla ENCARGADOS --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200/50">
                <div class="relative w-full rounded-xl overflow-hidden"
                    style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain; scrollbar-gutter:stable;">
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm table-auto min-w-[1400px] md:min-w-[1600px] xl:min-w-0 border-collapse">
                            <colgroup>
                                <col class="w-16">
                                <col class="w-40">
                                <col class="w-40">
                                <col class="w-32">
                                <col class="w-48">
                                <col class="w-36">
                                <col class="w-24">
                                <col class="w-36">
                                <col class="w-24">
                                <col class="w-52">
                            </colgroup>
                            <thead class="bg-indigo-600 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium first:pl-6 first:rounded-tl-xl">ID</th>
                                    <th class="px-4 py-3 text-left font-medium">Nombre</th>
                                    <th class="px-4 py-3 text-left font-medium">Apellido</th>
                                    <th class="px-4 py-3 text-left font-medium">DNI</th>
                                    <th class="px-4 py-3 text-left font-medium">Dirección</th>
                                    <th class="px-4 py-3 text-left font-medium">Teléfono</th>
                                    <th class="px-4 py-3 text-left font-medium">Edad</th>
                                    <th class="px-4 py-3 text-left font-medium">Fecha Nac.</th>
                                    <th class="px-4 py-3 text-left font-medium">Sexo</th>
                                    <th
                                        class="px-4 py-3 text-center font-medium last:pr-6 last:rounded-tr-xl bg-indigo-600">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="e in res" :key="'e-' + e.id_encargado">
                                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                        <td class="px-4 py-3 text-center first:pl-6">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium"
                                                x-text="e.id_encargado"></span>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-slate-800" x-text="e.nombre"></td>
                                        <td class="px-4 py-3 font-medium text-slate-800" x-text="e.apellido"></td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm inline-block"
                                                x-text="e.dni"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="bg-blue-50 text-blue-700 rounded-md px-2 py-1 text-sm inline-block"
                                                x-text="e.direccion || '-'"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="bg-emerald-50 text-emerald-700 rounded-md px-2 py-1 text-sm inline-block"
                                                x-text="e.telefono || '-'"></span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[2rem] bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-sm"
                                                x-text="e.edad || '-'"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <template x-if="e.fecha_nacimiento">
                                                <span
                                                    class="bg-purple-50 text-purple-700 rounded-md px-2 py-1 text-sm inline-block"
                                                    x-text="formatDate(e.fecha_nacimiento)"></span>
                                            </template>
                                            <template x-if="!e.fecha_nacimiento">
                                                <span class="text-slate-400 italic text-sm">-</span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <template x-if="e.sexo === 'M'">
                                                <span
                                                    class="bg-cyan-50 text-cyan-700 rounded-md px-2 py-1 text-sm inline-block">
                                                    Masculino
                                                </span>
                                            </template>
                                            <template x-if="e.sexo === 'F'">
                                                <span
                                                    class="bg-pink-50 text-pink-700 rounded-md px-2 py-1 text-sm inline-block">
                                                    Femenino
                                                </span>
                                            </template>
                                            <template x-if="!e.sexo">
                                                <span class="text-slate-400 italic text-sm">-</span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 text-center last:pr-6 bg-white">
                                            <button type="button" @click="abrirEditar(e)"
                                                class="inline-flex items-center gap-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-lg text-xs font-medium shadow-xs hover:shadow-sm transition-all duration-200 whitespace-nowrap">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="!res.length && !cargando">
                                    <td colspan="10" class="px-6 py-8 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <p class="text-slate-600">No se encontraron resultados</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-slate-50/50 border-t border-slate-200/50" x-show="res.length">
                                <tr>
                                    <td colspan="10" class="px-6 py-3 text-slate-500 text-sm rounded-b-xl">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-slate-600 font-medium">Total:</span>
                                                <span
                                                    class="bg-white border border-slate-200 rounded-lg px-3 py-1 text-sm"
                                                    x-text="meta.total + ' encargados'"></span>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="button"
                                                    class="px-3 py-1 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-sm transition-colors"
                                                    :disabled="meta.current_page <= 1"
                                                    @click="buscar(meta.current_page-1)">
                                                    ← Anterior
                                                </button>
                                                <span class="px-3 py-1 text-slate-600 text-sm">
                                                    Página <span x-text="meta.current_page"></span>/<span
                                                        x-text="meta.last_page"></span>
                                                </span>
                                                <button type="button"
                                                    class="px-3 py-1 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-sm transition-colors"
                                                    :disabled="meta.current_page >= meta.last_page"
                                                    @click="buscar(meta.current_page+1)">
                                                    Siguiente →
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal editar Encargado --}}
            <template x-teleport="body">
                <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
                    @keydown.escape.window="cerrarEditar()">
                    <div class="fixed inset-0 bg-black/40" @click="cerrarEditar()"></div>

                    <!-- Contenedor principal del modal con scroll -->
                    <div class="relative min-h-screen py-8 flex items-center justify-center">
                        <div
                            class="relative mx-auto w-[min(720px,92vw)] max-h-[90vh] rounded-xl bg-white shadow-xl ring-1 ring-slate-200 flex flex-col">
                            <!-- Encabezado fijo -->
                            <div
                                class="flex items-center justify-between px-6 py-4 border-b border-slate-200 shrink-0">
                                <h3 class="text-lg font-semibold text-slate-800">
                                    Editar Encargado #<span x-text="edit?.id_encargado"></span>
                                </h3>
                                <button type="button" @click="cerrarEditar()"
                                    class="rounded-lg p-2 hover:bg-slate-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Contenido con scroll -->
                            <div class="flex-1 overflow-y-auto px-6 py-4">
                                <form @submit.prevent="guardarEdicion">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-slate-700">Nombre</label>
                                            <input id="edit-nombre" type="text" x-model="edit.nombre"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                                required>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-slate-700">Apellido</label>
                                            <input type="text" x-model="edit.apellido"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                                required>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-slate-700">DNI</label>
                                            <input type="text" x-model="edit.dni" maxlength="13"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                                required>
                                        </div>

                                        {{-- Edad (calculada automáticamente) --}}
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-slate-700">Edad</label>
                                            <div class="relative">
                                                <input type="text" x-model="edit.edad_display"
                                                    class="w-full rounded-lg border border-slate-300 bg-slate-50 text-slate-600 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all readonly-field"
                                                    readonly disabled>
                                                <div
                                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <span
                                                        class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                                        Calculado
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-xs text-slate-500" id="edit-edad-info">
                                                Se calcula automáticamente desde la fecha de nacimiento
                                            </p>
                                            {{-- Campo oculto para enviar la edad al servidor --}}
                                            <input type="hidden" x-model="edit.edad" id="edit-edad-hidden">
                                        </div>

                                        {{-- Fecha de Nacimiento (calcula edad automáticamente) --}}
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-slate-700">Fecha de
                                                Nacimiento</label>
                                            <input type="date" x-model="edit.fecha_nacimiento_formatted"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                                max="{{ now()->format('Y-m-d') }}" @blur="calcularEdadDesdeFechaEdit">
                                            <p class="text-xs text-slate-500" id="edit-fecha-info">
                                                Complete la fecha y salga del campo para calcular la edad
                                            </p>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-slate-700">Sexo</label>
                                            <select x-model="edit.sexo"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                                <option value="">Seleccione…</option>
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-slate-700">Teléfono</label>
                                            <input type="text" x-model="edit.telefono"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                        </div>
                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-sm font-medium text-slate-700">Dirección</label>
                                            <input type="text" x-model="edit.direccion"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                        </div>
                                    </div>

                                    <div
                                        class="mt-6 pt-4 border-t border-slate-200 flex items-center justify-end gap-3 shrink-0">
                                        <button type="button" @click="cerrarEditar()"
                                            class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition-colors">
                                            Cancelar
                                        </button>
                                        <button type="submit" id="btn-guardar-edicion"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium shadow-sm hover:shadow-md transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Guardar cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <style>
        /* Estilos para scroll suave */
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

        /* Transiciones suaves */
        tr,
        button,
        input,
        select {
            transition: all 0.2s ease;
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
    </style>

    <script>
        function buscadorEncargados() {
            return {
                q: new URLSearchParams(location.search).get('q') || '',
                perPage: 25,
                cargando: false,
                res: [],
                meta: {
                    current_page: 1,
                    last_page: 1,
                    total: 0
                },

                editOpen: false,
                edit: null,

                init() {
                    const page = Number(new URLSearchParams(location.search).get('page')) || 1;
                    this.buscar(page);
                },

                async buscar(page = 1) {
                    this.cargando = true;
                    try {
                        const base = `{{ route('recepcion.api.encargados') }}`;
                        const url = new URL(base, window.location.origin);
                        if (this.q) url.searchParams.set('q', this.q);
                        url.searchParams.set('per_page', this.perPage);
                        url.searchParams.set('page', page);

                        const r = await fetch(url, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        const js = await r.json();

                        this.res = js.data || [];
                        this.meta = js.meta || {
                            current_page: page,
                            last_page: 1,
                            total: this.res.length,
                            per_page: this.perPage
                        };

                        const qs = new URLSearchParams({
                            q: this.q || '',
                            page: String(this.meta.current_page)
                        });
                        history.replaceState(null, '', `?${qs.toString()}`);
                    } finally {
                        this.cargando = false;
                    }
                },

                // Función para formatear fecha
                formatDate(dateString) {
                    if (!dateString) return '-';
                    try {
                        const date = new Date(dateString);
                        return date.toLocaleDateString('es-ES', {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit'
                        });
                    } catch (e) {
                        return dateString || '-';
                    }
                },

                // Nueva función para convertir fecha al formato YYYY-MM-DD
                formatDateForInput(dateString) {
                    if (!dateString) return '';
                    try {
                        const date = new Date(dateString);
                        return date.toISOString().split('T')[0];
                    } catch (e) {
                        return '';
                    }
                },

                // Función para calcular edad desde fecha de nacimiento (en modal de edición)
                calcularEdadDesdeFechaEdit() {
                    const fechaNacInput = this.edit?.fecha_nacimiento_formatted;
                    const edadInfo = document.getElementById('edit-edad-info');
                    const fechaInfo = document.getElementById('edit-fecha-info');

                    if (!fechaNacInput) {
                        this.edit.edad = '';
                        this.edit.edad_display = '';
                        if (edadInfo) {
                            edadInfo.textContent = 'Seleccione una fecha de nacimiento';
                            edadInfo.className = 'text-xs text-amber-600';
                        }
                        return;
                    }

                    // Verificar que la fecha sea válida (formato completo YYYY-MM-DD)
                    const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
                    if (!fechaRegex.test(fechaNacInput)) {
                        // Fecha incompleta, no calcular todavía
                        if (edadInfo) {
                            edadInfo.textContent = 'Complete la fecha para calcular la edad';
                            edadInfo.className = 'text-xs text-amber-600';
                        }
                        return;
                    }

                    const fechaNac = new Date(fechaNacInput);
                    const hoy = new Date();

                    // Verificar que la fecha sea válida (no NaN)
                    if (isNaN(fechaNac.getTime())) {
                        if (edadInfo) {
                            edadInfo.textContent = 'Fecha inválida';
                            edadInfo.className = 'text-xs text-rose-600';
                        }
                        return;
                    }

                    // Validar que la fecha no sea futura
                    if (fechaNac > hoy) {
                        if (edadInfo) {
                            edadInfo.textContent = 'La fecha de nacimiento no puede ser futura';
                            edadInfo.className = 'text-xs text-rose-600';
                        }
                        this.edit.edad = '';
                        this.edit.edad_display = '';
                        return;
                    }

                    // Cálculo preciso de edad
                    let edad = hoy.getFullYear() - fechaNac.getFullYear();
                    const mesDiferencia = hoy.getMonth() - fechaNac.getMonth();
                    const diaDiferencia = hoy.getDate() - fechaNac.getDate();

                    // Ajustar si aún no ha cumplido años este año
                    if (mesDiferencia < 0 || (mesDiferencia === 0 && diaDiferencia < 0)) {
                        edad--;
                    }

                    // Validar rango de edad razonable
                    if (edad < 0 || edad > 120) {
                        if (edadInfo) {
                            edadInfo.textContent = 'Edad fuera del rango permitido (0-120 años)';
                            edadInfo.className = 'text-xs text-rose-600';
                        }
                        this.edit.edad = '';
                        this.edit.edad_display = '';
                        return;
                    }

                    // Asignar la edad calculada
                    this.edit.edad = edad;
                    this.edit.edad_display = `${edad} años`;

                    // Actualizar información de fecha
                    if (fechaInfo) {
                        const fechaFormateada = this.formatDate(fechaNacInput);
                        fechaInfo.textContent = `Fecha: ${fechaFormateada}`;
                        fechaInfo.className = 'text-xs text-emerald-600';
                    }

                    if (edadInfo) {
                        edadInfo.textContent = `Edad calculada: ${edad} años`;
                        edadInfo.className = 'text-xs text-emerald-600';
                    }

                    console.log('Edad calculada en edición:', edad);
                },

                abrirEditar(e) {
                    // Clonar el objeto para no modificar el original
                    this.edit = {
                        ...e
                    };

                    // Convertir fecha_nacimiento al formato YYYY-MM-DD para el input[type="date"]
                    if (this.edit.fecha_nacimiento) {
                        this.edit.fecha_nacimiento_formatted = this.formatDateForInput(this.edit.fecha_nacimiento);
                    } else {
                        this.edit.fecha_nacimiento_formatted = '';
                    }

                    // Inicializar campo de visualización de edad
                    this.edit.edad_display = this.edit.edad ? `${this.edit.edad} años` : '';

                    this.editOpen = true;

                    // Calcular edad si ya hay fecha
                    this.$nextTick(() => {
                        if (this.edit.fecha_nacimiento_formatted) {
                            setTimeout(() => {
                                this.calcularEdadDesdeFechaEdit();
                            }, 100);
                        }
                        document.getElementById('edit-nombre')?.focus();
                    });
                },

                cerrarEditar() {
                    this.editOpen = false;
                    this.edit = null;
                },

                async guardarEdicion() {
                    if (!this.edit) return;

                    // Validar campos requeridos
                    const nombre = this.edit.nombre?.trim();
                    const apellido = this.edit.apellido?.trim();
                    const dni = this.edit.dni?.trim();
                    const sexo = this.edit.sexo;

                    let errores = [];

                    if (!nombre) errores.push('• Nombre es requerido');
                    if (!apellido) errores.push('• Apellido es requerido');
                    if (!dni) errores.push('• DNI es requerido');
                    if (dni && dni.length !== 13) errores.push('• DNI debe tener 13 dígitos');
                    if (!sexo) errores.push('• Sexo es requerido');

                    // Validar fecha de nacimiento vs edad
                    if (this.edit.fecha_nacimiento_formatted && !this.edit.edad) {
                        errores.push('• La fecha de nacimiento no pudo calcular la edad. Verifique la fecha.');
                    }

                    if (errores.length > 0) {
                        this.toast('Por favor complete los campos requeridos:\n\n' + errores.join('\n'), 'error');
                        return;
                    }

                    const url = `{{ route('recepcion.api.encargados.update', ['id' => '_ID_']) }}`.replace('_ID_', this
                        .edit.id_encargado);

                    // Preparar payload - asegurarse de que fecha_nacimiento esté en formato correcto
                    const payload = {
                        nombre: nombre || null,
                        apellido: apellido || null,
                        dni: dni || null,
                        edad: this.edit.edad || null,
                        fecha_nacimiento: this.edit.fecha_nacimiento_formatted || null,
                        sexo: sexo || null,
                        telefono: this.edit.telefono?.trim() || null,
                        direccion: this.edit.direccion?.trim() || null,
                    };

                    console.log('Enviando payload:', payload); // Para debug

                    // Deshabilitar botón para evitar doble envío
                    const btnGuardar = document.getElementById('btn-guardar-edicion');
                    const originalText = btnGuardar?.innerHTML;
                    if (btnGuardar) {
                        btnGuardar.disabled = true;
                        btnGuardar.innerHTML = `
                        <svg class="w-4 h-4 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Guardando...
                    `;
                        btnGuardar.classList.add('opacity-70', 'cursor-not-allowed');
                    }

                    try {
                        const r = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload),
                        });

                        // Restaurar botón
                        if (btnGuardar && originalText) {
                            btnGuardar.disabled = false;
                            btnGuardar.innerHTML = originalText;
                            btnGuardar.classList.remove('opacity-70', 'cursor-not-allowed');
                        }

                        if (r.status === 419) {
                            this.toast('Sesión expirada. Refresca la página.', 'error');
                            return;
                        }

                        const response = await r.json();

                        if (r.status === 422) {
                            const errors = Object.values(response.errors || {}).flat().join('\n') || 'Datos inválidos';
                            this.toast(errors, 'error');
                            return;
                        }

                        if (!r.ok) {
                            console.error('Update error:', response);
                            this.toast(response.message || 'No se pudo actualizar', 'error');
                            return;
                        }

                        // Actualizar la fila en memoria
                        const updated = response.data || response;
                        this.res = this.res.map(x =>
                            x.id_encargado === updated.id_encargado ? {
                                ...x,
                                ...updated
                            } : x
                        );

                        // Cerrar modal
                        this.cerrarEditar();

                        // Mostrar confirmación
                        await Swal.fire({
                            title: '¡Éxito!',
                            text: 'Encargado actualizado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                            buttonsStyling: false,
                            customClass: {
                                popup: 'rounded-xl shadow-lg',
                                confirmButton: 'px-4 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700'
                            }
                        });
                    } catch (error) {
                        // Restaurar botón en caso de error
                        if (btnGuardar && originalText) {
                            btnGuardar.disabled = false;
                            btnGuardar.innerHTML = originalText;
                            btnGuardar.classList.remove('opacity-70', 'cursor-not-allowed');
                        }
                        this.toast('Error de conexión. Intente nuevamente.', 'error');
                        console.error('Error:', error);
                    }
                },

                toast(msg = 'Listo', type = 'success') {
                    if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            icon: type,
                            title: msg,
                            position: 'top-end',
                            timer: 1800,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-lg'
                            }
                        });
                    } else {
                        alert(msg);
                    }
                },
            }
        }
    </script>
</x-app-layout>
