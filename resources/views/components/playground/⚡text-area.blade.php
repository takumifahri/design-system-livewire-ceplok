<?php

use CeplokUi\DesignKit\Enums\TextAreaApplication;
use CeplokUi\DesignKit\Enums\TextAreaPlatform;
use CeplokUi\DesignKit\Enums\TextAreaType;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Text Area. Isiannya diikat ke $value lewat
 * wire:model.live, jadi penghitung karakter di hint ikut dihitung server.
 * `wire:key` memuat type karena editor menambah x-data di elemen akarnya.
 */
new class extends Component
{
    public TextAreaType $type = TextAreaType::Editor;

    public TextAreaPlatform $platform = TextAreaPlatform::Default;

    public TextAreaApplication $application = TextAreaApplication::Default;

    /** Isi hint: `counter`, `opsional`, atau `none` — hint tak harus penghitung karakter. */
    public string $hint = 'opsional';

    public bool $helper = true;

    public bool $submit = true;

    public bool $dark = false;

    public string $value = '';

    #[Computed]
    public function editor(): bool
    {
        return $this->type === TextAreaType::Editor;
    }

    #[Computed]
    public function hintText(): ?string
    {
        return match ($this->hint) {
            'counter' => mb_strlen($this->value).'/500',
            'opsional' => 'Opsional',
            default => null,
        };
    }

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::text-area',
            $this->editor ? '    ⟦type⟧="editor"' : null,
            $this->platform !== TextAreaPlatform::Default ? '    ⟦platform⟧="'.$this->platform->value.'"' : null,
            $this->application !== TextAreaApplication::Default ? '    ⟦application⟧="'.$this->application->value.'"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    label="Isi pesan"',
            match ($this->hint) {
                'counter' => '    :⟦hint⟧="mb_strlen($pesan).\'/500\'"',
                'opsional' => '    ⟦hint⟧="Opsional"',
                default => null,
            },
            '    placeholder="Tulis pesan Anda…"',
            $this->helper ? '    ⟦helper-text⟧="Maksimal 500 karakter."' : null,
            '    maxlength="500"',
            $this->hint === 'counter' ? '    wire:model.live="pesan"' : '    wire:model="pesan"',
            $this->editor && $this->submit ? '    ⟦submit-label⟧="Submit Text"' : null,
            $this->editor && $this->submit ? '    wire:submit="kirim"' : null,
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

        <x-docs.stage :max-width="$platform === TextAreaPlatform::Mobile && ! $this->editor ? 'max-w-95' : 'max-w-123.5'" :dark="$dark">
            <x-ceplok::text-area
                wire:key="text-area-{{ $type->value }}"
                wire:model.live="value"
                :type="$type"
                :platform="$platform"
                :application="$application"
                :dark-mode="$dark"
                label="Isi pesan"
                :hint="$this->hintText"
                placeholder="Tulis pesan Anda…"
                :helper-text="$helper ? 'Maksimal 500 karakter.' : null"
                maxlength="500"
                :submit-label="$submit ? 'Submit Text' : null"
            />
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Type">
                <x-docs.segmented label="Pilih type" model="type" :value="$type" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'editor', 'label' => 'Editor'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'default', 'label' => 'Desktop'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Application">
                <x-docs.segmented label="Pilih aplikasi" model="application" :value="$application" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Hint">
                <x-docs.segmented label="Pilih isi hint" model="hint" :value="$hint" item-class="px-2.5" :options="[
                    ['value' => 'counter', 'label' => 'Counter'],
                    ['value' => 'opsional', 'label' => 'Opsional'],
                    ['value' => 'none', 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Helper text">
                <x-docs.segmented label="Tampilkan helper text" model="helper" :value="$helper" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Tombol kirim">
                {{-- Mati pada type default: di sana tombol kirim memang tidak pernah dirender. --}}
                <x-docs.segmented label="Tampilkan tombol kirim" model="submit" :value="$submit" :disabled="! $this->editor" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500">
            Tombol kirim hanya dirender pada type <em>editor</em>, jadi kontrolnya ikut mati saat type
            <em>default</em>. Hint dan helper text berlaku di kedua type; platform hanya mengubah tinggi kotak
            pada type <em>default</em>. Isi <span class="text-primary-500">hint</span> bebas — penghitung karakter perlu diikat ke nilai
            isian, sedangkan penanda seperti <em>Opsional</em> cukup teks tetap.
        </p>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground — ubah kontrolnya, kodenya ikut berubah. Prop yang
            nilainya masih bawaan sengaja tidak ditulis, jadi yang tersisa hanya yang benar-benar perlu Anda
            salin.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
