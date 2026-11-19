{{--
    Kartu unduhan aplikasi KOSMO untuk satu toko — padanan KartuKosmo di
    PortalDashboard React. Di desain kartu ini gambar (logo, kode QR, dan
    lencana toko) yang belum ada di repo, jadi sementara dirakit dari token
    warna dan ikon kit, termasuk kotak QR-nya. Ganti dengan <img> begitu
    berkasnya tersedia. Warnanya tetap sama di mode gelap, seperti kartu
    sapaan. Tombolnya memanggil unduhKosmo() milik dasbor.
--}}
@props([
    // Kunci di ⚡dashboard::TOKO, mis. "android".
    'id',
    // Isi TOKO: sistem, toko, ajakan, tautan, ikon.
    'toko',
])

<div class="relative isolate flex flex-col items-center gap-3 overflow-hidden rounded-lg bg-primary-800 px-3 pt-12 pb-4 text-white shadow-soft">
    <span aria-hidden="true" class="absolute top-3 left-3 grid size-8 place-items-center rounded-lg bg-white text-primary-800">
        {{ svg($toko['ikon'], 'size-5') }}
    </span>
    <div aria-hidden="true" class="absolute -top-12 -right-14 -z-10 size-40 rounded-full bg-primary-700"></div>
    <div aria-hidden="true" class="absolute -bottom-20 -left-12 -z-10 size-44 rounded-full bg-primary-900"></div>
    <p class="flex items-center gap-1.5 text-heading-4 font-black tracking-wide">
        <x-ceplok-o-rocket class="size-6 shrink-0" aria-hidden="true" />
        KOSMO
    </p>
    <div
        role="img"
        aria-label="Kode QR unduhan KOSMO untuk {{ $toko['sistem'] }}"
        class="grid aspect-square w-full max-w-36 place-items-center rounded-lg bg-white text-gray-900"
    >
        <x-ceplok-o-qr-code class="size-3/4" aria-hidden="true" />
    </div>
    <p class="w-full truncate rounded bg-white px-1.5 py-0.5 text-center text-caption text-primary-800">{{ $toko['tautan'] }}</p>
    <button
        type="button"
        wire:click="unduhKosmo('{{ $id }}')"
        class="flex items-center gap-2 rounded-lg bg-gray-900 px-3 py-1.5 text-left ring-1 ring-white/50 transition-colors hover:bg-gray-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
    >
        {{ svg($toko['ikon'], 'size-5 shrink-0', ['aria-hidden' => 'true']) }}
        <span class="leading-tight">
            <span class="block text-caption">{{ $toko['ajakan'] }}</span>
            <span class="block text-body-sm font-semibold">{{ $toko['toko'] }}</span>
        </span>
    </button>
</div>
