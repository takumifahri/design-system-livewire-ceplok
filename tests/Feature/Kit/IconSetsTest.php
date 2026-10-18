<?php

namespace Tests\Feature\Kit;

use BladeUI\Icons\Factory;
use Tests\TestCase;

/**
 * Set ikon yang didaftarkan kit ke blade-icons: Flowbite (salinan persis dari
 * kit React, lihat scripts/sync-icons.mjs) dan logo brand milik kit.
 */
class IconSetsTest extends TestCase
{
    public function test_kit_mendaftarkan_set_flowbite_dan_brand(): void
    {
        $sets = app(Factory::class)->all();

        $this->assertSame('ceplok', $sets['ceplok-flowbite']['prefix']);
        $this->assertSame('brand', $sets['ceplok-brands']['prefix']);
    }

    public function test_jumlah_ikon_sama_dengan_flowbite_react_icons(): void
    {
        $dir = base_path('vendor/ceplok-ui/design-kit-livewire/resources/svg');

        $this->assertCount(411, glob($dir.'/flowbite/o-*.svg'));
        $this->assertCount(340, glob($dir.'/flowbite/s-*.svg'));
        $this->assertCount(30, glob($dir.'/brands/*.svg'));
    }

    public function test_outline_dan_solid_dibedakan_lewat_awalan(): void
    {
        $outline = svg('ceplok-o-bell')->toHtml();
        $solid = svg('ceplok-s-bell')->toHtml();

        $this->assertNotSame($outline, $solid);
        $this->assertStringContainsString('stroke="currentColor"', $outline);
        $this->assertStringContainsString('fill="currentColor"', $solid);
    }

    public function test_komponen_ikon_menerima_kelas(): void
    {
        $svg = $this->find($this->dom('<x-ceplok-o-home class="size-5 text-gray-500" />'), 'body > svg');

        $this->assertHasClasses($svg, 'size-5 text-gray-500');
        $this->assertSame('0 0 24 24', $svg->getAttribute('viewBox'));
    }

    public function test_logo_brand_selalu_dekoratif(): void
    {
        $svg = $this->find($this->dom('<x-brand-github class="size-5" />'), 'body > svg');

        $this->assertSame('true', $svg->getAttribute('aria-hidden'));
        $this->assertHasClasses($svg, 'size-5');
        $this->assertStringContainsString('aria-hidden="true"', svg('brand-x')->toHtml());
    }

    public function test_ikon_flowbite_tidak_dipaksa_dekoratif(): void
    {
        // Ikon antarmuka bisa saja satu-satunya isi sebuah tombol; yang
        // memutuskan aria-hidden adalah pemakainya.
        $this->assertStringNotContainsString('aria-hidden', svg('ceplok-o-home')->toHtml());
    }
}
