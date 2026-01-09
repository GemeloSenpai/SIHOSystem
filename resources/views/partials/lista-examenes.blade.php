<div class="overflow-x-auto">
    <table class="w-full min-w-[780px] border border-slate-300 border-collapse text-sm">
        <thead class="bg-slate-100 text-slate-800">
            <tr>
                <th class="border px-3 py-2 text-left">Examen</th>
                <th class="border px-3 py-2 text-left">Categoría</th>
                <th class="border px-3 py-2 text-left">Fecha asignación</th>
                <th class="border px-3 py-2 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white text-slate-700">
            @forelse ($examenes as $item)
            <tr class="hover:bg-slate-50">
                <td class="border px-3 py-2 font-medium text-slate-900">
                    {{ $item->examen->nombre_examen ?? '—' }}
                </td>
                <td class="border px-3 py-2">
                    {{ $item->examen->categoria->nombre_categoria ?? 'Sin categoría' }}
                </td>
                <td class="border px-3 py-2">
                    {{ $item->fecha_asignacion ? \Carbon\Carbon::parse($item->fecha_asignacion)->format('d/m/Y H:i') : '—' }}
                </td>
                <td class="border px-3 py-2 text-center">
                    <form method="POST"
                        action="{{ route('expedientes.examenes.destroy', [$expediente->id_expediente, $item->id_examen_medico]) }}"
                        data-confirm="¿Quitar este examen del expediente?"
                        data-examen-id="{{ $item->id_examen_medico }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn-quitar-examen inline-flex items-center rounded-lg bg-rose-100 hover:bg-rose-200 border border-rose-300 px-3 py-1.5 text-xs font-semibold text-black shadow-sm">
                            Quitar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="border px-3 py-4 text-center text-slate-500">
                    No hay exámenes asignados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>