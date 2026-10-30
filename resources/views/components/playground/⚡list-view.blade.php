<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $variant = 'simple';
    public string $platform = 'desktop';
    public string $size = 'm';
    public bool $decoration = false;
    public bool $hasBadge = false;
    public bool $dark = false;

    public function updatedVariant(string $variant): void
    {
        if ($variant === 'article') {
            $this->decoration = true;

            return;
        }

        $this->decoration = false;
        if ($this->size === 'xl') {
            $this->size = 'm';
        }
    }

    #[Computed]
    public function usage(): string
    {
        if ($this->variant === 'article') {
            $props = collect([
                $this->size !== 'm' ? '    ⟦*size⟧="'.$this->size.'"' : null,
                '    ⟦*subject⟧="Subject Name"',
                '    ⟦*category⟧="Category"',
                '    ⟦*timestamp⟧="12 Okt 2026 09:59 WIB"',
                '    ⟦*title⟧="Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi"',
                '    :⟦*views⟧="609"',
                '    :⟦*comments⟧="3"',
                '    :⟦*likes⟧="16"',
                $this->dark ? '    ⟦*dark-mode⟧' : null,
            ])->filter()->implode("\n");

            return "<x-ceplok::list-view\n    variant=\"article\"\n{$props}\n/>";
        }

        $props = collect([
            $this->platform === 'mobile' ? '    ⟦*platform⟧="mobile"' : null,
            $this->size !== 'm' ? '    ⟦*size⟧="'.$this->size.'"' : null,
            $this->decoration ? '    ⟦*decoration⟧' : null,
            '    ⟦*title⟧="Project brief"',
            "    :⟦*metadata⟧=\"['PDF', '2.4 MB', 'Updated Oct 10, 2026']\"",
            $this->platform !== 'mobile' ? '    ⟦*action-label⟧="Open"' : null,
            $this->dark ? '    ⟦*dark-mode⟧' : null,
        ])->filter()->implode("\n");

        $badge = $this->hasBadge
            ? "\n    <x-slot:⟦*badge⟧>\n        <x-ceplok::badge variant=\"success\" size=\"sm\" :icon=\"svg('ceplok-s-clock')\">Badge</x-ceplok::badge>\n    </x-slot:badge>"
            : '';

        return "<x-ceplok::list-view\n{$props}{$badge}\n/>";
    }
};
?>

<div class="space-y-16">
<x-docs.section id="playground" title="Playground">
    <x-docs.lead>Uji kombinasi varian, platform, ukuran, dekorasi, badge, dan tema secara langsung.</x-docs.lead>
    @php
        $canvasWidth = match (true) {
            $variant === 'article' && $size === 's' => 'max-w-[410px]',
            $variant === 'article' && $size === 'l' => 'max-w-[728px]',
            $variant === 'article' && $size === 'xl' => 'max-w-[1028px]',
            $variant === 'article' => 'max-w-[514px]',
            $platform === 'mobile' => 'max-w-[348px]',
            $size === 's' => 'max-w-[410px]',
            $size === 'l' => 'max-w-[600px]',
            default => 'max-w-[514px]',
        };
        $canvasMinWidth = match (true) {
            $variant === 'article' && $size === 's' => '410px',
            $variant === 'article' && $size === 'l' => '728px',
            $variant === 'article' && $size === 'xl' => '1028px',
            $variant === 'article' => '514px',
            $platform === 'mobile' => '348px',
            $size === 's' => '410px',
            $size === 'l' => '600px',
            default => '514px',
        };
    @endphp
    <x-docs.stage max-width="max-w-6xl" :min-width="$canvasMinWidth" :dark="$dark">
        <div class="flex min-h-[328px] items-center justify-center py-4">
            <div class="w-full transition-[max-width] duration-300 {{ $canvasWidth }}">
                @if ($variant === 'article')
                    <x-ceplok::list-view variant="article" :size="$size" subject="subject Name" category="Category" timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi" :views="609" :comments="3" :likes="16" :dark-mode="$dark" />
                @else
                    <x-ceplok::list-view :platform="$platform" :size="$size" :decoration="$decoration" title="Project brief" :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" :dark-mode="$dark" :action-label="$platform === 'mobile' ? null : 'Open'">
                        @if ($hasBadge)
                            <x-slot:badge><x-ceplok::badge variant="success" size="sm" :dark-mode="$dark" :icon="svg('ceplok-s-clock')">Badge</x-ceplok::badge></x-slot:badge>
                        @endif
                    </x-ceplok::list-view>
                @endif
            </div>
        </div>
    </x-docs.stage>
    <x-docs.controls>
        <x-docs.control label="Variant"><x-docs.segmented label="Pilih variant" model="variant" :value="$variant" :options="[['value' => 'simple', 'label' => 'Simple'], ['value' => 'article', 'label' => 'Article']]" /></x-docs.control>
        <x-docs.control label="Platform"><x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :disabled="$variant === 'article'" :options="[['value' => 'desktop', 'label' => 'Desktop'], ['value' => 'mobile', 'label' => 'Mobile']]" /></x-docs.control>
        <x-docs.control label="Size"><x-docs.segmented label="Pilih ukuran" model="size" :value="$size" :options="$variant === 'article' ? [['value' => 's', 'label' => 'S'], ['value' => 'm', 'label' => 'M'], ['value' => 'l', 'label' => 'L'], ['value' => 'xl', 'label' => 'XL']] : [['value' => 's', 'label' => 'S'], ['value' => 'm', 'label' => 'M'], ['value' => 'l', 'label' => 'L']]" /></x-docs.control>
        <x-docs.control label="Decoration"><x-docs.segmented label="Pilih dekorasi" model="decoration" :value="$decoration" :disabled="$variant === 'article'" :options="[['value' => false, 'label' => 'Tidak Ada'], ['value' => true, 'label' => 'Ada']]" /></x-docs.control>
        <x-docs.control label="Badge"><x-docs.segmented label="Pilih badge" model="hasBadge" :value="$hasBadge" :disabled="$variant === 'article'" :options="[['value' => false, 'label' => 'Tidak Ada'], ['value' => true, 'label' => 'Ada']]" /></x-docs.control>
        <x-docs.control label="Mode"><x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[['value' => false, 'label' => 'Light'], ['value' => true, 'label' => 'Dark']]" /></x-docs.control>
    </x-docs.controls>
</x-docs.section>

<x-docs.section id="penggunaan" title="Penggunaan">
    <x-docs.lead>Kode ini mengikuti konfigurasi Playground. Prop yang dikendalikan kontrol diberi highlight tebal.</x-docs.lead>
    <x-docs.code :code="$this->usage" flush />
</x-docs.section>
</div>
