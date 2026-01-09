<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Asignar/Registrar Visita — Paciente 
        </h2>
    </x-slot>

    @php
        // Defaults UI
        $ahora = now()->format('Y-m-d\TH:i'); // datetime-local format
    @endphp

    @php
        $tabDefault = request('tab') ?: (request()->has('query') ? 'existente' : 'sin');
    @endphp

    <div x-data="{
        tab: @js($tabDefault),
        // selección
        seleccionado: null,
        seleccionadoNombre: '',
        setSeleccionado(id, nombre) { this.seleccionado = id;
            this.seleccionadoNombre = nombre;
            this.tab = 'existente'; },
    
        // búsqueda AJAX
        q: @js(request('query', '')),
        cargando: false,
        res: [],
        meta: { current_page: 1, last_page: 1, total: 0 },
        async buscarAjax(page = 1) {
            this.cargando = true;
            try {
                const url = new URL(`{{ route('recepcion.pacientes.buscarEncargados') }}`, window.location.origin);
                url.searchParams.set('paciente_id', '{{ $paciente->id_paciente }}');
                if (this.q) url.searchParams.set('query', this.q);
                url.searchParams.set('page', page);
                const r = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const js = await r.json();
                this.res = js.data;
                this.meta = js.meta;
                this.tab = 'existente';
                // Mantén URL limpia/compartible sin recargar
                const qs = new URLSearchParams({ paciente_id: '{{ $paciente->id_paciente }}', query: this.q || '', tab: 'existente' });
                history.replaceState(null, '', `?${qs.toString()}`);
            } finally {
                this.cargando = false;
            }
        },
        
        // Cálculo automático de edad mejorado
        calcularEdadDesdeFecha() {
            const fechaInput = document.getElementById('encargado_fecha_nacimiento');
            const edadHiddenInput = document.getElementById('encargado_edad');
            const edadDisplayInput = document.getElementById('encargado_edad_display');
            const edadInfo = document.getElementById('edad-info');
            
            if (!fechaInput || !fechaInput.value) {
                if (edadHiddenInput) edadHiddenInput.value = '';
                if (edadDisplayInput) edadDisplayInput.value = '';
                if (edadInfo) {
                    edadInfo.textContent = 'Seleccione una fecha de nacimiento';
                    edadInfo.className = 'mt-1 text-xs text-amber-600';
                }
                return;
            }
            
            // Verificar que la fecha sea válida (formato completo YYYY-MM-DD)
            const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
            if (!fechaRegex.test(fechaInput.value)) {
                // Fecha incompleta, no calcular todavía
                if (edadInfo) {
                    edadInfo.textContent = 'Complete la fecha para calcular la edad';
                    edadInfo.className = 'mt-1 text-xs text-amber-600';
                }
                return;
            }
            
            const fechaNac = new Date(fechaInput.value);
            const hoy = new Date();
            
            // Verificar que la fecha sea válida (no NaN)
            if (isNaN(fechaNac.getTime())) {
                if (edadInfo) {
                    edadInfo.textContent = 'Fecha inválida';
                    edadInfo.className = 'mt-1 text-xs text-rose-600';
                }
                return;
            }
            
            // Validar que la fecha no sea futura
            if (fechaNac > hoy) {
                if (edadInfo) {
                    edadInfo.textContent = 'La fecha de nacimiento no puede ser futura';
                    edadInfo.className = 'mt-1 text-xs text-rose-600';
                }
                if (edadHiddenInput) edadHiddenInput.value = '';
                if (edadDisplayInput) edadDisplayInput.value = '';
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
                    edadInfo.className = 'mt-1 text-xs text-rose-600';
                }
                if (edadHiddenInput) edadHiddenInput.value = '';
                if (edadDisplayInput) edadDisplayInput.value = '';
                return;
            }
            
            // Asignar la edad calculada
            if (edadHiddenInput) edadHiddenInput.value = edad;
            if (edadDisplayInput) edadDisplayInput.value = `${edad} años`;
            if (edadInfo) {
                edadInfo.textContent = `Edad calculada: ${edad} años`;
                edadInfo.className = 'mt-1 text-xs text-emerald-600';
            }
            
            console.log('Edad calculada y asignada:', edad);
        }
    }" x-init="// si viene query en URL, dispara búsqueda al cargar
    @if (request()->has('query')) buscarAjax(); @endif
    // Inicializar cálculo de edad si hay fecha pre-cargada
    @if(old('encargado_fecha_nacimiento'))
        setTimeout(() => calcularEdadDesdeFecha(), 100);
    @endif" class="max-w-7xl mx-auto space-y-5">

        {{-- Tarjeta: Datos del Paciente --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200">
            <div class="p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    🧍 Datos del Paciente
                </h3>
                <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
                    <div>
                        <div class="text-slate-500">Codigo del Paciente:</div>
                        <div class="font-medium">
                            {{ $paciente->codigo_paciente }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500">Nombre</div>
                        <div class="font-medium">
                            {{ $paciente->persona->nombre }} {{ $paciente->persona->apellido }}
                        </div>
                    </div>
                    <div>
                        <div class="text-slate-500">DNI</div>
                        <div class="font-medium">{{ $paciente->persona->dni }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Edad</div>
                        <div class="font-medium">{{ $paciente->persona->edad }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Fecha de Nacimiento</div>
                        <div class="font-medium">{{ $paciente->persona->fecha_nacimiento->format('d-m-Y') }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Teléfono</div>
                        <div class="font-medium">{{ $paciente->persona->telefono }}</div>
                    </div>

                    <div>
                        <div class="text-slate-500">Direccion</div>
                        <div class="font-medium">{{ $paciente->persona->direccion }}</div>
                    </div>
                </div>
            </div>
            <div class="border-t p-4 bg-slate-50 text-xs text-slate-600">
                Codigo del paciente: <span class="font-mono">{{ $paciente->codigo_paciente }}</span>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200">
            <div class="px-4 pt-4 md:px-6">
                <div class="flex flex-wrap gap-2">
                    <button @click="tab='sin'"
                        :class="tab === 'sin' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition">
                        Sin encargado
                    </button>
                    <button @click="tab='existente'"
                        :class="tab === 'existente' ? 'bg-indigo-600 text-white' :
                            'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition">
                        Encargado existente
                    </button>
                    <button @click="tab='nuevo'"
                        :class="tab === 'nuevo' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition">
                        Nuevo encargado
                    </button>
                </div>
            </div>

            {{-- Contenido de tabs --}}
            <div class="p-4 md:p-6 space-y-6">

                {{-- TAB: Sin encargado --}}
                <div x-show="tab === 'sin'" x-cloak>
                    <form method="POST" action="{{ route('recepcion.pacientes.guardarRelacion') }}"
                        class="grid md:grid-cols-3 gap-4">
                        @csrf
                        <input type="hidden" name="paciente_id" value="{{ $paciente->id_paciente }}">
                        {{-- encargado_id NULL (no se envía) --}}

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Fecha y hora de la visita</label>
                            <input type="datetime-local" name="fecha_visita" value="{{ old('fecha_visita', $ahora) }}"
                                class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tipo de consulta</label>
                            <select name="tipo_consulta"
                                class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Seleccione…</option>
                                <option value="general" @selected(old('tipo_consulta') === 'general')>General</option>
                                <option value="especializada" @selected(old('tipo_consulta') === 'especializada')>
                                    Especializada</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full md:w-auto px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                                Registrar sin encargado
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TAB: Encargado existente --}}
                <div x-show="tab === 'existente'" x-cloak id="tab-existente">
                    {{-- Form de búsqueda sin recarga --}}
                    <form @submit.prevent="buscarAjax()" class="flex flex-col md:flex-row gap-3 md:items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700">Buscar encargado (DNI, nombre o
                                apellido)</label>
                            <input type="text" x-model="q" placeholder="Ej: 0801..., Juan, Pérez..."
                                class="mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-4 py-2 rounded-xl bg-slate-800 text-white font-semibold hover:bg-slate-900">
                                <span x-show="!cargando">Buscar</span>
                                <span x-show="cargando">Buscando…</span>
                            </button>
                            <button type="button"
                                @click="q=''; res=[]; meta={current_page:1,last_page:1,total:0}; history.replaceState(null,'','?paciente_id={{ $paciente->id_paciente }}&tab=existente')"
                                class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200">
                                Limpiar
                            </button>
                        </div>
                    </form>

                    {{-- Resultados AJAX --}}
                    <div class="mt-5 overflow-x-auto" x-show="res.length || cargando">
                        <table class="min-w-full text-sm border border-slate-200">
                            <thead class="bg-slate-50 text-slate-700">
                                <tr>
                                    <th class="px-3 py-2 text-left border">ID</th>
                                    <th class="px-3 py-2 text-left border">Nombre</th>
                                    <th class="px-3 py-2 text-left border">Apellido</th>
                                    <th class="px-3 py-2 text-left border">DNI</th>
                                    <th class="px-3 py-2 text-left border">Edad</th>
                                    <th class="px-3 py-2 text-left border">Teléfono</th>
                                    <th class="px-3 py-2 text-center border">Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="e in res" :key="e.id_encargado">
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2 border" x-text="e.id_encargado"></td>
                                        <td class="px-3 py-2 border" x-text="e.nombre"></td>
                                        <td class="px-3 py-2 border" x-text="e.apellido"></td>
                                        <td class="px-3 py-2 border" x-text="e.dni"></td>
                                        <td class="px-3 py-2 border" x-text="e.edad"></td>
                                        <td class="px-3 py-2 border" x-text="e.telefono"></td>
                                        <td class="px-3 py-2 border text-center">
                                            <button type="button"
                                                @click="setSeleccionado(e.id_encargado, `${e.nombre} ${e.apellido}`)"
                                                class="px-3 py-1.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 border border-indigo-300">
                                                Usar
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="!res.length && !cargando">
                                    <td colspan="7" class="px-3 py-3 text-center text-slate-500">Sin resultados
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Paginación AJAX --}}
                        <div class="mt-3 flex items-center justify-between text-xs text-slate-600">
                            <div>Total: <span x-text="meta.total"></span></div>
                            <div class="flex gap-2">
                                <button class="px-2 py-1 rounded bg-slate-100" :disabled="meta.current_page <= 1"
                                    @click="buscarAjax(meta.current_page-1)">
                                    ← Anterior
                                </button>
                                <span>Página <span x-text="meta.current_page"></span>/<span
                                        x-text="meta.last_page"></span></span>
                                <button class="px-2 py-1 rounded bg-slate-100"
                                    :disabled="meta.current_page >= meta.last_page"
                                    @click="buscarAjax(meta.current_page+1)">
                                    Siguiente →
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Confirmar visita con encargado seleccionado --}}
                    <div class="mt-6 p-4 rounded-xl border"
                        :class="seleccionado ? 'border-emerald-300 bg-emerald-50/40' : 'border-slate-200 bg-slate-50'">
                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <div class="text-slate-500">Encargado seleccionado</div>
                                <div class="font-medium"
                                    x-text="seleccionado ? (`#${seleccionado} — `+seleccionadoNombre) : 'Ninguno'">
                                </div>
                            </div>
                            <div class="text-xs">
                                <button type="button" @click="seleccionado=null; seleccionadoNombre='';"
                                    class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200">Quitar</button>
                            </div>
                        </div>

                        <form x-show="seleccionado" x-cloak method="POST"
                            action="{{ route('recepcion.pacientes.guardarRelacion') }}"
                            class="mt-4 grid md:grid-cols-3 gap-3">
                            @csrf
                            <input type="hidden" name="paciente_id" value="{{ $paciente->id_paciente }}">
                            <input type="hidden" name="encargado_id" :value="seleccionado">

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Fecha y hora de la
                                    visita</label>
                                <input type="datetime-local" name="fecha_visita"
                                    value="{{ now()->format('Y-m-d\TH:i') }}"
                                    class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Tipo de consulta</label>
                                <select name="tipo_consulta"
                                    class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                    <option value="">Seleccione…</option>
                                    <option value="general">General</option>
                                    <option value="especializada">Especializada</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full md:w-auto px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                                    Registrar visita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TAB: Nuevo encargado --}}
                <div x-show="tab === 'nuevo'" x-cloak>
                    <form method="POST" action="{{ route('recepcion.pacientes.crearEncargadoYRelacion') }}"
                        class="grid lg:grid-cols-2 gap-6" id="form-nuevo-encargado">
                        @csrf
                        <input type="hidden" name="paciente_id" value="{{ $paciente->id_paciente }}">

                        <div class="space-y-4">
                            <h4 class="text-base font-semibold text-slate-900">Datos del Encargado</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                {{-- Nombre --}}
                                <div>
                                    <label class="text-sm text-slate-700">Nombre *</label>
                                    <input type="text" name="encargado_nombre"
                                        value="{{ old('encargado_nombre') }}"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                </div>

                                {{-- Apellido --}}
                                <div>
                                    <label class="text-sm text-slate-700">Apellido *</label>
                                    <input type="text" name="encargado_apellido"
                                        value="{{ old('encargado_apellido') }}"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                </div>

                                {{-- DNI --}}
                                <div>
                                    <label class="text-sm text-slate-700">DNI</label>
                                    <input type="text" name="encargado_dni" value="{{ old('encargado_dni') }}"
                                        maxlength="13" inputmode="numeric"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                {{-- Fecha de Nacimiento --}}
                                <div>
                                    <label class="text-sm text-slate-700">Fecha de Nacimiento *</label>
                                    <input type="date" name="encargado_fecha_nacimiento" id="encargado_fecha_nacimiento"
                                        value="{{ old('encargado_fecha_nacimiento') }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                        @blur="calcularEdadDesdeFecha()">
                                    <p class="mt-1 text-xs text-slate-500">Complete la fecha y haga clic fuera del campo para calcular la edad</p>
                                </div>

                                {{-- Edad oculta para enviar al servidor --}}
                                <input type="hidden" name="encargado_edad" id="encargado_edad" value="{{ old('encargado_edad') }}">
                                
                                {{-- Edad para visualización --}}
                                <div>
                                    <label class="text-sm text-slate-700">Edad</label>
                                    <div class="relative">
                                        <input type="text" id="encargado_edad_display"
                                            class="mt-1 w-full rounded-lg border-slate-300 bg-slate-50 text-slate-600 focus:border-indigo-500 focus:ring-indigo-500"
                                            readonly disabled>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                                Calculado
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500" id="edad-info">
                                        Complete la fecha de nacimiento para calcular la edad
                                    </p>
                                </div>

                                {{-- Sexo --}}
                                <div>
                                    <label class="text-sm text-slate-700">Sexo *</label>
                                    <select name="encargado_sexo"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                        <option value="">Seleccione…</option>
                                        <option value="M" @selected(old('encargado_sexo') === 'M')>Masculino</option>
                                        <option value="F" @selected(old('encargado_sexo') === 'F')>Femenino</option>
                                    </select>
                                </div>

                                {{-- Teléfono --}}
                                <div>
                                    <label class="text-sm text-slate-700">Teléfono</label>
                                    <input type="text" name="encargado_telefono"
                                        value="{{ old('encargado_telefono') }}"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        maxlength="15">
                                </div>

                                {{-- Dirección --}}
                                <div class="md:col-span-2">
                                    <label class="text-sm text-slate-700">Dirección</label>
                                    <input type="text" name="encargado_direccion"
                                        value="{{ old('encargado_direccion') }}"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-base font-semibold text-slate-900">Datos de la Visita</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                {{-- Fecha y hora de visita --}}
                                <div>
                                    <label class="text-sm text-slate-700">Fecha y hora *</label>
                                    <input type="datetime-local" name="fecha_visita"
                                        value="{{ old('fecha_visita', $ahora) }}"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                </div>

                                {{-- Tipo de consulta --}}
                                <div>
                                    <label class="text-sm text-slate-700">Tipo de consulta *</label>
                                    <select name="tipo_consulta"
                                        class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                        <option value="">Seleccione…</option>
                                        <option value="general" @selected(old('tipo_consulta') === 'general')>General</option>
                                        <option value="especializada" @selected(old('tipo_consulta') === 'especializada')>Especializada</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Mensaje de advertencia --}}
                            <div class="rounded-lg bg-amber-50 border border-amber-200 p-3 hidden" id="edad-warning">
                                <div class="flex items-center gap-2 text-amber-700 text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Por favor, complete la fecha de nacimiento para calcular la edad.</span>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="pt-2 flex gap-2">
                                <a href="{{ route('recepcion.verPacientes') }}"
                                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200">
                                    Cancelar
                                </a>
                                <button type="submit" id="btn-crear-encargado"
                                    class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-colors">
                                    Crear encargado + Registrar visita
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Acciones inferiores --}}
        <div class="flex justify-between">
            <a href="{{ route('recepcion.verPacientes') }}"
                class="px-4 py-2 rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200">
                ← Volver al listado
            </a>
        </div>
    </div>

    {{-- Script para mejorar la validación y UX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-nuevo-encargado');
            const btnCrear = document.getElementById('btn-crear-encargado');
            const fechaNacInput = document.getElementById('encargado_fecha_nacimiento');
            const edadHiddenInput = document.getElementById('encargado_edad');
            const edadDisplayInput = document.getElementById('encargado_edad_display');
            const edadInfo = document.getElementById('edad-info');
            const edadWarning = document.getElementById('edad-warning');
            
            if (!form) return;
            
            // Variable para rastrear si ya se verificó la fecha
            let fechaValidada = false;
            
            // Cálculo preciso de edad (versión mejorada)
            function calcularEdadPrecisa(fechaNacimiento) {
                if (!fechaNacimiento) return null;
                
                // Verificar que la fecha tenga el formato completo YYYY-MM-DD
                const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
                if (!fechaRegex.test(fechaNacimiento)) {
                    return null; // Fecha incompleta
                }
                
                const birthDate = new Date(fechaNacimiento);
                const today = new Date();
                
                // Verificar que la fecha sea válida (no NaN)
                if (isNaN(birthDate.getTime())) {
                    return null;
                }
                
                // Validar que la fecha no sea futura
                if (birthDate > today) {
                    return {
                        error: 'La fecha de nacimiento no puede ser futura'
                    };
                }
                
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                const dayDiff = today.getDate() - birthDate.getDate();
                
                // Ajustar si aún no ha cumplido años este año
                if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                    age--;
                }
                
                // Validar rango de edad razonable
                if (age < 0 || age > 120) {
                    return {
                        error: 'La edad calculada está fuera del rango permitido (0-120 años)'
                    };
                }
                
                return age;
            }
            
            // Configurar cálculo de edad con evento blur (cuando el usuario sale del campo)
            if (fechaNacInput && edadHiddenInput && edadDisplayInput) {
                // Usar blur en lugar de change para evitar cálculo mientras se escribe
                fechaNacInput.addEventListener('blur', function() {
                    const fecha = this.value;
                    
                    if (!fecha) {
                        edadHiddenInput.value = '';
                        edadDisplayInput.value = '';
                        if (edadInfo) {
                            edadInfo.textContent = 'Complete la fecha de nacimiento';
                            edadInfo.className = 'mt-1 text-xs text-amber-600';
                        }
                        fechaValidada = false;
                        return;
                    }
                    
                    const resultado = calcularEdadPrecisa(fecha);
                    
                    if (resultado === null) {
                        // Fecha incompleta o inválida
                        edadHiddenInput.value = '';
                        edadDisplayInput.value = '';
                        if (edadInfo) {
                            edadInfo.textContent = 'Fecha incompleta o inválida. Use formato DD/MM/AAAA';
                            edadInfo.className = 'mt-1 text-xs text-amber-600';
                        }
                        fechaValidada = false;
                    } else if (typeof resultado === 'object' && resultado.error) {
                        // Error en el cálculo
                        edadHiddenInput.value = '';
                        edadDisplayInput.value = '';
                        if (edadInfo) {
                            edadInfo.textContent = resultado.error;
                            edadInfo.className = 'mt-1 text-xs text-rose-600';
                        }
                        fechaValidada = false;
                        
                        // Solo mostrar alerta si es un error crítico (fecha futura)
                        if (resultado.error.includes('futura')) {
                            setTimeout(() => {
                                alert(resultado.error);
                                fechaNacInput.focus();
                            }, 100);
                        }
                    } else {
                        // Cálculo exitoso
                        edadHiddenInput.value = resultado;
                        edadDisplayInput.value = `${resultado} años`;
                        if (edadInfo) {
                            edadInfo.textContent = `Edad calculada: ${resultado} años`;
                            edadInfo.className = 'mt-1 text-xs text-emerald-600';
                        }
                        fechaValidada = true;
                    }
                });
                
                // También calcular al cargar si hay fecha válida
                if (fechaNacInput.value) {
                    // Pequeño delay para asegurar que AlpineJS esté listo
                    setTimeout(() => {
                        fechaNacInput.dispatchEvent(new Event('blur'));
                    }, 300);
                }
                
                // Agregar evento input para dar feedback mientras se escribe (sin calcular)
                fechaNacInput.addEventListener('input', function() {
                    if (edadInfo && this.value) {
                        edadInfo.textContent = 'Complete la fecha para calcular la edad';
                        edadInfo.className = 'mt-1 text-xs text-amber-600';
                    }
                });
            }
            
            // Prevenir que el usuario escriba en el campo de edad display
            if (edadDisplayInput) {
                edadDisplayInput.addEventListener('keydown', function(e) {
                    e.preventDefault();
                });
                
                edadDisplayInput.addEventListener('click', function() {
                    if (fechaNacInput) {
                        fechaNacInput.focus();
                        if (window.innerWidth < 768) {
                            setTimeout(() => {
                                fechaNacInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }, 100);
                        }
                        alert('La edad se calcula automáticamente desde la fecha de nacimiento. Modifique la fecha para cambiar la edad.');
                    }
                });
            }
            
            // Validación antes de enviar el formulario
            if (form && btnCrear) {
                form.addEventListener('submit', function(e) {
                    // Verificar campos requeridos
                    const nombre = form.querySelector('input[name="encargado_nombre"]')?.value.trim();
                    const apellido = form.querySelector('input[name="encargado_apellido"]')?.value.trim();
                    const fechaNacimiento = form.querySelector('input[name="encargado_fecha_nacimiento"]')?.value;
                    const edad = form.querySelector('input[name="encargado_edad"]')?.value;
                    const sexo = form.querySelector('select[name="encargado_sexo"]')?.value;
                    const fechaVisita = form.querySelector('input[name="fecha_visita"]')?.value;
                    const tipoConsulta = form.querySelector('select[name="tipo_consulta"]')?.value;
                    
                    let errores = [];
                    
                    if (!nombre) errores.push('• Nombre del encargado es requerido');
                    if (!apellido) errores.push('• Apellido del encargado es requerido');
                    if (!fechaNacimiento) errores.push('• Fecha de nacimiento del encargado es requerida');
                    
                    // Forzar validación de fecha si no se ha hecho
                    if (fechaNacimiento && !fechaValidada) {
                        if (fechaNacInput) {
                            fechaNacInput.dispatchEvent(new Event('blur'));
                            // Esperar un momento para que se actualice
                            setTimeout(() => {
                                const edadRecalculada = form.querySelector('input[name="encargado_edad"]')?.value;
                                if (!edadRecalculada) {
                                    errores.push('• La fecha de nacimiento es inválida o incompleta');
                                }
                            }, 100);
                        }
                    }
                    
                    if (!edad) {
                        errores.push('• La edad no se pudo calcular. Verifique la fecha de nacimiento');
                    }
                    
                    if (!sexo) errores.push('• Sexo del encargado es requerido');
                    if (!fechaVisita) errores.push('• Fecha y hora de la visita es requerida');
                    if (!tipoConsulta) errores.push('• Tipo de consulta es requerido');
                    
                    // Validar DNI si está presente (13 dígitos)
                    const dni = form.querySelector('input[name="encargado_dni"]')?.value.trim();
                    if (dni && dni.length !== 13) {
                        errores.push('• DNI debe tener 13 dígitos');
                    }
                    
                    // Si hay errores, mostrar alerta y prevenir envío
                    if (errores.length > 0) {
                        e.preventDefault();
                        
                        // Mostrar warning de edad si aplica
                        if (fechaNacimiento && !edad) {
                            if (edadWarning) {
                                edadWarning.classList.remove('hidden');
                                edadWarning.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        }
                        
                        alert('Por favor complete los campos requeridos:\n\n' + errores.join('\n'));
                        return false;
                    }
                    
                    // Ocultar warning si todo está bien
                    if (edadWarning) {
                        edadWarning.classList.add('hidden');
                    }
                    
                    // Verificar que la edad se haya calculado correctamente
                    if (!edad) {
                        e.preventDefault();
                        alert('Error: La edad no se calculó correctamente. Por favor, complete correctamente la fecha de nacimiento.');
                        return false;
                    }
                    
                    // Deshabilitar botón para evitar doble envío
                    btnCrear.disabled = true;
                    btnCrear.innerHTML = `
                        <svg class="inline w-4 h-4 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Procesando...
                    `;
                    btnCrear.classList.add('opacity-70', 'cursor-not-allowed');
                    
                    console.log('Enviando formulario con edad calculada:', edad);
                    return true;
                });
            }
            
            // Mejorar experiencia en móviles
            if (window.innerWidth < 768) {
                // Ajustar campos de fecha para mejor UX en móviles
                const dateInputs = form?.querySelectorAll('input[type="date"], input[type="datetime-local"]');
                dateInputs?.forEach(input => {
                    input.addEventListener('touchstart', function() {
                        // Forzar mostrar el teclado en iOS para mejor UX
                        this.focus();
                    });
                });
                
                // Ajustar scroll en campos focus
                const formElements = form?.querySelectorAll('input, select, textarea');
                formElements?.forEach(element => {
                    element.addEventListener('focus', function() {
                        setTimeout(() => {
                            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                    });
                });
            }
        });
    </script>
</x-app-layout>