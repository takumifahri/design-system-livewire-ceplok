{{--
    Bingkai contoh aplikasi penuh layar — padanan ExampleAppFrame di docs
    React. Aplikasinya dirender tanpa kerangka dokumentasi supaya terlihat
    seperti aplikasi sungguhan; bingkai ini hanya menambah tombol pulang ke
    pintu masuknya dan tirai transisi (resources/js/docs/transition.js).

    Semua gerak dikumpulkan di sini, di luar aplikasinya: aplikasi contoh
    harus murni memakai komponen kit, sedangkan transisi antar-halaman adalah
    urusan situs dokumentasi.
--}}
@props([
    'title',
    // Pintu masuk yang dituju tombol pulang.
    'kembali',
])

<!DOCTYPE html>
<html lang="id">
    <x-docs.head :title="$title" />
    <body x-data="docsTirai">
        <div x-ref="konten">{{ $slot }}</div>

        <div class="fixed right-4 bottom-4 z-50">
            <span x-magnetic class="ds-magnetic inline-flex">
                {{-- Tautan, bukan tombol: tetap bisa dibuka di tab baru, dan tanpa JavaScript tetap berpindah halaman. --}}
                <a
                    href="{{ $kembali }}"
                    x-on:click="pulang($event)"
                    x-bind:aria-disabled="berangkat ? 'true' : null"
                    class="inline-flex items-center gap-2 rounded-full border border-border bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-lg transition-colors hover:text-primary-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700 aria-disabled:cursor-default aria-disabled:hover:text-gray-700"
                >
                    <x-ceplok-o-arrow-left class="size-4" aria-hidden="true" />
                    Kembali ke dokumentasi
                </a>
            </span>
        </div>

        {{-- Gelembung: paruh kedua transisi masuk. Panel: paruh pertama transisi pulang. --}}
        <div x-ref="gelembung" data-tirai-gelembung class="pointer-events-none fixed top-0 left-0 z-100 aspect-square w-[300vmax] scale-0 rounded-full bg-linear-to-br from-primary-100 to-primary-300" aria-hidden="true"></div>
        <div x-ref="panel" data-tirai-panel class="pointer-events-none fixed inset-0 z-100 translate-y-full bg-linear-to-b from-primary-100 to-primary-300" aria-hidden="true"></div>

        {{-- Livewire dan Alpine dibundel di resources/js/app.js; di sini cukup konfigurasinya. --}}
        @livewireScriptConfig
    </body>
</html>
