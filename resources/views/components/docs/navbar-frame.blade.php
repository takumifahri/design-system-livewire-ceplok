@props([
    'title',
    'src',
    'width' => 1440,
    'height' => 94,
    'dark' => false,
])

{{-- Navbar diuji pada viewport consumer yang sebenarnya, lalu diperkecil agar muat di docs. --}}
<div
    x-data="{
        scale: 1,
        observer: null,
        fit() { this.scale = Math.min(1, Math.max(0.1, this.$refs.viewport.clientWidth / {{ (int) $width }})) },
        init() {
            this.fit()
            this.observer = new ResizeObserver(() => this.fit())
            this.observer.observe(this.$refs.viewport)
        },
        destroy() { this.observer?.disconnect() },
    }"
    class="space-y-2"
>
    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $title }}</p>
    <div @class([
        'max-w-full overflow-hidden rounded-xl border p-3',
        $dark ? 'border-gray-800 bg-gray-900 dark:border-gray-700' : 'border-border bg-surface-subtle',
    ])>
        <div x-ref="viewport">
            <div class="relative" x-bind:style="{ height: ({{ (int) $height }} * scale) + 'px' }">
                <iframe
                    title="{{ $title }}"
                    src="{{ $src }}"
                    width="{{ $width }}"
                    height="{{ $height }}"
                    class="absolute top-0 left-0 block max-w-none origin-top-left border-0"
                    x-bind:style="`transform: scale(${scale})`"
                ></iframe>
            </div>
        </div>
    </div>
</div>
