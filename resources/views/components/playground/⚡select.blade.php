<?php

use CeplokUi\DesignKit\Enums\SelectApplication;
use CeplokUi\DesignKit\Enums\SelectState;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Regular Select Form. Pilihannya diikat ke $value lewat
 * wire:model, jadi tetap ada saat kontrol lain diganti.
 */
new class extends Component
{
    public SelectApplication $application = SelectApplication::Default;

    public SelectState $state = SelectState::Default;

    /** Lebar wadah: 340px (Mobile) atau 348px (Default). Bukan prop. */
    public bool $narrow = false;

    public bool $info = true;

    public bool $helper = true;

    public bool $dark = false;

    public string $value = '';

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::select',
            $this->state !== SelectState::Default ? '    ⟦state⟧="inactive"' : null,
            $this->application !== SelectApplication::Default ? '    ⟦application⟧="'.$this->application->value.'"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    label="Label"',
            $this->info ? '    ⟦info⟧="Keterangan singkat tentang isian ini."' : null,
            '    placeholder="Pilih Apapun Itu"',
            '    :options="$provinsi"',
            $this->helper ? '    ⟦helper-text⟧="Pilih salah satu provinsi."' : null,
            '    wire:model="provinsi"',
            '/>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Satu komponen yang bisa Anda utak-atik lewat kontrol di bawahnya. Setiap perubahan langsung
            terlihat di sini, dan bagian Penggunaan menuliskan kodenya.
        </x-docs.lead>

        <x-docs.stage :max-width="$narrow ? 'max-w-85' : 'max-w-87'" :dark="$dark">
            <x-ceplok::select
                wire:model="value"
                :application="$application"
                :state="$state"
                :dark-mode="$dark"
                label="Label"
                :info="$info ? 'Keterangan singkat tentang isian ini.' : null"
                placeholder="Pilih Apapun Itu"
                :options="[
                    ['value' => 'dki', 'label' => 'DKI Jakarta'],
                    ['value' => 'jabar', 'label' => 'Jawa Barat'],
                    ['value' => 'jateng', 'label' => 'Jawa Tengah'],
                    ['value' => 'jatim', 'label' => 'Jawa Timur'],
                ]"
                :helper-text="$helper ? 'Pilih salah satu provinsi.' : null"
            />
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Application">
                <x-docs.segmented label="Pilih aplikasi" model="application" :value="$application" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="State">
                <x-docs.segmented label="Pilih state" model="state" :value="$state" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'inactive', 'label' => 'Inactive'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Lebar wadah">
                <x-docs.segmented label="Pilih lebar" model="narrow" :value="$narrow" :options="[
                    ['value' => true, 'label' => 'Mobile'],
                    ['value' => false, 'label' => 'Default'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Ikon info">
                <x-docs.segmented label="Tampilkan ikon info" model="info" :value="$info" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Helper text">
                <x-docs.segmented label="Tampilkan helper text" model="helper" :value="$helper" :options="[
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

        <p class="mt-4 text-body-sm text-gray-500">
            Kontrol <em>Lebar wadah</em> tidak mengubah prop apa pun — varian Mobile hanya menyempitkan
            wadahnya (340px, dari 348px), sedangkan tinggi, jarak, dan ukuran teksnya sama persis. Karena itu
            Select tidak punya prop <span class="text-primary-500">platform</span>.
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
