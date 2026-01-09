<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🧪 Panel de Laboratorio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botón principal para acceder a gestión de expedientes -->
            <div class="mb-8 text-center">
                <a href="{{ route('expedientes.index') }}" 
                   class="inline-flex items-center px-6 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 border border-transparent rounded-md font-semibold text-black uppercase tracking-widest hover:from-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Gestionar Expedientes
                </a>
                <p class="mt-3 text-gray-600">
                    Accede a los expedientes para ver e imprimir exámenes de laboratorio
                </p>
            </div>

            <!-- Estadísticas rápidas (opcional) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Exámenes Pendientes</h3>
                            <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Exámenes Completados</h3>
                            <p class="text-3xl font-bold text-green-600 mt-2">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Por Imprimir</h3>
                            <p class="text-3xl font-bold text-purple-600 mt-2">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instrucciones rápidas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Instrucciones de Uso</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">1</span>
                        <p>Haz clic en <strong>"Gestionar Expedientes"</strong> para ver todos los pacientes.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">2</span>
                        <p>En la lista, busca el paciente usando nombre, apellido o DNI.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">3</span>
                        <p>Haz clic en <strong>"Ver"</strong> para acceder a los expedientes del paciente.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">4</span>
                        <p>En cada expediente, podrás ver e imprimir los exámenes médicos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
