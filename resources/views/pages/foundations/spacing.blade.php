@php
    // Skala spacing mengikuti default Tailwind — basis 4px (0.25rem).
    // Nama class ditulis literal agar terdeteksi oleh scanner Tailwind.
    $spacingScale = [
        ['name' => '0', 'size' => '0px', 'px' => '0px', 'bar' => 'w-0'],
        ['name' => 'px', 'size' => '1px', 'px' => '1px', 'bar' => 'w-px'],
        ['name' => '0.5', 'size' => '0.125rem', 'px' => '2px', 'bar' => 'w-0.5'],
        ['name' => '1', 'size' => '0.25rem', 'px' => '4px', 'bar' => 'w-1'],
        ['name' => '1.5', 'size' => '0.375rem', 'px' => '6px', 'bar' => 'w-1.5'],
        ['name' => '2', 'size' => '0.5rem', 'px' => '8px', 'bar' => 'w-2'],
        ['name' => '2.5', 'size' => '0.625rem', 'px' => '10px', 'bar' => 'w-2.5'],
        ['name' => '3', 'size' => '0.75rem', 'px' => '12px', 'bar' => 'w-3'],
        ['name' => '3.5', 'size' => '0.875rem', 'px' => '14px', 'bar' => 'w-3.5'],
        ['name' => '4', 'size' => '1rem', 'px' => '16px', 'bar' => 'w-4'],
        ['name' => '5', 'size' => '1.25rem', 'px' => '20px', 'bar' => 'w-5'],
        ['name' => '6', 'size' => '1.5rem', 'px' => '24px', 'bar' => 'w-6'],
        ['name' => '7', 'size' => '1.75rem', 'px' => '28px', 'bar' => 'w-7'],
        ['name' => '8', 'size' => '2rem', 'px' => '32px', 'bar' => 'w-8'],
        ['name' => '9', 'size' => '2.25rem', 'px' => '36px', 'bar' => 'w-9'],
        ['name' => '10', 'size' => '2.5rem', 'px' => '40px', 'bar' => 'w-10'],
        ['name' => '11', 'size' => '2.75rem', 'px' => '44px', 'bar' => 'w-11'],
        ['name' => '12', 'size' => '3rem', 'px' => '48px', 'bar' => 'w-12'],
        ['name' => '14', 'size' => '3.5rem', 'px' => '56px', 'bar' => 'w-14'],
        ['name' => '16', 'size' => '4rem', 'px' => '64px', 'bar' => 'w-16'],
        ['name' => '20', 'size' => '5rem', 'px' => '80px', 'bar' => 'w-20'],
        ['name' => '24', 'size' => '6rem', 'px' => '96px', 'bar' => 'w-24'],
        ['name' => '28', 'size' => '7rem', 'px' => '112px', 'bar' => 'w-28'],
        ['name' => '32', 'size' => '8rem', 'px' => '128px', 'bar' => 'w-32'],
        ['name' => '36', 'size' => '9rem', 'px' => '144px', 'bar' => 'w-36'],
        ['name' => '40', 'size' => '10rem', 'px' => '160px', 'bar' => 'w-40'],
        ['name' => '44', 'size' => '11rem', 'px' => '176px', 'bar' => 'w-44'],
        ['name' => '48', 'size' => '12rem', 'px' => '192px', 'bar' => 'w-48'],
        ['name' => '52', 'size' => '13rem', 'px' => '208px', 'bar' => 'w-52'],
        ['name' => '56', 'size' => '14rem', 'px' => '224px', 'bar' => 'w-56'],
        ['name' => '60', 'size' => '15rem', 'px' => '240px', 'bar' => 'w-60'],
        ['name' => '64', 'size' => '16rem', 'px' => '256px', 'bar' => 'w-64'],
        ['name' => '72', 'size' => '18rem', 'px' => '288px', 'bar' => 'w-72'],
        ['name' => '80', 'size' => '20rem', 'px' => '320px', 'bar' => 'w-80'],
        ['name' => '96', 'size' => '24rem', 'px' => '384px', 'bar' => 'w-96'],
    ];

    $prefixes = [
        ['label' => 'Padding', 'code' => 'p-* · px-* · py-* · pt-* pr-* pb-* pl-*', 'desc' => 'Ruang di dalam elemen, antara border dan konten.'],
        ['label' => 'Margin', 'code' => 'm-* · mx-* · my-* · mt-* mr-* mb-* ml-*', 'desc' => 'Ruang di luar elemen, memisahkan dari elemen lain.'],
        ['label' => 'Gap', 'code' => 'gap-* · gap-x-* · gap-y-*', 'desc' => 'Jarak antaritem di dalam flex dan grid container.'],
    ];
@endphp

<x-docs-layout section="foundations" title="Spacing">
    <x-doc-hero eyebrow="Foundations · Spacing" title="Skala spacing basis 4px" description="Seluruh jarak — padding, margin, dan gap — mengikuti satu skala dengan basis 4px (0.25rem). Skala yang konsisten menjaga ritme visual dan alignment di seluruh produk." />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <div class="mb-5">
            <p class="ds-eyebrow">Scale</p>
            <h3 class="mt-2 text-heading-3 font-black text-gray-900">Skala spacing</h3>
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Setiap langkah adalah kelipatan 4px — nama token dikali 4 menghasilkan nilai pixel (mis. <code class="text-xs font-bold text-gray-700">4</code> = 16px). Berlaku untuk utility <code class="text-xs font-bold text-gray-700">p-*</code>, <code class="text-xs font-bold text-gray-700">m-*</code>, <code class="text-xs font-bold text-gray-700">gap-*</code>, <code class="text-xs font-bold text-gray-700">w-*</code>, dan <code class="text-xs font-bold text-gray-700">h-*</code>.</p>
        </div>

        <article class="ds-card overflow-hidden">
            <div class="ds-scroll-x overflow-x-auto">
                <div class="min-w-[560px]">
                    <div class="grid grid-cols-[90px_110px_90px_1fr] gap-4 border-b border-border px-5 py-3.5 sm:px-7">
                        <p class="text-xs font-black text-gray-900">Name</p>
                        <p class="text-xs font-black text-gray-900">Size</p>
                        <p class="text-xs font-black text-gray-900">Pixels</p>
                        <p aria-hidden="true"></p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($spacingScale as $step)
                            <div class="grid grid-cols-[90px_110px_90px_1fr] items-center gap-4 px-5 py-3 sm:px-7">
                                <code class="text-sm font-bold text-gray-900">{{ $step['name'] }}</code>
                                <code class="text-sm text-gray-500">{{ $step['size'] }}</code>
                                <code class="text-sm text-gray-500">{{ $step['px'] }}</code>
                                <div><div class="{{ $step['bar'] }} h-4 rounded-xs bg-primary-600"></div></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </article>

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Usage</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Padding, margin, dan gap</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Satu skala yang sama dipakai oleh ketiga jenis jarak — cukup ganti prefix utility-nya.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($prefixes as $prefix)
                    <article class="ds-card p-5">
                        <p class="text-sm font-black text-gray-900">{{ $prefix['label'] }}</p>
                        <code class="mt-1 block text-xs font-bold text-primary-700">{{ $prefix['code'] }}</code>
                        <p class="mt-3 text-sm leading-6 text-gray-600">{{ $prefix['desc'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Demo padding: p-4 --}}
                <article class="ds-card p-5">
                    <code class="text-sm font-bold text-primary-700">.p-4</code>
                    <div class="mt-3 rounded-lg bg-primary-100">
                        <div class="p-4">
                            <div class="rounded-md bg-white px-3 py-2 text-xs font-bold text-gray-700 shadow-sm">Konten</div>
                        </div>
                    </div>
                </article>
                {{-- Demo margin: mt-4 --}}
                <article class="ds-card p-5">
                    <code class="text-sm font-bold text-primary-700">.mt-4</code>
                    <div class="mt-3 rounded-lg border border-dashed border-primary-300 p-2">
                        <div class="rounded-md bg-gray-100 px-3 py-2 text-xs font-bold text-gray-500">Elemen pertama</div>
                        <div class="mt-4 rounded-md bg-primary-100 px-3 py-2 text-xs font-bold text-primary-800">Elemen kedua</div>
                    </div>
                </article>
                {{-- Demo gap: gap-4 --}}
                <article class="ds-card p-5">
                    <code class="text-sm font-bold text-primary-700">.gap-4</code>
                    <div class="mt-3 flex gap-4 rounded-lg border border-dashed border-primary-300 p-2">
                        <div class="flex-1 rounded-md bg-primary-100 px-3 py-2 text-center text-xs font-bold text-primary-800">1</div>
                        <div class="flex-1 rounded-md bg-primary-100 px-3 py-2 text-center text-xs font-bold text-primary-800">2</div>
                        <div class="flex-1 rounded-md bg-primary-100 px-3 py-2 text-center text-xs font-bold text-primary-800">3</div>
                    </div>
                </article>
            </div>
        </div>

        <div class="mt-8">
            <article class="ds-card p-6">
                <p class="ds-eyebrow">Prinsip penggunaan</p>
                <ul class="mt-5 space-y-4 text-sm leading-6 text-gray-600">
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Selalu pakai langkah pada skala — hindari nilai arbitrer seperti <code class="text-xs font-bold text-gray-700">p-[13px]</code> agar ritme 4px terjaga.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Gunakan langkah kecil (<strong class="text-gray-900">1–4</strong>) untuk jarak di dalam komponen, langkah sedang (<strong class="text-gray-900">5–12</strong>) antarkomponen, dan langkah besar (<strong class="text-gray-900">14+</strong>) antarseksi halaman.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Pilih <code class="text-xs font-bold text-gray-700">gap-*</code> dibanding margin antaritem di dalam flex/grid — lebih mudah dirawat dan tidak menimbulkan margin ganda.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Elemen dengan fungsi setara harus memakai jarak yang sama agar hierarki terlihat konsisten.</span></li>
                </ul>
            </article>
        </div>
    </div>
</x-docs-layout>
