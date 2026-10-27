@php
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'variant' => 'simple', 'title' => '', 'description' => null, 'metadata' => [],
    'icon' => null, 'iconContainerClass' => null, 'actionLabel' => null,
    'actionHref' => null, 'actionButtonSize' => null, 'actionWireClick' => null, 'href' => null,
    'size' => 'default', 'platform' => 'desktop', 'decoration' => false,
    'badge' => null, 'darkMode' => false, 'subject' => null, 'category' => null,
    'timestamp' => null, 'views' => null, 'comments' => null, 'likes' => null,
    'pinned' => true,
])

@php
    $variant = in_array($variant, ['simple', 'article'], true) ? $variant : 'simple';
    $darkMode = Prop::bool($darkMode, false, 'list-view.item', 'dark-mode');
    $mobile = $platform === 'mobile';
    $hasBadge = Content::filled($badge);

    if ($variant === 'article') {
        $articleSize = match ($size) {
            'sm', 's' => 's', 'lg', 'l' => 'l', 'xl' => 'xl', default => 'm',
        };
    } else {
        $size = match ($size) {
            's', 'sm' => 'sm', 'l', 'lg', 'xl' => 'lg', default => 'default',
        };
    }

    $sizes = [
        'sm' => ['container' => 'px-4 py-3 gap-3 min-h-[63px]', 'decoration' => 'pl-5', 'iconBox' => 'size-8 rounded-lg', 'icon' => 'size-4', 'title' => 'text-sm font-bold', 'description' => 'text-xs', 'button' => 'xs'],
        'default' => ['container' => 'px-5 py-3.5 gap-3.5 sm:gap-4 min-h-[81px]', 'decoration' => 'pl-6 sm:pl-7', 'iconBox' => 'size-10 rounded-lg', 'icon' => 'size-5', 'title' => 'text-base font-bold', 'description' => 'text-sm', 'button' => 's'],
        'lg' => ['container' => 'px-6 py-4.5 gap-4 sm:gap-5 min-h-[103px]', 'decoration' => 'pl-7 sm:pl-8', 'iconBox' => 'size-12 rounded-lg', 'icon' => 'size-6', 'title' => 'text-lg font-bold', 'description' => 'text-sm sm:text-base', 'button' => 'base'],
    ];
    $mobileSize = [
        'container' => $hasBadge ? 'px-3.5 py-3 gap-3 min-h-[88px]' : 'px-3.5 py-2.5 gap-3 min-h-[58px]',
        'decoration' => 'pl-4 sm:pl-4.5', 'iconBox' => 'size-10 rounded-lg', 'icon' => 'size-5',
        'title' => 'text-sm font-bold', 'description' => 'text-xs', 'button' => 'xs',
    ];
    $badgeSizes = [
        'sm' => ['container' => 'px-4 py-3 gap-3 min-h-[88px]', 'decoration' => 'pl-5'],
        'default' => ['container' => 'px-6 py-3.5 gap-3.5 sm:gap-4 min-h-[108px]', 'decoration' => 'pl-7 sm:pl-8'],
        'lg' => ['container' => 'px-8 py-4 gap-4 sm:gap-5 min-h-[134px]', 'decoration' => 'pl-9 sm:pl-10'],
    ];
    $currentSize = $mobile ? $mobileSize : $sizes[$size];
    if (! $mobile && $hasBadge) {
        $currentSize['container'] = $badgeSizes[$size]['container'];
        $currentSize['decoration'] = $badgeSizes[$size]['decoration'];
    }
    $tag = filled($href) ? 'a' : 'article';
@endphp

@if ($variant === 'article')
    <x-ceplok::list-view.article
        :size="$articleSize" :title="$title" :subject="$subject" :category="$category"
        :timestamp="$timestamp" :views="$views" :comments="$comments" :likes="$likes"
        :pinned="$pinned" :href="$href" :dark-mode="$darkMode" {{ $attributes }}
    >{{ $slot }}</x-ceplok::list-view.article>
@else
    <{{ $tag }} @if ($href) href="{{ $href }}" @endif
        {{ $attributes->class([
            'group relative flex items-center justify-between overflow-hidden rounded-lg border border-border bg-surface text-content transition-all',
            'data-[theme=dark]:border-gray-500 in-data-[theme=dark]:border-gray-500 data-[theme=dark]:bg-gray-700 in-data-[theme=dark]:bg-gray-700',
            $currentSize['container'],
            $decoration ? $currentSize['decoration'] : '',
            filled($href) ? 'cursor-pointer hover:border-primary-400/80 hover:shadow-soft active:scale-[0.998]' : '',
        ])->merge($darkMode ? ['data-theme' => 'dark'] : []) }}>
        @if ($decoration)<span class="absolute inset-y-0 left-0 w-1 bg-primary-700 sm:w-1.5" aria-hidden="true"></span>@endif

        <div class="flex min-w-0 flex-1 items-center gap-3.5 sm:gap-4">
            @if ($icon !== false)
                <div @class([
                    'flex shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-700 transition-colors',
                    'in-data-[theme=dark]:border in-data-[theme=dark]:border-gray-700 in-data-[theme=dark]:bg-gray-700 in-data-[theme=dark]:text-gray-200',
                    $currentSize['iconBox'], $iconContainerClass,
                ])>
                    @if ($icon){!! Content::icon($icon) !!}@else<x-ceplok-o-file-lines class="{{ $currentSize['icon'] }} shrink-0" aria-hidden="true" />@endif
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <h4 class="truncate leading-snug text-content in-data-[theme=dark]:text-white {{ $currentSize['title'] }}">{{ $title }}</h4>
                @if (filled($description))<div class="mt-0.5 truncate leading-normal text-content-muted in-data-[theme=dark]:text-gray-400 {{ $currentSize['description'] }}">{{ $description }}</div>@endif
                @if (! empty($metadata))
                    <div class="mt-0.5 flex flex-wrap items-center gap-1.5 leading-normal text-content-muted in-data-[theme=dark]:text-gray-400 {{ $currentSize['description'] }}">
                        @foreach ($metadata as $index => $meta)
                            <span class="inline-flex items-center gap-1.5">@if ($index)<span class="select-none text-content-muted/60 in-data-[theme=dark]:text-gray-400/60" aria-hidden="true">&middot;</span>@endif<span>{{ $meta }}</span></span>
                        @endforeach
                    </div>
                @endif
                @if (Content::filled($badge))
                    <div class="mt-2 flex items-center">@if (is_string($badge))<x-ceplok::badge variant="success" size="sm" :dark-mode="$darkMode">{{ $badge }}</x-ceplok::badge>@else{{ $badge }}@endif</div>
                @endif
                {{ $slot }}
            </div>
        </div>

        @if (filled($actionLabel))
            <div class="flex shrink-0 items-center pl-2">
                @if (filled($actionHref))
                    @if (filled($actionWireClick))
                        <x-ceplok::button :size="$actionButtonSize ?? $currentSize['button']" :href="$actionHref" wire:click="{{ $actionWireClick }}">{{ $actionLabel }}</x-ceplok::button>
                    @else
                        <x-ceplok::button :size="$actionButtonSize ?? $currentSize['button']" :href="$actionHref">{{ $actionLabel }}</x-ceplok::button>
                    @endif
                @else
                    @if (filled($actionWireClick))
                        <x-ceplok::button :size="$actionButtonSize ?? $currentSize['button']" wire:click="{{ $actionWireClick }}">{{ $actionLabel }}</x-ceplok::button>
                    @else
                        <x-ceplok::button :size="$actionButtonSize ?? $currentSize['button']">{{ $actionLabel }}</x-ceplok::button>
                    @endif
                @endif
            </div>
        @endif
    </{{ $tag }}>
@endif
