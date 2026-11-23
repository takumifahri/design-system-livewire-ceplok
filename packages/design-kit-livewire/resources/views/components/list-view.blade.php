@php
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'items' => null, 'gap' => 'md', 'size' => 'default', 'platform' => 'desktop', 'decoration' => null, 'darkMode' => false, 'as' => 'div',
    'variant' => null, 'title' => null, 'description' => null, 'metadata' => [], 'icon' => null, 'iconContainerClass' => null,
    'badge' => null, 'actionLabel' => null, 'actionHref' => null, 'actionDisabled' => false, 'actionButtonSize' => null, 'actionWireClick' => null, 'actionClick' => null, 'actionAttributes' => [], 'href' => null,
    'subject' => null, 'category' => null, 'timestamp' => null, 'views' => null, 'comments' => null, 'likes' => null, 'pinned' => true,
])

@php
    $gap = in_array($gap, ['sm', 'md', 'lg', 'none'], true) ? $gap : 'md';
    $as = Prop::tag($as, 'div', 'list-view', 'as');
    $darkMode = Prop::bool($darkMode, false, 'list-view', 'dark-mode');
    $gaps = ['none' => 'space-y-0', 'sm' => 'space-y-2', 'md' => 'space-y-3', 'lg' => 'space-y-4'];
    if ($items === null && $title !== null) {
        $items = [[
            'variant' => $variant ?? 'simple', 'size' => $size, 'platform' => $platform, 'decoration' => $decoration,
            'darkMode' => $darkMode, 'title' => $title, 'description' => $description, 'metadata' => $metadata,
            'icon' => $icon, 'iconContainerClass' => $iconContainerClass, 'badge' => $badge, 'actionLabel' => $actionLabel,
            'actionHref' => $actionHref, 'actionDisabled' => $actionDisabled, 'actionButtonSize' => $actionButtonSize, 'actionWireClick' => $actionWireClick, 'actionClick' => $actionClick, 'actionAttributes' => $actionAttributes,
            'href' => $href, 'subject' => $subject, 'category' => $category, 'timestamp' => $timestamp,
            'views' => $views, 'comments' => $comments, 'likes' => $likes, 'pinned' => $pinned,
        ]];
    }
@endphp

<{{ $as }} {{ $attributes->class(['w-full', $gaps[$gap]])->merge($darkMode ? ['data-theme' => 'dark'] : []) }}>
    @if (is_array($items))
        @foreach ($items as $item)
            @php
                $variant = in_array($item['variant'] ?? 'simple', ['simple', 'article'], true) ? ($item['variant'] ?? 'simple') : 'simple';
                $itemSize = $item['size'] ?? $size;
                $itemDarkMode = Prop::bool($item['darkMode'] ?? $darkMode, false, 'list-view', 'dark-mode');
                $itemPlatform = $item['platform'] ?? $platform;
                $actionDisabled = Prop::bool($item['actionDisabled'] ?? false, false, 'list-view', 'action-disabled');
                $actionAttributes = new \Illuminate\View\ComponentAttributeBag($item['actionAttributes'] ?? []);
                $cardOwnsAction = $variant === 'simple' && $itemPlatform === 'desktop' && $actionDisabled;
                $itemHref = $item['href'] ?? null;

                if ($cardOwnsAction && ! filled($itemHref)) {
                    $itemHref = $item['actionHref'] ?? null;
                }

                $itemTag = filled($itemHref) ? 'a' : 'article';
                $itemAttributes = new \Illuminate\View\ComponentAttributeBag($item['attributes'] ?? []);

                if ($cardOwnsAction) {
                    $itemAttributes = $itemAttributes->merge($actionAttributes->getAttributes());

                    if (filled($item['actionWireClick'] ?? null)) {
                        $itemAttributes = $itemAttributes->merge(['wire:click' => $item['actionWireClick']]);
                    }

                    if (filled($item['actionClick'] ?? null)) {
                        $itemAttributes = $itemAttributes->merge(['x-on:click' => $item['actionClick']]);
                    }
                }

                $itemIsClickable = filled($itemHref) || ($cardOwnsAction && (
                    filled($item['actionWireClick'] ?? null)
                    || filled($item['actionClick'] ?? null)
                    || count($actionAttributes->getAttributes()) > 0
                ));
                $title = $item['title'] ?? '';
                $badge = $item['badge'] ?? null;
                $hasBadge = Content::filled($badge);
                $itemDecoration = $item['decoration'] ?? $decoration;
            @endphp

            @if ($variant === 'article')
                @php
                    $articleSize = match ($itemSize) { 'sm', 's' => 's', 'lg', 'l' => 'l', 'xl' => 'xl', default => 'm' };
                    $articleSizes = [
                        's' => ['container' => 'w-full max-w-[410px] min-h-[140px] px-4 pt-3.5 pb-3', 'decoration' => 'pl-5', 'headerGap' => 'gap-2', 'subject' => 'text-xs font-semibold', 'category' => 'text-xs font-normal', 'timestamp' => 'text-[11px]', 'divider' => 'mt-3.5 mb-2.5', 'title' => 'text-sm font-semibold leading-snug', 'titleSpacing' => 'mb-3', 'metricsGap' => 'gap-4', 'metricsText' => 'text-xs font-medium', 'metricIcon' => 'size-3.5'],
                        'm' => ['container' => 'w-full max-w-[514px] min-h-[159px] pr-6 pt-4 pb-4', 'decoration' => 'pl-[30px]', 'headerGap' => 'gap-2.5', 'subject' => 'text-sm font-semibold', 'category' => 'text-xs font-normal', 'timestamp' => 'text-xs', 'divider' => 'mt-4 mb-3', 'title' => 'text-base font-semibold leading-snug', 'titleSpacing' => 'mb-3.5', 'metricsGap' => 'gap-5.5', 'metricsText' => 'text-sm font-medium', 'metricIcon' => 'size-4'],
                        'l' => ['container' => 'w-full max-w-[728px] min-h-[149px] pr-5 pt-3 pb-3.5', 'decoration' => 'pl-9', 'headerGap' => 'gap-3', 'subject' => 'text-sm font-semibold', 'category' => 'text-xs sm:text-sm font-normal', 'timestamp' => 'text-xs', 'divider' => 'mt-2 mb-3.5', 'title' => 'text-lg font-semibold leading-snug', 'titleSpacing' => 'mb-4', 'metricsGap' => 'gap-5.5', 'metricsText' => 'text-sm font-medium', 'metricIcon' => 'size-4'],
                        'xl' => ['container' => 'w-full max-w-[1028px] min-h-[149px] pr-5 pt-3 pb-3.5', 'decoration' => 'pl-9', 'headerGap' => 'gap-3.5', 'subject' => 'text-sm font-semibold', 'category' => 'text-sm font-normal', 'timestamp' => 'text-xs sm:text-sm', 'divider' => 'mt-2 mb-4', 'title' => 'text-xl font-semibold leading-snug', 'titleSpacing' => 'mb-4.5', 'metricsGap' => 'gap-5.5', 'metricsText' => 'text-sm font-medium', 'metricIcon' => 'size-4'],
                    ];
                    $current = $articleSizes[$articleSize];
                @endphp
                <{{ $itemTag }} @if ($itemHref) href="{{ $itemHref }}" @endif {{ $itemAttributes->class(['group relative flex flex-col justify-between overflow-hidden rounded-[8px] border-t border-r border-b border-border bg-surface text-content transition-all', 'data-[theme=dark]:border-gray-600 in-data-[theme=dark]:border-gray-600 data-[theme=dark]:bg-gray-700 in-data-[theme=dark]:bg-gray-700', $current['container'], $current['decoration'], filled($itemHref) ? 'cursor-pointer hover:border-primary-400/80 hover:shadow-soft active:scale-[0.998]' : ''])->merge($itemDarkMode ? ['data-theme' => 'dark'] : []) }}>
                    <span class="absolute inset-y-0 left-0 w-1 bg-primary-700" aria-hidden="true"></span>
                    <div><div class="flex items-start justify-between {{ $current['headerGap'] }}"><div class="min-w-0 pr-2">@if (!empty($item['subject']))<div class="truncate text-content in-data-[theme=dark]:text-gray-100 {{ $current['subject'] }}">{{ $item['subject'] }}</div>@endif</div><div class="flex shrink-0 items-center gap-2.5 sm:gap-3">@if (!empty($item['category']) || !empty($item['timestamp']))<div class="text-left">@if (!empty($item['category']))<div class="leading-none text-content-muted in-data-[theme=dark]:text-gray-100 {{ $current['category'] }}">{{ $item['category'] }}</div>@endif @if (!empty($item['timestamp']))<div class="mt-1 leading-none text-content-muted in-data-[theme=dark]:text-gray-100 {{ $current['timestamp'] }}">{{ $item['timestamp'] }}</div>@endif</div>@endif @if ($item['pinned'] ?? true)<span class="flex size-6 shrink-0 items-center justify-center rounded-full border border-primary-100/80 bg-primary-50 text-gray-800 in-data-[theme=dark]:border-primary-800 in-data-[theme=dark]:bg-transparent in-data-[theme=dark]:text-primary-600"><x-ceplok-o-thumbtack class="size-4 shrink-0 -rotate-45" aria-hidden="true" /></span>@endif</div></div><div class="w-full border-b border-gray-200 in-data-[theme=dark]:border-gray-500 {{ $current['divider'] }}"></div><h4 class="text-content in-data-[theme=dark]:text-gray-200 {{ $current['title'] }} {{ $current['titleSpacing'] }}">{{ $title }}</h4></div>
                    @if (isset($item['views']) || isset($item['comments']) || isset($item['likes']))<div class="flex items-center justify-end select-none text-content-muted in-data-[theme=dark]:text-gray-300 {{ $current['metricsGap'] }} {{ $current['metricsText'] }}">@if (isset($item['views']))<span class="inline-flex items-center gap-1.5"><x-ceplok-o-eye class="{{ $current['metricIcon'] }} shrink-0" /><span>{{ $item['views'] }}</span></span>@endif @if (isset($item['comments']))<span class="inline-flex items-center gap-1.5"><x-ceplok-o-message-caption class="{{ $current['metricIcon'] }} shrink-0" /><span>{{ $item['comments'] }}</span></span>@endif @if (isset($item['likes']))<span class="inline-flex items-center gap-1.5"><x-ceplok-o-heart class="{{ $current['metricIcon'] }} shrink-0" /><span>{{ $item['likes'] }}</span></span>@endif</div>@endif
                </{{ $itemTag }}>
            @else
                @php
                    $simpleSize = match ($itemSize) { 's', 'sm' => 'sm', 'l', 'lg', 'xl' => 'lg', default => 'default' };
                    $mobile = $itemPlatform === 'mobile';
                    $simpleSizes = ['sm' => ['container' => 'px-4 py-3 gap-3 min-h-[63px]', 'decoration' => 'pl-5', 'iconBox' => 'size-8 rounded-lg', 'icon' => 'size-4', 'title' => 'text-sm font-bold', 'description' => 'text-xs', 'button' => 'xs'], 'default' => ['container' => 'px-5 py-3.5 gap-3.5 sm:gap-4 min-h-[81px]', 'decoration' => 'pl-6 sm:pl-7', 'iconBox' => 'size-10 rounded-lg', 'icon' => 'size-5', 'title' => 'text-base font-bold', 'description' => 'text-sm', 'button' => 's'], 'lg' => ['container' => 'px-6 py-4.5 gap-4 sm:gap-5 min-h-[103px]', 'decoration' => 'pl-7 sm:pl-8', 'iconBox' => 'size-12 rounded-lg', 'icon' => 'size-6', 'title' => 'text-lg font-bold', 'description' => 'text-sm sm:text-base', 'button' => 'base']];
                    $current = $mobile ? ['container' => $hasBadge ? 'px-3.5 py-3 gap-3 min-h-[88px]' : 'px-3.5 py-2.5 gap-3 min-h-[58px]', 'decoration' => 'pl-4 sm:pl-4.5', 'iconBox' => 'size-10 rounded-lg', 'icon' => 'size-5', 'title' => 'text-sm font-bold', 'description' => 'text-xs', 'button' => 'xs'] : $simpleSizes[$simpleSize];
                    if (! $mobile && $hasBadge) {
                        $desktopBadgeSizes = [
                            'sm' => ['container' => 'px-4 py-3 gap-3 min-h-[88px]', 'decoration' => 'pl-5'],
                            'default' => ['container' => 'px-6 py-3.5 gap-3.5 sm:gap-4 min-h-[108px]', 'decoration' => 'pl-7 sm:pl-8'],
                            'lg' => ['container' => 'px-8 py-4 gap-4 sm:gap-5 min-h-[134px]', 'decoration' => 'pl-9 sm:pl-10'],
                        ];
                        $current['container'] = $desktopBadgeSizes[$simpleSize]['container'];
                        $current['decoration'] = $desktopBadgeSizes[$simpleSize]['decoration'];
                    }
                    if (! $cardOwnsAction && filled($item['actionClick'] ?? null)) {
                        $actionAttributes = $actionAttributes->merge([
                            'x-on:click' => $item['actionClick'],
                        ]);
                    }
                    // Tautan aksi yang dinonaktifkan tidak bisa memakai `disabled`, jadi dikeluarkan dari urutan
                    // fokus. Atributnya dipasang di sini karena direktif Blade di dalam tag komponen tidak dikompilasi.
                    if (! $cardOwnsAction && $actionDisabled && filled($item['actionHref'] ?? null)) {
                        $actionAttributes = $actionAttributes->merge([
                            'aria-disabled' => 'true',
                            'tabindex' => '-1',
                        ]);
                    }

                @endphp
                <{{ $itemTag }} @if ($itemHref) href="{{ $itemHref }}" @endif {{ $itemAttributes->class(['group relative flex items-center justify-between overflow-hidden rounded-lg border border-border bg-surface text-content transition-all', 'data-[theme=dark]:border-gray-500 in-data-[theme=dark]:border-gray-500 data-[theme=dark]:bg-gray-700 in-data-[theme=dark]:bg-gray-700', $current['container'], $itemDecoration ? $current['decoration'] : '', $itemIsClickable ? 'cursor-pointer hover:border-primary-400/80 hover:shadow-soft active:scale-[0.998]' : ''])->merge($itemDarkMode ? ['data-theme' => 'dark'] : []) }}>
                    @if ($itemDecoration)<span class="absolute inset-y-0 left-0 w-1 bg-primary-700 sm:w-1.5" aria-hidden="true"></span>@endif
                    <div class="flex min-w-0 flex-1 items-center gap-3.5 sm:gap-4">@if (($item['icon'] ?? null) !== false)<div @class(['flex shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-700 transition-colors', 'in-data-[theme=dark]:border in-data-[theme=dark]:border-gray-700 in-data-[theme=dark]:bg-gray-700 in-data-[theme=dark]:text-gray-200', $current['iconBox'], $item['iconContainerClass'] ?? null])>@if (!empty($item['icon'])){!! Content::icon($item['icon']) !!}@else<x-ceplok-o-file-lines class="{{ $current['icon'] }} shrink-0" aria-hidden="true" />@endif</div>@endif<div class="min-w-0 flex-1"><h4 class="truncate leading-snug text-content in-data-[theme=dark]:text-white {{ $current['title'] }}">{{ $title }}</h4>@if (filled($item['description'] ?? null))<div class="mt-0.5 truncate leading-normal text-content-muted in-data-[theme=dark]:text-gray-400 {{ $current['description'] }}">{{ $item['description'] }}</div>@endif @if (!empty($item['metadata']))<div class="mt-0.5 flex flex-wrap items-center gap-1.5 leading-normal text-content-muted in-data-[theme=dark]:text-gray-400 {{ $current['description'] }}">@foreach ($item['metadata'] as $index => $meta)<span class="inline-flex items-center gap-1.5">@if ($index)<span class="select-none text-content-muted/60 in-data-[theme=dark]:text-gray-400/60" aria-hidden="true">&middot;</span>@endif<span>{{ $meta }}</span></span>@endforeach</div>@endif @if ($hasBadge)<div class="mt-2 flex items-center">@if (is_string($badge))<x-ceplok::badge variant="success" size="sm" :dark-mode="$itemDarkMode">{{ $badge }}</x-ceplok::badge>@else{{ $badge }}@endif</div>@endif</div></div>
                    @if (! $cardOwnsAction && filled($item['actionLabel'] ?? null))<div class="flex shrink-0 items-center pl-2"><x-ceplok::button :size="$item['actionButtonSize'] ?? $current['button']" :href="$item['actionHref'] ?? null" :wire-click="$item['actionWireClick'] ?? null" :disabled="! filled($item['actionHref'] ?? null) && $actionDisabled" @class(['pointer-events-none opacity-50' => $actionDisabled && filled($item['actionHref'] ?? null)]) :attributes="$actionAttributes">{{ $item['actionLabel'] }}</x-ceplok::button></div>@endif
                </{{ $itemTag }}>
            @endif
        @endforeach
    @else
        {{ $slot }}
    @endif
</{{ $as }}>
