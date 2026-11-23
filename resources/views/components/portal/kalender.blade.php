{{--
    Kalender bulanan Portal Dashboard yang selalu terbuka, bergaya kalender di
    panel Datepicker: panah yang sama, judul dan tanggal text-xs, nama hari
    tiga huruf gray-500, tanggal luar bulan gray-500, dan hari ini bertanda
    seperti tanggal terpilih (bg-primary-700). Datepicker belum punya bentuk
    tanpa field, jadi tampilannya disalin di sini. Bedanya satu, mengikuti
    desain: Minggu dan Sabtu di bulan itu merah.

    Kisinya memenuhi kartu: tujuh kolom selebar kartu, dan baris tanggal ikut
    memanjang bila kartu lebih tinggi dari isinya, mis. saat bersebelahan
    dengan kartu wiki di layar md. Jarak lainnya mengikuti desain: judul 21px
    dari tepi atas, baris tanggal minimal 25px, dan baris terakhir 23px dari
    tepi bawah. Enam baris tetap: sisa minggu diisi tanggal bulan sebelum dan
    sesudahnya. `kalender` dirakit komponen Livewire induknya, dan panahnya
    memanggil aksi `bulanSebelumnya` dan `bulanBerikutnya` milik induk itu.

    Di mode gelap warnanya mengikuti panel gelap Datepicker. Akhir pekan
    memakai red-400, karena red-600 terlalu redup di atas kartu gray-700.
--}}
@props([
    // ['judul' => 'Oktober 2026', 'hari' => ['Min', …], 'minggu' => 6 × 7 sel]
    'kalender',
    'darkMode' => false,
])

@php
    // Tombol panah seperti di Datepicker: area tekannya 6px lebih besar ke
    // segala arah, lalu ditarik kembali dengan margin negatif.
    $panah = '-m-1.5 justify-self-center rounded-lg p-1.5 text-gray-900 transition-colors hover:bg-gray-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand in-data-[theme=dark]:text-white in-data-[theme=dark]:hover:bg-gray-600';
@endphp

<x-ceplok::card {{ $attributes }} :dark-mode="$darkMode">
    {{-- Margin negatif menarik kalender ke jarak desain, yang lebih rapat dari
         padding bawaan Card (16px, lalu 24px mulai kartu 320px). `flex-1`
         mengisi sisa tinggi kartu, dan tabelnya membaginya ke baris tanggal. --}}
    <div class="-mt-1 -mb-2 flex w-full flex-1 flex-col @xs:-mt-3 @xs:-mb-4">
        {{-- Header ikut kisi 7 kolom, supaya panahnya tepat di atas kolom Min dan Sab. --}}
        <div class="grid h-4.5 grid-cols-7 items-center">
            <button type="button" aria-label="Bulan sebelumnya" wire:click="bulanSebelumnya" class="{{ $panah }}">
                {{-- Panah 16×12 milik panel Datepicker. --}}
                <svg class="h-3 w-4" viewBox="0 0 16 12" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14.8 6H1.2m0 0 4.5 4.8M1.2 6l4.5-4.8" />
                </svg>
            </button>
            <h2 aria-live="polite" class="col-span-5 text-center text-xs font-bold text-gray-900 in-data-[theme=dark]:text-white">{{ $kalender['judul'] }}</h2>
            <button type="button" aria-label="Bulan berikutnya" wire:click="bulanBerikutnya" class="{{ $panah }}">
                <svg class="h-3 w-4 rotate-180" viewBox="0 0 16 12" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14.8 6H1.2m0 0 4.5 4.8M1.2 6l4.5-4.8" />
                </svg>
            </button>
        </div>

        <table class="mt-2.5 w-full flex-1 table-fixed text-center text-xs">
            <caption class="sr-only">Kalender {{ $kalender['judul'] }}</caption>
            <thead>
                {{-- Baris 30px: pb-1 menaruh nama hari 13px dari atasnya, dan sisanya
                     menjadi jarak ke baris tanggal pertama, seperti desainnya. --}}
                <tr class="h-7.5">
                    @foreach ($kalender['hari'] as $hari)
                        <th scope="col" class="pb-1 font-medium text-gray-500 in-data-[theme=dark]:text-gray-400">{{ $hari }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($kalender['minggu'] as $minggu)
                    <tr class="h-6.25">
                        @foreach ($minggu as $sel)
                            <td class="p-0">
                                <span
                                    @if ($sel['hariIni']) aria-current="date" @endif
                                    @class([
                                        'mx-auto flex size-6.25 items-center justify-center rounded-lg font-semibold',
                                        match (true) {
                                            $sel['hariIni'] => 'bg-primary-700 text-white in-data-[theme=dark]:bg-primary-600',
                                            $sel['diLuar'] => 'text-gray-500',
                                            $sel['akhirPekan'] => 'text-red-600 in-data-[theme=dark]:text-red-400',
                                            default => 'text-gray-900 in-data-[theme=dark]:text-white',
                                        },
                                    ])
                                >{{ $sel['tanggal'] }}</span>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-ceplok::card>
