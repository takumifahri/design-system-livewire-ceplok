{{-- Kartu contoh: judul opsional, lalu area pratinjau berlatar netral — port DocExample di docs React. --}}
@props([
    'title' => null,
])

<div {{ $attributes->class('ds-card overflow-hidden') }}>
    @if ($title)
        <div class="border-b border-border px-5 py-3">
            <p class="text-sm font-black text-gray-900">{{ $title }}</p>
        </div>
    @endif
    <div class="flex flex-wrap items-center gap-3 bg-surface-subtle p-6">{{ $slot }}</div>
</div>
