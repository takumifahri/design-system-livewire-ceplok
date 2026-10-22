@php
    use CeplokUi\DesignKit\Enums\DrawerNavItemTheme;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'icon' => null,
    'label' => '',
    'active' => false,
    'expanded' => false,
    'collapsible' => false,
    'theme' => 'primary',
    'href' => null,
    'badge' => null,
    'darkMode' => false,
    'buttonAttributes' => [],
])

@php
    $theme = Prop::oneOf(DrawerNavItemTheme::class, $theme, DrawerNavItemTheme::Primary, 'drawer.nav-item', 'theme');
    $active = Prop::bool($active, false, 'drawer.nav-item', 'active');
    $expanded = Prop::bool($expanded, false, 'drawer.nav-item', 'expanded');
    $collapsible = Prop::bool($collapsible, false, 'drawer.nav-item', 'collapsible');
    $darkMode = Prop::bool($darkMode, false, 'drawer.nav-item', 'dark-mode');

    $isExpandable = $collapsible || Content::filled($slot);
    $component = filled($href) ? 'a' : 'button';

    $activeThemeClasses = [
        'primary' => 'bg-blue-50 text-blue-600 font-medium',
        'purple' => 'bg-purple-100/70 text-purple-700 font-medium',
        'blue' => 'bg-blue-50 text-blue-600 font-medium',
        'gray' => 'bg-gray-100 text-gray-900 font-medium',
    ];

    $activeIconClasses = [
        'primary' => 'text-blue-600',
        'purple' => 'text-purple-600',
        'blue' => 'text-blue-600',
        'gray' => 'text-gray-700',
    ];

    $hoverThemeClasses = [
        'primary' => 'hover:bg-blue-50 hover:text-blue-600',
        'purple' => 'hover:bg-purple-100/70 hover:text-purple-700',
        'blue' => 'hover:bg-blue-50 hover:text-blue-600',
        'gray' => 'hover:bg-gray-100/70 hover:text-gray-900',
    ];

    $hoverIconClasses = [
        'primary' => 'group-hover:text-blue-600',
        'purple' => 'group-hover:text-purple-700',
        'blue' => 'group-hover:text-blue-600',
        'gray' => 'group-hover:text-gray-900',
    ];
@endphp

<div
    @if ($isExpandable) x-data="{ expanded: {{ $expanded ? 'true' : 'false' }} }" @endif
    {{ $attributes->class(['w-full']) }}
>
    <{{ $component }}
        @if ($href) href="{{ $href }}" @else type="button" @endif
        @if ($isExpandable) x-on:click="expanded = !expanded" @endif
        {{ (new \Illuminate\View\ComponentAttributeBag($buttonAttributes))->class([
            'group flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-base transition-all text-left font-medium',
            $active
                ? ($darkMode ? 'bg-gray-700 hover:bg-gray-600 text-white' : $activeThemeClasses[$theme])
                : ($darkMode ? 'text-gray-50 hover:bg-gray-700 hover:text-white' : 'text-gray-900 '.$hoverThemeClasses[$theme]),
        ]) }}
    >
        <div class="flex items-center gap-3 min-w-0 flex-1">
            @if ($icon)
                <span
                    @class([
                        'flex w-[18px] h-[18px] shrink-0 items-center justify-center transition-colors',
                        $active
                            ? ($darkMode ? 'text-white' : $activeIconClasses[$theme])
                            : ($darkMode ? 'text-gray-400 group-hover:text-white' : 'text-gray-500 '.$hoverIconClasses[$theme]),
                    ])
                >
                    {!! Content::icon($icon) !!}
                </span>
            @endif
            <span class="truncate">{{ $label }}</span>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            @if (Content::filled($badge))
                {{ $badge }}
            @endif
            @if ($isExpandable)
                <span
                    @class([
                        'flex w-[18px] h-[18px] items-center justify-center transition-all',
                        $active
                            ? ($darkMode ? 'text-white' : $activeIconClasses[$theme])
                            : ($darkMode ? 'text-gray-400 group-hover:text-white' : 'text-gray-500 '.$hoverIconClasses[$theme]),
                    ])
                >
                    <x-ceplok-o-chevron-up x-show="expanded" class="w-[18px] h-[18px]" style="display: none;" />
                    <x-ceplok-o-chevron-down x-show="!expanded" class="w-[18px] h-[18px]" />
                </span>
            @endif
        </div>
    </{{ $component }}>

    @if (Content::filled($slot))
        <div x-show="expanded" class="mt-1 space-y-1 pl-8" style="{{ $expanded ? '' : 'display: none;' }}">
            {{ $slot }}
        </div>
    @endif
</div>
