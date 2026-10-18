<?php

use CeplokUi\DesignKit\Enums\ToggleApplication;
use CeplokUi\DesignKit\Enums\TogglePlatform;
use CeplokUi\DesignKit\Enums\ToggleState;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Toggle Button: sakelarnya diikat ke $aktif lewat
 * wire:model, jadi keadaannya tetap saat kontrol lain diganti.
 */
new class extends Component
{
    public TogglePlatform $platform = TogglePlatform::Default;

    public ToggleState $state = ToggleState::Default;

    public ToggleApplication $application = ToggleApplication::Default;

    public bool $caption = false;

    public bool $dark = false;

    public bool $aktif = true;

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::toggle',
            $this->platform !== TogglePlatform::Default ? '    ⟦platform⟧="mobile"' : null,
            $this->state !== ToggleState::Default ? '    ⟦state⟧="inactive"' : null,
            $this->application !== ToggleApplication::Default ? '    ⟦application⟧="'.$this->application->value.'"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    label="Notifikasi email"',
            $this->caption ? '    ⟦helper-text⟧="Kirim ringkasan permohonan baru setiap pagi."' : null,
            '    wire:model="aktif"',
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

        {{-- max-w-fit: blok menyusut seukuran isinya, jadi mx-auto benar-benar memusatkannya. --}}
        <x-docs.stage max-width="max-w-fit" :dark="$dark">
            <x-ceplok::toggle
                wire:model="aktif"
                :platform="$platform"
                :state="$state"
                :application="$application"
                :dark-mode="$dark"
                label="Notifikasi email"
                :helper-text="$caption ? 'Kirim ringkasan permohonan baru setiap pagi.' : null"
            />
        </x-docs.stage>

        <x-docs.controls>
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

            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500">
            Kondisi mati dan menyala diatur lewat <span class="text-primary-500">checked</span> atau <span class="text-primary-500">wire:model</span> seperti checkbox biasa —
            bukan lewat prop <span class="text-primary-500">state</span>, yang hanya menangani <em>inactive</em>.
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
