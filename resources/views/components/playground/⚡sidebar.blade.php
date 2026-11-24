<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Sidebar: kontrol mengubah state konfigurasi, Livewire
 * merender ulang preview secara interaktif, dan blok Penggunaan menyusun kode
 * Blade yang setara dengan konfigurasi aktif.
 */
new class extends Component
{
    public string $collapse = 'expanded';

    public string $theme = 'light';

    public string $badge = 'show';

    public string $menuIcon = 'show';

    public string $userInfo = 'show';

    public string $logoWeb = 'show';

    public string $collapseButton = 'show';

    public string $separator = 'show';

    public string $activeMenu = 'Menu 1';

    /** Mode awal mengikuti mode docs halaman (lihat App\Docs\Theme). */
    public function mount(bool $dark = false): void
    {
        $this->theme = $dark ? 'dark' : 'light';
    }

    public function selectMenu(string $label): void
    {
        $this->activeMenu = $label;
    }

    public function toggleCollapse(): void
    {
        $this->collapse = ($this->collapse === 'expanded' ? 'collapsed' : 'expanded');
    }

    #[Computed]
    public function usage(): string
    {
        $resolvedCollapse = ($this->menuIcon === 'hide' ? 'expanded' : $this->collapse);
        $resolvedCollapseBtn = ($this->menuIcon === 'hide' ? 'hide' : $this->collapseButton);
        $isDark = ($this->theme === 'dark');
        $hasSep = ($this->separator === 'show');
        $hasIcons = ($this->menuIcon === 'show');
        $hasBadge = ($this->badge === 'show');
        $hasUser = ($this->userInfo === 'show');
        $hasLogo = ($this->logoWeb === 'show');

        $lines = [];
        $lines[] = '@php';
        $lines[] = '    $items = [';
        $lines[] = '        [\'label\' => \'Menu 1\', \'href\' => \'#\''.($hasIcons ? ', \'icon\' => \'ceplok-s-chart-pie\'' : '').'],';
        $lines[] = '        [\'label\' => \'Menu 2\', \'href\' => \'#\''.($hasIcons ? ', \'icon\' => \'ceplok-s-clipboard\'' : '').'],';
        $lines[] = '        [';
        $lines[] = '            \'label\' => \'Menu 3\',';
        if ($hasIcons) {
            $lines[] = '            \'icon\' => \'ceplok-s-cart\',';
        }
        $lines[] = '            \'defaultOpen\' => true,';
        $lines[] = '            \'children\' => [';
        $lines[] = '                [\'label\' => \'Sub - Menu 1\', \'href\' => \'#\'],';
        $lines[] = '                [\'label\' => \'Sub - Menu 2\', \'href\' => \'#\'],';
        $lines[] = '                [\'label\' => \'Sub - Menu 3\', \'href\' => \'#\'],';
        $lines[] = '            ],';
        $lines[] = '        ],';
        $lines[] = '        [\'label\' => \'Menu 4\', \'href\' => \'#\''.($hasIcons ? ', \'icon\' => \'ceplok-s-inbox\'' : '').($hasBadge ? ', \'badge\' => 1' : '').'],';
        $lines[] = '        [\'label\' => \'Menu 5\', \'href\' => \'#\''.($hasIcons ? ', \'icon\' => \'ceplok-s-lock\'' : '').'],';
        $lines[] = '        [\'label\' => \'Menu 6\', \'href\' => \'#\''.($hasIcons ? ', \'icon\' => \'ceplok-s-clipboard\'' : '').'],';
        $lines[] = '        [\'label\' => \'Menu 7\', \'href\' => \'#\', \'disabled\' => true'.($hasIcons ? ', \'icon\' => \'ceplok-s-layers\'' : '').'],';
        $lines[] = '        [\'label\' => \'Menu 8\', \'href\' => \'#\', \'disabled\' => true'.($hasIcons ? ', \'icon\' => \'ceplok-s-chart-pie\'' : '').'],';
        $lines[] = '    ];';

        if ($hasSep) {
            $lines[] = '';
            $lines[] = '    $groups = [';
            $lines[] = '        [\'id\' => \'main\', \'items\' => array_slice($items, 0, 5)],';
            $lines[] = '        [\'id\' => \'secondary\', ⟦\'separator\' => true⟧, \'items\' => array_slice($items, 5)],';
            $lines[] = '    ];';
        }

        if ($hasUser) {
            $lines[] = '';
            $lines[] = '    $user = [';
            $lines[] = '        \'name\' => \'Nama User\',';
            $lines[] = '        \'profileLabel\' => \'Lihat Profil\',';
            $lines[] = '        \'items\' => [';
            $lines[] = '            [\'label\' => \'Profil Saya\', \'href\' => \'#profile\'],';
            $lines[] = '            [\'label\' => \'Pengaturan Akun\', \'href\' => \'#settings\'],';
            $lines[] = '            [\'label\' => \'Dokumentasi\', \'href\' => \'https://example.com\', \'external\' => true],';
            $lines[] = '            [\'label\' => \'Keluar\', \'tone\' => \'danger\'],';
            $lines[] = '        ],';
            $lines[] = '    ];';
        }
        $lines[] = '@endphp';
        $lines[] = '';
        $lines[] = '<x-ceplok::sidebar';
        if ($isDark) {
            $lines[] = '    ⟦dark-mode⟧';
        }
        if ($hasSep) {
            $lines[] = '    :⟦groups⟧="$groups"';
        } else {
            $lines[] = '    :⟦items⟧="$items"';
        }
        if ($hasUser) {
            $lines[] = '    :⟦user⟧="$user"';
        }
        $lines[] = '    ⟦sticky⟧';
        if ($resolvedCollapse === 'collapsed') {
            $lines[] = '    ⟦collapsed⟧';
        }
        if ($resolvedCollapseBtn === 'show') {
            $lines[] = '    ⟦show-collapse-button⟧';
        }
        $lines[] = '>';
        if ($hasLogo) {
            $lines[] = '    <x-slot:⟦logo⟧>';
            $lines[] = '        <span class="flex items-center gap-2 font-bold text-sm">';
            $lines[] = '            <img src="/images/ceplok.svg" alt="CEPLOK" class="size-9 object-contain">';
            $lines[] = '            CEPLOK';
            $lines[] = '        </span>';
            $lines[] = '    </x-slot:logo>';
            $lines[] = '    <x-slot:⟦collapsed-logo⟧>';
            $lines[] = '        <img src="/images/ceplok.svg" alt="CEPLOK" class="size-9 object-contain">';
            $lines[] = '    </x-slot:collapsed-logo>';
        }
        $lines[] = '</x-ceplok::sidebar>';

        return implode("\n", $lines);
    }
};
?>

@php
    $resolvedCollapse = ($menuIcon === 'hide' ? 'expanded' : $collapse);
    $resolvedCollapseBtn = ($menuIcon === 'hide' ? 'hide' : $collapseButton);
    $isCollapsed = ($resolvedCollapse === 'collapsed');
    $isDark = ($theme === 'dark');

    $rawItems = [
        ['label' => 'Menu 1', 'href' => '#', 'icon' => ($menuIcon === 'show' ? 'ceplok-s-chart-pie' : null), 'attributes' => ['wire:click.prevent' => "selectMenu('Menu 1')"]],
        ['label' => 'Menu 2', 'href' => '#', 'icon' => ($menuIcon === 'show' ? 'ceplok-s-clipboard' : null), 'attributes' => ['wire:click.prevent' => "selectMenu('Menu 2')"]],
        [
            'label' => 'Menu 3',
            'icon' => ($menuIcon === 'show' ? 'ceplok-s-cart' : null),
            'defaultOpen' => true,
            'attributes' => ['wire:click' => "selectMenu('Menu 3')"],
            'children' => [
                ['label' => 'Sub - Menu 1', 'href' => '#', 'attributes' => ['wire:click.prevent' => "selectMenu('Sub - Menu 1')"]],
                ['label' => 'Sub - Menu 2', 'href' => '#', 'attributes' => ['wire:click.prevent' => "selectMenu('Sub - Menu 2')"]],
                ['label' => 'Sub - Menu 3', 'href' => '#', 'attributes' => ['wire:click.prevent' => "selectMenu('Sub - Menu 3')"]],
            ],
        ],
        ['label' => 'Menu 4', 'href' => '#', 'icon' => ($menuIcon === 'show' ? 'ceplok-s-inbox' : null), 'badge' => ($badge === 'show' ? 1 : null), 'attributes' => ['wire:click.prevent' => "selectMenu('Menu 4')"]],
        ['label' => 'Menu 5', 'href' => '#', 'icon' => ($menuIcon === 'show' ? 'ceplok-s-lock' : null), 'attributes' => ['wire:click.prevent' => "selectMenu('Menu 5')"]],
        ['label' => 'Menu 6', 'href' => '#', 'icon' => ($menuIcon === 'show' ? 'ceplok-s-clipboard' : null), 'attributes' => ['wire:click.prevent' => "selectMenu('Menu 6')"]],
        ['label' => 'Menu 7', 'href' => '#', 'icon' => ($menuIcon === 'show' ? 'ceplok-s-layers' : null), 'disabled' => true],
        ['label' => 'Menu 8', 'href' => '#', 'icon' => ($menuIcon === 'show' ? 'ceplok-s-chart-pie' : null), 'disabled' => true],
    ];

    $items = array_map(function ($item) use ($activeMenu) {
        $item['active'] = ($activeMenu === $item['label']);
        if (!empty($item['children'])) {
            $item['children'] = array_map(function ($sub) use ($activeMenu) {
                $sub['active'] = ($activeMenu === $sub['label']);
                return $sub;
            }, $item['children']);
        }
        return $item;
    }, $rawItems);

    $groups = ($separator === 'show') ? [
        ['id' => 'main', 'items' => array_slice($items, 0, 5)],
        ['id' => 'secondary', 'separator' => true, 'items' => array_slice($items, 5)],
    ] : null;

    $userData = ($userInfo === 'show') ? [
        'name' => 'Nama User',
        'profileLabel' => 'Lihat Profil',
        'items' => [
            ['label' => 'Profil Saya', 'href' => '#profile'],
            ['label' => 'Pengaturan Akun', 'href' => '#settings'],
            ['label' => 'Dokumentasi', 'href' => 'https://example.com', 'external' => true],
            ['label' => 'Keluar', 'tone' => 'danger'],
        ],
    ] : null;
@endphp

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Coba konfigurasi Sidebar melalui kontrol di bawah ini untuk melihat perubahan mode
            collapse, badge, separator, logo, user info, dan tombol collapse.
        </x-docs.lead>

        <x-docs.stage max-width="max-w-[768px]" min-width="768px" :dark="$isDark">
            <div
                wire:key="sidebar-stage-{{ $separator }}-{{ $resolvedCollapse }}-{{ $theme }}"
                style="height: 920px"
                @class([
                    'flex w-full items-stretch overflow-hidden rounded-xl border',
                    $isDark ? 'border-gray-700 bg-gray-900 text-white' : 'border-gray-200 bg-white text-gray-900',
                ])
            >
                <x-ceplok::sidebar
                    class="!min-h-0 h-full shrink-0"
                    :collapsed="$isCollapsed"
                    :dark-mode="$isDark"
                    :show-collapse-button="$resolvedCollapseBtn === 'show'"
                    :groups="$groups"
                    :items="$groups ? [] : $items"
                    :user="$userData"
                    wire:collapse="toggleCollapse"
                >
                    @if ($logoWeb === 'show')
                        <x-slot:logo>
                            <span class="flex items-center gap-2">
                                <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                                <span class="text-sm font-black tracking-tight">CEPLOK</span>
                            </span>
                        </x-slot:logo>
                        <x-slot:collapsed-logo>
                            <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                        </x-slot:collapsed-logo>
                    @endif
                </x-ceplok::sidebar>

                <div class="flex w-full flex-col gap-6 p-6 min-w-0">
                    <div class="flex flex-col gap-2">
                        <h2 @class(['text-xl font-bold', $isDark ? 'text-white' : 'text-gray-900'])>
                            {{ $activeMenu }}
                        </h2>
                        <p @class(['text-sm', $isDark ? 'text-gray-400' : 'text-gray-500'])>
                            Ini adalah simulasi konten untuk halaman {{ $activeMenu }}. Anda bisa menaruh konten aplikasi yang sebenarnya di sini.
                        </p>
                    </div>

                </div>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Sidebar">
                <x-docs.segmented
                    label="Pilih kondisi Sidebar"
                    model="collapse"
                    :value="$resolvedCollapse"
                    :disabled="$menuIcon === 'hide'"
                    :options="[
                        ['value' => 'expanded', 'label' => 'Expanded'],
                        ['value' => 'collapsed', 'label' => 'Collapsed'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Tampilan">
                <x-docs.segmented
                    label="Pilih tampilan"
                    model="theme"
                    :value="$theme"
                    :options="[
                        ['value' => 'light', 'label' => 'Light'],
                        ['value' => 'dark', 'label' => 'Dark'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Badge">
                <x-docs.segmented
                    label="Tampilkan badge"
                    model="badge"
                    :value="$badge"
                    :options="[
                        ['value' => 'show', 'label' => 'Ada'],
                        ['value' => 'hide', 'label' => 'Tidak Ada'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Icon menu">
                <x-docs.segmented
                    label="Tampilkan icon menu"
                    model="menuIcon"
                    :value="$menuIcon"
                    :options="[
                        ['value' => 'show', 'label' => 'Ada'],
                        ['value' => 'hide', 'label' => 'Tidak Ada'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="User info">
                <x-docs.segmented
                    label="Tampilkan informasi user"
                    model="userInfo"
                    :value="$userInfo"
                    :options="[
                        ['value' => 'show', 'label' => 'Ada'],
                        ['value' => 'hide', 'label' => 'Tidak Ada'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Logo web">
                <x-docs.segmented
                    label="Tampilkan logo web"
                    model="logoWeb"
                    :value="$logoWeb"
                    :options="[
                        ['value' => 'show', 'label' => 'Ada'],
                        ['value' => 'hide', 'label' => 'Tidak Ada'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Button collapse">
                <x-docs.segmented
                    label="Tampilkan tombol collapse"
                    model="collapseButton"
                    :value="$resolvedCollapseBtn"
                    :disabled="$menuIcon === 'hide'"
                    :options="[
                        ['value' => 'show', 'label' => 'Ada'],
                        ['value' => 'hide', 'label' => 'Tidak Ada'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Content separator">
                <x-docs.segmented
                    label="Tampilkan content separator"
                    model="separator"
                    :value="$separator"
                    :options="[
                        ['value' => 'show', 'label' => 'Ada'],
                        ['value' => 'hide', 'label' => 'Tidak Ada'],
                    ]"
                />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Bagian ini menampilkan contoh penggunaan Sidebar berdasarkan konfigurasi yang dipilih pada
            Playground.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
