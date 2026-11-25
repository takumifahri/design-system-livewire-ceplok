<?php

use CeplokUi\DesignKit\Enums\ClipboardPlatform;
use CeplokUi\DesignKit\Enums\ClipboardVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public ClipboardVariant $variant = ClipboardVariant::Default;
    public ClipboardPlatform $platform = ClipboardPlatform::Default;
    public string $value = 'INV-2026-001';
    public bool $withLabel = true;
    public bool $withHelper = true;
    public bool $withPrefix = true;
    public bool $disabled = false;
    public bool $dark = false;
    #[Computed]
    public function usage(): string
    {
        $lines = ['<x-ceplok::clipboard', '    ⟦value⟧="'.e($this->value).'"'];
        $lines[] = $this->withLabel ? '    ⟦label⟧="Nomor referensi"' : '    ⟦aria-label⟧="Nomor referensi"';
        if ($this->variant === ClipboardVariant::Segmented) $lines[] = '    ⟦variant⟧="segmented"';
        if ($this->platform === ClipboardPlatform::Mobile) $lines[] = '    ⟦platform⟧="mobile"';
        if ($this->variant === ClipboardVariant::Segmented && $this->withPrefix) $lines[] = '    ⟦prefix⟧="ID"';
        if ($this->withHelper) $lines[] = '    ⟦helper-text⟧="Gunakan nomor ini saat menghubungi layanan."';
        if ($this->disabled) $lines[] = '    ⟦disabled⟧';
        if ($this->dark) $lines[] = '    ⟦dark-mode⟧';
        $lines[] = '/>';

        return implode("\n", $lines);
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>Ubah tampilan, variasi, dan konten melalui kontrol berikut. Preview memakai komponen production yang sama; bagian Penggunaan mengikuti pilihan Anda.</x-docs.lead>
        <x-docs.stage :max-width="match ([$variant, $platform]) {
            [ClipboardVariant::Segmented, ClipboardPlatform::Mobile] => 'max-w-[355px]',
            [ClipboardVariant::Segmented, ClipboardPlatform::Default] => 'max-w-[452px]',
            [ClipboardVariant::Default, ClipboardPlatform::Mobile] => 'max-w-[380px]',
            default => 'max-w-[384px]',
        }" :dark="$dark">
            <div>
                <x-ceplok::clipboard
                    :value="$value"
                    :variant="$variant"
                    :platform="$platform"
                    :label="$withLabel ? 'Nomor referensi' : null"
                    :aria-label="$withLabel ? null : 'Nomor referensi'"
                    :helper-text="$withHelper ? 'Gunakan nomor ini saat menghubungi layanan.' : null"
                    :prefix="$variant === ClipboardVariant::Segmented && $withPrefix ? 'ID' : null"
                    :disabled="$disabled"
                    :dark-mode="$dark"
                />
            </div>
        </x-docs.stage>
        <x-docs.controls>
            <x-docs.control label="Tampilan"><x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[['value' => false, 'label' => 'Light'], ['value' => true, 'label' => 'Dark']]" /></x-docs.control>
            <x-docs.control label="Variasi"><x-docs.segmented label="Pilih variasi" model="variant" :value="$variant" :options="[['value' => 'default', 'label' => 'Default'], ['value' => 'segmented', 'label' => 'Dengan segmen']]" /></x-docs.control>
            <x-docs.control label="Platform"><x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[['value' => 'default', 'label' => 'Desktop'], ['value' => 'mobile', 'label' => 'Mobile']]" /></x-docs.control>
            <x-docs.control label="Nilai"><input wire:model.live="value" aria-label="Nilai yang disalin" class="h-10 w-full rounded-lg border border-gray-300 bg-surface px-3 text-sm text-content focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" /></x-docs.control>
            <x-docs.control label="Label"><x-docs.segmented label="Tampilkan label" model="withLabel" :value="$withLabel" :options="[['value' => true, 'label' => 'Ada'], ['value' => false, 'label' => 'Tidak ada']]" /></x-docs.control>
            <x-docs.control label="Helper text"><x-docs.segmented label="Tampilkan helper" model="withHelper" :value="$withHelper" :options="[['value' => true, 'label' => 'Ada'], ['value' => false, 'label' => 'Tidak ada']]" /></x-docs.control>
            <x-docs.control label="Prefix"><x-docs.segmented label="Tampilkan prefix" model="withPrefix" :value="$withPrefix" :options="[['value' => true, 'label' => 'Ada'], ['value' => false, 'label' => 'Tidak ada']]" :disabled="$variant === ClipboardVariant::Default" /></x-docs.control>
            <x-docs.control label="Nonaktif"><x-docs.segmented label="Nonaktifkan salin" model="disabled" :value="$disabled" :options="[['value' => false, 'label' => 'Tidak'], ['value' => true, 'label' => 'Ya']]" /></x-docs.control>
        </x-docs.controls>
        <p class="mt-4 text-body-sm text-content-subtle">
            Kontrol Nilai hanya mengubah contoh. Clipboard production tetap hanya-baca; penyalinan memerlukan HTTPS
            atau lingkungan lokal yang dipercaya browser.
        </p>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>Blok ini mengikuti kontrol di Playground. Prop bernilai bawaan sengaja tidak ditulis.</x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
