<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class ListViewTest extends TestCase
{
    public function test_list_view_item_simple_dan_article_merender(): void
    {
        $simple = $this->dom('<x-ceplok::list-view title="Project brief" :metadata="[\'PDF\', \'2.4 MB\']" />');
        $article = $this->dom('<x-ceplok::list-view variant="article" title="Diskusi" subject="Admin" :views="12" />');

        $simpleItem = $this->find($simple, 'article');
        $articleItem = $this->find($article, 'article');

        $this->assertNotNull($simpleItem);
        $this->assertStringContainsString('Project brief', $simpleItem->textContent);
        $this->assertNotNull($articleItem);
        $this->assertStringContainsString('Diskusi', $articleItem->textContent);
    }

    public function test_simple_memakai_typography_ikon_dan_tombol_sesuai_ukuran_react(): void
    {
        $expected = [
            's' => ['size-8', 'size-4', 'text-xs', 'h-[34px]'],
            'm' => ['size-10', 'size-5', 'text-sm', 'h-[38px]'],
            'l' => ['size-12', 'size-6', 'sm:text-base', 'h-[40px]'],
        ];

        foreach ($expected as $size => [$box, $icon, $metadata, $button]) {
            $item = $this->find($this->dom("<x-ceplok::list-view size=\"{$size}\" title=\"Project brief\" :metadata=\"['PDF', '2.4 MB']\" action-label=\"Open\" />"), 'article');

            $this->assertHasClasses($this->find($item, 'div > div'), $box);
            $this->assertHasClasses($this->find($item, 'svg'), $icon);
            $this->assertHasClasses($this->find($item, 'div[class*="text-content-muted"]'), $metadata);
            $this->assertHasClasses($this->find($item, 'button'), $button);
        }
    }

    public function test_article_memakai_dimensi_react_untuk_semua_ukuran(): void
    {
        $expected = [
            's' => ['max-w-[410px]', 'min-h-[140px]', 'text-sm', 'size-3.5'],
            'm' => ['max-w-[514px]', 'min-h-[159px]', 'text-base', 'size-4'],
            'l' => ['max-w-[728px]', 'min-h-[149px]', 'text-lg', 'size-4'],
            'xl' => ['max-w-[1028px]', 'min-h-[149px]', 'text-xl', 'size-4'],
        ];

        foreach ($expected as $size => $classes) {
            $article = $this->find($this->dom("<x-ceplok::list-view variant=\"article\" size=\"{$size}\" title=\"Diskusi\" subject=\"Admin\" :views=\"12\" />"), 'article');

            $this->assertHasClasses($article, implode(' ', array_slice($classes, 0, 2)));
            $this->assertHasClasses($this->find($article, 'h4'), $classes[2]);
            $this->assertHasClasses($this->find($article, 'div[class*="justify-end"] svg'), $classes[3]);
        }
    }

    public function test_event_aksi_diteruskan_hanya_ke_tombol_open(): void
    {
        $item = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::list-view
                title="Project brief"
                action-label="Open"
                action-wire-click="openDocument(1)"
            />
            BLADE), 'article');

        $button = $this->find($item, 'button');

        $this->assertSame('openDocument(1)', $button->getAttribute('wire:click'));
        $this->assertFalse($item->hasAttribute('wire:click'));
    }
}
