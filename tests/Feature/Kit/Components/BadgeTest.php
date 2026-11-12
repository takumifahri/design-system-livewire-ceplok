<?php

namespace Tests\Feature\Kit\Components;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    public function test_badge_statis_tidak_membawa_alpine(): void
    {
        $badge = $this->find($this->dom('<x-ceplok::badge>Draf</x-ceplok::badge>'), 'body > span');

        // Ukuran desain: sm 22px dengan padding 10px × 2px dan jarak ikon 4px.
        $this->assertHasClasses($badge, 'inline-flex items-center rounded-md h-[22px] gap-1 px-2.5 py-0.5 text-sm bg-gray-100 text-gray-900');
        $this->assertFalse($badge->hasAttribute('x-data'));
        $this->assertLacksClasses($badge, 'animate-ceplok-fade-in');
        $this->assertSame('Draf', trim($badge->textContent));
    }

    /** @return array<string, array{string, string, string}> */
    public static function variant(): array
    {
        return [
            'gray' => ['gray', 'bg-gray-100 text-gray-900', 'bg-gray-700 text-gray-300'],
            'brand' => ['brand', 'bg-primary-100 text-primary-800', 'bg-primary-900 text-primary-300'],
            'danger' => ['danger', 'bg-red-100 text-red-800', 'bg-red-900 text-red-300'],
            'warning' => ['warning', 'bg-yellow-100 text-yellow-900', 'bg-yellow-900 text-yellow-300'],
            'success' => ['success', 'bg-green-100 text-green-900', 'bg-green-900 text-green-300'],
        ];
    }

    #[DataProvider('variant')]
    public function test_variant_dan_dark_mode_menentukan_warna(string $variant, string $light, string $dark): void
    {
        $this->assertHasClasses($this->find($this->dom("<x-ceplok::badge variant=\"{$variant}\">Badge</x-ceplok::badge>"), 'span'), $light);
        $this->assertHasClasses($this->find($this->dom("<x-ceplok::badge variant=\"{$variant}\" dark-mode>Badge</x-ceplok::badge>"), 'span'), $dark);
    }

    public function test_size_lg_memperbesar_badge_dan_ikonnya(): void
    {
        $badge = $this->find($this->dom("<x-ceplok::badge size=\"lg\" :icon=\"svg('ceplok-s-clock')\">Besar</x-ceplok::badge>"), 'body > span');

        // lg 25px dengan padding 12px × 2px, jarak 4px, dan ikon 18px.
        $this->assertHasClasses($badge, 'h-[25px] gap-1 px-3 py-0.5 text-base');
        $this->assertHasClasses($this->find($badge, 'span[aria-hidden="true"]'), 'size-4.5');
    }

    public function test_ikon_dirender_sebelum_teks_dan_disembunyikan_dari_pembaca_layar(): void
    {
        $badge = $this->find($this->dom("<x-ceplok::badge :icon=\"svg('ceplok-s-clock')\">Menunggu</x-ceplok::badge>"), 'body > span');
        [$icon, $label] = $this->childrenOf($badge);

        $this->assertSame('true', $icon->getAttribute('aria-hidden'));
        $this->assertHasClasses($icon, 'inline-flex shrink-0 [&>svg]:size-full size-3.5');
        $this->assertNotNull($icon->querySelector('svg'));
        $this->assertSame('Menunggu', trim($label->textContent));
    }

    public function test_dismissible_memasang_perilaku_tutup_dan_tombol_hapus(): void
    {
        $badge = $this->find($this->dom('<x-ceplok::badge variant="brand" dismissible>Filter</x-ceplok::badge>'), 'body > span');

        $this->assertSame('ceplokDismissible', $badge->getAttribute('x-data'));
        $this->assertFalse($badge->hasAttribute('style'));
        $this->assertSame('open', $badge->getAttribute('x-modelable'));
        $this->assertSame('shown', $badge->getAttribute('x-show'));
        $this->assertHasClasses($badge, 'transition-opacity duration-200 opacity-100 animate-ceplok-fade-in');

        $close = $this->find($badge, 'button[type="button"][aria-label="Hapus"]');

        $this->assertSame('close()', $close->getAttribute('x-on:click'));
        $this->assertHasClasses($this->find($close, 'svg'), 'size-3.5');
        // Hanya variant gray yang tombolnya lebih pucat dari teksnya.
        $this->assertLacksClasses($close, 'text-gray-500 text-gray-400');
    }

    public function test_tombol_tutup_gray_lebih_pucat_dari_teksnya(): void
    {
        $this->assertHasClasses($this->find($this->dom('<x-ceplok::badge dismissible>Filter</x-ceplok::badge>'), 'button'), 'text-gray-500');
        $this->assertHasClasses($this->find($this->dom('<x-ceplok::badge dismissible dark-mode>Filter</x-ceplok::badge>'), 'button'), 'text-gray-400');
    }

    public function test_open_false_menyembunyikan_badge_interaktif_sejak_awal(): void
    {
        $badge = $this->find($this->dom('<x-ceplok::badge dismissible :open="false">Filter</x-ceplok::badge>'), 'body > span');

        $this->assertSame('ceplokDismissible', $badge->getAttribute('x-data'));
        $this->assertSame('display: none;', $badge->getAttribute('style'));
    }

    public function test_badge_statis_yang_ditutup_tidak_dirender(): void
    {
        $this->assertNull($this->dom('<x-ceplok::badge :open="false">Filter</x-ceplok::badge>')->querySelector('body > *'));
    }

    public function test_x_model_membuat_badge_interaktif_tanpa_tombol_tutup(): void
    {
        $badge = $this->find($this->dom('<x-ceplok::badge x-model="tampil">Baru</x-ceplok::badge>'), 'body > span');

        $this->assertSame('ceplokDismissible', $badge->getAttribute('x-data'));
        $this->assertFalse($badge->hasAttribute('style'));
        $this->assertSame('tampil', $badge->getAttribute('x-model'));
        $this->assertNull($badge->querySelector('button'));

        $hidden = $this->find($this->dom('<x-ceplok::badge x-model="tampil" :open="false">Baru</x-ceplok::badge>'), 'body > span');

        $this->assertSame('display: none;', $hidden->getAttribute('style'));
    }

    public function test_tanpa_isi_slot_tidak_ada_span_label(): void
    {
        $badge = $this->find($this->dom("<x-ceplok::badge :icon=\"svg('ceplok-s-clock')\" />"), 'body > span');

        $this->assertCount(1, $this->childrenOf($badge));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::badge variant="info">Badge</x-ceplok::badge>', "Prop `variant` pada <x-ceplok::badge> harus salah satu dari 'gray', 'brand', 'danger', 'warning', 'success'");
        $this->assertPropError('<x-ceplok::badge size="md">Badge</x-ceplok::badge>', 'Prop `size` pada <x-ceplok::badge>');
        $this->assertPropError('<x-ceplok::badge open="nanti">Badge</x-ceplok::badge>', 'Prop `open` pada <x-ceplok::badge> harus boolean');
    }
}
