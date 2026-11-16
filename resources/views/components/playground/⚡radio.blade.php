<?php

use CeplokUi\DesignKit\Enums\RadioApplication;
use CeplokUi\DesignKit\Enums\RadioPlatform;
use CeplokUi\DesignKit\Enums\RadioState;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Radio Button: satu kelompok pilihan yang diikat ke
 * $pilihan lewat wire:model, jadi pilihannya tetap saat kontrol lain diganti.
 */
new class extends Component
{
    public RadioPlatform $platform = RadioPlatform::Default;

    public RadioState $state = RadioState::Default;

    public RadioApplication $application = RadioApplication::Default;

    public bool $caption = false;

    public bool $dark = false;

    public string $pilihan = 'wni';

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            "{{-- \$kewarganegaraan = ['wni' => 'Warga negara Indonesia', …] --}}",
            '@foreach ($kewarganegaraan as $value => $label)',
            '    <x-ceplok::radio',
            '        name="kewarganegaraan"',
            '        :value="$value"',
            $this->platform !== RadioPlatform::Default ? '        ⟦platform⟧="mobile"' : null,
            $this->state !== RadioState::Default ? '        ⟦state⟧="inactive"' : null,
            $this->application !== RadioApplication::Default ? '        ⟦application⟧="'.$this->application->value.'"' : null,
            $this->dark ? '        ⟦dark-mode⟧' : null,
            '        :label="$label"',
            $this->caption ? '        ⟦helper-text⟧="Keterangan singkat tentang pilihan ini."' : null,
            '        wire:model="pilihan"',
            '    />',
            '@endforeach',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Satu kelompok pilihan yang bisa Anda utak-atik lewat kontrol di bawahnya. Setiap perubahan
            langsung terlihat di sini, dan bagian Penggunaan menuliskan kodenya.
        </x-docs.lead>

        {{--
            max-w-fit, bukan lebar tetap: kolom 420px membuat kelompok pilihan
            menempel di tepi kirinya dan tampak melenceng dari tengah kartu.
            Dengan fit-content, blok menyusut seukuran isinya lalu mx-auto
            benar-benar memusatkannya.
        --}}
        <x-docs.stage max-width="max-w-fit" :dark="$dark">
            <div class="space-y-4">
                @foreach (['wni' => 'Warga negara Indonesia', 'wna' => 'Warga negara asing'] as $value => $label)
                    <x-ceplok::radio
                        name="ds-radio-playground"
                        :value="$value"
                        wire:model="pilihan"
                        :platform="$platform"
                        :state="$state"
                        :application="$application"
                        :dark-mode="$dark"
                        :label="$label"
                        :helper-text="$caption ? 'Keterangan singkat tentang pilihan ini.' : null"
                    />
                @endforeach
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'default', 'label' => 'Desktop'],
                ]" />
            </x-docs.control>

            <x-docs.control label="State">
                <x-docs.segmented label="Pilih state" model="state" :value="$state" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'inactive', 'label' => 'Inactive'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Application">
                <x-docs.segmented label="Pilih aplikasi" model="application" :value="$application" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Caption">
                <x-docs.segmented label="Tampilkan caption" model="caption" :value="$caption" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
            Satu kelompok pilihan dibentuk dengan memberi <span class="text-primary-500 dark:text-primary-400">name</span> yang sama pada tiap Radio — sama seperti
            formulir HTML biasa, jadi panah atas/bawah otomatis berpindah antar-opsi.
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
