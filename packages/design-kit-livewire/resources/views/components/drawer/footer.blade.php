@php
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'darkMode' => false,
])

@php
    $darkMode = Prop::bool($darkMode, false, 'drawer.footer', 'dark-mode');
@endphp

<div
    {{ $attributes->class([
        'flex shrink-0 flex-wrap items-center justify-end gap-2 border-t px-5 py-3',
        $darkMode ? 'border-gray-700' : 'border-border',
    ]) }}
>
    {{ $slot }}
</div>
