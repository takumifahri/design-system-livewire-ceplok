<?php

namespace Tests\Feature\Docs;

use Livewire\Livewire;
use Tests\TestCase;

/**
 * Portal Dashboard: pintu masuk di dalam kerangka docs dan aplikasinya yang
 * penuh layar, padanan /portal-dashboard di docs React. Halaman pintu
 * masuknya ikut diuji PagesTest; di sini aplikasinya.
 */
class PortalDashboardTest extends TestCase
{
    public function test_pintu_masuk_membuka_aplikasinya_lewat_tirai(): void
    {
        $dom = $this->parse($this->get(route('portal.index'))->getContent());
        $tombol = $this->find($dom, 'a[x-on\:click="masuk($event)"]');

        $this->assertSame(route('portal.app'), $tombol->getAttribute('href'));
        $this->assertStringContainsString('Buka Portal Dashboard', $tombol->textContent);
        $this->assertSame('docsTirai', $tombol->closest('[x-data]')->getAttribute('x-data'));
        // Tirai di luar elemen ber-isolate, supaya z-100-nya tidak terkurung di stacking context itu.
        $this->assertNull($this->find($dom, '[data-tirai-gelembung]')->closest('.isolate'));
        $this->assertNull($this->find($dom, '[data-tirai-panel]')->closest('.isolate'));
    }

    public function test_aplikasi_dirender_penuh_layar_tanpa_kerangka_docs(): void
    {
        $response = $this->get(route('portal.app'));
        $response->assertOk();
        $dom = $this->parse($response->getContent());

        $this->assertSame('Portal Dashboard — Ceplok Design Kit', $this->find($dom, 'title')->textContent);
        $this->assertSame('Dashboard', trim($this->find($dom, 'h1')->textContent));
        $this->assertNull($dom->querySelector('nav[aria-label="Area utama"]'));
        $this->assertNull($dom->querySelector('footer'));

        // Tombol pulang ke pintu masuknya, dengan tirai transisi di bingkainya.
        $this->assertSame('docsTirai', $this->find($dom, 'body')->getAttribute('x-data'));
        $this->assertSame(route('portal.index'), $this->find($dom, 'a[x-on\:click="pulang($event)"]')->getAttribute('href'));
        $this->assertNotNull($dom->querySelector('[data-tirai-gelembung]'));
        $this->assertNotNull($dom->querySelector('[data-tirai-panel]'));

        $ids = array_map(fn ($el) => $el->getAttribute('id'), iterator_to_array($dom->querySelectorAll('[id]'), false));
        $this->assertSame($ids, array_values(array_unique($ids)));
    }

    public function test_menu_dan_profil_memberi_tahu_lewat_toast(): void
    {
        $dom = $this->parse(Livewire::test('portal.dashboard')->html());
        $sidebar = $this->find($dom, 'aside[x-data="ceplokSidebar"]');

        // Dashboard adalah halaman ini; menu lain belum dirancang, jadi memberi tahu lewat Toast.
        $this->assertSame('page', $this->find($sidebar, 'a[href="'.route('portal.app').'"]')->getAttribute('aria-current'));
        $this->assertNotNull($sidebar->querySelector('a[wire\:click\.prevent="menuDemo(\'kms\')"]'));
        $this->assertNotNull($sidebar->querySelector('a[href="'.route('portal.index').'"][wire\:navigate]'));
        $this->assertSame("kabari('Halaman profil belum ada di contoh ini.')", $this->find($sidebar, 'a[href="#"]')->getAttribute('wire:click'));
        // wire:collapse menampilkan tombol collapse yang aktif.
        $this->assertFalse($this->find($sidebar, 'header button')->hasAttribute('disabled'));

        // Menu yang sama ada di Drawer untuk layar sempit, dengan id yang berbeda.
        $drawer = $this->find($dom, 'dialog#portal-menu');
        $this->assertSame('portal-menu-judul', $drawer->getAttribute('aria-labelledby'));
        $this->assertNotNull($drawer->querySelector('a[wire\:click\.prevent="menuDemo(\'forum\')"]'));
    }

    public function test_aksi_demo_mengisi_toast(): void
    {
        Livewire::test('portal.dashboard')
            ->assertSet('umpanTampil', false)
            ->call('cari', '  wiki ', 'forum')
            ->assertSet('umpan', 'Pencarian demo: "wiki" di Forum.')
            ->assertSet('umpanTampil', true)
            ->call('cari', 'wiki', '')
            ->assertSet('umpan', 'Pencarian demo: "wiki" di semua menu.')
            ->call('cari', '   ', 'forum')
            ->assertSet('umpan', 'Ketik kata kunci dulu untuk mencari.')
            ->set('menuSeluler', true)
            ->call('menuDemo', 'kms')
            ->assertSet('menuSeluler', false)
            ->assertSet('umpan', 'Halaman KMS belum ada di contoh ini.')
            ->assertSet('umpanKali', 4);
    }

    public function test_sumber_anggaran_mengganti_angkanya(): void
    {
        Livewire::test('portal.dashboard')
            ->assertSeeInOrder(['Rp. 10.214.835.286.000', 'Rp. 6.117.956.903.552', '59.89%'])
            ->set('sumber', 'sakti')
            ->assertSeeInOrder(['Rp. 1.482.650.000.000', 'Rp. 902.715.448.120', '60.89%'])
            // Nilai yang tidak dikenal jatuh ke MONSAKTI, bukan error.
            ->set('sumber', 'tidak-ada')
            ->assertSee('Rp. 10.214.835.286.000');
    }

    public function test_kalender_menandai_hari_ini_dan_berpindah_bulan(): void
    {
        $komponen = Livewire::test('portal.dashboard');
        $dom = $this->parse($komponen->html());
        $sel = fn ($dom) => array_map(fn ($el) => trim($el->textContent), iterator_to_array($dom->querySelectorAll('tbody span'), false));

        $this->assertSame('Oktober 2026', trim($this->find($dom, 'h2[aria-live="polite"]')->textContent));
        // Enam baris tetap; Oktober 2026 dimulai Kamis, jadi baris pertama dibuka 27 September.
        $this->assertCount(42, $sel($dom));
        $this->assertSame(['27', '28', '29', '30', '1', '2', '3'], array_slice($sel($dom), 0, 7));
        $this->assertSame('13', trim($this->find($dom, 'tbody span[aria-current="date"]')->textContent));
        $this->assertHasClasses($this->find($dom, 'tbody span[aria-current="date"]'), 'bg-primary-700 text-white');

        $dom = $this->parse($komponen->call('bulanBerikutnya')->html());

        $this->assertSame('November 2026', trim($this->find($dom, 'h2[aria-live="polite"]')->textContent));
        $this->assertNull($dom->querySelector('tbody span[aria-current="date"]'));

        $dom = $this->parse($komponen->call('bulanSebelumnya')->call('bulanSebelumnya')->html());

        $this->assertSame('September 2026', trim($this->find($dom, 'h2[aria-live="polite"]')->textContent));
    }
}
