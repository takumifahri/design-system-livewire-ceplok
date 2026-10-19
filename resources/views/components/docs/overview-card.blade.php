{{--
    Kartu tautan di halaman Overview: pratinjau di atas, judul + deskripsi di
    bawah — port OverviewCard di docs React.

    Kartunya <div>, bukan <a>: pratinjau seperti Footer berisi tautannya
    sendiri, dan <a> di dalam <a> dipecah parser HTML. Yang membuat seluruh
    kartu bisa diklik adalah ::after milik tautan judul (stretched link).
    Pratinjaunya `inert`, jadi tidak ikut diklik, difokus, atau dibacakan.

    Grid meregangkan tiap kartu setinggi barisnya; `flex-1` membuat pratinjau
    yang menyerap sisa tinggi itu, dan `grid` pada pembungkusnya meregangkan
    kotak pratinjau di dalamnya, supaya yang melar latarnya — bukan ruang kosong.
--}}
@props([
    'route',
    'name',
    'desc',
    'wide' => false,
])

<div x-spotlight @class([
    'ds-spotlight ds-card group flex flex-col p-6 transition-[transform,box-shadow] duration-300 hover:-translate-y-1 hover:shadow-lg',
    'has-[a:focus-visible]:outline-2 has-[a:focus-visible]:outline-offset-2 has-[a:focus-visible]:outline-primary-600',
    'sm:col-span-2' => $wide,
])>
    <div class="grid flex-1" inert>{{ $slot }}</div>
    <h2 class="mt-4 text-heading-4 font-black text-gray-900">
        <a href="{{ route($route) }}" wire:navigate class="rounded-sm after:absolute after:inset-0 after:rounded-2xl focus-visible:outline-none">{{ $name }}</a>
    </h2>
    <p class="mt-1.5 text-body-sm text-gray-500">{{ $desc }}</p>
    <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary-700" aria-hidden="true">
        Lihat detail
        <svg class="size-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
        </svg>
    </span>
</div>
