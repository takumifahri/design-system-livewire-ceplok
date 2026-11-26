<?php

use CeplokUi\DesignKit\Enums\DrawerNavItemTheme;
use Illuminate\Support\Js;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public bool $open = false;
    public DrawerNavItemTheme $theme = DrawerNavItemTheme::Primary;
    public string $font = 'lato';
    public bool $icons = true;
    public bool $dark = false;
    public string $activeMenu = 'Menu 3 - sub menu 1';

    /** Seperti React: memilih menu memindahkan sorotan aktif dan menutup Drawer. */
    public function pilih(string $menu): void
    {
        $this->activeMenu = $menu;
    }

    #[Computed]
    public function items(): array
    {
        // Klik item tidak menutup Drawer dengan sendirinya (seperti React), jadi
        // Playground menutupnya sendiri lewat `attributes`, padanan onClick.
        $pilih = fn (string $menu) => [
            'active' => $this->activeMenu === $menu,
            'attributes' => ['wire:click' => 'pilih('.Js::from($menu).')', 'x-on:click' => 'drawerOpen = false'],
        ];
        $sub = fn (string $parent, array $labels) => array_map(fn ($label) => ['label' => $label, ...$pilih("{$parent} - {$label}")], $labels);

        return [
            ['label' => 'Menu 1', 'icon' => $this->icons ? 'ceplok-s-chart-pie' : null, ...$pilih('Menu 1')],
            ['label' => 'Menu 2', 'icon' => $this->icons ? 'ceplok-s-file-lines' : null, 'expanded' => false, 'children' => $sub('Menu 2', ['sub menu 1', 'sub menu 2'])],
            ['label' => 'Menu 3', 'icon' => $this->icons ? 'ceplok-s-cart' : null, 'expanded' => true, 'children' => $sub('Menu 3', ['sub menu 1', 'sub menu 2'])],
            ['label' => 'Menu 4', 'icon' => $this->icons ? 'ceplok-s-inbox' : null, 'disabled' => true],
            ['label' => 'Menu 5', 'icon' => $this->icons ? 'ceplok-s-lock' : null, 'expanded' => false, 'children' => $sub('Menu 5', ['sub menu 1', 'sub menu 2'])],
        ];
    }

    #[Computed]
    public function usage(): string
    {
        $attributes = [
            '<x-ceplok::drawer',
            '    ⟦x-model⟧="drawerOpen"',
            '    position="right"',
            '    size="s"',
            '    eyebrow="MENU"',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            $this->font !== 'lato' ? '    font="⟦'.$this->font.'⟧"' : null,
            '    theme="⟦'.$this->theme->value.'⟧"',
            '    :⟦items⟧="[',
        ];

        foreach ($this->items as $item) {
            $parts = [];
            if ($this->icons && $item['icon']) $parts[] = "⟦'icon' => '{$item['icon']}'⟧";
            $parts[] = "'label' => '{$item['label']}'";
            if (! empty($item['active'])) $parts[] = "'active' => true";
            if (! empty($item['expanded'])) $parts[] = "'expanded' => true";
            if (! empty($item['children'])) {
                $children = collect($item['children'])->map(fn ($child) => "['label' => '{$child['label']}'".(! empty($child['active']) ? ", 'active' => true" : '').']')->implode(', ');
                $parts[] = "'children' => [{$children}]";
            }
            $attributes[] = '        ['.implode(', ', $parts).'],';
        }

        $attributes[] = '    ]"';
        $attributes[] = '/>';

        return implode("\n", array_filter($attributes));
    }
};
?>

<div class="space-y-12">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>Uji coba perilaku dan variasi konfigurasi Drawer secara langsung pada tampilan mobile.</x-docs.lead>

        <x-docs.stage max-width="max-w-[760px]" min-width="760px" :dark="$dark">
            <div @class(['flex min-h-[668px] w-full items-center justify-center rounded-xl p-5', $dark ? 'bg-gray-950' : 'bg-gray-50'])>
                <div
                    x-data="{ drawerOpen: true }"
                    @class(['relative flex max-w-full overflow-hidden border-x shadow-md', $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200', $font === 'lato' ? 'font-sans' : ($font === 'inter' ? 'font-alt' : 'font-alt-2')])
                    style="width: 420px; height: 588px"
                >
                    <div class="w-full">
                        <div @class(['flex items-center border-b', $dark ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white']) style="padding: 14px 16px">
                            <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-7 object-contain">
                            <span @class(['ml-2.5 text-base font-bold tracking-tight', $dark ? 'text-white' : 'text-gray-900'])>CEPLOK</span>
                            <button type="button" x-on:click="drawerOpen = true" aria-label="Buka drawer" @class(['ml-auto inline-flex size-8 items-center justify-center rounded-lg transition-colors', $dark ? 'text-gray-200 hover:bg-gray-700 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'])><x-ceplok-o-bars-from-left class="size-6" /></button>
                        </div>
                        <div @class([$dark ? 'text-gray-300' : 'text-gray-600']) style="padding: 24px">
                            <h4 @class(['text-xl font-bold tracking-tight', $dark ? 'text-white' : 'text-gray-900'])>{{ $activeMenu }}</h4>
                            <p class="mt-4 text-sm">Anda sedang berada di halaman utama {{ $activeMenu }}.</p>
                        </div>
                    </div>
                    <div x-show="drawerOpen" x-on:click="drawerOpen = false" @class(['absolute inset-0', $dark ? 'bg-gray-900/50' : 'bg-gray-900/20'])></div>
                    <x-ceplok::drawer preview x-model="drawerOpen" style="width: 260px" eyebrow="MENU" :font="$font" :theme="$theme" :dark-mode="$dark" :items="$this->items" />
                </div>
            </div>
        </x-docs.stage>

        <x-docs.controls :fonts="false">
            <x-docs.control label="Font"><x-docs.segmented label="Pilih font" model="font" :value="$font" :options="[['value' => 'lato', 'label' => 'Lato'], ['value' => 'inter', 'label' => 'Inter'], ['value' => 'arial', 'label' => 'Arial']]" /></x-docs.control>
            <x-docs.control label="Tampilan"><x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[['value' => false, 'label' => 'Light'], ['value' => true, 'label' => 'Dark']]" /></x-docs.control>
            <x-docs.control label="Theme"><x-docs.segmented label="Pilih theme" model="theme" :value="$theme" :options="[['value' => 'primary', 'label' => 'Primary'], ['value' => 'purple', 'label' => 'Purple']]" /></x-docs.control>
            <x-docs.control label="Icons"><x-docs.segmented label="Pilih ikon" model="icons" :value="$icons" :options="[['value' => true, 'label' => 'Ada'], ['value' => false, 'label' => 'Tidak Ada']]" /></x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan" class="pt-10">
        <x-docs.lead>Contoh berikut mengikuti konfigurasi Playground di atas secara langsung.</x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
