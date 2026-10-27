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
        $this->assertSame('width: 280px', $aside->getAttribute('style'));
        $this->assertSame("{ width: collapsed ? '72px' : '280px' }", $aside->getAttribute('x-bind:style'));

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
        $this->assertSame('width: 72px', $aside->getAttribute('style'));
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
        $this->assertNotNull($button->querySelector('svg'));
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
        $this->assertSame('B', trim($this->find($link, 'span[aria-hidden="true"]')->textContent));
    }

    public function test_sidebar_user_dengan_menu_merender_dropdown(): void
    {
        $user = [
            'name' => 'Keisha',
            'profileLabel' => 'Lihat Profil',
            'items' => [
                ['label' => 'Profil Saya', 'href' => '#profile'],
                ['label' => 'Keluar', 'tone' => 'danger', 'href' => '#logout'],
            ],
        ];

        $dom = $this->dom('<x-ceplok::sidebar :user="$user" />', ['user' => $user]);
        $trigger = $this->find($dom, 'button[aria-haspopup="true"]');

        $this->assertNotNull($trigger);
        $this->assertSame('Menu profil Keisha', $trigger->getAttribute('aria-label'));

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
}
