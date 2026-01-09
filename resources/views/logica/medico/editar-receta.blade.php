<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                📝 Editar Receta
            </h2>
            <div class="md:hidden">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.recetas.ver', $receta->id_receta) }}" class="text-sm text-slate-600 hover:text-slate-800">
                        ← Cancelar
                    </a>
                @else
                    <a href="{{ route('medico.recetas.ver', $receta->id_receta) }}" class="text-sm text-slate-600 hover:text-slate-800">
                        ← Cancelar
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-2 px-1 sm:py-4 sm:px-2 lg:px-4">
        <div class="mx-auto max-w-7xl">
            <!-- Información de la Receta - Header Responsive -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow border border-slate-200 overflow-hidden mb-4">
                <div class="md:hidden bg-gradient-to-r from-blue-50 to-indigo-50 p-3 border-b border-slate-200">
                    <div class="space-y-2">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-1">
                                <span class="text-indigo-600">💊</span>
                                REC-{{ str_pad($receta->id_receta, 6, '0', STR_PAD_LEFT) }}
                            </h3>
                            <p class="text-xs text-slate-600">
                                Exp: {{ $receta->expediente->codigo }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ 
                                $receta->estado == 'activa' ? 'bg-green-100 text-green-800' : 
                                ($receta->estado == 'completada' ? 'bg-blue-100 text-blue-800' : 
                                ($receta->estado == 'suspendida' ? 'bg-yellow-100 text-yellow-800' : 
                                'bg-red-100 text-red-800')) }}">
                                {{ ucfirst($receta->estado) }}
                            </span>
                            <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($receta->fecha_prescripcion)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Header desktop -->
                <div class="hidden md:block border-b border-slate-200 p-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="text-indigo-600">💊</span>
                                Editar Receta: <span class="text-indigo-700">REC-{{ str_pad($receta->id_receta, 6, '0', STR_PAD_LEFT) }}</span>
                            </h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-sm">
                                <span class="text-slate-600">Expediente: <span class="font-semibold">{{ $receta->expediente->codigo }}</span></span>
                                <span class="text-slate-400 hidden sm:inline">•</span>
                                <span class="text-slate-600">Paciente: <span class="font-semibold">{{ $receta->expediente->paciente->persona->nombre }} {{ $receta->expediente->paciente->persona->apellido }}</span></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($receta->fecha_prescripcion)->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información del Paciente - Responsive -->
                <div class="p-3 sm:p-4">
                    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 mb-4">
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Paciente</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base truncate">
                                {{ $receta->expediente->paciente->persona->nombre }} {{ $receta->expediente->paciente->persona->apellido }}
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Edad / Sexo</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base">
                                {{ $receta->edad_paciente_en_receta }} años / {{ $receta->expediente->paciente->persona->genero == 'M' ? '♂️' : '♀️' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Peso</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base">
                                @if($receta->peso_paciente_en_receta)
                                    {{ number_format($receta->peso_paciente_en_receta, 1) }} kg
                                @else
                                    No registrado
                                @endif
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200">
                            <div class="text-xs text-slate-500 mb-1">Doctor</div>
                            <div class="font-semibold text-slate-800 text-sm sm:text-base truncate">
                                Dr. {{ $receta->doctor->nombre }} {{ $receta->doctor->apellido }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ auth()->user()->role === 'admin' ? route('admin.recetas.update', $receta->id_receta) : route('medico.recetas.update', $receta->id_receta) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Editar Información Clínica -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow border border-slate-200 overflow-hidden mb-4">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-3 sm:p-4 border-b border-slate-200">
                        <h3 class="font-semibold text-slate-800 text-sm sm:text-base flex items-center gap-2">
                            <span class="text-indigo-600">🩺</span>
                            Información Clínica
                        </h3>
                    </div>
                    
                    <div class="p-3 sm:p-4 space-y-4">
                        <!-- <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                             Diagnóstico
                            <div class="space-y-2 sm:col-span-2">
                                <label for="diagnostico" class="block text-sm font-medium text-slate-700">
                                    Diagnóstico <span class="text-red-500">*</span>
                                </label>
                                <textarea name="diagnostico" id="diagnostico" 
                                        class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('diagnostico') border-red-500 @enderror"
                                        rows="3" placeholder="Ingrese el diagnóstico del paciente">{{ old('diagnostico', $receta->diagnostico) }}</textarea>
                                @error('diagnostico')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="diagnostico-counter" class="text-xs text-slate-500"></div>
                            </div>
                             -->
                            
                            <!-- Alergias Conocidas 
                            <div class="space-y-2 sm:col-span-2">
                                <label for="alergias_conocidas" class="block text-sm font-medium text-slate-700">
                                    Alergias Conocidas
                                </label>
                                <textarea name="alergias_conocidas" id="alergias_conocidas" 
                                        class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        rows="2" placeholder="Ingrese alergias conocidas del paciente">{{ old('alergias_conocidas', $receta->alergias_conocidas) }}</textarea>
                            </div>
                          
                        </div>
                          -->
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Peso del Paciente 
                            <div class="space-y-2">
                                <label for="peso_paciente_en_receta" class="block text-sm font-medium text-slate-700">
                                    Peso del Paciente (kg)
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="peso_paciente_en_receta" id="peso_paciente_en_receta" 
                                           class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           value="{{ old('peso_paciente_en_receta', $receta->peso_paciente_en_receta) }}"
                                           placeholder="Ej: 65.5">
                                    <span class="absolute right-3 top-2 text-slate-500 text-sm">kg</span>
                                </div>
                            </div>
                            -->
                            <!-- Estado de la Receta -->
                            <div class="space-y-2">
                                <label for="estado" class="block text-sm font-medium text-slate-700">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="estado" id="estado" 
                                        class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estado') border-red-500 @enderror">
                                    <option value="activa" {{ old('estado', $receta->estado) == 'activa' ? 'selected' : '' }}>Activa</option>
                                    <option value="completada" {{ old('estado', $receta->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                                    <option value="suspendida" {{ old('estado', $receta->estado) == 'suspendida' ? 'selected' : '' }}>Suspendida</option>
                                    <option value="cancelada" {{ old('estado', $receta->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Editar Receta Médica -->
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
                                      rows="8" placeholder="Ingrese la prescripción médica detallada">{{ old('receta', $receta->receta) }}</textarea>
                            @error('receta')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="text-xs text-slate-500">
                                Use formato claro para medicamentos, dosis y frecuencia
                            </div>
                        </div>
                        
                        <!-- Observaciones 
                        <div class="space-y-2">
                            <label for="observaciones" class="block text-sm font-medium text-slate-700">
                                Observaciones Adicionales
                            </label>
                            <textarea name="observaciones" id="observaciones" 
                                      class="w-full px-3 py-2 text-slate-700 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      rows="3" placeholder="Ingrese observaciones adicionales">{{ old('observaciones', $receta->observaciones) }}</textarea>
                        </div>
                        -->
                    </div>
                </div>

                <!-- Botones de Acción - Responsive -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow border border-slate-200 overflow-hidden">
                    <div class="p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <!-- Botón Cancelar -->
                            <div class="w-full sm:w-auto">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.recetas.ver', $receta->id_receta) }}" 
                                       class="inline-flex items-center justify-center gap-1 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 w-full sm:w-auto">
                                        <span class="hidden xs:inline">←</span> Cancelar
                                    </a>
                                @else
                                    <a href="{{ route('medico.recetas.ver', $receta->id_receta) }}" 
                                       class="inline-flex items-center justify-center gap-1 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 w-full sm:w-auto">
                                        <span class="hidden xs:inline">←</span> Cancelar
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Botones de Acción Derecha -->
                            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                                <!-- Botón Imprimir -->
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.recetas.imprimir', $receta->id_receta) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center gap-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 flex-1 sm:flex-none min-w-[120px]">
                                        <span class="hidden xs:inline">🖨️</span> Vista Imprimir
                                    </a>
                                @else
                                    <a href="{{ route('medico.recetas.imprimir', $receta->id_receta) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center gap-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 flex-1 sm:flex-none min-w-[120px]">
                                        <span class="hidden xs:inline">🖨️</span> Vista Imprimir
                                    </a>
                                @endif
                                
                                <!-- Botón Guardar -->
                                <button type="submit" 
                                        class="inline-flex items-center justify-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex-1 sm:flex-none min-w-[140px]">
                                    <span class="hidden xs:inline">💾</span> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Contador de caracteres para diagnóstico
        document.addEventListener('DOMContentLoaded', function() {
            const diagnostico = document.getElementById('diagnostico');
            const counter = document.getElementById('diagnostico-counter');
            
            function updateCounter() {
                const charCount = diagnostico.value.length;
                counter.textContent = `${charCount} caracteres`;
                counter.className = `text-xs ${charCount < 10 ? 'text-red-500' : 'text-green-500'}`;
            }
            
            diagnostico.addEventListener('input', updateCounter);
            
            // Inicializar contador
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
        });
    </script>
    @endpush
</x-app-layout>
