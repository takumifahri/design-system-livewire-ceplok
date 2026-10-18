<?php

namespace Tests\Feature\Kit\Components;

use Dom\HTMLDocument;
use Tests\TestCase;

class HeroTest extends TestCase
{
    private function hero(string $props = '', string $slots = ''): HTMLDocument
    {
        return $this->dom(<<<BLADE
            <x-ceplok::hero
                heading="Layanan Publik Digital"
                sub-heading="Sub heading"
                description="Deskripsi singkat hero."
                image="/images/hero-sample.svg"
                image-alt="Ilustrasi layanan"
                {$props}
            >{$slots}</x-ceplok::hero>
            BLADE);
    }

    public function test_bawaannya_horizontal_dengan_gambar_di_kiri(): void
    {
        $dom = $this->hero();
        $section = $this->find($dom, 'body > section');

        $this->assertHasClasses($section, '@container w-full bg-surface data-[theme=dark]:bg-surface-subtle in-data-[theme=dark]:bg-surface-subtle');
        $this->assertHasClasses($this->find($section, 'section > div'), 'mx-auto max-w-[1440px] px-5 py-6 @[1024px]:min-h-[762px]');

        $row = $this->find($section, 'section > div > div');

        // Teks lebih dulu di DOM; gambar dipindah ke kiri lewat arah baris.
        $this->assertHasClasses($row, 'flex flex-col gap-18 @[768px]:flex-row @[768px]:items-center @[768px]:flex-row-reverse');
        $this->assertSame('H1', $this->childrenOf($row)[0]->querySelector('h1')?->tagName);
        $this->assertSame('/images/hero-sample.svg', $this->find($this->childrenOf($row)[1], 'img')->getAttribute('src'));

        $h1 = $this->find($section, 'h1');

        $this->assertSame('Layanan Publik Digital', trim($h1->textContent));
        $this->assertHasClasses($h1, 'text-heading-1 font-black tracking-tight text-content @[1024px]:text-display');
        $this->assertHasClasses($this->find($section, 'p.text-brand'), 'mt-2 text-body-lg font-bold');
        $this->assertSame('Deskripsi singkat hero.', trim($this->find($section, 'p.text-content-subtle')->textContent));
    }

    public function test_gambar_di_kanan_tanpa_membalik_baris(): void
    {
        $row = $this->find($this->hero('type="horizontal-image-right"'), 'section > div > div');

        $this->assertLacksClasses($row, '@[768px]:flex-row-reverse');
        $this->assertHasClasses($row, '@[768px]:flex-row');
    }

    public function test_tombol_bawaan_dan_tautan(): void
    {
        $button = $this->find($this->hero(), 'section button');

        $this->assertSame('Lihat Lebih Lanjut', trim($button->textContent));
        $this->assertHasClasses($button, 'w-full @[768px]:w-auto bg-primary-700');
        $this->assertNotNull($button->querySelector('span > svg[aria-hidden="true"]'));

        $link = $this->find($this->hero('button-label="Mulai" button-href="/mulai"'), 'section a');

        $this->assertSame('/mulai', $link->getAttribute('href'));
        $this->assertSame('Mulai', trim($link->textContent));
    }

    public function test_button_attributes_diteruskan_dan_kelasnya_disambung(): void
    {
        $button = $this->find($this->hero(":button-attributes=\"['wire:click' => 'mulai', 'class' => 'shadow-lg']\""), 'section button');

        $this->assertSame('mulai', $button->getAttribute('wire:click'));
        $this->assertHasClasses($button, 'w-full @[768px]:w-auto shadow-lg');
    }

    public function test_show_heading_dan_show_button(): void
    {
        $section = $this->find($this->hero(':show-heading="false" :show-button="false"'), 'section');

        $this->assertNull($section->querySelector('p.text-brand'));
        $this->assertNull($section->querySelector('button, a'));
        $this->assertNotNull($section->querySelector('h1'));
    }

    public function test_centered_tanpa_gambar_dan_tombol_seukuran_teks(): void
    {
        $section = $this->find($this->hero('type="centered"'), 'body > section');

        $this->assertHasClasses($section, 'bg-primary-50');
        $this->assertNull($section->querySelector('img'));
        $this->assertLacksClasses($this->find($section, 'section > div'), '@[1024px]:min-h-[762px]');
        $this->assertHasClasses($this->find($section, 'section > div > div'), 'min-w-0 flex-1 text-center mx-auto max-w-3xl py-16 @[768px]:py-24');
        $this->assertLacksClasses($this->find($section, 'button'), 'w-full');
    }

    public function test_centered_image_mengisi_penuh_dan_judulnya_khusus_pembaca_layar(): void
    {
        $section = $this->find($this->hero('type="centered" centered-content="image"'), 'body > section');

        $this->assertLacksClasses($section, '@container');
        $this->assertHasClasses($this->find($section, 'h1'), 'sr-only');
        $this->assertHasClasses($this->find($section, 'img'), 'block h-auto w-full');
        $this->assertNull($section->querySelector('button'));
    }

    public function test_platform_mobile_memaksa_susunan_bertumpuk(): void
    {
        $dom = $this->hero('platform="mobile"');

        $this->assertLacksClasses($this->find($dom, 'section > div'), '@[1024px]:min-h-[762px]');
        $this->assertLacksClasses($this->find($dom, 'section > div > div'), '@[768px]:flex-row');
        $this->assertLacksClasses($this->find($dom, 'h1'), '@[1024px]:text-display');

        $button = $this->find($dom, 'button');

        $this->assertHasClasses($button, 'w-full');
        $this->assertLacksClasses($button, '@[768px]:w-auto');
    }

    public function test_image_orientation_mengatur_lebar_kolom_gambar(): void
    {
        $portrait = $this->find($this->hero(), 'img')->parentElement;
        $landscape = $this->find($this->hero('image-orientation="landscape"'), 'img')->parentElement;

        $this->assertHasClasses($portrait, 'mx-auto w-full max-w-[270px] @[768px]:w-[320px] @[1024px]:w-[386px]');
        $this->assertHasClasses($landscape, 'w-full @[768px]:w-[380px] @[1024px]:w-[435px]');
    }

    public function test_slot_image_content_menggantikan_gambar(): void
    {
        $dom = $this->dom('<x-ceplok::hero heading="Judul"><x-slot:image-content><svg id="ilustrasi"></svg></x-slot:image-content></x-ceplok::hero>');

        $this->assertNotNull($dom->querySelector('section div.shrink-0 > svg#ilustrasi'));
    }

    public function test_dark_mode_memasang_data_theme(): void
    {
        $this->assertSame('dark', $this->find($this->hero('dark-mode'), 'section')->getAttribute('data-theme'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::hero type="vertical" />', "Prop `type` pada <x-ceplok::hero> harus salah satu dari 'horizontal-image-left', 'horizontal-image-right', 'centered'");
        $this->assertPropError('<x-ceplok::hero platform="tablet" />', 'Prop `platform` pada <x-ceplok::hero>');
        $this->assertPropError('<x-ceplok::hero image-orientation="square" />', 'Prop `image-orientation` pada <x-ceplok::hero>');
        $this->assertPropError('<x-ceplok::hero centered-content="video" />', 'Prop `centered-content` pada <x-ceplok::hero>');
    }
}
