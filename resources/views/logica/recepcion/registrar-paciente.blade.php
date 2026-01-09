<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏥 Registrar Paciente
        </h2>
    </x-slot>

    <style>
        [x-cloak] {
            display: none !important
        }

        /* Estilos para la transición del encargado */
        .encargado-section {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        /* Spinner animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Estilo para cuando el formulario está enviando */
        .form-submitting button[type="submit"] {
            opacity: 0.8;
            cursor: not-allowed;
        }

        /* Estilo para campos readonly */
        .readonly-field {
            background-color: #f9fafb;
            border-color: #d1d5db;
            color: #6b7280;
            cursor: not-allowed;
        }

        /* Responsive styles */
        @media (max-width: 640px) {
            .mobile-full-width {
                width: 100% !important;
            }
            
            .mobile-stack {
                display: flex;
                flex-direction: column;
            }
            
            .mobile-padding {
                padding: 1rem !important;
            }
            
            .mobile-grid {
                grid-template-columns: 1fr !important;
            }
            
            .mobile-form-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            .mobile-text-sm {
                font-size: 0.875rem !important;
            }
            
            .mobile-input {
                font-size: 16px !important; /* Previene zoom en iOS */
                padding: 0.75rem !important;
            }
            
            .mobile-label {
                font-size: 0.875rem !important;
                margin-bottom: 0.25rem !important;
            }
            
            .mobile-button {
                padding: 0.75rem 1rem !important;
                width: 100%;
                justify-content: center;
            }
            
            .mobile-gap {
                gap: 1rem !important;
            }
            
            .mobile-section {
                margin-bottom: 1.5rem !important;
            }
            
            .mobile-scroll {
                overflow-x: hidden !important;
            }
        }
        
        @media (max-width: 768px) {
            .tablet-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            .tablet-form-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }
        }
        
        /* Mejorar experiencia táctil */
        .touch-optimized {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
        
        .touch-optimized:focus {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
        }
        
        /* Scroll suave para formularios largos */
        .form-container {
            scroll-behavior: smooth;
        }
        
        /* Asegurar que los inputs sean fácilmente seleccionables */
        input, select, textarea {
            min-height: 44px;
        }
    </style>

    <div class="bg-white p-4 md:p-6 rounded-2xl shadow ring-1 ring-slate-200 mobile-scroll">
        @if (session('success') || $errors->any())
            <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
            <script>
                (function waitSwal(tries = 40) {
                    if (window.Swal) {
                        @if (session('success'))
                            Swal.fire({
                                icon: 'success',
                                title: @json(session('success')),
                                position: 'center',
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                                backdrop: true,
                            });
                        @endif

                        @if ($errors->any())
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                title: 'Hay {{ $errors->count() }} error(es) en el formulario',
                                position: 'top-end',
                                timer: 2800,
                                showConfirmButton: false
                            });
                        @endif
                        return;
                    }
                    if (tries <= 0) {
                        @if (session('success'))
                            alert(@json(session('success')));
                        @endif
                        @if ($errors->any())
                            alert('Hay errores en el formulario');
                        @endif
                        return;
                    }
                    setTimeout(() => waitSwal(tries - 1), 80);
                })();
            </script>
        @endif

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-2 lg:px-4">
                <div class="bg-white overflow-hidden">
                    <div class="p-0 sm:p-2">
                        <form method="POST" action="{{ route('recepcion.pacientes.store') }}" class="space-y-8 form-container"
                            id="form-registro-paciente">
                            @csrf
                            
                            {{-- === Datos del Paciente === --}}
                            <div class="space-y-6 mobile-section">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2 mobile-text-sm">
                                        🧍 Ingrese los datos del Paciente
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-500 mobile-text-sm">
                                        Todos los campos marcados con <span class="text-rose-600 font-semibold">*</span>
                                        son obligatorios.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mobile-form-grid tablet-form-grid">
                                    {{-- Código de Paciente --}}
                                    <div class="md:col-span-2">
                                        <label for="codigo_paciente" class="block text-sm font-medium text-gray-700 mobile-label">
                                            Código de Paciente <span class="text-rose-600">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="codigo_paciente" id="codigo_paciente"
                                                value="{{ old('codigo_paciente', App\Models\Paciente::generarCodigoUnico()) }}"
                                                readonly
                                                class="mt-1 block w-full rounded-md border bg-gray-50 border-gray-300 shadow-sm sm:text-sm py-3 px-3 readonly-field mobile-input touch-optimized">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                    Generado
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @error('codigo_paciente')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nombre --}}
                                    <div class="md:col-span-2">
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 mobile-label">
                                            Nombre <span class="text-rose-600">*</span>
                                        </label>
                                        <input type="text" name="nombre" id="nombre" placeholder="Maria" autocomplete="given-name"
                                            autofocus value="{{ old('nombre') }}"
                                            class="mt-1 block w-full rounded-md border {{ $errors->has('nombre') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                        @error('nombre')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Apellido --}}
                                    <div class="md:col-span-2">
                                        <label for="apellido" class="block text-sm font-medium text-gray-700 mobile-label">
                                            Apellido <span class="text-rose-600">*</span>
                                        </label>
                                        <input type="text" name="apellido" id="apellido" placeholder="Cipriano" autocomplete="family-name"
                                            value="{{ old('apellido') }}"
                                            class="mt-1 block w-full rounded-md border {{ $errors->has('apellido') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                        @error('apellido')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- DNI --}}
                                    <div class="md:col-span-1">
                                        <label for="dni" class="block text-sm font-medium text-gray-700 mobile-label">
                                            DNI <span class="text-rose-600">*</span>
                                        </label>
                                        <input type="text" name="dni" id="dni" inputmode="numeric"
                                            pattern="^[0-9]{13}$" maxlength="13" placeholder="1234567891234"
                                            value="{{ old('dni') }}"
                                            class="mt-1 block w-full rounded-md border {{ $errors->has('dni') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                        @error('dni')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Edad --}}
                                    <div class="md:col-span-1">
                                        <label for="edad" class="block text-sm font-medium text-gray-700 mobile-label">
                                            Edad <span class="text-rose-600">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="edad" id="edad" min="0"
                                                max="120" inputmode="numeric" value="{{ old('edad') }}" readonly
                                                class="mt-1 block w-full rounded-md border readonly-field bg-gray-50 {{ $errors->has('edad') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm sm:text-sm py-3 px-3 mobile-input">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                    Calculado
                                                </span>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500" id="edad-info">
                                            Calculado desde fecha de nacimiento
                                        </p>
                                        @error('edad')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Fecha de Nacimiento --}}
                                    <div class="md:col-span-1">
                                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mobile-label">
                                            Fecha de Nacimiento <span class="text-rose-600">*</span>
                                        </label>
                                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                            value="{{ old('fecha_nacimiento') }}" max="{{ now()->format('Y-m-d') }}"
                                            class="mt-1 block w-full rounded-md border {{ $errors->has('fecha_nacimiento') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                        <p class="mt-1 text-xs text-gray-500" id="fecha-info">
                                            @php
                                                $fechaPaciente = old('fecha_nacimiento');
                                            @endphp
                                            @if ($fechaPaciente)
                                                {{ \Carbon\Carbon::parse($fechaPaciente)->format('d/m/Y') }}
                                            @else
                                                Seleccione fecha
                                            @endif
                                        </p>
                                        @error('fecha_nacimiento')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Sexo --}}
                                    <div class="md:col-span-1">
                                        <label for="sexo" class="block text-sm font-medium text-gray-700 mobile-label">
                                            Sexo <span class="text-rose-600">*</span>
                                        </label>
                                        <select id="sexo" name="sexo"
                                            class="mt-1 block w-full rounded-md border {{ $errors->has('sexo') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} bg-white py-3 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mobile-input touch-optimized">
                                            <option value="">Seleccione...</option>
                                            <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                                            <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                                        </select>
                                        @error('sexo')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Teléfono --}}
                                    <div class="md:col-span-2">
                                        <label for="telefono" class="block text-sm text-slate-600 mb-1 mobile-label">
                                            Teléfono <span class="text-slate-400">(opcional)</span>
                                        </label>
                                        <input type="text" id="telefono" name="telefono"
                                            value="{{ old('telefono') }}" placeholder="+504 00000000"
                                            class="w-full border rounded-lg px-3 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 mobile-input touch-optimized">
                                        @error('telefono')
                                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Dirección --}}
                                    <div class="md:col-span-2">
                                        <label for="direccion" class="block text-sm font-medium text-gray-700 mobile-label">
                                            Dirección <span class="text-rose-600">*</span>
                                        </label>
                                        <input type="text" name="direccion" id="direccion" placeholder="Colonia, Municipio, Depto"
                                            autocomplete="street-address" value="{{ old('direccion') }}"
                                            class="mt-1 block w-full rounded-md border {{ $errors->has('direccion') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                        @error('direccion')
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Tipo de Consulta --}}
                            <div class="md:col-span-2 mobile-section">
                                <label for="tipo_consulta" class="block text-sm font-medium text-gray-700 mobile-label">
                                    Tipo de Consulta <span class="text-rose-600">*</span>
                                </label>
                                <select id="tipo_consulta" name="tipo_consulta"
                                    class="mt-1 block w-full rounded-md border {{ $errors->has('tipo_consulta') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} bg-white py-3 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mobile-input touch-optimized">
                                    <option value="">Seleccione...</option>
                                    <option value="general" @selected(old('tipo_consulta') === 'general')>Consulta General</option>
                                    <option value="especializada" @selected(old('tipo_consulta') === 'especializada')>Consulta Especializada</option>
                                </select>
                                @error('tipo_consulta')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- === Encargado (Opcional) === --}}
                            <div class="space-y-6 mobile-section">
                                <div class="flex items-center">
                                    <input type="checkbox" id="toggle-encargado"
                                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 touch-optimized">
                                    <label for="toggle-encargado"
                                        class="ml-2 block text-sm font-medium text-gray-700">
                                        ¿Desea agregar información de un encargado?
                                    </label>
                                </div>

                                <div id="encargado-section"
                                    class="encargado-section bg-indigo-50/40 p-4 md:p-6 rounded-lg border border-indigo-100"
                                    style="display: none;">
                                    <div class="mb-4">
                                        <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2 mobile-text-sm">
                                            <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0" />
                                            </svg>
                                            Datos del Encargado
                                        </h3>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mobile-form-grid tablet-form-grid">
                                        {{-- Nombre encargado --}}
                                        <div class="md:col-span-2">
                                            <label for="encargado_nombre"
                                                class="block text-sm font-medium text-gray-700 mobile-label">Nombre</label>
                                            <input type="text" name="encargado_nombre" id="encargado_nombre"
                                                value="{{ old('encargado_nombre') }}"
                                                class="mt-1 block w-full rounded-md border {{ $errors->has('encargado_nombre') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                            @error('encargado_nombre')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Apellido encargado --}}
                                        <div class="md:col-span-2">
                                            <label for="encargado_apellido"
                                                class="block text-sm font-medium text-gray-700 mobile-label">Apellido</label>
                                            <input type="text" name="encargado_apellido" id="encargado_apellido"
                                                value="{{ old('encargado_apellido') }}"
                                                class="mt-1 block w-full rounded-md border {{ $errors->has('encargado_apellido') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                            @error('encargado_apellido')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- DNI encargado --}}
                                        <div class="md:col-span-1">
                                            <label for="encargado_dni"
                                                class="block text-sm font-medium text-gray-700 mobile-label">DNI</label>
                                            <input type="text" name="encargado_dni" id="encargado_dni"
                                                inputmode="numeric" pattern="^[0-9]{13}$" maxlength="13"
                                                placeholder="13 dígitos" value="{{ old('encargado_dni') }}"
                                                class="mt-1 block w-full rounded-md border {{ $errors->has('encargado_dni') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                            @error('encargado_dni')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Edad encargado --}}
                                        <div class="md:col-span-1">
                                            <label for="encargado_edad"
                                                class="block text-sm font-medium text-gray-700 mobile-label">Edad</label>
                                            <div class="relative">
                                                <input type="number" name="encargado_edad" id="encargado_edad"
                                                    min="0" max="120"
                                                    value="{{ old('encargado_edad') }}" readonly
                                                    class="mt-1 block w-full rounded-md border readonly-field bg-gray-50 {{ $errors->has('encargado_edad') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm sm:text-sm py-3 px-3 mobile-input">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                        Calculado
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500" id="encargado-edad-info">
                                                Calculado automáticamente
                                            </p>
                                            @error('encargado_edad')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Fecha de Nacimiento encargado --}}
                                        <div class="md:col-span-1">
                                            <label for="encargado_fecha_nacimiento"
                                                class="block text-sm font-medium text-gray-700 mobile-label">Fecha de Nacimiento</label>
                                            <input type="date" name="encargado_fecha_nacimiento"
                                                id="encargado_fecha_nacimiento"
                                                value="{{ old('encargado_fecha_nacimiento') }}"
                                                max="{{ now()->format('Y-m-d') }}"
                                                class="mt-1 block w-full rounded-md border {{ $errors->has('encargado_fecha_nacimiento') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                            <p class="mt-1 text-xs text-gray-500" id="encargado-fecha-info">
                                                @php
                                                    $fechaEncargado = old('encargado_fecha_nacimiento');
                                                @endphp
                                                @if ($fechaEncargado)
                                                    {{ \Carbon\Carbon::parse($fechaEncargado)->format('d/m/Y') }}
                                                @else
                                                    Seleccione fecha
                                                @endif
                                            </p>
                                            @error('encargado_fecha_nacimiento')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Sexo encargado --}}
                                        <div class="md:col-span-1">
                                            <label for="encargado_sexo"
                                                class="block text-sm font-medium text-gray-700 mobile-label">Sexo</label>
                                            <select id="encargado_sexo" name="encargado_sexo"
                                                class="mt-1 block w-full rounded-md border {{ $errors->has('encargado_sexo') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} bg-white py-3 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mobile-input touch-optimized">
                                                <option value="">Seleccione...</option>
                                                <option value="M" @selected(old('encargado_sexo') === 'M')>Masculino</option>
                                                <option value="F" @selected(old('encargado_sexo') === 'F')>Femenino</option>
                                            </select>
                                            @error('encargado_sexo')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Teléfono encargado --}}
                                        <div class="md:col-span-2">
                                            <label for="encargado_telefono"
                                                class="block text-sm font-medium text-gray-700 mobile-label">Teléfono</label>
                                            <input type="text" name="encargado_telefono" id="encargado_telefono"
                                                inputmode="tel" pattern="^[0-9+()\-\s]+$" maxlength="15"
                                                value="{{ old('encargado_telefono') }}"
                                                class="mt-1 block w-full rounded-md border {{ $errors->has('encargado_telefono') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                            @error('encargado_telefono')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Dirección encargado --}}
                                        <div class="md:col-span-2">
                                            <label for="encargado_direccion"
                                                class="block text-sm font-medium text-gray-700 mobile-label">Dirección</label>
                                            <input type="text" name="encargado_direccion" id="encargado_direccion"
                                                value="{{ old('encargado_direccion') }}"
                                                class="mt-1 block w-full rounded-md border {{ $errors->has('encargado_direccion') ? 'border-rose-400 ring-1 ring-rose-300' : 'border-gray-300' }} shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-3 mobile-input touch-optimized">
                                            @error('encargado_direccion')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- === Acciones === --}}
                            <div class="pt-6 mobile-section">
                                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 mobile-gap mobile-stack">
                                    <a href="{{ route('recepcion.verPacientes') }}"
                                        class="inline-flex justify-center rounded-md border border-slate-300 bg-white py-3 px-6 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400 mobile-button touch-optimized w-full sm:w-auto">
                                        Cancelar
                                    </a>

                                    <button type="submit" id="submit-button"
                                        class="inline-flex justify-center items-center gap-2 rounded-md border border-transparent bg-indigo-600 py-3 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 mobile-button touch-optimized w-full sm:w-auto">
                                        <svg class="-ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                        </svg>
                                        <span id="submit-text">Registrar Paciente</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Resumen de errores --}}
                        @if ($errors->any())
                            <div class="mt-6 rounded-md bg-rose-50 p-4 border border-rose-200 mobile-section">
                                <h3 class="text-sm font-semibold text-rose-800">Revisa los siguientes puntos:</h3>
                                <ul class="mt-2 list-disc pl-5 text-sm text-rose-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript puro --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ Script de registro de paciente iniciado');

            // ============================================
            // 1. VARIABLES GLOBALES
            // ============================================
            let isSubmitting = false;
            const form = document.getElementById('form-registro-paciente');
            const checkbox = document.getElementById('toggle-encargado');
            const encargadoSection = document.getElementById('encargado-section');
            const submitButton = document.getElementById('submit-button');
            const submitText = document.getElementById('submit-text');

            // ============================================
            // 2. CÁLCULO AUTOMÁTICO DE EDAD
            // ============================================
            function calcularEdadPrecisa(fechaNacimiento) {
                if (!fechaNacimiento) return null;

                const birthDate = new Date(fechaNacimiento);
                const today = new Date();

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
                        error: 'La edad calculada está fuera del rango permitido (0-120)'
                    };
                }

                return age;
            }

            function formatearFecha(fechaStr) {
                if (!fechaStr) return '';
                const fecha = new Date(fechaStr);
                return fecha.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function actualizarEdadDesdeFecha(fechaInputId, edadInputId, infoElementId, fechaInfoId) {
                const fechaInput = document.getElementById(fechaInputId);
                const edadInput = document.getElementById(edadInputId);
                const infoElement = document.getElementById(infoElementId);
                const fechaInfo = document.getElementById(fechaInfoId);

                if (fechaInput && edadInput) {
                    fechaInput.addEventListener('change', function() {
                        const fecha = this.value;

                        if (!fecha) {
                            edadInput.value = '';
                            if (infoElement) {
                                infoElement.textContent = 'Selecciona una fecha de nacimiento';
                                infoElement.className = 'mt-1 text-xs text-amber-600';
                            }
                            if (fechaInfo) {
                                fechaInfo.textContent = fechaInputId === 'fecha_nacimiento' ?
                                    'Seleccione la fecha para calcular la edad' :
                                    'Seleccione fecha';
                                fechaInfo.className = 'mt-1 text-xs text-gray-500';
                            }
                            return;
                        }

                        const resultado = calcularEdadPrecisa(fecha);

                        if (typeof resultado === 'object' && resultado.error) {
                            edadInput.value = '';
                            if (infoElement) {
                                infoElement.textContent = resultado.error;
                                infoElement.className = 'mt-1 text-xs text-rose-600';
                            }

                            // Enfocar el campo de fecha si es futura
                            if (resultado.error.includes('futura')) {
                                fechaInput.focus();
                            }
                        } else {
                            edadInput.value = resultado;
                            if (infoElement) {
                                infoElement.textContent = `Edad calculada: ${resultado} años`;
                                infoElement.className = 'mt-1 text-xs text-emerald-600';
                            }
                        }

                        // Actualizar información de fecha
                        if (fechaInfo) {
                            const fechaFormateada = formatearFecha(fecha);
                            fechaInfo.textContent = fechaInputId === 'fecha_nacimiento' ?
                                `${fechaFormateada}` :
                                `${fechaFormateada}`;
                            fechaInfo.className = 'mt-1 text-xs text-emerald-600';
                        }
                    });

                    // Calcular edad al cargar si hay fecha
                    if (fechaInput.value) {
                        fechaInput.dispatchEvent(new Event('change'));
                    }
                }
            }

            // Configurar cálculo para paciente
            actualizarEdadDesdeFecha(
                'fecha_nacimiento',
                'edad',
                'edad-info',
                'fecha-info'
            );

            // Configurar cálculo para encargado
            actualizarEdadDesdeFecha(
                'encargado_fecha_nacimiento',
                'encargado_edad',
                'encargado-edad-info',
                'encargado-fecha-info'
            );

            // ============================================
            // 3. MANEJO DEL CHECKBOX "MOSTRAR ENCARGADO"
            // ============================================
            if (checkbox && encargadoSection) {
                const encargadoNombre = "{{ old('encargado_nombre') }}";
                const encargadoApellido = "{{ old('encargado_apellido') }}";
                const encargadoDni = "{{ old('encargado_dni') }}";
                const encargadoEdad = "{{ old('encargado_edad') }}";
                const encargadoFecha = "{{ old('encargado_fecha_nacimiento') }}";

                const tieneDatosEncargado =
                    encargadoNombre !== "" ||
                    encargadoApellido !== "" ||
                    encargadoDni !== "" ||
                    encargadoEdad !== "" ||
                    encargadoFecha !== "";

                // Configurar estado inicial
                checkbox.checked = tieneDatosEncargado;
                encargadoSection.style.display = tieneDatosEncargado ? 'block' : 'none';

                // Evento para mostrar/ocultar
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        encargadoSection.style.display = 'block';
                        // Scroll suave a la sección del encargado en móviles
                        if (window.innerWidth < 768) {
                            setTimeout(() => {
                                encargadoSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }, 100);
                        }
                        setTimeout(() => {
                            encargadoSection.style.opacity = '1';
                        }, 10);
                    } else {
                        encargadoSection.style.opacity = '0';
                        setTimeout(() => {
                            encargadoSection.style.display = 'none';
                        }, 300);
                    }
                });

                // Mejorar accesibilidad del checkbox en móviles
                checkbox.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    this.checked = !this.checked;
                    this.dispatchEvent(new Event('change'));
                });

                console.log('✅ Checkbox configurado correctamente');
            }

            // ============================================
            // 4. VALIDACIÓN EN TIEMPO REAL
            // ============================================
            // Validar DNI (solo números, máximo 13)
            const dniInput = document.getElementById('dni');
            const encargadoDniInput = document.getElementById('encargado_dni');

            function validarDNI(input) {
                if (input) {
                    input.addEventListener('input', function() {
                        this.value = this.value.replace(/\D/g, '').substring(0, 13);
                    });
                }
            }

            validarDNI(dniInput);
            validarDNI(encargadoDniInput);

            // Validar teléfono (solo números y algunos símbolos)
            const telefonoInput = document.getElementById('telefono');
            const encargadoTelefonoInput = document.getElementById('encargado_telefono');

            function validarTelefono(input) {
                if (input) {
                    input.addEventListener('input', function() {
                        this.value = this.value.replace(/[^\d\s\+\-\(\)]/g, '');
                    });
                }
            }

            validarTelefono(telefonoInput);
            validarTelefono(encargadoTelefonoInput);

            // ============================================
            // 5. VALIDACIÓN DE FECHA DE NACIMIENTO ANTES DE ENVIAR
            // ============================================
            function validarFechaNacimiento(inputId, esEncargado = false) {
                const input = document.getElementById(inputId);
                if (input && input.value) {
                    const fecha = new Date(input.value);
                    const hoy = new Date();

                    if (fecha > hoy) {
                        return 'La fecha de nacimiento no puede ser futura';
                    }

                    const edad = calcularEdadPrecisa(input.value);
                    if (typeof edad === 'object' && edad.error) {
                        return edad.error;
                    }

                    if (edad < 0 || edad > 120) {
                        return 'La edad debe estar entre 0 y 120 años';
                    }

                    // Validación adicional: comparar fechas si ambos campos están llenos
                    if (esEncargado) {
                        const fechaPacienteInput = document.getElementById('fecha_nacimiento');
                        if (fechaPacienteInput && fechaPacienteInput.value) {
                            const fechaPaciente = new Date(fechaPacienteInput.value);
                            if (fechaPaciente.getTime() === fecha.getTime()) {
                                return 'La fecha de nacimiento del encargado no puede ser igual a la del paciente';
                            }
                        }
                    }
                }
                return null;
            }

            // ============================================
            // 6. MANEJO DEL ENVÍO DEL FORMULARIO
            // ============================================
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Si ya está enviando, prevenir reenvío
                    if (isSubmitting) {
                        e.preventDefault();
                        console.log('⏳ Ya se está enviando, previniendo reenvío...');
                        return false;
                    }

                    // Validación de campos requeridos
                    const nombre = document.getElementById('nombre')?.value.trim();
                    const apellido = document.getElementById('apellido')?.value.trim();
                    const dni = document.getElementById('dni')?.value.trim();
                    const edad = document.getElementById('edad')?.value;
                    const fechaNacimiento = document.getElementById('fecha_nacimiento')?.value;
                    const sexo = document.getElementById('sexo')?.value;
                    const direccion = document.getElementById('direccion')?.value.trim();
                    const tipoConsulta = document.getElementById('tipo_consulta')?.value;

                    let errores = [];

                    if (!nombre) errores.push('• Nombre es requerido');
                    if (!apellido) errores.push('• Apellido es requerido');
                    if (!dni) errores.push('• DNI es requerido');
                    if (!fechaNacimiento) errores.push('• Fecha de nacimiento es requerida');
                    if (!edad) errores.push(
                        '• La edad no se pudo calcular. Verifique la fecha de nacimiento');
                    if (!sexo) errores.push('• Sexo es requerido');
                    if (!direccion) errores.push('• Dirección es requerida');
                    if (!tipoConsulta) errores.push('• Tipo de consulta es requerido');

                    // Validar DNI (13 dígitos)
                    if (dni && dni.length !== 13) {
                        errores.push('• DNI debe tener 13 dígitos');
                    }

                    // Validar fecha de nacimiento del paciente
                    const errorFechaPaciente = validarFechaNacimiento('fecha_nacimiento', false);
                    if (errorFechaPaciente) {
                        errores.push('• Paciente: ' + errorFechaPaciente);
                    }

                    // Validar fecha de nacimiento del encargado si está activo
                    if (checkbox && checkbox.checked) {
                        const encargadoFecha = document.getElementById('encargado_fecha_nacimiento')?.value;
                        if (!encargadoFecha) {
                            errores.push('• Encargado: Fecha de nacimiento es requerida');
                        } else {
                            const errorFechaEncargado = validarFechaNacimiento('encargado_fecha_nacimiento',
                                true);
                            if (errorFechaEncargado) {
                                errores.push('• Encargado: ' + errorFechaEncargado);
                            }
                        }
                    }

                    // Si hay errores, mostrar alerta y prevenir envío
                    if (errores.length > 0) {
                        e.preventDefault();
                        // Mejor alerta para móviles
                        const errorMessage = errores.join('\n');
                        if (window.innerWidth < 768) {
                            // Scroll al primer error
                            const firstErrorField = document.querySelector('.border-rose-400');
                            if (firstErrorField) {
                                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstErrorField.focus();
                            }
                        }
                        alert('Por favor complete los campos requeridos:\n\n' + errorMessage);
                        return false;
                    }

                    // Si todo está bien, mostrar estado de carga
                    isSubmitting = true;

                    if (submitButton && submitText) {
                        const originalHTML = submitButton.innerHTML;
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-60', 'cursor-not-allowed');
                        submitButton.innerHTML = `
                            <svg class="-ml-1 h-5 w-5 animate-spin" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Guardando…</span>
                        `;

                        // Timeout de seguridad (30 segundos)
                        setTimeout(() => {
                            if (isSubmitting) {
                                isSubmitting = false;
                                submitButton.disabled = false;
                                submitButton.classList.remove('opacity-60', 'cursor-not-allowed');
                                submitButton.innerHTML = originalHTML;
                                alert(
                                    'El envío está tardando mucho. Por favor, intente nuevamente.'
                                    );
                            }
                        }, 30000);
                    }

                    console.log('✅ Formulario enviándose...');
                    return true;
                });
            }

            // ============================================
            // 7. MEJORAS DE UX PARA MÓVILES
            // ============================================
            // Auto-focus en el primer campo
            setTimeout(() => {
                const firstInput = document.querySelector(
                    'input:not([type="hidden"]):not([readonly]):not([type="checkbox"]), select, textarea');
                if (firstInput) {
                    firstInput.focus();
                }
            }, 300);

            // Limpiar mensajes de error al empezar a escribir
            const inputs = document.querySelectorAll('input:not([readonly]), select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const errorElement = this.nextElementSibling;
                    if (errorElement && errorElement.classList.contains('text-rose-600')) {
                        errorElement.style.display = 'none';
                    }
                });
            });

            // Prevenir que el usuario escriba en campos de edad
            const edadInputs = document.querySelectorAll('input[type="number"][readonly]');
            edadInputs.forEach(input => {
                input.addEventListener('keydown', function(e) {
                    e.preventDefault();
                });

                input.addEventListener('click', function() {
                    const fechaId = this.id === 'edad' ? 'fecha_nacimiento' :
                        'encargado_fecha_nacimiento';
                    const fechaInput = document.getElementById(fechaId);
                    if (fechaInput) {
                        fechaInput.focus();
                        if (window.innerWidth < 768) {
                            // Mejor experiencia en móviles
                            setTimeout(() => {
                                fechaInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }, 100);
                        }
                        alert(
                            'La edad se calcula automáticamente desde la fecha de nacimiento. Modifique la fecha para cambiar la edad.'
                            );
                    }
                });
            });

            // Mejorar selección de fecha en móviles
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('touchstart', function() {
                    // Forzar mostrar el teclado en iOS para mejor UX
                    if (window.innerWidth < 768) {
                        this.focus();
                    }
                });
            });

            // Ajustar scroll en móviles al cambiar de campo
            if (window.innerWidth < 768) {
                const formElements = document.querySelectorAll('input, select, textarea');
                formElements.forEach(element => {
                    element.addEventListener('focus', function() {
                        setTimeout(() => {
                            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                    });
                });
            }

            console.log('✅ Script de registro de paciente cargado completamente');
        });
    </script>
</x-app-layout>