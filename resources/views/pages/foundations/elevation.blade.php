@php
    // Skala bayangan mengikuti penamaan Figma/v3: shadow-sm adalah langkah
    // terkecil (nilainya dioverride di design-tokens.css) dan `shadow` adalah
    // default — di Tailwind v4 `shadow` tetap bekerja sebagai alias kompat.
    // Nama class ditulis literal agar terdeteksi oleh scanner Tailwind.
    $shadowScale = [
        ['class' => 'shadow-sm'],
        ['class' => 'shadow'],
        ['class' => 'shadow-md'],
        ['class' => 'shadow-lg'],
        ['class' => 'shadow-xl'],
        ['class' => 'shadow-2xl'],
    ];

    // Label mengikuti penamaan warna di Figma; tile dirender dengan token
    // palet proyek yang nilainya identik (blue = primary, green = success,
    // red = danger). "Blue portal" memakai token --color-blue-portal-600.
    $shadowColors = [
        ['label' => 'shadow-blue-600/50', 'tile' => 'bg-primary-600 shadow-lg shadow-primary-600/50'],
        ['label' => 'shadow-blue-portal-600/50', 'tile' => 'bg-blue-portal-600 shadow-lg shadow-blue-portal-600/50'],
        ['label' => 'shadow-green-500/50', 'tile' => 'bg-green-500 shadow-lg shadow-green-500/50'],
        ['label' => 'shadow-red-500/50', 'tile' => 'bg-red-500 shadow-lg shadow-red-500/50'],
        ['label' => 'shadow-orange-500/50', 'tile' => 'bg-orange-500 shadow-lg shadow-orange-500/50'],
        ['label' => 'shadow-purple-500/50', 'tile' => 'bg-purple-500 shadow-lg shadow-purple-500/50'],
    ];

    // Cuplikan kode untuk tombol salin di tiap section.
    $usage = [
        'shadow' => <<<'CODE'
        {{-- Semakin tinggi melayang, semakin besar bayangannya --}}
        <div class="rounded-lg bg-white shadow-sm">Kartu diam</div>
        <div class="rounded-lg bg-white shadow-lg">Dropdown / popover</div>
        <div class="rounded-xl bg-white shadow-2xl">Modal</div>

        {{-- Transisi bayangan saat hover --}}
        <div class="rounded-lg bg-white shadow transition-shadow hover:shadow-md">
            Kartu yang bisa diklik
        </div>
        CODE,

        'color' => <<<'CODE'
        {{-- Bayangan berwarna — khusus penekanan seperti CTA --}}
        <button class="rounded-lg bg-primary-600 px-4 py-2 text-white shadow-lg shadow-primary-600/50">
            Aksi utama
        </button>
        <div class="rounded-lg bg-green-500 p-4 shadow-lg shadow-green-500/50">Berhasil</div>
        CODE,
    ];
@endphp

<x-docs-layout section="foundations" title="Elevation">
    <x-doc-hero eyebrow="Foundations · Elevation" title="Bayangan dan kedalaman" description="Elevation memberi hierarki kedalaman antarpermukaan — semakin tinggi elemen melayang, semakin lembut dan panjang bayangannya. Gunakan skala berikut secara konsisten agar kedalaman terasa natural." />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <div class="mb-5">
            <p class="ds-eyebrow">Box shadow</p>
            <h3 class="mt-2 text-heading-3 font-black text-gray-900">Skala bayangan</h3>
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Enam tingkat bayangan dari <code class="text-xs font-bold text-gray-700">shadow-sm</code> hingga <code class="text-xs font-bold text-gray-700">shadow-2xl</code>. Gunakan <code class="text-xs font-bold text-gray-700">shadow</code> sebagai default untuk elemen melayang standar.</p>
        </div>
        <article class="ds-card p-5 sm:p-7">
            <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($shadowScale as $shadow)
                    <div class="{{ $shadow['class'] }} flex aspect-6/7 items-center justify-center rounded-lg bg-white text-center">
                        <code class="text-sm font-bold text-primary-700">.{{ $shadow['class'] }}</code>
                    </div>
                @endforeach
            </div>
        </article>

        <x-doc-usage :code="$usage['shadow']" />

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Box shadow color</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Bayangan berwarna</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Kombinasikan skala bayangan dengan warna token, misalnya <code class="text-xs font-bold text-gray-700">shadow-lg shadow-primary-600/50</code>. Efektif untuk memberi glow pada tombol CTA atau elemen promosi.</p>
            </div>
            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($shadowColors as $color)
                        <div class="{{ $color['tile'] }} flex h-32 items-center justify-center rounded-lg text-center">
                            <code class="px-3 text-sm font-bold text-white">.{{ $color['label'] }}</code>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <x-doc-usage :code="$usage['color']" />

        <div class="mt-8">
            <article class="ds-card p-6">
                <p class="ds-eyebrow">Prinsip penggunaan</p>
                <ul class="mt-5 space-y-4 text-sm leading-6 text-gray-600">
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Semakin tinggi elemen melayang, semakin besar bayangannya — kartu memakai <code class="text-xs font-bold text-gray-700">shadow-sm</code> atau <code class="text-xs font-bold text-gray-700">shadow</code>, dropdown/popover <code class="text-xs font-bold text-gray-700">shadow-lg</code>, dan modal <code class="text-xs font-bold text-gray-700">shadow-xl</code> ke atas.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Batasi maksimal <strong class="text-gray-900">dua tingkat elevation</strong> dalam satu tampilan agar hierarki tetap jelas.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Bayangan berwarna hanya untuk penekanan khusus (CTA, kartu promosi) — jangan dipakai pada konten reguler.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Bayangan bukan pengganti kontras — elemen interaktif tetap butuh batas atau warna yang memenuhi standar aksesibilitas.</span></li>
                </ul>
            </article>
        </div>
    </div>
</x-docs-layout>
