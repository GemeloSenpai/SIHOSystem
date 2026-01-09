@props([
    // UI toggles
    'sticky' => true,
    'compact' => false,
    'hover' => true,
    // Color del encabezado (clases Tailwind)
    'headerClass' => 'bg-indigo-600 text-white',
    // Ancho mínimo para forzar scroll horizontal en móviles
    'minWidth' => 'min-w-[980px]',
])

<div {{ $attributes->merge(['class' => 'w-full overflow-x-auto rounded-2xl shadow bg-white']) }}>
    <table class="w-full {{ $minWidth }} text-sm">
        <thead class="{{ $headerClass }} uppercase text-xs font-bold {{ $sticky ? 'sticky top-0 z-10' : '' }}">
            {{ $head ?? '' }}
        </thead>

        <tbody class="divide-y divide-slate-100">
            {{ $slot }}
        </tbody>
    </table>
</div>

{{-- Helpers para celdas opcionales --}}
@once
    @push('styles')
        <style>
            /* Suave desplazamiento horizontal en móviles */
            .overflow-x-auto { -webkit-overflow-scrolling: touch; }
        </style>
    @endpush
@endonce
