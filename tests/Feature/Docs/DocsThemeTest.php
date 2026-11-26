<?php

namespace Tests\Feature\Docs;

use App\Docs\Theme;
use Dom\HTMLDocument;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Mode gelap situs dokumentasi (App\Docs\Theme): pilihan pembaca di cookie,
 * berlaku hanya di halaman yang sudah punya tampilan gelap, dan membawa
 * pratinjau, cuplikan kode, bagian Dark mode, serta Playground ikut gelap.
 */
class DocsThemeTest extends TestCase
{
    /** @return array<string, array{string}> */
    public static function halamanGelap(): array
    {
        $routes = array_values(array_diff(Theme::pages(), ['portal.app']));

        return array_combine($routes, array_map(fn (string $route) => [$route], $routes));
    }

    public function test_semua_halaman_mode_gelap_punya_rute(): void
    {
        foreach (Theme::pages() as $route) {
            $this->assertTrue(Route::has($route), "Rute {$route} tidak ada.");
        }
    }

    public function test_tanpa_cookie_situs_terang_dan_tombolnya_siap_dipakai(): void
    {
        $dom = $this->page(route('form.search'));

        $this->assertFalse($this->find($dom, 'html')->hasAttribute('data-docs-theme'));

        $toggles = $dom->querySelectorAll('button[aria-label="Mode gelap"]');
        $this->assertCount(2, $toggles, 'Tombol mode gelap ada di rail dan di top bar mobile.');

        foreach ($toggles as $toggle) {
            $this->assertSame('false', $toggle->getAttribute('aria-pressed'));
            $this->assertFalse($toggle->hasAttribute('aria-disabled'));
            $this->assertSame('$docsTheme(true)', $toggle->getAttribute('x-on:click'));
            $this->assertSame('Nyalakan mode gelap', $toggle->getAttribute('title'));
        }
    }

    #[DataProvider('halamanGelap')]
    public function test_cookie_gelap_menggelapkan_halaman_yang_sudah_mendukung(string $route): void
    {
        $dom = $this->page(route($route), dark: true);

        $this->assertSame('dark', $this->find($dom, 'html')->getAttribute('data-docs-theme'));

        foreach ($dom->querySelectorAll('button[aria-label="Mode gelap"]') as $toggle) {
            $this->assertSame('true', $toggle->getAttribute('aria-pressed'));
            $this->assertSame('$docsTheme(false)', $toggle->getAttribute('x-on:click'));
        }
    }

    public function test_halaman_tanpa_tampilan_gelap_tetap_terang_dan_tombolnya_diredupkan(): void
    {
        $dom = $this->page(route('components.badge'), dark: true);

        $this->assertFalse($this->find($dom, 'html')->hasAttribute('data-docs-theme'));

        foreach ($dom->querySelectorAll('button[aria-label="Mode gelap"]') as $toggle) {
            $this->assertSame('false', $toggle->getAttribute('aria-pressed'));
            $this->assertSame('true', $toggle->getAttribute('aria-disabled'));
            $this->assertFalse($toggle->hasAttribute('x-on:click'));
            $this->assertSame('Mode gelap belum tersedia di halaman ini', $toggle->getAttribute('title'));
            $this->assertHasClasses($toggle, 'cursor-not-allowed opacity-40');
        }
    }

    public function test_pratinjau_dan_cuplikan_mengikuti_mode_docs(): void
    {
        $terang = $this->find($this->page(route('form.search')), '#search-form');
        $gelap = $this->find($this->page(route('form.search'), dark: true), '#search-form');

        // Search ber-dark-mode memasang data-theme="dark" di akarnya sendiri.
        $this->assertNull($terang->querySelector('[data-theme="dark"]'));
        $this->assertNotNull($gelap->querySelector('[data-theme="dark"]'));

        $this->assertStringNotContainsString('dark-mode', $this->find($terang, 'pre code')->textContent);
        $this->assertStringContainsString("    dark-mode\n/>", $this->find($gelap, 'pre code')->textContent);
    }

    /**
     * Halaman komponen yang pratinjaunya komponen kit ber-`dark-mode` (bukan
     * tiruan statis seperti Drawer) — padanan halaman ber-docsDark di docs React.
     * Tiap baris: rute, penanda pratinjau gelap, dan apakah cuplikannya ikut
     * menulis dark-mode. Dropdown dan Pagination belum berbasis token, jadi
     * penandanya kelas gelapnya, bukan data-theme. Cuplikan Dropdown dan
     * Popover tidak ditandai, sama seperti docs React.
     *
     * @return array<string, array{string, string, bool}>
     */
    public static function halamanKomponenGelap(): array
    {
        $data = [
            ['components.avatar', '[data-theme="dark"]', true],
            ['components.breadcrumb', '[data-theme="dark"]', true],
            ['components.card', '[data-theme="dark"]', true],
            ['components.clipboard', '[data-theme="dark"]', true],
            ['components.container', '[data-theme="dark"]', true],
            ['components.dropdown', '[data-theme="dark"]', false],
            ['components.list-view', '[data-theme="dark"]', true],
            ['components.pagination', '.border-gray-600.bg-gray-700', true],
            ['components.popover', '[data-theme="dark"]', false],
            ['components.sidebar', '[data-theme="dark"]', true],
            ['components.table-of-contents', '[data-theme="dark"]', true],
        ];

        return array_combine(array_column($data, 0), $data);
    }

    #[DataProvider('halamanKomponenGelap')]
    public function test_pratinjau_halaman_komponen_mengikuti_mode_docs(string $route, string $penanda, bool $cuplikan): void
    {
        // Di luar bagian Dark mode, pratinjau baru gelap bila docs gelap, dan cuplikannya ikut menulis dark-mode.
        // Kode Penggunaan mengikuti mode Playground, yang memang dimulai dari mode docs, jadi ikut dilewati.
        $diLuar = fn (HTMLDocument $dom, string $selector) => array_values(array_filter(
            iterator_to_array($dom->querySelectorAll($selector)),
            fn ($el) => $el->closest('#dark-mode, #penggunaan') === null,
        ));

        $terang = $this->page(route($route));
        $gelap = $this->page(route($route), dark: true);

        $this->assertSame([], $diLuar($terang, $penanda), "{$route}: pratinjau gelap di docs terang.");
        $this->assertNotEmpty($diLuar($gelap, $penanda), "{$route}: pratinjau tidak ikut docs gelap.");
        // Panggung ikut gelap: pratinjau gelap di panggung terang membuat trigger putih atau teks putih hilang.
        $this->assertSame([], $diLuar($gelap, '[data-theme="light"].bg-surface-subtle'), "{$route}: panggung tetap terang di docs gelap.");

        $teks = fn (HTMLDocument $dom) => implode("\n", array_map(fn ($el) => $el->textContent, $diLuar($dom, 'pre code')));
        if ($cuplikan) {
            $this->assertStringContainsString('dark-mode', $teks($gelap), "{$route}: cuplikan tidak menulis dark-mode di docs gelap.");
        } else {
            $this->assertSame($teks($terang), $teks($gelap), "{$route}: cuplikan berubah mengikuti mode docs.");
        }
    }

    /**
     * Halaman gelap yang punya bagian Dark mode (<x-docs.mode-section>).
     *
     * @return array<string, array{string}>
     */
    public static function halamanBagianDarkMode(): array
    {
        $routes = [
            'components.alert', 'components.avatar', 'components.breadcrumb', 'components.card',
            'components.clipboard', 'components.container', 'components.drawer', 'components.dropdown',
            'components.list-view', 'components.pagination', 'components.popover', 'components.sidebar',
            'components.table-of-contents', 'components.timeline', 'components.toast',
            'form.input-field.input', 'form.input-field.floating-label', 'form.input-field.text-area',
            'form.select', 'form.search', 'form.datepicker', 'form.upload', 'form.radio', 'form.toggle', 'form.checkbox',
        ];

        return array_combine($routes, array_map(fn (string $route) => [$route], $routes));
    }

    #[DataProvider('halamanBagianDarkMode')]
    public function test_bagian_dark_mode_setiap_halaman_tetap_gelap(string $route): void
    {
        $this->assertContains($route, Theme::pages());

        foreach ([false, true] as $dark) {
            $dom = $this->page(route($route), dark: $dark);
            $bagian = $this->find($dom, '#dark-mode');

            $this->assertStringStartsWith('Dark mode', trim($this->find($bagian, 'h2')->textContent), "{$route}: judul bagian Dark mode.");
            $this->assertSame('Dark mode', trim($this->find($dom, 'nav[aria-label="Daftar isi halaman"] a[href="#dark-mode"]')->textContent), "{$route}: daftar isi.");
            $this->assertNotNull($bagian->querySelector('.bg-gray-900, .bg-gray-950, [data-theme="dark"]'), "{$route}: bagian Dark mode tidak memperagakan tampilan gelap.");
        }
    }

    public function test_bagian_dark_mode_tetap_gelap_di_kedua_mode_docs(): void
    {
        foreach ([false, true] as $dark) {
            $dom = $this->page(route('form.search'), dark: $dark);
            $bagian = $this->find($dom, '#dark-mode');

            $this->assertStringStartsWith('Dark mode', trim($this->find($bagian, 'h2')->textContent));
            $this->assertSame('Dark mode', trim($this->find($dom, 'nav[aria-label="Daftar isi halaman"] a[href="#dark-mode"]')->textContent));
            $this->assertStringContainsString('<x-ceplok::search dark-mode placeholder=', $this->find($bagian, 'pre code')->textContent);
            $this->assertNotNull($bagian->querySelector('[data-theme="dark"]'));
            $this->assertNull($bagian->querySelector('[data-theme="light"]'));
        }
    }

    public function test_playground_mulai_dari_mode_docs(): void
    {
        $terang = $this->page(route('components.alert'));
        $gelap = $this->page(route('components.alert'), dark: true);

        $this->assertSame('Light', trim($this->find($terang, '[aria-label="Pilih tampilan"] [aria-pressed="true"]')->textContent));
        $this->assertSame('Dark', trim($this->find($gelap, '[aria-label="Pilih tampilan"] [aria-pressed="true"]')->textContent));
        // Kode Penggunaannya ikut menuliskan dark-mode.
        $this->assertStringNotContainsString('dark-mode', $this->find($terang, '#penggunaan pre code')->textContent);
        $this->assertStringContainsString('dark-mode', $this->find($gelap, '#penggunaan pre code')->textContent);
    }

    public function test_button_docs_selalu_memperagakan_bagian_dark_mode(): void
    {
        $terang = $this->page(route('components.button'));
        $gelap = $this->page(route('components.button'), dark: true);

        foreach ([$terang, $gelap] as $dom) {
            $section = $this->find($dom, '#dark-mode');

            $this->assertStringStartsWith('Dark mode', trim($this->find($section, 'h2')->textContent));
            $buttons = $section->querySelectorAll('div.grid > div > div > button[class~="inline-flex"]');
            $this->assertCount(4, $buttons);
            $this->assertHasClasses($buttons->item(0), 'bg-primary-800 text-white');
            $this->assertHasClasses($buttons->item(1), 'bg-gray-50 text-gray-700');
            $this->assertHasClasses($buttons->item(3), 'border-gray-50 text-gray-50');
            $this->assertStringContainsString('dark-mode', $this->find($section, 'pre code')->textContent);
        }

        $this->assertSame('Dark mode', trim($this->find($gelap, 'nav[aria-label="Daftar isi halaman"] a[href="#dark-mode"]')->textContent));
        $this->assertSame('Dark', trim($this->find($gelap, '#playground [aria-label="Pilih tampilan"] [aria-pressed="true"]')->textContent));
    }

    public function test_breadcrumb_dan_sidebar_mengikuti_mode_docs(): void
    {
        $breadcrumb = $this->page(route('components.breadcrumb'), dark: true);
        $sidebar = $this->page(route('components.sidebar'), dark: true);

        $this->assertNotNull($this->find($breadcrumb, '#sizes')->querySelector('nav[data-theme="dark"]'));
        $this->assertStringStartsWith('Dark mode', trim($this->find($breadcrumb, '#dark-mode h2')->textContent));
        $this->assertSame('Dark mode', trim($this->find($breadcrumb, 'nav[aria-label="Daftar isi halaman"] a[href="#dark-mode"]')->textContent));
        $this->assertNotNull($this->find($breadcrumb, '#dark-mode')->querySelector('[data-theme="dark"]'));
        $this->assertSame('Dark', trim($this->find($breadcrumb, '#playground [aria-label="Pilih tampilan"] [aria-pressed="true"]')->textContent));

        $this->assertNotNull($this->find($sidebar, '#variants')->querySelector('aside[data-theme="dark"]'));
        $this->assertStringStartsWith('Dark mode', trim($this->find($sidebar, '#dark-mode h2')->textContent));
        $this->assertSame('Dark mode', trim($this->find($sidebar, 'nav[aria-label="Daftar isi halaman"] a[href="#dark-mode"]')->textContent));
        $this->assertNotNull($this->find($sidebar, '#dark-mode')->querySelector('[data-theme="dark"]'));
        $this->assertSame('Dark', trim($this->find($sidebar, '#playground [aria-label="Pilih tampilan"] [aria-pressed="true"]')->textContent));
    }

    public function test_portal_dashboard_mengikuti_mode_docs_dan_mengabarkan_tombol_temanya(): void
    {
        $dom = $this->page(route('portal.app'), dark: true);

        $this->assertSame('dark', $this->find($dom, 'html')->getAttribute('data-docs-theme'));
        $this->assertSame('$docsTheme($event.detail.darkMode)', $this->find($dom, 'body')->getAttribute('x-on:dark-mode-change'));
        $this->assertSame('dark', $this->find($dom, 'main')->closest('[data-theme]')->getAttribute('data-theme'));

        // Seperti docs React, tombol tema layar sempit ditempel di baris atas header
        // (di luar <header>, dalam pembungkus sticky) dan disembunyikan mulai 576px.
        $toggle = $this->find($dom, 'div.sticky > button[aria-label="Mode gelap"]');

        $this->assertSame('true', $toggle->getAttribute('aria-pressed'));
        $this->assertSame('portalTema(true)', $toggle->getAttribute('x-data'));
        $this->assertSame('pilih(! gelap)', $toggle->getAttribute('x-on:click'));
        $this->assertHasClasses($toggle, 'absolute top-4.5 right-29 min-[576px]:hidden');

        // Pilihan baru digeser dulu di browser, lalu dikabarkan ke bingkai docs.
        $this->assertStringContainsString(
            "this.\$dispatch('dark-mode-change', { darkMode: gelap })",
            file_get_contents(resource_path('js/docs/portal-tema.js')),
        );
    }

    public function test_toggle_tema_portal_dashboard_di_kaki_sidebar(): void
    {
        $dom = $this->page(route('portal.app'), dark: true);
        [$sidebar, $sidebarSeluler] = iterator_to_array($dom->querySelectorAll('aside[x-data="ceplokSidebar"]'));

        $grup = $this->find($sidebar, '[role="group"][aria-label="Pilih tema"]');
        $this->assertSame('portalTema(true)', $grup->getAttribute('x-data'));

        // Sorotan bergeser ke Gelap; Terang dan Gelap masing-masing mengabarkan pilihannya.
        $this->assertHasClasses($this->find($grup, 'span[aria-hidden="true"]'), 'translate-x-full');
        $pilihan = [];
        foreach ($grup->querySelectorAll('button') as $tombol) {
            $pilihan[trim($tombol->textContent)] = [$tombol->getAttribute('aria-pressed'), $tombol->getAttribute('x-on:click')];
        }
        $this->assertSame(['Terang' => ['false', 'pilih(false)'], 'Gelap' => ['true', 'pilih(true)']], $pilihan);

        // Sidebar ringkas memakai tombol berikon; keduanya bertukar mengikuti `collapsed` Sidebar.
        $this->assertSame('! collapsed', $grup->parentElement->getAttribute('x-show'));
        $ringkas = $this->find($sidebar, 'button[aria-label="Mode gelap"]');
        $this->assertSame('collapsed', $ringkas->parentElement->getAttribute('x-show'));
        $this->assertSame('display: none;', $ringkas->parentElement->getAttribute('style'));

        // Sidebar di Drawer layar sempit juga membawa pilihan tema (beda dari docs React),
        // supaya tema tetap bisa diganti di lebar 576px–lg. Sidebar ini tidak pernah
        // diringkas, jadi tanpa tombol berikon.
        $this->assertSame('portalTema(true)', $this->find($sidebarSeluler, '[role="group"][aria-label="Pilih tema"]')->getAttribute('x-data'));
        $this->assertNull($sidebarSeluler->querySelector('button[aria-label="Mode gelap"]'));
    }

    private function page(string $url, bool $dark = false): HTMLDocument
    {
        if ($dark) {
            // Cookie mode gelap sengaja tidak dienkripsi (lihat bootstrap/app.php) karena ditulis JavaScript.
            $this->withUnencryptedCookie(Theme::COOKIE, 'dark');
        }

        $response = $this->get($url);

        $response->assertOk();

        return $this->parse($response->getContent());
    }
}
