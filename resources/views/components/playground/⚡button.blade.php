<?php

use CeplokUi\DesignKit\Enums\ButtonAs;
use CeplokUi\DesignKit\Enums\ButtonSize;
use CeplokUi\DesignKit\Enums\ButtonTheme;
use CeplokUi\DesignKit\Enums\ButtonTone;
use CeplokUi\DesignKit\Enums\ButtonVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Button: setiap kontrol mengubah satu properti, Livewire
 * merender ulang tombolnya di server, dan blok Penggunaan menyusun kode yang
 * sama dengan yang sedang tampil.
 */
new class extends Component
{
    public ButtonAs $as = ButtonAs::Button;

    public bool $iconOnly = false;

    public ButtonSize $size = ButtonSize::Base;

    public ButtonVariant $variant = ButtonVariant::Filled;

    public ButtonTone $tone = ButtonTone::Light;

    /** Posisi ikon: none, left, right, atau both. */
    public string $icons = 'both';

    public ButtonTheme $theme = ButtonTheme::Primary;

    /** Tombol khusus ikon tidak punya ikon kiri/kanan. */
    public function updatedIconOnly(bool $iconOnly): void
    {
        if ($iconOnly) {
            $this->icons = 'none';
        }
    }

    public function hasIcon(string $side): bool
    {
        return ! $this->iconOnly && in_array($this->icons, [$side, 'both'], true);
    }

    #[Computed]
    public function usage(): string
    {
        $icon = "svg('ceplok-s-messages', 'size-4')";

        return collect([
            '<x-ceplok::button',
            '    ⟦as⟧="'.$this->as->value.'"',
            $this->as === ButtonAs::Anchor ? '    href="/foundations/colors"' : null,
            $this->iconOnly ? '    ⟦icon-only⟧' : null,
            $this->iconOnly ? '    aria-label="Kirim pesan"' : null,
            '    ⟦variant⟧="'.$this->variant->value.'"',
            '    ⟦theme⟧="'.$this->theme->value.'"',
            '    ⟦tone⟧="'.$this->tone->value.'"',
            '    ⟦size⟧="'.$this->size->value.'"',
            $this->hasIcon('left') ? '    :⟦left-icon⟧="'.$icon.'"' : null,
            $this->hasIcon('right') ? '    :⟦right-icon⟧="'.$icon.'"' : null,
            '>',
            $this->iconOnly ? '    {{ '.$icon.' }}' : '    Button',
            '</x-ceplok::button>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <p class="mb-6 text-body-sm text-gray-500">
            Memungkinkan Anda mencoba kombinasi prop secara langsung untuk membantu menyesuaikan
            tombol dengan kebutuhan aplikasi Anda sebelum diimplementasikan.
        </p>

        <x-docs.stage max-width="max-w-[380px]">
            <div class="mx-auto flex min-h-45 items-center justify-center">
                <x-ceplok::button
                    :as="$as"
                    :href="$as === ButtonAs::Anchor ? route('foundations.colors') : null"
                    :icon-only="$iconOnly"
                    :aria-label="$iconOnly ? 'Kirim pesan' : null"
                    :size="$size"
                    :variant="$variant"
                    :theme="$theme"
                    :tone="$tone"
                    :left-icon="$this->hasIcon('left') ? svg('ceplok-s-messages', 'size-4') : null"
                    :right-icon="$this->hasIcon('right') ? svg('ceplok-s-messages', 'size-4') : null"
                >
                    @if ($iconOnly)
                        {{ svg('ceplok-s-messages', 'size-4') }}
                    @else
                        Button
                    @endif
                </x-ceplok::button>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="As">
                <x-docs.segmented label="Pilih render" model="as" :value="$as" :options="[
                    ['value' => 'button', 'label' => 'Button'],
                    ['value' => 'anchor', 'label' => 'Link'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Icon Only">
                <x-docs.segmented label="Pilih icon only" model="iconOnly" :value="$iconOnly" :options="[
                    ['value' => true, 'label' => 'Ya'],
                    ['value' => false, 'label' => 'Tidak'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Size">
                <x-docs.segmented
                    label="Pilih ukuran"
                    model="size"
                    :value="$size"
                    :options="collect(ButtonSize::cases())->map(fn ($size) => ['value' => $size->value, 'label' => strtoupper($size->value)])->all()"
                    wrap
                />
            </x-docs.control>

            <x-docs.control label="Variant">
                <x-docs.segmented label="Pilih variant" model="variant" :value="$variant" :options="[
                    ['value' => 'filled', 'label' => 'Filled'],
                    ['value' => 'outline', 'label' => 'Outline'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Tone">
                <x-docs.segmented label="Pilih tone" model="tone" :value="$tone" :options="[
                    ['value' => 'light', 'label' => 'Light'],
                    ['value' => 'dark', 'label' => 'Dark'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Icons">
                <x-docs.segmented
                    label="Pilih posisi ikon"
                    model="icons"
                    :value="$iconOnly ? 'none' : $icons"
                    :disabled="$iconOnly"
                    :options="[
                        ['value' => 'none', 'label' => 'None'],
                        ['value' => 'left', 'label' => 'Left'],
                        ['value' => 'right', 'label' => 'Right'],
                        ['value' => 'both', 'label' => 'Both'],
                    ]"
                />
            </x-docs.control>

            <x-docs.control label="Theme">
                <x-docs.segmented
                    label="Pilih theme"
                    model="theme"
                    :value="$theme"
                    :options="collect(ButtonTheme::cases())->map(fn ($theme) => ['value' => $theme->value, 'label' => ucfirst($theme->value)])->all()"
                    wrap
                    item-class="basis-1/3 justify-center"
                />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <p class="mb-6 text-body-sm text-gray-500">
            Bagian ini menampilkan kode setelah Anda menggunakan Playground untuk kombinasi prop. Kode
            yang tampil di sini adalah kode yang siap diimplementasikan ke aplikasi.
        </p>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
