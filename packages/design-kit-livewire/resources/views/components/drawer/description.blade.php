@php
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'darkMode' => false,
    'id' => null,
])

@php
    $darkMode = Prop::bool($darkMode, false, 'drawer.description', 'dark-mode');
@endphp

<p
    @if (filled($id)) id="{{ $id }}" @endif
    {{ $attributes->class([
        'text-xs',
        $darkMode ? 'text-gray-400' : 'text-gray-500',
    ]) }}
>
    {{ $slot }}
</p>
