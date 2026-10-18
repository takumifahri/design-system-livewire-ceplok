@php
    $shadowScale = ['shadow-sm', 'shadow', 'shadow-md', 'shadow-lg', 'shadow-xl', 'shadow-2xl'];
    $shadowColors = [
        ['shadow-blue-600/50', 'bg-primary-600 shadow-lg shadow-primary-600/50'],
        ['shadow-green-500/50', 'bg-green-500 shadow-lg shadow-green-500/50'],
        ['shadow-red-500/50', 'bg-red-500 shadow-lg shadow-red-500/50'],
        ['shadow-orange-500/50', 'bg-orange-500 shadow-lg shadow-orange-500/50'],
        ['shadow-purple-500/50', 'bg-purple-500 shadow-lg shadow-purple-500/50'],
    ];
    $code = fn (string $c) => '<code class="text-xs font-bold text-gray-700">'.e($c).'</code>';
@endphp

<x-docs.layout title="Elevation">
    <x-docs.page
        eyebrow="Foundations · Elevation"
        title="Bayangan dan kedalaman"
        description="Elevation memberi hierarki kedalaman antarpermukaan — semakin tinggi elemen melayang, semakin lembut dan panjang bayangannya."
        :toc="['shadow' => 'Box shadow', 'warna' => 'Bayangan berwarna', 'prinsip' => 'Prinsip penggunaan']"
    >
        <x-docs.section id="shadow" title="Box shadow">
            <x-docs.lead>
                Enam tingkat dari {!! $code('shadow-sm') !!} hingga {!! $code('shadow-2xl') !!}. Gunakan {!! $code('shadow') !!} sebagai default
                untuk elemen melayang standar.
            </x-docs.lead>

            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-6">
                    @foreach ($shadowScale as $class)
                        <div class="{{ $class }} flex aspect-6/7 items-center justify-center rounded-lg bg-white text-center">
                            <code class="text-sm font-bold text-primary-700">.{{ $class }}</code>
                        </div>
                    @endforeach
                </div>
            </article>

            <x-docs.code>
                @verbatim
                {{-- Semakin tinggi melayang, semakin besar bayangannya --}}
                <div class="rounded-lg bg-white shadow-sm">Kartu diam</div>
                <div class="rounded-lg bg-white shadow-lg">Dropdown / popover</div>
                <div class="rounded-xl bg-white shadow-2xl">Modal</div>

                {{-- Transisi bayangan saat hover --}}
                <div class="rounded-lg bg-white shadow transition-shadow hover:shadow-md">
                    Kartu yang bisa diklik
                </div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="warna" title="Bayangan berwarna">
            <x-docs.lead>
                Kombinasikan skala bayangan dengan warna token, mis. {!! $code('shadow-lg shadow-primary-600/50') !!}.
                Efektif untuk glow pada tombol CTA.
            </x-docs.lead>

            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($shadowColors as [$label, $tile])
                        <div class="{{ $tile }} flex h-32 items-center justify-center rounded-lg text-center">
                            <code class="px-3 text-sm font-bold text-white">.{{ $label }}</code>
                        </div>
                    @endforeach
                </div>
            </article>

            <x-docs.code>
                @verbatim
                {{-- Bayangan berwarna — khusus penekanan seperti CTA --}}
                <button class="rounded-lg bg-primary-600 px-4 py-2 text-white shadow-lg shadow-primary-600/50">
                    Aksi utama
                </button>
                <div class="rounded-lg bg-green-500 p-4 shadow-lg shadow-green-500/50">Berhasil</div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="prinsip" title="Prinsip penggunaan">
            <x-docs.principles>
                <x-docs.principle>Kartu memakai {!! $code('shadow-sm') !!}/{!! $code('shadow') !!}, dropdown/popover {!! $code('shadow-lg') !!}, modal {!! $code('shadow-xl') !!} ke atas.</x-docs.principle>
                <x-docs.principle>Batasi maksimal <strong class="text-gray-900">dua tingkat elevation</strong> dalam satu tampilan agar hierarki jelas.</x-docs.principle>
                <x-docs.principle>Bayangan berwarna hanya untuk penekanan khusus (CTA, kartu promosi) — bukan konten reguler.</x-docs.principle>
                <x-docs.principle>Bayangan bukan pengganti kontras — elemen interaktif tetap butuh batas/warna yang aksesibel.</x-docs.principle>
            </x-docs.principles>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
