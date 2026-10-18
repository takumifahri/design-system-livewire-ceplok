<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class BreadcrumbTest extends TestCase
{
    private const ITEMS = <<<'PHP'
        [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'Components', 'href' => '/components'],
            ['label' => 'Breadcrumb', 'href' => '/components/breadcrumb'],
        ]
        PHP;

    public function test_item_terakhir_halaman_aktif_dan_bukan_tautan(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::breadcrumb :items="'.self::ITEMS.'" />'), 'body > nav');

        $this->assertSame('Breadcrumb', $nav->getAttribute('aria-label'));
        $this->assertCount(3, $nav->querySelectorAll('ol > li'));
        $this->assertCount(2, $nav->querySelectorAll('li > a'));

        $current = $this->find($nav, 'li:last-child > span[aria-current="page"]');

        $this->assertSame('Breadcrumb', trim($current->textContent));
        $this->assertHasClasses($current, 'text-sm font-medium text-breadcrumb-active');
        $this->assertNull($nav->querySelector('a[href="/components/breadcrumb"]'));
    }

    public function test_tautan_dan_pemisah(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::breadcrumb :items="'.self::ITEMS.'" />'), 'nav');

        $link = $this->find($nav, 'a[href="/components"]');

        $this->assertHasClasses($link, 'flex items-center gap-2 transition-colors text-sm text-content-muted hover:text-breadcrumb-link-hover');
        $this->assertSame('Components', trim($link->textContent));

        $separators = $nav->querySelectorAll('li > span[aria-hidden="true"]');

        $this->assertCount(2, $separators);
        $this->assertHasClasses($separators->item(0), 'text-sm text-gray-400');
        $this->assertNotNull($separators->item(0)->querySelector('svg'));
    }

    public function test_item_home_otomatis_berikon_rumah(): void
    {
        $home = $this->find($this->dom('<x-ceplok::breadcrumb :items="'.self::ITEMS.'" />'), 'a[href="/"]');

        $this->assertHasClasses($this->find($home, 'svg'), 'size-4');
        $this->assertSame(
            $this->find($this->parse(svg('ceplok-s-home')->toHtml()), 'path')->getAttribute('d'),
            $this->find($home, 'svg path')->getAttribute('d'),
        );
    }

    public function test_ikon_item_lewat_nama_dan_atribut_tambahan_ke_tautan(): void
    {
        $nav = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::breadcrumb :items="[
                ['label' => 'Profil', 'href' => '/profil', 'icon' => 'ceplok-o-user', 'attributes' => ['wire:navigate' => true, 'data-uji' => 'profil']],
                ['label' => 'Ubah'],
            ]" />
            BLADE), 'nav');

        $link = $this->find($nav, 'a[href="/profil"]');

        $this->assertTrue($link->hasAttribute('wire:navigate'));
        $this->assertSame('profil', $link->getAttribute('data-uji'));
        $this->assertNotNull($link->querySelector('span > svg'));
    }

    public function test_item_tengah_tanpa_href_berupa_teks(): void
    {
        $nav = $this->find($this->dom("<x-ceplok::breadcrumb :items=\"[['label' => 'Arsip'], ['label' => 'Detail']]\" />"), 'nav');

        $middle = $this->find($nav, 'li:first-child > span:not([aria-hidden])');

        $this->assertHasClasses($middle, 'text-content-muted');
        $this->assertFalse($middle->hasAttribute('aria-current'));
    }

    public function test_size_background_dark_mode_dan_pemisah_kustom(): void
    {
        $nav = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::breadcrumb :items="[['label' => 'Docs', 'href' => '/docs'], ['label' => 'Aktif']]" size="sm" background="bg" dark-mode>
                <x-slot:separator>/</x-slot:separator>
            </x-ceplok::breadcrumb>
            BLADE), 'nav');

        $this->assertHasClasses($nav, 'flex items-center rounded-lg bg-breadcrumb-surface px-4 py-3');
        $this->assertSame('dark', $nav->getAttribute('data-theme'));
        $this->assertHasClasses($this->find($nav, 'a'), 'text-xs');
        $this->assertSame('/', trim($this->find($nav, 'li > span[aria-hidden="true"]')->textContent));
    }

    public function test_items_boleh_berupa_collection(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::breadcrumb :items="$items" />', [
            'items' => collect([['label' => 'Home', 'href' => '/'], ['label' => 'Aktif']]),
        ]), 'nav');

        $this->assertCount(2, $nav->querySelectorAll('li'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::breadcrumb size="lg" />', "Prop `size` pada <x-ceplok::breadcrumb> harus salah satu dari 'sm', 'base'");
        $this->assertPropError('<x-ceplok::breadcrumb background="gray" />', "Prop `background` pada <x-ceplok::breadcrumb> harus salah satu dari 'bg', 'none'");
    }
}
