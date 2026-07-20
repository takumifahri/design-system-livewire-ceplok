@php
    $typeScale = [
        ['name' => 'Display', 'class' => 'text-display font-bold', 'spec' => '48 / 55 · Bold', 'sample' => 'Layanan digital untuk semua'],
        ['name' => 'Heading 1', 'class' => 'text-heading-1 font-bold', 'spec' => '36 / 43 · Bold', 'sample' => 'Transformasi digital Indonesia'],
        ['name' => 'Heading 2', 'class' => 'text-heading-2 font-bold', 'spec' => '30 / 38 · Bold', 'sample' => 'Informasi dan layanan publik'],
        ['name' => 'Heading 3', 'class' => 'text-heading-3 font-bold', 'spec' => '24 / 31 · Bold', 'sample' => 'Akses mudah dan terpercaya'],
        ['name' => 'Heading 4', 'class' => 'text-heading-4 font-bold', 'spec' => '20 / 28 · Bold', 'sample' => 'Pelayanan dalam satu tempat'],
        ['name' => 'Body Large', 'class' => 'text-body-lg font-normal', 'spec' => '18 / 29 · Regular', 'sample' => 'Kami menghadirkan layanan digital yang inklusif, aman, dan mudah digunakan.'],
        ['name' => 'Body', 'class' => 'text-body font-normal', 'spec' => '16 / 24 · Regular', 'sample' => 'Gunakan bahasa yang ringkas agar informasi dapat dipahami oleh seluruh masyarakat.'],
        ['name' => 'Body Small', 'class' => 'text-body-sm font-normal', 'spec' => '14 / 20 · Regular', 'sample' => 'Informasi pendukung, deskripsi singkat, dan label komponen.'],
        ['name' => 'Caption', 'class' => 'text-caption font-bold tracking-wide uppercase', 'spec' => '12 / 17 · Bold', 'sample' => 'Terakhir diperbarui 18 Juli 2026'],
    ];

    // Skala ukuran mengikuti default Tailwind (text-xs … text-9xl). Nama class
    // ditulis literal agar terdeteksi oleh scanner Tailwind.
    $tailwindScale = [
        ['class' => 'text-xs', 'label' => '12px'],
        ['class' => 'text-sm', 'label' => '14px'],
        ['class' => 'text-base', 'label' => '16px'],
        ['class' => 'text-lg', 'label' => '18px'],
        ['class' => 'text-xl', 'label' => '20px'],
        ['class' => 'text-2xl', 'label' => '24px'],
        ['class' => 'text-3xl', 'label' => '30px'],
        ['class' => 'text-4xl', 'label' => '36px'],
        ['class' => 'text-5xl', 'label' => '48px'],
        ['class' => 'text-6xl', 'label' => '60px'],
        ['class' => 'text-7xl', 'label' => '72px'],
        ['class' => 'text-8xl', 'label' => '96px'],
        ['class' => 'text-9xl', 'label' => '128px'],
    ];

    $fontWeights = [
        ['class' => 'font-thin', 'label' => 'Thin', 'value' => 100],
        ['class' => 'font-extralight', 'label' => 'Extra Light', 'value' => 200],
        ['class' => 'font-light', 'label' => 'Light', 'value' => 300],
        ['class' => 'font-normal', 'label' => 'Regular', 'value' => 400],
        ['class' => 'font-medium', 'label' => 'Medium', 'value' => 500],
        ['class' => 'font-semibold', 'label' => 'Semi Bold', 'value' => 600],
        ['class' => 'font-bold', 'label' => 'Bold', 'value' => 700],
        ['class' => 'font-extrabold', 'label' => 'Extra Bold', 'value' => 800],
        ['class' => 'font-black', 'label' => 'Black', 'value' => 900],
    ];

    $textTransforms = [
        ['class' => 'uppercase', 'label' => 'Uppercase', 'desc' => 'text-transform: uppercase'],
        ['class' => 'line-through', 'label' => 'Line Through', 'desc' => 'text-decoration: line-through'],
        ['class' => 'underline', 'label' => 'Underline', 'desc' => 'text-decoration: underline'],
    ];

    $lineHeights = [
        ['class' => 'leading-none', 'value' => '100%'],
        ['class' => 'leading-normal', 'value' => '150%'],
        ['class' => 'leading-loose', 'value' => '200%'],
    ];

    // Cuplikan kode untuk tombol salin di tiap section.
    $usage = [
        'semantic' => <<<'CODE'
        {{-- Token semantik — hierarki utama halaman --}}
        <h1 class="text-display font-bold">Judul besar</h1>
        <h2 class="text-heading-1 font-bold">Judul halaman</h2>
        <p class="text-body text-gray-500">Paragraf isi konten.</p>
        <span class="text-caption font-bold uppercase">Label kecil</span>
        CODE,

        'scale' => <<<'CODE'
        {{-- Skala Tailwind — untuk kebutuhan di luar token semantik --}}
        <p class="text-xs">Teks 12px</p>
        <p class="text-base">Teks 16px</p>
        <p class="text-2xl">Teks 24px</p>
        <p class="text-5xl">Teks 48px</p>
        CODE,

        'weight' => <<<'CODE'
        {{-- Sembilan tingkat ketebalan --}}
        <p class="font-thin">Thin 100</p>
        <p class="font-normal">Regular 400</p>
        <p class="font-semibold">Semi Bold 600</p>
        <p class="font-black">Black 900</p>
        CODE,

        'leading' => <<<'CODE'
        {{-- Jarak antarbaris --}}
        <p class="leading-none">Rapat — 100%</p>
        <p class="leading-normal">Standar — 150%</p>
        <p class="leading-loose">Renggang — 200%</p>
        CODE,

        'transform' => <<<'CODE'
        {{-- Transform & decoration --}}
        <p class="uppercase">huruf kapital semua</p>
        <p class="line-through">Teks dicoret</p>
        <a href="#" class="underline">Tautan bergaris bawah</a>
        CODE,
    ];
@endphp

<x-docs-layout section="foundations" title="Typography">
    <x-doc-hero eyebrow="Foundations · Typography" title="Lato type system" description="Lato dipilih karena terbuka, humanis, dan tetap terbaca baik pada layar kecil. Skala berikut menjaga hierarki tetap konsisten di seluruh produk." />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <article class="ds-card overflow-hidden">
            <div class="grid gap-8 border-b border-border bg-primary-900 p-6 text-white sm:p-8 lg:grid-cols-[1fr_auto] lg:items-end">
                <div>
                    <p class="text-xs font-bold tracking-[0.14em] text-primary-300 uppercase">Primary typeface</p>
                    <p class="mt-3 text-[clamp(4.5rem,15vw,9rem)] leading-none font-black tracking-tight">Lato</p>
                </div>
                <div class="max-w-sm lg:text-right">
                    <p class="text-heading-4 font-bold">Aa Bb Cc 123</p>
                    <p class="mt-2 text-sm leading-6 text-primary-200">ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>abcdefghijklmnopqrstuvwxyz<br>0123456789 !@#$%&amp;*</p>
                </div>
            </div>

            <div class="divide-y divide-border">
                @foreach ($typeScale as $type)
                    <div class="grid gap-4 p-5 sm:p-7 lg:grid-cols-[180px_1fr] lg:items-baseline">
                        <div>
                            <p class="text-sm font-black text-gray-900">{{ $type['name'] }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ $type['spec'] }}</p>
                        </div>
                        <p class="{{ $type['class'] }} text-gray-900">{{ $type['sample'] }}</p>
                    </div>
                @endforeach
            </div>
        </article>

        <x-doc-usage :code="$usage['semantic']" />

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Type scale</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Skala ukuran Tailwind</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Skala <code class="text-xs font-bold text-gray-700">text-xs</code> hingga <code class="text-xs font-bold text-gray-700">text-9xl</code> untuk kebutuhan di luar token semantik.</p>
            </div>
            <article class="ds-card divide-y divide-border overflow-hidden">
                @foreach ($tailwindScale as $size)
                    <div class="p-5 sm:p-6">
                        <div class="flex items-baseline gap-2">
                            <code class="text-sm font-bold text-primary-700">.{{ $size['class'] }}</code>
                            <span class="text-xs text-gray-400">{{ $size['label'] }}</span>
                        </div>
                        <div class="ds-scroll-x mt-3 overflow-x-auto">
                            <p class="{{ $size['class'] }} font-normal whitespace-nowrap text-gray-900">The quick brown fox jumps over the lazy dog.</p>
                        </div>
                    </div>
                @endforeach
            </article>
        </div>

        <x-doc-usage :code="$usage['scale']" />

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Font weights</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Sembilan tingkat ketebalan</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Dari <code class="text-xs font-bold text-gray-700">.font-thin</code> (100) hingga <code class="text-xs font-bold text-gray-700">.font-black</code> (900). Lato memuat weight 100/300/400/700/900 secara native; 200/500/600/800 dirender sintetis oleh browser.</p>
            </div>
            <article class="ds-card divide-y divide-border overflow-hidden">
                @foreach ($fontWeights as $weight)
                    <div class="grid gap-2 p-5 sm:grid-cols-[1fr_auto] sm:items-center sm:p-6">
                        <p class="{{ $weight['class'] }} text-4xl text-gray-900">State Security Service</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 sm:justify-end">
                            <code class="text-sm font-bold text-primary-700">.{{ $weight['class'] }}</code>
                            <code class="text-xs text-gray-400">font-weight: {{ $weight['value'] }}</code>
                        </div>
                    </div>
                @endforeach
            </article>
        </div>

        <x-doc-usage :code="$usage['weight']" />

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Line height</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Leading (jarak antarbaris)</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Kontrol kerapatan baris dengan <code class="text-xs font-bold text-gray-700">.leading-none</code> hingga <code class="text-xs font-bold text-gray-700">.leading-loose</code>.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($lineHeights as $leading)
                    <article class="ds-card p-5">
                        <div class="flex items-center justify-between gap-2">
                            <code class="text-sm font-bold text-primary-700">.{{ $leading['class'] }}</code>
                            <code class="text-xs text-gray-400">{{ $leading['value'] }}</code>
                        </div>
                        <p class="{{ $leading['class'] }} mt-3 text-sm text-gray-900">Layanan digital untuk semua masyarakat Indonesia yang inklusif dan mudah diakses.</p>
                    </article>
                @endforeach
            </div>
        </div>

        <x-doc-usage :code="$usage['leading']" />

        <div class="mt-8 grid gap-5 lg:grid-cols-2">
            <article class="ds-card p-6">
                <p class="ds-eyebrow">Transform & decoration</p>
                <div class="mt-5 space-y-5">
                    @foreach ($textTransforms as $transform)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between gap-3">
                                <code class="text-sm font-bold text-primary-700">.{{ $transform['class'] }}</code>
                                <code class="text-[11px] text-gray-400">{{ $transform['desc'] }}</code>
                            </div>
                            <p class="{{ $transform['class'] }} mt-2 text-lg text-gray-900">The quick brown fox jumps over the lazy dog.</p>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="ds-card p-6">
                <p class="ds-eyebrow">Prinsip penggunaan</p>
                <ul class="mt-5 space-y-4 text-sm leading-6 text-gray-600">
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Gunakan <strong class="text-gray-900">Bold atau Black</strong> untuk heading dan angka penting.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Pertahankan body minimal <strong class="text-gray-900">16px</strong> untuk konten utama.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Hindari paragraf panjang dengan huruf kapital karena menurunkan keterbacaan.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Batasi panjang baris ideal antara <strong class="text-gray-900">45–75 karakter</strong>.</span></li>
                </ul>
            </article>
        </div>
        <x-doc-usage :code="$usage['transform']" />

    </div>
</x-docs-layout>
