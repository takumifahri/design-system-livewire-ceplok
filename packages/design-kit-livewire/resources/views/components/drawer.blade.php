@php
    use CeplokUi\DesignKit\Enums\DrawerNavItemTheme;
    use CeplokUi\DesignKit\Enums\DrawerPosition;
    use CeplokUi\DesignKit\Enums\DrawerSize;
    use CeplokUi\DesignKit\Support\Bound;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

{{--
    Drawer — panel off-canvas interaktif berbasis elemen <dialog> native
    (Side Sheet). Muncul mengambang dari tepi layar (kanan, kiri, atas,
    atau bawah) untuk navigasi, filter, atau konten sekunder.

    Komponen ini padanan Drawer di kit React (@ceplok-ui/design-kit-react).
    Bisa dikendalikan lewat `wire:model`, `x-model`, atau prop `:open`.
    Menutup saat tombol tutup diklik, backdrop ditekan (bila `close-on-overlay-click`),
    atau tombol Esc ditekan (bila `close-on-esc`).
--}}
@props([
    'open' => null,
    'position' => 'right',
    'size' => 'm',
    'closeOnOverlayClick' => true,
    'closeOnEsc' => true,
    'eyebrow' => null,
    'header' => null,
    'items' => null,
    'theme' => 'primary',
    'font' => 'lato',
    'darkMode' => false,
    'footer' => null,
    'preview' => false,
])

@php
    $position = Prop::oneOf(DrawerPosition::class, $position, DrawerPosition::Right, 'drawer', 'position');
    $size = Prop::oneOf(DrawerSize::class, $size, DrawerSize::M, 'drawer', 'size');
    $theme = Prop::oneOf(DrawerNavItemTheme::class, $theme, DrawerNavItemTheme::Primary, 'drawer', 'theme');
    $font = in_array($font, ['lato', 'inter', 'arial'], true) ? $font : 'lato';
    $closeOnOverlayClick = Prop::bool($closeOnOverlayClick, true, 'drawer', 'close-on-overlay-click');
    $closeOnEsc = Prop::bool($closeOnEsc, true, 'drawer', 'close-on-esc');
    $darkMode = Prop::bool($darkMode, false, 'drawer', 'dark-mode');
    $preview = Prop::bool($preview, false, 'drawer', 'preview');

    $bound = Bound::has($attributes);
    $open = $open === null
        ? ($bound ? Bound::bool($attributes, false) : false)
        : Prop::bool($open, false, 'drawer', 'open');

    $sideSizes = [
        's' => 'w-[320px] max-w-[calc(100vw-2rem)]',
        'm' => 'w-[440px] max-w-[calc(100vw-2rem)]',
        'l' => 'w-[600px] max-w-[calc(100vw-2rem)]',
        'xl' => 'w-[800px] max-w-[calc(100vw-2rem)]',
        'full' => 'w-screen max-w-full',
    ];

    $verticalSizes = [
        's' => 'h-[260px] max-h-[calc(100vh-2rem)]',
        'm' => 'h-[380px] max-h-[calc(100vh-2rem)]',
        'l' => 'h-[540px] max-h-[calc(100vh-2rem)]',
        'xl' => 'h-[720px] max-h-[calc(100vh-2rem)]',
        'full' => 'h-screen max-h-full',
    ];

    $positionPlacements = [
        'right' => 'fixed inset-y-0 right-0 left-auto h-full max-h-full border-l rounded-none ' . $sideSizes[$size],
        'left' => 'fixed inset-y-0 left-0 right-auto h-full max-h-full border-r rounded-none ' . $sideSizes[$size],
        'top' => 'fixed inset-x-0 top-0 bottom-auto w-full max-w-full border-b rounded-none ' . $verticalSizes[$size],
        'bottom' => 'fixed inset-x-0 bottom-0 top-auto w-full max-w-full border-t rounded-none ' . $verticalSizes[$size],
    ];
    $panelTag = $preview ? 'div' : 'dialog';
    $panelPlacement = $preview
        ? 'absolute inset-y-0 right-0 h-full w-3/5 border-l rounded-none'
        : $positionPlacements[$position];
    $fontClass = ['lato' => 'font-sans', 'inter' => 'font-alt', 'arial' => 'font-alt-2'][$font];
@endphp

<{{ $panelTag }}
    x-data="ceplokDrawer()"
    x-modelable="open"
    data-initial-open="{{ ($preview || $open) ? 'true' : 'false' }}"
    data-close-on-overlay="{{ $closeOnOverlayClick ? 'true' : 'false' }}"
    data-close-on-esc="{{ $closeOnEsc ? 'true' : 'false' }}"
    x-on:cancel="handleCancel($event)"
    x-on:click="handleClick($event)"
    @if ($preview) x-show="open" @endif
    {{ $attributes
        ->class([
            'm-0 overflow-hidden p-0 text-left shadow-2xl backdrop:bg-gray-900/50',
            $fontClass,
            $preview ? 'flex flex-col' : 'open:flex open:flex-col',
            $darkMode ? 'bg-gray-800 text-gray-300 border-gray-700' : 'bg-surface text-content border-border',
            $panelPlacement,
        ])
        ->merge([
            ...($darkMode ? ['data-theme' => 'dark'] : []),
            ...($preview ? ['data-preview' => 'true'] : []),
        ]) }}
>
    @if (Content::filled($slot))
        {{ $slot }}
    @else
        @if (filled($eyebrow) || Content::filled($header))
            <div class="flex shrink-0 items-center justify-between gap-4 px-5 pt-4 pb-2">
                <div class="min-w-0 flex-1 space-y-1">
                    @if (filled($eyebrow))<span class="block text-base font-semibold tracking-wider uppercase text-gray-500">{{ $eyebrow }}</span>@endif
                    @if (Content::filled($header)){{ $header }}@endif
                </div>
                <button type="button" x-on:click="close()" aria-label="Tutup drawer" @class(['-mr-2 ml-auto inline-flex size-8 shrink-0 items-center justify-center rounded-lg transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600', $darkMode ? 'text-gray-500 hover:bg-gray-700 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'])><x-ceplok-o-close class="w-[18px] h-[18px]" aria-hidden="true" /></button>
            </div>
        @endif

        @if (!empty($items))
            <div @class(['min-h-0 flex-1 overflow-y-auto overscroll-y-contain space-y-1 p-3 text-xs', $darkMode ? 'text-gray-400' : 'text-gray-600'])>
                @foreach ($items as $item)
                    @php
                        $itemTheme = $item['theme'] ?? $theme;
                        $children = $item['children'] ?? [];
                        $hasChildren = !empty($children);
                        $expanded = $item['expanded'] ?? $hasChildren;
                        $active = !empty($item['active']);
                        $href = $item['href'] ?? null;
                        $disabled = !empty($item['disabled']);
                        $itemTag = filled($href) ? 'a' : 'button';
                        $activeClass = $darkMode ? 'bg-gray-700 hover:bg-gray-600 text-white' : match ($itemTheme) { 'purple' => 'bg-purple-100/70 text-purple-700 hover:bg-purple-100/70 hover:text-purple-700', 'gray' => 'bg-gray-100 text-gray-900 hover:bg-gray-100 hover:text-gray-900', default => 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' };
                        $inactiveClass = $darkMode ? 'text-gray-50 hover:bg-gray-700 hover:text-white' : match ($itemTheme) { 'purple' => 'text-gray-900 hover:bg-purple-100/70 hover:text-purple-700', 'gray' => 'text-gray-900 hover:bg-gray-100 hover:text-gray-900', default => 'text-gray-900 hover:bg-blue-50 hover:text-blue-600' };
                        $inactiveIconClass = $darkMode ? 'text-gray-400 group-hover:text-white' : match ($itemTheme) { 'purple' => 'text-gray-500 group-hover:text-purple-700', 'gray' => 'text-gray-500 group-hover:text-gray-900', default => 'text-gray-500 group-hover:text-blue-600' };
                        $activeIconClass = $darkMode ? 'text-white' : match ($itemTheme) { 'purple' => 'text-purple-600', 'gray' => 'text-gray-700', default => 'text-blue-600' };
                        $itemAttributes = new \Illuminate\View\ComponentAttributeBag($item['attributes'] ?? []);
                        if ($hasChildren && ! $disabled) {
                            $itemAttributes = $itemAttributes->merge(['x-on:click' => 'expanded = !expanded']);
                        } elseif (! $disabled) {
                            $navigateClick = "close(); \$dispatch('drawer-navigate', { label: ".json_encode($item['label'] ?? '').' })';
                            $itemAttributes = $itemAttributes->merge(['x-on:click' => $navigateClick]);
                        }
                    @endphp
                    <div x-data="{ expanded: {{ $expanded ? 'true' : 'false' }} }" class="w-full">
                        <{{ $itemTag }}
                            @if ($href && !$disabled) href="{{ $href }}" @elseif (!$href) type="button" @endif
                            @if ($disabled && !$href) disabled @endif
                            @if ($disabled) aria-disabled="true" tabindex="-1" @endif
                            {{ $itemAttributes->class(['group flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-base transition-all text-left font-medium disabled:cursor-not-allowed disabled:opacity-50 aria-disabled:cursor-not-allowed aria-disabled:opacity-50', $active ? $activeClass : $inactiveClass]) }}
                        >
                            <span class="flex min-w-0 flex-1 items-center gap-3">@if (!empty($item['icon']))<span @class(['flex size-[18px] shrink-0 items-center justify-center transition-colors', $active ? $activeIconClass : $inactiveIconClass])>{!! Content::icon($item['icon']) !!}</span>@endif<span class="truncate">{{ $item['label'] ?? '' }}</span></span>
                            <span @class(['flex shrink-0 items-center gap-2', $active ? $activeIconClass : $inactiveIconClass])>@if (!empty($item['badge'])){{ $item['badge'] }}@endif @if ($hasChildren)<x-ceplok-o-chevron-down x-bind:class="expanded ? 'rotate-180' : ''" class="size-[18px] transition-transform" />@endif</span>
                        </{{ $itemTag }}>
                        @if ($hasChildren)
                            <div x-show="expanded" class="mt-1 space-y-1 pl-8" @if (!$expanded) style="display:none" @endif>
                                @foreach ($children as $sub)
                                    @php
                                        $subHref = $sub['href'] ?? null;
                                        $subDisabled = !empty($sub['disabled']);
                                        $subTag = filled($subHref) ? 'a' : 'button';
                                        $subTheme = $sub['theme'] ?? $itemTheme;
                                        $subActiveClass = match ($subTheme) { 'purple' => 'bg-purple-100/70 text-purple-700 hover:bg-purple-100/70 hover:text-purple-700', 'gray' => 'bg-gray-100 text-gray-900 hover:bg-gray-100 hover:text-gray-900', default => 'bg-blue-50 text-blue-600 hover:bg-blue-50 hover:text-blue-600' };
                                        $subHoverClass = match ($subTheme) { 'purple' => 'hover:bg-purple-100/70 hover:text-purple-700', 'gray' => 'hover:bg-gray-100 hover:text-gray-900', default => 'hover:bg-blue-50 hover:text-blue-600' };
                                        $subAttributes = new \Illuminate\View\ComponentAttributeBag($sub['attributes'] ?? []);
                                        if (! $subDisabled) {
                                            $subNavigateClick = "close(); \$dispatch('drawer-navigate', { label: ".json_encode($sub['label'] ?? '').' })';
                                            $subAttributes = $subAttributes->merge(['x-on:click' => $subNavigateClick]);
                                        }
                                    @endphp
                                    <{{ $subTag }}
                                        @if ($subHref && !$subDisabled) href="{{ $subHref }}" @elseif (!$subHref) type="button" @endif
                                        @if ($subDisabled && !$subHref) disabled @endif
                                        @if ($subDisabled) aria-disabled="true" tabindex="-1" @endif
                                        {{ $subAttributes->class(['flex w-full items-center gap-2 rounded-lg px-3 py-2 text-base text-left transition-colors font-medium disabled:cursor-not-allowed disabled:opacity-50 aria-disabled:cursor-not-allowed aria-disabled:opacity-50', !empty($sub['active']) ? ($darkMode ? 'bg-gray-700/50 text-white' : $subActiveClass) : ($darkMode ? 'text-gray-50 hover:bg-gray-700 hover:text-white' : 'text-gray-900 '.$subHoverClass)]) }}
                                    ><span class="min-w-0 truncate">{{ $sub['label'] ?? '' }}</span></{{ $subTag }}>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if (Content::filled($footer))
            <div @class(['flex shrink-0 flex-wrap items-center justify-end gap-2 border-t px-5 py-3', $darkMode ? 'border-gray-700' : 'border-border'])>{{ $footer }}</div>
        @endif
    @endif
</{{ $panelTag }}>
