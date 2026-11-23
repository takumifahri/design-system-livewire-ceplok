{{-- Satu kelompok kontrol Playground: label kecil di atas, kontrolnya di bawah. --}}
@props([
    'label',
])

<div {{ $attributes }}>
    <span class="text-xs font-black tracking-wide text-gray-500 uppercase dark:text-gray-400">{{ $label }}</span>
    <div class="mt-2">{{ $slot }}</div>
</div>
