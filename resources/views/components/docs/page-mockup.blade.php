{{--
    Kerangka halaman untuk Hero centered versi gambar: navbar tiruan di atas,
    hero di tengah, lalu baris konten tiruan di bawah — menunjukkan hero gambar
    di konteks halaman, bukan berdiri sendiri. Warnanya mengikuti `dark`.
--}}
@props([
    'dark' => false,
])

@php
    $bar = $dark ? 'bg-gray-700' : 'bg-gray-200';
@endphp

<div @class([$dark ? 'bg-gray-900' : 'bg-surface'])>
    <div aria-hidden="true" @class([
        'flex items-center justify-between gap-4 border-b px-5 py-4 sm:px-8',
        $dark ? 'border-gray-800' : 'border-border',
    ])>
        <div class="flex items-center gap-2.5">
            <span @class(['size-8 rounded-lg', $dark ? 'bg-gray-600' : 'bg-gray-300'])></span>
            <span class="h-3 w-24 rounded-full {{ $bar }}"></span>
        </div>
        <div class="hidden items-center gap-6 sm:flex">
            <span class="h-2.5 w-14 rounded-full {{ $bar }}"></span>
            <span class="h-2.5 w-16 rounded-full {{ $bar }}"></span>
            <span class="h-2.5 w-12 rounded-full {{ $bar }}"></span>
            <span class="h-2.5 w-14 rounded-full {{ $bar }}"></span>
        </div>
        <span class="h-8 w-20 rounded-lg bg-primary-700/80"></span>
    </div>

    {{ $slot }}

    <div aria-hidden="true" class="grid gap-4 px-5 py-8 sm:grid-cols-3 sm:px-8">
        @foreach (range(1, 3) as $i)
            <div class="space-y-2.5">
                <span class="block aspect-video rounded-lg {{ $bar }}"></span>
                <span class="block h-3 w-3/4 rounded-full {{ $bar }}"></span>
                <span class="block h-2.5 w-1/2 rounded-full {{ $bar }}"></span>
            </div>
        @endforeach
    </div>
</div>
