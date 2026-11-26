<?php

namespace Tests\Feature\Kit\Components;

use Illuminate\Support\HtmlString;
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

    public function test_simple_dengan_badge_desktop_memakai_dimensi_react_untuk_semua_ukuran(): void
    {
        $expected = [
            's' => ['px-4', 'py-3', 'min-h-[88px]', 'pl-5'],
            'm' => ['px-6', 'py-3.5', 'min-h-[108px]', 'pl-7', 'sm:pl-8'],
            'l' => ['px-8', 'py-4', 'min-h-[134px]', 'pl-9', 'sm:pl-10'],
        ];

        foreach ($expected as $size => $classes) {
            $item = $this->find($this->dom("<x-ceplok::list-view size=\"{$size}\" title=\"Project brief\" badge=\"Aktif\" decoration />"), 'article');

            $this->assertHasClasses($item, implode(' ', $classes));
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

    public function test_action_disabled_desktop_menyembunyikan_tombol_dan_memindahkan_aksi_ke_kartu(): void
    {
        $linkedCard = $this->find($this->dom('<x-ceplok::list-view title="Project brief" action-label="Open" action-href="/brief" action-disabled />'), 'a[href="/brief"]');
        $this->assertNull($linkedCard->querySelector('button'));
        $this->assertHasClasses($linkedCard, 'cursor-pointer hover:border-primary-400/80');

        $livewireCard = $this->find($this->dom('<x-ceplok::list-view title="Project brief" action-label="Open" action-wire-click="openDocument(1)" action-disabled />'), 'button[type="button"]');
        $this->assertSame('openDocument(1)', $livewireCard->getAttribute('wire:click'));
        $this->assertNull($livewireCard->querySelector('button'));
        $this->assertHasClasses($livewireCard, 'cursor-pointer hover:border-primary-400/80');
    }

    public function test_action_disabled_mobile_juga_mendelegasikan_aksi_ke_kartu(): void
    {
        $card = $this->find($this->dom('<x-ceplok::list-view platform="mobile" title="Project brief" action-label="Open" action-href="/brief" action-disabled />'), 'a[href="/brief"]');

        $this->assertNull($card->querySelector('button'));
        $this->assertHasClasses($card, 'w-full text-left cursor-pointer');
    }

    public function test_interactive_feedback_dapat_dimatikan_tanpa_menonaktifkan_aksi(): void
    {
        $card = $this->find($this->dom('<x-ceplok::list-view title="Project brief" href="/brief" :interactive-feedback="false" />'), 'a[href="/brief"]');

        $this->assertHasClasses($card, 'cursor-pointer');
        $this->assertLacksClasses($card, 'hover:border-primary-400/80 hover:shadow-soft active:scale-[0.998]');
    }

    public function test_atribut_tombol_aksi_terpasang_tanpa_atribut_liar(): void
    {
        // Seperti kit React, action-disabled menyembunyikan tombol aksi walau kartu punya href sendiri;
        // kartunya tetap tautan ke href-nya, bukan ke action-href.
        foreach (['desktop', 'mobile'] as $platform) {
            $dom = $this->dom('<x-ceplok::list-view platform="'.$platform.'" title="Project brief" href="/kartu" action-label="Open" action-href="/brief" action-disabled />');
            $link = $this->find($dom, 'a[href="/kartu"]');
            $this->assertNull($link->querySelector('a, button'), "{$platform}: tombol aksi masih tampil.");
            $this->assertNull($dom->querySelector('a[href="/brief"]'), "{$platform}: action-href masih dipakai.");
            $this->assertFalse($link->hasAttribute('aria-disabled'));
        }

        // action-click dan action-attributes sampai ke tombolnya.
        $button = $this->find($this->dom('<x-ceplok::list-view title="Project brief" action-label="Open" action-click="open = true" :action-attributes="[\'data-aksi\' => \'buka\']" />'), 'button');
        $this->assertSame('open = true', $button->getAttribute('x-on:click'));
        $this->assertSame('buka', $button->getAttribute('data-aksi'));

        // Tidak ada sisa direktif atau nama variabel yang tercetak sebagai atribut.
        foreach ([$link, $button] as $el) {
            foreach ($el->attributes as $attr) {
                $this->assertDoesNotMatchRegularExpression('/^@|^actionAttributes$|^actionDisabled$/', $attr->name);
            }
        }
    }

    public function test_target_dan_rel_hanya_untuk_tautan_dengan_rel_aman_bawaan(): void
    {
        $blank = $this->find($this->dom('<x-ceplok::list-view title="Brief" href="/brief" target="_blank" />'), 'a[href="/brief"]');
        $custom = $this->find($this->dom('<x-ceplok::list-view variant="article" title="Topik" href="/topik" target="_self" rel="next" />'), 'a[href="/topik"]');
        $tanpaHref = $this->find($this->dom('<x-ceplok::list-view title="Brief" target="_blank" />'), 'article');

        // Seperti React: target _blank tanpa rel mendapat noreferrer noopener.
        $this->assertSame('_blank', $blank->getAttribute('target'));
        $this->assertSame('noreferrer noopener', $blank->getAttribute('rel'));
        $this->assertSame('_self', $custom->getAttribute('target'));
        $this->assertSame('next', $custom->getAttribute('rel'));
        $this->assertFalse($tanpaHref->hasAttribute('target'));
    }

    public function test_item_dengan_klik_sendiri_menjadi_tombol_kecuali_ada_tombol_aksi(): void
    {
        $items = [
            ['title' => 'Klik', 'attributes' => ['wire:click.prevent' => 'pilih']],
            ['title' => 'Klik + aksi', 'actionLabel' => 'Open', 'attributes' => ['wire:click' => 'pilih']],
            ['variant' => 'article', 'title' => 'Topik', 'attributes' => ['wire:click' => 'baca']],
        ];
        $dom = $this->dom('<x-ceplok::list-view :items="$items" />', compact('items'));

        // Seperti React: item simple yang bisa diklik tanpa href dirender sebagai <button>.
        $button = $this->find($dom, 'button[wire\:click\.prevent="pilih"]');
        $this->assertSame('button', $button->getAttribute('type'));
        $this->assertHasClasses($button, 'cursor-pointer hover:shadow-soft');
        // Dengan tombol aksi, item tetap <article> agar tidak ada <button> bersarang.
        $this->assertNotNull($dom->querySelector('article[wire\:click="pilih"] button'));
        // Article dengan klik sendiri ikut interaktif.
        $this->assertHasClasses($this->find($dom, 'article[wire\:click="baca"]'), 'cursor-pointer hover:border-primary-400/80');
    }

    public function test_pin_attributes_menjadikan_lencana_pin_tombol(): void
    {
        $dom = $this->dom('<x-ceplok::list-view variant="article" title="Topik" :pin-attributes="[\'wire:click\' => \'sematkan\']" />');
        $pin = $this->find($dom, '[role="button"]');

        // Padanan onPin: tombol yang tidak meneruskan kliknya ke kartu.
        $this->assertSame('sematkan', $pin->getAttribute('wire:click'));
        $this->assertSame('0', $pin->getAttribute('tabindex'));
        $this->assertTrue($pin->hasAttribute('x-on:click.stop'));
        $this->assertHasClasses($pin, 'cursor-pointer hover:bg-primary-100');

        $diam = $this->dom('<x-ceplok::list-view variant="article" title="Topik" />');
        $this->assertNull($diam->querySelector('[role="button"]'));
    }

    public function test_content_menambah_isi_di_kolom_teks_seperti_children(): void
    {
        $items = [
            ['title' => 'Brief', 'badge' => 'Baru', 'content' => new HtmlString('<p id="isi-simple">Catatan</p>')],
            ['variant' => 'article', 'title' => 'Topik', 'content' => new HtmlString('<p id="isi-article">Ringkasan</p>')],
        ];
        $dom = $this->dom('<x-ceplok::list-view :items="$items" />', compact('items'));

        // Simple: di kolom teks, setelah badge.
        $simple = $this->find($dom, '#isi-simple');
        $this->assertSame('mt-2 flex items-center', $simple->previousElementSibling->getAttribute('class'));
        // Article: setelah judul, di blok atas.
        $this->assertSame('h4', strtolower($this->find($dom, '#isi-article')->previousElementSibling->tagName));

        // Mode satu item: slot menjadi isi tambahan, di dalam kartunya.
        $tunggal = $this->dom('<x-ceplok::list-view title="Brief"><p id="slot-isi">Dari slot</p></x-ceplok::list-view>');
        $this->assertNotNull($this->find($tunggal, 'article')->querySelector('#slot-isi'));
    }

    public function test_kunci_as_mengatur_tag_kartu(): void
    {
        $items = [
            ['title' => 'Satu', 'as' => 'li'],
            ['variant' => 'article', 'title' => 'Dua', 'as' => 'li'],
        ];
        $dom = $this->dom('<x-ceplok::list-view as="ul" :items="$items" />', compact('items'));

        $this->assertCount(2, $dom->querySelectorAll('ul > li'));
        $this->assertNull($dom->querySelector('article'));
    }
}
