@php
    // Skala radius mengikuti penamaan Figma/v3: rounded-sm adalah langkah
    // terkecil (nilainya dioverride di design-tokens.css) dan `rounded` adalah
    // default — di Tailwind v4 `rounded` tetap bekerja sebagai alias kompat.
    // Nama class ditulis literal agar terdeteksi oleh scanner Tailwind.
    $radiusScale = [
        ['class' => 'rounded-sm', 'value' => '2px'],
        ['class' => 'rounded', 'value' => '4px'],
        ['class' => 'rounded-md', 'value' => '6px'],
        ['class' => 'rounded-lg', 'value' => '8px'],
        ['class' => 'rounded-xl', 'value' => '12px'],
        ['class' => 'rounded-2xl', 'value' => '16px'],
        ['class' => 'rounded-3xl', 'value' => '24px'],
        ['class' => 'rounded-full', 'value' => '9999px'],
    ];

    $borderWidths = [
        ['class' => 'border-0', 'value' => '0px', 'extra' => 'outline outline-dashed outline-gray-300'],
        ['class' => 'border', 'value' => '1px', 'extra' => ''],
        ['class' => 'border-2', 'value' => '2px', 'extra' => ''],
        ['class' => 'border-4', 'value' => '4px', 'extra' => ''],
        ['class' => 'border-8', 'value' => '8px', 'extra' => ''],
    ];
@endphp

<x-docs-layout section="foundations" title="Border">
    <x-doc-hero eyebrow="Foundations · Border" title="Radius, ketebalan, dan gaya garis" description="Border membentuk kontur komponen — radius menentukan karakter sudut, ketebalan memberi penekanan, dan gaya garis membedakan status. Gunakan skala berikut agar kontur konsisten di seluruh produk." />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <div class="mb-5">
            <p class="ds-eyebrow">Border radius</p>
            <h3 class="mt-2 text-heading-3 font-black text-gray-900">Skala radius sudut</h3>
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Dari <code class="text-xs font-bold text-gray-700">rounded-sm</code> (2px) hingga <code class="text-xs font-bold text-gray-700">rounded-full</code> (pill/lingkaran). Gunakan <code class="text-xs font-bold text-gray-700">rounded</code> sebagai default untuk sudut standar.</p>
        </div>
        <article class="ds-card p-5 sm:p-7">
            {{-- Satu baris horizontal, scroll ke samping. rounded-full dibuat
                 persegi (size-40) agar menjadi lingkaran sempurna. --}}
            <div class="ds-scroll-x flex items-center gap-4 overflow-x-auto pb-2">
                @foreach ($radiusScale as $radius)
                    <div class="{{ $radius['class'] }} {{ $radius['class'] === 'rounded-full' ? 'size-40' : 'aspect-6/7 w-40' }} flex shrink-0 flex-col items-center justify-center gap-1 bg-primary-600 text-center">
                        <code class="text-sm font-bold text-white">.{{ $radius['class'] }}</code>
                        <code class="text-xs text-primary-200">{{ $radius['value'] }}</code>
                    </div>
                @endforeach
            </div>
        </article>

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Border width</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Ketebalan garis</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Lima tingkat ketebalan dari <code class="text-xs font-bold text-gray-700">border-0</code> hingga <code class="text-xs font-bold text-gray-700">border-8</code>. Default <code class="text-xs font-bold text-gray-700">border</code> (1px) cukup untuk mayoritas kebutuhan; tingkat tebal hanya untuk penekanan.</p>
            </div>
            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    @foreach ($borderWidths as $width)
                        <div class="{{ $width['class'] }} {{ $width['extra'] }} flex aspect-6/7 flex-col items-center justify-center gap-1 rounded-lg border-primary-300 bg-white text-center">
                            <code class="text-sm font-bold break-all text-primary-700">.{{ $width['class'] }}</code>
                            <code class="text-xs text-gray-400">{{ $width['value'] }}</code>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Border style</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Gaya garis</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Gaya standar adalah <code class="text-xs font-bold text-gray-700">border-solid</code> — dipakai untuk seluruh kontur komponen.</p>
            </div>
            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="flex aspect-6/7 flex-col items-center justify-center gap-1 rounded-lg border-2 border-solid border-white bg-primary-500 text-center ring-1 ring-gray-200">
                        <code class="text-sm font-bold break-all text-white">.border-solid</code>
                        <code class="text-xs text-primary-100">solid</code>
                    </div>
                </div>
            </article>
        </div>

        <div class="mt-8">
            <article class="ds-card p-6">
                <p class="ds-eyebrow">Prinsip penggunaan</p>
                <ul class="mt-5 space-y-4 text-sm leading-6 text-gray-600">
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Kontrol interaktif (button, input) memakai <code class="text-xs font-bold text-gray-700">rounded-lg</code>, kartu memakai <code class="text-xs font-bold text-gray-700">rounded-2xl</code>, dan badge/avatar memakai <code class="text-xs font-bold text-gray-700">rounded-full</code>.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Gunakan warna token <code class="text-xs font-bold text-gray-700">border-border</code> (gray-200) untuk garis pemisah dan kontur netral.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Ketebalan di atas 1px hanya untuk penekanan — misalnya status fokus, seleksi aktif, atau indikator validasi.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Jangan mencampur radius berbeda pada elemen yang bertumpuk; sudut dalam sebaiknya lebih kecil dari sudut luar agar terlihat serasi.</span></li>
                </ul>
            </article>
        </div>
    </div>
</x-docs-layout>
