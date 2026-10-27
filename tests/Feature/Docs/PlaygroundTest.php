<?php

namespace Tests\Feature\Docs;

use Dom\HTMLDocument;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Playground dan contoh Livewire di halaman docs (komponen satu berkas di
 * resources/views/components/playground).
 */
class PlaygroundTest extends TestCase
{
    /** @return array<string, array{string}> */
    public static function playground(): array
    {
        $names = [
            'alert', 'alert-notice', 'avatar', 'badge', 'badge-filters', 'breadcrumb', 'button', 'card', 'container',
            'footer', 'hero', 'pagination', 'pagination-list', 'table', 'table-demo', 'popover', 'modal', 'modal-livewire', 'spinner', 'spinner-loading', 'toast', 'toast-saved',
            'dropdown', 'dropdown-actions', 'dropdown-sort',
            'input-field', 'input-field-validate', 'floating-label', 'floating-label-fill', 'text-area', 'text-area-send',
            'select', 'select-region', 'search', 'search-live', 'datepicker', 'datepicker-filter', 'upload', 'upload-livewire',
            'radio', 'radio-livewire', 'toggle', 'toggle-livewire', 'checkbox', 'checkbox-livewire',
            'timeline', 'timeline-livewire', 'table-of-contents',
        ];

        return array_combine($names, array_map(fn (string $name) => ["playground.{$name}"], $names));
    }

    #[DataProvider('playground')]
    public function test_playground_dirender(string $name): void
    {
        $this->assertNotSame('', trim(Livewire::test($name)->html()));
    }

    public function test_daftar_playground_di_tes_ini_lengkap(): void
    {
        $files = array_map(
            fn (string $file) => 'playground.'.str_replace('⚡', '', basename($file, '.blade.php')),
            glob(resource_path('views/components/playground/*.blade.php')),
        );

        $this->assertEqualsCanonicalizing(array_column(self::playground(), 0), $files);
    }

    public function test_button_merender_ulang_tombol_dan_kode_penggunaannya(): void
    {
        $component = Livewire::test('playground.button')
            ->set('variant', 'outline')
            ->set('theme', 'green')
            ->set('size', 'xl');

        $dom = $this->domOf($component);

        $this->assertHasClasses($this->find($dom, 'div.min-h-45 > button'), 'border-green-700 text-green-700 h-[46px] text-xl');
        $this->assertSame('Outline', trim($this->find($dom, '[aria-label="Pilih variant"] [aria-pressed="true"]')->textContent));

        $code = $this->code($dom);

        $this->assertStringContainsString('variant="outline"', $code);
        $this->assertStringContainsString('theme="green"', $code);
        $this->assertStringContainsString('size="xl"', $code);
    }

    public function test_button_icon_only_melepas_ikon_kiri_kanan(): void
    {
        $component = Livewire::test('playground.button')->set('iconOnly', true);

        $component->assertSet('icons', 'none');

        $dom = $this->domOf($component);
        $button = $this->find($dom, 'div.min-h-45 > button');

        $this->assertSame('Kirim pesan', $button->getAttribute('aria-label'));
        $this->assertHasClasses($button, 'rounded-full');
        $this->assertStringContainsString('icon-only', $this->code($dom));
        $this->assertStringNotContainsString('left-icon', $this->code($dom));
    }

    public function test_button_as_anchor_merender_tautan(): void
    {
        $dom = $this->domOf(Livewire::test('playground.button')->set('as', 'anchor'));

        $this->assertSame(route('foundations.colors'), $this->find($dom, 'div.min-h-45 > a')->getAttribute('href'));
    }

    public function test_timeline_menulis_isi_lengkap_item_dan_mengikuti_kontrol(): void
    {
        $component = Livewire::test('playground.timeline')
            ->set('count', 2)
            ->set('lampiran', 'unduh')
            ->set('platform', 'mobile');

        $code = $component->get('usage');

        $this->assertStringContainsString("'role' => 'Role / Department',", $code);
        $this->assertStringContainsString("'time' => '2026-01-01 09:05',", $code);
        $this->assertStringContainsString("'elapsed' => '5 minutes',", $code);
        $this->assertStringContainsString("'details' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',", $code);
        $this->assertStringContainsString("['name' => 'sample-document-02.pdf', 'href' => asset('files/sample-document.pdf'), ⟦'download'⟧ => 'sample-document-02.pdf'],", $code);
        $this->assertStringContainsString("'status' => ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true],", $code);
        $this->assertStringContainsString('⟦platform⟧="mobile"', $code);
        $this->assertSame(2, substr_count($code, "'name' => 'User 0"));
        $this->assertStringNotContainsString('⟦size⟧', $code);

        $dom = $this->domOf($component);
        $links = $dom->querySelectorAll('ol a');

        $this->assertSame('01 Jan 2026 · 09:05', $dom->querySelectorAll('ol time')->item(1)->textContent);
        $this->assertCount(2, $links);
        $this->assertSame('sample-document-02.pdf', $links->item(0)->getAttribute('download'));
        $this->assertSame(asset('files/sample-document.pdf'), $links->item(0)->getAttribute('href'));
    }

    public function test_timeline_menutup_status_lewat_wire_dismiss(): void
    {
        $component = Livewire::test('playground.timeline-livewire');
        $badges = fn () => $this->domOf($component)->querySelectorAll('ol [wire\:dismiss]');

        $this->assertCount(3, $badges());
        $this->assertSame('tutupStatus(1)', $badges()->item(1)->getAttribute('wire:dismiss'));

        $component->call('tutupStatus', 1);

        $this->assertCount(2, $badges());
        $this->assertStringNotContainsString('Terverifikasi', $component->html());
        $this->assertStringContainsString('1 dari 3 status ditutup.', $component->html());

        $component->call('pulihkan');

        $this->assertCount(3, $badges());
    }

    public function test_filter_badge_terikat_wire_model_dan_wire_dismiss(): void
    {
        $component = Livewire::test('playground.badge-filters')
            ->set('filters.aktif', false)
            ->call('removed', 'Status');

        $component->assertSet('last', 'Status')->assertSeeText('terakhir dihapus: Status');

        $dom = $this->domOf($component);

        $this->assertSame('2', trim($this->find($dom, 'strong')->textContent));
        $this->assertSame('display: none;', $this->find($dom, 'span[wire\:model\.live="filters.aktif"]')->getAttribute('style'));
        $this->assertFalse($this->find($dom, 'span[wire\:model\.live="filters.jakarta"]')->hasAttribute('style'));

        $component->call('restore')->assertSet('filters', ['aktif' => true, 'jakarta' => true, 'tahun' => true])->assertSet('last', null);
    }

    public function test_alert_notice_tersembunyi_dan_tombol_tampilkan_lagi_aktif(): void
    {
        $component = Livewire::test('playground.alert-notice');

        $this->assertTrue($this->find($this->domOf($component), 'button[wire\:click]')->hasAttribute('disabled'));

        $component->set('notice', false)->call('dismissed');
        $dom = $this->domOf($component);

        $alert = $this->find($dom, 'div[role="alert"]');

        $this->assertSame('ceplokDismissible', $alert->getAttribute('x-data'));
        $this->assertSame('display: none;', $alert->getAttribute('style'));
        $this->assertFalse($this->find($dom, 'button[wire\:click]')->hasAttribute('disabled'));
        $component->assertSeeText('ditutup 1×');
    }

    public function test_toast_tersimpan_muncul_setelah_aksi_server(): void
    {
        $component = Livewire::test('playground.toast-saved');

        $this->assertSame('display: none;', $this->find($this->domOf($component), 'div[role="status"]')->getAttribute('style'));

        $component->call('save')->assertSet('saved', true)->assertSet('count', 1);
        $toast = $this->find($this->domOf($component), 'div[role="status"]');

        $this->assertSame('ceplokDismissible', $toast->getAttribute('x-data'));
        $this->assertFalse($toast->hasAttribute('style'));
        $this->assertStringContainsString('Data #1 berhasil disimpan!', $toast->textContent);
    }

    public function test_pagination_menjaga_halaman_aktif_dalam_batas(): void
    {
        Livewire::test('playground.pagination')
            ->set('current', 50)->assertSet('current', 50)
            ->set('total', 20)->assertSet('current', 20)
            ->set('current', 0)->assertSet('current', 1)
            ->set('total', 0)->assertSet('total', 1);
    }

    public function test_daftar_pengajuan_membaca_dan_menjepit_halaman_dari_alamat(): void
    {
        $component = Livewire::withQueryParams(['halaman' => 3])->test('playground.pagination-list');

        $component->assertSet('page', 3)->assertSeeText('Pengajuan #011')->assertDontSeeText('Pengajuan #010');

        $component->set('page', 99)->assertSet('page', 9)->assertSeeText('Halaman 9 dari 9 · 42 pengajuan');

        $rows = $this->domOf($component)->querySelectorAll('ul > li');

        $this->assertCount(2, $rows);
        $this->assertStringContainsString('Pengajuan #042', $rows->item(1)->textContent);
    }

    public function test_container_menjepit_lebar_ruang_kustom(): void
    {
        Livewire::test('playground.container')
            ->set('width', 100)->assertSet('width', 320)
            ->set('width', 5000)->assertSet('width', 1600);
    }

    private function domOf(Testable $component): HTMLDocument
    {
        return $this->parse($component->html());
    }

    /** Teks kode pada blok Penggunaan, tanpa markup sorotannya. */
    private function code(HTMLDocument $dom): string
    {
        return $this->find($dom, 'pre > code')->textContent;
    }
}
