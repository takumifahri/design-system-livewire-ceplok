@php
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'darkMode' => false,
])

@php
    $darkMode = Prop::bool($darkMode, false, 'drawer.body', 'dark-mode');
@endphp

<div
    {{ $attributes->class([
        'min-h-0 flex-1 overflow-y-auto overscroll-y-contain p-5 text-xs',
        $darkMode ? 'text-gray-400' : 'text-gray-600',
    ]) }}
>
    {{ $slot }}
</div>
