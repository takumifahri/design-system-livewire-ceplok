@php
    use App\Docs\Navigation;
    use App\Docs\Theme;

    $install = 'composer require ceplok-ui/design-kit-livewire';

    // Komponen kit di halaman ini hanya pajangan tanpa cuplikan kode, jadi ikut
    // memakai `dark-mode` di mode gelap docs. Container dibiarkan: di sini ia
    // pembungkus tata letak, dan versi gelapnya memberi latar gray-700. Button
    // kit Livewire belum punya `dark-mode`, jadi tombolnya tetap terang.
    $docsDark = Theme::dark();

    // Angka diturunkan dari sumber aslinya — navigasi dan berkas ikon kit —
    // supaya tidak pernah basi saat halaman atau ikon baru ditambahkan.
    // Sub-halaman dihitung satuan, induknya tidak: Input Field Form adalah tiga elemen.
    $count = fn (string $area) => collect(Navigation::sidebars()[$area]['items'])
        ->where('label', '!=', 'Overview')
        ->sum(fn (array $item) => count($item['children'] ?? []) ?: 1);
    $svg = base_path('vendor/ceplok-ui/design-kit-livewire/resources/svg');
    $icons = count(glob($svg.'/flowbite/*.svg') ?: []);
    $brands = count(glob($svg.'/brands/*.svg') ?: []);

    $stats = [
        [$count('components'), 'Komponen', 'Siap pakai dari satu paket Composer'],
        [$count('form'), 'Elemen form', 'Input, select, radio, toggle, checkbox'],
        [$count('foundations'), 'Foundations', 'Warna, tipografi, spacing, dan lainnya'],
        [$icons, 'Ikon', "Set solid dan outline, plus {$brands} logo brand"],
    ];

    $features = [
        ['M12 3.5c3.8 3.2 6.5 6 6.5 9.5a6.5 6.5 0 1 1-13 0c0-3.5 2.7-6.3 6.5-9.5Z', 'Satu sumber token', 'Warna, jarak, radius, dan bayangan hidup di Foundations. Komponen tidak pernah memakai nilai lepas, jadi perubahan token langsung terasa di seluruh produk.'],
        ['M4 5.5h16v13H4v-13Zm5 0v13M4 10h5', 'Menyesuaikan ruangnya', 'Card, Footer, dan Container memakai container query — tata letaknya mengikuti lebar kolom tempatnya berada, bukan lebar layar.'],
        ['M12 4.5a7.5 7.5 0 1 1 0 15 7.5 7.5 0 0 1 0-15Zm-3 7.5 2 2 4-4', 'Aksesibilitas bawaan', 'Dibangun di atas elemen HTML asli, lengkap dengan label, role, dan fokus yang terlihat — tanpa perlu ditambahkan ulang di setiap halaman.'],
        ['M13 3 5 13.5h6L10 21l8-10.5h-6L13 3Z', 'Livewire tanpa lem', 'wire:model langsung bekerja di setiap elemen form dan komponen interaktif, begitu pula wire:dismiss dan wire:loading. Komponennya dirender server lewat Blaze, jadi tetap cepat walau dipakai ratusan kali di satu halaman.'],
    ];

    $sections = [
        ['foundations.index', 'Foundations', 'Warna, tipografi, spacing, border, elevation, dan ikon — dasar visual seluruh produk.', $count('foundations').' halaman'],
        ['components.index', 'Components', 'Container, Button, Badge, Dropdown, Alert, Toast, Card, Footer, dan lainnya beserta playground-nya.', $count('components').' komponen'],
        ['form.index', 'Form', 'Input field, select, radio, toggle, dan checkbox dengan state serta varian aplikasi.', $count('form').' elemen'],
    ];

    $steps = [
        ['Pasang paketnya', 'Satu dependensi Composer. Laravel, Livewire 4, dan Tailwind v4 sudah ada di proyekmu.', 'Terminal', $install],
        ['Sambungkan stylesheet-nya', 'Sekali saja di stylesheet utama. Baris @source yang membuat Tailwind ikut memindai view kit di vendor/.', 'app.css', <<<'CSS'
            /* Sekali saja, di stylesheet utama proyek */
            @import '../../vendor/ceplok-ui/design-kit-livewire/resources/css/styles.css';

            /* Tailwind v4 tidak memindai vendor/ — tambahkan view kit agar
               utility yang dipakai komponen ikut ter-generate */
            @source '../../vendor/ceplok-ui/design-kit-livewire/resources/views/**/*.blade.php';
            CSS],
        ['Daftarkan plugin Alpine-nya', 'Komponen interaktif — Dropdown, Select, Datepicker, Upload, Alert, Toast, dan lainnya — memakai Alpine. Bundel Livewire dan Alpine di app.js, lalu pakai @livewireScriptConfig di layout — bukan @livewireScripts — supaya Alpine tidak dimuat dua kali.', 'app.js', <<<'JS'
            import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
            import ceplok from '../../vendor/ceplok-ui/design-kit-livewire/resources/js/index.js';

            Alpine.plugin(ceplok);
            Livewire.start();
            JS],
        ['Pakai komponennya', 'Tidak ada import, provider, atau konfigurasi tambahan — tag komponennya langsung tersedia di view mana pun.', 'Blade', <<<'BLADE'
            <form wire:submit="simpan" class="space-y-4">
                <x-ceplok::input-field label="Nama lengkap" placeholder="Masukkan nama lengkap" wire:model="nama" />
                <div class="flex items-center gap-3">
                    <x-ceplok::button type="submit">Simpan</x-ceplok::button>
                    <x-ceplok::badge variant="success">Draf tersimpan</x-ceplok::badge>
                </div>
            </form>
            BLADE],
    ];

    // Potongan kode di jendela hero, sebagai data: animasinya perlu tahu
    // panjang tiap baris untuk mengetik — dan menghapus — per karakter.
    $key = 'text-primary-300';
    $str = 'text-green-300';
    $prop = 'text-purple-300';
    $tag = fn (string $name) => ['t' => $name, 'cls' => $key];
    $attr = fn (string $name, string $value, string $indent = '') => array_values(array_filter([
        $indent !== '' ? ['t' => $indent] : null,
        ['t' => $name, 'cls' => $prop],
        ['t' => '='],
        ['t' => "\"{$value}\"", 'cls' => $str],
    ]));
    $preamble = [
        [$tag('<h3>'), ['t' => 'Tulis sekali. Pakai di mana saja.'], $tag('</h3>')],
        [$tag('<p '), ...$attr('class', 'text-body-sm text-gray-500'), $tag('>')],
        [['t' => '    Token Foundations → Tailwind v4']],
        [$tag('</p>')],
        [],
    ];
    // Selang-seling antara komponen tampilan dan elemen form, urut seperti di docs React.
    $snippets = array_map(fn (array $snippet) => ['file' => $snippet[0], 'lines' => [...$preamble, ...$snippet[1]]], [
        ['pilih-aplikasi.blade.php', [
            [$tag('<x-ceplok::select')],
            $attr('label', 'Pilih aplikasi', '    '),
            [['t' => '    '], ['t' => ':options', 'cls' => $prop], ['t' => '='], ['t' => "\"['default', 'purple']\"", 'cls' => $str]],
            [$tag('/>')],
        ]],
        ['aksi-permohonan.blade.php', [
            [$tag('<x-ceplok::button'), $tag('>'), ['t' => 'Simpan'], $tag('</x-ceplok::button>')],
            [$tag('<x-ceplok::badge '), ...$attr('variant', 'success'), $tag('>')],
            [['t' => '    Tersimpan']],
            [$tag('</x-ceplok::badge>')],
        ]],
        ['data-pemohon.blade.php', [
            [$tag('<x-ceplok::input-field')],
            $attr('label', 'Nama lengkap', '    '),
            $attr('placeholder', 'Masukkan nama lengkap', '    '),
            [$tag('/>')],
        ]],
        ['ringkasan-layanan.blade.php', [
            [$tag('<x-ceplok::card')],
            $attr('title', 'Kartu ringkas', '    '),
            $attr('description', 'Judul dan deskripsi opsional.', '    '),
            [$tag('/>')],
        ]],
        ['preferensi-notifikasi.blade.php', [
            [$tag('<x-ceplok::checkbox')],
            $attr('label', 'Kirim salinan ke email', '    '),
            [['t' => '    '], ['t' => 'checked', 'cls' => $prop]],
            [$tag('/>')],
            [$tag('<x-ceplok::toggle '), ...$attr('label', 'Notifikasi'), $tag(' />')],
        ]],
    ]);
    $maxLines = max(array_map(fn (array $snippet) => count($snippet['lines']), $snippets));
@endphp

<x-docs.layout title="Home">
    <div aria-hidden="true" class="pointer-events-none fixed inset-x-0 top-0 z-50 h-0.75 bg-transparent">
        <div x-scroll-progress class="ds-progress h-full bg-primary-600"></div>
    </div>

    {{-- ══ Hero ══ --}}
    {{-- Ujung bawah gradiennya sewarna section angka di bawahnya, terang maupun gelap. --}}
    <section x-parallax class="relative overflow-hidden border-b border-border bg-linear-to-b from-primary-50 via-white to-white px-5 py-16 sm:px-8 lg:px-12 lg:py-24 xl:px-14 dark:border-gray-700 dark:from-primary-900/60 dark:via-gray-800 dark:to-gray-800">
        {{-- Latar: kisi halus, gumpalan cahaya, dan sapuan sinar — makin dalam makin pelan bergeser saat digulir. --}}
        <x-docs.aurora />

        {{-- Lencana CEPLOK sebagai cap air yang sangat samar; hanya dari xl, di bawahnya bertabrakan dengan jendela kode. --}}
        <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="pointer-events-none absolute -top-16 -right-40 hidden w-152 opacity-[0.035] xl:block">

        <x-ceplok::container :padded="false" class="relative">
            {{-- grid-cols-1 (minmax(0,1fr)) wajib di bawah lg: tanpa itu kolom implisitnya
                 melebar mengikuti baris kode terpanjang di jendela kode dan hero meluber dari layar. --}}
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,30rem)] lg:gap-16">
                <div>
                    @php $headline = 'Menyatukan layanan digital lewat desain dan kode'; @endphp
                    {{-- Satu baris tanpa spasi antar-span: jarak antarkata sudah dari mr-[0.26em]. --}}
                    <h1 x-split-words data-delay="0.15" aria-label="{{ $headline }}" class="mt-5 text-[clamp(2.25rem,5vw,3.5rem)] leading-[1.05] font-black tracking-tight text-gray-900 dark:text-white">@foreach (explode(' ', $headline) as $word)<span aria-hidden="true" class="mr-[0.26em] inline-flex overflow-hidden pb-[0.12em] align-bottom last:mr-0"><span data-word class="inline-block">{{ $word }}</span></span>@endforeach</h1>

                    <div x-reveal data-delay="0.5">
                        <p class="mt-5 max-w-xl text-body-lg text-gray-600 dark:text-gray-300">
                            Satu sumber visual dan komponen yang konsisten untuk membangun
                            layanan yang jelas, inklusif, dan mudah dikenali. Token,
                            komponen, dan dokumentasinya tinggal dipakai — tidak perlu
                            dirancang ulang tiap proyek.
                        </p>
                    </div>

                    {{-- Perintah pasang dengan tombol salin --}}
                    <div x-reveal data-delay="0.75">
                        <button
                            type="button"
                            x-on:click="copy(@js($install), 'install')"
                            aria-label="Salin perintah: {{ $install }}"
                            x-bind:aria-label="copied === 'install' ? 'Perintah tersalin' : @js('Salin perintah: '.$install)"
                            class="mt-8 inline-flex max-w-full items-center gap-3 rounded-lg border border-border bg-surface px-4 py-2.5 font-mono text-sm text-gray-700 transition-colors hover:border-primary-300 hover:bg-primary-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:border-primary-700 dark:hover:bg-gray-900"
                        >
                            <span class="text-gray-400 dark:text-gray-500">$</span>
                            <span class="truncate">{{ $install }}</span>
                            {{-- Ikon salin dan ceklis ditumpuk di sel grid yang sama, bergantian lewat opacity + scale. --}}
                            <span class="grid size-4 shrink-0 -translate-y-px place-items-center *:col-start-1 *:row-start-1 *:size-4 *:transition-[opacity,scale] *:duration-200">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true" class="text-gray-400" x-bind:class="copied === 'install' ? 'scale-50 opacity-0' : 'scale-100 opacity-100'">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V5.5A1.5 1.5 0 0 1 10.5 4h8A1.5 1.5 0 0 1 20 5.5v8a1.5 1.5 0 0 1-1.5 1.5H15M5.5 9h8A1.5 1.5 0 0 1 15 10.5v8a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 18.5v-8A1.5 1.5 0 0 1 5.5 9Z" />
                                </svg>
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-hidden="true" class="scale-50 text-green-600 opacity-0 dark:text-green-400" x-bind:class="copied === 'install' ? 'scale-100 opacity-100' : 'scale-50 opacity-0'">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4.5 4.5L19 7" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div x-reveal data-delay="0.65" class="mt-4 flex flex-wrap items-center gap-3">
                        <span x-magnetic="12" class="ds-magnetic inline-flex">
                            <span class="inline-flex">
                                <x-ceplok::button as="anchor" :href="route('foundations.index')" wire:navigate variant="outline">Lihat Foundations</x-ceplok::button>
                            </span>
                        </span>
                        <span x-magnetic class="ds-magnetic inline-flex">
                            <span class="inline-flex">
                                <x-ceplok::button as="anchor" :href="route('components.index')" wire:navigate>Mulai pakai</x-ceplok::button>
                            </span>
                        </span>
                    </div>
                </div>

                {{-- x-reveal menggeser pembungkus ini, x-tilt memutar pelat di dalamnya:
                     transform keduanya ada di elemen berbeda, jadi tidak saling menimpa. --}}
                <div x-tilt x-reveal data-delay="0.4" class="ds-tilt">
                    <div class="ds-tilt-plate">
                        {{-- Jendela kode yang mengetik sendiri; logikanya di resources/js/docs/motion.js (codeWindow). --}}
                        <div x-data="docsCodeWindow(@js($snippets))" class="relative">
                            <div class="relative overflow-hidden rounded-2xl bg-gray-900 shadow-[0_2px_4px_rgb(17_24_39/0.06),0_12px_28px_rgb(17_24_39/0.12),0_36px_64px_rgb(17_24_39/0.16)] ring-1 ring-gray-900/10 dark:ring-gray-700">
                                <div class="relative z-10 flex items-center gap-3 bg-gray-800/70 px-4 py-3">
                                    <span class="flex gap-1.5" aria-hidden="true">
                                        <span class="size-3 rounded-full bg-red-500"></span>
                                        <span class="size-3 rounded-full bg-yellow-300"></span>
                                        <span class="size-3 rounded-full bg-green-500"></span>
                                    </span>
                                    <span class="ml-1 inline-flex items-center gap-2 rounded-md bg-gray-900 px-3 py-1">
                                        {{-- Logo Laravel menandai berkas Blade. --}}
                                        {{ svg('brand-laravel', 'size-4 shrink-0 text-red-500') }}
                                        <span x-text="snippet.file" class="text-xs font-bold text-gray-200">{{ $snippets[0]['file'] }}</span>
                                    </span>
                                </div>

                                <pre class="ds-scroll-x relative z-10 overflow-x-auto p-5 font-mono text-xs leading-6 text-gray-300 sm:p-6"><code
                                    x-ref="code"
                                    data-typing
                                    x-bind:data-selected="selected ? '' : null"
                                    class="group/code relative block"
                                    style="height: {{ $maxLines * 1.5 }}rem"
                                >@foreach ($snippets as $n => $snippet)<span data-group="{{ $n }}" x-show="index === {{ $n }}" @if ($n > 0) style="display: none" @endif class="block">@foreach ($snippet['lines'] as $line)<span class="block h-6"><span data-line class="inline-block overflow-hidden align-top whitespace-pre group-data-selected/code:bg-primary-400/30">@foreach ($line as $token)<span @class([$token['cls'] ?? null])>{{ $token['t'] }}</span>@endforeach</span><span data-caret aria-hidden="true" class="ds-caret invisible border-l-2 border-primary-300">&#8203;</span></span>@endforeach</span>@endforeach</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-ceplok::container>
    </section>

    {{-- ══ Angka ringkas ══ --}}
    <section class="border-b border-border bg-surface dark:border-gray-700 dark:bg-gray-800">
        <x-ceplok::container class="py-10">
            {{-- Pembatas vertikal hanya saat keempatnya benar-benar sebaris (lg). --}}
            <dl x-reveal data-stagger class="grid grid-cols-2 gap-x-6 gap-y-8 lg:grid-cols-4 lg:divide-x lg:divide-border lg:gap-y-0 dark:divide-gray-700">
                @foreach ($stats as [$value, $label, $desc])
                    <div class="group relative lg:px-8 lg:first:pl-0 lg:last:pr-0">
                        <dd class="text-heading-2 font-black tracking-tight text-primary-700 dark:text-primary-400"><span x-count-up>{{ $value }}</span></dd>
                        <dt class="mt-1 text-sm font-black text-gray-900 dark:text-white">{{ $label }}</dt>
                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                        {{-- Garis yang memanjang saat kolomnya disentuh — tidak menggeser apa pun di sekitarnya. --}}
                        <span aria-hidden="true" class="mt-3 block h-0.5 w-8 origin-left scale-x-100 rounded-full bg-primary-200 transition-transform duration-500 group-hover:scale-x-[3] dark:bg-primary-800"></span>
                    </div>
                @endforeach
            </dl>
        </x-ceplok::container>
    </section>

    {{-- ══ Prinsip ══ --}}
    <x-ceplok::container class="py-14 lg:py-20">
        <div class="grid gap-10 lg:grid-cols-[minmax(0,20rem)_minmax(0,1fr)] lg:gap-16">
            {{-- `self-start` wajib: tanpa itu kolom ini meregang setinggi baris grid dan tidak bisa lengket. --}}
            <div class="lg:sticky lg:top-12 lg:self-start">
                <div class="mb-8 max-w-2xl">
                    <div x-reveal data-stagger>
                        <p class="ds-eyebrow">Prinsip</p>
                        <h2 class="mt-2 text-heading-2 font-black tracking-tight text-gray-900 dark:text-white">Rancang sekali, pakai di mana saja</h2>
                    </div>
                    <p class="mt-3 text-body-sm leading-6 text-gray-500 dark:text-gray-400">
                        Empat keputusan yang membuat design kit ini tetap konsisten saat
                        dipakai banyak tim sekaligus.
                    </p>
                </div>
            </div>

            <ol x-reveal data-stagger class="border-t border-border dark:border-gray-700">
                @foreach ($features as $i => [$icon, $title, $desc])
                    <li class="grid grid-cols-[auto_minmax(0,1fr)] gap-x-5 border-b border-border py-7 dark:border-gray-700">
                        <span class="inline-flex size-10 items-center justify-center rounded-lg bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-300">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                            </svg>
                        </span>
                        <div>
                            <div class="flex items-baseline gap-3">
                                <span aria-hidden="true" class="font-mono text-xs font-black text-primary-400">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="text-heading-4 font-black text-gray-900 dark:text-white">{{ $title }}</h3>
                            </div>
                            <p class="mt-2 max-w-2xl text-body-sm leading-6 text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </x-ceplok::container>

    {{-- ══ Komponen hidup ══ --}}
    <section class="border-y border-border bg-surface-subtle dark:border-gray-700 dark:bg-gray-900">
        <x-ceplok::container class="py-12 lg:py-16">
            <div class="mb-8 max-w-2xl">
                <div x-reveal data-stagger>
                    <p class="ds-eyebrow">Komponen</p>
                    <h2 class="mt-2 text-heading-2 font-black tracking-tight text-gray-900 dark:text-white">Semuanya sungguhan, bukan gambar</h2>
                </div>
                <p class="mt-3 text-body-sm leading-6 text-gray-500 dark:text-gray-400">
                    Yang tampil di bawah ini adalah komponen yang benar-benar dirender dari
                    <code class="text-xs font-bold text-gray-700 dark:text-gray-300">ceplok-ui/design-kit-livewire</code>
                    — sama persis dengan yang akan kamu pakai. Klik untuk membuka playground-nya.
                </p>
            </div>

            {{-- Enam kolom: 2+2+2 lalu 3+3 mengisi dua baris tepat, tanpa sel yang menganga. --}}
            <div x-reveal data-stagger class="grid gap-5 sm:grid-cols-2 lg:grid-cols-6">
                @foreach ([
                    ['Button', 'components.button', 'lg:col-span-2'],
                    ['Badge', 'components.badge', 'lg:col-span-2'],
                    ['Checkbox & Toggle', 'form.checkbox', 'lg:col-span-2'],
                    ['Input Field', 'form.input-field.input', 'sm:col-span-2 lg:col-span-3'],
                    ['Card', 'components.card', 'sm:col-span-2 lg:col-span-3'],
                ] as [$name, $route, $span])
                    {{-- Pembungkus ini yang dianimasikan masuk; kartunya bebas memakai transisi hover sendiri. --}}
                    <div class="{{ $span }}">
                        <div x-spotlight class="ds-spotlight ds-card group flex h-full flex-col gap-4 p-5 transition-[transform,box-shadow] duration-300 hover:-translate-y-1 hover:shadow-lg has-[a:focus-visible]:outline-2 has-[a:focus-visible]:outline-offset-2 has-[a:focus-visible]:outline-primary-600">
                            {{-- Gelap di mode gelap docs; komponen di dalamnya ikut memakai `dark-mode`. --}}
                            <div class="flex min-h-23 flex-wrap items-center gap-3 rounded-xl bg-surface-subtle p-4 dark:bg-gray-900" inert>
                                @switch($name)
                                    @case('Button')
                                        <x-ceplok::button variant="outline" size="s">Batal</x-ceplok::button>
                                        <x-ceplok::button size="s">Simpan</x-ceplok::button>
                                        @break
                                    @case('Badge')
                                        <x-ceplok::badge variant="success" :dark-mode="$docsDark">Aktif</x-ceplok::badge>
                                        <x-ceplok::badge variant="warning" :dark-mode="$docsDark">Menunggu</x-ceplok::badge>
                                        <x-ceplok::badge variant="danger" :dark-mode="$docsDark">Ditolak</x-ceplok::badge>
                                        <x-ceplok::badge variant="gray" :dark-mode="$docsDark">Draf</x-ceplok::badge>
                                        @break
                                    @case('Checkbox & Toggle')
                                        <div class="space-y-3">
                                            <x-ceplok::checkbox label="Kirim salinan ke email" checked :dark-mode="$docsDark" />
                                            <x-ceplok::toggle label="Notifikasi permohonan" checked :dark-mode="$docsDark" />
                                        </div>
                                        @break
                                    @case('Input Field')
                                        <div class="w-full max-w-sm">
                                            <x-ceplok::input-field label="Nama lengkap" placeholder="Masukkan nama lengkap" helper-text="Sesuai yang tertera pada KTP." :dark-mode="$docsDark" />
                                        </div>
                                        @break
                                    @case('Card')
                                        <div class="w-full">
                                            <x-ceplok::card title="Kartu ringkas" description="Judul, deskripsi, dan aksi yang semuanya opsional." :dark-mode="$docsDark" />
                                        </div>
                                        @break
                                @endswitch
                            </div>
                            <div class="mt-auto flex items-center justify-between">
                                <a href="{{ route($route) }}" wire:navigate class="text-sm font-black text-gray-900 after:absolute after:inset-0 after:rounded-2xl focus-visible:outline-none dark:text-white">{{ $name }}</a>
                                <span aria-hidden="true" class="text-xs font-bold text-primary-700 transition-transform group-hover:translate-x-0.5 dark:text-primary-400">Lihat →</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ceplok::container>
    </section>

    {{-- ══ Mulai cepat ══ --}}
    <x-ceplok::container class="py-14 lg:py-20">
        <div class="mb-8 max-w-2xl">
            <div x-reveal data-stagger>
                <p class="ds-eyebrow">Mulai cepat</p>
                <h2 class="mt-2 text-heading-2 font-black tracking-tight text-gray-900 dark:text-white">Empat langkah untuk komponen pertama</h2>
            </div>
            <p class="mt-3 text-body-sm leading-6 text-gray-500 dark:text-gray-400">
                Pasang paketnya lewat Composer, sambungkan stylesheet dan plugin Alpine-nya sekali di
                proyek, lalu pakai tag komponennya di view mana pun. Tidak ada provider atau
                konfigurasi tambahan.
            </p>
        </div>

        <ol class="relative ml-4 border-l border-border pl-8 sm:ml-5 sm:pl-10 dark:border-gray-700">
            @foreach ($steps as $i => [$title, $desc, $label, $code])
                <li x-reveal class="relative pb-10 last:pb-0">
                    {{-- Bulatan nomor tepat di atas garis: setengah lebarnya ditarik ke kiri dari tepi konten, ditambah 1px tebal garis. --}}
                    <span aria-hidden="true" class="absolute top-0 -left-[calc(2rem+1rem+0.5px)] inline-flex size-8 items-center justify-center rounded-full border border-border bg-surface font-mono text-xs font-black text-primary-700 sm:-left-[calc(2.5rem+1rem+0.5px)] dark:border-gray-700 dark:bg-gray-800 dark:text-primary-400">{{ $i + 1 }}</span>
                    <h3 class="text-heading-4 font-black text-gray-900 dark:text-white">{{ $title }}</h3>
                    <p class="mt-1 mb-4 text-body-sm leading-6 text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                    <x-docs.code :code="$code" :label="$label" flush />
                </li>
            @endforeach
        </ol>
    </x-ceplok::container>

    {{-- ══ Jelajahi ══ --}}
    <section class="border-t border-border bg-surface-subtle dark:border-gray-700 dark:bg-gray-900">
        <x-ceplok::container class="py-12 lg:py-16">
            <div class="mb-8 max-w-2xl">
                <div x-reveal data-stagger>
                    <p class="ds-eyebrow">Jelajahi</p>
                    <h2 class="mt-2 text-heading-2 font-black tracking-tight text-gray-900 dark:text-white">Mulai dari mana?</h2>
                </div>
                <p class="mt-3 text-body-sm leading-6 text-gray-500 dark:text-gray-400">Tiga pintu masuk, tergantung yang sedang kamu kerjakan.</p>
            </div>

            <div x-reveal data-stagger class="grid gap-5 sm:grid-cols-2">
                @foreach ($sections as [$route, $name, $desc, $badge])
                    {{-- Kartu terakhir yang sendirian di barisnya mengisi kedua kolom. --}}
                    <div x-spotlight @class([
                        'ds-spotlight ds-card group flex flex-col p-6 transition-[transform,box-shadow] duration-300 hover:-translate-y-1 hover:shadow-lg has-[a:focus-visible]:outline-2 has-[a:focus-visible]:outline-offset-2 has-[a:focus-visible]:outline-primary-600',
                        'sm:col-span-2' => $loop->last && $loop->odd,
                    ])>
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-heading-4 font-black text-gray-900 dark:text-white">
                                <a href="{{ route($route) }}" wire:navigate class="after:absolute after:inset-0 after:rounded-2xl focus-visible:outline-none">{{ $name }}</a>
                            </h3>
                            <x-ceplok::badge variant="gray" :dark-mode="$docsDark">{{ $badge }}</x-ceplok::badge>
                        </div>
                        <p class="mt-2 flex-1 text-body-sm leading-6 text-gray-500 dark:text-gray-400">{{ $desc }}</p>
                        <span aria-hidden="true" class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary-700 dark:text-primary-400">
                            Buka
                            <span class="transition-transform group-hover:translate-x-1">→</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </x-ceplok::container>
    </section>
</x-docs.layout>
