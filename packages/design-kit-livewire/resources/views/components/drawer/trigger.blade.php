@php
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'icon' => null,
    'darkMode' => false,
])

@php
    $darkMode = Prop::bool($darkMode, false, 'drawer.trigger', 'dark-mode');
@endphp

<button
    type="button"
    aria-label="Buka menu drawer"
    {{ $attributes->class([
        'inline-flex shrink-0 items-center justify-center rounded-lg p-1.5 transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600',
        $darkMode ? 'text-gray-200 hover:bg-gray-700 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700',
    ]) }}
>
    @if (Content::filled($slot))
        {{ $slot }}
    @elseif ($icon)
        {!! Content::icon($icon) !!}
    @else
        <x-ceplok-o-bars-from-left class="w-6 h-6" aria-hidden="true" />
    @endif
</button>
