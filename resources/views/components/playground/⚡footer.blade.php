<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Footer. Kontrol Tampilan hanya mengubah lebar
 * pembungkusnya — footer menyesuaikan diri lewat container query.
 */
new class extends Component
{
    public const COPYRIGHT = '© 2025 Ceplok Design System';

    /** Lebar tampilan: mobile atau desktop. */
    public string $view = 'desktop';

    public int $menus = 4;

    /** Jenis logo: image atau text. */
    public string $logo = 'image';

    public bool $socials = true;

    /** @return list<array{label: string, url: string}> */
    public function menuItems(): array
    {
        return array_map(fn (int $i) => ['label' => "Menu {$i}", 'url' => '#'], range(1, max(1, min(8, $this->menus))));
    }

    /** @return list<array{label: string, url: string, icon: string}> */
    public function socialItems(): array
    {
        return $this->socials ? [
            ['label' => 'Instagram', 'url' => 'https://www.instagram.com/ceplok/', 'icon' => 'brand-instagram'],
            ['label' => 'X', 'url' => 'https://x.com/ceplok', 'icon' => 'brand-x'],
            ['label' => 'Facebook', 'url' => '#', 'icon' => 'brand-facebook'],
        ] : [];
    }

    #[Computed]
    public function usage(): string
    {
        $mobile = $this->view === 'mobile';
        $indent = $mobile ? '    ' : '';
        $summary = implode(' · ', [
            $mobile ? 'Mobile · dibatasi 390px' : 'Desktop · lebar penuh',
            count($this->menuItems()).' menu',
            $this->logo === 'image' ? 'logo gambar' : 'logo teks',
            $this->socials ? 'dengan ikon sosial' : 'tanpa ikon sosial',
        ]);
        $text = $this->logo === 'text';

        $lines = [
            '<x-ceplok::footer',
            ...(! $text ? ['    ⟦*logo⟧="/images/footer-logo.svg"', '    ⟦*logo-alt⟧="Ceplok Design System"'] : []),
            '    :⟦*menus⟧="[',
            ...array_map(fn (array $menu, int $i) => "        ['label' => '{$menu['label']}', 'url' => '/menu-".($i + 1)."'],", $this->menuItems(), array_keys($this->menuItems())),
            '    ]"',
            '    copyright="'.self::COPYRIGHT.'"',
            ...($this->socials ? [
                '    :⟦*socials⟧="[',
                "        ['label' => 'Instagram', 'url' => 'https://instagram.com/ceplok', 'icon' => 'brand-instagram'],",
                "        ['label' => 'X',         'url' => 'https://x.com/ceplok',         'icon' => 'brand-x'],",
                "        ['label' => 'Facebook',  'url' => '#',                            'icon' => 'brand-facebook'],",
                '    ]"',
            ] : []),
            ...($text ? [
                '>',
                '    <x-slot:⟦*logo-content⟧>',
                '        <span class="text-xl font-black text-white">CEPLOK</span>',
                '    </x-slot:logo-content>',
                '</x-ceplok::footer>',
            ] : ['/>']),
        ];

        return implode("\n", array_filter([
            "{{-- {$summary} --}}",
            $mobile ? "{{-- Footer tidak perlu tahu ini mobile — susunannya mengikuti\n     lebar pembungkusnya sendiri lewat container query. --}}" : null,
            $mobile ? '<div class="⟦*max-w-[390px]⟧">' : null,
            implode("\n", array_map(fn (string $line) => $indent.$line, $lines)),
            $mobile ? '</div>' : null,
        ]));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Keempat kontrol di bawah panggung menggabungkan prop-prop di atas dalam satu tampilan, dan
            bagian Penggunaan menuliskan kodenya. Deretan menu dibatasi lima per baris — pilih 6 atau 8
            menu untuk melihat sisanya turun ke baris berikutnya.
        </x-docs.lead>

        <x-docs.stage :max-width="$view === 'mobile' ? 'max-w-[390px]' : 'max-w-full'">
            <div class="overflow-hidden rounded-xl shadow-soft">
                <x-ceplok::footer
                    :logo="$logo === 'image' ? '/images/footer-logo.svg' : null"
                    logo-alt="Ceplok Design System"
                    :menus="$this->menuItems()"
                    :copyright="$this::COPYRIGHT"
                    :socials="$this->socialItems()"
                >
                    @if ($logo === 'text')
                        <x-slot:logo-content>
                            <span class="text-xl font-black text-white">CEPLOK</span>
                        </x-slot:logo-content>
                    @endif
                </x-ceplok::footer>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih lebar tampilan" model="view" :value="$view" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile', 'icon' => 'M7 3h10a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm4 15h2'],
                    ['value' => 'desktop', 'label' => 'Desktop', 'icon' => 'M3 5h18v11H3V5Zm6 15h6m-3-4v4'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Jumlah menu">
                <x-docs.segmented
                    label="Pilih jumlah menu"
                    model="menus"
                    :value="$menus"
                    item-class="w-9 justify-center"
                    :options="array_map(fn ($n) => ['value' => $n, 'label' => (string) $n], [2, 4, 5, 6, 8])"
                />
            </x-docs.control>

            <x-docs.control label="Logo">
                <x-docs.segmented label="Pilih jenis logo" model="logo" :value="$logo" :options="[
                    ['value' => 'image', 'label' => 'Gambar'],
                    ['value' => 'text', 'label' => 'Teks'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Ikon sosial">
                <x-docs.segmented label="Tampilkan ikon sosial" model="socials" :value="$socials" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Mengikuti keempat kontrol di Playground — logo, jumlah menu, ikon sosial, dan
            pembungkusnya ikut berubah. Nama prop yang sedang dikendalikan kontrol ditandai dengan warna
            biru, dan tombol <em>Salin</em> selalu menyalin persis yang sedang tampil.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
