@php
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'darkMode' => false,
    'id' => null,
])

@php
    $darkMode = Prop::bool($darkMode, false, 'drawer.title', 'dark-mode');
@endphp

<h2
    @if (filled($id)) id="{{ $id }}" @endif
    {{ $attributes->class([
        'text-base font-bold',
        $darkMode ? 'text-white' : 'text-gray-900',
    ]) }}
>
    {{ $slot }}
</h2>
