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
    'darkMode' => false,
    'footer' => null,
])

@php
    $position = Prop::oneOf(DrawerPosition::class, $position, DrawerPosition::Right, 'drawer', 'position');
    $size = Prop::oneOf(DrawerSize::class, $size, DrawerSize::M, 'drawer', 'size');
    $theme = Prop::oneOf(DrawerNavItemTheme::class, $theme, DrawerNavItemTheme::Primary, 'drawer', 'theme');
    $closeOnOverlayClick = Prop::bool($closeOnOverlayClick, true, 'drawer', 'close-on-overlay-click');
    $closeOnEsc = Prop::bool($closeOnEsc, true, 'drawer', 'close-on-esc');
    $darkMode = Prop::bool($darkMode, false, 'drawer', 'dark-mode');

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
@endphp

<dialog
    x-data="ceplokDrawer()"
    x-modelable="open"
    data-initial-open="{{ $open ? 'true' : 'false' }}"
    data-close-on-overlay="{{ $closeOnOverlayClick ? 'true' : 'false' }}"
    data-close-on-esc="{{ $closeOnEsc ? 'true' : 'false' }}"
    x-on:cancel="handleCancel($event)"
    x-on:click="handleClick($event)"
    {{ $attributes
        ->class([
            'm-0 p-0 text-left shadow-2xl backdrop:bg-gray-900/50 open:flex open:flex-col overflow-hidden',
            $darkMode ? 'bg-gray-800 text-gray-300 border-gray-700' : 'bg-surface text-content border-border',
            $positionPlacements[$position],
        ])
        ->merge($darkMode ? ['data-theme' => 'dark'] : []) }}
>
    @if (Content::filled($slot))
        {{ $slot }}
    @else
        @if (filled($eyebrow) || Content::filled($header))
            <x-ceplok::drawer.header :eyebrow="$eyebrow" :dark-mode="$darkMode">
                {{ $header }}
            </x-ceplok::drawer.header>
        @endif

        @if (!empty($items))
            <x-ceplok::drawer.body class="p-3 space-y-1" :dark-mode="$darkMode">
                @foreach ($items as $item)
                    @php
                        $itemTheme = $item['theme'] ?? $theme;
                        $children = $item['children'] ?? [];
                        $hasChildren = !empty($children);
                        $expanded = $item['expanded'] ?? $hasChildren;
                    @endphp
                    <x-ceplok::drawer.nav-item
                        :icon="$item['icon'] ?? null"
                        :label="$item['label'] ?? ''"
                        :active="!empty($item['active'])"
                        :expanded="$expanded"
                        :collapsible="!empty($item['collapsible'])"
                        :theme="$itemTheme"
                        :href="$item['href'] ?? null"
                        :badge="$item['badge'] ?? null"
                        :dark-mode="$darkMode"
                        :button-attributes="$item['attributes'] ?? []"
                    >
                        @if ($hasChildren)
                            @foreach ($children as $sub)
                                <x-ceplok::drawer.sub-item
                                    :label="$sub['label'] ?? ''"
                                    :active="!empty($sub['active'])"
                                    :theme="$sub['theme'] ?? $itemTheme"
                                    :href="$sub['href'] ?? null"
                                    :disabled="!empty($sub['disabled'])"
                                    :dark-mode="$darkMode"
                                />
                            @endforeach
                        @endif
                    </x-ceplok::drawer.nav-item>
                @endforeach
            </x-ceplok::drawer.body>
        @endif

        @if (Content::filled($footer))
            <x-ceplok::drawer.footer :dark-mode="$darkMode">
                {{ $footer }}
            </x-ceplok::drawer.footer>
        @endif
    @endif
</dialog>
