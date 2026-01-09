@props(['compact' => false])

<td {{ $attributes->class([
    'px-3 whitespace-nowrap',
    'py-1.5' => $compact,
    'py-2' => ! $compact,
]) }}>
    {{ $slot }}
</td>
