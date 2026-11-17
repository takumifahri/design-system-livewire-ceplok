@php
    $scale = [
        ['0', '0px', '0px', 'w-0'], ['px', '1px', '1px', 'w-px'], ['0.5', '0.125rem', '2px', 'w-0.5'],
        ['1', '0.25rem', '4px', 'w-1'], ['1.5', '0.375rem', '6px', 'w-1.5'], ['2', '0.5rem', '8px', 'w-2'],
        ['2.5', '0.625rem', '10px', 'w-2.5'], ['3', '0.75rem', '12px', 'w-3'], ['3.5', '0.875rem', '14px', 'w-3.5'],
        ['4', '1rem', '16px', 'w-4'], ['5', '1.25rem', '20px', 'w-5'], ['6', '1.5rem', '24px', 'w-6'],
        ['7', '1.75rem', '28px', 'w-7'], ['8', '2rem', '32px', 'w-8'], ['9', '2.25rem', '36px', 'w-9'],
        ['10', '2.5rem', '40px', 'w-10'], ['11', '2.75rem', '44px', 'w-11'], ['12', '3rem', '48px', 'w-12'],
        ['14', '3.5rem', '56px', 'w-14'], ['16', '4rem', '64px', 'w-16'], ['20', '5rem', '80px', 'w-20'],
        ['24', '6rem', '96px', 'w-24'], ['28', '7rem', '112px', 'w-28'], ['32', '8rem', '128px', 'w-32'],
        ['36', '9rem', '144px', 'w-36'], ['40', '10rem', '160px', 'w-40'], ['44', '11rem', '176px', 'w-44'],
        ['48', '12rem', '192px', 'w-48'], ['52', '13rem', '208px', 'w-52'], ['56', '14rem', '224px', 'w-56'],
        ['60', '15rem', '240px', 'w-60'], ['64', '16rem', '256px', 'w-64'], ['72', '18rem', '288px', 'w-72'],
        ['80', '20rem', '320px', 'w-80'], ['96', '24rem', '384px', 'w-96'],
    ];

    $prefixes = [
        ['Padding', 'p-* · px-* · py-* · pt-* pr-* pb-* pl-*', 'Ruang di dalam elemen, antara border dan konten.'],
        ['Margin', 'm-* · mx-* · my-* · mt-* mr-* mb-* ml-*', 'Ruang di luar elemen, memisahkan dari elemen lain.'],
        ['Gap', 'gap-* · gap-x-* · gap-y-*', 'Jarak antaritem di dalam flex dan grid container.'],
    ];

    $code = fn (string $c) => '<code class="text-xs font-bold text-gray-700 dark:text-gray-300">'.e($c).'</code>';
@endphp

<x-docs.layout title="Spacing">
    <x-docs.page
        eyebrow="Foundations · Spacing"
        title="Skala spacing basis 4px"
        description="Seluruh jarak — padding, margin, dan gap — mengikuti satu skala dengan basis 4px (0.25rem). Skala yang konsisten menjaga ritme visual dan alignment di seluruh produk."
        :toc="['skala' => 'Skala spacing', 'prefix' => 'Padding, margin, gap', 'prinsip' => 'Prinsip penggunaan']"
    >
        <x-docs.section id="skala" title="Skala spacing">
            <x-docs.lead>
                Setiap langkah adalah kelipatan 4px — nama token dikali 4 menghasilkan nilai pixel (mis.
                {!! $code('4') !!} = 16px). Berlaku untuk {!! $code('p-*') !!}, {!! $code('m-*') !!}, {!! $code('gap-*') !!}, {!! $code('w-*') !!}, dan {!! $code('h-*') !!}.
            </x-docs.lead>

            <article class="ds-card overflow-hidden">
                <div class="ds-scroll-x overflow-x-auto">
                    <div class="min-w-140">
                        <div class="grid grid-cols-[90px_110px_90px_1fr] gap-4 border-b border-border px-5 py-3.5 sm:px-7 dark:border-gray-700">
                            <p class="text-xs font-black text-gray-900 dark:text-white">Name</p>
                            <p class="text-xs font-black text-gray-900 dark:text-white">Size</p>
                            <p class="text-xs font-black text-gray-900 dark:text-white">Pixels</p>
                            <p aria-hidden="true"></p>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($scale as [$name, $size, $px, $bar])
                                <div class="grid grid-cols-[90px_110px_90px_1fr] items-center gap-4 px-5 py-3 sm:px-7">
                                    <code class="text-sm font-bold text-gray-900 dark:text-white">{{ $name }}</code>
                                    <code class="text-sm text-gray-500 dark:text-gray-400">{{ $size }}</code>
                                    <code class="text-sm text-gray-500 dark:text-gray-400">{{ $px }}</code>
                                    <div><div class="{{ $bar }} h-4 rounded-xs bg-primary-600"></div></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </article>

            <x-docs.code>
                @verbatim
                {{-- Padding: ruang di dalam elemen --}}
                <div class="p-6">Padding 24px di semua sisi</div>
                <div class="px-4 py-2">Horizontal 16px, vertikal 8px</div>

                {{-- Skala yang sama dipakai untuk ukuran --}}
                <div class="size-10">40 × 40px</div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="prefix" title="Padding, margin, dan gap">
            <x-docs.lead>
                Satu skala yang sama dipakai oleh ketiga jenis jarak — cukup ganti prefix utility-nya.
            </x-docs.lead>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($prefixes as [$label, $utilities, $description])
                    <article class="ds-card p-5">
                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ $label }}</p>
                        <code class="mt-1 block text-xs font-bold text-primary-700 dark:text-primary-400">{{ $utilities }}</code>
                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">{{ $description }}</p>
                    </article>
                @endforeach
            </div>

            {{-- Di mode gelap docs ilustrasinya memakai ujung gelap tangga yang sama (primary-900, gray-700). --}}
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <article class="ds-card p-5">
                    <code class="text-sm font-bold text-primary-700 dark:text-primary-400">.p-4</code>
                    <div class="mt-3 rounded-lg bg-primary-100 dark:bg-primary-900">
                        <div class="p-4">
                            <div class="rounded-md bg-white px-3 py-2 text-xs font-bold text-gray-700 shadow-sm dark:bg-gray-700 dark:text-gray-200">Konten</div>
                        </div>
                    </div>
                </article>
                <article class="ds-card p-5">
                    <code class="text-sm font-bold text-primary-700 dark:text-primary-400">.mt-4</code>
                    <div class="mt-3 rounded-lg border border-dashed border-primary-300 p-2 dark:border-primary-700">
                        <div class="rounded-md bg-gray-100 px-3 py-2 text-xs font-bold text-gray-500 dark:bg-gray-700 dark:text-gray-300">Elemen pertama</div>
                        <div class="mt-4 rounded-md bg-primary-100 px-3 py-2 text-xs font-bold text-primary-800 dark:bg-primary-900 dark:text-primary-200">Elemen kedua</div>
                    </div>
                </article>
                <article class="ds-card p-5">
                    <code class="text-sm font-bold text-primary-700 dark:text-primary-400">.gap-4</code>
                    <div class="mt-3 flex gap-4 rounded-lg border border-dashed border-primary-300 p-2 dark:border-primary-700">
                        @foreach ([1, 2, 3] as $n)
                            <div class="flex-1 rounded-md bg-primary-100 px-3 py-2 text-center text-xs font-bold text-primary-800 dark:bg-primary-900 dark:text-primary-200">{{ $n }}</div>
                        @endforeach
                    </div>
                </article>
            </div>

            <x-docs.code>
                @verbatim
                {{-- Gap — utamakan ini di flex/grid daripada margin --}}
                <div class="flex gap-3">...</div>
                <div class="grid grid-cols-3 gap-6">...</div>

                {{-- Margin & space-y --}}
                <div class="mt-8">Margin atas 32px</div>
                <div class="space-y-4">Setiap anak berjarak 16px</div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="prinsip" title="Prinsip penggunaan">
            <x-docs.principles>
                <x-docs.principle>Selalu pakai langkah pada skala — hindari nilai arbitrer seperti {!! $code('p-[13px]') !!} agar ritme 4px terjaga.</x-docs.principle>
                <x-docs.principle>Langkah kecil (<strong class="text-gray-900 dark:text-white">1–4</strong>) untuk jarak dalam komponen, sedang (<strong class="text-gray-900 dark:text-white">5–12</strong>) antarkomponen, besar (<strong class="text-gray-900 dark:text-white">14+</strong>) antarseksi.</x-docs.principle>
                <x-docs.principle>Pilih {!! $code('gap-*') !!} dibanding margin antaritem di flex/grid — lebih mudah dirawat dan tidak menimbulkan margin ganda.</x-docs.principle>
                <x-docs.principle>Elemen dengan fungsi setara harus memakai jarak yang sama agar hierarki konsisten.</x-docs.principle>
            </x-docs.principles>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
