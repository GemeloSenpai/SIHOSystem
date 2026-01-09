<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Paciente
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-2xl shadow ring-1 ring-slate-200">

        {{-- Alerts bonitos --}}
        @if (session('success'))
            <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-rose-800">
                <strong>Revisa el formulario:</strong>
                <ul class="list-disc pl-5 mt-2 text-sm">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('recepcion.pacientes.actualizar', $paciente->id_paciente) }}"
            class="space-y-6" id="pacienteForm">
            @csrf
            @method('PUT')

            <div
                class="mb-8 p-5 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl shadow-sm">
                <div class="flex flex-col md:flex-row items-center justify-center gap-3 md:gap-4">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 bg-indigo-100 text-indigo-700 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-sm font-medium text-indigo-600 mb-1">PACIENTE SELECCIONADO</p>
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <span
                                class="inline-flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-indigo-100 shadow-xs">
                                <span class="text-slate-500 font-medium">CODIGO:</span>
                                <span
                                    class="text-indigo-700 font-bold">{{ old('id_paciente', $paciente->codigo_paciente) }}</span>
                            </span>
                            <span class="hidden sm:block text-slate-300">|</span>
                            <span class="text-lg font-bold text-slate-800">
                                Nombre: {{ old('nombre', $paciente->persona->nombre) }}
                                {{ old('apellido', $paciente->persona->apellido) }}
                            </span>
                        </div>
                        
                        @if ($paciente->persona->edad || $paciente->persona->dni)
                            <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-2">
                                @if ($paciente->persona->edad)
                                    <span
                                        class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 text-xs px-2 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Edad: {{ $paciente->persona->edad }} años
                                    </span>
                                @endif
                                @if ($paciente->persona->dni)
                                    <span
                                        class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 text-xs px-2 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                        DNI: {{ $paciente->persona->dni }}
                                    </span>
                                @endif
                                @if ($paciente->persona->fecha_nacimiento)
                                    <span
                                        class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-md" id="fechaNacDisplay">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Nac:
                                        {{ \Carbon\Carbon::parse($paciente->persona->fecha_nacimiento)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nombre *</label>
                    <input type="text" name="nombre" required
                        value="{{ old('nombre', $paciente->persona->nombre) }}"
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Apellido *</label>
                    <input type="text" name="apellido" required
                        value="{{ old('apellido', $paciente->persona->apellido) }}"
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">DNI *</label>
                    <input type="text" name="dni" required value="{{ old('dni', $paciente->persona->dni) }}"
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Edad *</label>
                    <div class="mt-1 flex items-center gap-2">
                        <input type="number" name="edad" id="edadInput" min="0" max="120" required
                            value="{{ old('edad', $paciente->persona->edad) }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        <button type="button" id="calcularEdadBtn" 
                                class="px-3 py-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
                            Calcular
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-slate-500" id="edadInfo">
                        La edad se calcula automáticamente desde la fecha de nacimiento
                    </p>
                </div>

                {{-- CAMPO AÑADIDO: Fecha de Nacimiento --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700">Fecha de Nacimiento *</label>
                    @php
                        $fechaNacimiento = old('fecha_nacimiento', $paciente->persona->fecha_nacimiento);
                        $fechaFormateada = $fechaNacimiento
                            ? \Carbon\Carbon::parse($fechaNacimiento)->format('Y-m-d')
                            : '';
                    @endphp
                    <input type="date" name="fecha_nacimiento" id="fechaNacimientoInput" 
                           value="{{ $fechaFormateada }}" required
                           max="{{ now()->format('Y-m-d') }}"
                           class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                    <p class="mt-1 text-xs text-slate-500" id="fechaInfo">
                        @if ($fechaNacimiento)
                            Fecha actual: {{ \Carbon\Carbon::parse($fechaNacimiento)->format('d/m/Y') }}
                        @else
                            Selecciona la fecha de nacimiento para calcular la edad
                        @endif
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Sexo *</label>
                    <select name="sexo" required
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        <option value="">Seleccione...</option>
                        <option value="M" @selected(old('sexo', $paciente->persona->sexo) === 'M')>Masculino</option>
                        <option value="F" @selected(old('sexo', $paciente->persona->sexo) === 'F')>Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Teléfono *</label>
                    <input type="text" name="telefono" required
                        value="{{ old('telefono', $paciente->persona->telefono) }}"
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Dirección *</label>
                <input type="text" name="direccion" required
                    value="{{ old('direccion', $paciente->persona->direccion) }}"
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('recepcion.verPacientes') }}"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Toasts con SweetAlert --}}
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    timer: 2200,
                    showConfirmButton: false,
                    icon: 'success',
                    title: '{{ session('success') }}',
                    customClass: {
                        popup: 'rounded-lg'
                    }
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const fechaNacimientoInput = document.getElementById('fechaNacimientoInput');
            const edadInput = document.getElementById('edadInput');
            const calcularEdadBtn = document.getElementById('calcularEdadBtn');
            const fechaInfo = document.getElementById('fechaInfo');
            const edadInfo = document.getElementById('edadInfo');
            const fechaNacDisplay = document.getElementById('fechaNacDisplay');
            const form = document.getElementById('pacienteForm');

            // Función para calcular edad precisa
            function calcularEdad(fechaNacimiento) {
                if (!fechaNacimiento) return null;
                
                const birthDate = new Date(fechaNacimiento);
                const today = new Date();
                
                // Validar que la fecha no sea futura
                if (birthDate > today) {
                    return { error: 'La fecha de nacimiento no puede ser futura' };
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
                    return { error: 'La edad calculada está fuera del rango permitido (0-120)' };
                }
                
                return age;
            }

            // Función para formatear fecha
            function formatearFecha(fechaStr) {
                const fecha = new Date(fechaStr);
                return fecha.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            // Función para actualizar información de fecha
            function actualizarInfoFecha(fecha) {
                if (fecha) {
                    fechaInfo.textContent = `Fecha seleccionada: ${formatearFecha(fecha)}`;
                    fechaInfo.className = 'mt-1 text-xs text-emerald-600';
                    
                    // Actualizar display en la cabecera si existe
                    if (fechaNacDisplay) {
                        fechaNacDisplay.innerHTML = `
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Nac: ${formatearFecha(fecha)}
                        `;
                    }
                } else {
                    fechaInfo.textContent = 'Selecciona la fecha de nacimiento para calcular la edad';
                    fechaInfo.className = 'mt-1 text-xs text-slate-500';
                }
            }

            // Función para calcular y mostrar edad
            function calcularYMostrarEdad() {
                const fecha = fechaNacimientoInput.value;
                
                if (!fecha) {
                    edadInfo.textContent = 'Primero selecciona una fecha de nacimiento';
                    edadInfo.className = 'mt-1 text-xs text-amber-600';
                    edadInput.value = '';
                    return;
                }
                
                const resultado = calcularEdad(fecha);
                
                if (typeof resultado === 'object' && resultado.error) {
                    edadInfo.textContent = resultado.error;
                    edadInfo.className = 'mt-1 text-xs text-rose-600';
                    edadInput.value = '';
                    
                    // Mostrar alerta suave
                    if (resultado.error.includes('futura')) {
                        fechaNacimientoInput.focus();
                    }
                } else {
                    edadInput.value = resultado;
                    edadInfo.textContent = `Edad calculada: ${resultado} años`;
                    edadInfo.className = 'mt-1 text-xs text-emerald-600';
                }
                
                actualizarInfoFecha(fecha);
            }

            // Event Listeners
            if (fechaNacimientoInput) {
                // Calcular al cambiar la fecha
                fechaNacimientoInput.addEventListener('change', calcularYMostrarEdad);
                
                // Calcular al cargar si hay fecha
                if (fechaNacimientoInput.value) {
                    calcularYMostrarEdad();
                }
            }

            if (calcularEdadBtn) {
                calcularEdadBtn.addEventListener('click', calcularYMostrarEdad);
            }

            // Validar antes de enviar el formulario
            if (form) {
                form.addEventListener('submit', function(e) {
                    const fecha = fechaNacimientoInput.value;
                    const edad = edadInput.value;
                    
                    // Validar que haya fecha de nacimiento
                    if (!fecha) {
                        e.preventDefault();
                        alert('Por favor, ingresa la fecha de nacimiento');
                        fechaNacimientoInput.focus();
                        return;
                    }
                    
                    // Validar que la edad calculada sea correcta
                    const edadCalculada = calcularEdad(fecha);
                    
                    if (typeof edadCalculada === 'object' && edadCalculada.error) {
                        e.preventDefault();
                        alert(`Error en la fecha de nacimiento: ${edadCalculada.error}`);
                        fechaNacimientoInput.focus();
                        return;
                    }
                    
                    // Si la edad manual difiere mucho de la calculada, pedir confirmación
                    if (edad && Math.abs(parseInt(edad) - edadCalculada) > 1) {
                        const confirmar = confirm(
                            `La edad ingresada (${edad}) difiere de la calculada (${edadCalculada}). ¿Deseas continuar?`
                        );
                        
                        if (!confirmar) {
                            e.preventDefault();
                            edadInput.focus();
                        }
                    }
                });
            }

            // Mantener validación de solo números para DNI y teléfono
            const dniInput = document.querySelector('input[name="dni"]');
            const telefonoInput = document.querySelector('input[name="telefono"]');

            function soloNumeros(e) {
                const input = e.target;
                input.value = input.value.replace(/\D/g, '');
            }

            if (dniInput) {
                dniInput.addEventListener('input', soloNumeros);
                dniInput.addEventListener('blur', soloNumeros);
            }

            if (telefonoInput) {
                telefonoInput.addEventListener('input', soloNumeros);
                telefonoInput.addEventListener('blur', soloNumeros);
            }

            // Prevenir fechas futuras (ya está en HTML con max, pero reforzamos)
            const hoy = new Date().toISOString().split('T')[0];
            if (fechaNacimientoInput) {
                fechaNacimientoInput.max = hoy;
            }
        });
    </script>
</x-app-layout>