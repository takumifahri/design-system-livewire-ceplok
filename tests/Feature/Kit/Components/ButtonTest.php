<?php

namespace Tests\Feature\Kit\Components;

use CeplokUi\DesignKit\Enums\ButtonTheme;
use CeplokUi\DesignKit\Enums\ButtonVariant;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ButtonTest extends TestCase
{
    public function test_bawaannya_tombol_primary_light_filled_berukuran_base(): void
    {
        $button = $this->find($this->dom('<x-ceplok::button>Simpan</x-ceplok::button>'), 'body > button');

        $this->assertSame('button', $button->getAttribute('type'));
        $this->assertSame('Simpan', trim($button->textContent));
        $this->assertHasClasses($button, 'inline-flex items-center justify-center font-medium focus:ring-2 focus:ring-primary-400 disabled:opacity-50');
        $this->assertHasClasses($button, 'bg-primary-700 text-white hover:bg-primary-800 h-[40px] px-4 gap-2 rounded-lg text-base');
    }

    /** @return array<string, array{string, string, string, string}> */
    public static function warna(): array
    {
        return [
            'primary light filled' => ['primary', 'light', 'filled', 'bg-primary-700 text-white hover:bg-primary-800'],
            'primary dark outline' => ['primary', 'dark', 'outline', 'border border-primary-800 text-primary-800 hover:bg-primary-50'],
            'green light outline' => ['green', 'light', 'outline', 'border border-green-700 text-green-700 hover:bg-green-50'],
            'gray light memakai 500' => ['gray', 'light', 'filled', 'bg-gray-500 hover:bg-gray-700'],
            'gray dark outline menggelap saat hover' => ['gray', 'dark', 'outline', 'border-gray-700 text-gray-700 hover:text-gray-900 hover:border-gray-900'],
            'purple dark filled' => ['purple', 'dark', 'filled', 'bg-purple-800 hover:bg-purple-700'],
            'orange light memakai 600' => ['orange', 'light', 'filled', 'bg-orange-600 hover:bg-orange-700'],
            'yellow dark filled' => ['yellow', 'dark', 'filled', 'bg-yellow-800 hover:bg-yellow-700'],
        ];
    }

    #[DataProvider('warna')]
    public function test_theme_tone_dan_variant_menentukan_warna(string $theme, string $tone, string $variant, string $classes): void
    {
        $button = $this->find($this->dom("<x-ceplok::button theme=\"{$theme}\" tone=\"{$tone}\" variant=\"{$variant}\">Simpan</x-ceplok::button>"), 'button');

        $this->assertHasClasses($button, $classes);
    }

    /** @return array<string, array{string, string, string, string}> */
    public static function ukuran(): array
    {
        return [
            'xs' => ['xs', 'h-[34px] px-4 text-xs', 'h-[34px] w-[34px]', 'size-3'],
            's' => ['s', 'h-[38px] px-4 text-sm', 'h-[38px] w-[38px]', 'size-3'],
            'base' => ['base', 'h-[40px] px-4 text-base', 'h-[40px] w-[40px]', 'size-3.5'],
            'l' => ['l', 'h-[43px] px-5 text-lg', 'h-[43px] w-[43px]', 'size-3.5'],
            'xl' => ['xl', 'h-[46px] px-6 text-xl', 'h-[46px] w-[46px]', 'size-4'],
        ];
    }

    #[DataProvider('ukuran')]
    public function test_size_mengatur_tinggi_padding_dan_ukuran_ikon(string $size, string $button, string $iconOnly, string $icon): void
    {
        $dom = $this->dom("<x-ceplok::button size=\"{$size}\" :left-icon=\"svg('ceplok-o-bell')\">Simpan</x-ceplok::button>");

        $this->assertHasClasses($this->find($dom, 'button'), $button);
        $this->assertHasClasses($this->find($dom, 'button > span'), $icon);

        $round = $this->find($this->dom("<x-ceplok::button size=\"{$size}\" icon-only aria-label=\"Tutup\">{{ svg('ceplok-o-close') }}</x-ceplok::button>"), 'button');

        $this->assertHasClasses($round, $iconOnly.' rounded-full p-0');
        $this->assertLacksClasses($round, 'px-4 px-5 px-6');
    }

    public function test_href_tanpa_as_merender_tautan_tanpa_atribut_type(): void
    {
        $link = $this->find($this->dom('<x-ceplok::button href="/panduan">Panduan</x-ceplok::button>'), 'body > a');

        $this->assertSame('/panduan', $link->getAttribute('href'));
        $this->assertFalse($link->hasAttribute('type'));
        $this->assertHasClasses($link, 'inline-flex bg-primary-700');
    }

    public function test_as_menentukan_tag_meski_ada_href(): void
    {
        $this->find($this->dom('<x-ceplok::button as="anchor" href="/panduan">Panduan</x-ceplok::button>'), 'body > a');
        $this->find($this->dom('<x-ceplok::button as="button" wire:click="simpan">Simpan</x-ceplok::button>'), 'body > button[type="button"]');
        $this->assertNull($this->dom('<x-ceplok::button as="button" href="/panduan">Panduan</x-ceplok::button>')->querySelector('a'));
    }

    public function test_type_bisa_diganti_dan_divalidasi(): void
    {
        $this->find($this->dom('<x-ceplok::button type="submit">Kirim</x-ceplok::button>'), 'button[type="submit"]');

        $this->assertPropError(
            '<x-ceplok::button type="link">Kirim</x-ceplok::button>',
            "Prop `type` pada <x-ceplok::button> harus salah satu dari 'button', 'submit', 'reset'; diberikan 'link'.",
        );
    }

    public function test_ikon_kiri_dan_kanan_mengapit_teks(): void
    {
        $button = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::button :left-icon="svg('ceplok-o-bell')">
                <x-slot:right-icon>{{ svg('ceplok-o-arrow-right') }}</x-slot:right-icon>
                Kirim
            </x-ceplok::button>
            BLADE), 'button');

        $children = $this->childrenOf($button);

        $this->assertCount(2, $children);
        $this->assertSame('SPAN', $children[0]->tagName);
        $this->assertNotNull($children[0]->querySelector('svg'));
        $this->assertHasClasses($children[0], 'flex shrink-0 items-center justify-center size-3.5');
        $this->assertNotNull($children[1]->querySelector('svg'));
        $this->assertSame('Kirim', trim($button->textContent));
    }

    public function test_slot_ikon_kosong_tidak_merender_pembungkus(): void
    {
        $button = $this->find($this->dom('<x-ceplok::button><x-slot:left-icon> </x-slot:left-icon>Kirim</x-ceplok::button>'), 'button');

        $this->assertCount(0, $this->childrenOf($button));
    }

    public function test_icon_only_mengabaikan_ikon_kiri_kanan(): void
    {
        $button = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::button icon-only aria-label="Kirim pesan" :left-icon="svg('ceplok-o-bell')" :right-icon="svg('ceplok-o-bell')">
                {{ svg('ceplok-s-messages') }}
            </x-ceplok::button>
            BLADE), 'button');

        $this->assertSame('Kirim pesan', $button->getAttribute('aria-label'));
        $this->assertCount(1, $this->childrenOf($button));
        $this->assertHasClasses($this->childrenOf($button)[0], 'flex items-center justify-center size-3.5');
    }

    public function test_menerima_kasus_enum(): void
    {
        $button = $this->find($this->dom(
            '<x-ceplok::button :variant="$variant" :theme="$theme">Simpan</x-ceplok::button>',
            ['variant' => ButtonVariant::Outline, 'theme' => ButtonTheme::Green],
        ), 'button');

        $this->assertHasClasses($button, 'border-green-700 text-green-700');
    }

    public function test_atribut_tambahan_diteruskan_dan_kelas_disambung(): void
    {
        $button = $this->find($this->dom('<x-ceplok::button class="w-full" wire:click="simpan" disabled>Simpan</x-ceplok::button>'), 'button');

        $this->assertHasClasses($button, 'w-full inline-flex bg-primary-700');
        $this->assertSame('simpan', $button->getAttribute('wire:click'));
        $this->assertTrue($button->hasAttribute('disabled'));
    }

    /** @return array<string, array{string, string, string}> */
    public static function warnaGelap(): array
    {
        return [
            'light filled' => ['light', 'filled', 'bg-gray-50 text-gray-700 hover:bg-white hover:text-gray-700'],
            'light outline' => ['light', 'outline', 'border border-gray-50 text-gray-50 hover:border-gray-50 hover:bg-white hover:text-gray-700'],
            'dark filled' => ['dark', 'filled', 'bg-gray-50 text-gray-700 hover:bg-white hover:text-gray-700'],
            'dark outline' => ['dark', 'outline', 'border border-gray-500 text-gray-500 hover:border-gray-50 hover:bg-white hover:text-gray-700'],
        ];
    }

    #[DataProvider('warnaGelap')]
    public function test_dark_mode_mengganti_warna_apa_pun_themenya(string $tone, string $variant, string $classes): void
    {
        $button = $this->find($this->dom('<x-ceplok::button theme="orange" tone="'.$tone.'" variant="'.$variant.'" dark-mode>Simpan</x-ceplok::button>'), 'button');

        $this->assertHasClasses($button, $classes);
        $this->assertLacksClasses($button, 'bg-orange-600 border-orange-600');
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::button dark-mode="ya">Simpan</x-ceplok::button>', 'Prop `dark-mode` pada <x-ceplok::button> harus boolean');
        $this->assertPropError(
            '<x-ceplok::button variant="ghost">Simpan</x-ceplok::button>',
            "Prop `variant` pada <x-ceplok::button> harus salah satu dari 'filled', 'outline'; diberikan 'ghost'.",
        );
        $this->assertPropError('<x-ceplok::button theme="red">Simpan</x-ceplok::button>', 'Prop `theme` pada <x-ceplok::button>');
        $this->assertPropError('<x-ceplok::button size="m">Simpan</x-ceplok::button>', 'Prop `size` pada <x-ceplok::button>');
        $this->assertPropError('<x-ceplok::button icon-only="ya">Simpan</x-ceplok::button>', 'Prop `icon-only` pada <x-ceplok::button> harus boolean');
    }
}
