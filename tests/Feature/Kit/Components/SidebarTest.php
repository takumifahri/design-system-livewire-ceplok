<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class SidebarTest extends TestCase
{
    public function test_sidebar_default_markup(): void
    {
        $dom = $this->dom('<x-ceplok::sidebar>Konten</x-ceplok::sidebar>');
        $aside = $this->find($dom, 'aside');

        $this->assertNotNull($aside);
        $this->assertSame('ceplokSidebar', $aside->getAttribute('x-data'));
        $this->assertSame('false', $aside->getAttribute('data-initial-collapsed'));
        // Lebar lewat kelas, seperti React, supaya bisa ditimpa `class`.
        $this->assertHasClasses($aside, 'w-[280px]');
        $this->assertLacksClasses($aside, 'w-[72px]');
        $this->assertFalse($aside->hasAttribute('style'));
        $this->assertSame("{ 'w-[72px]': collapsed, 'w-[280px]': ! collapsed }", $aside->getAttribute('x-bind:class'));

        $this->assertHasClasses(
            $aside,
            'flex min-h-screen flex-col border-r border-border bg-sidebar-surface text-content transition-all duration-200'
        );
    }

    public function test_sidebar_collapsed(): void
    {
        $dom = $this->dom('<x-ceplok::sidebar collapsed>Konten</x-ceplok::sidebar>');
        $aside = $this->find($dom, 'aside');

        $this->assertNotNull($aside);
        $this->assertSame('ceplokSidebar', $aside->getAttribute('x-data'));
        $this->assertSame('true', $aside->getAttribute('data-initial-collapsed'));
        $this->assertHasClasses($aside, 'w-[72px]');
        $this->assertLacksClasses($aside, 'w-[280px]');
    }

    public function test_sidebar_sticky_dan_dark_mode(): void
    {
        $dom = $this->dom('<x-ceplok::sidebar sticky dark-mode>Konten</x-ceplok::sidebar>');
        $aside = $this->find($dom, 'aside');

        $this->assertHasClasses($aside, 'sticky top-0 h-screen self-start');
        $this->assertSame('dark', $aside->getAttribute('data-theme'));
    }

    public function test_sidebar_header_dan_collapse_button(): void
    {
        $items = [
            ['label' => 'Dashboard', 'icon' => 'ceplok-s-chart-pie', 'href' => '#'],
        ];

        $dom = $this->dom(
            '<x-ceplok::sidebar :items="$items" show-collapse-button><x-slot:logo><span id="logo">Ceplok</span></x-slot:logo></x-ceplok::sidebar>',
            ['items' => $items]
        );
        $aside = $this->find($dom, 'aside');
        $header = $this->find($aside, 'header');

        $this->assertNotNull($header);
        $this->assertHasClasses($header, 'flex shrink-0 items-center px-4');
        $this->assertSame("collapsed ? 'flex-col gap-3 py-4' : 'h-[72px] justify-between'", $header->getAttribute('x-bind:class'));
        $this->assertNotNull($this->find($header, '#logo'));

        $button = $this->find($header, 'button');
        $this->assertNotNull($button);
        $this->assertSame('Tutup sidebar', $button->getAttribute('aria-label'));
        $this->assertTrue($button->hasAttribute('disabled'));
        $this->assertHasClasses($button, 'disabled:cursor-not-allowed disabled:hover:bg-transparent');
        $this->assertNotNull($button->querySelector('svg'));
    }

    public function test_pendengar_collapse_menampilkan_tombol_collapse_aktif(): void
    {
        // Padanan onCollapse di React: tanpa show-collapse-button pun tombolnya tampil, dan aktif.
        $items = [
            ['label' => 'Dashboard', 'icon' => 'ceplok-o-home', 'href' => '#'],
        ];

        $dom = $this->dom('<x-ceplok::sidebar :items="$items" wire:collapse="$toggle(\'ringkas\')" />', ['items' => $items]);
        $button = $this->find($dom, 'aside > header > button');

        $this->assertSame('Tutup sidebar', $button->getAttribute('aria-label'));
        $this->assertFalse($button->hasAttribute('disabled'));

        // Tanpa ikon menu, Sidebar ringkas tidak punya apa pun untuk ditampilkan: tombolnya tetap tidak ada.
        $tanpaIkon = $this->dom('<x-ceplok::sidebar :items="$items" wire:collapse="$toggle(\'ringkas\')" />', ['items' => [['label' => 'Dashboard', 'href' => '#']]]);

        $this->assertNull($tanpaIkon->querySelector('aside > header'));
    }

    public function test_sidebar_user_tanpa_menu_merender_tautan(): void
    {
        $user = [
            'name' => 'Budi Santoso',
            'profileLabel' => 'Administrator',
            'href' => '/profile',
        ];

        $dom = $this->dom('<x-ceplok::sidebar :user="$user" />', ['user' => $user]);
        $link = $this->find($dom, 'a[href="/profile"]');

        $this->assertNotNull($link);
        $this->assertStringContainsString('Budi Santoso', $link->textContent);
        $this->assertStringContainsString('Administrator', $link->textContent);
        // Avatar kit, seperti React: inisial dua huruf dari nama, ukuran default 32px.
        $avatar = $this->find($link, 'div[role="img"]');
        $this->assertSame('Budi Santoso', $avatar->getAttribute('aria-label'));
        $this->assertHasClasses($avatar, 'size-8 bg-avatar-surface text-avatar-content transition');
        $this->assertSame('BS', trim($avatar->textContent));
        // Tautan yang punya tujuan dibiarkan berpindah halaman.
        $this->assertFalse($link->hasAttribute('x-on:click.prevent'));
    }

    public function test_profil_tanpa_tujuan_meneruskan_atribut_dan_tidak_melompat(): void
    {
        // Padanan user.onClick di React: atributnya untuk wire:click, dan "#" tidak diikuti.
        $user = [
            'name' => 'Rafli Arandhana',
            'attributes' => ['wire:click' => "kabari('profil')"],
        ];

        $link = $this->find($this->dom('<x-ceplok::sidebar :user="$user" />', ['user' => $user]), 'a[href="#"]');

        $this->assertSame("kabari('profil')", $link->getAttribute('wire:click'));
        $this->assertTrue($link->hasAttribute('x-on:click.prevent'));
        $this->assertHasClasses($link, 'flex transition-colors');
    }

    public function test_sidebar_user_dengan_menu_merender_dropdown(): void
    {
        $user = [
            'name' => 'Keisha',
            'profileLabel' => 'Lihat Profil',
            'attributes' => ['wire:click' => 'catatProfil'],
            'items' => [
                ['label' => 'Profil Saya', 'href' => '#profile'],
                ['label' => 'Keluar', 'tone' => 'danger', 'href' => '#logout'],
            ],
        ];

        $dom = $this->dom('<x-ceplok::sidebar :user="$user" />', ['user' => $user]);
        $trigger = $this->find($dom, 'button[aria-haspopup="true"]');

        $this->assertNotNull($trigger);
        $this->assertSame('Menu profil Keisha', $trigger->getAttribute('aria-label'));
        // Seperti di React, user.onClick tetap dipanggil saat menu profil dibuka.
        $this->assertSame('catatProfil', $trigger->getAttribute('wire:click'));

        $menu = $this->find($dom, 'div[role="menu"]');
        $this->assertNotNull($menu);

        $logout = $this->find($menu, 'a[href="#logout"]');
        $this->assertNotNull($logout);
        $this->assertHasClasses($logout, 'text-feedback-error');
    }

    public function test_sidebar_navigation_groups_items_dan_submenu(): void
    {
        $groups = [
            [
                'id' => 'main',
                'label' => 'UTAMA',
                'items' => [
                    ['label' => 'Menu 1', 'href' => '#m1', 'icon' => 'ceplok-s-chart-pie', 'badge' => 5],
                    [
                        'id' => 'menu-2',
                        'label' => 'Menu 2',
                        'icon' => 'ceplok-s-cart',
                        'children' => [
                            ['label' => 'Sub 1', 'href' => '#sub1'],
                            ['label' => 'Sub 2', 'href' => '#sub2'],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'secondary',
                'separator' => true,
                'items' => [
                    ['label' => 'Pengaturan', 'href' => '#settings'],
                ],
            ],
        ];

        $dom = $this->dom('<x-ceplok::sidebar :groups="$groups" />', ['groups' => $groups]);
        $nav = $this->find($dom, 'nav[aria-label="Navigasi sidebar"]');

        $this->assertNotNull($nav);
        $this->assertSame('UTAMA', trim($this->find($nav, 'h2')->textContent));

        // Separator
        $sections = iterator_to_array($nav->querySelectorAll('section'), false);
        $this->assertCount(2, $sections);
        $this->assertHasClasses($sections[1], 'mt-3 border-t border-border pt-3');

        // Badge
        $badge = $this->find($nav, 'span.bg-red-100');
        $this->assertNotNull($badge);
        $this->assertSame('5', trim($badge->textContent));

        // Submenu
        $submenuButton = $this->find($nav, 'button[aria-controls="sidebar-submenu-menu-2"]');
        $this->assertNotNull($submenuButton);
        $this->assertSame('Menu 2', trim($this->find($submenuButton, 'span.truncate')->textContent));

        $subItems = iterator_to_array($nav->querySelectorAll('#sidebar-submenu-menu-2 a'), false);
        $this->assertCount(2, $subItems);
        $this->assertSame('Sub 1', trim($subItems[0]->textContent));
        $this->assertSame('Sub 2', trim($subItems[1]->textContent));
    }

    public function test_sidebar_footer(): void
    {
        $dom = $this->dom('<x-ceplok::sidebar><x-slot:footer><span id="foot">v1.0.0</span></x-slot:footer></x-ceplok::sidebar>');
        $footer = $this->find($dom, 'div.border-t');

        $this->assertNotNull($footer);
        $this->assertNotNull($this->find($footer, '#foot'));
    }

    public function test_sidebar_submenu_aktif_selalu_terbuka_dan_ringkas_memerlukan_listener(): void
    {
        $items = [[
            'label' => 'Parent',
            'icon' => 'ceplok-s-cart',
            'children' => [['label' => 'Aktif', 'href' => '#aktif', 'active' => true]],
        ]];

        $dom = $this->dom('<x-ceplok::sidebar collapsed :items="$items" />', ['items' => $items]);
        $submenu = $this->find($dom, '[x-data="ceplokSidebarSubmenu"]');
        $button = $this->find($submenu, 'button');

        $this->assertSame('true', $submenu->getAttribute('data-has-active-child'));
        $this->assertSame('false', $submenu->getAttribute('data-can-expand'));
        $this->assertTrue($button->hasAttribute('disabled'));
        $this->assertSame('display: none;', $this->find($submenu, 'ul')->getAttribute('style'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError(
            '<x-ceplok::sidebar sticky="bukan-boolean">Konten</x-ceplok::sidebar>',
            'Prop `sticky` pada <x-ceplok::sidebar> harus boolean',
        );

        $this->assertPropError(
            '<x-ceplok::sidebar collapsed="miring">Konten</x-ceplok::sidebar>',
            'Prop `collapsed` pada <x-ceplok::sidebar> harus boolean',
        );

        $this->assertPropError(
            '<x-ceplok::sidebar show-collapse-button="iya">Konten</x-ceplok::sidebar>',
            'Prop `show-collapse-button` pada <x-ceplok::sidebar> harus boolean',
        );

        $this->assertPropError(
            '<x-ceplok::sidebar dark-mode="gelap">Konten</x-ceplok::sidebar>',
            'Prop `dark-mode` pada <x-ceplok::sidebar> harus boolean',
        );
    }

    public function test_item_dropdown_profil_meneruskan_attributes_target_dan_rel(): void
    {
        // Padanan onClick, target, dan rel pada SidebarUserItem di React.
        $user = [
            'name' => 'Keisha',
            'items' => [
                ['label' => 'Bantuan', 'href' => '/bantuan', 'target' => '_blank', 'rel' => 'help'],
                ['label' => 'Keluar', 'tone' => 'danger', 'attributes' => ['wire:click' => 'logout', 'x-on:click' => 'catat()']],
                ['label' => 'Arsip', 'disabled' => true, 'attributes' => ['wire:click' => 'arsip']],
            ],
        ];

        $menu = $this->find($this->dom('<x-ceplok::sidebar :user="$user" />', ['user' => $user]), 'div[role="menu"]');

        $bantuan = $this->find($menu, 'a[href="/bantuan"]');
        $this->assertSame('_blank', $bantuan->getAttribute('target'));
        $this->assertSame('help', $bantuan->getAttribute('rel'));

        $buttons = iterator_to_array($menu->querySelectorAll('button[role="menuitem"]'), false);
        $this->assertSame('logout', $buttons[0]->getAttribute('wire:click'));
        $this->assertSame('catat(); closeUserMenu()', $buttons[0]->getAttribute('x-on:click'));
        // Item nonaktif tidak meneruskan aksinya.
        $this->assertTrue($buttons[1]->hasAttribute('disabled'));
        $this->assertFalse($buttons[1]->hasAttribute('wire:click'));
    }

    public function test_menu_tanpa_tujuan_tidak_melompat_dan_submenu_ringkas_berlabel(): void
    {
        $items = [
            ['label' => 'Beranda', 'icon' => 'ceplok-s-chart-pie'],
            ['label' => 'Laporan', 'href' => '/laporan', 'icon' => 'ceplok-s-clipboard'],
            ['label' => 'Toko', 'icon' => 'ceplok-s-cart', 'active' => true, 'children' => [['label' => 'Pesanan', 'href' => '#']]],
        ];

        $dom = $this->dom('<x-ceplok::sidebar collapsed :items="$items" wire:collapse="$toggle(\'ringkas\')" />', ['items' => $items]);
        $nav = $this->find($dom, 'nav');

        // Seperti React: tanpa href atau "#" tidak melompat; tautan sungguhan dibiarkan.
        $this->assertTrue($this->find($nav, 'a[title="Beranda"]')->hasAttribute('x-on:click.prevent'));
        $this->assertFalse($this->find($nav, 'a[href="/laporan"]')->hasAttribute('x-on:click.prevent'));
        $this->assertTrue($this->find($nav, 'li ul a[href="#"]')->hasAttribute('x-on:click.prevent'));

        $button = $this->find($nav, 'button');
        $this->assertSame('Buka sidebar untuk melihat submenu Toko', $button->getAttribute('aria-label'));
        // Item aktif membuka submenunya, jadi chevron sudah terputar sejak render server.
        $svgs = iterator_to_array($button->querySelectorAll('svg'), false);
        $this->assertHasClasses(end($svgs), 'rotate-180');
    }

    public function test_navigasi_tetap_dirender_tanpa_item(): void
    {
        $dom = $this->dom('<x-ceplok::sidebar><x-slot:footer><span id="foot">v1</span></x-slot:footer></x-ceplok::sidebar>');

        // Navigasi flex-1 menahan footer di dasar, seperti React.
        $this->assertNotNull($dom->querySelector('aside > nav[aria-label="Navigasi sidebar"].flex-1'));
    }
}
