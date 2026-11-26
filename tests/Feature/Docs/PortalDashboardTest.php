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
        $sidebars = $dom->querySelectorAll('aside[x-data="ceplokSidebar"]');
        $this->assertCount(2, $sidebars);
        $sidebar = $sidebars->item(0);

        // Dashboard adalah halaman ini; menu lain belum dirancang, jadi memberi tahu lewat Toast.
        $this->assertSame('page', $this->find($sidebar, 'a[href="'.route('portal.app').'"]')->getAttribute('aria-current'));
        $this->assertNotNull($sidebar->querySelector('a[wire\:click\.prevent="menuDemo(\'kms\')"]'));
        $this->assertNotNull($sidebar->querySelector('a[href="'.route('portal.index').'"][wire\:navigate]'));
        // Seperti docs React, profil membuka dropdown berisi "Keluar" sekaligus memberi tahu lewat Toast.
        $profil = $this->find($sidebar, 'button[aria-haspopup="true"]');
        $this->assertSame("kabari('Halaman profil belum ada di contoh ini.')", $profil->getAttribute('wire:click'));
        $this->assertSame('toggleUserMenu()', $profil->getAttribute('x-on:click'));
        // wire:collapse menampilkan tombol collapse yang aktif.
        $this->assertFalse($this->find($sidebar, 'header button')->hasAttribute('disabled'));
        $this->assertSame(
            "document.cookie = 'ceplok_portal_sidebar_collapsed=' + (\$event.detail.collapsed ? 1 : 0) + '; path=/; max-age=31536000; samesite=lax'",
            $sidebar->getAttribute('x-on:collapse'),
        );

        // Drawer layar sempit hanya menjadi shell off-canvas; isinya tetap komponen Sidebar.
        $drawer = $this->find($dom, 'dialog#portal-menu');
        $this->assertSame('Menu Portal Dashboard', $drawer->getAttribute('aria-label'));
        $sidebarSeluler = $this->find($drawer, 'aside[x-data="ceplokSidebar"]');
        // Tombol "<" hanya menutup Drawer; Sidebar-nya tidak ikut ringkas saat dibuka lagi.
        $this->assertSame('collapsed = false; $wire.menuSeluler = false', $sidebarSeluler->getAttribute('x-on:collapse'));
        // Pilihan tema tetap ada di Sidebar layar sempit.
        $this->assertNotNull($sidebarSeluler->querySelector('[aria-label="Pilih tema"]'));

        foreach ([$sidebar, $sidebarSeluler] as $sidebarResponsif) {
            $this->assertSame('page', $this->find($sidebarResponsif, 'a[href="'.route('portal.app').'"]')->getAttribute('aria-current'));
            $this->assertNotNull($sidebarResponsif->querySelector('a[wire\:click\.prevent="menuDemo(\'kms\')"]'));
            // Pengumuman, Forum, Berita, dan Event pindah ke Navbar, seperti docs React.
            $this->assertNull($sidebarResponsif->querySelector('a[wire\:click\.prevent="menuDemo(\'forum\')"]'));
            $this->assertNotNull($sidebarResponsif->querySelector('a[href="'.route('portal.index').'"][wire\:navigate]'));
            $this->assertNotNull($sidebarResponsif->querySelector('img[alt="CEPLOK"]'));
            $this->assertStringContainsString('RAFLI ARANDHANA', $sidebarResponsif->textContent);
        }

        // Tautan Navbar: di header desktop dan di Drawer kanan layar sempit.
        $navbar = $this->find($dom, 'header nav[aria-label="Navigasi utama"]');
        $drawerNavbar = $this->find($dom, 'dialog#portal-menu-navbar');
        $this->assertSame('Menu', $drawerNavbar->getAttribute('aria-label'));
        foreach (['pengumuman', 'forum', 'berita', 'event'] as $id) {
            $this->assertNotNull($navbar->querySelector('a[wire\:click\.prevent="menuDemo(\''.$id.'\')"]'));
            $this->assertNotNull($drawerNavbar->querySelector('[wire\:click\.prevent="menuDemo(\''.$id.'\')"]'));
        }
        $this->assertSame('$wire.menuNavbar = true', $this->find($dom, 'button[aria-label="Buka navigasi Navbar"]')->getAttribute('x-on:click'));
        $this->assertSame('$wire.menuSeluler = true', $this->find($dom, 'button[aria-label="Buka navigasi utama"]')->getAttribute('x-on:click'));
    }

    public function test_sidebar_desktop_memulihkan_posisi_collapsed_dari_cookie(): void
    {
        $this->withUnencryptedCookie('ceplok_portal_sidebar_collapsed', '1');

        $response = $this->get(route('portal.app'));
        $response->assertOk();

        $dom = $this->parse($response->getContent());
        $sidebar = $dom->querySelectorAll('aside[x-data="ceplokSidebar"]')->item(0);

        // Sidebar ringkas: pilihan tema disembunyikan, tombol tema ringkas yang tampil.
        $this->assertSame('true', $sidebar->getAttribute('data-initial-collapsed'));
        $this->assertSame('display: none;', $this->find($sidebar, '[aria-label="Pilih tema"]')->parentElement->getAttribute('style'));
        $this->assertStringNotContainsString(
            'display: none',
            $this->find($sidebar, 'button[aria-label="Mode gelap"]')->parentElement->getAttribute('style') ?? '',
        );
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

    public function test_list_view_wiki_simple_desktop_memindahkan_aksi_ke_kartu(): void
    {
        $dom = $this->parse(Livewire::test('portal.dashboard')->html());
        $kartuWiki = $dom->querySelectorAll('button[type="button"][wire\:click="kabari(\'Detail wiki belum ada di contoh ini.\')"]');

        $this->assertCount(2, $kartuWiki);

        foreach ($kartuWiki as $kartu) {
            $this->assertNull($kartu->querySelector('button'));
            $this->assertHasClasses($kartu, 'cursor-pointer hover:border-primary-400/80');
        }
    }

    public function test_button_portal_mengikuti_variasi_light_dan_dark_mode(): void
    {
        $komponen = Livewire::test('portal.dashboard');
        $dom = $this->parse($komponen->html());
        $tombol = fn ($root, string $teks) => $this->find($root, 'button[wire\:click="kabari(\''.$teks.' belum ada di contoh ini.\')"]');

        $this->assertHasClasses($tombol($dom, 'Pengaturan layout'), 'bg-primary-700 text-white');
        $this->assertHasClasses($tombol($dom, 'Halaman buat forum'), 'border border-primary-700 text-primary-700');
        $this->assertLacksClasses($tombol($dom, 'Halaman buat forum'), 'bg-primary-700');
        $this->assertStringContainsString('Buat Forum', $tombol($dom, 'Halaman buat forum')->textContent);
        $this->assertHasClasses($tombol($dom, 'Halaman buat forum'), '[&>span:first-child]:size-[18px]');
        $this->assertHasClasses($this->find($tombol($dom, 'Halaman buat forum'), 'svg'), 'size-[18px]');

        $komponen->set('darkMode', true);
        $gelap = $this->parse($komponen->html());
        $checkOut = $this->find($gelap, 'button[wire\:click="kabari(\'Check-out belum ada di contoh ini.\')"]');
        $presensi = $checkOut->closest('[class*="@container"]');

        $this->assertHasClasses($tombol($gelap, 'Pengaturan layout'), 'bg-primary-800 text-white hover:bg-primary-700');
        $this->assertHasClasses($tombol($gelap, 'Halaman buat forum'), 'border border-gray-50 text-gray-50 hover:bg-white');
        $this->assertHasClasses($this->find($gelap, 'button[aria-label="Buka navigasi utama"]'), 'text-content-subtle in-data-[theme=dark]:text-gray-400');
        $this->assertHasClasses($this->find($gelap, 'button[aria-label="Tampilkan semua aplikasi"]'), 'bg-primary-800 text-white');
        $this->assertHasClasses($checkOut, 'justify-self-start sm:justify-self-stretch bg-primary-800 text-white');
        $this->assertHasClasses(
            $this->find($presensi, 'button[disabled]'),
            'bg-gray-500 text-white disabled:opacity-50 disabled:pointer-events-none',
        );
        $this->assertLacksClasses($this->find($presensi, 'button[disabled]'), 'bg-gray-700');
    }

    public function test_presensi_menampilkan_check_out_dan_detail_kehadiran(): void
    {
        $dom = $this->parse(Livewire::test('portal.dashboard')->html());
        $checkOut = $this->find($dom, 'button[wire\:click="kabari(\'Check-out belum ada di contoh ini.\')"]');
        $kartu = $checkOut->closest('[class*="@container"]');

        $this->assertSame('CHECK OUT', trim($checkOut->textContent));
        $this->assertHasClasses($checkOut, 'bg-primary-700 justify-self-start sm:justify-self-stretch');
        $this->assertHasClasses($checkOut->parentElement, 'grid sm:grid-cols-[minmax(0,1fr)_auto]');
        $this->assertCount(2, $checkOut->parentElement->querySelectorAll('button'));
        $this->assertHasClasses($this->find($kartu, 'button[disabled]'), 'justify-self-start sm:justify-self-stretch');
        $this->assertLacksClasses($checkOut, 'w-36');
        $this->assertStringContainsString('Jam Check-Out Anda', $kartu->textContent);
        $this->assertStringContainsString('15:30:00 WIB', $kartu->textContent);
        $this->assertStringContainsString('Untuk detail data kehadiran, silakan cek di aplikasi Apik.', $kartu->textContent);
    }

    public function test_aplikasi_intranet_memberi_tahu_lewat_toast(): void
    {
        $komponen = Livewire::test('portal.dashboard');
        $dom = $this->parse($komponen->html());
        $ubin = $dom->querySelectorAll('ul button[wire\:click^="bukaAplikasi("]');

        $this->assertCount(12, $ubin);
        $this->assertSame('bukaAplikasi(8)', $ubin->item(8)->getAttribute('wire:click'));
        $this->assertSame('E-Arsip', trim($ubin->item(8)->textContent));
        // Panah "tampilkan semua" berada di pembungkus kartu, bukan di dalam Card yang ber-overflow-hidden,
        // dan pembungkusnya berjarak bawah supaya panah itu tidak menempel ke kartu sesudahnya.
        $panah = $this->find($dom, 'button[aria-label="Tampilkan semua aplikasi"]');
        $this->assertNull($panah->closest('[class*="@container"]'));
        $this->assertHasClasses($panah->parentElement, 'relative mb-5');

        $komponen->call('bukaAplikasi', 8)->assertSet('umpan', 'Aplikasi E-Arsip belum ada di contoh ini.');
    }

    public function test_tombol_panah_membuka_dan_menutup_sisa_aplikasi(): void
    {
        $komponen = Livewire::test('portal.dashboard');
        $ubin = fn () => $this->parse($komponen->html())->querySelectorAll('#portal-aplikasi button');
        $panah = fn () => $this->find($this->parse($komponen->html()), 'button[aria-label="Tampilkan semua aplikasi"]');

        $this->assertSame('false', $panah()->getAttribute('aria-expanded'));
        $this->assertSame('portal-aplikasi', $panah()->getAttribute('aria-controls'));
        $this->assertSame("\$toggle('semuaAplikasi')", $panah()->getAttribute('wire:click'));

        $komponen->toggle('semuaAplikasi');

        $this->assertCount(18, $ubin());
        $this->assertSame('true', $panah()->getAttribute('aria-expanded'));
        $this->assertHasClasses($this->find($panah(), 'svg'), 'rotate-180');
        $this->assertSame('Tiket Layanan', trim($ubin()->item(17)->textContent));

        $komponen->call('bukaAplikasi', 12)->assertSet('umpan', 'Aplikasi Helpdesk TI belum ada di contoh ini.');
        $komponen->toggle('semuaAplikasi');

        $this->assertCount(12, $ubin());
    }

    public function test_forum_sticky_post_dan_kosmo_memberi_tahu_lewat_toast(): void
    {
        $komponen = Livewire::test('portal.dashboard');
        $dom = $this->parse($komponen->html());

        // Forum: ListView varian article, tiap kartunya tautan.
        $forum = $dom->querySelectorAll('a[wire\:click\.prevent^="bukaForum("]');
        $this->assertCount(5, $forum);
        $this->assertSame(route('portal.app'), $forum->item(1)->getAttribute('href'));
        $this->assertStringContainsString('Bima Pratama', $forum->item(1)->textContent);
        $this->assertStringContainsString('1689', $forum->item(1)->textContent);

        // Sticky Post: ListView simple tanpa ikon, aksinya di seluruh kartu button.
        $sticky = $dom->querySelectorAll('button[type="button"][wire\:click^="bukaSticky("]');
        $this->assertCount(4, $sticky);
        $this->assertSame('Panduan Akses Wi-Fi Kantor', trim($this->find($sticky->item(0), 'h4')->textContent));
        $this->assertNull($sticky->item(0)->querySelector('svg'));

        // KOSMO: dua kartu, masing-masing dengan kotak QR dan tombol tokonya.
        $kosmo = $this->find($dom, 'section[aria-label="Unduh aplikasi KOSMO"]');
        $this->assertCount(2, $kosmo->querySelectorAll('[role="img"][aria-label^="Kode QR unduhan KOSMO"]'));
        $this->assertSame("unduhKosmo('ios')", $kosmo->querySelectorAll('button')->item(1)->getAttribute('wire:click'));

        $komponen
            ->call('bukaForum', 1)
            ->assertSet('umpan', 'Detail forum "Tata cara penyerahan arsip inaktif ke unit kearsipan" belum ada di contoh ini.')
            ->call('bukaSticky', 0)
            ->assertSet('umpan', 'Detail "Panduan Akses Wi-Fi Kantor" belum ada di contoh ini.')
            ->call('unduhKosmo', 'ios')
            ->assertSet('umpan', 'Tautan KOSMO di App Store belum ada di contoh ini.');
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
