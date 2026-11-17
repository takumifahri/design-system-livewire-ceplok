{{--
    Bingkai contoh aplikasi penuh layar — padanan ExampleAppFrame di docs
    React. Aplikasinya dirender tanpa kerangka dokumentasi supaya terlihat
    seperti aplikasi sungguhan; bingkai ini hanya menambah tombol pulang ke
    pintu masuknya dan tirai transisi (resources/js/docs/transition.js).

    Mode gelapnya mengikuti pilihan yang sama dengan docs (App\Docs\Theme).
    Aplikasi yang punya tombol tema sendiri cukup mengabarkan pilihannya lewat
    event `dark-mode-change` (`detail.darkMode`); bingkai ini yang
    menyimpannya, seperti `onDarkModeChange` di docs React.

    Semua gerak dikumpulkan di sini, di luar aplikasinya: aplikasi contoh
    harus murni memakai komponen kit, sedangkan transisi antar-halaman adalah
    urusan situs dokumentasi.
--}}
@php
    use App\Docs\Theme;
@endphp

@props([
    'title',
    // Pintu masuk yang dituju tombol pulang.
    'kembali',
])

<!DOCTYPE html>
<html lang="id" @if (Theme::dark()) data-docs-theme="dark" @endif>
    <x-docs.head :title="$title" />
    <body x-data="docsTirai" x-on:dark-mode-change="$docsTheme($event.detail.darkMode)">
        <div x-ref="konten">{{ $slot }}</div>

        <div class="fixed right-4 bottom-4 z-50">
            <span x-magnetic class="ds-magnetic inline-flex">
                {{-- Tautan, bukan tombol: tetap bisa dibuka di tab baru, dan tanpa JavaScript tetap berpindah halaman. --}}
                <a
                    href="{{ $kembali }}"
                    x-on:click="pulang($event)"
                    x-bind:aria-disabled="berangkat ? 'true' : null"
                    class="inline-flex items-center gap-2 rounded-full border border-border bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-lg transition-colors hover:text-primary-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700 aria-disabled:cursor-default aria-disabled:hover:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:text-primary-300 dark:aria-disabled:hover:text-gray-200"
                >
                    <x-ceplok-o-arrow-left class="size-4" aria-hidden="true" />
                    Kembali ke dokumentasi
                </a>
            </span>
        </div>

        {{-- Gelembung: paruh kedua transisi masuk. Panel: paruh pertama transisi pulang. Warna gelapnya sama dengan di pintu masuk. --}}
        <div x-ref="gelembung" data-tirai-gelembung class="pointer-events-none fixed top-0 left-0 z-100 aspect-square w-[300vmax] scale-0 rounded-full bg-linear-to-br from-primary-100 to-primary-300 dark:from-primary-900 dark:to-primary-800" aria-hidden="true"></div>
        <div x-ref="panel" data-tirai-panel class="pointer-events-none fixed inset-0 z-100 translate-y-full bg-linear-to-b from-primary-100 to-primary-300 dark:from-primary-900 dark:to-primary-800" aria-hidden="true"></div>

        {{-- Livewire dan Alpine dibundel di resources/js/app.js; di sini cukup konfigurasinya. --}}
        @livewireScriptConfig
    </body>
</html>
