<?php

use CeplokUi\DesignKit\Enums\FloatingLabelApplication;
use CeplokUi\DesignKit\Enums\FloatingLabelPlatform;
use CeplokUi\DesignKit\Enums\FloatingLabelState;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Floating Label. Isiannya diikat ke $value lewat
 * wire:model, jadi tetap ada saat kontrol lain diganti, dan labelnya tetap
 * naik selama masih ada isi.
 */
new class extends Component
{
    public FloatingLabelPlatform $platform = FloatingLabelPlatform::Default;

    public FloatingLabelState $state = FloatingLabelState::Default;

    public FloatingLabelApplication $application = FloatingLabelApplication::Default;

    public bool $icon = true;

    public bool $helper = true;

    public bool $clear = true;

    public bool $dark = false;

    public string $value = '';

    #[Computed]
    public function helperText(): string
    {
        return $this->state === FloatingLabelState::Error ? 'Isian tidak dapat diproses.' : 'Sesuai yang tertera pada KTP.';
    }

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::floating-label',
            $this->platform !== FloatingLabelPlatform::Default ? '    ⟦platform⟧="'.$this->platform->value.'"' : null,
            $this->state !== FloatingLabelState::Default ? '    ⟦state⟧="'.$this->state->value.'"' : null,
            $this->application !== FloatingLabelApplication::Default ? '    ⟦application⟧="'.$this->application->value.'"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    label="Placeholder Text"',
            $this->helper ? '    ⟦helper-text⟧="'.$this->helperText.'"' : null,
            $this->icon ? '    :⟦icon⟧="svg(\'ceplok-s-user\', \'size-4\')"' : null,
            '    wire:model="nama"',
            $this->clear ? '    ⟦clearable⟧' : null,
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

        <x-docs.stage :max-width="$platform === FloatingLabelPlatform::Mobile ? 'max-w-87' : 'max-w-91'" :dark="$dark">
            <x-ceplok::floating-label
                wire:model="value"
                :platform="$platform"
                :state="$state"
                :application="$application"
                :dark-mode="$dark"
                label="Placeholder Text"
                :helper-text="$helper ? $this->helperText : null"
                :icon="$icon ? svg('ceplok-s-user', 'size-4') : null"
                :clearable="$clear"
            />
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
                <x-docs.segmented label="Pilih state" model="state" :value="$state" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'error', 'label' => 'Error'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Application">
                <x-docs.segmented label="Pilih aplikasi" model="application" :value="$application" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Ikon kiri">
                <x-docs.segmented label="Tampilkan ikon kiri" model="icon" :value="$icon" :options="[
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

            <x-docs.control label="Tombol hapus">
                <x-docs.segmented label="Tampilkan tombol hapus" model="clear" :value="$clear" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500">
            Klik field di atas tanpa mengubah prop apa pun: labelnya naik saat difokus dan tetap di atas
            selama masih ada isi. Perhatikan juga posisi label saat turun — ia menyesuaikan diri bila ada ikon
            kiri.
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
