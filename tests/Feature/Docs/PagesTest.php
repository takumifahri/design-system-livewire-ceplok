<?php

namespace Tests\Feature\Docs;

use Dom\HTMLDocument;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PagesTest extends TestCase
{
    /** @return array<string, array{string, string, string}> */
    public static function halaman(): array
    {
        return [
            'home' => ['home', 'Home', 'Menyatukan layanan digital lewat desain dan kode'],
            'components' => ['components.index', 'Components', 'Components'],
            'container' => ['components.container', 'Container', 'Container'],
            'button' => ['components.button', 'Button', 'Button'],
            'badge' => ['components.badge', 'Badge', 'Badge'],
            'avatar' => ['components.avatar', 'Avatar', 'Avatar'],
            'spinner' => ['components.spinner', 'Spinner', 'Spinner'],
            'popover' => ['components.popover', 'Popover', 'Popover'],
            'dropdown' => ['components.dropdown', 'Dropdown', 'Dropdown'],
            'modal' => ['components.modal', 'Modal', 'Modal'],
            'alert' => ['components.alert', 'Alert', 'Alert'],
            'toast' => ['components.toast', 'Toast', 'Toast'],
            'card' => ['components.card', 'Card', 'Card'],
            'hero' => ['components.hero', 'Hero', 'Hero'],
            'footer' => ['components.footer', 'Footer', 'Footer'],
            'breadcrumb' => ['components.breadcrumb', 'Breadcrumb', 'Breadcrumb'],
            'pagination' => ['components.pagination', 'Pagination', 'Pagination'],
            'table' => ['components.table', 'Table', 'Table'],
            'table-of-contents' => ['components.table-of-contents', 'Table of Contents', 'Table of Contents'],
            'timeline' => ['components.timeline', 'Timeline View', 'Timeline View'],
            'form' => ['form.index', 'Form', 'Form'],
            'input-field-form' => ['form.input-field', 'Input Field Form', 'Input Field Form'],
            'input-field' => ['form.input-field.input', 'Input Field', 'Input Field'],
            'floating-label' => ['form.input-field.floating-label', 'Floating Label', 'Floating Label'],
            'text-area' => ['form.input-field.text-area', 'Text Area', 'Text Area'],
            'select' => ['form.select', 'Regular Select Form', 'Regular Select Form'],
            'search' => ['form.search', 'Search Form', 'Search Form'],
            'datepicker' => ['form.datepicker', 'Datepicker', 'Datepicker'],
            'upload' => ['form.upload', 'Upload Form', 'Upload Form'],
            'radio' => ['form.radio', 'Radio Button', 'Radio Button'],
            'toggle' => ['form.toggle', 'Toggle Button', 'Toggle Button'],
            'checkbox' => ['form.checkbox', 'Checkbox', 'Checkbox'],
            'foundations' => ['foundations.index', 'Foundations', 'Foundations'],
            'colors' => ['foundations.colors', 'Colors', 'Color tokens'],
            'typography' => ['foundations.typography', 'Typography', 'Lato type system'],
            'spacing' => ['foundations.spacing', 'Spacing', 'Skala spacing basis 4px'],
            'border' => ['foundations.border', 'Border', 'Radius, ketebalan, dan gaya garis'],
            'elevation' => ['foundations.elevation', 'Elevation', 'Bayangan dan kedalaman'],
            'icons' => ['foundations.icons', 'Icons', 'Icons'],
        ];
    }

    /**
     * Halaman yang sama, hanya nama rutenya. PHPUnit 12 memperingatkan bila
     * provider mengirim lebih banyak argumen daripada parameter tesnya.
     *
     * @return array<string, array{string}>
     */
    public static function rute(): array
    {
        return array_map(fn (array $halaman) => [$halaman[0]], self::halaman());
    }

    #[DataProvider('halaman')]
    public function test_halaman_dirender_dengan_judul_dan_heading_utama(string $route, string $title, string $heading): void
    {
        $dom = $this->page(route($route));
        $h1 = $this->find($dom, 'h1');

        $this->assertSame("{$title} — Ceplok Design Kit", $this->find($dom, 'title')->textContent);
        // Judul Home dipecah per kata untuk animasinya; teks utuhnya ada di aria-label.
        $this->assertSame($heading, $h1->getAttribute('aria-label') ?: trim($h1->textContent));
    }

    #[DataProvider('rute')]
    public function test_navigasi_menandai_halaman_yang_sedang_dibuka(string $route): void
    {
        $dom = $this->page(route($route));
        $url = route($route);
        $area = explode('.', $route)[0];
        $areaUrl = route($area === 'home' ? 'home' : "{$area}.index");

        // Rail desktop: tepat satu area aktif, dan semua tautannya lewat wire:navigate.
        $rail = $this->find($dom, 'nav[aria-label="Area utama"]');

        $this->assertCount(1, $rail->querySelectorAll('a[aria-current="page"]'));
        $this->assertSame($areaUrl, $this->find($rail, 'a[aria-current="page"]')->getAttribute('href'));
        $this->assertSame($rail->querySelectorAll('a')->length, $rail->querySelectorAll('a[wire\:navigate]')->length);

        // Panel samping desktop (tidak ada di Home): tepat satu halaman aktif.
        if ($area !== 'home') {
            $panel = $this->find($dom, 'aside[x-bind\:inert] nav');

            $this->assertCount(1, $panel->querySelectorAll('a[aria-current="page"]'));
            $this->assertSame($url, $this->find($panel, 'a[aria-current="page"]')->getAttribute('href'));
        }
    }

    #[DataProvider('rute')]
    public function test_setiap_id_di_halaman_unik(string $route): void
    {
        // label for, aria-controls, dan popovertarget menunjuk lewat id: id kembar membuat label
        // menyalakan kontrol lain dan panel yang salah terbuka.
        $ids = array_map(fn ($el) => $el->getAttribute('id'), iterator_to_array($this->page(route($route))->querySelectorAll('[id]'), false));

        $this->assertSame([], array_values(array_unique(array_diff_assoc($ids, array_unique($ids)))));
    }

    public function test_sub_halaman_ditarik_masuk_di_bawah_induknya(): void
    {
        $dom = $this->page(route('form.input-field.text-area'));
        $panel = $this->find($dom, 'aside[x-bind\:inert] nav');
        $children = $this->find($panel, 'div.ml-5');

        $this->assertSame(['Input Field', 'Floating Label', 'Text Area'], array_map(fn ($a) => trim($a->textContent), iterator_to_array($children->querySelectorAll('a'), false)));
        $this->assertSame(route('form.input-field.text-area'), $this->find($children, 'a[aria-current="page"]')->getAttribute('href'));
        $this->assertHasClasses($this->find($children, 'a[aria-current="page"]'), 'ds-nav-link w-full py-2 text-[13px] is-active');
        // Induknya tidak ikut aktif.
        $this->assertFalse($this->find($panel, 'a[href="'.route('form.input-field').'"]')->hasAttribute('aria-current'));
    }

    public function test_layout_memuat_pencarian_dan_aset(): void
    {
        $dom = $this->page(route('components.button'));

        $search = $this->find($dom, 'dialog[closedby="any"]');

        $this->assertStringStartsWith('docsSearch(', $search->getAttribute('x-data'));
        $this->assertNotNull($dom->querySelector('link[rel="icon"]'));
        $this->assertNotNull($dom->querySelector('script[src*="/build/assets/app-"], script[type="module"]'));
        $this->assertNotNull($dom->querySelector('footer'));
    }

    public function test_panel_samping_mengikuti_cookie(): void
    {
        $open = $this->find($this->page(route('components.button')), 'aside[x-bind\:inert]');

        $this->assertHasClasses($open, 'w-62 border-r border-border');
        $this->assertFalse($open->hasAttribute('inert'));

        $closed = $this->find($this->page(route('components.button'), ['ceplok_docs_panel' => '0']), 'aside[x-bind\:inert]');

        $this->assertHasClasses($closed, 'w-0');
        $this->assertTrue($closed->hasAttribute('inert'));
    }

    public function test_typography_membaca_pilihan_font_dari_alamat(): void
    {
        $h1 = $this->find($this->page(route('foundations.typography', ['font' => 'inter'])), 'h1');

        $this->assertSame('Inter type system', trim($h1->textContent));
    }

    public function test_demo_mode_tautan_pagination_membaca_halaman_dari_alamat(): void
    {
        $dom = $this->page(route('components.pagination', ['hal' => 7]));

        $this->assertNotNull($dom->querySelector('a[aria-current="page"][href*="hal=7"]'));
    }

    /** @param  array<string, string>  $cookies */
    private function page(string $url, array $cookies = []): HTMLDocument
    {
        foreach ($cookies as $name => $value) {
            // Cookie panel sengaja tidak dienkripsi (lihat bootstrap/app.php) karena ditulis JavaScript.
            $this->withUnencryptedCookie($name, $value);
        }

        $response = $this->get($url);

        $response->assertOk();

        return $this->parse($response->getContent());
    }
}
