<?php

use CeplokUi\DesignKit\Enums\DatepickerPlatform;
use CeplokUi\DesignKit\Enums\DatepickerType;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Datepicker. Nilai single dan rentang disimpan di
 * properti terpisah, seperti dua state di Playground React, dan diikat lewat
 * wire:model.live supaya nilainya langsung tampil di bawah panggung.
 * `wire:key` memuat kontrol yang mengubah bentuk kotak atau konfigurasi
 * kalendernya.
 */
new class extends Component
{
    public DatepickerType $type = DatepickerType::Single;

    public DatepickerPlatform $platform = DatepickerPlatform::Default;

    public bool $dark = false;

    public bool $label = true;

    public bool $disabled = false;

    public bool $shortcuts = false;

    /** Batas data: hari ini sampai tanggal yang sama tahun depan. */
    public bool $batas = false;

    public ?string $tunggal = null;

    /** @var array{start: ?string, end: ?string}|null */
    public ?array $rentang = null;

    /** Nilai properti yang diikat, ditulis seperti di PHP. */
    #[Computed]
    public function nilai(): string
    {
        $tulis = fn (?string $tanggal) => $tanggal === null ? 'null' : "'{$tanggal}'";

        if ($this->type === DatepickerType::Single) {
            return $tulis($this->tunggal);
        }

        return $this->rentang === null
            ? 'null'
            : "['start' => {$tulis($this->rentang['start'] ?? null)}, 'end' => {$tulis($this->rentang['end'] ?? null)}]";
    }

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        [$model, $tipe] = match ($this->type) {
            DatepickerType::Single => ['tanggal', '?string'],
            DatepickerType::Period => ['periode', '?array'],
            DatepickerType::Multiple => ['rentang', '?array'],
        };

        return collect([
            "{{-- Komponen Livewire: public {$tipe} \${$model} = null; --}}",
            '<x-ceplok::datepicker',
            $this->type !== DatepickerType::Single ? '    ⟦type⟧="'.$this->type->value.'"' : null,
            $this->platform === DatepickerPlatform::Mobile ? '    ⟦platform⟧="mobile"' : null,
            $this->type === DatepickerType::Multiple && $this->shortcuts ? '    ⟦shortcuts⟧' : null,
            $this->batas ? '    :⟦min⟧="today()"' : null,
            $this->batas ? '    :⟦max⟧="today()->addYear()"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            $this->disabled ? '    ⟦disabled⟧' : null,
            $this->label ? '    label="Nama Tanggal"' : '    aria-label="Nama Tanggal"',
            "    wire:model=\"{$model}\"",
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

        @php
            $mobile = $platform === DatepickerPlatform::Mobile;
            $single = $type === DatepickerType::Single;
        @endphp

        <x-docs.stage :max-width="! $mobile && $type === DatepickerType::Multiple ? 'max-w-150' : 'max-w-81.25'" :dark="$dark">
            <x-ceplok::datepicker
                wire:key="datepicker-{{ $type->value }}-{{ $platform->value }}-{{ (int) $dark }}-{{ (int) $batas }}-{{ (int) $shortcuts }}"
                :wire:model.live="$single ? 'tunggal' : 'rentang'"
                :type="$type"
                :platform="$platform"
                :shortcuts="$type === DatepickerType::Multiple && $shortcuts"
                :min="$batas ? today() : null"
                :max="$batas ? today()->addYear() : null"
                :dark-mode="$dark"
                :disabled="$disabled"
                :label="$label ? 'Nama Tanggal' : null"
                :aria-label="$label ? null : 'Nama Tanggal'"
            />
        </x-docs.stage>

        <p class="mt-4 text-body-sm text-gray-500">
            Nilai properti <span class="text-primary-500">wire:model</span>: <span class="text-primary-500">{{ $this->nilai }}</span>
        </p>

        <x-docs.controls>
            <x-docs.control label="Type">
                <x-docs.segmented label="Pilih bentuk" model="type" :value="$type" :options="[
                    ['value' => 'single', 'label' => 'Single'],
                    ['value' => 'period', 'label' => 'Period'],
                    ['value' => 'multiple', 'label' => 'Multiple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'default', 'label' => 'Desktop'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Shortcuts">
                {{-- Hanya multiple yang bisa memilih; period selalu punya pintasan, single tidak pernah. --}}
                <x-docs.segmented
                    label="Pintasan periode"
                    model="shortcuts"
                    :value="$type === DatepickerType::Multiple ? $shortcuts : $type === DatepickerType::Period"
                    :disabled="$type !== DatepickerType::Multiple"
                    :options="[
                        ['value' => false, 'label' => 'Tidak'],
                        ['value' => true, 'label' => 'Ya'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Batas data">
                <x-docs.segmented label="Pilih batas data" model="batas" :value="$batas" :options="[
                    ['value' => false, 'label' => 'Tidak ada'],
                    ['value' => true, 'label' => 'Hari ini – setahun lagi'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Label">
                <x-docs.segmented label="Tampilkan label" model="label" :value="$label" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Disabled">
                <x-docs.segmented label="Nonaktifkan" model="disabled" :value="$disabled" :options="[
                    ['value' => false, 'label' => 'Tidak'],
                    ['value' => true, 'label' => 'Ya'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground — ubah kontrolnya, kodenya ikut berubah. Prop yang
            nilainya masih bawaan sengaja tidak ditulis.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
