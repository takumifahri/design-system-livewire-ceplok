<?php

namespace Tests\Feature\Kit\Components;

use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

/** Select dan Search: tombol pemicu, `<select>` pembawa nilai, dan panel Dropdown. */
class SelectSearchTest extends TestCase
{
    private const PROVINSI = "[['value' => 'jkt', 'label' => 'DKI Jakarta'], ['value' => 'jb', 'label' => 'Jawa Barat'], ['value' => 'jt', 'label' => 'Jawa Tengah', 'disabled' => true]]";

    public function test_select_dengan_placeholder(): void
    {
        $dom = $this->dom('<x-ceplok::select label="Provinsi" info="Sesuai KTP" helper-text="Wajib" placeholder="Pilih provinsi" :options="'.self::PROVINSI.'" name="provinsi" required />');
        $select = $this->find($dom, 'select');
        $trigger = $this->find($dom, 'button[popovertarget]');
        $panel = $this->find($dom, '[popover]');

        // <select> tersembunyi membawa nilai dan atribut pemakai.
        $this->assertSame('provinsi', $select->getAttribute('name'));
        $this->assertTrue($select->hasAttribute('required'));
        $this->assertSame('true', $select->getAttribute('aria-hidden'));
        $this->assertSame('-1', $select->getAttribute('tabindex'));
        $this->assertSame(['', 'jkt', 'jb', 'jt'], array_map(fn ($o) => $o->getAttribute('value'), iterator_to_array($select->querySelectorAll('option'), false)));

        // Tombol menampilkan placeholder; label ditautkan lewat aria-labelledby.
        $this->assertSame('Pilih provinsi', trim($trigger->textContent));
        $this->assertHasClasses($trigger, 'text-content-muted h-9.25');
        $this->assertSame($trigger->getAttribute('id').'-label', $trigger->getAttribute('aria-labelledby'));
        $this->assertSame('Sesuai KTP', $this->find($dom, 'span[title]')->getAttribute('aria-label'));

        // Panel daftar pilihan tetap terang.
        $this->assertSame('listbox', $panel->getAttribute('role'));
        $this->assertSame('Provinsi', $panel->getAttribute('aria-label'));
        $this->assertHasClasses($panel, 'ds-theme-light');
        $this->assertCount(4, $panel->querySelectorAll('[role="option"]'));
        $this->assertSame("ceplokChoose('jb')", $this->find($panel, '[role="option"]:nth-child(3)')->getAttribute('x-on:click'));
        $this->assertSame('true', $this->find($panel, '[role="option"]:nth-child(4)')->getAttribute('aria-disabled'));
    }

    public function test_select_nilai_awal_dan_opsi_dari_slot(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::select label="Wilayah" placeholder="Pilih" value="jt">
                <option value="aceh">Aceh</option>
                <optgroup label="Jawa"><option value="jb">Jawa Barat</option><option value="jt">Jawa Tengah</option></optgroup>
            </x-ceplok::select>
            BLADE);

        $this->assertSame('Jawa Tengah', trim($this->find($dom, 'button[popovertarget]')->textContent));
        $this->assertTrue($this->find($dom, 'option[value="jt"]')->hasAttribute('selected'));
        $this->assertNotNull($dom->querySelector('[popover] [role="group"][aria-label="Jawa"] > p[aria-hidden="true"]'));
    }

    public function test_select_tanpa_placeholder_menampilkan_pilihan_peramban(): void
    {
        $trigger = $this->find($this->dom('<x-ceplok::select aria-label="Provinsi" :options="'.self::PROVINSI.'" />'), 'button[popovertarget]');

        // Tanpa placeholder peramban memilih opsi pertama; tombolnya ikut jujur.
        $this->assertSame('DKI Jakarta', trim($trigger->textContent));
        $this->assertSame('Provinsi', $trigger->getAttribute('aria-label'));
    }

    public function test_select_inactive(): void
    {
        $dom = $this->dom('<x-ceplok::select label="Provinsi" state="inactive" :options="'.self::PROVINSI.'" />');

        $this->assertTrue($this->find($dom, 'select')->hasAttribute('disabled'));
        $this->assertTrue($this->find($dom, 'button[popovertarget]')->hasAttribute('disabled'));
        $this->assertHasClasses($this->find($dom, 'select')->parentElement, 'border-gray-300 bg-field-disabled');
    }

    public function test_search_polos_dan_dengan_kategori(): void
    {
        $plain = $this->dom('<x-ceplok::search label="Cari" name="q" placeholder="Kata kunci" />');

        $this->assertSame('search', $this->find($plain, 'input')->getAttribute('type'));
        $this->assertSame('q', $this->find($plain, 'input')->getAttribute('name'));
        $this->assertNotNull($plain->querySelector('[role="search"] > button'));
        $this->assertSame("ceplokSearch('enter', 300)", $this->find($plain, 'body > div')->getAttribute('x-data'));

        $category = $this->dom("<x-ceplok::search :categories=\"[['value' => 'izin', 'label' => 'Perizinan']]\" category=\"izin\" trigger=\"change\" :debounce=\"150\" aria-label=\"Cari\" :category-attributes=\"['name' => 'kategori']\" />");
        $select = $this->find($category, 'select');

        $this->assertSame('kategori', $select->getAttribute('name'));
        $this->assertSame('Perizinan', trim($this->find($category, 'button[popovertarget]')->textContent));
        $this->assertSame('Cari', $this->find($category, '[role="search"] > button')->getAttribute('aria-label'));
        $this->assertSame("ceplokSearch('change', 150)", $this->find($category, 'body > div')->getAttribute('x-data'));
    }

    public function test_search_compact_tanpa_tombol_dan_lebar_kustom(): void
    {
        $dom = $this->dom('<x-ceplok::search platform="compact" class="w-80" aria-label="Cari" />');

        $this->assertNull($dom->querySelector('[role="search"] > button'));
        $this->assertHasClasses($this->find($dom, 'body > div'), 'w-80');
        $this->assertLacksClasses($this->find($dom, 'body > div'), 'w-full');
    }

    public function test_search_berkategori_memakai_dimensi_dan_token_terbaru(): void
    {
        $dom = $this->dom('<x-ceplok::search dark-mode category="izin" :categories="[[\'value\' => \'izin\', \'label\' => \'Perizinan\']]" aria-label="Cari" />');
        $search = $this->find($dom, '[role="search"]');
        [$category, $field, $button] = $this->childrenOf($search);
        $trigger = $this->find($category, 'button[popovertarget]');

        $this->assertHasClasses($search, 'h-[39px] rounded-[10px] bg-search-category-surface');
        $this->assertHasClasses($category, 'w-[113px] border-search-category-border bg-search-category-surface');
        $this->assertHasClasses($field, 'border-field-border bg-search-category-field-surface');
        $this->assertHasClasses($button, 'my-px h-[37px] w-[42px]');
        $this->assertHasClasses($this->find($button, 'svg'), 'size-4');
        $this->assertSame('Kategori: Perizinan', $trigger->getAttribute('aria-label'));
        $this->assertSame('Perizinan', $trigger->getAttribute('title'));

        // Ring gelap dilukis di ::after sebatas kategori + field, hilang saat fokus.
        $this->assertHasClasses($search, 'relative in-data-[theme=dark]:after:ring-gray-500 in-data-[theme=dark]:after:right-[42px] in-data-[theme=dark]:focus-within:after:ring-transparent');
    }

    public function test_search_kategori_compact_tetap_punya_tombol_dan_memotong_label(): void
    {
        $dom = $this->dom('<x-ceplok::search platform="compact" category="adm" :categories="[[\'value\' => \'adm\', \'label\' => \'Administrasi Kependudukan\']]" aria-label="Cari" />');
        $search = $this->find($dom, '[role="search"]');
        [$category, , $button] = $this->childrenOf($search);
        $trigger = $this->find($category, 'button[popovertarget]');

        $this->assertHasClasses($search, 'h-[37px] rounded-lg');
        $this->assertHasClasses($category, 'w-[128px] border-field-border');
        $this->assertHasClasses($button, 'w-[37px]');
        $this->assertLacksClasses($button, 'my-px h-[37px] w-[42px]');
        $this->assertHasClasses($this->find($button, 'svg'), 'size-3.5');
        $this->assertHasClasses($trigger, 'px-2.5 py-[2px]');
        $this->assertHasClasses($trigger->firstElementChild, 'w-full gap-2.5');
        $this->assertSame('Administra…', trim($this->find($trigger, 'span.truncate')->textContent));
        $this->assertHasClasses($search, 'in-data-[theme=dark]:after:right-[37px]');
    }

    public function test_token_dark_search_memisahkan_field_polos_dan_field_kategori(): void
    {
        $css = file_get_contents(base_path('packages/design-kit-livewire/resources/css/tokens.css'));

        $this->assertStringContainsString('--color-search-category-border: var(--color-gray-700);', $css);
        $this->assertStringContainsString('--color-search-category-field-surface: var(--color-gray-600);', $css);
        $this->assertStringContainsString('--color-search-field-surface: var(--color-gray-700);', $css);
    }

    public function test_pilihan_awal_wire_model_dibaca_dari_properti(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public string $provinsi = 'jb';

            public string $kategori = 'pajak';

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::select id="provinsi" label="Provinsi" placeholder="Pilih" wire:model="provinsi"
                            :options="['jkt' => 'DKI Jakarta', 'jb' => 'Jawa Barat']" />
                        <x-ceplok::search aria-label="Cari" :categories="['izin' => 'Perizinan', 'pajak' => 'Pajak']"
                            :category-attributes="['wire:model.live' => 'kategori']" />
                    </div>
                    BLADE;
            }
        });

        $dom = $this->parse($component->html());

        $this->assertSame('Jawa Barat', trim($this->find($dom, '#provinsi')->textContent));
        $this->assertTrue($this->find($dom, 'select[wire\:model="provinsi"] option[value="jb"]')->hasAttribute('selected'));
        $this->assertSame('Pajak', trim($this->find($dom, '[role="search"] button[popovertarget]')->textContent));
    }
}
