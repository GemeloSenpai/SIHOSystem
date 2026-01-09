@props(['compact' => false])

<th {{ $attributes->class([
    'px-3 text-left whitespace-nowrap',
    'py-1.5' => $compact,
    'py-2' => ! $compact,
]) }}>
    {{ $slot }}
</th>
