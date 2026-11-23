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
        $dom = $this->page(route('components.button'), dark: true);

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

    public function test_portal_dashboard_mengikuti_mode_docs_dan_mengabarkan_tombol_temanya(): void
    {
        $dom = $this->page(route('portal.app'), dark: true);

        $this->assertSame('dark', $this->find($dom, 'html')->getAttribute('data-docs-theme'));
        $this->assertSame('$docsTheme($event.detail.darkMode)', $this->find($dom, 'body')->getAttribute('x-on:dark-mode-change'));
        $this->assertSame('dark', $this->find($dom, 'main')->closest('[data-theme]')->getAttribute('data-theme'));

        $toggle = $this->find($dom, 'header button[aria-label="Mode gelap"]');

        $this->assertSame('true', $toggle->getAttribute('aria-pressed'));
        $this->assertSame("\$dispatch('dark-mode-change', { darkMode: false })", $toggle->getAttribute('x-on:click'));
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
