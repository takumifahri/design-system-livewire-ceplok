@php
    use CeplokUi\DesignKit\Enums\BreadcrumbBackground;
    use CeplokUi\DesignKit\Enums\BreadcrumbSize;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
    use Illuminate\View\ComponentAttributeBag;
@endphp

{{--
    Breadcrumb — jejak lokasi halaman.

    `items` berisi array: `label` (wajib), `href`, `icon` (markup, mis. hasil
    svg(), atau nama ikon seperti `ceplok-o-user`), dan `attributes` untuk
    atribut tambahan pada tautannya — padanan onClick di React, mis.
    `['wire:navigate' => true]`. Item bernama "Home" otomatis berikon rumah.
    Item terakhir adalah halaman aktif (`aria-current="page"`) dan tidak
    pernah menjadi tautan.

    Seperti kit React, label dibatasi 12 karakter di mobile dan 17 di desktop
    (`lg`); label lengkapnya tetap ada di `title` dan `aria-label`. Bila lebih
    dari dua level tidak muat, plugin Alpine `ceplokBreadcrumb` meringkas level
    tengah menjadi "…" dan mempertahankan item pertama serta halaman aktif.

    Pemisah bawaannya chevron; ganti lewat slot `separator`.
--}}
@props([
    'items' => [],
    'size' => 'base',
    'background' => 'none',
    'separator' => null,
    'darkMode' => false,
])

@php
    $size = Prop::oneOf(BreadcrumbSize::class, $size, BreadcrumbSize::Base, 'breadcrumb', 'size');
    $background = Prop::oneOf(BreadcrumbBackground::class, $background, BreadcrumbBackground::None, 'breadcrumb', 'background');
    $darkMode = Prop::bool($darkMode, false, 'breadcrumb', 'dark-mode');
    $text = ['sm' => 'text-xs', 'base' => 'text-sm'][$size];
    $items = array_values(is_iterable($items) ? collect($items)->all() : []);
    $separator = Content::filled($separator) ? $separator : svg('ceplok-o-chevron-right');

    $mobileMax = 12;
    $desktopMax = 17;
    $truncate = fn (string $label, int $max) => mb_strlen($label) > $max ? mb_substr($label, 0, $max).'…' : $label;
    $entries = array_map(function (array $item) use ($mobileMax, $desktopMax, $truncate) {
        $label = (string) ($item['label'] ?? '');

        return [
            ...$item,
            'label' => $label,
            'mobile' => $truncate($label, $mobileMax),
            'desktop' => $truncate($label, $desktopMax),
            'truncated' => mb_strlen($label) > $mobileMax,
            'resolvedIcon' => $item['icon'] ?? (strtolower($label) === 'home' ? svg('ceplok-s-home', 'size-4') : null),
        ];
    }, $items);

    // Level tengah hanya bisa diringkas bila ada lebih dari dua item.
    $collapsible = count($entries) > 2;
    $hiddenLabels = implode(' / ', array_column(array_slice($entries, 1, -1), 'label'));
@endphp

<nav
    @if ($collapsible) x-data="ceplokBreadcrumb" @endif
    {{ $attributes
        ->class(['relative flex min-w-0 max-w-full items-center', 'rounded-lg bg-breadcrumb-surface px-4 py-3' => $background === 'bg'])
        ->merge(['aria-label' => 'Breadcrumb'] + ($darkMode ? ['data-theme' => 'dark'] : [])) }}
>
    <ol class="flex min-w-0 max-w-full items-center gap-0 overflow-hidden">
        @foreach ($entries as $index => $item)
            @php
                $isLast = $index === count($entries) - 1;
                $isMiddle = $collapsible && $index > 0 && ! $isLast;
            @endphp

            @if ($collapsible && $index === 1)
                <li x-show="collapsed" style="display: none;" class="flex shrink-0 items-center gap-0">
                    <span aria-label="Menu tersembunyi: {{ $hiddenLabels }}" title="{{ $hiddenLabels }}" class="{{ $text }} text-content-muted">…</span>
                    <span aria-hidden="true" class="{{ $text }} shrink-0 text-gray-400">{{ $separator }}</span>
                </li>
            @endif

            <li @if ($isMiddle) x-show="! collapsed" @endif class="flex shrink-0 items-center gap-0">
                @if (! $isLast && filled($item['href'] ?? null))
                    <a
                        {{ (new ComponentAttributeBag($item['attributes'] ?? []))
                            ->class(['flex items-center gap-2 whitespace-nowrap transition-colors', $text, 'text-content-muted hover:text-breadcrumb-link-hover'])
                            ->merge(['href' => $item['href']] + ($item['truncated'] ? ['aria-label' => $item['label'], 'title' => $item['label']] : [])) }}
                    >
                        @if (Content::filled($item['resolvedIcon']))
                            <span class="flex shrink-0 items-center justify-center">{!! Content::icon($item['resolvedIcon']) !!}</span>
                        @endif
                        <span class="lg:hidden">{{ $item['mobile'] }}</span>
                        <span class="hidden lg:inline">{{ $item['desktop'] }}</span>
                    </a>
                @else
                    <span
                        @if ($isLast) aria-current="page" @endif
                        @if ($item['truncated']) aria-label="{{ $item['label'] }}" title="{{ $item['label'] }}" @endif
                        @class(['flex items-center gap-2 whitespace-nowrap', $text, $isLast ? 'font-medium text-breadcrumb-active' : 'text-content-muted'])
                    >
                        @if (Content::filled($item['resolvedIcon']))
                            <span class="flex shrink-0 items-center justify-center">{!! Content::icon($item['resolvedIcon']) !!}</span>
                        @endif
                        <span class="lg:hidden">{{ $item['mobile'] }}</span>
                        <span class="hidden lg:inline">{{ $item['desktop'] }}</span>
                    </span>
                @endif

                @if (! $isLast)
                    <span aria-hidden="true" class="{{ $text }} shrink-0 text-gray-400">{{ $separator }}</span>
                @endif
            </li>
        @endforeach
    </ol>

    @if ($collapsible)
        {{-- Pengukur lebar penuh: seluruh item dengan label yang sama, tidak terlihat. --}}
        <ol x-ref="measure" aria-hidden="true" class="pointer-events-none invisible fixed top-0 left-0 flex w-max items-center gap-0 whitespace-nowrap">
            @foreach ($entries as $index => $item)
                <li class="flex shrink-0 items-center gap-0">
                    <span class="flex items-center gap-2 {{ $text }}">
                        @if (Content::filled($item['resolvedIcon']))
                            <span class="flex shrink-0 items-center justify-center">{!! Content::icon($item['resolvedIcon']) !!}</span>
                        @endif
                        <span class="lg:hidden">{{ $item['mobile'] }}</span>
                        <span class="hidden lg:inline">{{ $item['desktop'] }}</span>
                    </span>
                    @if ($index < count($entries) - 1)
                        <span class="{{ $text }} shrink-0">{{ $separator }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    @endif
</nav>
