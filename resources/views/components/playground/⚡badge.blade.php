<?php

use CeplokUi\DesignKit\Enums\BadgeSize;
use CeplokUi\DesignKit\Enums\BadgeVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Badge. `wire:key` badge-nya ikut berubah setiap kontrol
 * diganti, jadi Badge yang sudah ditutup dirender ulang sebagai elemen baru —
 * muncul lagi, seperti `key` di Playground React.
 */
new class extends Component
{
    public BadgeVariant $variant = BadgeVariant::Success;

    public BadgeSize $size = BadgeSize::Sm;

    public bool $icon = true;

    public bool $dismissible = true;

    public bool $dark = false;

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        $props = collect([
            $this->variant !== BadgeVariant::Gray ? '⟦variant⟧="'.$this->variant->value.'"' : null,
            $this->size !== BadgeSize::Sm ? '⟦size⟧="'.$this->size->value.'"' : null,
            $this->dark ? '⟦dark-mode⟧' : null,
            $this->icon ? ':⟦icon⟧="svg(\'ceplok-s-clock\')"' : null,
            $this->dismissible ? '⟦dismissible⟧' : null,
        ])->filter()->map(fn (string $prop) => ' '.$prop)->implode('');

        return "<x-ceplok::badge{$props}>\n    Badge\n</x-ceplok::badge>";
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Satu Badge yang bisa Anda utak-atik lewat kontrol di bawahnya. Bagian Penggunaan menuliskan
            kodenya. Setelah Badge ditutup, ubah kontrol mana saja untuk memunculkannya kembali.
        </x-docs.lead>

        <x-docs.stage max-width="max-w-full" :dark="$dark">
            <div class="flex justify-center">
                <x-ceplok::badge
                    wire:key="badge-{{ $variant->value }}-{{ $size->value }}-{{ (int) $icon }}-{{ (int) $dismissible }}-{{ (int) $dark }}"
                    :variant="$variant"
                    :size="$size"
                    :dark-mode="$dark"
                    :icon="$icon ? svg('ceplok-s-clock') : null"
                    :dismissible="$dismissible"
                >Badge</x-ceplok::badge>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Variant">
                <x-docs.segmented label="Pilih variant" model="variant" :value="$variant" item-class="px-2.5" wrap :options="[
                    ['value' => 'gray', 'label' => 'Gray'],
                    ['value' => 'danger', 'label' => 'Danger'],
                    ['value' => 'warning', 'label' => 'Warning'],
                    ['value' => 'success', 'label' => 'Success'],
                    ['value' => 'brand', 'label' => 'Brand'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Ukuran">
                <x-docs.segmented label="Pilih ukuran" model="size" :value="$size" :options="[
                    ['value' => 'sm', 'label' => 'Small'],
                    ['value' => 'lg', 'label' => 'Large'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Icon kiri">
                <x-docs.segmented label="Tampilkan icon kiri" model="icon" :value="$icon" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Tombol tutup">
                <x-docs.segmented label="Tampilkan tombol tutup" model="dismissible" :value="$dismissible" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground. Prop yang nilainya masih bawaan sengaja tidak ditulis.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
