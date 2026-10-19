<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class FooterTest extends TestCase
{
    public function test_logo_menu_hak_cipta_dan_sosial(): void
    {
        $footer = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::footer
                logo="/images/footer-logo.svg"
                logo-alt="Ceplok Design System"
                :menus="[['label' => 'Beranda', 'url' => '/'], ['label' => 'Kontak', 'url' => '/kontak']]"
                copyright="© 2026 Komdigi"
                :socials="[['label' => 'Instagram', 'url' => 'https://instagram.com/komdigi', 'icon' => 'brand-instagram']]"
            />
            BLADE), 'body > footer');

        $this->assertHasClasses($footer, '@container bg-gray-800 text-gray-300');
        $this->assertHasClasses($this->find($footer, 'footer > div'), 'mx-auto max-w-7xl');

        $logo = $this->find($footer, 'img');

        $this->assertSame('/images/footer-logo.svg', $logo->getAttribute('src'));
        $this->assertSame('Ceplok Design System', $logo->getAttribute('alt'));
        $this->assertHasClasses($logo, 'h-12 w-auto @3xl:h-15');

        $this->assertSame('Navigasi footer', $this->find($footer, 'nav')->getAttribute('aria-label'));
        $this->assertSame('Kontak', trim($this->find($footer, 'nav a[href="/kontak"]')->textContent));
        $this->assertSame('© 2026 Komdigi', trim($this->find($footer, 'p.text-base')->textContent));
    }

    public function test_menu_dipecah_per_lima_tiap_baris(): void
    {
        $menus = collect(range(1, 7))->map(fn (int $i) => ['label' => "Menu {$i}", 'url' => "#m{$i}"])->all();
        $rows = $this->dom('<x-ceplok::footer :menus="$menus" />', ['menus' => $menus])->querySelectorAll('nav ul');

        $this->assertCount(2, $rows);
        $this->assertCount(5, $rows->item(0)->querySelectorAll('li'));
        $this->assertCount(2, $rows->item(1)->querySelectorAll('li'));
        $this->assertHasClasses($rows->item(0), 'flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-base');
    }

    public function test_tautan_sosial_eksternal_dibuka_di_tab_baru(): void
    {
        $footer = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::footer :socials="[
                ['label' => 'Instagram', 'url' => 'https://instagram.com/komdigi', 'icon' => 'brand-instagram'],
                ['icon' => svg('ceplok-o-user')],
            ]" />
            BLADE), 'footer');

        [$external, $placeholder] = iterator_to_array($footer->querySelectorAll('ul a'), false);

        $this->assertSame('Instagram', $external->getAttribute('aria-label'));
        $this->assertSame('_blank', $external->getAttribute('target'));
        $this->assertSame('noopener noreferrer', $external->getAttribute('rel'));

        // Tanpa url: tautan "#" di tab yang sama, dengan label cadangan.
        $this->assertSame('#', $placeholder->getAttribute('href'));
        $this->assertFalse($placeholder->hasAttribute('target'));
        $this->assertSame('Media sosial', $placeholder->getAttribute('aria-label'));
    }

    public function test_ikon_sosial_dibungkus_icon_dan_logo_brand_dekoratif(): void
    {
        $link = $this->find($this->dom("<x-ceplok::footer :socials=\"[['label' => 'X', 'url' => 'https://x.com', 'icon' => 'brand-x']]\" />"), 'ul a');

        $wrapper = $this->find($link, 'span[aria-hidden="true"]');

        $this->assertHasClasses($wrapper, 'inline-flex size-6.5');
        $this->assertSame('true', $this->find($wrapper, 'svg')->getAttribute('aria-hidden'));
    }

    public function test_slot_logo_content_dan_copyright(): void
    {
        $footer = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::footer>
                <x-slot:logo-content><span id="logo-teks">Ceplok</span></x-slot:logo-content>
                <x-slot:copyright>Hak cipta <strong>Komdigi</strong></x-slot:copyright>
            </x-ceplok::footer>
            BLADE), 'footer');

        $this->assertNull($footer->querySelector('img'));
        $this->assertNotNull($footer->querySelector('#logo-teks'));
        $this->assertNotNull($footer->querySelector('p.text-base > strong'));
        $this->assertNull($footer->querySelector('nav'));
        $this->assertNull($footer->querySelector('ul'));
    }

    public function test_fluid_melebarkan_isi(): void
    {
        $inner = $this->find($this->dom('<x-ceplok::footer fluid />'), 'footer > div');

        $this->assertHasClasses($inner, 'w-full');
        $this->assertLacksClasses($inner, 'mx-auto max-w-7xl');
    }
}
