<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Exámenes al Expediente') }}

            <div class="flex justify-end">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Volver
                </a>
            </div>
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="bg-white p-6">
                <!-- Información del expediente -->
                <div class="text-center mb-8">

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Asignar Exámenes al Expediente</h2>

                    <div class="max-w-3xl mx-auto mt-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="text-sm text-gray-500 font-medium mb-1">Código de Expediente</div>
                                <div class="text-lg font-bold text-blue-600">{{ $expediente->codigo }}</div>
                            </div>

                            <div class="text-center">
                                <div class="text-sm text-gray-500 font-medium mb-1">Paciente</div>
                                <div class="text-lg font-bold text-gray-800">
                                    {{ $expediente->paciente->persona->nombre }}
                                    {{ $expediente->paciente->persona->apellido }}
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-sm text-gray-500 font-medium mb-1">Fecha de Creación</div>
                                <div class="text-lg font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($expediente->fecha_creacion)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Buscador -->
                <div class="max-w-4xl mx-auto mb-8">
                    <form method="GET" action="{{ route('medico.expediente.asignar', $expediente->id_expediente) }}">
                        <div class="bg-white p-6 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Buscar Exámenes</h3>
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del
                                        examen</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                                            placeholder="Escribe para buscar..."
                                            class="pl-10 w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                                    <select name="categoria_id"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Todas las categorías</option>
                                        @foreach ($categorias as $cat)
                                            <option value="{{ $cat->id_categoria }}"
                                                {{ request('categoria_id') == $cat->id_categoria ? 'selected' : '' }}>
                                                {{ $cat->nombre_categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Buscar
                                    </button>
                                    @if (request('buscar') || request('categoria_id'))
                                        <a href="{{ route('medico.expediente.asignar', $expediente->id_expediente) }}"
                                            class="ml-3 bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                                            Limpiar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Controles de selección rápida -->
                <div class="max-w-4xl mx-auto mb-4">
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded border">
                        <span class="text-sm text-gray-600">
                            <span id="count-selected">0</span> examenes seleccionados
                        </span>
                        <div class="flex space-x-2">
                            <button type="button" onclick="seleccionarTodos()"
                                class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200">
                                Seleccionar todos
                            </button>
                            <button type="button" onclick="deseleccionarTodos()"
                                class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200">
                                Deseleccionar todos
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla de exámenes -->
                @if ($examenes->count())
                    <div class="max-w-4xl mx-auto overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="w-12 px-4 py-3 text-left">
                                        <input type="checkbox" id="select-all-checkbox"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            onclick="toggleTodos(this)">
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre del Examen
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Categoría
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($examenes as $ex)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="checkbox" value="{{ $ex->id_examen }}"
                                                class="examen-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    class="text-sm font-medium text-gray-900">{{ $ex->nombre_examen }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $ex->categoria->nombre_categoria ?? 'Sin categoría' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="max-w-4xl mx-auto mt-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Mostrando {{ $examenes->firstItem() }} a {{ $examenes->lastItem() }} de
                                {{ $examenes->total() }} resultados
                            </div>
                            <div class="flex space-x-2">
                                {{ $examenes->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="max-w-4xl mx-auto text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron exámenes</h3>
                        <p class="text-gray-500">Intenta con otros términos de búsqueda o selecciona otra categoría.
                        </p>
                    </div>
                @endif

                <!-- Botones de acción CON AJAX -->
                <div class="max-w-4xl mx-auto mt-8">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-center sm:text-left">
                                <p class="text-sm text-gray-600">Paciente:
                                    <span class="font-medium">{{ $expediente->paciente->persona->nombre }}
                                        {{ $expediente->paciente->persona->apellido }}</span>
                                </p>
                                <p class="text-sm text-gray-600">Expediente:
                                    <span class="font-medium">{{ $expediente->codigo }}</span>
                                </p>
                            </div>

                            <div class="flex space-x-4">
                                <button type="button" onclick="noAsignarExamenes()"
                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                                    No Asignar Exámenes
                                </button>

                                <button type="button" onclick="guardarExamenes('guardar')" id="btn-guardar"
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    Guardar Selección
                                </button>

                                <button type="button" onclick="guardarExamenes('terminar')" id="btn-terminar"
                                    class="px-6 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                    Terminar y Salir
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 text-center text-sm text-gray-500">
                            Usa "Guardar Selección" para seguir agregando exámenes, o "Terminar y Salir" para finalizar.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript FUNCIONAL CON guardarExamenesDinamico -->
    <script>
        // Variables globales
        let seleccionados = new Set();
        const expedienteId = {{ $expediente->id_expediente }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Funciones para selección
        function seleccionarTodos() {
            console.log('Seleccionando todos...');
            const checkboxes = document.querySelectorAll('.examen-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
                seleccionados.add(parseInt(checkbox.value));
            });
            document.getElementById('select-all-checkbox').checked = true;
            actualizarContador();
        }

        function deseleccionarTodos() {
            console.log('Deseleccionando todos...');
            const checkboxes = document.querySelectorAll('.examen-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                seleccionados.delete(parseInt(checkbox.value));
            });
            document.getElementById('select-all-checkbox').checked = false;
            actualizarContador();
        }

        function toggleTodos(source) {
            console.log('Toggle todos:', source.checked);
            const checkboxes = document.querySelectorAll('.examen-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
                const id = parseInt(checkbox.value);
                if (source.checked) {
                    seleccionados.add(id);
                } else {
                    seleccionados.delete(id);
                }
            });
            actualizarContador();
        }

        // Actualizar contador
        function actualizarContador() {
            const count = seleccionados.size;
            document.getElementById('count-selected').textContent = count;

            // Habilitar/deshabilitar botones
            const btnGuardar = document.getElementById('btn-guardar');
            const btnTerminar = document.getElementById('btn-terminar');

            if (count > 0) {
                btnGuardar.disabled = false;
                btnTerminar.disabled = false;
            } else {
                btnGuardar.disabled = true;
                btnTerminar.disabled = true;
            }

            console.log('Contador actualizado:', count);
        }

        // Función principal para guardar con AJAX
        async function guardarExamenes(accion) {
            console.log('Guardar exámenes con acción:', accion);
            console.log('Seleccionados:', Array.from(seleccionados));

            if (seleccionados.size === 0) {
                alert('Por favor, selecciona al menos un examen.');
                return;
            }

            // Confirmación
            const mensaje = accion === 'guardar' ?
                `¿Estás seguro de asignar ${seleccionados.size} examen(es)?` :
                `¿Estás seguro de asignar ${seleccionados.size} examen(es) y terminar?`;

            if (!confirm(mensaje)) {
                return;
            }

            // Preparar datos
            const pacienteId = "{{ $expediente->paciente_id }}";
            const doctorId = "{{ $expediente->doctor_id }}";
            const consultaId = "{{ $expediente->consulta_id }}";

            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('paciente_id', pacienteId);
            formData.append('doctor_id', doctorId);
            formData.append('consulta_id', consultaId);
            formData.append('accion', accion);

            // Agregar exámenes
            seleccionados.forEach(id => {
                formData.append('examenes[]', id);
            });

            // URL de la función guardarExamenesDinamico
            const url = `/medico/expediente/${expedienteId}/asignar-examenes-dinamico`;
            console.log('Enviando a:', url);

            // Mostrar loading
            const btnGuardar = document.getElementById('btn-guardar');
            const btnTerminar = document.getElementById('btn-terminar');
            const btnGuardarText = btnGuardar.innerHTML;
            const btnTerminarText = btnTerminar.innerHTML;

            if (accion === 'guardar') {
                btnGuardar.innerHTML = 'Procesando...';
                btnGuardar.disabled = true;
            } else {
                btnTerminar.innerHTML = 'Procesando...';
                btnTerminar.disabled = true;
            }

            try {
                // Enviar petición AJAX
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                console.log('Respuesta del servidor:', data);

                if (data.ok) {
                    if (accion === 'terminar') {
                        // Redirigir a gestionar expediente
                        window.location.href =
                            "{{ route('medico.expedientes.gestionar', $expediente->id_expediente) }}";
                    } else {
                        // Mostrar éxito y limpiar selección
                        alert(data.message);

                        // Limpiar selección
                        seleccionados.clear();

                        // Desmarcar checkboxes
                        const checkboxes = document.querySelectorAll('.examen-checkbox:checked');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        document.getElementById('select-all-checkbox').checked = false;

                        // Actualizar contador
                        actualizarContador();

                        // Recargar la página para ver cambios
                        window.location.reload();
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar la solicitud. Verifica la consola.');
            } finally {
                // Restaurar botones
                btnGuardar.innerHTML = btnGuardarText;
                btnTerminar.innerHTML = btnTerminarText;
                btnGuardar.disabled = false;
                btnTerminar.disabled = false;
            }
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script cargado - inicializando...');

            // Configurar eventos para checkboxes individuales
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('examen-checkbox')) {
                    const id = parseInt(e.target.value);
                    if (e.target.checked) {
                        seleccionados.add(id);
                    } else {
                        seleccionados.delete(id);
                    }
                    actualizarContador();
                }
            });

            // Inicializar contador
            actualizarContador();

            console.log('Aplicación lista. Seleccionados:', seleccionados.size);

            // Depuración: verificar que todo funciona
            console.log('Para probar en consola:');
            console.log('1. seleccionarTodos() - para seleccionar todos');
            console.log('2. guardarExamenes("guardar") - para guardar');
            console.log('3. guardarExamenes("terminar") - para terminar');
        });

        function noAsignarExamenes() {
            if (confirm('¿Estás seguro de continuar sin asignar exámenes?')) {
                // Redirigir a donde corresponda según tu sistema
                // window.location.href = "{{ route('medico.consulta.form') }}";
                // O simplemente volver atrás:
                window.history.back();
            }
        }
    </script>
</x-app-layout>
