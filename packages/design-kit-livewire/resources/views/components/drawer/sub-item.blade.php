@php
    use CeplokUi\DesignKit\Enums\DrawerNavItemTheme;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'label' => '',
    'active' => false,
    'theme' => 'primary',
    'href' => null,
    'darkMode' => false,
])

@php
    $theme = Prop::oneOf(DrawerNavItemTheme::class, $theme, DrawerNavItemTheme::Primary, 'drawer.sub-item', 'theme');
    $active = Prop::bool($active, false, 'drawer.sub-item', 'active');
    $darkMode = Prop::bool($darkMode, false, 'drawer.sub-item', 'dark-mode');
    $component = filled($href) ? 'a' : 'button';

    $subItemHoverClasses = [
        'primary' => 'hover:bg-blue-50 hover:text-blue-600',
        'purple' => 'hover:bg-purple-100/70 hover:text-purple-700',
        'blue' => 'hover:bg-blue-50 hover:text-blue-600',
        'gray' => 'hover:bg-gray-100/70 hover:text-gray-900',
    ];

    $activeLightClass = match ($theme) {
        'purple' => 'text-purple-700 bg-purple-50/70',
        'gray' => 'text-gray-900 bg-gray-100',
        default => 'text-blue-600 bg-blue-50/70',
    };
@endphp

<{{ $component }}
    @if ($href) href="{{ $href }}" @else type="button" @endif
    {{ $attributes->class([
        'flex w-full items-center gap-2 rounded-lg px-3 py-2 text-base text-left transition-colors font-medium',
        $darkMode
            ? ($active ? 'bg-gray-700/50 hover:bg-gray-600 text-white' : 'text-gray-50 hover:bg-gray-700 hover:text-white')
            : ($subItemHoverClasses[$theme] . ' ' . ($active ? $activeLightClass : 'text-gray-900')),
    ]) }}
>
    {{ filled($label) ? $label : $slot }}
</{{ $component }}>
