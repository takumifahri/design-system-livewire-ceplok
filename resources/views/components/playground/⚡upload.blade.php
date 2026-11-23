<?php

use CeplokUi\DesignKit\Enums\UploadApplication;
use CeplokUi\DesignKit\Enums\UploadPlatform;
use CeplokUi\DesignKit\Enums\UploadType;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Upload Form. `wire:key` memuat type: kedua bentuk punya
 * input berkas di tempat yang berbeda, dan pendengarnya dipasang sekali saat
 * Upload diinisialisasi.
 */
new class extends Component
{
    public UploadType $type = UploadType::Default;

    public UploadApplication $application = UploadApplication::Default;

    public UploadPlatform $platform = UploadPlatform::Default;

    public bool $label = true;

    public bool $helper = true;

    public bool $dark = false;

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::upload',
            $this->type !== UploadType::Default ? '    ⟦type⟧="attach"' : null,
            $this->application !== UploadApplication::Default ? '    ⟦application⟧="'.$this->application->value.'"' : null,
            $this->platform !== UploadPlatform::Default && $this->type === UploadType::Default ? '    ⟦platform⟧="mobile"' : null,
            $this->label ? '    ⟦label⟧="Unggah dokumen"' : null,
            $this->helper ? '    ⟦helper-text⟧="PDF atau JPG, maksimal 2 MB."' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    wire:model="dokumen"',
            '/>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Satu komponen yang bisa Anda utak-atik lewat kontrol di bawahnya. Pilih berkas, atau seret
            satu ke area attach, untuk melihat namanya muncul.
        </x-docs.lead>

        <x-docs.stage :dark="$dark" :max-width="$platform === UploadPlatform::Mobile ? 'max-w-95.5' : 'max-w-134.5'">
            <x-ceplok::upload
                wire:key="upload-{{ $type->value }}"
                :type="$type"
                :platform="$platform"
                :application="$application"
                :label="$label ? 'Unggah dokumen' : null"
                :helper-text="$helper ? 'PDF atau JPG, maksimal 2 MB.' : null"
                :aria-label="$label ? null : 'Unggah dokumen'"
                :dark-mode="$dark"
            />
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Type">
                <x-docs.segmented label="Pilih bentuk" model="type" :value="$type" :options="[
                    ['value' => 'default', 'label' => 'Upload'],
                    ['value' => 'attach', 'label' => 'Attach'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Application">
                <x-docs.segmented label="Pilih aplikasi" model="application" :value="$application" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'mobile', 'label' => 'Mobile'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Label">
                <x-docs.segmented label="Tampilkan label" model="label" :value="$label" :options="[
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

        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
            Pada bentuk Attach, <em>Platform</em> hanya menyempitkan wadah pratinjaunya dan
            <em>Application</em> baru terlihat saat berkas diseret ke atas kotak — isi kotaknya sendiri
            tidak berubah.
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
