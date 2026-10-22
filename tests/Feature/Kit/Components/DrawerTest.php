<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class DrawerTest extends TestCase
{
    public function test_drawer_default_markup(): void
    {
        $dom = $this->dom('<x-ceplok::drawer>Konten</x-ceplok::drawer>');
        $dialog = $this->find($dom, 'dialog');

        $this->assertNotNull($dialog);
        $this->assertSame('ceplokDrawer()', $dialog->getAttribute('x-data'));
        $this->assertSame('open', $dialog->getAttribute('x-modelable'));
        $this->assertSame('false', $dialog->getAttribute('data-initial-open'));
        $this->assertSame('true', $dialog->getAttribute('data-close-on-overlay'));
        $this->assertSame('true', $dialog->getAttribute('data-close-on-esc'));

        $this->assertHasClasses(
            $dialog,
            'm-0 p-0 text-left shadow-2xl backdrop:bg-gray-900/50 open:flex open:flex-col overflow-hidden bg-surface text-content border-border fixed inset-y-0 right-0 left-auto h-full max-h-full border-l rounded-none w-[440px] max-w-[calc(100vw-2rem)]'
        );
    }

    public function test_drawer_positions_dan_sizes(): void
    {
        // Posisi kiri ukuran s
        $domLeft = $this->dom('<x-ceplok::drawer position="left" size="s">Kiri</x-ceplok::drawer>');
        $dialogLeft = $this->find($domLeft, 'dialog');
        $this->assertHasClasses($dialogLeft, 'fixed inset-y-0 left-0 right-auto h-full max-h-full border-r rounded-none w-[320px]');

        // Posisi atas ukuran full
        $domTop = $this->dom('<x-ceplok::drawer position="top" size="full">Atas</x-ceplok::drawer>');
        $dialogTop = $this->find($domTop, 'dialog');
        $this->assertHasClasses($dialogTop, 'fixed inset-x-0 top-0 bottom-auto w-full max-w-full border-b rounded-none h-screen max-h-full');

        // Posisi bawah ukuran l
        $domBottom = $this->dom('<x-ceplok::drawer position="bottom" size="l">Bawah</x-ceplok::drawer>');
        $dialogBottom = $this->find($domBottom, 'dialog');
        $this->assertHasClasses($dialogBottom, 'fixed inset-x-0 bottom-0 top-auto w-full max-w-full border-t rounded-none h-[540px]');
    }

    public function test_drawer_dark_mode(): void
    {
        $dom = $this->dom('<x-ceplok::drawer dark-mode>Gelap</x-ceplok::drawer>');
        $dialog = $this->find($dom, 'dialog');

        $this->assertHasClasses($dialog, 'bg-gray-800 text-gray-300 border-gray-700');
        $this->assertSame('dark', $dialog->getAttribute('data-theme'));
    }

    public function test_drawer_items_otomatis_merender_header_dan_nav_items(): void
    {
        $items = [
            ['label' => 'Menu 1', 'icon' => 'ceplok-s-chart-pie'],
            ['label' => 'Menu 2', 'children' => [['label' => 'Sub 1'], ['label' => 'Sub 2']]],
        ];

        $dom = $this->dom('<x-ceplok::drawer eyebrow="MENU UTAMA" :items="$items" />', ['items' => $items]);
        $dialog = $this->find($dom, 'dialog');

        $this->assertNotNull($dialog);
        $header = $this->find($dialog, 'span.uppercase');
        $this->assertSame('MENU UTAMA', trim($header->textContent));

        $navItems = iterator_to_array($dialog->querySelectorAll('button.group'), false);
        $this->assertCount(2, $navItems);
        $this->assertStringContainsString('Menu 1', $navItems[0]->textContent);
        $this->assertStringContainsString('Menu 2', $navItems[1]->textContent);

        // Sub items
        $subItems = iterator_to_array($dialog->querySelectorAll('button.pl-8 button, button.pl-8 a, div.pl-8 button'), false);
        $this->assertCount(2, $subItems);
        $this->assertSame('Sub 1', trim($subItems[0]->textContent));
    }

    public function test_drawer_nav_item_themes(): void
    {
        // Primary
        $primary = $this->find($this->dom('<x-ceplok::drawer.nav-item label="A" active theme="primary" />'), 'button');
        $this->assertHasClasses($primary, 'bg-blue-50 text-blue-600 font-medium');

        // Purple
        $purple = $this->find($this->dom('<x-ceplok::drawer.nav-item label="B" active theme="purple" />'), 'button');
        $this->assertHasClasses($purple, 'bg-purple-100/70 text-purple-700 font-medium');

        // Gray
        $gray = $this->find($this->dom('<x-ceplok::drawer.nav-item label="C" active theme="gray" />'), 'button');
        $this->assertHasClasses($gray, 'bg-gray-100 text-gray-900 font-medium');
    }

    public function test_drawer_trigger(): void
    {
        $dom = $this->dom('<x-ceplok::drawer.trigger />');
        $button = $this->find($dom, 'button');

        $this->assertNotNull($button);
        $this->assertSame('Buka menu drawer', $button->getAttribute('aria-label'));
        $this->assertNotNull($button->querySelector('svg'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError(
            '<x-ceplok::drawer position="center">Konten</x-ceplok::drawer>',
            "Prop `position` pada <x-ceplok::drawer> harus salah satu dari 'right', 'left', 'top', 'bottom'",
        );

        $this->assertPropError(
            '<x-ceplok::drawer size="extra-large">Konten</x-ceplok::drawer>',
            "Prop `size` pada <x-ceplok::drawer> harus salah satu dari 's', 'm', 'l', 'xl', 'full'",
        );

        $this->assertPropError(
            '<x-ceplok::drawer theme="merah">Konten</x-ceplok::drawer>',
            "Prop `theme` pada <x-ceplok::drawer> harus salah satu dari 'primary', 'purple', 'blue', 'gray'",
        );
    }
}
