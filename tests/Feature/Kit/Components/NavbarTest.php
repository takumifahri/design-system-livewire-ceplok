<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class NavbarTest extends TestCase
{
    private function brand(): string
    {
        return ":brand=\"['content' => 'Ceplok', 'href' => '/', 'ariaLabel' => 'Ceplok']\"";
    }

    public function test_front_office_merender_destination_disclosure_dan_guest_actions(): void
    {
        $dom = $this->dom('<x-ceplok::navbar platform="front-office" '.$this->brand().' :items="[
            [\'type\' => \'destination\', \'id\' => \'home\', \'label\' => \'Beranda\', \'href\' => \'/\', \'current\' => true],
            [\'type\' => \'disclosure\', \'id\' => \'services\', \'label\' => \'Layanan\', \'children\' => [[\'type\' => \'destination\', \'id\' => \'catalog\', \'label\' => \'Katalog\', \'href\' => \'/katalog\']]],
        ]" :guest-actions="[\'signIn\' => [\'label\' => \'Masuk\', \'href\' => \'/masuk\']]" />');

        $header = $this->find($dom, 'header');
        $this->assertSame('page', $this->find($header, 'a[aria-current="page"]')->getAttribute('aria-current'));
        $this->assertSame('Navigasi utama', $this->find($header, 'nav')->getAttribute('aria-label'));
        $this->assertNotNull($header->querySelector('[popover]'));
        $signIn = $this->find($header, 'a[href="/masuk"]');
        $this->assertSame('/masuk', $signIn->getAttribute('href'));
        $this->assertNotNull($signIn->querySelector('svg'));
        $this->assertStringNotContainsString('ceplok-o-arrow-right-to-bracket', $signIn->textContent);
    }

    public function test_account_search_dark_mode_dan_mobile_dialog(): void
    {
        $dom = $this->dom('<x-ceplok::navbar platform="front-office" dark-mode '.$this->brand().' :search="[\'placeholder\' => \'Cari layanan\']" :account="[\'name\' => \'Ayu\', \'initials\' => \'AY\', \'items\' => [[\'type\' => \'destination\', \'id\' => \'profile\', \'label\' => \'Profil\', \'href\' => \'/profil\']]]" />');
        $root = $this->find($dom, '[x-data="ceplokNavbar"]');
        $header = $this->find($root, 'header');
        $dialog = $this->find($root, 'dialog');

        $this->assertSame('dark', $header->getAttribute('data-theme'));
        $this->assertSame('dialog', $this->find($header, 'button[aria-controls]')->getAttribute('aria-haspopup') ?: 'dialog');
        $this->assertSame($dialog->getAttribute('id'), $this->find($header, 'button[aria-controls]')->getAttribute('aria-controls'));
        $this->assertNotNull($header->querySelector('[role="search"]'));
    }

    public function test_back_office_memakai_sidebar_kiri_drawer_kanan_dan_tema_eksplisit(): void
    {
        $dom = $this->dom('<x-ceplok::navbar platform="back-office" dark-mode data-theme="light" '.$this->brand().' :items="[[\'type\' => \'destination\', \'id\' => \'report\', \'label\' => \'Laporan\', \'href\' => \'/laporan\']]" :sidebar-items="[[\'label\' => \'Dashboard\', \'href\' => \'/dashboard\']]" :sidebar-user="[\'name\' => \'Ayu\', \'initials\' => \'AY\']" />');
        $root = $this->find($dom, '[x-data="ceplokNavbar"]');
        $header = $this->find($root, 'header');

        $this->assertSame('light', $header->getAttribute('data-theme'));
        $this->assertNotNull($root->querySelector('aside'));
        $this->assertNotNull($root->querySelector('[x-data="ceplokDrawer()"]'));
        $this->assertSame('Buka navigasi Navbar', $this->find($header, 'button[aria-label="Buka navigasi Navbar"]')->getAttribute('aria-label'));
    }

    public function test_desktop_merender_pengukuran_kapasitas_dan_panel_lainnya(): void
    {
        $dom = $this->dom('<x-ceplok::navbar platform="front-office" '.$this->brand().' :items="[
            [\'type\' => \'destination\', \'id\' => \'home\', \'label\' => \'Beranda\', \'href\' => \'/\'],
            [\'type\' => \'destination\', \'id\' => \'profile\', \'label\' => \'Profil Instansi\', \'href\' => \'/profil\'],
            [\'type\' => \'destination\', \'id\' => \'information\', \'label\' => \'Informasi Publik\', \'href\' => \'/informasi\'],
        ]" />');

        $nav = $this->find($dom, 'nav[aria-label="Navigasi utama"]');
        $this->assertCount(3, $nav->querySelectorAll('[data-navbar-primary-item]'));
        $this->assertCount(3, $nav->querySelectorAll('[data-navbar-overflow-item]'));
        $this->assertNotNull($nav->querySelector('[data-navbar-primary-measure]'));
        $this->assertNotNull($nav->querySelector('[data-navbar-load-more]'));
        $this->assertSame('Navigasi lainnya', $this->find($nav, '[popover][aria-label="Navigasi lainnya"]')->getAttribute('aria-label'));
    }

    public function test_destination_external_current_disabled_diteruskan_dan_icon_tidak_mengubah_baris_desktop(): void
    {
        $dom = $this->dom('<x-ceplok::navbar platform="front-office" '.$this->brand().' :items="[
            [\'type\' => \'destination\', \'id\' => \'external\', \'label\' => \'Portal\', \'href\' => \'https://example.com\', \'target\' => \'_blank\', \'current\' => true, \'icon\' => \'ceplok-o-home\'],
            [\'type\' => \'destination\', \'id\' => \'disabled\', \'label\' => \'Arsip\', \'href\' => \'/arsip\', \'disabled\' => true],
        ]" />');

        $external = $this->find($dom, 'nav a[href="https://example.com"]');
        $this->assertSame('_blank', $external->getAttribute('target'));
        $this->assertSame('noopener noreferrer', $external->getAttribute('rel'));
        $this->assertSame('page', $external->getAttribute('aria-current'));
        // Selaras React: ikon item tersedia bagi komposisi mobile, tetapi baris
        // navigasi desktop utama hanya menampilkan label destination.
        $this->assertNull($external->querySelector('svg'));
        $disabled = $this->find($dom, 'nav a[aria-disabled="true"]');
        $this->assertFalse($disabled->hasAttribute('href'));
    }

    public function test_platform_dan_identity_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::navbar platform="portal" '.$this->brand().' />', 'platform');
        $this->assertPropError('<x-ceplok::navbar platform="front-office" '.$this->brand().' :account="[\'name\' => \'A\']" :guest-actions="[\'signIn\' => [\'label\' => \'Masuk\', \'href\' => \'/\']]" />', 'salah satu');
        $this->assertPropError('<x-ceplok::navbar platform="front-office" '.$this->brand().' :notification-trigger="[\'ariaLabel\' => \'Notifikasi\']" />', 'bersama `account`');
        $this->assertPropError('<x-ceplok::navbar platform="back-office" '.$this->brand().' :search="[\'placeholder\' => \'Cari\']" />', 'search.categories');
        $this->assertPropError('<x-ceplok::navbar platform="front-office" '.$this->brand().' :items="[[\'type\' => \'destination\', \'id\' => \'missing-href\', \'label\' => \'Tanpa tujuan\']]" />', 'href');
        $this->assertPropError('<x-ceplok::navbar platform="front-office" '.$this->brand().' :items="[[\'type\' => \'disclosure\', \'id\' => \'group\', \'label\' => \'Grup\', \'href\' => \'/tidak-sah\', \'children\' => [[\'type\' => \'destination\', \'id\' => \'child\', \'label\' => \'Child\', \'href\' => \'/child\']]]]" />', 'tidak menerima `href`');
        $this->assertPropError('<x-ceplok::navbar platform="front-office" '.$this->brand().' :items="[[\'type\' => \'destination\', \'id\' => \'same\', \'label\' => \'A\', \'href\' => \'/a\'], [\'type\' => \'destination\', \'id\' => \'same\', \'label\' => \'B\', \'href\' => \'/b\']]" />', 'id` unik');
    }
}
