@php
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props(['items' => null, 'gap' => 'md', 'size' => 'default', 'platform' => 'desktop', 'decoration' => null, 'darkMode' => false, 'as' => 'div'])

@php
    $gap = in_array($gap, ['sm', 'md', 'lg', 'none'], true) ? $gap : 'md';
    $as = Prop::tag($as, 'div', 'list-view', 'as');
    $darkMode = Prop::bool($darkMode, false, 'list-view', 'dark-mode');
    $gaps = ['none' => 'space-y-0', 'sm' => 'space-y-2', 'md' => 'space-y-3', 'lg' => 'space-y-4'];
@endphp

<{{ $as }} {{ $attributes->class(['w-full', $gaps[$gap]])->merge($darkMode ? ['data-theme' => 'dark'] : []) }}>
    @if (is_array($items))
        @foreach ($items as $item)
            <x-ceplok::list-view.item
                :variant="$item['variant'] ?? 'simple'"
                :size="$item['size'] ?? $size"
                :platform="$item['platform'] ?? $platform"
                :decoration="$item['decoration'] ?? $decoration"
                :dark-mode="$item['darkMode'] ?? $darkMode"
                :title="$item['title'] ?? ''"
                :description="$item['description'] ?? null"
                :metadata="$item['metadata'] ?? []"
                :icon="$item['icon'] ?? null"
                :badge="$item['badge'] ?? null"
                :action-label="$item['actionLabel'] ?? null"
                :action-href="$item['actionHref'] ?? null"
                :action-wire-click="$item['actionWireClick'] ?? null"
                :href="$item['href'] ?? null"
                :subject="$item['subject'] ?? null"
                :category="$item['category'] ?? null"
                :timestamp="$item['timestamp'] ?? null"
                :views="$item['views'] ?? null"
                :comments="$item['comments'] ?? null"
                :likes="$item['likes'] ?? null"
                :pinned="$item['pinned'] ?? true"
            />
        @endforeach
    @else
        {{ $slot }}
    @endif
</{{ $as }}>
