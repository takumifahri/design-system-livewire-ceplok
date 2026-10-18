<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class CardTest extends TestCase
{
    public function test_kartu_lengkap_merender_semua_bagian_berurutan(): void
    {
        $card = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::card
                image="/images/card-sample.svg"
                image-alt="Suasana kerja tim"
                title="Judul kartu"
                description="Deskripsi singkat."
                href="/panduan"
                link-label="Lihat panduan"
            >
                <x-slot:actions>
                    <x-ceplok::button size="s">Daftar</x-ceplok::button>
                </x-slot:actions>
                <p id="tambahan">Isi tambahan</p>
            </x-ceplok::card>
            BLADE), 'body > article');

        $this->assertHasClasses($card, '@container flex flex-col overflow-hidden rounded-lg border border-border bg-surface shadow-soft');

        $img = $this->find($card, 'article > img');

        $this->assertSame('/images/card-sample.svg', $img->getAttribute('src'));
        $this->assertSame('Suasana kerja tim', $img->getAttribute('alt'));
        $this->assertHasClasses($img, 'aspect-[5/4] w-full object-cover in-data-[theme=dark]:brightness-90 @xs:aspect-video');

        $body = $this->find($card, 'article > div');

        $this->assertHasClasses($body, 'flex flex-1 flex-col gap-3 p-4 @xs:p-6');
        $this->assertSame(['H3', 'P', 'A', 'DIV', 'P'], array_map(fn ($el) => $el->tagName, $this->childrenOf($body)));
        $this->assertSame('Judul kartu', trim($this->find($body, 'h3')->textContent));
        $this->assertHasClasses($this->find($body, 'h3'), 'text-base leading-snug font-bold text-content @xs:text-xl');
        $this->assertSame('Deskripsi singkat.', trim($this->find($body, 'p')->textContent));

        $link = $this->find($body, 'a');

        $this->assertSame('/panduan', $link->getAttribute('href'));
        $this->assertSame('Lihat panduan', trim($link->textContent));
        $this->assertSame('true', $this->find($link, 'svg')->getAttribute('aria-hidden'));

        $this->assertNotNull($body->querySelector('div.flex.flex-wrap > button'));
        $this->assertSame('Isi tambahan', trim($this->find($body, '#tambahan')->textContent));
    }

    public function test_label_tautan_bawaannya_selengkapnya(): void
    {
        $this->assertSame('Selengkapnya', trim($this->find($this->dom('<x-ceplok::card title="Judul" href="/panduan" />'), 'a')->textContent));
    }

    public function test_bagian_yang_kosong_tidak_dirender(): void
    {
        $card = $this->find($this->dom('<x-ceplok::card title="Hanya judul" />'), 'article');

        $this->assertNull($card->querySelector('img'));
        $this->assertNull($card->querySelector('p'));
        $this->assertNull($card->querySelector('a'));
        $this->assertCount(1, $this->childrenOf($this->find($card, 'article > div')));
    }

    public function test_judul_dan_deskripsi_bisa_lewat_slot(): void
    {
        $card = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::card>
                <x-slot:title>Judul <em>khusus</em></x-slot:title>
                <x-slot:description>Deskripsi dari slot</x-slot:description>
            </x-ceplok::card>
            BLADE), 'article');

        $this->assertNotNull($card->querySelector('h3 > em'));
        $this->assertSame('Deskripsi dari slot', trim($this->find($card, 'p')->textContent));
    }

    public function test_dark_mode_memasang_data_theme(): void
    {
        $this->assertSame('dark', $this->find($this->dom('<x-ceplok::card title="Judul" dark-mode />'), 'article')->getAttribute('data-theme'));
        $this->assertFalse($this->find($this->dom('<x-ceplok::card title="Judul" />'), 'article')->hasAttribute('data-theme'));
    }
}
