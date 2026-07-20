@php
    // Ikon outline/solid dari paket resmi themesberg/flowbite-blade-icons
    // (Composer, prefix "flowbite"); ikon brand/sosial milik design kit
    // sendiri (packages/php/resources/svg/brands, prefix "brand").
    $names = collect(\Illuminate\Support\Facades\File::files(base_path('vendor/themesberg/flowbite-blade-icons/resources/svg')))
        ->map(fn ($file) => $file->getFilenameWithoutExtension());

    $brandIcons = collect(\Illuminate\Support\Facades\File::files(base_path('vendor/tpl/design-kit/resources/svg/brands')))
        ->map(fn ($file) => $file->getFilenameWithoutExtension())
        ->values();

    // Paket flowbite lama menyertakan sebagian logo brand di set solid
    // (s-facebook, s-github, …) — dikeluarkan agar tidak tampil ganda.
    $solidIcons = $names
        ->filter(fn ($name) => str_starts_with($name, 's-'))
        ->reject(fn ($name) => $brandIcons->contains(substr($name, 2)))
        ->values();

    $outlineIcons = $names->filter(fn ($name) => str_starts_with($name, 'o-'))->values();

    // Cuplikan kode untuk tombol salin di tiap section.
    $usage = [
        'solid' => <<<'CODE'
        {{-- Ikon solid — prefix flowbite-s- --}}
        <x-flowbite-s-bell class="size-5 text-primary-600" />
        CODE,

        'outline' => <<<'CODE'
        {{-- Ikon outline — prefix flowbite-o- --}}
        <x-flowbite-o-home class="size-5 text-gray-700" />
        <x-flowbite-o-search class="size-4 text-gray-500" />
        CODE,

        'brand' => <<<'CODE'
        {{-- Logo brand — prefix brand- --}}
        <x-brand-github class="size-5" />
        CODE,

        'a11y' => <<<'CODE'
        {{-- Alternatif lewat directive atau helper blade-icons --}}
        @svg('flowbite-o-search', 'size-4 text-gray-500')

        {{-- Ikon dekoratif — sembunyikan dari pembaca layar --}}
        <x-flowbite-o-home class="size-4" aria-hidden="true" />

        {{-- Ikon tanpa teks pendamping — wajib diberi label --}}
        <button type="button">
            <x-flowbite-o-search class="size-5" />
            <span class="sr-only">Cari</span>
        </button>
        CODE,
    ];
@endphp

<x-docs-layout section="foundations" title="Icons">
    <x-doc-hero eyebrow="Foundations · Icons" title="Flowbite Icons" description="Set ikon resmi design system — gaya solid untuk status dan penekanan, gaya outline untuk aksi dan navigasi, plus logo brand untuk tautan sosial. Ikon mewarisi warna teks (currentColor) sehingga mudah diselaraskan dengan konteksnya." />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14" x-data="{ q: '' }">
        <div class="mb-8">
            <label class="sr-only" for="icon-search">Cari ikon</label>
            <div class="relative max-w-md">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                    {{ svg('flowbite-o-search', 'size-4') }}
                </span>
                <input id="icon-search" type="search" x-model="q" placeholder="Cari ikon… (mis. arrow, user, chart)" class="w-full rounded-lg border border-border bg-surface py-2.5 pr-4 pl-9 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none">
            </div>
        </div>

        <div class="mb-5">
            <p class="ds-eyebrow">Solid</p>
            <h3 class="mt-2 text-heading-3 font-black text-gray-900">Ikon solid · {{ $solidIcons->count() }}</h3>
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Untuk status aktif, indikator, dan momen yang butuh bobot visual lebih. Arahkan kursor untuk melihat nama komponennya.</p>
        </div>
        <article class="ds-card p-5 sm:p-7">
            <div class="grid grid-cols-[repeat(auto-fill,minmax(44px,1fr))] gap-1">
                @foreach ($solidIcons as $name)
                    <div class="flex aspect-square items-center justify-center rounded-lg text-gray-700 transition-colors hover:bg-primary-50 hover:text-primary-700" title="&lt;x-flowbite-{{ $name }} /&gt;" x-show="q === '' || '{{ $name }}'.includes(q.toLowerCase())">
                        {{ svg('flowbite-' . $name, 'size-5') }}
                    </div>
                @endforeach
            </div>
        </article>

        <x-doc-usage :code="$usage['solid']" />

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Outline</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Ikon outline · {{ $outlineIcons->count() }}</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Gaya default untuk aksi, navigasi, dan elemen antarmuka umum.</p>
            </div>
            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-[repeat(auto-fill,minmax(44px,1fr))] gap-1">
                    @foreach ($outlineIcons as $name)
                        <div class="flex aspect-square items-center justify-center rounded-lg text-gray-700 transition-colors hover:bg-primary-50 hover:text-primary-700" title="&lt;x-flowbite-{{ $name }} /&gt;" x-show="q === '' || '{{ $name }}'.includes(q.toLowerCase())">
                            {{ svg('flowbite-' . $name, 'size-5') }}
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <x-doc-usage :code="$usage['outline']" />

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Social icons</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Logo brand · {{ $brandIcons->count() }}</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Logo sosial dan teknologi untuk footer, tautan berbagi, dan halaman login. Tersedia lewat prefix <code class="text-xs font-bold text-gray-700">brand-</code> dari design kit.</p>
            </div>
            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-[repeat(auto-fill,minmax(44px,1fr))] gap-1">
                    @foreach ($brandIcons as $name)
                        <div class="flex aspect-square items-center justify-center rounded-lg text-gray-700 transition-colors hover:bg-primary-50 hover:text-primary-700" title="&lt;x-brand-{{ $name }} /&gt;" x-show="q === '' || '{{ $name }}'.includes(q.toLowerCase())">
                            {{ svg('brand-' . $name, 'size-5') }}
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <x-doc-usage :code="$usage['brand']" />

        <div class="mt-8">
            <div class="mb-5">
                <p class="ds-eyebrow">Usage</p>
                <h3 class="mt-2 text-heading-3 font-black text-gray-900">Cara pakai</h3>
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Ikon outline dan solid memakai prefix <code class="text-xs font-bold text-gray-700">flowbite-</code> diikuti <code class="text-xs font-bold text-gray-700">o-</code> atau <code class="text-xs font-bold text-gray-700">s-</code>; logo brand memakai prefix <code class="text-xs font-bold text-gray-700">brand-</code>.</p>
            </div>
            <article class="ds-card divide-y divide-border overflow-hidden">
                <div class="grid gap-3 p-5 sm:grid-cols-[1fr_auto] sm:items-center sm:p-6">
                    <div>
                        <code class="text-sm font-bold text-primary-700">&lt;x-flowbite-o-home class=&quot;size-5&quot; /&gt;</code>
                        <p class="mt-1 text-xs text-gray-500">Komponen Blade — ukuran diatur lewat class <code class="font-bold text-gray-700">size-*</code>.</p>
                    </div>
                    <span class="flex items-center gap-3 text-gray-700">{{ svg('flowbite-o-home', 'size-5') }}</span>
                </div>
                <div class="grid gap-3 p-5 sm:grid-cols-[1fr_auto] sm:items-center sm:p-6">
                    <div>
                        <code class="text-sm font-bold text-primary-700">&lt;x-flowbite-s-bell class=&quot;size-5 text-primary-600&quot; /&gt;</code>
                        <p class="mt-1 text-xs text-gray-500">Warna mengikuti <code class="font-bold text-gray-700">currentColor</code> — atur lewat class <code class="font-bold text-gray-700">text-*</code>.</p>
                    </div>
                    <span class="flex items-center gap-3 text-primary-600">{{ svg('flowbite-s-bell', 'size-5') }}</span>
                </div>
                <div class="grid gap-3 p-5 sm:grid-cols-[1fr_auto] sm:items-center sm:p-6">
                    <div>
                        <code class="text-sm font-bold text-primary-700">&lt;x-brand-github class=&quot;size-5&quot; /&gt;</code>
                        <p class="mt-1 text-xs text-gray-500">Logo brand dari design kit — pakai warna netral atau warna resmi brand.</p>
                    </div>
                    <span class="flex items-center gap-3 text-gray-700">{{ svg('brand-github', 'size-5') }}</span>
                </div>
                <div class="grid gap-3 p-5 sm:grid-cols-[1fr_auto] sm:items-center sm:p-6">
                    <div>
                        <code class="text-sm font-bold text-primary-700">@@svg('flowbite-o-search', 'size-4 text-gray-500')</code>
                        <p class="mt-1 text-xs text-gray-500">Alternatif lewat directive <code class="font-bold text-gray-700">&#64;svg</code> atau helper <code class="font-bold text-gray-700">svg()</code> dari blade-icons.</p>
                    </div>
                    <span class="flex items-center gap-3 text-gray-500">{{ svg('flowbite-o-search', 'size-4') }}</span>
                </div>
            </article>
        </div>

        <x-doc-usage :code="$usage['a11y']" />

        <div class="mt-8">
            <article class="ds-card p-6">
                <p class="ds-eyebrow">Prinsip penggunaan</p>
                <ul class="mt-5 space-y-4 text-sm leading-6 text-gray-600">
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Konsisten dalam satu konteks — jangan mencampur outline dan solid pada kelompok aksi yang sama.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Selaraskan ukuran ikon dengan teks di sampingnya: <code class="text-xs font-bold text-gray-700">size-4</code> untuk body small, <code class="text-xs font-bold text-gray-700">size-5</code> untuk body, <code class="text-xs font-bold text-gray-700">size-6</code> untuk heading.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Ikon dekoratif wajib disembunyikan dari pembaca layar (<code class="text-xs font-bold text-gray-700">aria-hidden="true"</code>); ikon yang berdiri sendiri butuh label, misalnya lewat <code class="text-xs font-bold text-gray-700">sr-only</code>.</span></li>
                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Jangan mengubah warna ikon secara terpisah dari teks pendampingnya — keduanya harus memakai token warna yang sama.</span></li>
                </ul>
            </article>
        </div>
    </div>
</x-docs-layout>
