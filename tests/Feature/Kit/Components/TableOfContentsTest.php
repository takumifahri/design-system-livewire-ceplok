<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class TableOfContentsTest extends TestCase
{
    private const ITEMS = <<<'PHP'
        [
            ['id' => 'dasar', 'label' => 'Tampilan dasar'],
            ['id' => 'variasi', 'label' => 'Variasi'],
            ['id' => 'properties', 'label' => 'Properties'],
        ]
        PHP;

    private function toc(string $attributes = ''): string
    {
        return '<x-ceplok::table-of-contents :items="'.self::ITEMS.'" '.$attributes.' />';
    }

    public function test_layout_list_sama_dengan_kit_react(): void
    {
        $nav = $this->find($this->dom($this->toc('layout="list"')), 'body > nav');

        $this->assertSame('Daftar isi halaman', $nav->getAttribute('aria-label'));
        $this->assertSame('ceplokTableOfContents', $nav->getAttribute('x-data'));
        $this->assertSame('active', $nav->getAttribute('x-modelable'));
        $this->assertSame('96', $nav->getAttribute('data-offset'));
        $this->assertHasClasses($nav, 'bg-toc-surface transition-colors duration-300 w-52');

        $title = $this->find($nav, 'nav > div > p');

        $this->assertSame('On this page', trim($title->textContent));
        $this->assertHasClasses($title, 'text-[11px] leading-4 font-bold tracking-[0.14em] text-content-disabled uppercase');
        $this->assertHasClasses($this->find($nav, 'ul'), 'relative border-l border-border mt-3');
        $this->assertNull($nav->querySelector('button'));
        $this->assertNull($nav->querySelector('dialog'));
        $this->assertNull($nav->querySelector('[data-toc-list="drawer"]'));
    }

    public function test_entri_berupa_tautan_dan_entri_pertama_disorot(): void
    {
        $nav = $this->find($this->dom($this->toc()), 'nav');
        $links = $nav->querySelectorAll('[data-toc-list="inline"] > li > a');

        $this->assertCount(3, $links);
        $this->assertSame('#dasar', $links->item(0)->getAttribute('href'));
        $this->assertSame('dasar', $links->item(0)->getAttribute('data-id'));
        $this->assertSame('Tampilan dasar', trim($links->item(0)->textContent));

        $active = $links->item(0);
        $this->assertSame('location', $active->getAttribute('aria-current'));
        $this->assertHasClasses($active, '-ml-px block w-full border-l-2 pl-3.5 text-left transition-colors py-1.5 text-xs leading-5 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand border-transparent text-brand');

        $idle = $links->item(1);
        $this->assertFalse($idle->hasAttribute('aria-current'));
        $this->assertHasClasses($idle, 'border-transparent text-content-subtle hover:border-border-strong hover:text-content');
        $this->assertStringNotContainsString('text-brand', $idle->getAttribute('class'));
    }

    public function test_kelas_entri_berganti_lewat_alpine_tanpa_kunci_kembar(): void
    {
        $link = $this->find($this->dom($this->toc()), '[data-toc-list="inline"] a[data-id="variasi"]');
        $binding = $link->getAttribute('x-bind:class');

        // border-transparent ada di kedua keadaan, jadi dipasang statis (pola ClassToggle).
        $this->assertStringNotContainsString('border-transparent', $binding);
        $this->assertStringContainsString("'text-brand': active === 'variasi'", $binding);
        $this->assertSame("active === 'variasi' ? 'location' : null", $link->getAttribute('x-bind:aria-current'));
        $this->assertSame('choose($event, $el.dataset.id)', $link->getAttribute('x-on:click'));
    }

    public function test_indikator_garis_brand_tersembunyi_sampai_diukur(): void
    {
        $indicator = $this->find($this->dom($this->toc()), '[data-toc-list="inline"] > li[aria-hidden="true"]');

        $this->assertTrue($indicator->hasAttribute('data-toc-indicator'));
        $this->assertSame('display: none', $indicator->getAttribute('style'));
        $this->assertHasClasses($indicator, 'pointer-events-none absolute top-0 -left-px w-0.5 rounded-full bg-brand');
        $this->assertSame("{ 'transition-[transform,height] duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none': animated.inline }", $indicator->getAttribute('x-bind:class'));
        $this->assertStringContainsString('animated.drawer', $this->find($this->dom($this->toc()), '[data-toc-list="drawer"] > [data-toc-indicator]')->getAttribute('x-bind:class'));
    }

    public function test_judul_false_atau_kosong_menyembunyikan_judul(): void
    {
        foreach ([':title="false"', 'title=""'] as $attribute) {
            $nav = $this->find($this->dom($this->toc($attribute)), 'nav');

            $this->assertNull($nav->querySelector('p'), $attribute);
            $this->assertStringNotContainsString('mt-3', $this->find($nav, '[data-toc-list="inline"]')->getAttribute('class'), $attribute);
        }

        $custom = $this->find($this->dom($this->toc('title="Di halaman ini"')), 'nav > div > p');

        $this->assertSame('Di halaman ini', trim($custom->textContent));
    }

    public function test_tanpa_anchor_entri_berupa_tombol_tanpa_scroll_spy(): void
    {
        $nav = $this->find($this->dom($this->toc(':anchor="false"')), 'nav');

        $this->assertNull($nav->querySelector('a'));
        $this->assertSame('false', $nav->getAttribute('data-anchor'));
        $this->assertCount(3, $nav->querySelectorAll('[data-toc-list="drawer"] button[data-id]'));
        $this->assertSame('false', $nav->getAttribute('data-scroll-spy'));

        $button = $this->find($nav, '[data-toc-list="inline"] button[data-id="dasar"]');

        $this->assertSame('button', $button->getAttribute('type'));
        $this->assertSame('true', $button->getAttribute('aria-current'));
        $this->assertSame("active === 'dasar' ? 'true' : null", $button->getAttribute('x-bind:aria-current'));
    }

    public function test_active_id_menjadikannya_terkontrol(): void
    {
        $nav = $this->find($this->dom($this->toc('active-id="properties"')), 'nav');

        $this->assertSame('properties', $nav->getAttribute('data-active-id'));
        $this->assertSame('location', $this->find($nav, '[data-toc-list="inline"] a[data-id="properties"]')->getAttribute('aria-current'));
        $this->assertFalse($this->find($nav, '[data-toc-list="inline"] a[data-id="dasar"]')->hasAttribute('aria-current'));
        $this->assertSame('Properties', trim($this->find($nav, '[data-toc-current]')->textContent));
    }

    public function test_scroll_spy_offset_dan_wadah_gulir(): void
    {
        $nav = $this->find($this->dom($this->toc(':scroll-spy="false" :offset="24" scroll-container="#panel"')), 'nav');

        $this->assertSame('false', $nav->getAttribute('data-scroll-spy'));
        $this->assertSame('24', $nav->getAttribute('data-offset'));
        $this->assertSame('#panel', $nav->getAttribute('data-scroll-container'));
        $this->assertFalse($nav->hasAttribute('data-anchor'));
    }

    public function test_dark_mode_memasang_data_theme(): void
    {
        $this->assertSame('dark', $this->find($this->dom($this->toc('dark-mode')), 'nav')->getAttribute('data-theme'));
        $this->assertFalse($this->find($this->dom($this->toc()), 'nav')->hasAttribute('data-theme'));
    }

    public function test_max_height_membuat_daftar_bisa_digulir(): void
    {
        $list = $this->find($this->dom($this->toc(':max-height="256"')), '[data-toc-list="inline"]');

        $this->assertSame('max-height: 256px', $list->getAttribute('style'));
        $this->assertHasClasses($list, 'relative border-l border-border overflow-y-auto overscroll-contain mt-3');

        $calc = $this->find($this->dom($this->toc('max-height="calc(100vh - 8rem)"')), '[data-toc-list="inline"]');

        $this->assertSame('max-height: calc(100vh - 8rem)', $calc->getAttribute('style'));
        $this->assertFalse($this->find($this->dom($this->toc()), '[data-toc-list="inline"]')->hasAttribute('style'));
        $this->assertFalse($this->find($this->dom($this->toc(':max-height="256"')), '[data-toc-list="drawer"]')->hasAttribute('style'));
    }

    public function test_alamat_entri_dari_get_href_atau_href_item(): void
    {
        $pattern = $this->find($this->dom($this->toc('get-href="#/panduan#{id}"')), 'a[data-id="variasi"]');

        $this->assertSame('#/panduan#variasi', $pattern->getAttribute('href'));

        $closure = $this->find($this->dom($this->toc(':get-href="fn ($id) => \'/docs#\'.$id"')), 'a[data-id="dasar"]');

        $this->assertSame('/docs#dasar', $closure->getAttribute('href'));

        $own = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::table-of-contents get-href="#/x#{id}" :items="[['id' => 'luar', 'label' => 'Luar', 'href' => 'https://contoh.id/luar']]" />
            BLADE), 'a');

        $this->assertSame('https://contoh.id/luar', $own->getAttribute('href'));
    }

    public function test_atribut_tambahan_diteruskan_ke_nav(): void
    {
        $nav = $this->find($this->dom($this->toc('class="sticky top-10" aria-label="Isi panduan" wire:key="toc"')), 'nav');

        $this->assertHasClasses($nav, 'bg-toc-surface w-full lg:w-52 sticky top-10');
        $this->assertSame('Isi panduan', $nav->getAttribute('aria-label'));
        $this->assertSame('toc', $nav->getAttribute('wire:key'));
    }

    public function test_layout_auto_tombol_drawer_di_bawah_lg_dan_daftar_mulai_lg(): void
    {
        $nav = $this->find($this->dom($this->toc()), 'body > nav');

        $this->assertHasClasses($nav, 'bg-toc-surface transition-colors duration-300 w-full lg:w-52');
        $this->assertFalse($nav->hasAttribute('data-drawer-contained'));

        // Urutan elemen sama dengan React: tombol, Drawer, lalu daftar biasa.
        $children = array_map(fn ($el) => $el->tagName, iterator_to_array($nav->children, false));
        $this->assertSame(['BUTTON', 'DIALOG', 'DIV'], $children);

        $trigger = $nav->children->item(0);
        $this->assertSame('dialog', $trigger->getAttribute('aria-haspopup'));
        $this->assertSame('false', $trigger->getAttribute('aria-expanded'));
        $this->assertSame("drawerOpen ? 'true' : 'false'", $trigger->getAttribute('x-bind:aria-expanded'));
        $this->assertSame('openDrawer()', $trigger->getAttribute('x-on:click'));
        $this->assertHasClasses($trigger, 'flex w-full items-center gap-3 rounded-lg border border-border bg-surface px-4 py-2.5 text-left text-sm text-content lg:hidden');
        $this->assertSame('On this page:', trim($this->find($trigger, '.sr-only')->textContent));
        $this->assertSame('Tampilan dasar', trim($this->find($trigger, '[data-toc-current]')->textContent));
        $this->assertCount(2, $trigger->querySelectorAll('svg[aria-hidden="true"]'));

        $this->assertHasClasses($nav->children->item(2), 'hidden lg:block');
    }

    public function test_drawer_memakai_drawer_kit_dari_kanan_dengan_entri_14px(): void
    {
        $dialog = $this->find($this->dom($this->toc()), 'nav > dialog');

        $this->assertSame('drawerOpen', $dialog->getAttribute('x-model'));
        $this->assertTrue($dialog->hasAttribute('wire:ignore.self'));
        $this->assertHasClasses($dialog, 'fixed inset-y-0 right-0 w-[320px] max-w-[calc(100vw-2rem)] bg-surface text-content lg:hidden');

        $title = $this->find($dialog, 'h2');
        $this->assertSame($title->getAttribute('id'), $dialog->getAttribute('aria-labelledby'));
        $this->assertSame('On this page', trim($title->textContent));
        $this->assertHasClasses($title, 'text-xs font-bold tracking-[0.14em] text-content-disabled uppercase');
        $this->assertSame('close()', $this->find($dialog, 'button[aria-label="Tutup drawer"]')->getAttribute('x-on:click'));

        $link = $this->find($dialog, '[data-toc-list="drawer"] a[data-id="dasar"]');
        $this->assertSame('location', $link->getAttribute('aria-current'));
        $this->assertSame('chooseInDrawer($event, $el)', $link->getAttribute('x-on:click'));
        $this->assertHasClasses($link, 'py-2.5 text-sm leading-5 text-brand');
        $this->assertHasClasses($this->find($dialog, '[data-toc-list="drawer"]'), 'relative border-l border-border');
    }

    public function test_layout_drawer_tanpa_daftar_biasa(): void
    {
        $nav = $this->find($this->dom($this->toc('layout="drawer"')), 'nav');

        $this->assertHasClasses($nav, 'w-full');
        $this->assertStringNotContainsString('lg:', $nav->getAttribute('class'));
        $this->assertNull($nav->querySelector('[data-toc-list="inline"]'));
        $this->assertStringNotContainsString('lg:hidden', $this->find($nav, 'nav > button')->getAttribute('class'));
        $this->assertStringNotContainsString('lg:hidden', $this->find($nav, 'dialog')->getAttribute('class'));
    }

    public function test_drawer_contained_berupa_panel_di_dalam_bingkai(): void
    {
        $nav = $this->find($this->dom($this->toc('layout="drawer" drawer-contained :title="false"')), 'nav');

        $this->assertTrue($nav->hasAttribute('data-drawer-contained'));
        $this->assertNull($nav->querySelector('dialog'));

        $layer = $this->find($nav, 'nav > div');
        $this->assertTrue($layer->hasAttribute('inert'));
        $this->assertSame('! drawerOpen', $layer->getAttribute('x-bind:inert'));
        $this->assertHasClasses($layer, 'absolute inset-0 z-20 overflow-hidden rounded-[inherit] invisible');

        $panel = $this->find($layer, '[role="dialog"]');
        $this->assertSame('-1', $panel->getAttribute('tabindex'));
        $this->assertSame('closeDrawer()', $panel->getAttribute('x-on:keydown.escape.stop'));
        $this->assertHasClasses($panel, 'absolute inset-y-0 right-0 flex w-64 max-w-[85%] flex-col bg-surface shadow-2xl translate-x-full in-data-[theme=dark]:bg-gray-800');

        // Tanpa judul, judul panel memakai aria-label.
        $title = $this->find($panel, 'p');
        $this->assertSame($title->getAttribute('id'), $panel->getAttribute('aria-labelledby'));
        $this->assertSame('Daftar isi halaman', trim($title->textContent));
        $this->assertSame('closeDrawer()', $this->find($layer, 'div[aria-hidden="true"]')->getAttribute('x-on:click'));
        $this->assertNull($this->find($nav, 'nav > button')->querySelector('.sr-only'));
    }

    public function test_dark_mode_ikut_ke_drawer(): void
    {
        $dialog = $this->find($this->dom($this->toc('dark-mode')), 'dialog');

        $this->assertSame('dark', $dialog->getAttribute('data-theme'));
        $this->assertHasClasses($dialog, 'bg-gray-800 text-gray-300');
    }

    public function test_prop_tidak_sah_ditolak(): void
    {
        $this->assertPropError($this->toc(':offset="-1"'), 'Prop `offset` pada <x-ceplok::table-of-contents> harus bilangan bulat minimal 0');
        $this->assertPropError($this->toc('anchor="tidak"'), 'Prop `anchor` pada <x-ceplok::table-of-contents> harus boolean');
        $this->assertPropError($this->toc('layout="sheet"'), 'Prop `layout` pada <x-ceplok::table-of-contents>');
        $this->assertPropError($this->toc('drawer-contained="ya"'), 'Prop `drawer-contained` pada <x-ceplok::table-of-contents> harus boolean');
        $this->assertPropError(
            '<x-ceplok::table-of-contents :items="[[\'label\' => \'Tanpa id\']]" />',
            'Setiap item <x-ceplok::table-of-contents> butuh `id`',
        );
    }
}
