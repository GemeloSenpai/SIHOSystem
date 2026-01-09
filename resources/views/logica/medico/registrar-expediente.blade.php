<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🗂️ Generar Expediente Médico
        </h2>
    </x-slot>

    {{-- Notificaciones dinámicas --}}
    @if (session('success'))
        <script>
            if (window.Swal) {
                Swal.fire({
                    title: '¡Listo!',
                    text: @json(session('success')),
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    timer: 2200,
                    showConfirmButton: false
                });
            }
        </script>
    @endif

    @if ($errors->any())
        <script>
            if (window.Swal) {
                Swal.fire({
                    title: 'Hay errores en el formulario',
                    html: @json(implode('<br>', $errors->all())),
                    icon: 'error'
                });
            }
        </script>
        {{-- Fallback visible si no carga SweetAlert2 --}}
        <div class="mx-auto mt-6 max-w-5xl rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-slate-200" x-data="{ sending: false }">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800">Datos del expediente</h3>
                    <p class="text-sm text-slate-500">Completa la información. Los campos marcados con <span class="text-rose-600">*</span> son obligatorios.</p>
                </div>

                <form action="{{ route('medico.expediente.guardar') }}" method="POST" @submit="sending = true">
                    @csrf

                    {{-- Ocultos para enlace lógico --}}
                    <input type="hidden" name="paciente_id" value="{{ $paciente_id }}">
                    <input type="hidden" name="doctor_id" value="{{ $doctor_id }}">
                    <input type="hidden" name="signos_vitales_id" value="{{ $signos_vitales_id }}">
                    <input type="hidden" name="consulta_id" value="{{ $consulta_id }}">
                    <input type="hidden" name="encargado_id" value="{{ $encargado_id }}">
                    <input type="hidden" name="enfermera_id" value="{{ $enfermera_id }}">

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Motivo de Ingreso <span class="text-rose-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="motivo_ingreso"
                                value="{{ old('motivo_ingreso') }}"
                                required
                                placeholder="Ej. Dolor abdominal, control, etc."
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Diagnóstico <span class="text-rose-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="diagnostico"
                                value="{{ old('diagnostico') }}"
                                required
                                placeholder="Ej. Gastroenteritis aguda"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Observaciones</label>
                            <textarea
                                name="observaciones"
                                rows="3"
                                placeholder="Notas adicionales del caso (opcional)"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('observaciones') }}</textarea>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Estado <span class="text-rose-600">*</span>
                            </label>
                            <select
                                name="estado"
                                required
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="abierto" {{ old('estado') === 'abierto' ? 'selected' : '' }}>Abierto</option>
                                <option value="cerrado" {{ old('estado') === 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                            </select>
                            <p class="mt-1 text-xs text-slate-500">“Abierto” si el proceso clínico sigue activo.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('medico.consulta.form') }}"
                           class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                            Cancelar
                        </a>

                        <button type="submit"
                                :disabled="sending"
                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2 text-sm font-semibold text-black shadow-sm hover:bg-indigo-700 disabled:opacity-60">
                            <span x-show="!sending">Generar Expediente</span>
                            <span x-show="sending">Guardando…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
