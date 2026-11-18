{{--
    Kerangka halaman dokumentasi: hero + kolom isi + daftar isi "On this page"
    yang menempel di kanan — port UsulanPage di docs React.

    `toc` berisi pasangan id => label; id-nya sama dengan `id` pada
    <x-docs.section>. Lebar wadah = 57rem kolom isi + 2.5rem jarak + 190px
    daftar isi + padding, jadi kolom isinya tidak menyempit gara-gara daftar
    isi; daftar isinya yang bergeser ke ruang sisa di kanan.

    Hero ditulis di sini, bukan memakai <x-docs.hero>, karena hero itu
    memusatkan isinya di max-w-5xl — kalau lebarnya berbeda, judul dan isi
    tidak rata kiri.
--}}
@props([
    'eyebrow' => 'Components',
    'title',
    'description',
    'toc' => [],
    'stayDarkMode' => false,
])

@php
    // Di mode gelap docs bagian Dark mode bertukar menjadi Light mode (lihat
    // x-docs.mode-section). Id-nya tetap, jadi tautan ke bagian itu tidak putus.
    if (isset($toc['dark-mode']) && App\Docs\Theme::dark() && ! $stayDarkMode) {
        $toc['dark-mode'] = 'Light mode';
    }

    $ids = array_keys($toc);
    $first = $ids[0] ?? null;
@endphp

<div x-data="docsToc(@js($ids))">
    <header class="border-b border-border bg-white px-5 py-10 sm:px-8 lg:px-12 lg:py-12 xl:px-14 dark:border-gray-700 dark:bg-gray-800">
        <div class="mx-auto max-w-314">
            <p class="ds-eyebrow">{{ $eyebrow }}</p>
            <h1 class="mt-2 text-heading-1 font-black tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="mt-3 max-w-2xl text-body text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @isset($actions)
                <div class="mt-6">{{ $actions }}</div>
            @endisset
        </div>
    </header>

    <div class="mx-auto max-w-314 px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <div class="grid gap-10 2xl:grid-cols-[minmax(0,57rem)_190px]">
            <div class="min-w-0 max-w-228 space-y-16">{{ $slot }}</div>

            <aside class="hidden 2xl:block">
                <div class="sticky top-10">
                    <p class="text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">On this page</p>
                    <nav class="mt-3 border-l border-border dark:border-gray-700" aria-label="Daftar isi halaman">
                        @foreach ($toc as $id => $label)
                            {{-- Kelas awal dirender server (bagian pertama aktif); Alpine mengambil alih setelahnya. --}}
                            <a
                                href="#{{ $id }}"
                                x-on:click="go($event, @js($id))"
                                @if ($id === $first) aria-current="true" @endif
                                x-bind:aria-current="active === @js($id) ? 'true' : null"
                                @class([
                                    '-ml-px block w-full border-l-2 py-1.5 pl-3 text-left text-[13px] transition-colors',
                                    $id === $first ? 'border-primary-600 font-bold text-primary-700 dark:border-primary-400 dark:text-primary-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-800 dark:text-gray-400 dark:hover:border-gray-500 dark:hover:text-gray-100',
                                ])
                                x-bind:class="{
                                    'border-primary-600 font-bold text-primary-700 dark:border-primary-400 dark:text-primary-400': active === @js($id),
                                    'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-800 dark:text-gray-400 dark:hover:border-gray-500 dark:hover:text-gray-100': active !== @js($id),
                                }"
                            >{{ $label }}</a>
                        @endforeach
                    </nav>
                </div>
            </aside>
        </div>
    </div>
</div>
