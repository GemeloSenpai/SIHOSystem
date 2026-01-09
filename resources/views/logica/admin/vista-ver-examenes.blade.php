<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                🩺 Detalle de Examen Médico
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

        <!-- Información de Contacto del Laboratorio 
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3">
                <h4 class="text-base sm:text-lg font-bold text-white text-center">📍 Información del Laboratorio</h4>
            </div>
            <div class="p-4 bg-white">
                <div class="text-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-800 mb-1">🏥 Laboratorio Clínico Emmanuel</h3>
                    <p class="text-sm text-slate-600">"Precisión y Confianza en Cada Resultado"</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                        <div class="text-sm font-semibold text-indigo-700 mb-1">Dirección</div>
                        <p class="text-slate-700 text-sm">1ra. Calle, Barrio El Centro, San Manuel, Cortés</p>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                        <div class="text-sm font-semibold text-indigo-700 mb-1">Teléfonos</div>
                        <p class="text-slate-700 text-sm">2650-1311 / 1290</p>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                        <div class="text-sm font-semibold text-indigo-700 mb-1">Horario</div>
                        <p class="text-slate-700 text-sm">Lunes a Sábado 7:00 a.m. – 12:00 m.</p>
                    </div>
                </div>
            </div>
        </div>
        -->
        <!-- Contenedor principal -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
            <!-- Header superior -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-4 sm:p-5 text-black">
                <div class="text-center">
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Boleta de Exámenes</h1>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-center gap-1 sm:gap-3 text-sm">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Codigo Expediente: <span class="font-semibold">{{ $expediente->codigo ?? 'N/A' }}</span> ||
                            Codigo Paciente: <span class="font-semibold">{{ $expediente->paciente->codigo_paciente ?? 'N/A' }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Información del Laboratorio -->
            <div class="bg-white p-4 border-b border-slate-200">
                <div class="text-center">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-1 flex items-center justify-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                            📅
                        </span>
                        Fecha de Solicitud
                    </h3>
                    <p class="text-slate-700 font-medium">
                        {{ isset($fechaPrimera) ? \Carbon\Carbon::createFromFormat('d/m/Y', $fechaPrimera)->format('d/m/Y') : now()->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Contenido compacto -->
            <div class="p-4 sm:p-5 space-y-4">
                <!-- Información del Paciente - Paleta índigo -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            👤
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Paciente</h4>
                        <p class="text-slate-800 font-medium truncate text-sm">
                            {{ ($expediente->paciente->persona->nombre ?? 'N/A') . ' ' . ($expediente->paciente->persona->apellido ?? '') }}
                        </p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            🎂
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Edad</h4>
                        <p class="text-slate-800 font-medium text-sm">{{ $edad ?? '—' }} años</p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            📅
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Nacimiento</h4>
                        <p class="text-slate-800 font-medium text-sm">{{ $fecha_nacimiento->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            📞
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Teléfono</h4>
                        <p class="text-slate-800 font-medium text-sm">{{ $expediente->paciente->persona->telefono ?? '—' }}</p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            👨‍⚕️
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Médico</h4>
                        <p class="text-slate-800 font-medium text-sm truncate">{{ $medicoNombre ?: '—' }}</p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 p-3 rounded-xl text-center hover:bg-indigo-100 transition-all duration-200">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-2 bg-white text-indigo-600 rounded-full border border-indigo-200">
                            📋
                        </div>
                        <h4 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-1">Código</h4>
                        <p class="text-slate-800 font-medium text-sm">{{ $expediente->codigo ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Exámenes Solicitados -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3">
                        <h4 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                🔬
                            </span>
                            EXÁMENES SOLICITADOS
                        </h4>
                    </div>
                    <div class="p-4 bg-white">
                        @php
                            // Orden visual de categorías
                            $orden = [
                                'Hematología y Coagulación',
                                'Química Clínica',
                                'Uroanálisis Parasitología',
                                'Microbiología',
                                'Inmunología',
                                'Pruebas Hormonales',
                                'Pruebas Especiales',
                                'Toxicología y Fármacos',
                                'Pruebas de Orina 24 horas',
                                'Misceláneas',
                            ];

                            $categoriasOrdenadas = [];
                            foreach ($orden as $cat) {
                                if (!empty($porCategorias[$cat] ?? [])) {
                                    $categoriasOrdenadas[$cat] = $porCategorias[$cat];
                                }
                            }

                            foreach ($porCategorias ?? [] as $cat => $items) {
                                if (!isset($categoriasOrdenadas[$cat])) {
                                    $categoriasOrdenadas[$cat] = $items;
                                }
                            }
                        @endphp

                        @if (!empty($categoriasOrdenadas))
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach ($categoriasOrdenadas as $categoria => $examenes)
                                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 hover:shadow-md transition-shadow duration-200">
                                        <h3 class="font-semibold text-indigo-700 border-b border-indigo-200 pb-2 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            {{ $categoria }}
                                        </h3>
                                        <ul class="space-y-2">
                                            @foreach ($examenes as $examen)
                                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-white text-indigo-600 rounded-full text-xs mt-0.5">
                                                        •
                                                    </span>
                                                    <span>{{ $examen }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-5xl mb-4 text-slate-300">🔍</div>
                                <p class="text-slate-500 text-lg">No hay exámenes solicitados</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Observaciones 
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3">
                        <h4 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                                📝
                            </span>
                            OBSERVACIONES / INDICACIONES ESPECIALES
                        </h4>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="min-h-32 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                            <div class="text-slate-500 italic">
                                <!-- Espacio para observaciones
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                -->

                <!-- Botones de acción -->
                <div class="pt-4 border-t border-slate-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $user = auth()->user();

                            // Determinar rutas según el rol
                            if ($user->role === 'admin') {
                                $volverRoute = route('admin.expedientes.index');
                                $editarRoute = route('expedientes.editar', $expediente->id_expediente);
                                $imprimirRoute = route('expedientes.examenes.imprimir', $expediente->id_expediente);
                            } elseif ($user->role === 'medico') {
                                $volverRoute = route('expedientes.index');
                                $editarRoute = route('expedientes.editar', $expediente->id_expediente);
                                $imprimirRoute = route('expedientes.examenes.imprimir', $expediente->id_expediente);
                            } else {
                                $volverRoute = url()->previous();
                                $editarRoute = null;
                                $imprimirRoute = null;
                            }
                        @endphp

                        <!-- Botón Volver - Gris -->
                        <a href="{{ $volverRoute }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-600 hover:bg-slate-700 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver
                        </a>

                        <!-- Botón Imprimir - Índigo -->
                        @if($imprimirRoute)
                            <a href="{{ $imprimirRoute }}" target="_blank"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Imprimir
                            </a>
                        @endif

                        <!-- Botón Editar Expediente - Ámbar -->
                        @if ($editarRoute)
                            <a href="{{ $editarRoute }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar Expediente
                            </a>
                        @endif

                        <!-- Botón Agregar Exámenes - Esmeralda -->
                        @if ($user->role === 'medico')
                            <a href="{{ route('medico.expediente.asignar', $expediente->id_expediente) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-3 sm:px-4 py-3 text-sm font-semibold text-white shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Agregar Exámenes
                            </a>
                        @endif
                    </div>

                    <!-- Información de generación -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-slate-500 flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documento generado el {{ now()->format('d/m/Y H:i') }} · Código: EXAM-{{ str_pad($expediente->id_expediente ?? '000000', 6, '0', STR_PAD_LEFT) }}
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
                title: '¿Eliminar boleta de exámenes?',
                html: `
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.928-.833-2.698 0L3.288 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 mb-1">
                            ¿Estás seguro de eliminar esta boleta de exámenes?
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
                        text: 'Boleta de exámenes eliminada exitosamente',
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

        @if (session('success'))
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

        @if (session('error'))
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