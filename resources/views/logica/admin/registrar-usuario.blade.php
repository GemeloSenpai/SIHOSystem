<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Registrar Nuevo Usuario
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

    <div class="max-w-screen-2xl mx-auto px-4 py-4">
        {{-- Contenedor principal --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/50 p-5 mb-6">
            {{-- Encabezado --}}
            <div class="flex items-center gap-3 mb-6">
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Registrar Nuevo Empleado</h3>
                    <p class="text-sm text-slate-600">Complete todos los campos para registrar un nuevo usuario en el sistema</p>
                </div>
            </div>

            {{-- Mensajes de éxito --}}
            @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="mb-6 rounded-xl bg-emerald-50/80 border border-emerald-200 p-4 text-emerald-800">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            {{-- Mensajes de error --}}
            @if ($errors->any())
            <div class="mb-6 rounded-xl bg-rose-50/80 border border-rose-200 p-4 text-rose-800">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">Revisa los siguientes puntos:</span>
                </div>
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('admin.users.store') }}" id="formRegistrarUsuario">
                @csrf

                {{-- Campo hidden para la edad (para enviar al servidor) --}}
                <input type="hidden" name="edad" id="edadHidden" value="{{ old('edad') }}">

                {{-- Información Personal --}}
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Información Personal
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Nombre --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Nombre *</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('nombre') @enderror"
                                   required>
                            @error('nombre')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Apellido --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Apellido *</label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('apellido') @enderror"
                                   required>
                            @error('apellido')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Correo Electrónico *</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('email') @enderror"
                                   required>
                            @error('email')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rol --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Rol *</label>
                            <select name="role"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('role') @enderror"
                                    required>
                                <option value="">Seleccionar rol</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="medico" {{ old('role') == 'medico' ? 'selected' : '' }}>Médico</option>
                                <option value="enfermero" {{ old('role') == 'enfermero' ? 'selected' : '' }}>Enfermero</option>
                                <option value="recepcionista" {{ old('role') == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                                <option value="laboratorio" {{ old('role') == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                            </select>
                            @error('role')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fecha de Nacimiento con cálculo de edad --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Fecha de Nacimiento *</label>
                            <div class="relative">
                                <input type="date" name="fecha_nacimiento" id="fechaNacimiento" 
                                       value="{{ old('fecha_nacimiento') }}"
                                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('fecha_nacimiento') @enderror"
                                       max="{{ date('Y-m-d') }}"
                                       required>
                                <button type="button" 
                                        onclick="calcularEdadDesdeFecha()"
                                        class="absolute inset-y-0 right-0 flex items-center justify-center w-10 text-slate-500 hover:text-indigo-600 transition-colors"
                                        title="Calcular edad">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-slate-500" id="fechaInfo">
                                Selecciona la fecha para calcular la edad
                            </p>
                            @error('fecha_nacimiento')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Edad (calculada automáticamente) - SOLO VISUAL --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Edad *</label>
                            <input type="text" id="edadVisual" 
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 bg-slate-50"
                                   readonly disabled
                                   placeholder="Se calculará automáticamente">
                            <p class="mt-1 text-xs text-slate-500" id="edadInfo">
                                La edad se calcula automáticamente desde la fecha de nacimiento
                            </p>
                            @error('edad')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- DNI --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">DNI *</label>
                            <input type="text" name="dni" value="{{ old('dni') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('dni') @enderror"
                                   inputmode="numeric" autocomplete="off"
                                   maxlength="13" data-digits-only data-maxlen="13"
                                   placeholder="Solo números (máx. 13)" required>
                            @error('dni')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Sexo --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Sexo *</label>
                            <select name="sexo"
                                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('sexo') @enderror"
                                    required>
                                <option value="">Seleccionar sexo</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Contacto y Dirección --}}
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Contacto y Dirección
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Dirección --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Dirección *</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('direccion') @enderror"
                                   required>
                            @error('direccion')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Teléfono *</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}"
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('telefono') @enderror"
                                   inputmode="tel" autocomplete="off"
                                   maxlength="15" data-digits-only data-maxlen="15"
                                   placeholder="Solo números (máx. 15)" required>
                            @error('telefono')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Contraseñas --}}
                <div class="mb-8">
                    <h4 class="text-base font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Credenciales de Acceso
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Contraseña --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Contraseña *</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" autocomplete="new-password"
                                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 pr-10 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200 @error('password') @enderror"
                                       required>
                                <button type="button"
                                        class="absolute inset-y-0 right-0 my-auto h-full w-10 flex items-center justify-center text-slate-500 hover:text-slate-700 transition-colors"
                                        data-toggle="#password" aria-label="Mostrar u ocultar contraseña" aria-pressed="false">
                                    <svg data-eye-on class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                    <svg data-eye-off class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 3l18 18M4.5 5.5C2.4 7.3 1 9.5 1 12c0 0 4 7 11 7 2.3 0 4.3-.6 6-.1M9.9 9.9A3 3 0 0012 15a3 3 0 002.1-.9"
                                              stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Confirmar Contraseña *</label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       autocomplete="new-password" 
                                       class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all duration-200"
                                       required>
                                <button type="button"
                                        class="absolute inset-y-0 right-0 my-auto h-full w-10 flex items-center justify-center text-slate-500 hover:text-slate-700 transition-colors"
                                        data-toggle="#password_confirmation" aria-label="Mostrar u ocultar confirmación" aria-pressed="false">
                                    <svg data-eye-on class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                    <svg data-eye-off class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 3l18 18M4.5 5.5C2.4 7.3 1 9.5 1 12c0 0 4 7 11 7 2.3 0 4.3-.6 6-.1M9.9 9.9A3 3 0 0012 15a3 3 0 002.1-.9"
                                              stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">
                        La contraseña debe tener al menos 8 caracteres.
                    </p>
                </div>

                {{-- Botón de envío --}}
                <div class="pt-4 border-t border-slate-200">
                    <div class="flex justify-end">
                        <button id="btnGuardar" type="submit"
                                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 font-medium disabled:opacity-70 disabled:cursor-not-allowed">
                            <svg id="icoSave" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <svg id="icoSpin" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/>
                                <path d="M4 12a8 8 0 0 1 8-8" fill="currentColor" opacity=".7"/>
                            </svg>
                            <span>Registrar Usuario</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert para mejor feedback --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- Script para cálculo de edad --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaNacimientoInput = document.getElementById('fechaNacimiento');
            const edadHiddenInput = document.getElementById('edadHidden');
            const edadVisualInput = document.getElementById('edadVisual');
            const fechaInfo = document.getElementById('fechaInfo');
            const edadInfo = document.getElementById('edadInfo');
            const hoy = new Date().toISOString().split('T')[0];

            // Establecer la fecha máxima (hoy)
            if (fechaNacimientoInput) {
                fechaNacimientoInput.max = hoy;
            }

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
                if (age < 0) {
                    return { error: 'La edad no puede ser negativa' };
                }
                
                if (age > 120) {
                    return { error: 'La edad no puede ser mayor a 120 años' };
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
            function actualizarInfoFecha(fecha, edad) {
                if (fecha) {
                    fechaInfo.textContent = `Fecha seleccionada: ${formatearFecha(fecha)}`;
                    fechaInfo.className = 'mt-1 text-xs text-emerald-600';
                    
                    if (edad !== null && edad !== undefined) {
                        edadInfo.textContent = `Edad calculada: ${edad} años`;
                        edadInfo.className = 'mt-1 text-xs text-emerald-600';
                    }
                } else {
                    fechaInfo.textContent = 'Selecciona la fecha de nacimiento para calcular la edad';
                    fechaInfo.className = 'mt-1 text-xs text-slate-500';
                    edadInfo.textContent = 'La edad se calcula automáticamente desde la fecha de nacimiento';
                    edadInfo.className = 'mt-1 text-xs text-slate-500';
                }
            }

            // Función para calcular y mostrar edad
            function calcularYMostrarEdad() {
                const fecha = fechaNacimientoInput.value;
                
                if (!fecha) {
                    fechaInfo.textContent = 'Primero selecciona una fecha de nacimiento';
                    fechaInfo.className = 'mt-1 text-xs text-amber-600';
                    edadInfo.textContent = 'Selecciona una fecha para calcular la edad';
                    edadInfo.className = 'mt-1 text-xs text-amber-600';
                    edadVisualInput.value = '';
                    edadHiddenInput.value = '';
                    return;
                }
                
                const resultado = calcularEdad(fecha);
                
                if (typeof resultado === 'object' && resultado.error) {
                    fechaInfo.textContent = resultado.error;
                    fechaInfo.className = 'mt-1 text-xs text-rose-600';
                    edadInfo.textContent = resultado.error;
                    edadInfo.className = 'mt-1 text-xs text-rose-600';
                    edadVisualInput.value = '';
                    edadHiddenInput.value = '';
                    
                    // Mostrar alerta suave
                    if (resultado.error.includes('futura')) {
                        fechaNacimientoInput.focus();
                    }
                } else {
                    // Actualizar tanto el input visual como el hidden
                    edadVisualInput.value = `${resultado} años`;
                    edadHiddenInput.value = resultado;
                    actualizarInfoFecha(fecha, resultado);
                }
            }

            // Función global para calcular edad
            window.calcularEdadDesdeFecha = function() {
                calcularYMostrarEdad();
            };

            // Event Listeners
            if (fechaNacimientoInput) {
                // Calcular automáticamente al cambiar la fecha
                fechaNacimientoInput.addEventListener('change', calcularYMostrarEdad);
                
                // Calcular al cargar si hay fecha previamente seleccionada
                if (fechaNacimientoInput.value) {
                    setTimeout(calcularYMostrarEdad, 100);
                }
            }

            // Validar antes de enviar el formulario
            const form = document.getElementById('formRegistrarUsuario');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const fecha = fechaNacimientoInput.value;
                    const edad = edadHiddenInput.value;
                    
                    // Validar que haya fecha de nacimiento
                    if (!fecha) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fecha de nacimiento requerida',
                            text: 'Por favor, ingresa la fecha de nacimiento',
                            confirmButtonColor: '#4f46e5',
                        });
                        fechaNacimientoInput.focus();
                        return;
                    }
                    
                    // Validar que la edad calculada sea correcta
                    const edadCalculada = calcularEdad(fecha);
                    
                    if (typeof edadCalculada === 'object' && edadCalculada.error) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la fecha',
                            text: edadCalculada.error,
                            confirmButtonColor: '#4f46e5',
                        });
                        fechaNacimientoInput.focus();
                        return;
                    }
                    
                    // Si no hay edad calculada
                    if (!edad || edad === '') {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Edad no calculada',
                            text: 'Por favor, calcula la edad desde la fecha de nacimiento',
                            confirmButtonColor: '#4f46e5',
                        });
                        return;
                    }
                    
                    // Asegurarse de que el hidden input tenga el valor correcto
                    edadHiddenInput.value = edadCalculada;
                });
            }

            // Validación de DNI y teléfono para solo números
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

            // Si hay fecha en el campo old(), calcular automáticamente
            const fechaOld = "{{ old('fecha_nacimiento') }}";
            if (fechaOld && fechaNacimientoInput) {
                setTimeout(() => {
                    fechaNacimientoInput.value = fechaOld;
                    calcularYMostrarEdad();
                }, 200);
            }
            
            // Si hay edad en el campo old(), restaurarla
            const edadOld = "{{ old('edad') }}";
            if (edadOld && edadOld !== '') {
                edadHiddenInput.value = edadOld;
                edadVisualInput.value = `${edadOld} años`;
            }
        });
    </script>

    {{-- Envío: feedback de carga --}}
    <script>
    (function() {
        const form = document.getElementById('formRegistrarUsuario');
        const btn  = document.getElementById('btnGuardar');
        const save = document.getElementById('icoSave');
        const spin = document.getElementById('icoSpin');
        if (form && btn) {
            form.addEventListener('submit', () => {
                btn.disabled = true;
                btn.classList.add('opacity-70','cursor-not-allowed');
                save.classList.add('hidden');
                spin.classList.remove('hidden');
            });
        }
    })();
    </script>

    {{-- Mostrar/Ocultar contraseña --}}
    <script>
    (function() {
        const qs  = (s, c=document) => c.querySelector(s);
        const qsa = (s, c=document) => Array.from(c.querySelectorAll(s));

        qsa('button[data-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = qs(btn.getAttribute('data-toggle'));
                if (!input) return;
                const isPw = input.type === 'password';
                input.type  = isPw ? 'text' : 'password';

                const eyeOn  = btn.querySelector('[data-eye-on]');
                const eyeOff = btn.querySelector('[data-eye-off]');
                if (eyeOn && eyeOff) {
                    eyeOn.classList.toggle('hidden', !isPw);
                    eyeOff.classList.toggle('hidden',  isPw);
                }
                btn.setAttribute('aria-pressed', String(isPw));
                input.focus({ preventScroll:true });
                const v = input.value; input.value=''; input.value=v;
            });
        });
    })();
    </script>

    {{-- Reglas FRONTEND: DNI y Teléfono solo dígitos + longitudes máximas --}}
    <script>
    (function() {
        const onlyDigits = (v) => v.replace(/\D+/g, '');

        function enforce(el) {
            const max = parseInt(el.dataset.maxlen || el.getAttribute('maxlength') || '999', 10);
            const clean = onlyDigits(el.value).slice(0, max);
            if (el.value !== clean) el.value = clean;
        }

        // Delegado para inputs marcados con data-digits-only
        document.addEventListener('input', (e) => {
            const el = e.target;
            if (el && el.matches('input[data-digits-only]')) enforce(el);
        });

        // También al pegar
        document.addEventListener('paste', (e) => {
            const el = e.target;
            if (el && el.matches('input[data-digits-only]')) {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text');
                const max  = parseInt(el.dataset.maxlen || el.getAttribute('maxlength') || '999', 10);
                const clean = (text || '').replace(/\D+/g, '').slice(0, max);
                const start = el.selectionStart, end = el.selectionEnd;
                const before = el.value.slice(0, start);
                const after  = el.value.slice(end);
                el.value = (before + clean + after).slice(0, max);
            }
        });

        // Evita rueda en <input type="number"> que cambie valor sin querer
        document.addEventListener('wheel', (e) => {
            const el = e.target;
            if (el && el.matches('input[type="number"]') && el === document.activeElement) {
                el.blur();
            }
        });

        // Trim rápido antes de enviar
        const form = document.getElementById('formRegistrarUsuario');
        if (form) {
            form.addEventListener('submit', () => {
                form.querySelectorAll('input[type="text"], input[type="email"]').forEach(i => {
                    i.value = i.value.trim();
                });
            });
        }
    })();
    </script>

    {{-- Estilos adicionales --}}
    <style>
        /* Estilos para inputs y selects */
        input, select {
            transition: all 0.2s ease;
        }
        
        /* Placeholder más claro */
        input::placeholder {
            color: #94a3b8;
            font-size: 0.875rem;
        }
        
        /* Efecto de focus mejorado */
        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        /* Estilo para botón de mostrar/ocultar contraseña */
        button[data-toggle]:hover {
            background-color: rgba(241, 245, 249, 0.5);
        }
        
        /* Animación para mensajes */
        [x-cloak] {
            display: none;
        }
        
        /* Mejor espaciado en errores */
        .text-rose-600 {
            color: #dc2626;
        }
        
        /* Bordes de error más visibles */
        .border-rose-300 {
            border-color: #fca5a5;
        }
        
        /* Estilo para secciones del formulario */
        .section-header {
            position: relative;
        }
        
        .section-header::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        }
        
        /* Scroll suave */
        html {
            scroll-behavior: smooth;
        }
        
        /* Estilos específicos para el input de edad deshabilitado */
        #edadVisual:disabled {
            background-color: #f8fafc;
            color: #334155;
            cursor: not-allowed;
        }
        
        /* Animación para el cálculo de edad */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .text-emerald-600 {
            animation: fadeIn 0.3s ease-in-out;
        }
    </style>
</x-app-layout>