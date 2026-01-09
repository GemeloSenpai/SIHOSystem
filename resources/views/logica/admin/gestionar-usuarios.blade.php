<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Gestionar Usuarios
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
        
        /* Oculta elementos con x-cloak hasta que Alpine cargue */
        [x-cloak] {
            display: none !important;
        }

        /* Estilos para el input de edad deshabilitado */
        input:disabled {
            background-color: #f8fafc;
            cursor: not-allowed;
            opacity: 0.9;
        }

        /* Estilos para los botones de calcular */
        button[title="Calcular edad"]:hover svg {
            transform: scale(1.1);
        }

        /* Animación para el cálculo de edad */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .text-emerald-600 {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Responsive para botones */
        @media (max-width: 640px) {
            .flex.items-center.gap-2 > button {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                font-size: 0.75rem;
            }
        }
    </style>

    <div x-data="usuariosAdmin()" x-init="init()" class="max-w-screen-2xl mx-auto px-4 py-4">

        {{-- ================== Buscador mejorado ================== --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-5 mb-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-800">Buscar Usuarios</h3>
            </div>
            
            <div class="mb-4">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <input type="text" id="buscarUsuarios" x-model.debounce.350ms="q"
                        placeholder="Buscar por nombre, DNI o email..."
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200" />
                    <button type="button" @click="q=''; buscar();"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200">
                        Limpiar
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500">
                <template x-if="q">
                    <div class="flex items-center gap-2">
                        <span>Resultados:</span>
                        <span class="bg-indigo-50 text-indigo-700 rounded px-2 py-0.5 text-xs font-medium" x-text="resultados.length"></span>
                    </div>
                </template>
                <template x-if="!q">
                    <span class="text-slate-600">Mostrando usuarios por página</span>
                </template>

                <template x-if="cargando">
                    <span class="inline-flex items-center gap-2 text-amber-600">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 008 12H4z" />
                        </svg>
                        Buscando...
                    </span>
                </template>
            </div>
        </div>

        {{-- ================== Tabla mejorada ================== --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 overflow-hidden mb-4">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.943a8.25 8.25 0 00-13.668-5.108" />
                    </svg>
                    Gestión de Usuarios
                </h3>
                <div class="text-sm text-slate-600">
                    <span class="font-medium">Total:</span>
                    <span class="bg-indigo-50 text-indigo-700 rounded-md px-2 py-1 text-sm ml-1">{{ $usuarios->total() }}</span>
                </div>
            </div>

            <div class="overflow-x-auto rounded-b-xl"
                style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain; scrollbar-gutter:stable;">
                <table class="w-full text-xs table-auto min-w-[1200px] border-collapse">
                    <colgroup>
                        <col class="w-12"> {{-- ID --}}
                        <col class="w-48"> {{-- Nombre --}}
                        <col class="w-48"> {{-- Apellido --}}
                        <col class="w-32"> {{-- DNI --}}
                        <col class="w-40"> {{-- Teléfono --}}
                        <col class="w-64"> {{-- Email --}}
                        <col class="w-40"> {{-- Role --}}
                        <col class="w-32"> {{-- Estado --}}
                        <col class="w-48"> {{-- Acciones --}}
                    </colgroup>

                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium first:pl-6 first:rounded-tl-xl">ID</th>
                            <th class="px-4 py-3 text-left font-medium">Nombre</th>
                            <th class="px-4 py-3 text-left font-medium">Apellido</th>
                            <th class="px-4 py-3 text-left font-medium">DNI</th>
                            <th class="px-4 py-3 text-left font-medium">Teléfono</th>
                            <th class="px-4 py-3 text-left font-medium">Email</th>
                            <th class="px-4 py-3 text-left font-medium">Role</th>
                            <th class="px-4 py-3 text-left font-medium">Estado</th>
                            <th class="px-4 py-3 text-center font-medium last:pr-6 last:rounded-tr-xl">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        {{-- ========== Modo búsqueda ========== --}}
                        <template x-if="q">
                            <template x-for="u in resultados" :key="`s-${u.id_empleado}`">
                                <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                                    <td class="px-4 py-3 text-center first:pl-6">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-slate-100 text-slate-700 rounded text-xs font-medium" x-text="u.id_empleado"></span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-800 text-sm" x-text="u.nombre"></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-800 text-sm" x-text="u.apellido"></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-xs inline-block" x-text="u.dni"></span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="text"
                                            class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                            x-model="u.telefono" @change="quick(u, {telefono: u.telefono})">
                                    </td>

                                    <td class="px-4 py-3">
                                        <input type="email"
                                            class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 truncate"
                                            :title="u.email" x-model="u.email" @change="quick(u, {email: u.email})">
                                    </td>

                                    <td class="px-4 py-3">
                                        <select
                                            class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                            x-model="u.role" @change="quick(u, {role: u.role})">
                                            @foreach ($roles as $r)
                                            <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="px-4 py-3">
                                        <button @click="toggleEstado(u)"
                                            :class="u.estado==='activo' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-all duration-200">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="u.estado==='activo' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                            <span x-text="u.estado === 'activo' ? 'Activo' : 'Inactivo'"></span>
                                        </button>
                                    </td>

                                    <td class="px-4 py-3 text-center last:pr-6">
                                        <div class="flex justify-center gap-1.5">
                                            <button @click="ver(u)"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all duration-200"
                                                title="Ver">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button @click="editar(u)"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-indigo-300 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition-all duration-200"
                                                title="Editar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click="abrirActualizar(u)"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 transition-all duration-200"
                                                title="Actualizar rápido">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                            <button @click="eliminar(u)"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-rose-300 bg-rose-50 hover:bg-rose-100 text-rose-700 transition-all duration-200"
                                                title="Inactivar">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </template>

                        {{-- ========== Modo paginado ========== --}}
                        @foreach ($usuarios as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150" x-data="{u: @js($u)}">
                            <td class="px-4 py-3 text-center first:pl-6">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-slate-100 text-slate-700 rounded text-xs font-medium">{{ $u->id_empleado }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800 text-sm">{{ $u->nombre }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800 text-sm">{{ $u->apellido }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-slate-100 text-slate-700 rounded-md px-2 py-1 text-xs inline-block">{{ $u->dni }}</span>
                            </td>

                            <td class="px-4 py-3">
                                <input type="text"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                    value="{{ $u->telefono }}"
                                    @change="$store.usr.quick(u.id_empleado, {telefono: $event.target.value}, $el)">
                            </td>

                            <td class="px-4 py-3">
                                <input type="email"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 truncate"
                                    title="{{ $u->email }}" value="{{ $u->email }}"
                                    @change="$store.usr.quick(u.id_empleado, {email: $event.target.value}, $el)">
                            </td>

                            <td class="px-4 py-3">
                                <select
                                    class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                    @change="$store.usr.quick(u.id_empleado, {role: $event.target.value}, $el)">
                                    @foreach ($roles as $r)
                                    <option value="{{ $r }}" @selected($u->role === $r)>{{ ucfirst($r) }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="px-4 py-3">
                                <button @click="$store.usr.toggle(u.id_empleado, $el)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-all duration-200 {{ $u->estado==='activo' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $u->estado==='activo' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $u->estado === 'activo' ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>

                            <td class="px-4 py-3 text-center last:pr-6">
                                <div class="flex justify-center gap-1.5">
                                    <button @click="$store.usr.ver(u.id_empleado)"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-700 transition-all duration-200"
                                        title="Ver">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button @click="$store.usr.editar(u.id_empleado)"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-indigo-300 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition-all duration-200"
                                        title="Editar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="$store.usr.abrirActualizar(u.id_empleado)"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 transition-all duration-200"
                                        title="Actualizar rápido">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                    <button @click="$store.usr.eliminar(u.id_empleado)"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-lg border border-rose-300 bg-rose-50 hover:bg-rose-100 text-rose-700 transition-all duration-200"
                                        title="Inactivar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot class="bg-slate-50/50 border-t border-slate-200">
                        <tr>
                            <td colspan="9" class="px-6 py-3 text-slate-500 text-xs rounded-b-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-600 font-medium">Resumen:</span>
                                        @php
                                            $activos = $usuarios->where('estado', 'activo')->count();
                                            $inactivos = $usuarios->count() - $activos;
                                        @endphp
                                        <span class="bg-emerald-50 text-emerald-700 rounded-md px-2 py-1 text-xs">🟢 {{ $activos }} activos</span>
                                        <span class="bg-rose-50 text-rose-700 rounded-md px-2 py-1 text-xs">🔴 {{ $inactivos }} inactivos</span>
                                    </div>
                                    <div class="text-slate-600 text-xs">
                                        Mostrando {{ $usuarios->count() }} de {{ $usuarios->total() }} usuarios
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-4 text-center px-4 pb-4">
                <div x-show="!q">
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>

        {{-- ================== Modal mejorado ================== --}}
        <div x-show="modal.abierto" x-cloak x-trap.noscroll="modal.abierto" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true"
            :aria-label="modal.titulo ?? 'Modal'">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/50" @click="cerrarModal()"></div>

            {{-- Panel --}}
            <div class="relative bg-white rounded-xl shadow-xl ring-1 ring-slate-200/50
              w-[92vw] sm:w-[90vw] lg:w-[75vw] max-w-5xl 
              max-h-[85vh] lg:max-h-[70vh]
              flex flex-col overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-3 border-b border-slate-200 bg-white shrink-0 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                        <template x-if="modal.tipo==='ver'">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <template x-if="modal.tipo==='editar'">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </template>
                        <template x-if="modal.tipo==='actualizar'">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </template>
                        <span x-text="modal.titulo || 'Detalle'"></span>
                    </h3>
                    <button class="text-slate-500 hover:text-slate-700 transition-colors" @click="cerrarModal()"
                        aria-label="Cerrar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-5 py-4 overflow-y-auto flex-1 min-h-1 overscroll-contain"
                    style="-webkit-overflow-scrolling: touch;">
                    {{-- === VER === --}}
                    <template x-if="modal.tipo==='ver'">
                        <div>
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Nombre</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.nombre"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Apellido</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.apellido"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Fecha de Nacimiento</dt>
                                    <dd class="font-medium text-slate-800">
                                        <template x-if="sel.fecha_nacimiento">
                                            <span x-text="formatearFecha(sel.fecha_nacimiento)"></span>
                                        </template>
                                        <template x-if="!sel.fecha_nacimiento">
                                            <span class="text-slate-400">No registrada</span>
                                        </template>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Edad</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.edad || 'No registrada'"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">DNI</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.dni"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Sexo</dt>
                                    <dd class="font-medium text-slate-800">
                                        <template x-if="sel.sexo === 'M'">
                                            <span>Masculino</span>
                                        </template>
                                        <template x-if="sel.sexo === 'F'">
                                            <span>Femenino</span>
                                        </template>
                                        <template x-if="!sel.sexo">
                                            <span class="text-slate-400">No especificado</span>
                                        </template>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Dirección</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.direccion || 'No registrada'"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Teléfono</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.telefono || 'No registrado'"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Email</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.email"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Role</dt>
                                    <dd class="font-medium text-slate-800" x-text="sel.role"></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 mb-1">Estado</dt>
                                    <dd>
                                        <span :class="sel.estado==='activo' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="sel.estado==='activo' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                            <span x-text="sel.estado === 'activo' ? 'Activo' : 'Inactivo'"></span>
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                            <template x-if="passNueva">
                                <div class="mt-4 p-3 border border-amber-200 rounded-lg bg-amber-50 text-amber-800">
                                    <div class="font-medium text-xs mb-1">Nueva contraseña generada:</div>
                                    <div class="font-mono text-sm font-semibold" x-text="passNueva"></div>
                                    <p class="text-xs mt-1 text-amber-600">Guarda esta contraseña, solo se mostrará una vez.</p>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- === EDITAR === --}}
                    <template x-if="modal.tipo==='editar'">
                        <form id="formEditar" @submit.prevent="guardarEdicion()"
                            class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Nombre *</label>
                                <input type="text" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.nombre" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Apellido *</label>
                                <input type="text" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.apellido" required>
                            </div>
                            
                            {{-- CAMPO FECHA DE NACIMIENTO CON CÁLCULO DE EDAD --}}
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Fecha de Nacimiento *</label>
                                <div class="relative">
                                    <input type="date" 
                                           id="fechaNacimientoEdit"
                                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 pr-10"
                                           :value="formatearFechaParaInput(sel.fecha_nacimiento)"
                                           @change="actualizarFechaNacimiento($event.target.value)"
                                           required
                                           :max="hoy">
                                    <button type="button" 
                                            @click="calcularEdadDesdeFecha()"
                                            class="absolute inset-y-0 right-0 flex items-center justify-center w-10 text-slate-500 hover:text-indigo-600 transition-colors"
                                            title="Calcular edad">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-slate-500" id="fechaInfoEdit">
                                    Selecciona la fecha para calcular la edad
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Edad *</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           id="edadEdit"
                                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-slate-50"
                                           x-model="sel.edad" 
                                           min="0" 
                                           max="120"
                                           readonly
                                           disabled>
                                    <button type="button" 
                                            @click="calcularEdadDesdeFecha()"
                                            class="px-3 py-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg text-xs font-medium transition-colors whitespace-nowrap">
                                        Calcular
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-slate-500" id="edadInfoEdit">
                                    La edad se calcula automáticamente desde la fecha de nacimiento
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">DNI *</label>
                                <input type="text" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.dni" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Sexo</label>
                                <select class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.sexo">
                                    <option value="">(selecciona)</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-slate-700 mb-1">Dirección</label>
                                <input type="text" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.direccion">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Teléfono</label>
                                <input type="text" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.telefono">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Email *</label>
                                <input type="email" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.email" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Role *</label>
                                <select class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.role" required>
                                    @foreach ($roles as $r)
                                    <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Estado *</label>
                                <select class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.estado" required>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>

                            {{-- Contraseña opcional --}}
                            <div class="md:col-span-2">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    <div class="flex items-center gap-2 text-amber-800 font-medium text-xs mb-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Cambio de Contraseña (Opcional)
                                    </div>
                                    <p class="text-amber-600 text-xs mb-3">Deja en blanco si no quieres cambiar la contraseña</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 mb-1">Nueva Contraseña</label>
                                            <div class="relative">
                                                <input :type="mostrarPass ? 'text' : 'password'"
                                                    class="w-full border border-slate-300 rounded-lg px-3 py-2 pr-20 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500"
                                                    x-model="sel.password" 
                                                    autocomplete="new-password" 
                                                    placeholder="Deja en blanco para no cambiar"
                                                    minlength="8">
                                                <div class="absolute inset-y-0 right-0 flex items-center gap-1 pr-2">
                                                    <button type="button" @click="mostrarPass = !mostrarPass"
                                                        class="px-2 py-1 text-xs rounded bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all duration-200">
                                                        <span x-text="mostrarPass ? '👁️' : '👁️'"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-1" x-show="sel.password && sel.password.length < 8">
                                                Mínimo 8 caracteres
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 mb-1">Confirmar Contraseña</label>
                                            <input :type="mostrarPass ? 'text' : 'password'" 
                                                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500"
                                                x-model="sel.password_confirmation" 
                                                autocomplete="new-password" 
                                                placeholder="Repite la contraseña">
                                            <p class="text-xs text-red-500 mt-1" 
                                               x-show="sel.password && sel.password_confirmation && sel.password !== sel.password_confirmation">
                                                Las contraseñas no coinciden
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 flex gap-2">
                                        <button type="button" @click="generarPassword()"
                                            class="inline-flex items-center gap-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 border border-emerald-300 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Generar Contraseña Segura
                                        </button>
                                        
                                        <button type="button" @click="sel.password = ''; sel.password_confirmation = ''"
                                            class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200">
                                            Limpiar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </template>

                    {{-- === ACTUALIZAR RÁPIDO === --}}
                    <template x-if="modal.tipo==='actualizar'">
                        <div class="space-y-4" id="panelActualizar">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Teléfono</label>
                                    <input type="text" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.telefono">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Email</label>
                                    <input type="email" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.email">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Role</label>
                                    <select class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.role">
                                        @foreach ($roles as $r)
                                        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Estado</label>
                                    <select class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500" x-model="sel.estado">
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <template x-if="passNueva">
                                <div class="p-3 border border-amber-200 rounded-lg bg-amber-50 text-amber-800">
                                    <div class="font-medium text-xs mb-1">Nueva contraseña generada:</div>
                                    <div class="font-mono text-sm font-semibold" x-text="passNueva"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-slate-200 bg-white shrink-0 flex items-center justify-between">
                    <div>
                        <template x-if="modal.tipo==='ver' || modal.tipo==='actualizar'">
                            <button @click="resetPassword(sel)" 
                                class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Reset contraseña
                            </button>
                        </template>
                    </div>

                    <div class="flex gap-2">
                        <button @click="cerrarModal()" 
                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200">
                            Cerrar
                        </button>

                        <template x-if="modal.tipo==='editar'">
                            <button type="submit" form="formEditar"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200">
                                Guardar
                            </button>
                        </template>

                        <template x-if="modal.tipo==='actualizar'">
                            <button @click="guardarQuick()"
                                class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200">
                                Aplicar cambios
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- Script Alpine --}}
    <script>
    document.addEventListener('alpine:init', () => {
        // === Toast helpers ===
        const TOAST_MS = window.TOAST_DURATION_MS ?? 2400;
        
        function toastOk(msg) {
            if (window.toastOk) {
                window.toastOk(msg);
                return TOAST_MS;
            }
            alert(msg);
            return 0;
        }

        function toastWarn(msg) {
            if (window.toastWarn) {
                window.toastWarn(msg);
                return TOAST_MS;
            }
            alert(msg);
            return 0;
        }

        function toastErr(msg) {
            if (window.toastErr) {
                window.toastErr(msg);
                return TOAST_MS;
            }
            alert(msg);
            return 0;
        }

        Alpine.store('usr', {
            async quick(id, payload, el = null) {
                const res = await fetch(`{{ route('admin.usuarios.quick', '_ID_') }}`.replace(
                    '_ID_', id), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload),
                });
                if (!res.ok) {
                    toastErr('Error al actualizar');
                    if (el) {
                        el.classList.add('border-rose-300', 'ring-2', 'ring-rose-400');
                        setTimeout(() => el.classList.remove('border-rose-300', 'ring-2', 'ring-rose-400'), 800);
                    }
                    return;
                }
                if (el) {
                    el.classList.add('border-emerald-300', 'ring-2', 'ring-emerald-400');
                    setTimeout(() => el.classList.remove('border-emerald-300', 'ring-2', 'ring-emerald-400'), 800);
                }
                toastOk('Cambios guardados');
            },

            async toggle(id, el) {
                const res = await fetch(`{{ route('admin.usuarios.toggle', '_ID_') }}`.replace(
                    '_ID_', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                });
                const js = await res.json();
                if (res.ok) {
                    el.textContent = js.estado === 'activo' ? 'Activo' : 'Inactivo';
                    el.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-all duration-200 ' + 
                        (js.estado === 'activo' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200');
                    
                    const dot = el.querySelector('span:first-child');
                    if (dot) {
                        dot.className = 'w-1.5 h-1.5 rounded-full mr-1.5 ' + 
                            (js.estado === 'activo' ? 'bg-emerald-500' : 'bg-rose-500');
                    }
                    
                    toastOk('Estado actualizado');
                } else {
                    toastErr('No se pudo cambiar el estado');
                }
            },

            async ver(id) {
                const js = await (await fetch(`{{ route('admin.usuarios.show', '_ID_') }}`.replace(
                    '_ID_', id))).json();
                window._ua.modalAbrir('ver', 'Datos del usuario', js);
            },

            async editar(id) {
                const js = await (await fetch(`{{ route('admin.usuarios.edit', '_ID_') }}`.replace(
                    '_ID_', id))).json();
                window._ua.modalAbrir('editar', 'Editar usuario', js);
            },

            async abrirActualizar(id) {
                const js = await (await fetch(`{{ route('admin.usuarios.show', '_ID_') }}`.replace(
                    '_ID_', id))).json();
                window._ua.modalAbrir('actualizar', 'Actualizar rápido', js);
            },

            async eliminar(id) {
                if (!confirm('¿Desactivar este usuario?')) return;
                const res = await fetch(`{{ route('admin.usuarios.destroy', '_ID_') }}`.replace(
                    '_ID_', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                });
                if (res.ok) {
                    const ms = window.toastOkDelay('Usuario desactivado');
                    setTimeout(() => location.reload(), ms);
                } else {
                    toastErr('No se pudo desactivar');
                }
            },
        });
    });

    function usuariosAdmin() {
        return {
            q: '',
            resultados: [],
            modal: {
                abierto: false,
                tipo: null,
                titulo: ''
            },
            sel: {},
            passNueva: null,
            mostrarPass: false,
            cargando: false,
            hoy: new Date().toISOString().split('T')[0],

            init() {
                this.$watch('q', () => this.buscar());
                window._ua = {
                    modalAbrir: (tipo, titulo, data) => {
                        console.log('Datos recibidos en modal:', data);
                        this.modal = {
                            abierto: true,
                            tipo,
                            titulo
                        };
                        this.sel = JSON.parse(JSON.stringify(data));
                        this.passNueva = null;
                        this.mostrarPass = false;
                        
                        // Si hay fecha de nacimiento, calculamos la edad automáticamente
                        if (this.sel.fecha_nacimiento) {
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    this.calcularEdadDesdeFecha();
                                }, 100);
                            });
                        }
                        
                        delete this.sel.password;
                        delete this.sel.password_confirmation;
                    }
                };
            },

            async buscar() {
                this.cargando = true;
                try {
                    const url = new URL(`{{ route('admin.usuarios.search') }}`);
                    if (this.q) url.searchParams.set('q', this.q);
                    const res = await fetch(url);
                    const js = await res.json();
                    this.resultados = (js.mode === 'search') ? js.data : [];
                } finally {
                    this.cargando = false;
                }
            },

            cerrarModal() {
                this.modal = {
                    abierto: false,
                    tipo: null,
                    titulo: ''
                };
                this.sel = {};
                this.passNueva = null;
            },

            async resetPassword(u) {
                const res = await fetch(`{{ route('admin.usuarios.reset', '_ID_') }}`.replace('_ID_', u
                    .id_empleado), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                });
                const js = await res.json();
                if (res.ok) {
                    this.passNueva = js.password;
                    toastOk('Contraseña reseteada');
                } else {
                    toastErr('No se pudo resetear');
                }
            },

            // Método para actualizar fecha de nacimiento y calcular edad
            actualizarFechaNacimiento(fecha) {
                this.sel.fecha_nacimiento = fecha;
                this.calcularEdadDesdeFecha();
            },
            
            // Método para calcular edad desde fecha de nacimiento
            calcularEdadDesdeFecha() {
                if (!this.sel.fecha_nacimiento) {
                    this.mostrarErrorFecha('Por favor, selecciona una fecha de nacimiento');
                    this.sel.edad = '';
                    return;
                }
                
                const fechaNac = new Date(this.sel.fecha_nacimiento);
                const hoy = new Date();
                
                // Validar que la fecha no sea futura
                if (fechaNac > hoy) {
                    this.mostrarErrorFecha('La fecha de nacimiento no puede ser futura');
                    this.sel.edad = '';
                    return;
                }
                
                // Validar que la fecha sea válida
                if (isNaN(fechaNac.getTime())) {
                    this.mostrarErrorFecha('Fecha de nacimiento inválida');
                    this.sel.edad = '';
                    return;
                }
                
                // Calcular la edad
                let edad = hoy.getFullYear() - fechaNac.getFullYear();
                const mes = hoy.getMonth() - fechaNac.getMonth();
                const dia = hoy.getDate() - fechaNac.getDate();
                
                // Ajustar si aún no ha cumplido años este año
                if (mes < 0 || (mes === 0 && dia < 0)) {
                    edad--;
                }
                
                // Validar rango de edad razonable
                if (edad < 0) {
                    this.mostrarErrorFecha('La edad no puede ser negativa');
                    this.sel.edad = '';
                    return;
                }
                
                if (edad > 120) {
                    this.mostrarErrorFecha('La edad no puede ser mayor a 120 años');
                    this.sel.edad = '';
                    return;
                }
                
                // Actualizar la edad
                this.sel.edad = edad;
                
                // Mostrar mensaje de éxito
                this.mostrarInfoFecha(`Edad calculada: ${edad} años`);
            },
            
            // Método para mostrar información de fecha
            mostrarInfoFecha(mensaje) {
                const fechaInfo = document.getElementById('fechaInfoEdit');
                const edadInfo = document.getElementById('edadInfoEdit');
                
                if (fechaInfo) {
                    fechaInfo.textContent = mensaje;
                    fechaInfo.className = 'mt-1 text-xs text-emerald-600';
                }
                
                if (edadInfo) {
                    edadInfo.textContent = `Edad calculada automáticamente: ${this.sel.edad} años`;
                    edadInfo.className = 'mt-1 text-xs text-emerald-600';
                }
            },
            
            // Método para mostrar error en fecha
            mostrarErrorFecha(mensaje) {
                const fechaInfo = document.getElementById('fechaInfoEdit');
                const edadInfo = document.getElementById('edadInfoEdit');
                
                if (fechaInfo) {
                    fechaInfo.textContent = mensaje;
                    fechaInfo.className = 'mt-1 text-xs text-rose-600';
                }
                
                if (edadInfo) {
                    edadInfo.textContent = mensaje;
                    edadInfo.className = 'mt-1 text-xs text-rose-600';
                }
            },

            async guardarEdicion() {
                // Validación previa
                if (this.sel.password && this.sel.password.length < 8) {
                    toastErr('La contraseña debe tener al menos 8 caracteres');
                    return;
                }
                
                if (this.sel.password && this.sel.password !== this.sel.password_confirmation) {
                    toastErr('Las contraseñas no coinciden');
                    return;
                }

                // Validar que haya fecha de nacimiento y edad calculada
                if (!this.sel.fecha_nacimiento) {
                    toastErr('Por favor, ingresa la fecha de nacimiento');
                    return;
                }
                
                if (!this.sel.edad || this.sel.edad === '') {
                    toastErr('Por favor, calcula la edad desde la fecha de nacimiento');
                    return;
                }

                // Prepara los datos para enviar
                const datos = {
                    nombre: this.sel.nombre,
                    apellido: this.sel.apellido,
                    edad: this.sel.edad,
                    fecha_nacimiento: this.sel.fecha_nacimiento,
                    dni: this.sel.dni,
                    sexo: this.sel.sexo,
                    direccion: this.sel.direccion,
                    telefono: this.sel.telefono,
                    email: this.sel.email,
                    role: this.sel.role,
                    estado: this.sel.estado,
                };

                // Solo incluye la contraseña si se ha modificado/ingresado
                if (this.sel.password && this.sel.password.trim() !== '') {
                    datos.password = this.sel.password;
                    datos.password_confirmation = this.sel.password_confirmation;
                }

                try {
                    const res = await fetch(`{{ route('admin.usuarios.update', '_ID_') }}`.replace('_ID_', this.sel.id_empleado), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(datos),
                    });

                    const responseData = await res.json();
                    
                    if (res.ok) {
                        this.cerrarModal();
                        const ms = window.toastOkDelay('Usuario actualizado correctamente');
                        setTimeout(() => location.reload(), ms);
                    } else {
                        // Manejo de errores de validación
                        let errorMsg = 'Error al guardar';
                        if (responseData.errors) {
                            // Si hay errores de validación, mostrar el primero
                            const firstError = Object.values(responseData.errors)[0];
                            errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                        } else if (responseData.message) {
                            errorMsg = responseData.message;
                        }
                        toastErr(errorMsg);
                    }
                } catch (error) {
                    console.error('Error en guardarEdicion:', error);
                    toastErr('Error de conexión al guardar');
                }
            },

            async guardarQuick() {
                const payload = {
                    telefono: this.sel.telefono ?? null,
                    email: this.sel.email ?? null,
                    role: this.sel.role ?? null,
                    estado: this.sel.estado ?? null,
                };
                const res = await fetch(`{{ route('admin.usuarios.quick', '_ID_') }}`.replace('_ID_', this.sel
                    .id_empleado), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload),
                });
                if (res.ok) {
                    this.cerrarModal();
                    const ms = window.toastOkDelay('Cambios aplicados');
                    setTimeout(() => location.reload(), ms);
                } else {
                    toastErr('Error al aplicar actualización rápida');
                }
            },

            async quick(u, payload) {
                const res = await fetch(`{{ route('admin.usuarios.quick', '_ID_') }}`.replace('_ID_', u
                    .id_empleado), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload),
                });
                if (res.ok) toastOk('Cambios guardados');
                else toastErr('Error al actualizar');
            },

            async toggleEstado(u) {
                const res = await fetch(`{{ route('admin.usuarios.toggle', '_ID_') }}`.replace('_ID_', u
                    .id_empleado), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                });
                const js = await res.json();
                if (res.ok) {
                    u.estado = js.estado;
                    toastOk('Estado actualizado');
                } else {
                    toastErr('No se pudo cambiar el estado');
                }
            },

            // Método para formatear fecha para mostrar
            formatearFecha(fechaString) {
                if (!fechaString) return '';
                
                try {
                    // Si ya viene en formato YYYY-MM-DD
                    if (typeof fechaString === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(fechaString)) {
                        const [año, mes, dia] = fechaString.split('-');
                        return `${dia}/${mes}/${año}`;
                    }
                    
                    // Si viene como DD/MM/YYYY
                    const partes = fechaString.split('/');
                    if (partes.length === 3) {
                        return fechaString;
                    }
                    
                    // Intenta con Date
                    const fecha = new Date(fechaString);
                    if (!isNaN(fecha.getTime())) {
                        const dia = fecha.getDate().toString().padStart(2, '0');
                        const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
                        const año = fecha.getFullYear();
                        return `${dia}/${mes}/${año}`;
                    }
                    
                    return fechaString;
                } catch (e) {
                    console.error('Error formateando fecha:', e);
                    return fechaString;
                }
            },
            
            // Método para formatear fecha para input type="date"
            formatearFechaParaInput(fechaString) {
                if (!fechaString) return '';
                
                try {
                    // Si ya viene en formato YYYY-MM-DD
                    if (typeof fechaString === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(fechaString)) {
                        return fechaString;
                    }
                    
                    // Si viene como DD/MM/YYYY
                    const partes = fechaString.split('/');
                    if (partes.length === 3) {
                        return `${partes[2]}-${partes[1]}-${partes[0]}`;
                    }
                    
                    // Intenta con Date
                    const fecha = new Date(fechaString);
                    if (!isNaN(fecha.getTime())) {
                        const año = fecha.getFullYear();
                        const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
                        const dia = fecha.getDate().toString().padStart(2, '0');
                        return `${año}-${mes}-${dia}`;
                    }
                    
                    return '';
                } catch (e) {
                    console.error('Error convirtiendo fecha para input:', e);
                    return '';
                }
            },

            generarPassword() {
                // Genera una contraseña más segura y legible
                const caracteres = {
                    mayusculas: 'ABCDEFGHJKLMNPQRSTUVWXYZ',
                    minusculas: 'abcdefghijkmnopqrstuvwxyz',
                    numeros: '23456789',
                    simbolos: '!@#$%^&*'
                };
                
                let password = '';
                
                // Asegura al menos un carácter de cada tipo
                password += caracteres.mayusculas[Math.floor(Math.random() * caracteres.mayusculas.length)];
                password += caracteres.minusculas[Math.floor(Math.random() * caracteres.minusculas.length)];
                password += caracteres.numeros[Math.floor(Math.random() * caracteres.numeros.length)];
                password += caracteres.simbolos[Math.floor(Math.random() * caracteres.simbolos.length)];
                
                // Completa hasta 12 caracteres
                const todosCaracteres = caracteres.mayusculas + caracteres.minusculas + caracteres.numeros + caracteres.simbolos;
                for (let i = password.length; i < 12; i++) {
                    password += todosCaracteres[Math.floor(Math.random() * todosCaracteres.length)];
                }
                
                // Mezcla los caracteres
                password = password.split('').sort(() => Math.random() - 0.5).join('');
                
                this.sel.password = password;
                this.sel.password_confirmation = password;
                this.mostrarPass = true;
                
                // Muestra un toast de confirmación
                toastOk('Contraseña generada correctamente');
            },

            abrirActualizar(u) {
                this.$store.usr.abrirActualizar(u.id_empleado);
            },
            ver(u) {
                this.$store.usr.ver(u.id_empleado);
            },
            editar(u) {
                this.$store.usr.editar(u.id_empleado);
            },
            eliminar(u) {
                this.$store.usr.eliminar(u.id_empleado);
            },
        }
    }
    </script>

    {{-- Toast helpers --}}
    <script>
    window.TOAST_DURATION_MS = 2000;

    (function() {
        const D = () => (Number(window.TOAST_DURATION_MS) || 2000);

        function swalToast(icon, title) {
            if (!(window.Swal && Swal.mixin)) return 0;
            const t = Swal.mixin({
                toast: true,
                position: 'top-end',
                timer: D(),
                timerProgressBar: true,
                showConfirmButton: false,
                didOpen: (el) => {
                    el.addEventListener('mouseenter', Swal.stopTimer);
                    el.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            t.fire({
                icon,
                title
            });
            return D();
        }

        function run(fnName, msg, icon) {
            if (typeof window[fnName] === 'function') {
                try {
                    window[fnName](msg);
                } catch (e) {}
                return D();
            }
            return swalToast(icon, msg);
        }

        window.toastOkDelay = (msg) => run('toastOk', msg, 'success');
        window.toastWarnDelay = (msg) => run('toastWarn', msg, 'warning');
        window.toastErrDelay = (msg) => run('toastErr', msg, 'error');

        window.toastOkSimple = (msg) => run('toastOk', msg, 'success');
        window.toastWarnSimple = (msg) => run('toastWarn', msg, 'warning');
        window.toastErrSimple = (msg) => run('toastErr', msg, 'error');
    })();
    </script>

    {{-- Estilos adicionales --}}
    <style>
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            scrollbar-gutter: stable;
        }
        
        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
            margin: 0 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            transition: background 0.3s ease;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Bordes redondeados para la tabla */
        table {
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        /* Bordes redondeados para las esquinas */
        thead tr:first-child th:first-child {
            border-top-left-radius: 0.75rem;
        }
        
        thead tr:first-child th:last-child {
            border-top-right-radius: 0.75rem;
        }
        
        /* Transiciones suaves */
        tr, button, input, select, a {
            transition: all 0.2s ease;
        }
        
        /* Altura compacta para filas */
        tbody tr {
            height: 2.75rem;
        }
        
        /* Estados de hover */
        tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.8);
        }
        
        /* Estilos para los badges */
        span.bg-indigo-50, 
        span.bg-slate-100,
        span.bg-emerald-50,
        span.bg-rose-50,
        span.bg-purple-50,
        span.bg-blue-50,
        span.bg-cyan-50,
        span.bg-amber-50 {
            transition: all 0.2s ease;
        }
    </style>
</x-app-layout>