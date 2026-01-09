<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Editar Expediente — {{ $expediente->codigo }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        {{-- Contenedor para mensajes AJAX --}}
        <div id="mensajes-flash"></div>

        {{-- Mensajes de sesión --}}
        @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
            ✅ {{ session('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3">
            <div class="font-semibold mb-1">Revisa los siguientes campos:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Acciones superiores --}}
        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('expedientes.index') }}"
                class="rounded-lg bg-slate-200 px-4 py-2 text-sm text-slate-800 hover:bg-slate-300">
                ← Volver a Gestión
            </a>
        </div>

        {{-- Resumen principal (RO) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm text-slate-600">Código</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->codigo }}" disabled>
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
                    <label class="text-sm text-slate-600">Fecha de Nacimiento</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->paciente->persona->fecha_nacimiento->format('d/m/Y')}}"
                        disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Direccion de Recidencia</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->paciente->persona->direccion}}"
                        disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Identificador de Consulta (ID)</label>
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
                        value="{{ $expediente->enfermera?->nombre ?? '—' }} {{ $expediente->enfermera?->apellido ?? '' }}" disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Doctor que lo atendio</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->doctor?->nombre ?? '—' }} {{ $expediente->doctor?->apellido ?? '' }}" disabled>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Signos vitales (últ. registro)</label>
                    <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-slate-50"
                        value="{{ $expediente->signosVitales ? \Carbon\Carbon::parse($expediente->signosVitales->fecha_registro)->format('d/m/Y H:i') : '—' }}" disabled>
                </div>
            </div>
        </div>

        {{-- Signos Vitales (RO) --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 my-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">❤️ Últimos Signos Vitales</h3>
            @if($expediente->signosVitales)
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
                    $pesoLb = is_numeric($pesoKg) ? round($pesoKg * 2.2046226218, 2) : null;
                @endphp
                <div><span class="text-xs text-slate-500">Peso (kg)</span>
                    <div class="font-semibold"> 
                        @if(!is_null($pesoLb))
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

        {{-- FORM: Consulta + Expediente (EDITABLES) --}}
        <form method="POST" action="{{ route('expedientes.update', $expediente->id_expediente) }}"
            class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6">
            @csrf
            @method('PUT')

            {{-- Datos de ingreso --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">📁 Datos de ingreso</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-600">Motivo de ingreso *</label>
                        <textarea name="motivo_ingreso" rows="3" required
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('motivo_ingreso', $expediente->motivo_ingreso) }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Diagnóstico *</label>
                        <textarea name="diagnostico" rows="3" required
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('diagnostico', $expediente->diagnostico) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-slate-600">Observaciones</label>
                        <textarea name="observaciones" rows="3"
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('observaciones', $expediente->observaciones) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Consulta (editable) --}}
            <div class="mt-6 border-t pt-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">📝 Consulta (editable)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-600">Resumen Clínico *</label>
                        <textarea name="resumen_clinico" rows="3" required
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('resumen_clinico', $expediente->consulta?->resumen_clinico) }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Impresión Diagnóstica *</label>
                        <textarea name="impresion_diagnostica" rows="3" required
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('impresion_diagnostica', $expediente->consulta?->impresion_diagnostica) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-slate-600">Indicaciones *</label>
                        <textarea name="indicaciones" rows="2" required
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('indicaciones', $expediente->consulta?->indicaciones) }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Urgencia *</label>
                        <select name="urgencia"
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            @php $urgOld = old('urgencia', $expediente->consulta?->urgencia); @endphp
                            <option value="">—</option>
                            <option value="si" @selected($urgOld==='si' )>Sí</option>
                            <option value="no" @selected($urgOld==='no' )>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-slate-600">Tipo de Urgencia *</label>
                        @php $tipoOld = old('tipo_urgencia', $expediente->consulta?->tipo_urgencia); @endphp
                        <select name="tipo_urgencia"
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">—</option>
                            <option value="medica" @selected($tipoOld==='medica' )>Médica</option>
                            <option value="pediatrica" @selected($tipoOld==='pediatrica' )>Pediátrica</option>
                            <option value="quirurgico" @selected($tipoOld==='quirurgico' )>Quirúrgica</option>
                            <option value="gineco obstetrica" @selected($tipoOld==='gineco obstetrica' )>Gineco Obstétrica</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Resultado *</label>
                        @php $resOld = old('resultado', $expediente->consulta?->resultado); @endphp
                        <select name="resultado"
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">—</option>
                            <option value="alta" @selected($resOld==='alta' )>Alta</option>
                            <option value="seguimiento" @selected($resOld==='seguimiento' )>Seguimiento</option>
                            <option value="referido" @selected($resOld==='referido' )>Referido</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Citado</label>
                        @php
                        $citadoVal = old('citado', $expediente->consulta?->citado);
                        try { $citadoVal = $citadoVal ? \Carbon\Carbon::parse($citadoVal)->format('Y-m-d') : ''; }
                        catch(\Throwable $e) { $citadoVal = ''; }
                        @endphp
                        <input type="date" name="citado" value="{{ $citadoVal }}"
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Firma/Sello *</label>
                        @php $firmaOld = old('firma_sello', $expediente->consulta?->firma_sello); @endphp
                        <select name="firma_sello"
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">—</option>
                            <option value="si" @selected($firmaOld==='si' )>Sí</option>
                            <option value="no" @selected($firmaOld==='no' )>No</option>
                        </select>
                    </div>

                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm text-slate-600">Estado *</label>
                                <select name="estado"
                                    class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    required>
                                    <option value="abierto" @selected(old('estado', $expediente->estado)==='abierto')>Abierto</option>
                                    <option value="cerrado" @selected(old('estado', $expediente->estado)==='cerrado')>Cerrado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('expedientes.index') }}"
                    class="rounded-lg bg-slate-200 px-4 py-2 text-sm hover:bg-slate-300">
                    Cancelar
                </a>
                <button type="submit"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                    Guardar cambios del expediente
                </button>
            </div>
        </form>

        {{-- ================================
            Exámenes (agregar / quitar con AJAX)
        ================================ --}}
        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-6 my-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">🔬 Exámenes del expediente</h3>
                @unless($expediente->consulta)
                <span class="text-sm text-rose-600">No hay consulta asociada. Crea/relaciona una consulta para asignar exámenes.</span>
                @endunless
            </div>

            {{-- AGREGAR examen --}}
            <div class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div class="md:col-span-4">
                        <label class="text-sm text-slate-600">Selecciona un examen</label>
                        <select id="examen_id"
                            class="mt-1 w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                            {{ $expediente->consulta ? '' : 'disabled' }}>
                            <option value="">— Selecciona un examen —</option>
                            @foreach ($catalogoExamenes as $exa)
                            <option value="{{ $exa->id_examen }}">
                                {{ $exa->nombre_examen }} — {{ $exa->categoria->nombre_categoria ?? 'Sin categoría' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1 flex items-end">
                        <button type="button" id="btn-agregar-examen" 
                            {{ $expediente->consulta ? '' : 'disabled' }}
                            class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            + Agregar
                        </button>
                    </div>
                </div>
            </div>

            {{-- LISTA de exámenes (se actualiza via AJAX) --}}
            <div id="lista-examenes-container">
                @include('partials.lista-examenes', ['examenes' => $expediente->consulta?->examenesMedicos ?? collect()])
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // ==================== AGREGAR EXAMEN (AJAX) ====================
    const btnAgregar = document.getElementById('btn-agregar-examen');
    const selectExamen = document.getElementById('examen_id');
    
    if (btnAgregar) {
        btnAgregar.addEventListener('click', async function() {
            if (!selectExamen.value) {
                mostrarMensaje('error', 'Por favor selecciona un examen');
                return;
            }
            
            // Mostrar loading
            btnAgregar.disabled = true;
            const originalText = btnAgregar.innerHTML;
            btnAgregar.innerHTML = '<span class="animate-pulse">Agregando...</span>';
            
            try {
                const response = await fetch('{{ route("expedientes.examenes.store", $expediente->id_expediente) }}', {
                    method: 'POST',
                    body: JSON.stringify({ examen_id: selectExamen.value }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Actualizar la lista de exámenes
                    await actualizarListaExamenes();
                    selectExamen.value = '';
                    mostrarMensaje('success', data.message || 'Examen agregado correctamente');
                } else {
                    mostrarMensaje('error', data.message || 'Error al agregar examen');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('error', 'Error en la conexión');
            } finally {
                btnAgregar.disabled = false;
                btnAgregar.innerHTML = originalText;
            }
        });
    }
    
    // ==================== ELIMINAR EXAMEN (AJAX) ====================
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.btn-quitar-examen')) {
            e.preventDefault();
            const button = e.target.closest('.btn-quitar-examen');
            const form = button.closest('form');
            const action = form.getAttribute('action');
            const mensaje = form.getAttribute('data-confirm') || '¿Quitar este examen del expediente?';
            
            if (!confirm(mensaje)) return;
            
            // Mostrar loading en el botón
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="animate-pulse">Eliminando...</span>';
            
            try {
                const response = await fetch(action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await actualizarListaExamenes();
                    mostrarMensaje('success', data.message || 'Examen eliminado correctamente');
                } else {
                    mostrarMensaje('error', data.message || 'Error al eliminar examen');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('error', 'Error en la conexión');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        }
    });
    
    // ==================== FUNCIONES AUXILIARES ====================
    async function actualizarListaExamenes() {
        try {
            const response = await fetch('{{ route("expedientes.examenes.lista", $expediente->id_expediente) }}');
            const html = await response.text();
            document.getElementById('lista-examenes-container').innerHTML = html;
        } catch (error) {
            console.error('Error al actualizar lista:', error);
        }
    }
    
    function mostrarMensaje(tipo, texto) {
        // Eliminar mensajes anteriores del mismo tipo
        const mensajesAnteriores = document.querySelectorAll(`.mensaje-ajax-${tipo}`);
        mensajesAnteriores.forEach(msg => msg.remove());
        
        // Crear nuevo mensaje
        const mensaje = document.createElement('div');
        mensaje.className = `mensaje-ajax-${tipo} mb-4 rounded-xl border px-4 py-3 ${
            tipo === 'success' 
                ? 'border-emerald-200 bg-emerald-50 text-emerald-800' 
                : 'border-rose-200 bg-rose-50 text-rose-800'
        }`;
        mensaje.innerHTML = `${tipo === 'success' ? '✅' : '❌'} ${texto}`;
        
        // Insertar después del contenedor de mensajes flash
        const contenedorFlash = document.getElementById('mensajes-flash') || document.querySelector('.max-w-6xl');
        contenedorFlash.parentNode.insertBefore(mensaje, contenedorFlash.nextSibling);
        
        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            if (mensaje.parentNode) {
                mensaje.remove();
            }
        }, 5000);
    }
});
    </script>
</x-app-layout>