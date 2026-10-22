<?php

use CeplokUi\DesignKit\Enums\DrawerNavItemTheme;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Drawer: setiap kontrol mengubah satu properti, Livewire
 * merender ulang tampilannya di server, dan blok Penggunaan menyusun kode
 * yang sama persis dengan yang sedang aktif.
 */
new class extends Component
{
    public bool $open = true;

    public DrawerNavItemTheme $theme = DrawerNavItemTheme::Primary;

    public bool $icons = true;

    public bool $dark = false;

    public bool $expandedMenu2 = false;

    public bool $expandedMenu3 = true;

    public bool $expandedMenu5 = false;

    public string $activeMenu = 'Menu 3 - sub menu 1';

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function selectMenu(string $label): void
    {
        $this->activeMenu = $label;
        $this->open = false;
    }

    #[Computed]
    public function usage(): string
    {
        $showIcons = $this->icons;
        $themeStr = $this->theme->value;

        $lines = [
            '<x-ceplok::drawer',
            '    ⟦:open⟧="$open"',
            '    position="right"',
            '    size="s"',
            '    eyebrow="MENU"',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    ⟦theme⟧="'.$themeStr.'"',
            '    :⟦items⟧="[',
        ];

        if ($showIcons) {
            $lines[] = "        ['icon' => 'ceplok-s-chart-pie', 'label' => 'Menu 1'],";
            $lines[] = '        [';
            $lines[] = "            'icon' => 'ceplok-s-file-lines',";
            $lines[] = "            'label' => 'Menu 2',";
            $lines[] = "            'children' => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']],";
            $lines[] = '        ],';
            $lines[] = '        [';
            $lines[] = "            'icon' => 'ceplok-s-cart',";
            $lines[] = "            'label' => 'Menu 3',";
            $lines[] = "            'active' => true,";
            $lines[] = "            'children' => [";
            $lines[] = "                ['label' => 'sub menu 1', 'active' => true],";
            $lines[] = "                ['label' => 'sub menu 2'],";
            $lines[] = "                ['label' => 'sub menu 3'],";
            $lines[] = '            ],';
            $lines[] = '        ],';
            $lines[] = "        ['icon' => 'ceplok-s-inbox', 'label' => 'Menu 4'],";
            $lines[] = '        [';
            $lines[] = "            'icon' => 'ceplok-s-lock',";
            $lines[] = "            'label' => 'Menu 5',";
            $lines[] = "            'children' => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']],";
            $lines[] = '        ],';
        } else {
            $lines[] = "        ['label' => 'Menu 1'],";
            $lines[] = '        [';
            $lines[] = "            'label' => 'Menu 2',";
            $lines[] = "            'children' => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']],";
            $lines[] = '        ],';
            $lines[] = '        [';
            $lines[] = "            'label' => 'Menu 3',";
            $lines[] = "            'active' => true,";
            $lines[] = "            'children' => [";
            $lines[] = "                ['label' => 'sub menu 1', 'active' => true],";
            $lines[] = "                ['label' => 'sub menu 2'],";
            $lines[] = "                ['label' => 'sub menu 3'],";
            $lines[] = '            ],';
            $lines[] = '        ],';
            $lines[] = "        ['label' => 'Menu 4'],";
            $lines[] = '        [';
            $lines[] = "            'label' => 'Menu 5',";
            $lines[] = "            'children' => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']],";
            $lines[] = '        ],';
        }

        $lines[] = '    ]"';
        $lines[] = '/>';

        return implode("\n", array_filter($lines));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Coba konfigurasi Drawer secara langsung melalui kontrol di bawah ini untuk melihat
            perubahan theme, ikon, dan mode gelap.
        </x-docs.lead>

        <x-docs.stage max-width="max-w-[700px]" :dark="$dark">
            <div class="flex h-[520px] w-full items-center justify-center py-4">
                {{-- Mockup layar mobile --}}
                <div @class([
                    'relative flex h-full w-[380px] max-w-full flex-col overflow-hidden rounded-xl border shadow-md',
                    $dark ? 'bg-gray-800 border-white/10 shadow-2xl' : 'bg-white border-gray-200',
                ])>
                    {{-- Topbar Header --}}
                    <div @class([
                        'flex shrink-0 items-center justify-between border-b px-4 py-3.5',
                        $dark ? 'border-white/10 bg-gray-800' : 'border-gray-200 bg-white',
                    ])>
                        <div class="flex items-center gap-2.5">
                            <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">
                                C
                            </div>
                            <span @class(['text-base font-bold tracking-tight', $dark ? 'text-white' : 'text-gray-900'])>CEPLOK</span>
                        </div>
                        <x-ceplok::drawer.trigger :dark-mode="$dark" wire:click="toggle" />
                    </div>

                    {{-- Konten halaman mobile dummy --}}
                    <div @class(['flex-1 min-w-0 p-6 overflow-y-auto', $dark ? 'bg-gray-700' : 'bg-gray-50/50'])>
                        <h4 @class(['text-lg font-bold tracking-tight mb-2', $dark ? 'text-white' : 'text-gray-900'])>
                            {{ $activeMenu }}
                        </h4>
                        <p @class(['text-xs leading-5', $dark ? 'text-gray-300' : 'text-gray-500'])>
                            Anda sedang berada di halaman utama {{ $activeMenu }}. Klik tombol hamburger di kanan atas untuk membuka menu Drawer.
                        </p>
                    </div>

                    {{-- Overlay dan Panel Drawer Mobile --}}
                    @if ($open)
                        <div
                            class="absolute inset-0 z-10 bg-gray-900/40 transition-opacity"
                            wire:click="toggle"
                        ></div>
                        <div @class([
                            'absolute right-0 top-0 bottom-0 z-20 flex h-full w-[260px] flex-col border-l shadow-2xl rounded-none',
                            $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200',
                        ])>
                            <x-ceplok::drawer.header eyebrow="MENU" :dark-mode="$dark" wire:close="toggle" />

                            <x-ceplok::drawer.body class="p-3 space-y-1" :dark-mode="$dark">
                                <x-ceplok::drawer.nav-item
                                    :icon="$icons ? 'ceplok-s-chart-pie' : null"
                                    label="Menu 1"
                                    :active="$activeMenu === 'Menu 1'"
                                    :theme="$theme"
                                    :dark-mode="$dark"
                                    wire:click="selectMenu('Menu 1')"
                                />

                                <x-ceplok::drawer.nav-item
                                    :icon="$icons ? 'ceplok-s-file-lines' : null"
                                    label="Menu 2"
                                    :expanded="$expandedMenu2"
                                    :theme="$theme"
                                    :dark-mode="$dark"
                                    wire:click="$toggle('expandedMenu2')"
                                >
                                    <x-ceplok::drawer.sub-item
                                        label="sub menu 1"
                                        :active="$activeMenu === 'Menu 2 - sub menu 1'"
                                        :theme="$theme"
                                        :dark-mode="$dark"
                                        wire:click="selectMenu('Menu 2 - sub menu 1')"
                                    />
                                    <x-ceplok::drawer.sub-item
                                        label="sub menu 2"
                                        :active="$activeMenu === 'Menu 2 - sub menu 2'"
                                        :theme="$theme"
                                        :dark-mode="$dark"
                                        wire:click="selectMenu('Menu 2 - sub menu 2')"
                                    />
                                </x-ceplok::drawer.nav-item>

                                <x-ceplok::drawer.nav-item
                                    :icon="$icons ? 'ceplok-s-cart' : null"
                                    label="Menu 3"
                                    active
                                    :expanded="$expandedMenu3"
                                    :theme="$theme"
                                    :dark-mode="$dark"
                                    wire:click="$toggle('expandedMenu3')"
                                >
                                    <x-ceplok::drawer.sub-item
                                        label="sub menu 1"
                                        :active="$activeMenu === 'Menu 3 - sub menu 1'"
                                        :theme="$theme"
                                        :dark-mode="$dark"
                                        wire:click="selectMenu('Menu 3 - sub menu 1')"
                                    />
                                    <x-ceplok::drawer.sub-item
                                        label="sub menu 2"
                                        :active="$activeMenu === 'Menu 3 - sub menu 2'"
                                        :theme="$theme"
                                        :dark-mode="$dark"
                                        wire:click="selectMenu('Menu 3 - sub menu 2')"
                                    />
                                    <x-ceplok::drawer.sub-item
                                        label="sub menu 3"
                                        :active="$activeMenu === 'Menu 3 - sub menu 3'"
                                        :theme="$theme"
                                        :dark-mode="$dark"
                                        wire:click="selectMenu('Menu 3 - sub menu 3')"
                                    />
                                </x-ceplok::drawer.nav-item>

                                <x-ceplok::drawer.nav-item
                                    :icon="$icons ? 'ceplok-s-inbox' : null"
                                    label="Menu 4"
                                    :active="$activeMenu === 'Menu 4'"
                                    :theme="$theme"
                                    :dark-mode="$dark"
                                    wire:click="selectMenu('Menu 4')"
                                />

                                <x-ceplok::drawer.nav-item
                                    :icon="$icons ? 'ceplok-s-lock' : null"
                                    label="Menu 5"
                                    :expanded="$expandedMenu5"
                                    :theme="$theme"
                                    :dark-mode="$dark"
                                    wire:click="$toggle('expandedMenu5')"
                                >
                                    <x-ceplok::drawer.sub-item
                                        label="sub menu 1"
                                        :active="$activeMenu === 'Menu 5 - sub menu 1'"
                                        :theme="$theme"
                                        :dark-mode="$dark"
                                        wire:click="selectMenu('Menu 5 - sub menu 1')"
                                    />
                                    <x-ceplok::drawer.sub-item
                                        label="sub menu 2"
                                        :active="$activeMenu === 'Menu 5 - sub menu 2'"
                                        :theme="$theme"
                                        :dark-mode="$dark"
                                        wire:click="selectMenu('Menu 5 - sub menu 2')"
                                    />
                                </x-ceplok::drawer.nav-item>
                            </x-ceplok::drawer.body>
                        </div>
                    @endif
                </div>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Theme">
                <x-docs.segmented label="Pilih theme" model="theme" :value="$theme" :options="[
                    ['value' => 'primary', 'label' => 'Primary'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Icons">
                <x-docs.segmented label="Pilih ikon" model="icons" :value="$icons" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak Ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Praktik terbaik dan panduan integrasi Drawer dalam aplikasi Laravel dan Livewire.
            Gunakan <span class="text-primary-500">$open</span> sebagai properti publik di komponen Livewire Anda.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
