@php
    $drawerProps = [
        ['open', 'bool', 'false', 'Menentukan apakah Drawer sedang terbuka. Mendukung wire:model dan x-model.'],
        ['position', '"right" | "left" | "top" | "bottom"', 'right', 'Arah kemunculan panel Drawer pada layar.'],
        ['size', '"s" | "m" | "l" | "xl" | "full"', 'm', 'Ukuran lebar (atau tinggi pada top/bottom) dari panel Drawer.'],
        ['close-on-overlay-click', 'bool', 'true', 'Jika true, mengklik backdrop akan menutup Drawer.'],
        ['close-on-esc', 'bool', 'true', 'Jika true, menekan tombol ESC akan menutup Drawer.'],
        ['eyebrow', 'string', 'null', 'Label kapital bagian atas header (mis. "MENU").'],
        ['header', 'Htmlable | slot', 'null', 'Elemen kustom untuk konten header.'],
        ['items', 'array', 'null', 'Daftar konfigurasi menu item (otomatis merender DrawerHeader & DrawerNavItem jika tanpa slot).'],
        ['theme', '"primary" | "purple" | "blue" | "gray"', 'primary', 'Skema warna item yang aktif.'],
        ['dark-mode', 'bool', 'false', 'Mengaktifkan tema gelap (latar gray-800, teks gray-300).'],
        ['footer', 'Htmlable | slot', 'null', 'Konten bagian bawah panel Drawer.'],
        ['wire:close', 'event', '—', 'Dipicu saat Drawer ditutup. Juga bisa x-on:close.'],
    ];

    $headerProps = [
        ['eyebrow', 'string', 'null', 'Teks label atas header bergaya kapital (mis. "MENU").'],
        ['close-label', 'string', 'Tutup drawer', 'Aria-label untuk tombol tutup.'],
        ['show-close-button', 'bool', 'true', 'Menampilkan atau menyembunyikan tombol ikon X.'],
        ['dark-mode', 'bool', 'false', 'Tema gelap untuk teks dan tombol header.'],
    ];

    $navItemProps = [
        ['icon', 'Htmlable | string', 'null', 'Ikon di sisi kiri label menu (nama ikon Flowbite atau SVG).'],
        ['label', 'string', 'required', 'Judul / teks item menu.'],
        ['active', 'bool', 'false', 'Menandai status aktif dengan latar pill berwarna.'],
        ['expanded', 'bool', 'false', 'Menentukan apakah sub-menu sedang terbuka (menampilkan chevron).'],
        ['collapsible', 'bool', 'false', 'Menampilkan ikon chevron tanda dropdown jika tidak memiliki anak.'],
        ['theme', '"primary" | "purple" | "blue" | "gray"', 'primary', 'Skema warna sorotan saat item status active.'],
        ['href', 'string', 'null', 'Link tujuan navigasi (mengubah elemen menjadi tag <a>).'],
        ['badge', 'Htmlable | slot', 'null', 'Elemen badge di sisi kanan label.'],
        ['dark-mode', 'bool', 'false', 'Tema gelap untuk item navigasi.'],
    ];

    $subItemProps = [
        ['label', 'string', 'required', 'Judul / teks sub-item menu.'],
        ['active', 'bool', 'false', 'Menandai status aktif.'],
        ['theme', '"primary" | "purple" | "blue" | "gray"', 'primary', 'Skema warna teks saat aktif.'],
        ['href', 'string', 'null', 'Link tujuan navigasi.'],
        ['dark-mode', 'bool', 'false', 'Tema gelap untuk sub-item.'],
    ];

    $triggerProps = [
        ['icon', 'Htmlable', 'null', 'Ikon kustom untuk tombol trigger (default icon hamburger ceplok-o-bars-from-left).'],
        ['dark-mode', 'bool', 'false', 'Tema gelap untuk tombol trigger.'],
    ];
@endphp

<x-docs.layout title="Drawer">
    <x-docs.page
        title="Drawer"
        description="Drawer adalah panel navigasi off-canvas di tepi layar yang slide-in untuk menampilkan menu navigasi dan submenu tanpa meninggalkan konteks utama halaman."
        :toc="[
            'variants' => 'Variants',
            'dark-mode' => 'Dark Mode',
            'menu' => 'Menu',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        {{-- SECTION 1: VARIANTS --}}
        <x-docs.section id="variants" title="Variants">
            <x-docs.lead>
                Drawer dapat berada dalam kondisi <strong>Terbuka (Open Drawer)</strong> saat dipanggil oleh pengguna, maupun <strong>Tertutup (Closed Drawer)</strong> dengan pemicu tombol hamburger menu pada header halaman.
            </x-docs.lead>

            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Variant 1: Terbuka --}}
                <article class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs">
                    <div class="shrink-0 border-b border-gray-200 bg-white px-4 py-3">
                        <h3 class="text-sm font-bold text-gray-900">1. Drawer Terbuka (Open Drawer)</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500">Drawer muncul dari sisi kanan secara mengambang (overlay) di atas tampilan layar mobile.</p>
                    </div>
                    <div class="flex h-[480px] w-full items-center justify-center py-4 bg-gray-50/80">
                        <div class="relative flex h-full w-[360px] max-w-full flex-col overflow-hidden rounded-none border-x border-gray-200 bg-white shadow-md">
                            <div class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                                        C
                                    </div>
                                    <span class="text-base font-bold tracking-tight text-gray-900">CEPLOK</span>
                                </div>
                                <x-ceplok::drawer.trigger />
                            </div>
                            <div class="flex-1 p-6 bg-white">
                                <h4 class="text-lg font-bold mb-2">Halaman Beranda</h4>
                                <p class="text-xs text-gray-500">Anda sedang berada di halaman utama.</p>
                            </div>
                            <div class="absolute inset-0 bg-gray-900/20 z-10 pointer-events-none"></div>
                            <div class="absolute right-0 top-0 bottom-0 w-[240px] h-full border-l border-gray-200 bg-white flex flex-col z-20 shadow-2xl">
                                <x-ceplok::drawer.header eyebrow="MENU" />
                                <div class="p-3 space-y-1 flex-1 overflow-y-auto">
                                    <x-ceplok::drawer.nav-item icon="ceplok-s-chart-pie" label="Menu 1" />
                                    <x-ceplok::drawer.nav-item icon="ceplok-s-file-lines" label="Menu 2" :expanded="true">
                                        <x-ceplok::drawer.sub-item label="sub menu 1" />
                                        <x-ceplok::drawer.sub-item label="sub menu 2" />
                                    </x-ceplok::drawer.nav-item>
                                    <x-ceplok::drawer.nav-item icon="ceplok-s-cart" label="Menu 3" active :expanded="true">
                                        <x-ceplok::drawer.sub-item label="sub menu 1" active />
                                        <x-ceplok::drawer.sub-item label="sub menu 2" />
                                    </x-ceplok::drawer.nav-item>
                                    <x-ceplok::drawer.nav-item icon="ceplok-s-inbox" label="Menu 4" />
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Variant 2: Tertutup --}}
                <article class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs">
                    <div class="shrink-0 border-b border-gray-200 bg-white px-4 py-3">
                        <h3 class="text-sm font-bold text-gray-900">2. Drawer Tertutup (Closed State / Page View)</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500">Tampilan utama layar mobile dengan topbar header dan icon hamburger drawer di bagian kanan.</p>
                    </div>
                    <div class="flex h-[480px] w-full items-center justify-center py-4 bg-gray-50/80">
                        <div class="relative flex h-full w-[360px] max-w-full flex-col overflow-hidden rounded-none border-x border-gray-200 bg-white shadow-md">
                            <div class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                                        C
                                    </div>
                                    <span class="text-base font-bold tracking-tight text-gray-900">CEPLOK</span>
                                </div>
                                <x-ceplok::drawer.trigger />
                            </div>
                            <div class="flex-1 p-6 bg-white">
                                <h4 class="text-lg font-bold mb-2">Halaman Beranda</h4>
                                <p class="text-xs text-gray-500">Anda sedang berada di halaman utama Beranda.</p>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </x-docs.section>

        {{-- SECTION 2: DARK MODE --}}
        <x-docs.section id="dark-mode" title="Dark Mode">
            <x-docs.lead>
                Drawer mendukung mode gelap penuh dengan warna latar gray-800, border gray-700, dan teks putih / gray-300 yang nyaman di mata.
            </x-docs.lead>

            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Dark Mode 1: Terbuka --}}
                <article class="flex flex-col overflow-hidden rounded-xl border border-gray-800 bg-gray-900 shadow-xs">
                    <div class="shrink-0 border-b border-gray-800 bg-gray-900 px-4 py-3">
                        <h3 class="text-sm font-bold text-white">1. Drawer Terbuka (Dark Mode)</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-400">Tampilan Drawer terbuka dengan tema gelap.</p>
                    </div>
                    <div class="flex h-[480px] w-full items-center justify-center py-4 bg-gray-950">
                        <div class="relative flex h-full w-[360px] max-w-full flex-col overflow-hidden rounded-none border-x border-white/10 bg-gray-800 shadow-2xl">
                            <div class="flex shrink-0 items-center justify-between border-b border-gray-800 bg-gray-900 px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                                        C
                                    </div>
                                    <span class="text-base font-bold tracking-tight text-white">CEPLOK</span>
                                </div>
                                <x-ceplok::drawer.trigger dark-mode />
                            </div>
                            <div class="flex-1 p-6 bg-gray-800">
                                <h4 class="text-lg font-bold text-white mb-2">Halaman Beranda</h4>
                                <p class="text-xs text-gray-400">Anda sedang berada di halaman utama.</p>
                            </div>
                            <div class="absolute inset-0 bg-gray-900/50 z-10 pointer-events-none"></div>
                            <div class="absolute right-0 top-0 bottom-0 w-[240px] h-full border-l border-gray-700 bg-gray-800 flex flex-col z-20 shadow-2xl">
                                <x-ceplok::drawer.header eyebrow="MENU" dark-mode />
                                <div class="p-3 space-y-1 flex-1 overflow-y-auto">
                                    <x-ceplok::drawer.nav-item icon="ceplok-s-chart-pie" label="Menu 1" dark-mode />
                                    <x-ceplok::drawer.nav-item icon="ceplok-s-file-lines" label="Menu 2" :expanded="true" dark-mode>
                                        <x-ceplok::drawer.sub-item label="sub menu 1" dark-mode />
                                        <x-ceplok::drawer.sub-item label="sub menu 2" dark-mode />
                                    </x-ceplok::drawer.nav-item>
                                    <x-ceplok::drawer.nav-item icon="ceplok-s-cart" label="Menu 3" active :expanded="true" dark-mode>
                                        <x-ceplok::drawer.sub-item label="sub menu 1" active dark-mode />
                                        <x-ceplok::drawer.sub-item label="sub menu 2" dark-mode />
                                    </x-ceplok::drawer.nav-item>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Dark Mode 2: Tertutup --}}
                <article class="flex flex-col overflow-hidden rounded-xl border border-gray-800 bg-gray-900 shadow-xs">
                    <div class="shrink-0 border-b border-gray-800 bg-gray-900 px-4 py-3">
                        <h3 class="text-sm font-bold text-white">2. Drawer Tertutup (Dark Mode)</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-400">Tampilan utama layar mobile dalam mode gelap.</p>
                    </div>
                    <div class="flex h-[480px] w-full items-center justify-center py-4 bg-gray-950">
                        <div class="relative flex h-full w-[360px] max-w-full flex-col overflow-hidden rounded-none border-x border-white/10 bg-gray-800 shadow-2xl">
                            <div class="flex shrink-0 items-center justify-between border-b border-gray-800 bg-gray-900 px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                                        C
                                    </div>
                                    <span class="text-base font-bold tracking-tight text-white">CEPLOK</span>
                                </div>
                                <x-ceplok::drawer.trigger dark-mode />
                            </div>
                            <div class="flex-1 p-6 bg-gray-800">
                                <h4 class="text-lg font-bold text-white mb-2">Halaman Beranda</h4>
                                <p class="text-xs text-gray-400">Anda sedang berada di halaman utama Beranda.</p>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </x-docs.section>

        {{-- SECTION 3: MENU --}}
        <x-docs.section id="menu" title="Menu">
            <x-docs.lead>
                Menu pada Drawer dapat dikonfigurasi melalui prop <code>items</code> (Data-driven array) atau disusun manual menggunakan <code>&lt;x-ceplok::drawer.nav-item&gt;</code> dan <code>&lt;x-ceplok::drawer.sub-item&gt;</code>.
            </x-docs.lead>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900">Menu Sederhana</h3>
                    <div class="mb-4 max-w-[300px] overflow-hidden rounded-xl border border-border bg-surface p-4 shadow-xs space-y-1">
                        <x-ceplok::drawer.nav-item icon="ceplok-s-chart-pie" label="Menu 1" />
                        <x-ceplok::drawer.nav-item icon="ceplok-s-file-lines" label="Menu 2" />
                        <x-ceplok::drawer.nav-item icon="ceplok-s-inbox" label="Menu 3" />
                    </div>

                    <x-docs.code>
                        @verbatim
                        <x-ceplok::drawer.nav-item icon="ceplok-s-chart-pie" label="Menu 1" />
                        <x-ceplok::drawer.nav-item icon="ceplok-s-file-lines" label="Menu 2" />
                        @endverbatim
                    </x-docs.code>
                </div>

                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900">Sub Menu (Dropdown)</h3>
                    <div class="mb-4 max-w-[300px] overflow-hidden rounded-xl border border-border bg-surface p-4 shadow-xs">
                        <x-ceplok::drawer.nav-item icon="ceplok-s-cart" label="Menu 3" :expanded="true">
                            <x-ceplok::drawer.sub-item label="sub menu 1" />
                            <x-ceplok::drawer.sub-item label="sub menu 2" />
                        </x-ceplok::drawer.nav-item>
                    </div>

                    <x-docs.code>
                        @verbatim
                        <x-ceplok::drawer.nav-item icon="ceplok-s-cart" label="Menu 3" :expanded="true">
                            <x-ceplok::drawer.sub-item label="sub menu 1" />
                            <x-ceplok::drawer.sub-item label="sub menu 2" />
                        </x-ceplok::drawer.nav-item>
                        @endverbatim
                    </x-docs.code>
                </div>
            </div>
        </x-docs.section>

        {{-- SECTION 4: PLAYGROUND --}}
        <livewire:playground.drawer />

        {{-- SECTION 5: LIVEWIRE --}}
        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Kendalikan buka/tutup Drawer dari state komponen Livewire dengan <span class="text-primary-500">wire:model</span> (atau <span class="text-primary-500">wire:model.live</span>).
                Event penutupan (<span class="text-primary-500">close</span>) saat tombol ×, backdrop, atau tombol Esc ditekan dapat ditangkap dengan <span class="text-primary-500">wire:close</span>.
            </x-docs.lead>
            <x-docs.demo>
                <livewire:playground.drawer-livewire />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::button wire:click="$set('buka', true)">
                    Buka Drawer (Livewire)
                </x-ceplok::button>

                <x-ceplok::drawer
                    ⟦wire:model.live⟧="buka"
                    ⟦wire:close⟧="closed"
                    position="right"
                    size="s"
                    eyebrow="MENU UTAMA"
                    :items="[
                        ['label' => 'Beranda', 'icon' => 'ceplok-s-chart-pie', 'attributes' => ['wire:click' => 'pilihMenu(\'Beranda\')']],
                        ['label' => 'Laporan', 'icon' => 'ceplok-s-file-lines', 'attributes' => ['wire:click' => 'pilihMenu(\'Laporan\')']],
                        ['label' => 'Pesan Masuk', 'icon' => 'ceplok-s-inbox', 'badge' => '3', 'attributes' => ['wire:click' => 'pilihMenu(\'Pesan Masuk\')']],
                    ]"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        {{-- SECTION 6: PROPERTIES --}}
        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>Daftar lengkap props untuk komponen Drawer dan subkomponen pendukungnya.</x-docs.lead>
            <div class="space-y-8">
                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900">Drawer Props</h3>
                    <x-docs.props-table :rows="$drawerProps" />
                </div>

                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900">DrawerHeader Props</h3>
                    <x-docs.props-table :rows="$headerProps" />
                </div>

                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900">DrawerNavItem Props</h3>
                    <x-docs.props-table :rows="$navItemProps" />
                </div>

                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900">DrawerSubItem Props</h3>
                    <x-docs.props-table :rows="$subItemProps" />
                </div>

                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900">DrawerTrigger Props</h3>
                    <x-docs.props-table :rows="$triggerProps" />
                </div>
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
