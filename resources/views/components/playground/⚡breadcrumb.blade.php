<?php

use CeplokUi\DesignKit\Enums\BreadcrumbSize;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Breadcrumb: ukuran, latar, mode, dan jumlah item.
 */
new class extends Component
{
    public BreadcrumbSize $size = BreadcrumbSize::Base;

    public bool $background = false;

    public bool $dark = false;

    /** Jumlah item termasuk Home: 2–5. */
    public int $count = 3;

    /** @return list<array{label: string, href?: string}> */
    public function items(): array
    {
        $all = [
            ['label' => 'Home', 'href' => '#'],
            ['label' => 'Menu 1', 'href' => '#'],
            ['label' => 'Menu 2', 'href' => '#'],
            ['label' => 'Menu 3', 'href' => '#'],
            ['label' => 'Menu 4'],
        ];

        return array_slice($all, 0, max(2, min(5, $this->count)));
    }

    #[Computed]
    public function usage(): string
    {
        $items = array_map(
            fn (array $item) => "        ['label' => '{$item['label']}'".(isset($item['href']) ? ", 'href' => '{$item['href']}'" : '').'],',
            $this->items(),
        );

        return implode("\n", array_filter([
            '@php',
            '    $items = [',
            ...$items,
            '    ];',
            '@endphp',
            '',
            '<x-ceplok::breadcrumb',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    :⟦items⟧="$items"',
            '    ⟦size⟧="'.$this->size->value.'"',
            '    ⟦background⟧="'.($this->background ? 'bg' : 'none').'"',
            '/>',
        ], fn ($line) => $line !== null));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <p class="mb-6 text-body-sm text-gray-500">
            Coba konfigurasi Breadcrumb secara langsung melalui kontrol di bawah ini. Anda dapat
            mengubah ukuran, background, dan jumlah menu.
        </p>

        <x-docs.stage max-width="max-w-[700px]" :dark="$dark">
            <div class="flex min-h-40 items-center justify-center">
                <x-ceplok::breadcrumb :dark-mode="$dark" :items="$this->items()" :size="$size" :background="$background ? 'bg' : 'none'" />
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Size">
                <x-docs.segmented label="Pilih ukuran" model="size" :value="$size" :options="[
                    ['value' => 'sm', 'label' => '12px'],
                    ['value' => 'base', 'label' => '14px'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Background">
                <x-docs.segmented label="Pilih background" model="background" :value="$background" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>


            <x-docs.control label="Jumlah Menu">
                <x-docs.segmented label="Pilih jumlah menu" model="count" :value="$count" :options="[
                    ['value' => 2, 'label' => '1'],
                    ['value' => 3, 'label' => '2'],
                    ['value' => 4, 'label' => '3'],
                    ['value' => 5, 'label' => '4'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <p class="mb-6 text-body-sm text-gray-500">
            Bagian ini menampilkan contoh kode penggunaan Breadcrumb berdasarkan konfigurasi yang
            dipilih pada Playground.
        </p>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
