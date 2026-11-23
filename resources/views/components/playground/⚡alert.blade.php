<?php

use CeplokUi\DesignKit\Enums\AlertVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Alert. Alert menutup dirinya sendiri lewat Alpine;
 * "Tampilkan Alert" menaikkan $shown yang ikut menjadi wire:key, sehingga
 * Alert-nya dipasang ulang dari nol — seperti `key` di Playground React.
 * `dismissible` juga ikut key-nya karena prop itu menambah/mencabut x-data
 * di elemen akarnya; perubahan lain cukup ditambal morph.
 */
new class extends Component
{
    public AlertVariant $variant = AlertVariant::Success;

    /** Lebar panggung: mobile atau desktop. */
    public string $width = 'desktop';

    public bool $icon = true;

    public bool $dismissible = true;

    public bool $actions = true;

    public bool $dark = false;

    public int $shown = 0;

    public function show(): void
    {
        $this->shown++;
    }

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::alert',
            $this->variant !== AlertVariant::Info ? '    ⟦variant⟧="'.$this->variant->value.'"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    heading="Ini adalah Alert"',
            ! $this->icon ? '    :⟦icon⟧="false"' : null,
            ! $this->dismissible ? '    :⟦dismissible⟧="false"' : null,
            '>',
            '    Ini merupakan Design system Ceplok berupa component alert.',
            $this->actions ? '    <x-slot:⟦actions⟧>' : null,
            $this->actions ? '        <button type="button" class="…">Button text</button>' : null,
            $this->actions ? '    </x-slot:actions>' : null,
            '</x-ceplok::alert>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Satu Alert yang bisa Anda utak-atik lewat kontrol di bawahnya. Setiap perubahan langsung terlihat
            di sini, dan bagian Penggunaan menuliskan kodenya. Setelah Alert ditutup, klik
            <span class="text-primary-500 dark:text-primary-400">Tampilkan Alert</span> untuk memasangnya kembali.
        </x-docs.lead>

        <x-docs.stage :max-width="$width === 'mobile' ? 'max-w-75' : 'max-w-full'" :dark="$dark">
            <x-ceplok::alert
                wire:key="alert-{{ $shown }}-{{ (int) $dismissible }}"
                :variant="$variant"
                :dark-mode="$dark"
                heading="Ini adalah Alert"
                :icon="$icon ? null : false"
                :dismissible="$dismissible"
            >
                Ini merupakan Design system Ceplok berupa component alert.
                @if ($actions)
                    <x-slot:actions>
                        <x-docs.alert-action :variant="$variant" :dark="$dark" />
                    </x-slot:actions>
                @endif
            </x-ceplok::alert>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Variant">
                <x-docs.segmented label="Pilih variant" model="variant" :value="$variant" item-class="px-2.5" wrap :options="[
                    ['value' => 'success', 'label' => 'Success'],
                    ['value' => 'danger', 'label' => 'Danger'],
                    ['value' => 'warning', 'label' => 'Warning'],
                    ['value' => 'info', 'label' => 'Info'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Lebar">
                <x-docs.segmented label="Pilih lebar alert" model="width" :value="$width" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'desktop', 'label' => 'Desktop'],
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

            <x-docs.control label="Tombol aksi">
                <x-docs.segmented label="Tampilkan tombol aksi" model="actions" :value="$actions" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

        </x-docs.controls>

        <button
            type="button"
            wire:click="show"
            class="mt-5 inline-flex items-center rounded-lg bg-primary-700 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-primary-800"
        >
            Tampilkan Alert
        </button>

        <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
            Tombol tutup mengurus visibilitasnya sendiri, jadi tidak ada state yang perlu Anda siapkan. Pasang
            <span class="text-primary-500 dark:text-primary-400">wire:dismiss</span> bila penutupan perlu dicatat, atau <span class="text-primary-500 dark:text-primary-400">wire:model</span> bila Alert dikendalikan penuh dari
            komponen Livewire.
        </p>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground — ubah kontrolnya, kodenya ikut berubah. Prop yang
            nilainya masih bawaan sengaja tidak ditulis.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
