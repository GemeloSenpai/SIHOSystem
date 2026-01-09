<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                📝 Nueva Receta
            </h2>
            <div class="md:hidden">
                <a href="{{ route('medico.consulta.form') }}" class="text-sm text-slate-600 hover:text-slate-800">
                    ← Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-2 px-1 sm:py-4 sm:px-2 lg:px-4">
        <div class="mx-auto max-w-7xl">
            <!-- Información del Paciente - Header Responsive -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow border border-slate-200 overflow-hidden mb-4">
                <div class="md:hidden bg-gradient-to-r from-blue-50 to-indigo-50 p-3 border-b border-slate-200">
                    <div class="space-y-2">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-1">
                                <span class="text-indigo-600">👤</span>
                                Nuevo Paciente
                            </h3>
                            <p class="text-xs text-slate-600 truncate">
                                {{ $expediente->paciente->persona->nombre }} {{ $expediente->paciente->persona->apellido }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-700 font-medium">
                                Exp: {{ $expediente->codigo }}
                            </span>
                            <span class="text-xs text-slate-500">{{ date('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Header desktop -->
                <div class="hidden md:block border-b border-slate-200 p-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="text-indigo-600">👤</span>
                                Nueva Receta para: 
                                <span class="text-indigo-700">{{ $expediente->paciente->persona->nombre }} {{ $expediente->paciente->persona->apellido }}</span>
                            </h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-sm">
                                <span class="text-slate-600">Expediente: <span class="font-semibold">{{ $expediente->codigo }}</span></span>
                                <span class="text-slate-400 hidden sm:inline">•</span>
                                <span class="text-slate-600">Fecha: <span class="font-semibold">{{ date('d/m/Y') }}</span></span>
                                <span class="text-slate-400 hidden sm:inline">•</span>
                                <span class="text-slate-600">Codigo de Paciente:</span><span class="font-semibold">{{ $expediente->paciente->codigo_paciente }}</span></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500">Creando nueva receta</span>
                        </div>
                    </div>
                </div>

                <!-- Datos del Paciente - Responsive -->
                <div class="p-3 sm:p-4">
                    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-3 mb-2">
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Paciente</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base truncate">
                                {{ $expediente->paciente->persona->nombre }} {{ $expediente->paciente->persona->apellido }}
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Edad</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base">
                                {{ $expediente->paciente->persona->edad }} años
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Sexo</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base">
                                {{ $expediente->paciente->persona->sexo == 'M' ? '♂️ Masculino' : '♀️ Femenino' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Expediente</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base">
                                {{ $expediente->codigo }}
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Fecha</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base">
                                {{ date('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('medico.recetas.store', $expediente->id_expediente) }}" method="POST">
                @csrf
                
                <!-- Información Clínica -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow border border-slate-200 overflow-hidden mb-4">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-3 sm:p-4 border-b border-slate-200">
                        <h3 class="font-semibold text-slate-800 text-sm sm:text-base flex items-center gap-2">
                            <span class="text-indigo-600">🩺</span>
                            Información Clínica
                        </h3>
                    </div>
                    
                    <div class="p-3 sm:p-4 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Diagnóstico -->
                            <div class="space-y-2 sm:col-span-2">
                                <label for="diagnostico" class="block text-sm font-medium text-slate-700">
                                    Diagnóstico <span class="text-red-500">*</span>
                                </label>
                                <textarea name="diagnostico" id="diagnostico" 
                                        class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('diagnostico') border-red-500 @enderror"
                                        rows="3" required placeholder="Ingrese el diagnóstico del paciente">{{ old('diagnostico') }}</textarea>
                                @error('diagnostico')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="diagnostico-counter" class="text-xs text-slate-500"></div>
                            </div>
                            
                            <!-- Alergias Conocidas -->
                            <div class="space-y-2 sm:col-span-2">
                                <label for="alergias_conocidas" class="block text-sm font-medium text-slate-700">
                                    Alergias Conocidas
                                </label>
                                <textarea name="alergias_conocidas" id="alergias_conocidas" 
                                        class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        rows="2" placeholder="Lista de alergias conocidas (separadas por coma)">{{ old('alergias_conocidas') }}</textarea>
                                <p class="text-xs text-slate-500 mt-1">
                                    Ej: Penicilina, Aspirina, Latex
                                </p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Peso del Paciente -->
                            <div class="space-y-2">
                                <label for="peso_paciente_en_receta" class="block text-sm font-medium text-slate-700">
                                    Peso del Paciente (kg)
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="peso_paciente_en_receta" id="peso_paciente_en_receta" 
                                           class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           value="{{ $ultimoPeso ?? old('peso_paciente_en_receta') }}" 
                                           placeholder="Ej: 65.5">
                                    <span class="absolute right-3 top-2 text-slate-500 text-sm">kg</span>
                                </div>
                                @if(isset($ultimoPeso))
                                    <p class="text-xs text-green-600 mt-1">
                                        Último registro: {{ $ultimoPeso }} kg
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Estado (oculto, predeterminado activa) -->
                            <input type="hidden" name="estado" value="activa">
                        </div>
                    </div>
                </div>

                <!-- Receta Médica -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow border border-slate-200 overflow-hidden mb-4">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-3 sm:p-4 border-b border-slate-200">
                        <h3 class="font-semibold text-slate-800 text-sm sm:text-base flex items-center gap-2">
                            <span class="text-emerald-600">💊</span>
                            Prescripción Médica <span class="text-red-500">*</span>
                        </h3>
                    </div>
                    
                    <div class="p-3 sm:p-4 space-y-4">
                        <!-- Prescripción -->
                        <div class="space-y-2">
                            <label for="receta" class="block text-sm font-medium text-slate-700">
                                Prescripción Médica
                            </label>
                            <textarea name="receta" id="receta" 
                                      class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('receta') border-red-500 @enderror"
                                      rows="8" required placeholder="Escriba la receta completa incluyendo medicamentos, dosis, frecuencia y duración del tratamiento">{{ old('receta') }}</textarea>
                            @error('receta')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-2">
                                <p class="text-xs text-slate-500">
                                    Formato sugerido: Medicamento - Dosis - Frecuencia - Duración
                                </p>
                                <button type="button" onclick="insertFormat()" 
                                        class="inline-flex items-center justify-center gap-1 rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-300 w-full sm:w-auto">
                                    <span class="hidden xs:inline">✨</span> Sugerir Formato
                                </button>
                            </div>
                        </div>
                        
                        <!-- Observaciones -->
                        <div class="space-y-2">
                            <label for="observaciones" class="block text-sm font-medium text-slate-700">
                                Observaciones Adicionales
                            </label>
                            <textarea name="observaciones" id="observaciones" 
                                      class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      rows="3" placeholder="Indicaciones especiales, recomendaciones, precauciones, etc.">{{ old('observaciones') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción - Responsive -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow border border-slate-200 overflow-hidden">
                    <div class="p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <!-- Botón Cancelar -->
                            <div class="w-full sm:w-auto">
                                <a href="{{ route('medico.consulta.form') }}" 
                                   class="inline-flex items-center justify-center gap-1 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 w-full sm:w-auto">
                                    <span class="hidden xs:inline">←</span> Cancelar
                                </a>
                            </div>
                            
                            <!-- Botón Guardar -->
                            <button type="submit" 
                                    class="inline-flex items-center justify-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto min-w-[140px]">
                                <span class="hidden xs:inline">💾</span> Guardar Receta
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Sugerir formato de receta
        function insertFormat() {
            const recetaTextarea = document.getElementById('receta');
            if (!recetaTextarea.value.trim()) {
                const ejemplo = `1. Paracetamol 500mg - 1 tableta cada 8 horas - 5 días
2. Amoxicilina 500mg - 1 cápsula cada 12 horas - 7 días
3. Ibuprofeno 400mg - 1 tableta cada 12 horas (si hay dolor)

Tomar con alimentos.
Evitar alcohol durante el tratamiento.

Firma: _________________________`;
                recetaTextarea.value = ejemplo;
                
                // Ajustar altura del textarea
                recetaTextarea.style.height = 'auto';
                recetaTextarea.style.height = (recetaTextarea.scrollHeight) + 'px';
                
                // Mostrar notificación
                showNotification('Formato sugerido insertado', 'success');
            }
        }
        
        // Notificación responsive
        function showNotification(message, type) {
            const isMobile = window.innerWidth < 640;
            const notification = document.createElement('div');
            notification.className = `fixed z-50 px-4 py-3 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-emerald-500' : 'bg-blue-500'
            } ${isMobile ? 'top-4 left-4 right-4 mx-auto max-w-sm' : 'top-4 right-4'}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="${type === 'success' ? 'text-emerald-100' : 'text-blue-100'} mr-2">
                        ${type === 'success' ? '✓' : 'ℹ️'}
                    </span>
                    <span class="text-sm font-medium">${message}</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Validación y contador de caracteres
        document.addEventListener('DOMContentLoaded', function() {
            // Contador para diagnóstico
            const diagnostico = document.getElementById('diagnostico');
            const counter = document.getElementById('diagnostico-counter');
            
            function updateCounter() {
                const charCount = diagnostico.value.length;
                if (counter) {
                    counter.textContent = `${charCount} caracteres`;
                    counter.className = `text-xs ${charCount < 10 ? 'text-red-500' : 'text-green-500'}`;
                }
            }
            
            diagnostico.addEventListener('input', updateCounter);
            updateCounter();
            
            // Ajustar altura automáticamente de textareas
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight) + 'px';
                
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });
            
            // Validación de formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const diagnosticoValue = diagnostico.value.trim();
                const recetaValue = document.getElementById('receta').value.trim();
                
                if (diagnosticoValue.length < 10) {
                    e.preventDefault();
                    showNotification('El diagnóstico debe tener al menos 10 caracteres', 'error');
                    diagnostico.focus();
                    return;
                }
                
                if (recetaValue.length < 20) {
                    e.preventDefault();
                    showNotification('La receta debe tener al menos 20 caracteres', 'error');
                    document.getElementById('receta').focus();
                    return;
                }
            });
        });
    </script>
    
    <style>
        /* Estilos para mejorar la experiencia táctil en móviles */
        @media (max-width: 640px) {
            textarea {
                font-size: 16px !important; /* Evita zoom en iOS */
            }
            
            input[type="number"] {
                font-size: 16px !important;
            }
            
            /* Mejorar espaciado en móviles */
            .space-y-4 > * + * {
                margin-top: 1rem !important;
            }
        }
        
        /* Scroll suave para textareas grandes */
        textarea {
            scroll-behavior: smooth;
        }
        
        /* Placeholders más visibles */
        ::placeholder {
            color: #94a3b8 !important;
            opacity: 0.7 !important;
        }
    </style>
    @endpush
</x-app-layout>