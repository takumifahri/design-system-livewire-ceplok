<?php

use CeplokUi\DesignKit\Enums\CheckboxApplication;
use CeplokUi\DesignKit\Enums\CheckboxPlatform;
use CeplokUi\DesignKit\Enums\CheckboxState;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Checkbox: tiga checkbox sekelompok yang diikat ke array
 * $dipilih lewat wire:model, jadi centangnya tetap saat kontrol lain diganti.
 */
new class extends Component
{
    public CheckboxPlatform $platform = CheckboxPlatform::Default;

    public CheckboxState $state = CheckboxState::Default;

    public CheckboxApplication $application = CheckboxApplication::Default;

    public bool $caption = false;

    public bool $dark = false;

    /** @var list<string> */
    public array $dipilih = ['ktp'];

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            "{{-- \$berkas = ['ktp' => 'KTP elektronik', …] --}}",
            '@foreach ($berkas as $value => $label)',
            '    <x-ceplok::checkbox',
            '        :value="$value"',
            $this->platform !== CheckboxPlatform::Default ? '        ⟦platform⟧="mobile"' : null,
            $this->state !== CheckboxState::Default ? '        ⟦state⟧="inactive"' : null,
            $this->application !== CheckboxApplication::Default ? '        ⟦application⟧="'.$this->application->value.'"' : null,
            $this->dark ? '        ⟦dark-mode⟧' : null,
            '        :label="$label"',
            $this->caption ? '        ⟦helper-text⟧="Unggah berkas asli berwarna, maksimal 2 MB."' : null,
            '        wire:model="dipilih"',
            '    />',
            '@endforeach',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Sekelompok checkbox yang bisa Anda utak-atik lewat kontrol di bawahnya. Setiap perubahan langsung
            terlihat di sini, dan bagian Penggunaan menuliskan kodenya.
        </x-docs.lead>

        {{-- max-w-fit: blok menyusut seukuran isinya, jadi mx-auto benar-benar memusatkannya. --}}
        <x-docs.stage max-width="max-w-fit" :dark="$dark">
            <div class="space-y-4">
                @foreach (['ktp' => 'KTP elektronik', 'kk' => 'Kartu keluarga', 'npwp' => 'NPWP'] as $value => $label)
                    <x-ceplok::checkbox
                        :value="$value"
                        wire:model="dipilih"
                        :platform="$platform"
                        :state="$state"
                        :application="$application"
                        :dark-mode="$dark"
                        :label="$label"
                        :helper-text="$caption ? 'Unggah berkas asli berwarna, maksimal 2 MB.' : null"
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

        <p class="mt-4 text-body-sm text-gray-500">
            Berbeda dengan Radio, tiap Checkbox berdiri sendiri — pakai <span class="text-primary-500">name</span> yang sama hanya bila
            server Anda memang mengharapkan satu daftar nilai dari satu nama.
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
