@props([
    'title' => null,
])

<div class="ds-card overflow-hidden">
    @if ($title)
        <div class="border-b border-border px-5 py-3">
            <p class="text-sm font-black text-gray-900">{{ $title }}</p>
        </div>
    @endif
    <div class="flex flex-wrap items-center gap-3 bg-surface-subtle p-6">
        {{ $slot }}
    </div>
    @isset($code)
        <pre class="ds-scroll-x overflow-x-auto border-t border-border bg-gray-900 p-5 text-xs leading-6 text-gray-300"><code>{!! trim($code) !!}</code></pre>
    @endisset
</div>
