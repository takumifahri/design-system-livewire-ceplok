{{--
    Pintu masuk Portal Dashboard — padanan PortalDashboardPage di docs React.
    Halaman ini sengaja hampir kosong: satu tombol di tengah. Dasbornya ada di
    /portal-dashboard/app, yang dirender penuh layar tanpa kerangka
    dokumentasi supaya terlihat seperti aplikasi sungguhan.
--}}
@php
    $judul = 'Beranda portal pegawai dari komponen yang sudah ada';
@endphp

<x-docs.layout title="Portal Dashboard">
    {{--
        Tirai harus di luar elemen ber-`isolate` di bawah ini: `isolate` membuat
        stacking context, dan z-100 milik tirai akan terkurung di dalamnya —
        rail dokumentasi (z-30) tetap tampak menembusnya.
    --}}
    <div x-data="docsTirai">
        <div class="relative isolate flex min-h-[calc(100vh-4rem)] items-center justify-center overflow-hidden px-6">
            {{-- Di mode gelap docs yang menyambung di bawahnya latar halaman gray-900. --}}
            <x-docs.aurora dark-fade="dark:to-gray-900" />

            <div x-ref="konten" class="relative z-10 max-w-2xl text-center">
                <div x-reveal data-delay="0.15">
                    <p class="ds-eyebrow">Portal Dashboard</p>
                </div>

                {{-- Satu baris tanpa spasi antar-span: jarak antarkata sudah dari mr-[0.26em]. --}}
                <h1 x-split-words data-delay="0.25" aria-label="{{ $judul }}" class="mt-4 text-heading-1 font-black text-gray-900 dark:text-white">@foreach (explode(' ', $judul) as $kata)<span aria-hidden="true" class="mr-[0.26em] inline-flex overflow-hidden pb-[0.12em] align-bottom last:mr-0"><span data-word class="inline-block">{{ $kata }}</span></span>@endforeach</h1>

                <div x-reveal data-delay="0.27">
                    <p class="mx-auto mt-5 max-w-xl text-body text-gray-600 dark:text-gray-300">
                        Sapaan, kalender, wiki terbaru, anggaran, presensi, dan rencana kegiatan harian — dirakit dari
                        komponen <code class="font-mono text-sm">ceplok-ui/design-kit-livewire</code>.
                    </p>
                </div>

                <div x-reveal data-delay="0.39" class="mt-10 flex justify-center">
                    <span x-magnetic="22" class="ds-magnetic inline-flex">
                        {{--
                            Perpindahannya menunggu tirai selesai menutup, jadi klik biasa
                            ditahan transition.js. Tautannya tetap tautan: bisa dibuka di
                            tab baru, dan tanpa JavaScript tetap berpindah halaman.
                        --}}
                        <a
                            href="{{ route('portal.app') }}"
                            x-on:click="masuk($event)"
                            x-bind:aria-disabled="berangkat ? 'true' : null"
                            class="group inline-flex items-center gap-3 rounded-full bg-primary-700 px-8 py-4 text-base font-black text-white shadow-lg transition-colors hover:bg-primary-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700 aria-disabled:cursor-default aria-disabled:hover:bg-primary-700"
                        >
                            Buka Portal Dashboard
                            <x-ceplok-o-arrow-right class="size-5 transition-transform group-hover:translate-x-1" aria-hidden="true" />
                        </a>
                    </span>
                </div>
            </div>
        </div>

        {{--
            Gelembung: paruh pertama transisi masuk. Panel: paruh kedua transisi pulang.
            Di mode gelap docs keduanya memakai ujung gelap tangga yang sama,
            primary-900 ke primary-800: gradien biru muda di atas halaman gelap
            justru menjadi kilatan.
        --}}
        <div x-ref="gelembung" data-tirai-gelembung class="pointer-events-none fixed top-0 left-0 z-100 aspect-square w-[300vmax] scale-0 rounded-full bg-linear-to-br from-primary-100 to-primary-300 dark:from-primary-900 dark:to-primary-800" aria-hidden="true"></div>
        <div x-ref="panel" data-tirai-panel class="pointer-events-none fixed inset-0 z-100 translate-y-full bg-linear-to-b from-primary-100 to-primary-300 dark:from-primary-900 dark:to-primary-800" aria-hidden="true"></div>
    </div>
</x-docs.layout>
