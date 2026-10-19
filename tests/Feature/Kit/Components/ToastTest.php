<?php

namespace Tests\Feature\Kit\Components;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ToastTest extends TestCase
{
    public function test_pesan_satu_baris_rata_tengah(): void
    {
        $toast = $this->find($this->dom('<x-ceplok::toast>Data tersimpan.</x-ceplok::toast>'), 'body > div');

        $this->assertSame('status', $toast->getAttribute('role'));
        $this->assertSame('polite', $toast->getAttribute('aria-live'));
        $this->assertSame('ceplokDismissible', $toast->getAttribute('x-data'));
        $this->assertFalse($toast->hasAttribute('style'));
        $this->assertHasClasses($toast, 'relative w-full max-w-80 rounded-lg p-4 shadow bg-surface transition ease-out animate-ceplok-toast-in');

        $row = $this->find($toast, 'div > div');

        $this->assertHasClasses($row, 'flex gap-3 items-center');
        $this->assertHasClasses($this->find($row, 'span.grid'), 'size-8 shrink-0 place-items-center rounded-lg bg-toast-info-surface text-toast-info-icon');
        $this->assertHasClasses($this->find($row, '.min-w-0 > div'), 'text-sm leading-normal text-content-subtle pr-6');

        $close = $this->find($toast, 'button[aria-label="Tutup notifikasi"]');

        $this->assertHasClasses($close, 'absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 focus-visible:outline-brand');
        $this->assertHasClasses($this->find($close, 'svg'), 'size-3');
    }

    public function test_heading_membuat_isi_jadi_teks_sekunder_dan_rata_atas(): void
    {
        $toast = $this->find($this->dom('<x-ceplok::toast heading="Update tersedia">Muat ulang halaman.</x-ceplok::toast>'), 'div[role="status"]');

        $this->assertHasClasses($this->find($toast, 'div > div'), 'items-start');
        $this->assertHasClasses($this->find($toast, 'p'), 'text-sm leading-normal font-bold text-toast-heading pr-6');
        $this->assertHasClasses($this->find($toast, 'p + div'), 'text-toast-body');
        $this->assertHasClasses($this->find($toast, 'button'), 'top-3');
    }

    public function test_actions_juga_rata_atas(): void
    {
        $toast = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::toast>
                Versi baru tersedia.
                <x-slot:actions><x-ceplok::button size="xs" class="w-full">Perbarui</x-ceplok::button></x-slot:actions>
            </x-ceplok::toast>
            BLADE), 'div[role="status"]');

        $this->assertHasClasses($this->find($toast, 'div > div'), 'items-start');
        $this->assertNotNull($toast->querySelector('.min-w-0 > div.mt-3 > button.w-full'));
    }

    /** @return array<string, array{string, string}> */
    public static function variant(): array
    {
        return [
            'success' => ['success', 'ceplok-o-check'],
            'danger' => ['danger', 'ceplok-o-close'],
            'warning' => ['warning', 'ceplok-o-exclamation-circle'],
            'info' => ['info', 'ceplok-o-info-circle'],
            'purple' => ['purple', 'ceplok-o-info-circle'],
        ];
    }

    #[DataProvider('variant')]
    public function test_hanya_badge_ikon_yang_membawa_warna_variant(string $variant, string $icon): void
    {
        $toast = $this->find($this->dom("<x-ceplok::toast variant=\"{$variant}\">Pesan</x-ceplok::toast>"), 'div[role="status"]');
        $badge = $this->find($toast, 'span.grid');

        $this->assertHasClasses($badge, "bg-toast-{$variant}-surface text-toast-{$variant}-icon");
        $this->assertHasClasses($toast, 'bg-surface');
        $this->assertSame(
            $this->find($this->parse(svg($icon)->toHtml()), 'path')->getAttribute('d'),
            $this->find($badge, 'path')->getAttribute('d'),
        );
    }

    public function test_tanpa_ikon_dan_tanpa_tombol_tutup(): void
    {
        $toast = $this->find($this->dom('<x-ceplok::toast :icon="false" :dismissible="false">Pesan</x-ceplok::toast>'), 'div[role="status"]');

        $this->assertNull($toast->querySelector('span.grid'));
        $this->assertNull($toast->querySelector('button'));
        $this->assertFalse($toast->hasAttribute('x-data'));
        $this->assertLacksClasses($this->find($toast, '.min-w-0 > div'), 'pr-6');
    }

    public function test_open_false_menyembunyikan_sejak_awal(): void
    {
        $toast = $this->find($this->dom('<x-ceplok::toast :open="false">Pesan</x-ceplok::toast>'), 'div[role="status"]');

        $this->assertSame('ceplokDismissible', $toast->getAttribute('x-data'));
        $this->assertSame('display: none;', $toast->getAttribute('style'));
    }

    public function test_dark_mode_memasang_data_theme(): void
    {
        $this->assertSame('dark', $this->find($this->dom('<x-ceplok::toast dark-mode>Pesan</x-ceplok::toast>'), 'div[role="status"]')->getAttribute('data-theme'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::toast variant="error">Pesan</x-ceplok::toast>', "Prop `variant` pada <x-ceplok::toast> harus salah satu dari 'success', 'danger', 'warning', 'info', 'purple'");
    }
}
