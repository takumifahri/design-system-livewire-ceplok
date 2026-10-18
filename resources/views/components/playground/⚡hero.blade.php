<?php

use CeplokUi\DesignKit\Enums\HeroImageOrientation;
use CeplokUi\DesignKit\Enums\HeroPlatform;
use CeplokUi\DesignKit\Enums\HeroType;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Hero. Kontrol Platform mengubah dua hal sekaligus: prop
 * `platform` pada hero dan lebar pembungkus pratinjaunya.
 */
new class extends Component
{
    public const HEADING = 'Hero Design system Ceplok';

    public const DESCRIPTION = 'Design System Ceplok adalah sebuah kumpulan standar desain terpadu yang digunakan untuk memastikan seluruh produk digital di lingkungan Kementerian Komunikasi dan Informatika (Ceplok)';

    public HeroType $type = HeroType::HorizontalImageLeft;

    public HeroPlatform $platform = HeroPlatform::Desktop;

    public HeroImageOrientation $orientation = HeroImageOrientation::Portrait;

    public bool $subHeading = true;

    public bool $button = true;

    public bool $dark = false;

    public function image(): string
    {
        return $this->orientation === HeroImageOrientation::Landscape
            ? '/images/hero-sample-landscape.svg'
            : '/images/hero-sample.svg';
    }

    #[Computed]
    public function usage(): string
    {
        $mobile = $this->platform === HeroPlatform::Mobile;
        $centered = $this->type === HeroType::Centered;
        $indent = $mobile ? '    ' : '';
        $summary = implode(' · ', [
            'type "'.$this->type->value.'"',
            $mobile ? 'Mobile · dibatasi 390px' : 'Desktop · lebar penuh',
            $centered ? 'tanpa gambar' : 'gambar '.$this->orientation->value,
            $this->subHeading ? 'dengan sub heading' : 'tanpa sub heading',
            $this->button ? 'dengan tombol' : 'tanpa tombol',
            $this->dark ? 'gelap' : 'terang',
        ]);

        $hero = array_map(fn (string $line) => $indent.$line, array_values(array_filter([
            '<x-ceplok::hero',
            '    ⟦*type⟧="'.$this->type->value.'"',
            '    ⟦*platform⟧="'.$this->platform->value.'"',
            $this->dark ? '    ⟦*dark-mode⟧' : null,
            '    heading="'.self::HEADING.'"',
            $this->subHeading ? '    sub-heading="'.self::HEADING.'"' : '    :⟦*show-heading⟧="false"',
            '    description="'.self::DESCRIPTION.'"',
            $this->button ? '    button-href="/design-system"' : '    :⟦*show-button⟧="false"',
            ! $centered && $this->orientation === HeroImageOrientation::Landscape ? '    ⟦*image-orientation⟧="landscape"' : null,
            ! $centered ? '    image="'.$this->image().'"' : null,
            '/>',
        ])));

        return implode("\n", array_filter([
            "{{-- {$summary} --}}",
            $mobile ? "{{-- Hero tidak perlu tahu ini mobile — susunannya mengikuti\n     lebar pembungkusnya sendiri lewat container query. --}}" : null,
            $mobile ? '<div class="⟦*max-w-[390px]⟧">' : null,
            implode("\n", $hero),
            $mobile ? '</div>' : null,
        ]));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Keenam kontrol di bawah panggung menggabungkan prop-prop di atas dalam
            satu tampilan, dan bagian Penggunaan menuliskan kodenya. Kontrol
            <em>Platform</em> mengubah dua hal sekaligus: prop <span class="text-primary-500">platform</span>
            pada hero dan lebar pembungkus pratinjaunya. Pada <span class="text-primary-500">centered</span>
            gambar memang tidak muncul walau <span class="text-primary-500">image</span> tetap diisi — itu bagian
            dari tipenya, bukan kekeliruan.
        </x-docs.lead>

        <x-docs.stage :max-width="$platform === HeroPlatform::Mobile ? 'max-w-[390px]' : 'max-w-full'" :dark="$dark">
            <x-docs.hero-frame :dark="$dark">
                <x-ceplok::hero
                    :dark-mode="$dark"
                    :type="$type"
                    :platform="$platform"
                    :heading="$this::HEADING"
                    :sub-heading="$this::HEADING"
                    :description="$this::DESCRIPTION"
                    :show-heading="$subHeading"
                    :show-button="$button"
                    :image-orientation="$orientation"
                    :image="$this->image()"
                    button-href="#"
                />
            </x-docs.hero-frame>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tipe">
                <x-docs.segmented label="Pilih tipe hero" model="type" :value="$type" wrap :options="[
                    ['value' => 'horizontal-image-left', 'label' => 'Image Left'],
                    ['value' => 'horizontal-image-right', 'label' => 'Image Right'],
                    ['value' => 'centered', 'label' => 'Centered'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile', 'icon' => 'M7 3h10a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm4 15h2'],
                    ['value' => 'desktop', 'label' => 'Desktop', 'icon' => 'M3 5h18v11H3V5Zm6 15h6m-3-4v4'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Gambar">
                <x-docs.segmented label="Pilih orientasi gambar" model="orientation" :value="$orientation" :disabled="$type === HeroType::Centered" :options="[
                    ['value' => 'portrait', 'label' => 'Potret'],
                    ['value' => 'landscape', 'label' => 'Lanskap'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Sub heading">
                <x-docs.segmented label="Tampilkan sub heading" model="subHeading" :value="$subHeading" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Tombol">
                <x-docs.segmented label="Tampilkan tombol" model="button" :value="$button" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Terang'],
                    ['value' => true, 'label' => 'Gelap'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Mengikuti keenam kontrol di Playground — tipe, platform, orientasi
            gambar, sub heading, tombol, dan mode. Nama prop yang sedang dikendalikan
            kontrol ditandai dengan warna biru, dan tombol <em>Salin</em> selalu
            menyalin persis yang sedang tampil.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
