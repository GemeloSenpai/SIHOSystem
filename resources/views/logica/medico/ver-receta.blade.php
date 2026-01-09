<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                📋 Detalle de Receta
            </h2>
            <div class="md:hidden">
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 transition-all duration-200">
                    ← Volver
                </a>
            </div>
        </div>
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
        <!-- Formulario de eliminación -->
        <form id="deleteForm" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>

        <!-- Contenedor principal -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
            <!-- Header superior con gradiente índigo -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-4 sm:p-5 text-white">
                <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="text-center md:text-left">
                        <div class="flex items-center gap-2 justify-center md:justify-start">
                            <span class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                📋
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-bold">REC-{{ str_pad($receta->id_receta, 6, '0', STR_PAD_LEFT) }}</h1>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-center md:justify-start gap-1 sm:gap-3 mt-2 text-sm text-indigo-100">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Expediente: <span class="font-semibold text-white">{{ $receta->expediente->codigo }}</span>
                            </span>
                            <span class="hidden sm:inline text-white/40">•</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Dr. {{ $receta->doctor->nombre }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center md:items-end gap-2">
                        <span class="px-3 sm:px-4 py-1 rounded-full text-xs sm:text-sm font-semibold bg-white/20 backdrop-blur-sm text-black border border-white/30">
                            {{ ucfirst($receta->estado) }}
                        </span>
                        <span class="text-xs sm:text-sm text-black flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($receta->fecha_prescripcion)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Información compacta del Doctor -->
            <div class="bg-white p-4 border-b border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <div class="text-center md:text-left">
                        <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-1 flex items-center justify-center md:justify-start gap-2">
                            Codigo Paciente: {{ $receta->expediente->paciente->codigo_paciente}}
                        </h3>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-1 flex items-center justify-center md:justify-start gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                                👨‍⚕️
                            </span>
                            Médico Responsable
                        </h3>
                        <p class="text-slate-700 font-medium">Dr. {{ $receta->doctor->nombre }} {{ $receta->doctor->apellido }}</p>
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-1 flex items-center justify-center md:justify-start gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                                📅
                            </span>
                            Fecha de Prescripción
                        </h3>
                        <p class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($receta->fecha_prescripcion)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contenido compacto -->
            <div class="p-4 sm:p-5 space-y-4">
                <!-- Grid de información del paciente - Siguiendo la paleta -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            👤
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Paciente</h4>
                        <p class="text-slate-800 font-medium truncate" title="{{ $receta->expediente->paciente->persona->nombre }} {{ $receta->expediente->paciente->persona->apellido }}">
                            {{ $receta->expediente->paciente->persona->nombre }} {{ $receta->expediente->paciente->persona->apellido }}
                        </p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            🎂
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Edad / Sexo</h4>
                        <p class="text-slate-800 font-medium">{{ $receta->edad_paciente_en_receta }} años / {{ $receta->expediente->paciente->persona->genero == 'M' ? '♂️ Masculino' : '♀️ Femenino' }}</p>
                    </div>
                    
                    <!-- 
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            ⚖️
                        </div>

                        
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Peso</h4>
                        <p class="text-slate-800 font-medium">
                            @if($receta->peso_paciente_en_receta)
                                {{ number_format($receta->peso_paciente_en_receta, 1) }} kg<br>
                                <span class="text-xs text-indigo-600 font-normal">({{ number_format($receta->peso_paciente_en_receta * 2.20462, 1) }} lb)</span>
                            @else
                                <span class="text-slate-400 italic">No registrado</span>
                            @endif
                        </p>
                    </div>
                -->

                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            📋
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Código Receta</h4>
                        <p class="text-slate-800 font-medium text-sm">REC-{{ str_pad($receta->id_receta, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <!-- Diagnóstico - Estilo consistente 
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3">
                        <h4 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                🩺
                            </span>
                            Diagnóstico
                        </h4>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="text-slate-800 whitespace-pre-line text-sm sm:text-base leading-relaxed bg-indigo-50 p-3 sm:p-4 rounded-lg border border-indigo-100">
                            {{ $receta->diagnostico }}
                        </div>
                    </div>
                </div>
                -->

                <!-- Alergias (solo si existen) - Usando ámbar para advertencias -->
                @if($receta->alergias_conocidas)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-3">
                        <h4 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                ⚠️
                            </span>
                            Alergias Conocidas
                        </h4>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="text-slate-800 whitespace-pre-line text-sm sm:text-base leading-relaxed bg-amber-50 p-3 sm:p-4 rounded-lg border border-amber-100">
                            {{ $receta->alergias_conocidas }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Prescripción Médica - Usando esmeralda para elementos positivos -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-4 py-3">
                        <h4 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                💊
                            </span>
                            Prescripción Médica
                        </h4>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="text-slate-800 whitespace-pre-line font-mono text-sm leading-relaxed bg-emerald-50 p-3 sm:p-4 rounded-lg border border-emerald-100 shadow-sm">
                            {{ $receta->receta }}
                        </div>
                    </div>
                </div>

                <!-- Observaciones (solo si existen) - Volviendo al índigo -->
                @if($receta->observaciones)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3">
                        <h4 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                📝
                            </span>
                            Observaciones
                        </h4>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="text-slate-800 whitespace-pre-line text-sm sm:text-base leading-relaxed bg-indigo-50 p-3 sm:p-4 rounded-lg border border-indigo-100">
                            {{ $receta->observaciones }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Botones de acción - Siguiendo la paleta exacta -->
                <div class="pt-4 border-t border-slate-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $user = auth()->user();
                            
                            if($user->role === 'admin') {
                                $editarRoute = route('admin.recetas.editar', $receta->id_receta);
                                $imprimirRoute = route('admin.recetas.imprimir', $receta->id_receta);
                                $destroyRoute = route('admin.recetas.destroy', $receta->id_receta);
                                $redirectAfterDelete = route('admin.expedientes.index');
                                $volverRoute = route('admin.expedientes.index');
                                $mostrarEliminar = true;
                            } elseif($user->role === 'medico') {
                                $editarRoute = route('medico.recetas.editar', $receta->id_receta);
                                $imprimirRoute = route('medico.recetas.imprimir', $receta->id_receta);
                                $destroyRoute = route('medico.recetas.destroy', $receta->id_receta);
                                $redirectAfterDelete = route('expedientes.index');
                                $volverRoute = route('expedientes.index');
                                $mostrarEliminar = true;
                            } else {
                                $editarRoute = null;
                                $imprimirRoute = null;
                                $destroyRoute = null;
                                $volverRoute = url()->previous();
                                $mostrarEliminar = false;
                            }
                        @endphp
                        
                        <!-- Botón Volver - Gris como en la otra vista -->
                        <a href="{{ $volverRoute }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-600 hover:bg-slate-700 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver
                        </a>
                        
                        <!-- Botón Imprimir - Índigo como botón principal -->
                        @if($imprimirRoute)
                            <a href="{{ $imprimirRoute }}" target="_blank"
                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Imprimir
                            </a>
                        @endif
                        
                        <!-- Botón Editar - Ámbar como en la otra vista -->
                        @if($editarRoute)
                            <a href="{{ $editarRoute }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>
                        @endif
                        
                        <!-- Botón Eliminar - Rojo como en la otra vista -->
                        @if($mostrarEliminar && $destroyRoute)
                            <button type="button" 
                                    onclick="confirmDelete('{{ $destroyRoute }}', '{{ $redirectAfterDelete }}')"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>
                        @endif
                    </div>
                    
                    <!-- Información de generación - Texto sutil -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-slate-500 flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documento generado el {{ date('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        function confirmDelete(deleteUrl, redirectUrl) {
            Swal.fire({
                title: '¿Eliminar receta?',
                html: `
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.928-.833-2.698 0L3.288 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 mb-1">
                            ¿Estás seguro de eliminar esta receta?
                        </p>
                        <p class="text-xs text-red-500 font-medium">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    actions: 'gap-3'
                },
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = deleteUrl;
                        form.style.display = 'none';
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);
                        
                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(method);
                        
                        document.body.appendChild(form);
                        form.submit();
                        
                        setTimeout(() => resolve(), 100);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¡Eliminado!',
                        text: 'Receta eliminada exitosamente',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'rounded-xl'
                        },
                        willClose: () => {
                            window.location.href = redirectUrl;
                        }
                    });
                }
            });
        }

        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    position: 'top-end',
                    toast: true,
                    customClass: {
                        popup: 'rounded-lg'
                    }
                });
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700'
                    },
                    buttonsStyling: false
                });
            });
        @endif
    </script>
</x-app-layout>