<?php

use CeplokUi\DesignKit\Enums\InputFieldApplication;
use CeplokUi\DesignKit\Enums\InputFieldPlatform;
use CeplokUi\DesignKit\Enums\InputFieldState;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Input Field. Isiannya diikat ke $value lewat
 * wire:model, jadi tetap ada saat kontrol lain diganti. `wire:key` memuat
 * tipe dan tombol hapus karena keduanya menambah/mencabut x-data di elemen
 * akar Input Field; perubahan lain cukup ditambal morph.
 */
new class extends Component
{
    /** Tipe isian: teks atau kata sandi. */
    public string $kind = 'text';

    public InputFieldPlatform $platform = InputFieldPlatform::Default;

    public InputFieldState $state = InputFieldState::Default;

    public InputFieldApplication $application = InputFieldApplication::Default;

    public bool $icon = true;

    public bool $helper = true;

    public bool $clear = true;

    public bool $dark = false;

    public string $value = '';

    #[Computed]
    public function password(): bool
    {
        return $this->kind === 'password';
    }

    /**
     * Password tidak memakai state inactive: pilihannya dimatikan, dan selama
     * tipe password field tampil default. Kembali ke teks, pilihan semula
     * berlaku lagi.
     */
    #[Computed]
    public function fieldState(): InputFieldState
    {
        return $this->password && $this->state === InputFieldState::Inactive ? InputFieldState::Default : $this->state;
    }

    #[Computed]
    public function label(): string
    {
        return $this->password ? 'Kata sandi' : 'Nama lengkap';
    }

    #[Computed]
    public function placeholder(): string
    {
        return $this->password ? '••••••••' : 'Masukkan nama lengkap';
    }

    #[Computed]
    public function helperText(): string
    {
        $failed = $this->fieldState === InputFieldState::Failed;

        return $this->password
            ? ($failed ? 'Kata sandi minimal 8 karakter.' : 'Gunakan minimal 8 karakter.')
            : ($failed ? 'Nama lengkap wajib diisi.' : 'Sesuai yang tertera pada KTP.');
    }

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::input-field',
            $this->password ? '    ⟦type⟧="password"' : null,
            $this->platform !== InputFieldPlatform::Default ? '    ⟦platform⟧="'.$this->platform->value.'"' : null,
            $this->fieldState !== InputFieldState::Default ? '    ⟦state⟧="'.$this->fieldState->value.'"' : null,
            $this->application !== InputFieldApplication::Default ? '    ⟦application⟧="'.$this->application->value.'"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    label="'.$this->label.'"',
            '    placeholder="'.$this->placeholder.'"',
            $this->helper ? '    ⟦helper-text⟧="'.$this->helperText.'"' : null,
            $this->icon && ! $this->password ? '    :⟦icon⟧="svg(\'ceplok-s-user\', \'size-4\')"' : null,
            $this->password ? '    wire:model="sandi"' : '    wire:model="nama"',
            $this->clear && ! $this->password && $this->fieldState !== InputFieldState::Inactive ? '    ⟦clearable⟧' : null,
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

        <x-docs.stage :max-width="$platform === InputFieldPlatform::Mobile ? 'max-w-81.5' : 'max-w-91'" :dark="$dark">
            {{-- Password selalu memakai gembok bawaannya dan tidak pernah bertombol hapus. --}}
            <x-ceplok::input-field
                wire:key="input-{{ $kind }}-{{ (int) ($clear && ! $this->password) }}"
                wire:model="value"
                :type="$this->password ? 'password' : null"
                :platform="$platform"
                :state="$this->fieldState"
                :application="$application"
                :dark-mode="$dark"
                :label="$this->label"
                :placeholder="$this->placeholder"
                :helper-text="$helper ? $this->helperText : null"
                :icon="! $this->password && $icon ? svg('ceplok-s-user', 'size-4') : null"
                :clearable="$clear && ! $this->password"
            />
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Tipe">
                <x-docs.segmented label="Pilih tipe" model="kind" :value="$kind" :options="[
                    ['value' => 'text', 'label' => 'Teks'],
                    ['value' => 'password', 'label' => 'Password'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'default', 'label' => 'Desktop'],
                ]" />
            </x-docs.control>

            <x-docs.control label="State">
                <x-docs.segmented label="Pilih state" model="state" :value="$this->fieldState" item-class="px-2.5" wrap :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'typing', 'label' => 'Typing'],
                    ['value' => 'inactive', 'label' => 'Inactive', 'disabled' => $this->password],
                    ['value' => 'failed', 'label' => 'Failed'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Application">
                <x-docs.segmented label="Pilih aplikasi" model="application" :value="$application" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Ikon kiri">
                {{-- Mati pada password: gemboknya selalu ada, sesuai desain. --}}
                <x-docs.segmented label="Tampilkan ikon kiri" model="icon" :value="$this->password || $icon" :disabled="$this->password" :options="[
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
                {{--
                    Mati saat inactive — field yang tidak bisa diisi tidak perlu tombol hapus — dan pada
                    password, yang selalu tanpa tombol hapus karena sisi kanannya dipakai tombol mata.
                --}}
                <x-docs.segmented label="Tampilkan tombol hapus" model="clear" :value="$clear && ! $this->password" :disabled="$this->password || $this->fieldState === InputFieldState::Inactive" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500">
            Ketik pada field di atas untuk melihat state <em>typing</em> yang sesungguhnya — garisnya berubah
            lewat <span class="text-primary-500">focus-within</span>, tanpa perlu mengubah prop. Pada state <em>failed</em>, helper text
            otomatis berganti jadi pesan kesalahan.
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
