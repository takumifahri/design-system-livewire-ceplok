<?php

namespace Tests\Feature\Kit\Components;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AlertTest extends TestCase
{
    public function test_bawaannya_info_dengan_ikon_dan_tombol_tutup(): void
    {
        $alert = $this->find($this->dom('<x-ceplok::alert heading="Info">Isi pesan.</x-ceplok::alert>'), 'body > div');

        $this->assertSame('alert', $alert->getAttribute('role'));
        $this->assertSame('ceplokDismissible', $alert->getAttribute('x-data'));
        $this->assertFalse($alert->hasAttribute('style'));
        $this->assertSame('open', $alert->getAttribute('x-modelable'));
        $this->assertHasClasses($alert, 'rounded-lg p-4 sm:p-5 transition-opacity duration-200 opacity-100 bg-alert-info-surface animate-ceplok-fade-in');

        $icon = $this->find($alert, 'span[aria-hidden="true"]');

        $this->assertHasClasses($icon, 'inline-flex size-5 mt-0.5 shrink-0 text-alert-info-icon');
        $this->assertSame($this->iconPath('ceplok-o-info-circle'), $this->find($icon, 'path')->getAttribute('d'));

        $close = $this->find($alert, 'button[aria-label="Tutup peringatan"]');

        $this->assertSame('close()', $close->getAttribute('x-on:click'));
        $this->assertHasClasses($close, 'text-alert-info-heading');
        $this->assertHasClasses($this->find($close, 'svg'), 'size-3.5 text-alert-info-close');
    }

    /** @return array<string, array{string, string}> */
    public static function variant(): array
    {
        return [
            'success' => ['success', 'ceplok-o-check-circle'],
            'danger' => ['danger', 'ceplok-o-close-circle'],
            'warning' => ['warning', 'ceplok-o-exclamation-circle'],
            'info' => ['info', 'ceplok-o-info-circle'],
            'purple' => ['purple', 'ceplok-o-info-circle'],
        ];
    }

    #[DataProvider('variant')]
    public function test_variant_memilih_token_warna_dan_ikon_bawaan(string $variant, string $icon): void
    {
        $alert = $this->find($this->dom("<x-ceplok::alert variant=\"{$variant}\" heading=\"Judul\">Isi</x-ceplok::alert>"), 'div[role="alert"]');

        $this->assertHasClasses($alert, "bg-alert-{$variant}-surface");
        $this->assertHasClasses($this->find($alert, 'p'), "text-sm font-bold text-alert-{$variant}-heading");
        $this->assertHasClasses($this->find($alert, 'p + div'), "text-sm leading-6 mt-1 text-alert-{$variant}-body");
        $this->assertSame($this->iconPath($icon), $this->find($alert, 'span[aria-hidden="true"] path')->getAttribute('d'));
    }

    public function test_tanpa_heading_isi_tidak_diberi_jarak_atas(): void
    {
        $body = $this->find($this->dom('<x-ceplok::alert>Isi</x-ceplok::alert>'), '.min-w-0 > div');

        $this->assertLacksClasses($body, 'mt-1');
    }

    public function test_ikon_bisa_diganti_atau_disembunyikan(): void
    {
        $custom = $this->dom("<x-ceplok::alert><x-slot:icon>{{ svg('ceplok-o-bell') }}</x-slot:icon>Isi</x-ceplok::alert>");

        $this->assertSame($this->iconPath('ceplok-o-bell'), $this->find($custom, 'span[aria-hidden="true"] path')->getAttribute('d'));
        $this->assertNull($this->dom('<x-ceplok::alert :icon="false">Isi</x-ceplok::alert>')->querySelector('span[aria-hidden="true"]'));
    }

    public function test_actions_di_bawah_isi(): void
    {
        $alert = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::alert heading="Judul">
                Isi
                <x-slot:actions><x-ceplok::button size="xs">Lihat</x-ceplok::button></x-slot:actions>
            </x-ceplok::alert>
            BLADE), 'div[role="alert"]');

        $this->assertHasClasses($this->find($alert, 'button[type="button"]:not([aria-label])')->parentElement, 'mt-3 flex flex-wrap items-center gap-3');
    }

    public function test_tidak_dismissible_menjadi_statis(): void
    {
        $alert = $this->find($this->dom('<x-ceplok::alert :dismissible="false">Isi</x-ceplok::alert>'), 'div[role="alert"]');

        $this->assertFalse($alert->hasAttribute('x-data'));
        $this->assertNull($alert->querySelector('button'));
        $this->assertNull($this->dom('<x-ceplok::alert :dismissible="false" :open="false">Isi</x-ceplok::alert>')->querySelector('body > *'));
    }

    public function test_open_false_menyembunyikan_sejak_awal(): void
    {
        $alert = $this->find($this->dom('<x-ceplok::alert :open="false">Isi</x-ceplok::alert>'), 'div[role="alert"]');

        $this->assertSame('ceplokDismissible', $alert->getAttribute('x-data'));
        $this->assertSame('display: none;', $alert->getAttribute('style'));
    }

    public function test_dark_mode_memasang_data_theme(): void
    {
        $this->assertSame('dark', $this->find($this->dom('<x-ceplok::alert dark-mode>Isi</x-ceplok::alert>'), 'div[role="alert"]')->getAttribute('data-theme'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::alert variant="error">Isi</x-ceplok::alert>', "Prop `variant` pada <x-ceplok::alert> harus salah satu dari 'success', 'danger', 'warning', 'info', 'purple'");
        $this->assertPropError('<x-ceplok::alert dismissible="tidak">Isi</x-ceplok::alert>', 'Prop `dismissible` pada <x-ceplok::alert> harus boolean');
    }

    private function iconPath(string $name): string
    {
        return $this->find($this->parse(svg($name)->toHtml()), 'path')->getAttribute('d');
    }
}
