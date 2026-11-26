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
        $this->assertCount(3, $this->find($nav, 'ol:not([aria-hidden])')->querySelectorAll('li:not([x-show="collapsed"])'));
        $this->assertCount(2, $nav->querySelectorAll('li > a'));

        $current = $this->find($nav, 'li:last-child > span[aria-current="page"]');

        $this->assertSame('Breadcrumb', trim($this->find($current, '[class="lg:hidden"]')->textContent));
        $this->assertHasClasses($current, 'text-sm font-medium text-breadcrumb-active');
        $this->assertNull($nav->querySelector('a[href="/components/breadcrumb"]'));
    }

    public function test_tautan_dan_pemisah(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::breadcrumb :items="'.self::ITEMS.'" />'), 'nav');

        $link = $this->find($nav, 'a[href="/components"]');

        $this->assertHasClasses($link, 'flex items-center gap-2 whitespace-nowrap transition-colors text-sm text-content-muted hover:text-breadcrumb-link-hover');
        $this->assertSame('Components', trim($this->find($link, '[class="lg:hidden"]')->textContent));

        $separators = $this->find($nav, 'ol:not([aria-hidden])')->querySelectorAll('li:not([x-show="collapsed"]) > span[aria-hidden="true"]');

        $this->assertCount(2, $separators);
        $this->assertHasClasses($separators->item(0), 'text-sm shrink-0 text-gray-400');
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

    public function test_layout_tidak_patah_dan_item_non_tautan_sejajar_dengan_ikon(): void
    {
        $nav = $this->find($this->dom("<x-ceplok::breadcrumb :items=\"[['label' => 'Home'], ['label' => 'Aktif']]\" />"), 'nav');

        $this->assertHasClasses($nav, 'relative flex min-w-0 max-w-full items-center');
        $this->assertHasClasses($this->find($nav, 'ol'), 'flex min-w-0 max-w-full items-center gap-0 overflow-hidden');
        $this->assertHasClasses($this->find($nav, 'li'), 'flex shrink-0 items-center gap-0');

        $home = $this->find($nav, 'li:first-child > span:not([aria-hidden])');
        $this->assertHasClasses($home, 'flex items-center gap-2 whitespace-nowrap text-content-muted');
        $this->assertNotNull($home->querySelector('span > svg'));
    }

    public function test_label_dipotong_12_karakter_di_mobile_dan_17_di_desktop(): void
    {
        $nav = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::breadcrumb :items="[
                ['label' => 'Administrasi Kependudukan Daerah', 'href' => '/adm'],
                ['label' => 'Dokumen Pengajuan Lengkap'],
            ]" />
            BLADE), 'nav');

        $link = $this->find($nav, 'a[href="/adm"]');
        $this->assertSame('Administrasi…', trim($this->find($link, '[class="lg:hidden"]')->textContent));
        $this->assertSame('Administrasi Kepe…', trim($this->find($link, '[class="hidden lg:inline"]')->textContent));
        $this->assertSame('Administrasi Kependudukan Daerah', $link->getAttribute('aria-label'));
        $this->assertSame('Administrasi Kependudukan Daerah', $link->getAttribute('title'));

        $current = $this->find($nav, 'span[aria-current="page"]');
        $this->assertSame('Dokumen Peng…', trim($this->find($current, '[class="lg:hidden"]')->textContent));
        $this->assertSame('Dokumen Pengajuan Lengkap', $current->getAttribute('title'));

        // Label pendek tidak diberi title/aria-label tambahan.
        $short = $this->find($this->dom('<x-ceplok::breadcrumb :items="'.self::ITEMS.'" />'), 'a[href="/components"]');
        $this->assertFalse($short->hasAttribute('aria-label'));
        $this->assertFalse($short->hasAttribute('title'));
    }

    public function test_level_tengah_dapat_diringkas_menjadi_elipsis(): void
    {
        $nav = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::breadcrumb :items="[
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Menu 1', 'href' => '/1'],
                ['label' => 'Menu 2', 'href' => '/2'],
                ['label' => 'Menu 3'],
            ]" />
            BLADE), 'nav');

        $this->assertSame('ceplokBreadcrumb', $nav->getAttribute('x-data'));

        $items = $this->childrenOf($this->find($nav, 'ol:not([aria-hidden])'));
        $this->assertCount(5, $items);
        $this->assertFalse($items[0]->hasAttribute('x-show'));
        $this->assertSame('collapsed', $items[1]->getAttribute('x-show'));
        $this->assertSame('display: none;', $items[1]->getAttribute('style'));
        $this->assertSame('! collapsed', $items[2]->getAttribute('x-show'));
        $this->assertSame('! collapsed', $items[3]->getAttribute('x-show'));
        $this->assertFalse($items[4]->hasAttribute('x-show'));

        $ellipsis = $this->find($items[1], 'span:not([aria-hidden])');
        $this->assertSame('…', trim($ellipsis->textContent));
        $this->assertSame('Menu tersembunyi: Menu 1 / Menu 2', $ellipsis->getAttribute('aria-label'));
        $this->assertSame('Menu 1 / Menu 2', $ellipsis->getAttribute('title'));
        $this->assertNotNull($items[1]->querySelector('span[aria-hidden="true"] svg'));

        // Pengukur lebar penuh berisi semua item, tersembunyi dari pembaca layar.
        $measure = $this->find($nav, 'ol[x-ref="measure"]');
        $this->assertSame('true', $measure->getAttribute('aria-hidden'));
        $this->assertHasClasses($measure, 'pointer-events-none invisible fixed w-max whitespace-nowrap');
        $this->assertCount(4, $this->childrenOf($measure));

        // Dua item tidak bisa diringkas, jadi tanpa Alpine dan tanpa pengukur.
        $pendek = $this->find($this->dom("<x-ceplok::breadcrumb :items=\"[['label' => 'Home', 'href' => '/'], ['label' => 'Aktif']]\" />"), 'nav');
        $this->assertFalse($pendek->hasAttribute('x-data'));
        $this->assertNull($pendek->querySelector('ol[x-ref="measure"]'));
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
