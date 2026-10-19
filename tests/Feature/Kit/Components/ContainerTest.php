<?php

namespace Tests\Feature\Kit\Components;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContainerTest extends TestCase
{
    public function test_elemen_luar_titik_ukur_dan_elemen_dalam_pembatas_lebar(): void
    {
        $outer = $this->find($this->dom('<x-ceplok::container class="py-12">Isi</x-ceplok::container>'), 'body > div');

        // Kelas pemakai di elemen luar; lebar, pemusatan, dan padding di dalam.
        $this->assertHasClasses($outer, '@container mx-auto py-12');

        $inner = $this->find($outer, 'div > div');

        $this->assertHasClasses($inner, 'mx-auto w-full max-w-[380px] rounded-xl @[640px]:max-w-[1126px] @[640px]:rounded-none');
        $this->assertHasClasses($inner, 'px-5 @[640px]:px-8 @[1024px]:px-12 @[1280px]:px-14');
        $this->assertHasClasses($inner, 'in-data-[theme=dark]:bg-container in-data-[theme=dark]:text-content-subtle');
        $this->assertLacksClasses($inner, 'py-12');
        $this->assertSame('Isi', trim($inner->textContent));
    }

    /** @return array<string, array{string, string}> */
    public static function ukuran(): array
    {
        return [
            'prose' => ['prose', 'max-w-[380px] @[640px]:max-w-[720px]'],
            'default' => ['default', 'max-w-[380px] @[640px]:max-w-[1126px]'],
            'wide' => ['wide', 'max-w-[380px] @[640px]:max-w-[1440px]'],
            'full' => ['full', 'max-w-none'],
        ];
    }

    #[DataProvider('ukuran')]
    public function test_size_menggeser_batas_lebar_desktop(string $size, string $classes): void
    {
        $this->assertHasClasses($this->find($this->dom("<x-ceplok::container size=\"{$size}\">Isi</x-ceplok::container>"), 'div > div'), $classes);
    }

    public function test_full_tidak_dijepit_lebar_mobile(): void
    {
        $this->assertLacksClasses($this->find($this->dom('<x-ceplok::container size="full">Isi</x-ceplok::container>'), 'div > div'), 'max-w-[380px] rounded-xl');
    }

    public function test_padded_false_mematikan_padding(): void
    {
        $inner = $this->find($this->dom('<x-ceplok::container :padded="false">Isi</x-ceplok::container>'), 'div > div');

        $this->assertLacksClasses($inner, 'px-5 @[640px]:px-8');
    }

    public function test_as_mengganti_tag_luar(): void
    {
        $this->find($this->dom('<x-ceplok::container as="section">Isi</x-ceplok::container>'), 'body > section.\@container > div');
        $this->assertPropError('<x-ceplok::container as="div onclick=alert(1)">Isi</x-ceplok::container>', 'Prop `as` pada <x-ceplok::container> harus nama tag HTML');
    }

    public function test_dark_mode_memasang_data_theme_di_elemen_luar(): void
    {
        $this->assertSame('dark', $this->find($this->dom('<x-ceplok::container dark-mode>Isi</x-ceplok::container>'), 'body > div')->getAttribute('data-theme'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::container size="narrow">Isi</x-ceplok::container>', "Prop `size` pada <x-ceplok::container> harus salah satu dari 'prose', 'default', 'wide', 'full'");
    }
}
