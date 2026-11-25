@php
    use App\Docs\Theme;
    use CeplokUi\DesignKit\Support\Content;

    // Sama dengan isi preview Variants di DrawerPage kit React.
    $menuItems = [
        ['label' => 'Menu 1', 'icon' => 'ceplok-s-chart-pie'],
        ['label' => 'Menu 2', 'icon' => 'ceplok-s-file-lines', 'expanded' => false, 'children' => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']]],
        ['label' => 'Menu 3', 'icon' => 'ceplok-s-cart', 'active' => true, 'expanded' => true, 'children' => [['label' => 'sub menu 1', 'active' => true], ['label' => 'sub menu 2', 'active' => false]]],
        ['label' => 'Menu 4', 'icon' => 'ceplok-s-inbox'],
        ['label' => 'Menu 5', 'icon' => 'ceplok-s-lock', 'expanded' => false, 'children' => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']]],
    ];

    $drawerProps = [
        ['open', 'bool', 'false', 'Status buka Drawer; mendukung wire:model dan x-model.'],
        ['wire:close', 'event', '—', 'Dipicu saat Drawer ditutup lewat tombol X, backdrop, Esc, atau penutupan dialog lainnya; padanan onClose.'],
        ['position', '"right" | "left" | "top" | "bottom"', 'right', 'Arah kemunculan panel Drawer pada layar.'],
        ['size', '"s" | "m" | "l" | "xl" | "full"', 'm', 'Ukuran lebar (atau tinggi pada top/bottom) dari panel Drawer.'],
        ['close-on-overlay-click', 'bool', 'true', 'Jika true, mengklik backdrop akan menutup Drawer.'],
        ['close-on-esc', 'bool', 'true', 'Jika true, menekan tombol ESC akan menutup Drawer.'],
        ['eyebrow', 'string', 'null', 'Label kapital bagian atas header (mis. "MENU").'],
        ['header', 'Htmlable | slot', 'null', 'Elemen kustom untuk konten header.'],
        ['close-label', 'string', 'Tutup drawer', 'Aria-label untuk tombol tutup di header.'],
        ['show-close-button', 'bool', 'true', 'Menampilkan atau menyembunyikan tombol ikon X di header.'],
        ['items', 'array', 'null', 'Daftar konfigurasi menu item; lihat tabel Item di bawah.'],
        ['after-items', 'slot', 'null', 'Konten tambahan setelah seluruh items, di dalam body yang sama; padanan afterItems.'],
        ['wire:expanded-change', 'event', '—', 'Dipicu saat pengguna membuka atau menutup submenu, dengan detail.expanded dan detail.label; padanan onExpandedChange.'],
        ['theme', '"primary" | "purple" | "blue" | "gray"', 'primary', 'Skema warna item yang aktif.'],
        ['dark-mode', 'bool', 'false', 'Mengaktifkan tema gelap (latar gray-800, teks gray-50).'],
        ['footer', 'Htmlable | slot', 'null', 'Konten kaki Drawer, dipisah garis di bagian bawah.'],
        ['font', '"lato" | "inter" | "arial"', 'lato', 'Keluarga font panel dan seluruh item Drawer.'],
        ['preview', 'bool', 'false', 'Merender panel sebagai <div> statis di dalam wadahnya (bukan <dialog>), untuk pratinjau dan dokumentasi.'],
        ['slot', 'slot', '—', 'Konten kustom; menggantikan header dan items bawaan.'],
    ];

    $itemProps = [
        ['icon', 'Htmlable | string', 'null', 'Ikon di sisi kiri label menu.'],
        ['label', 'string', 'required', 'Judul / teks item menu.'],
        ['active', 'bool', 'false', 'Menandai status aktif dengan latar pill berwarna; otomatis aktif bila ada child aktif.'],
        ['defaultExpanded', 'bool', 'true bila ada children', 'Status submenu saat render pertama.'],
        ['expanded', 'bool', 'null', 'Status submenu terkendali: nilai awal, dan state ikut berubah bila server merender nilai lain. Gunakan bersama wire:expanded-change.'],
        ['collapsible', 'bool', 'false', 'Menampilkan ikon chevron tanda dropdown meski tidak memiliki anak.'],
        ['theme', '"primary" | "purple" | "blue" | "gray"', 'theme Drawer', 'Skema warna sorotan saat item status active.'],
        ['href', 'string', 'null', 'Link tujuan navigasi. Item yang memiliki children berperan sebagai disclosure, sehingga href diabaikan.'],
        ['target / rel', 'string', 'null', 'Atribut tautan untuk href, mis. target="_blank".'],
        ['badge', 'Htmlable | string', 'null', 'Elemen badge di sisi kanan label.'],
        ['disabled', 'bool', 'false', 'Menonaktifkan interaksi dan menampilkan status nonaktif.'],
        ['attributes', 'array', '[]', "Atribut tambahan pada item, mis. ['wire:click' => 'buka', 'x-on:click' => 'close()']; padanan onClick. Mengklik item tidak menutup Drawer dengan sendirinya."],
        ['children', 'array', 'null', 'Daftar sub-item ter-indentasi di bawah item; lihat tabel Sub-item.'],
    ];

    $subItemProps = [
        ['label', 'string', 'required', 'Judul / teks sub-item menu.'],
        ['active', 'bool', '—', 'Menandai status aktif. Bila tidak diisi, sub-item yang diklik menjadi aktif.'],
        ['href', 'string', 'null', 'Link tujuan navigasi.'],
        ['target / rel', 'string', 'null', 'Atribut tautan untuk href.'],
        ['disabled', 'bool', 'false', 'Menonaktifkan interaksi dan menampilkan status nonaktif.'],
        ['attributes', 'array', '[]', 'Atribut tambahan pada sub-item; padanan onClick.'],
    ];

    $propTables = [
        ['Drawer Props', $drawerProps],
        ['Item Props (items)', $itemProps],
        ['Sub-item Props (children)', $subItemProps],
    ];

    // Kerangka layar mobile untuk Variants dan Dark mode — padanan
    // VariantPreview, topbar, DrawerTrigger, dan DummyPageContent di
    // DrawerPage kit React. `stay` = section Dark mode (topbar gray-900).
    $previews = fn (bool $dark, bool $stay) => [
        ['title' => '1. Drawer Terbuka (Open Drawer)', 'description' => 'Drawer muncul dari sisi kanan secara mengambang (overlay) di atas tampilan layar mobile.', 'open' => true, 'dark' => $dark, 'stay' => $stay],
        ['title' => '2. Drawer Tertutup (Closed State / Page View)', 'description' => 'Tampilan utama layar mobile dengan topbar header dan icon hamburger drawer di bagian kanan.', 'open' => false, 'dark' => $dark, 'stay' => $stay],
    ];

    // Contoh di halaman ini mengikuti mode docs: bingkai preview dan
    // Playground mulai dari mode docs. Bagian Dark mode tetap gelap di kedua
    // mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Drawer">
    <x-docs.page
        eyebrow="Components · Drawer"
        title="Drawer"
        description="Drawer adalah panel navigasi off-canvas di tepi layar yang slide-in untuk menampilkan menu navigasi dan submenu tanpa meninggalkan konteks utama halaman."
        :toc="['variants' => 'Variants', 'dark-mode' => 'Dark mode', 'menu' => 'Menu', 'playground' => 'Playground', 'penggunaan' => 'Penggunaan', 'livewire' => 'Livewire', 'properties' => 'Properties']"
    >
        {{--
            Variants dan Dark mode memakai kerangka yang sama, jadi markupnya ditulis
            sekali di sini. Section Dark mode sama dengan x-docs.mode-section:
            selalu gelap di kedua mode docs. Seperti preview React, tombol X pada
            Drawer terbuka hanya tampilan: kliknya dihentikan di pembungkus.
        --}}
        @foreach ([
            ['id' => 'variants', 'title' => 'Variants', 'cards' => $previews($docsDark, false), 'lead' => 'Drawer dapat berada dalam kondisi <strong>Terbuka (Open Drawer)</strong> saat dipanggil oleh pengguna, maupun <strong>Tertutup (Closed Drawer)</strong> dengan pemicu tombol hamburger menu pada header halaman.'],
            ['id' => 'dark-mode', 'title' => 'Dark mode', 'cards' => $previews(true, true), 'lead' => 'Tampilan Drawer dalam mode gelap (<span class="text-primary-500 dark:text-primary-400">dark-mode</span>), menyesuaikan warna latar belakang, teks, dan status aktif.'],
        ] as $section)
            <x-docs.section :id="$section['id']" :title="$section['title']">
                <x-docs.lead>{!! $section['lead'] !!}</x-docs.lead>
                <div class="grid gap-6 lg:grid-cols-2">
                    @foreach ($section['cards'] as $card)
                        @php($dark = $card['dark'])
                        <article @class(['flex flex-col overflow-hidden rounded-xl border shadow-xs', $dark ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'])>
                            <div @class(['shrink-0 border-b px-4 py-3', $dark ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'])>
                                <h3 @class(['text-sm font-bold', $dark ? 'text-white' : 'text-gray-900'])>{{ $card['title'] }}</h3>
                                <p @class(['mt-1 text-xs leading-5', $dark ? 'text-gray-400' : 'text-gray-500'])>{{ $card['description'] }}</p>
                            </div>
                            <div @class(['ds-scroll-x h-[500px] w-full overflow-x-auto py-4', $dark ? 'bg-gray-900' : 'bg-gray-50/80'])>
                                <div x-data="{ drawerOpen: @js($card['open']) }" x-on:click.capture="$event.target.closest('[data-preview] > div:first-child button') && $event.stopPropagation()" style="min-width: 420px" @class(['relative mx-auto flex h-full w-[420px] flex-col overflow-hidden rounded-none border-x shadow-md', $dark ? 'border-white/10 bg-gray-800 shadow-2xl' : 'border-gray-200 bg-white'])>
                                    <div class="flex h-full w-full flex-col">
                                        <div @class(['flex shrink-0 items-center justify-between border-b px-4 py-3.5', $card['stay'] ? 'border-gray-800 bg-gray-900' : ($dark ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white')])>
                                            <div class="flex items-center gap-2.5">
                                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-7 shrink-0 object-contain">
                                                <span @class(['text-base font-bold tracking-tight', $dark ? 'text-white' : 'text-gray-900'])>CEPLOK</span>
                                            </div>
                                            {{-- Padanan DrawerTrigger di kit React. --}}
                                            <button type="button" aria-label="Buka menu drawer" @class(['inline-flex shrink-0 items-center justify-center rounded-lg p-1.5 transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600', $dark ? 'text-gray-200 hover:bg-gray-700 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'])><x-ceplok-o-bars-from-left class="size-6" aria-hidden="true" /></button>
                                        </div>
                                        <div @class(['min-w-0 flex-1 overflow-y-auto p-6', $dark ? 'bg-gray-600' : 'bg-white'])>
                                            <h4 @class(['mb-4 text-xl font-bold tracking-tight', $dark ? 'text-white' : 'text-gray-900'])>Halaman Beranda</h4>
                                            <p @class(['text-sm', $dark ? 'text-gray-400' : 'text-gray-500'])>Anda sedang berada di halaman utama Halaman Beranda.</p>
                                        </div>
                                    </div>
                                    @if ($card['open'])
                                        <div x-show="drawerOpen" @class(['pointer-events-none absolute inset-0 z-10', $dark ? 'bg-gray-900/40' : 'bg-gray-900/20'])></div>
                                        <x-ceplok::drawer preview x-model="drawerOpen" eyebrow="MENU" class="z-20" style="width: 250px" :dark-mode="$dark" :items="$menuItems" />
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                @if ($section['id'] === 'dark-mode')
                    <x-docs.code>
                        @verbatim
                        <x-ceplok::drawer ⟦dark-mode⟧ wire:model="open" />
                        @endverbatim
                    </x-docs.code>
                @endif
            </x-docs.section>
        @endforeach

        <x-docs.section id="menu" title="Menu">
            <x-docs.lead>Menu pada Drawer dikonfigurasi melalui prop <span class="text-primary-500 dark:text-primary-400">items</span> sebagai data-driven array. Gunakan <span class="text-primary-500 dark:text-primary-400">children</span> untuk membentuk submenu.</x-docs.lead>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900 dark:text-white">Menu</h3>
                    <div class="ds-scroll-x mb-4 overflow-x-auto">
                        <div class="w-[300px] space-y-1 overflow-hidden rounded-xl border border-border bg-surface p-4 shadow-xs dark:border-gray-700 dark:bg-gray-800">
                            @foreach ([['Menu 1', 'ceplok-s-chart-pie'], ['Menu 2', 'ceplok-s-file-lines'], ['Menu 3', 'ceplok-s-inbox']] as [$label, $icon])
                                <div class="group flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left text-base font-medium text-gray-900 transition-all hover:bg-blue-50 hover:text-blue-600 dark:text-gray-50 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <div class="flex min-w-0 flex-1 items-center gap-3"><span class="flex size-[18px] shrink-0 items-center justify-center text-gray-500 transition-colors group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-white">{!! Content::icon($icon) !!}</span><span class="truncate">{{ $label }}</span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <x-docs.code scroll>@verbatim
<x-ceplok::drawer
    :⟦items⟧="[
        [⟦'icon' => 'ceplok-s-chart-pie'⟧, 'label' => 'Menu 1'],
        [⟦'icon' => 'ceplok-s-file-lines'⟧, 'label' => 'Menu 2'],
        [⟦'icon' => 'ceplok-s-inbox'⟧, 'label' => 'Menu 3'],
    ]"
/>
@endverbatim</x-docs.code>
                </div>
                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900 dark:text-white">Sub Menu</h3>
                    <div class="ds-scroll-x mb-4 overflow-x-auto">
                        <div class="w-[300px] overflow-hidden rounded-xl border border-border bg-surface p-4 shadow-xs dark:border-gray-700 dark:bg-gray-800">
                            <div class="group flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left text-base font-medium text-gray-900 transition-all hover:bg-blue-50 hover:text-blue-600 dark:text-gray-50 dark:hover:bg-gray-700 dark:hover:text-white">
                                <div class="flex min-w-0 flex-1 items-center gap-3"><span class="flex size-[18px] shrink-0 items-center justify-center text-gray-500 transition-colors group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-white">{!! Content::icon('ceplok-s-cart') !!}</span><span class="truncate">Menu 3</span></div>
                                <span class="flex size-[18px] items-center justify-center text-gray-500 transition-all group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-white"><x-ceplok-o-chevron-up class="size-[18px]" /></span>
                            </div>
                            <div class="mt-1 space-y-1 pl-8">
                                @foreach (['sub menu 1', 'sub menu 2'] as $label)
                                    <div class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-base font-medium text-gray-900 transition-colors hover:bg-blue-50 hover:text-blue-600 dark:text-gray-50 dark:hover:bg-gray-700 dark:hover:text-white">{{ $label }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <x-docs.code scroll>@verbatim
<x-ceplok::drawer
    :⟦items⟧="[
        [
            ⟦'icon' => 'ceplok-s-cart'⟧, 'label' => 'Menu 3', ⟦'expanded' => true⟧,
            ⟦'children'⟧ => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']],
        ],
    ]"
/>
@endverbatim</x-docs.code>
                </div>
            </div>
        </x-docs.section>

        <livewire:playground.drawer :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Gunakan <span class="text-primary-500 dark:text-primary-400">wire:model.live</span> untuk mengikat status buka/tutup, dan kunci
                <span class="text-primary-500 dark:text-primary-400">attributes</span> pada item untuk memanggil aksi Livewire. <span class="text-primary-500 dark:text-primary-400">wire:close</span>
                menangkap penutupan dari tombol, backdrop, atau Esc.
            </x-docs.lead>
            <livewire:playground.drawer-livewire :dark="$docsDark" />
            <x-docs.code>
                @verbatim
                <x-ceplok::drawer
                    ⟦wire:model.live⟧="buka"
                    eyebrow="MENU"
                    :items="[
                        ['label' => 'Beranda', 'icon' => 'ceplok-s-chart-pie',
                            '⟦attributes⟧' => ['wire:click' => "pilihMenu('Beranda')"]],
                        ['label' => 'Laporan', 'icon' => 'ceplok-s-file-lines',
                            '⟦attributes⟧' => ['wire:click' => "pilihMenu('Laporan')"]],
                    ]"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>Daftar lengkap props untuk komponen Drawer serta kunci item dan sub-item menunya.</x-docs.lead>
            <div class="space-y-6">
                @foreach ($propTables as [$heading, $rows])
                    <div>
                        <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">{{ $heading }}</h3>
                        <x-docs.props-table :rows="$rows" />
                    </div>
                @endforeach
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
