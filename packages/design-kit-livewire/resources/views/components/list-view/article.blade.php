@php
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'subject' => null, 'category' => null, 'timestamp' => null, 'title' => '',
    'views' => null, 'comments' => null, 'likes' => null, 'pinned' => true,
    'size' => 'm', 'href' => null, 'darkMode' => false,
])

@php
    $size = match ($size) {
        'sm' => 's', 'default' => 'm', 'lg' => 'l',
        's', 'm', 'l', 'xl' => $size,
        default => 'm',
    };
    $darkMode = Prop::bool($darkMode, false, 'list-view.article', 'dark-mode');
    $sizes = [
        's' => [
            'container' => 'w-full max-w-[410px] min-h-[140px] px-4 pt-3.5 pb-3', 'decoration' => 'pl-5',
            'headerGap' => 'gap-2', 'subject' => 'text-xs font-semibold', 'category' => 'text-xs font-normal',
            'timestamp' => 'text-[11px]', 'divider' => 'mt-3.5 mb-2.5', 'title' => 'text-sm font-semibold leading-snug',
            'titleSpacing' => 'mb-3', 'metricsGap' => 'gap-4', 'metricsText' => 'text-xs font-medium', 'metricIcon' => 'size-3.5',
        ],
        'm' => [
            'container' => 'w-full max-w-[514px] min-h-[159px] pr-6 pt-4 pb-4', 'decoration' => 'pl-[30px]',
            'headerGap' => 'gap-2.5', 'subject' => 'text-sm font-semibold', 'category' => 'text-xs font-normal',
            'timestamp' => 'text-xs', 'divider' => 'mt-4 mb-3', 'title' => 'text-base font-semibold leading-snug',
            'titleSpacing' => 'mb-3.5', 'metricsGap' => 'gap-5.5', 'metricsText' => 'text-sm font-medium', 'metricIcon' => 'size-4',
        ],
        'l' => [
            'container' => 'w-full max-w-[728px] min-h-[149px] pr-5 pt-3 pb-3.5', 'decoration' => 'pl-9',
            'headerGap' => 'gap-3', 'subject' => 'text-sm font-semibold', 'category' => 'text-xs sm:text-sm font-normal',
            'timestamp' => 'text-xs', 'divider' => 'mt-2 mb-3.5', 'title' => 'text-lg font-semibold leading-snug',
            'titleSpacing' => 'mb-4', 'metricsGap' => 'gap-5.5', 'metricsText' => 'text-sm font-medium', 'metricIcon' => 'size-4',
        ],
        'xl' => [
            'container' => 'w-full max-w-[1028px] min-h-[149px] pr-5 pt-3 pb-3.5', 'decoration' => 'pl-9',
            'headerGap' => 'gap-3.5', 'subject' => 'text-sm font-semibold', 'category' => 'text-sm font-normal',
            'timestamp' => 'text-xs sm:text-sm', 'divider' => 'mt-2 mb-4', 'title' => 'text-xl font-semibold leading-snug',
            'titleSpacing' => 'mb-4.5', 'metricsGap' => 'gap-5.5', 'metricsText' => 'text-sm font-medium', 'metricIcon' => 'size-4',
        ],
    ];
    $current = $sizes[$size];
    $tag = filled($href) ? 'a' : 'article';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    {{ $attributes->class([
        'group relative flex flex-col justify-between overflow-hidden rounded-[8px] border-t border-r border-b border-border bg-surface text-content transition-all',
        'data-[theme=dark]:border-gray-600 in-data-[theme=dark]:border-gray-600 data-[theme=dark]:bg-gray-700 in-data-[theme=dark]:bg-gray-700',
        $current['container'], $current['decoration'],
        filled($href) ? 'cursor-pointer hover:border-primary-400/80 hover:shadow-soft active:scale-[0.998]' : '',
    ])->merge($darkMode ? ['data-theme' => 'dark'] : []) }}>
    <span class="absolute inset-y-0 left-0 w-1 bg-primary-700" aria-hidden="true"></span>

    <div>
        <div class="flex items-start justify-between {{ $current['headerGap'] }}">
            <div class="min-w-0 pr-2">
                @if ($subject)<div class="truncate text-content in-data-[theme=dark]:text-gray-100 {{ $current['subject'] }}">{{ $subject }}</div>@endif
            </div>
            <div class="flex shrink-0 items-center gap-2.5 sm:gap-3">
                @if ($category || $timestamp)
                    <div class="text-left">
                        @if ($category)<div class="leading-none text-content-muted in-data-[theme=dark]:text-gray-100 {{ $current['category'] }}">{{ $category }}</div>@endif
                        @if ($timestamp)<div class="mt-1 leading-none text-content-muted in-data-[theme=dark]:text-gray-100 {{ $current['timestamp'] }}">{{ $timestamp }}</div>@endif
                    </div>
                @endif
                @if ($pinned)<span class="flex size-6 shrink-0 items-center justify-center rounded-full border border-primary-100/80 bg-primary-50 text-gray-800 in-data-[theme=dark]:border-primary-800 in-data-[theme=dark]:bg-transparent in-data-[theme=dark]:text-primary-600"><x-ceplok-o-thumbtack class="size-4 shrink-0 -rotate-45" aria-hidden="true" /></span>@endif
            </div>
        </div>

        <div class="w-full border-b border-gray-200 in-data-[theme=dark]:border-gray-500 {{ $current['divider'] }}"></div>
        <h4 class="text-content in-data-[theme=dark]:text-gray-200 {{ $current['title'] }} {{ $current['titleSpacing'] }}">{{ $title }}</h4>
        {{ $slot }}
    </div>

    @if ($views !== null || $comments !== null || $likes !== null)
        <div class="flex items-center justify-end select-none text-content-muted in-data-[theme=dark]:text-gray-300 {{ $current['metricsGap'] }} {{ $current['metricsText'] }}">
            @if ($views !== null)<span class="inline-flex items-center gap-1.5"><x-ceplok-o-eye class="{{ $current['metricIcon'] }} shrink-0" aria-hidden="true" /><span>{{ $views }}</span></span>@endif
            @if ($comments !== null)<span class="inline-flex items-center gap-1.5"><x-ceplok-o-message-caption class="{{ $current['metricIcon'] }} shrink-0" aria-hidden="true" /><span>{{ $comments }}</span></span>@endif
            @if ($likes !== null)<span class="inline-flex items-center gap-1.5"><x-ceplok-o-heart class="{{ $current['metricIcon'] }} shrink-0" aria-hidden="true" /><span>{{ $likes }}</span></span>@endif
        </div>
    @endif
</{{ $tag }}>
