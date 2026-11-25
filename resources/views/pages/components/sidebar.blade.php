@php
    use App\Docs\Theme;

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
    $sidebarProps = [
        ['items', 'array', '[]', 'Daftar menu yang ditampilkan pada Sidebar.'],
        ['groups', 'array', 'null', 'Daftar grup menu; mendukung content separator.'],
        ['logo', 'Htmlable | slot', 'null', 'Logo atau identitas aplikasi pada bagian atas Sidebar.'],
        ['collapsed-logo', 'Htmlable | slot', 'null', 'Logo/mark ringkas untuk Sidebar collapsed.'],
        ['user', 'array', 'null', 'Informasi pengguna yang ditampilkan pada profile section.'],
        ['sticky', 'bool', 'false', 'Menempelkan Sidebar di atas viewport dan membatasi tingginya setinggi viewport.'],
        ['collapsed', 'bool', 'false', 'State controlled untuk membuka atau menutup Sidebar; gunakan bersama wire:collapse.'],
        ['show-collapse-button', 'bool', 'false', 'Menampilkan tombol collapse nonaktif; independen dari prop logo.'],
        ['footer', 'Htmlable | slot', 'null', 'Area di kaki Sidebar, di bawah daftar menu — versi aplikasi, tautan bantuan, atau catatan status.'],
        ['dark-mode', 'bool', 'false', 'Mengaktifkan tampilan mode gelap pada Sidebar secara manual.'],
        ['wire:collapse', 'event', '—', 'Menampilkan tombol collapse aktif dan dipicu saat tombol itu diklik (detail.collapsed); independen dari prop logo.'],
    ];

    $sidebarItemProps = [
        ['label', 'string', 'required', 'Teks menu yang ditampilkan.'],
        ['href', 'string', 'null', 'Tujuan tautan menu; diabaikan bila item memiliki children karena item menjadi tombol disclosure.'],
        ['external', 'bool', 'false', 'Menandai tautan eksternal; otomatis membuka tab baru dengan rel="noreferrer noopener".'],
        ['icon', 'Htmlable | string', 'null', 'Icon opsional di sebelah label menu.'],
        ['badge', 'Htmlable | string | int', 'null', 'Konten badge pada sisi kanan menu.'],
        ['active', 'bool', 'false', 'Mengaktifkan tampilan state aktif.'],
        ['disabled', 'bool', 'false', 'Menonaktifkan interaksi pada menu.'],
        ['children', 'array', 'null', 'Daftar submenu pada menu.'],
        ['defaultOpen', 'bool', 'false', 'Membuka submenu saat pertama dirender; parent juga otomatis terbuka bila ada child aktif.'],
        ['submenuToggleDisabled', 'bool', 'false', 'Menonaktifkan tombol buka/tutup submenu tanpa menonaktifkan menu.'],
        ['attributes', 'array', '[]', 'Atribut tambahan pada menu, mis. wire:click atau wire:navigate; padanan onClick.'],
    ];

    $sidebarUserProps = [
        ['name', 'string', 'required', 'Nama pengguna yang ditampilkan pada area profil.'],
        ['profileLabel', 'string', 'Lihat Profil', 'Label sekunder di bawah nama pengguna.'],
        ['avatar', 'string', 'null', 'URL gambar avatar pengguna.'],
        ['avatarSrc', 'string', 'null', 'Alias URL gambar avatar pengguna.'],
        ['avatarAlt', 'string', 'null', 'Teks alternatif untuk gambar avatar.'],
        ['initials', 'string', 'null', 'Inisial avatar fallback jika gambar tidak tersedia.'],
        ['href', 'string', 'null', 'Tautan navigasi profil jika dropdown tidak digunakan.'],
        ['external', 'bool', 'false', 'Menandai tautan eksternal (target="_blank" rel="noreferrer noopener").'],
        ['attributes', 'array', '[]', 'Atribut tambahan pada area profil, mis. wire:click; padanan onClick. Tanpa href, tautannya tidak melompat ke "#".'],
        ['items', 'array', 'null', 'Daftar item dropdown profil pengguna (seperti Profil, Pengaturan, Keluar).'],
        ['groups', 'array', 'null', 'Kelompok item dropdown profil pengguna dengan dukungan pemisah dan label grup.'],
    ];

    $sidebarUserItemProps = [
        ['label', 'string | Htmlable', 'required', 'Teks atau label item dropdown profil.'],
        ['description', 'string | Htmlable', 'null', 'Keterangan tambahan di bawah label item.'],
        ['icon', 'Htmlable | string', 'null', 'Icon opsional pada sisi kiri item.'],
        ['href', 'string', 'null', 'Tautan tujuan (jika item bertindak sebagai link).'],
        ['external', 'bool', 'false', 'Menandai tautan eksternal; membuka tab baru dengan rel="noreferrer noopener".'],
        ['tone', '"default" | "danger"', 'default', 'Variasi warna item (gunakan "danger" untuk aksi destruktif seperti Keluar).'],
        ['disabled', 'bool', 'false', 'Menonaktifkan item dropdown.'],
        ['attributes', 'array', '[]', "Atribut tambahan pada item dropdown, mis. ['wire:click' => 'logout']; padanan onClick."],
    ];

    $menuOnlyItems = array_map(function ($i) {
        return [
            'label' => "Menu {$i}",
            'href' => '#',
            'active' => ($i === 3),
        ];
    }, range(1, 14));

    $standardItems = array_values(array_filter($menuOnlyItems, fn ($it) => $it['label'] !== 'Menu 5'));

    $nestedItems = [
        ['label' => 'Menu 1', 'href' => '#'],
        ['label' => 'Menu 2', 'href' => '#'],
        [
            'label' => 'Menu 3',
            'active' => true,
            'defaultOpen' => true,
            'children' => [
                ['label' => 'Sub - Menu 1', 'href' => '#'],
                ['label' => 'Sub - Menu 2', 'href' => '#'],
                ['label' => 'Sub - Menu 3', 'href' => '#'],
            ],
        ],
        ['label' => 'Menu 4', 'href' => '#', 'badge' => 1],
        ['label' => 'Menu 5', 'href' => '#'],
        ['label' => 'Menu 6', 'href' => '#'],
        ['label' => 'Menu 7', 'href' => '#'],
        ['label' => 'Menu 8', 'href' => '#'],
    ];

    $baseWithIcons = [
        ['label' => 'Menu 1', 'href' => '#', 'icon' => 'ceplok-s-chart-pie'],
        ['label' => 'Menu 2', 'href' => '#', 'icon' => 'ceplok-s-clipboard'],
        [
            'label' => 'Menu 3',
            'icon' => 'ceplok-s-cart',
            'defaultOpen' => true,
            'children' => [
                ['label' => 'Sub - Menu 1', 'href' => '#'],
                ['label' => 'Sub - Menu 2', 'href' => '#'],
                ['label' => 'Sub - Menu 3', 'href' => '#'],
            ],
        ],
        ['label' => 'Menu 4', 'href' => '#', 'icon' => 'ceplok-s-inbox', 'badge' => 1],
        ['label' => 'Menu 5', 'href' => '#', 'icon' => 'ceplok-s-lock'],
        ['label' => 'Menu 6', 'href' => '#', 'icon' => 'ceplok-s-clipboard'],
        ['label' => 'Menu 7', 'href' => '#', 'icon' => 'ceplok-s-layers'],
        ['label' => 'Menu 8', 'href' => '#', 'icon' => 'ceplok-s-chart-pie'],
    ];

    $iconGroups = [
        ['id' => 'main', 'items' => array_slice($baseWithIcons, 0, 5)],
        ['id' => 'secondary', 'separator' => true, 'items' => array_slice($baseWithIcons, 5)],
    ];

    $separatorGroups = [
        [
            'id' => 'primary',
            'items' => [
                ['label' => 'Menu 4', 'href' => '#', 'icon' => 'ceplok-s-inbox', 'badge' => 1],
                ['label' => 'Menu 5', 'href' => '#', 'icon' => 'ceplok-s-lock'],
            ],
        ],
        [
            'id' => 'secondary',
            'separator' => true,
            'items' => [
                ['label' => 'Menu 6', 'href' => '#', 'icon' => 'ceplok-s-clipboard'],
                ['label' => 'Menu 7', 'href' => '#', 'icon' => 'ceplok-s-layers'],
                ['label' => 'Menu 8', 'href' => '#', 'icon' => 'ceplok-s-chart-pie'],
            ],
        ],
    ];

    $dummyUser = [
        'name' => 'Nama User',
        'profileLabel' => 'Lihat Profil',
        'href' => '#',
    ];

@endphp

<x-docs.layout title="Sidebar">
    <x-docs.page
        eyebrow="Components · Sidebar"
        title="Sidebar"
        description="Navigasi vertikal yang digunakan untuk menampilkan struktur menu utama aplikasi, profile pengguna, submenu, dan informasi tambahan."
        :toc="[
            'variants' => 'Variants',
            'menu' => 'Menu',
            'badge' => 'Badge',
            'separator' => 'Content Separator',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        {{-- SECTION 1: VARIANTS --}}
        <x-docs.section id="variants" title="Variants">
            <x-docs.lead>
                Sidebar tersedia dalam lima konfigurasi, dari navigasi sederhana sampai menu berikon yang
                dikelompokkan dengan content separator.
            </x-docs.lead>

            <div class="mb-4 grid gap-4 lg:grid-cols-2">
                {{-- Varian 1 --}}
                <article class="sidebar-component-preview overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">1. Menu Only</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">Navigasi dasar tanpa logo dan informasi akun.</p>
                    </div>
                    <div class="ds-scroll-x h-[440px] overflow-x-auto overflow-y-hidden bg-gray-50 dark:bg-gray-900">
                        <x-ceplok::sidebar class="!min-h-0 h-full shrink-0" :items="$menuOnlyItems" :dark-mode="$docsDark" />
                    </div>
                </article>

                {{-- Varian 2 --}}
                <article class="sidebar-component-preview overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">2. Menu, user info, logo web</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">Navigasi dasar dengan identitas aplikasi dan area akun.</p>
                    </div>
                    <div class="ds-scroll-x h-[440px] overflow-x-auto overflow-y-hidden bg-gray-50 dark:bg-gray-900">
                        <x-ceplok::sidebar
                            class="!min-h-0 h-full shrink-0"
                            :items="$standardItems"
                            :user="$dummyUser"
                            :dark-mode="$docsDark"
                            show-collapse-button
                        >
                            <x-slot:logo>
                                <span class="flex items-center gap-2">
                                    <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                                    <span class="text-sm font-black tracking-tight">CEPLOK</span>
                                </span>
                            </x-slot:logo>
                            <x-slot:collapsed-logo>
                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                            </x-slot:collapsed-logo>
                        </x-ceplok::sidebar>
                    </div>
                </article>

                {{-- Varian 3 --}}
                <article class="sidebar-component-preview overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">3. Multi-level menu, user info, logo web</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">Menu anak dipakai untuk mengelompokkan navigasi yang berkaitan.</p>
                    </div>
                    <div class="ds-scroll-x h-[440px] overflow-x-auto overflow-y-hidden bg-gray-50 dark:bg-gray-900">
                        <x-ceplok::sidebar
                            class="!min-h-0 h-full shrink-0"
                            :items="$nestedItems"
                            :user="$dummyUser"
                            :dark-mode="$docsDark"
                            show-collapse-button
                        >
                            <x-slot:logo>
                                <span class="flex items-center gap-2">
                                    <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                                    <span class="text-sm font-black tracking-tight">CEPLOK</span>
                                </span>
                            </x-slot:logo>
                            <x-slot:collapsed-logo>
                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                            </x-slot:collapsed-logo>
                        </x-ceplok::sidebar>
                    </div>
                </article>

                {{-- Varian 4 --}}
                <article class="sidebar-component-preview overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">4. Multi-level menu + icon, user info, logo web</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">Menu berikon dikelompokkan; separator membedakan area konten.</p>
                    </div>
                    <div class="ds-scroll-x h-[440px] overflow-x-auto overflow-y-hidden bg-gray-50 dark:bg-gray-900">
                        <x-ceplok::sidebar
                            class="!min-h-0 h-full shrink-0"
                            :groups="$iconGroups"
                            :user="$dummyUser"
                            :dark-mode="$docsDark"
                            show-collapse-button
                        >
                            <x-slot:logo>
                                <span class="flex items-center gap-2">
                                    <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                                    <span class="text-sm font-black tracking-tight">CEPLOK</span>
                                </span>
                            </x-slot:logo>
                            <x-slot:collapsed-logo>
                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                            </x-slot:collapsed-logo>
                        </x-ceplok::sidebar>
                    </div>
                </article>

                {{-- Varian 5 --}}
                <article class="sidebar-component-preview overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">5. Collapsed</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">Konfigurasi varian 4 dalam kondisi tertutup: icon, user, dan logo mark tetap terlihat.</p>
                    </div>
                    <div class="ds-scroll-x h-[440px] overflow-x-auto overflow-y-hidden bg-gray-50 dark:bg-gray-900">
                        <x-ceplok::sidebar
                            class="!min-h-0 h-full shrink-0"
                            :groups="$iconGroups"
                            :user="$dummyUser"
                            :dark-mode="$docsDark"
                            collapsed
                            show-collapse-button
                        >
                            <x-slot:logo>
                                <span class="flex items-center gap-2">
                                    <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                                    <span class="text-sm font-black tracking-tight">CEPLOK</span>
                                </span>
                            </x-slot:logo>
                            <x-slot:collapsed-logo>
                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                            </x-slot:collapsed-logo>
                        </x-ceplok::sidebar>
                    </div>
                </article>
            </div>
        </x-docs.section>

        {{-- SECTION 2: MENU --}}
        <x-docs.section id="menu" title="Menu">
            <x-docs.lead>
                Menu digunakan sebagai navigasi utama pada Sidebar. Setiap item dapat memiliki ikon,
                label, tautan, dan state interaksi.
            </x-docs.lead>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900 dark:text-white">Menu</h3>
                    <div class="mb-4 max-w-[280px] rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="space-y-1">
                            <div class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 dark:text-gray-50">
                                <x-ceplok-s-chart-pie class="size-5" />
                                <span>Menu 1</span>
                            </div>
                            <div class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 dark:text-gray-50">
                                <x-ceplok-s-clipboard class="size-5" />
                                <span>Menu 2</span>
                            </div>
                            <div class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 dark:text-gray-50">
                                <x-ceplok-s-inbox class="size-5" />
                                <span>Menu 3</span>
                            </div>
                        </div>
                    </div>

                    <x-docs.code>
                        @verbatim
                        $items = [
                            [⟦'label'⟧ => 'Menu 1', 'icon' => 'ceplok-s-chart-pie', 'href' => '#'],
                            [⟦'label'⟧ => 'Menu 2', 'icon' => 'ceplok-s-clipboard', 'href' => '#'],
                            [⟦'label'⟧ => 'Menu 3', 'icon' => 'ceplok-s-inbox', 'href' => '#'],
                        ];
                        @endverbatim
                    </x-docs.code>
                </div>

                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900 dark:text-white">Sub Menu</h3>
                    <div class="mb-4 max-w-[280px] overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                        <x-ceplok::sidebar
                            class="!min-h-0"
                            :dark-mode="$docsDark"
                            :items="[
                                [
                                    'label' => 'Menu 3',
                                    'icon' => 'ceplok-s-cart',
                                    'active' => true,
                                    'defaultOpen' => true,
                                    'submenuToggleDisabled' => true,
                                    'children' => [
                                        ['label' => 'Sub - Menu 1', 'href' => '#'],
                                        ['label' => 'Sub - Menu 2', 'href' => '#'],
                                    ],
                                ],
                            ]"
                        />
                    </div>

                    <x-docs.code>
                        @verbatim
                        [
                            'label' => 'Menu 3',
                            'icon' => 'ceplok-s-cart',
                            ⟦'children'⟧ => [
                                ['label' => 'Sub - Menu 1', 'href' => '#'],
                                ['label' => 'Sub - Menu 2', 'href' => '#'],
                            ],
                        ]
                        @endverbatim
                    </x-docs.code>
                </div>
            </div>
        </x-docs.section>

        {{-- SECTION 3: BADGE --}}
        <x-docs.section id="badge" title="Badge">
            <x-docs.lead>
                Badge dapat digunakan untuk menampilkan informasi singkat seperti jumlah notifikasi atau
                item baru pada menu tertentu.
            </x-docs.lead>

            <div class="mb-4 max-w-[280px] rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 dark:text-gray-50">
                    <x-ceplok-s-inbox class="size-5" />
                    <span class="flex-1">Menu 4</span>
                    <span class="flex min-w-5 items-center justify-center rounded-full bg-red-100 px-1.5 py-0.5 text-xs font-medium text-red-600">
                        1
                    </span>
                </div>
            </div>

            <x-docs.code>
                @verbatim
                $item = [
                    'label' => 'Menu 4',
                    'icon' => 'ceplok-s-inbox',
                    ⟦'badge'⟧ => 1,
                ];
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        {{-- SECTION 4: SEPARATOR --}}
        <x-docs.section id="separator" title="Content Separator">
            <x-docs.lead>
                Separator memisahkan kelompok menu yang berbeda. Tambahkan <code>'separator' => true</code> pada group yang ingin diberi garis pemisah di bagian atas.
            </x-docs.lead>

            <div class="mb-4 max-w-[280px] overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                <x-ceplok::sidebar class="!min-h-0" :groups="$separatorGroups" :dark-mode="$docsDark" />
            </div>

            <x-docs.code>
                @verbatim
                $groups = [
                    [
                        'id' => 'primary',
                        'items' => [
                            ['label' => 'Menu 4', 'href' => '#', 'icon' => 'ceplok-s-inbox', 'badge' => 1],
                            ['label' => 'Menu 5', 'href' => '#', 'icon' => 'ceplok-s-lock'],
                        ],
                    ],
                    [
                        'id' => 'secondary',
                        ⟦'separator' => true⟧,
                        'items' => [
                            ['label' => 'Menu 6', 'href' => '#', 'icon' => 'ceplok-s-clipboard'],
                            ['label' => 'Menu 7', 'href' => '#', 'icon' => 'ceplok-s-layers'],
                            ['label' => 'Menu 8', 'href' => '#', 'icon' => 'ceplok-s-chart-pie'],
                        ],
                    ],
                ];

                <x-ceplok::sidebar :groups="$groups"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        {{-- SECTION 5: DARK MODE --}}
        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-300">dark-mode</span> dapat digunakan untuk mengaktifkan warna gelap secara manual pada Sidebar.
                Komponen secara otomatis menyesuaikan warna ikon, teks aktif/inaktif, badge, profil, dan border untuk menjaga rasio kontras.
            </x-slot:lead>

            <div class="mb-4 grid gap-4 lg:grid-cols-2">
                <article class="sidebar-component-preview overflow-hidden rounded-xl border border-gray-700 bg-gray-800">
                    <div class="border-b border-gray-700 px-4 py-3">
                        <h3 class="text-sm font-bold text-white">1. Multi-level menu + icon, user info, logo web</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-400">Menu berikon dikelompokkan; separator membedakan area konten.</p>
                    </div>
                    <div class="ds-scroll-x h-[440px] overflow-x-auto overflow-y-hidden bg-gray-900">
                        <x-ceplok::sidebar
                            class="!min-h-0 h-full shrink-0"
                            :groups="$iconGroups"
                            :user="$dummyUser"
                            dark-mode
                            show-collapse-button
                        >
                            <x-slot:logo>
                                <span class="flex items-center gap-2">
                                    <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                                    <span class="text-sm font-black tracking-tight text-white">CEPLOK</span>
                                </span>
                            </x-slot:logo>
                            <x-slot:collapsed-logo>
                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                            </x-slot:collapsed-logo>
                        </x-ceplok::sidebar>
                    </div>
                </article>

                <article class="sidebar-component-preview overflow-hidden rounded-xl border border-gray-700 bg-gray-800">
                    <div class="border-b border-gray-700 px-4 py-3">
                        <h3 class="text-sm font-bold text-white">2. Collapsed</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-400">Konfigurasi varian 1 dalam kondisi tertutup: icon, user, dan logo terlihat.</p>
                    </div>
                    <div class="ds-scroll-x h-[440px] overflow-x-auto overflow-y-hidden bg-gray-900">
                        <x-ceplok::sidebar
                            class="!min-h-0 h-full shrink-0"
                            :groups="$iconGroups"
                            :user="$dummyUser"
                            collapsed
                            dark-mode
                            show-collapse-button
                        >
                            <x-slot:logo>
                                <span class="flex items-center gap-2">
                                    <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                                    <span class="text-sm font-black tracking-tight text-white">CEPLOK</span>
                                </span>
                            </x-slot:logo>
                            <x-slot:collapsed-logo>
                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                            </x-slot:collapsed-logo>
                        </x-ceplok::sidebar>
                    </div>
                </article>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::sidebar ⟦dark-mode⟧ :groups="$groups" />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        {{-- SECTION 6: PLAYGROUND & PENGGUNAAN --}}
        <livewire:playground.sidebar :dark="$docsDark" />

        {{-- SECTION 7: LIVEWIRE --}}
        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Sidebar dapat diintegrasikan secara penuh dengan Livewire untuk mengendalikan navigasi single-page, menangkap interaksi menu, dan merespons toggle collapse tanpa reload halaman.
            </x-docs.lead>

            <livewire:playground.sidebar-livewire :dark="$docsDark" />

            <x-docs.code>
                @verbatim
                <x-ceplok::sidebar
                    :collapsed="$collapsed"
                    ⟦wire:collapse⟧="$toggle('collapsed')"
                    :items="[
                        ['label' => 'Dashboard', 'icon' => 'ceplok-s-chart-pie', 'active' => $halaman === 'Dashboard',
                            '⟦attributes⟧' => ['wire:click' => "navigasi('Dashboard')"]],
                        ['label' => 'Pesanan', 'icon' => 'ceplok-s-cart', 'active' => $halaman === 'Pesanan',
                            '⟦attributes⟧' => ['wire:click' => "navigasi('Pesanan')"]],
                    ]"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>

        </x-docs.section>

        {{-- SECTION 8: PROPERTIES --}}
        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Referensi seluruh prop Sidebar, termasuk state container serta state per-item
                menu/submenu.
            </x-docs.lead>

            <div class="space-y-8">
                <div>
                    <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">Sidebar Props</h3>
                    <x-docs.props-table :rows="$sidebarProps" />
                </div>

                <div>
                    <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">SidebarItem Props</h3>
                    <x-docs.props-table :rows="$sidebarItemProps" />
                </div>

                <div>
                    <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">SidebarUser Props</h3>
                    <x-docs.props-table :rows="$sidebarUserProps" />
                </div>

                <div>
                    <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">SidebarUserItem Props</h3>
                    <x-docs.props-table :rows="$sidebarUserItemProps" />
                </div>
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
