@php
    $svg = base_path('vendor/ceplok-ui/design-kit-livewire/resources/svg');
    // Diurutkan ulang setelah ekstensi dibuang. glob() mengurutkan nama berkas
    // utuh, jadi arrow-down-left.svg mendahului arrow-down.svg; urutan nama
    // tanpa ekstensi sama dengan urutan galeri di docs React.
    $names = function (string $pattern): array {
        $names = array_map(fn (string $file) => basename($file, '.svg'), glob($pattern) ?: []);
        sort($names, SORT_STRING);

        return $names;
    };

    // Set solid Flowbite ikut memuat 30 logo brand, dan kedua set memuat
    // apple-full. Logo-logo itu punya galerinya sendiri di bagian "Logo brand",
    // jadi di sini dikeluarkan supaya tiap ikon hanya muncul di satu tempat.
    $brands = $names($svg.'/brands/*.svg');
    $isBrand = fn (string $name) => in_array($name, [...$brands, 'apple-full'], true);
    $solid = array_values(array_filter(array_map(fn ($n) => substr($n, 2), $names($svg.'/flowbite/s-*.svg')), fn ($n) => ! $isBrand($n)));
    $outline = array_values(array_filter(array_map(fn ($n) => substr($n, 2), $names($svg.'/flowbite/o-*.svg')), fn ($n) => ! $isBrand($n)));

    $code = fn (string $c) => '<code class="text-xs font-bold text-gray-700">'.e($c).'</code>';

    // Tiga cara memanggil ikon. Teks tag komponen dan direktif ditulis di sini:
    // di luar blok PHP, Blade mengompilasinya sebagai komponen/direktif sungguhan.
    $ways = $code('<x-ceplok-o-home />').', lewat helper '.$code("svg('ceplok-o-home')").', atau direktif '.$code("@svg('ceplok-o-home')");

    // Cuplikan per baris pada bagian "Cara pakai". Ditulis di blok PHP ini karena
    // di luarnya Blade ikut mengompilasi teks tag komponen sebagai komponen.
    $snippets = [
        'component' => '<x-ceplok-o-home class="size-5" />',
        'color' => '<x-ceplok-s-bell class="size-5 text-primary-600" />',
        'pair' => "<x-ceplok-o-bell class=\"size-5 text-gray-500\" />\n<x-ceplok-s-bell class=\"size-5 text-primary-600\" />",
        'brand' => '<x-brand-github class="size-5" />',
        'brandColor' => "<x-brand-youtube class=\"size-5 text-[#FF0000]\" />\n<x-brand-whatsapp class=\"size-5 text-[#25D366]\" />\n<x-brand-linkedin class=\"size-5 text-[#0A66C2]\" />",
        'wrapper' => "<x-ceplok::icon class=\"text-gray-500\">\n    {{ svg('ceplok-o-search') }}\n</x-ceplok::icon>",
    ];
    $solidCode = "{{ svg('ceplok-s-bell', 'size-5 text-primary-600') }}\n\n<x-ceplok-s-bell class=\"size-5 text-primary-600\" />";
    $outlineCode = "{{ svg('ceplok-o-home', 'size-5 text-gray-700') }}\n\n<x-ceplok-o-search class=\"size-4 text-gray-500\" />";
    $brandCode = "{{ svg('brand-github', 'size-5') }}\n\n<x-brand-instagram class=\"size-5 text-purple-600\" />\n<x-brand-laravel class=\"size-5\" />";
    $a11yCode = <<<'BLADE'
        {{-- Ikon dekoratif — sembunyikan dari pembaca layar --}}
        <x-ceplok-o-home class="size-4" aria-hidden="true" />

        {{-- Ikon tanpa teks pendamping — wajib diberi label --}}
        <button type="button">
            <x-ceplok-o-search class="size-5" />
            <span class="sr-only">Cari</span>
        </button>
        BLADE;
@endphp

<x-docs.layout title="Icons">
    {{-- Pencarian menyaring ketiga galeri sekaligus; tiap petak membawa data-key tanpa tanda hubung. --}}
    <div x-data="{ q: '' }">
        <x-docs.page
            eyebrow="Foundations · Icons"
            title="Icons"
            description="Seluruh ikon dibawa paket kit — solid untuk status & penekanan, outline untuk aksi & navigasi, plus logo brand untuk tautan sosial. Semuanya terdaftar di blade-icons, jadi cukup dipanggil lewat namanya. Ikon mewarisi warna teks (currentColor)."
            :toc="[
                'solid' => 'Ikon solid',
                'outline' => 'Ikon outline',
                'brand' => 'Logo brand',
                'nama' => 'Nama ikon',
                'cara-pakai' => 'Cara pakai',
                'prinsip' => 'Prinsip penggunaan',
            ]"
        >
            <div>
                <label class="sr-only" for="icon-search">Cari ikon</label>
                <div class="relative max-w-md">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        {{ svg('ceplok-o-search', 'size-4') }}
                    </span>
                    <input
                        id="icon-search"
                        type="search"
                        x-model="q"
                        placeholder="Cari ikon… (mis. arrow, user, chart)"
                        class="w-full rounded-lg border border-border bg-surface py-2.5 pr-4 pl-9 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                    >
                </div>
            </div>

            @foreach ([
                ['solid', 'Ikon solid · '.count($solid), 's', $solid],
                ['outline', 'Ikon outline · '.count($outline), 'o', $outline],
            ] as [$id, $title, $style, $icons])
                <x-docs.section :id="$id" :title="$title">
                    @if ($style === 's')
                        <x-docs.lead>
                            Untuk status aktif, indikator, dan momen yang butuh bobot visual lebih. Arahkan kursor untuk
                            melihat nama ikonnya. Logo brand tidak ikut di sini — lihat bagian {!! $code('Logo brand') !!} di bawah.
                        </x-docs.lead>
                    @else
                        <x-docs.lead>Gaya default untuk aksi, navigasi, dan elemen antarmuka umum.</x-docs.lead>
                    @endif

                    <article class="ds-card p-5 sm:p-7">
                        <div
                            x-effect="const n = q.toLowerCase().replace(/[\s-]/g, ''); for (const tile of $el.children) tile.hidden = n !== '' && ! tile.dataset.key.includes(n)"
                            class="grid grid-cols-[repeat(auto-fill,minmax(44px,1fr))] gap-1"
                        >
                            @foreach ($icons as $name)
                                <div title="ceplok-{{ $style }}-{{ $name }}" data-key="{{ str_replace('-', '', $name) }}" class="flex aspect-square items-center justify-center rounded-lg text-gray-700 transition-colors hover:bg-primary-50 hover:text-primary-700">
                                    {{ svg("ceplok-{$style}-{$name}", 'size-5') }}
                                </div>
                            @endforeach
                        </div>
                    </article>

                    <x-docs.code :code="$style === 's' ? $solidCode : $outlineCode" />
                </x-docs.section>
            @endforeach

            <x-docs.section id="brand" :title="'Logo brand · '.count($brands)">
                <x-docs.lead>
                    Logo sosial dan teknologi untuk footer, tautan berbagi, dan halaman login — satu-satunya tempat
                    logo brand ditampilkan. Berbeda dari solid &amp; outline, logo brand memakai awalan
                    {!! $code('brand-') !!} dan sudah membawa {!! $code('aria-hidden="true"') !!}.
                </x-docs.lead>

                <article class="ds-card p-5 sm:p-7">
                    <div
                        x-effect="const n = q.toLowerCase().replace(/[\s-]/g, ''); for (const tile of $el.children) tile.hidden = n !== '' && ! tile.dataset.key.includes(n)"
                        class="grid grid-cols-[repeat(auto-fill,minmax(44px,1fr))] gap-1"
                    >
                        @foreach ($brands as $name)
                            <div title="brand-{{ $name }}" data-key="{{ str_replace('-', '', $name) }}" class="flex aspect-square items-center justify-center rounded-lg text-gray-700 transition-colors hover:bg-primary-50 hover:text-primary-700">
                                {{ svg("brand-{$name}", 'size-5') }}
                            </div>
                        @endforeach
                    </div>
                </article>

                <x-docs.code :code="$brandCode" />
            </x-docs.section>

            <x-docs.section id="nama" title="Nama ikon">
                <x-docs.lead>
                    Solid dan outline dibedakan lewat awalan nama — banyak ikon punya nama yang sama persis di
                    kedua set (mis. {!! $code('home') !!}, {!! $code('bell') !!}). Hanya ikon yang benar-benar dipakai yang ikut
                    terkirim ke halaman, karena semuanya dirender di server.
                </x-docs.lead>

                <article class="ds-card overflow-hidden">
                    <div class="ds-scroll-x overflow-x-auto">
                        <table class="w-full min-w-136 text-left text-sm">
                            <thead class="border-b border-border bg-surface-subtle text-xs font-black tracking-wide text-gray-500 uppercase">
                                <tr>
                                    <th class="px-5 py-3">Set</th>
                                    <th class="px-5 py-3">Nama</th>
                                    <th class="px-5 py-3">Contoh</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach ([['Solid', 'ceplok-s-{nama}', 'ceplok-s-bell'], ['Outline', 'ceplok-o-{nama}', 'ceplok-o-home'], ['Logo brand', 'brand-{nama}', 'brand-github']] as [$set, $pattern, $example])
                                    <tr>
                                        <td class="px-5 py-3 font-bold text-gray-900">{{ $set }}</td>
                                        <td class="px-5 py-3"><code class="text-xs font-bold text-primary-700">{{ $pattern }}</code></td>
                                        <td class="px-5 py-3"><code class="text-xs text-gray-500">{{ $example }}</code></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </article>
            </x-docs.section>

            <x-docs.section id="cara-pakai" title="Cara pakai">
                <x-docs.lead>
                    Setiap ikon bisa dipanggil sebagai komponen Blade {!! $ways !!} — ukuran dan warnanya diatur
                    lewat {!! $code('class') !!}.
                </x-docs.lead>

                <div class="space-y-4">
                    <x-docs.snippet :code="$snippets['component']">
                        <x-slot:description>Komponen Blade — ukuran diatur lewat class {!! $code('size-*') !!}.</x-slot:description>
                        <x-slot:preview>
                            <span title="ceplok-o-home" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('ceplok-o-home', 'size-6 text-gray-700') }}</span>
                        </x-slot:preview>
                    </x-docs.snippet>

                    <x-docs.snippet :code="$snippets['color']">
                        <x-slot:description>Warna mengikuti {!! $code('currentColor') !!} — atur lewat class {!! $code('text-*') !!}.</x-slot:description>
                        <x-slot:preview>
                            <span title="ceplok-s-bell" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('ceplok-s-bell', 'size-6 text-primary-600') }}</span>
                        </x-slot:preview>
                    </x-docs.snippet>

                    <x-docs.snippet :code="$snippets['pair']">
                        <x-slot:description>
                            Kedua set bisa dipakai bersamaan tanpa alias karena awalannya berbeda. Pola yang sering
                            muncul: outline untuk keadaan biasa, solid untuk keadaan aktif — misalnya lonceng
                            notifikasi yang berubah padat saat ada pesan baru.
                        </x-slot:description>
                        <x-slot:preview>
                            <div class="flex items-center">
                                <span title="ceplok-o-bell" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('ceplok-o-bell', 'size-6 text-gray-500') }}</span>
                                <span title="ceplok-s-bell" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('ceplok-s-bell', 'size-6 text-primary-600') }}</span>
                            </div>
                        </x-slot:preview>
                    </x-docs.snippet>

                    <x-docs.snippet :code="$snippets['brand']">
                        <x-slot:description>Logo brand dalam warna netral — ikut warna teks di sekitarnya, cocok untuk deretan tautan sosial yang tidak ingin mencolok.</x-slot:description>
                        <x-slot:preview>
                            <span title="brand-github" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('brand-github', 'size-6 text-gray-700') }}</span>
                        </x-slot:preview>
                    </x-docs.snippet>

                    <x-docs.snippet :code="$snippets['brandColor']">
                        <x-slot:description>
                            Logo brand dalam warna resmi perusahaannya, ditulis sebagai nilai arbitrer — kit tidak
                            menyediakan token untuk warna pihak lain. Jangan pakai {!! $code('text-brand') !!}: itu warna utama
                            kit ini, bukan warna logonya.
                        </x-slot:description>
                        <x-slot:preview>
                            {{-- Tiga petak 36px butuh 108px; -mx-4 meminjam padding di tiap sisi supaya tetap di tengah. --}}
                            <div class="-mx-4 flex items-center">
                                <span title="brand-youtube" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('brand-youtube', 'size-6 text-[#FF0000]') }}</span>
                                <span title="brand-whatsapp" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('brand-whatsapp', 'size-6 text-[#25D366]') }}</span>
                                <span title="brand-linkedin" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">{{ svg('brand-linkedin', 'size-6 text-[#0A66C2]') }}</span>
                            </div>
                        </x-slot:preview>
                    </x-docs.snippet>

                    <x-docs.snippet :code="$snippets['wrapper']">
                        <x-slot:description>
                            Alternatif lewat pembungkus {!! $code('x-ceplok::icon') !!} — ukurannya seragam (bawaan {!! $code('size-5') !!}),
                            otomatis {!! $code('aria-hidden') !!}, dan bisa membungkus SVG milik Anda sendiri.
                        </x-slot:description>
                        <x-slot:preview>
                            <span title="x-ceplok::icon" class="flex size-9 items-center justify-center rounded-lg transition-colors hover:bg-primary-50">
                                <x-ceplok::icon class="text-gray-500">{{ svg('ceplok-o-search') }}</x-ceplok::icon>
                            </span>
                        </x-slot:preview>
                    </x-docs.snippet>
                </div>

                <x-docs.code :code="$a11yCode" />
            </x-docs.section>

            <x-docs.section id="prinsip" title="Prinsip penggunaan">
                <x-docs.principles>
                    <x-docs.principle>Konsisten dalam satu konteks — jangan mencampur outline dan solid pada kelompok aksi yang sama.</x-docs.principle>
                    <x-docs.principle>Selaraskan ukuran ikon dengan teks: {!! $code('size-4') !!} untuk body small, {!! $code('size-5') !!} untuk body, {!! $code('size-6') !!} untuk heading.</x-docs.principle>
                    <x-docs.principle>Ikon dekoratif wajib {!! $code('aria-hidden="true"') !!}; ikon berdiri sendiri butuh label (mis. {!! $code('sr-only') !!}).</x-docs.principle>
                    <x-docs.principle>Jangan mengubah warna ikon terpisah dari teks pendampingnya — keduanya memakai token warna yang sama.</x-docs.principle>
                </x-docs.principles>
            </x-docs.section>
        </x-docs.page>
    </div>
</x-docs.layout>
