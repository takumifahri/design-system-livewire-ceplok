<?php

namespace Tests\Feature\Kit\Components;

use Dom\Element;
use Dom\HTMLDocument;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

class TableTest extends TestCase
{
    private const COLUMNS = "[['key' => 'nama', 'header' => 'Nama', 'sortable' => true, 'emphasis' => true, 'align' => 'left'], ['key' => 'kota', 'header' => 'Kota']]";

    private const ROWS = "[['id' => 1, 'nama' => 'Record 10', 'kota' => 'Bandung'], ['id' => 2, 'nama' => 'Record 9', 'kota' => null], ['id' => 3, 'nama' => 'Andi', 'kota' => 'Cimahi']]";

    /** @param  array<string, mixed>  $data */
    private function table(string $props = '', array $data = []): HTMLDocument
    {
        return $this->dom('<x-ceplok::table :columns="'.self::COLUMNS.'" :data="'.self::ROWS.'" row-key="id" '.$props.' />', $data);
    }

    /** @return list<Element> */
    private function all(HTMLDocument|Element $root, string $selector): array
    {
        return iterator_to_array($root->querySelectorAll($selector), false);
    }

    /** @return list<string> */
    private function texts(HTMLDocument|Element $root, string $selector): array
    {
        return array_map(fn (Element $el) => trim($el->textContent), $this->all($root, $selector));
    }

    public function test_kerangka_dan_rupa_normal_sama_dengan_kit_react(): void
    {
        $dom = $this->table('id="pengajuan" class="mt-4"');
        $root = $this->find($dom, 'body > div');

        $this->assertSame('ceplokTable', $root->getAttribute('x-data'));
        $this->assertSame('selected', $root->getAttribute('x-modelable'));
        $this->assertSame('pengajuan', $root->getAttribute('id'));
        $this->assertHasClasses($root, '@container overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mt-4');
        $this->assertHasClasses($this->find($root, 'table'), 'w-full border-separate border-spacing-0 text-sm');

        // Judul tanpa align rata kiri, walau isi selnya rata tengah.
        $th = $this->find($dom, 'thead th:nth-child(2)');
        $this->assertHasClasses($th, 'font-medium whitespace-nowrap bg-gray-50 text-xs uppercase px-4 py-3 text-left border-t border-t-gray-200 border-b border-gray-200 text-gray-500');

        // Isi sel: emphasis gelap, kosong menjadi "—", rata tengah bila align tidak diisi.
        $cells = $this->all($dom, 'tbody tr:first-child td');
        $this->assertHasClasses($cells[0], 'border-b border-gray-200 px-4 py-4 text-left text-gray-900 whitespace-nowrap');
        $this->assertHasClasses($cells[1], 'text-center text-gray-500');
        $this->assertSame(['Bandung', '—', 'Cimahi'], $this->texts($dom, 'tbody td:nth-child(2)'));
        // Tanpa toolbar dan pagination, hanya pembungkus gulir.
        $this->assertCount(1, $this->childrenOf($root));
    }

    public function test_size_compact(): void
    {
        $dom = $this->table('size="compact"');

        $this->assertHasClasses($this->find($dom, 'thead th'), 'h-16 px-4 py-2 bg-white text-sm border-t-transparent');
        $this->assertLacksClasses($this->find($dom, 'thead th'), 'uppercase');
        $this->assertHasClasses($this->find($dom, 'tbody td:nth-child(2)'), 'h-16 text-gray-900');
    }

    public function test_judul_sortable_mengirim_urutan_berikutnya(): void
    {
        $dom = $this->table(":sort=\"['key' => 'nama', 'direction' => 'asc']\"");
        $th = $this->find($dom, 'thead th');
        $button = $this->find($th, 'button');

        $this->assertSame('ascending', $th->getAttribute('aria-sort'));
        $this->assertHasClasses($th, 'text-gray-900');
        // Urutan berikutnya dihitung Alpine dari urutan terakhir dan siklus kolomnya,
        // jadi ekspresinya sama di setiap render dan klik cepat beruntun tetap benar.
        $this->assertSame('{"key":"nama","direction":"asc"}', $this->find($dom, 'body > div')->getAttribute('data-sort'));
        $this->assertSame("toggleSort('nama', JSON.parse('[\\u0022asc\\u0022,\\u0022desc\\u0022,null]'))", $button->getAttribute('x-on:click'));
        $this->assertHasClasses($button, 'flex w-full items-center gap-2 justify-between uppercase');
        $this->assertSame('true', $this->find($button, 'svg')->getAttribute('aria-hidden'));
        // Kolom yang tidak sortable tidak punya tombol.
        $this->assertNull($dom->querySelector('thead th:nth-child(2) button'));
        // Tanpa manual, Table mengurutkan sendiri: angka di teks urut wajar.
        $this->assertSame(['Andi', 'Record 9', 'Record 10'], $this->texts($dom, 'tbody td:first-child'));
    }

    public function test_pagination_bawaan_memotong_data_dan_menulis_ringkasan(): void
    {
        $dom = $this->table(":pagination=\"['page' => 2, 'page-size' => 2, 'theme' => 'purple']\"");
        $footer = $this->find($dom, 'body > div > div:last-child');

        $this->assertSame(['Andi'], $this->texts($dom, 'tbody td:first-child'));
        $this->assertSame('Menampilkan 3–3 dari 3 data', preg_replace('/\s+/', ' ', trim($this->find($footer, 'p')->textContent)));
        $this->assertHasClasses($footer, 'border-t border-gray-200 p-4 @lg:flex-row');
        $this->assertSame('2', trim($this->find($footer, 'nav [aria-current="page"]')->textContent));
        $this->assertHasClasses($this->find($footer, 'nav [aria-current="page"]'), 'bg-purple-50 @lg:h-10');
    }

    public function test_halaman_dijepit_ke_halaman_terakhir(): void
    {
        $dom = $this->table(":pagination=\"['page' => 9, 'page-size' => 2]\"");

        $this->assertSame('2', trim($this->find($dom, 'nav [aria-current="page"]')->textContent));
    }

    public function test_manual_dan_paginator_tidak_mengurutkan_ulang(): void
    {
        $dom = $this->table(":sort=\"['key' => 'nama', 'direction' => 'asc']\" manual :pagination=\"['page' => 3, 'page-size' => 3, 'total' => 30]\"");

        $this->assertSame(['Record 10', 'Record 9', 'Andi'], $this->texts($dom, 'tbody td:first-child'));
        $this->assertStringContainsString('7–9 dari 30', $this->find($dom, 'body > div > div:last-child p')->textContent);

        $paginator = new LengthAwarePaginator([['id' => 41, 'nama' => 'Z', 'kota' => 'A']], 45, 10, 5);
        $dom = $this->dom('<x-ceplok::table :columns="'.self::COLUMNS.'" :data="$rows" row-key="id" />', ['rows' => $paginator]);

        $this->assertSame(['Z'], $this->texts($dom, 'tbody td:first-child'));
        $this->assertStringContainsString('41–45 dari 45', $this->find($dom, 'body > div > div:last-child p')->textContent);
        $this->assertSame('5', trim($this->find($dom, 'nav [aria-current="page"]')->textContent));
    }

    public function test_pilihan_baris_per_halaman_memakai_dropdown_listbox(): void
    {
        $dom = $this->table(":pagination=\"['page-size' => 2, 'page-size-options' => [2, 5]]\"");
        $trigger = $this->find($dom, 'button[aria-labelledby]');
        [$label, $value] = explode(' ', $trigger->getAttribute('aria-labelledby'));

        $this->assertSame('Baris per halaman', trim($this->find($dom, "#{$label}")->textContent));
        $this->assertSame('2', trim($this->find($dom, "#{$value}")->textContent));
        $this->assertHasClasses($trigger, 'inline-flex h-8 min-w-[4.5rem] rounded-lg border border-gray-300 @lg:h-10');
        $this->assertSame('Baris per halaman', $this->find($dom, '[role="listbox"]')->getAttribute('aria-label'));

        $options = $this->all($dom, '[role="option"]');
        $this->assertSame(['true', 'false'], array_map(fn (Element $o) => $o->getAttribute('aria-selected'), $options));
        $this->assertSame('setPageSize(5)', $options[1]->getAttribute('x-on:click'));
        $this->assertNotNull($options[0]->querySelector('svg.text-primary-700'));
    }

    public function test_seleksi_baris_dan_pilih_semua(): void
    {
        $dom = $this->table('selectable :selected-keys="[1, 3]"');
        $all = $this->find($dom, 'thead input[type="checkbox"]');
        $rows = $this->all($dom, 'tbody tr');

        $this->assertSame('[1,3]', $this->find($dom, 'body > div')->getAttribute('data-selected'));
        $this->assertSame('mixed', $all->getAttribute('aria-checked'));
        $this->assertSame('Pilih semua di halaman ini', $all->getAttribute('aria-label'));
        $this->assertHasClasses($all, 'peer/all border-primary-700 bg-primary-700');
        $this->assertStringContainsString('toggleAll(', $all->getAttribute('x-on:change'));
        $this->assertNotNull($all->getAttribute('x-bind:class'));

        $this->assertSame(['true', 'false', 'true'], array_map(fn (Element $tr) => $tr->getAttribute('aria-selected'), $rows));
        $this->assertSame(['1', '2', '3'], array_map(fn (Element $tr) => $tr->getAttribute('wire:key'), $rows));

        $box = $this->find($rows[0], 'input[type="checkbox"]');
        $this->assertTrue($box->hasAttribute('checked'));
        $this->assertSame('Pilih baris 1', $box->getAttribute('aria-label'));
        $this->assertSame('toggleRow(1, $event.target.checked)', $box->getAttribute('x-on:change'));
        $this->assertFalse($this->find($rows[1], 'input[type="checkbox"]')->hasAttribute('checked'));
    }

    public function test_seleksi_awal_dibaca_dari_wire_model(): void
    {
        $html = Livewire::test(new class extends Component
        {
            /** @var list<int> */
            public array $terpilih = [1, 2, 3];

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::table :columns="[['key' => 'nama', 'header' => 'Nama']]" :data="[['id' => 1, 'nama' => 'A'], ['id' => 2, 'nama' => 'B'], ['id' => 3, 'nama' => 'C']]" row-key="id" selectable wire:model.live="terpilih" />
                    </div>
                    BLADE;
            }
        })->html();
        $dom = $this->parse($html);
        $all = $this->find($dom, 'thead input[type="checkbox"]');

        $this->assertSame('terpilih', $this->find($dom, '[x-data="ceplokTable"]')->getAttribute('wire:model.live'));
        $this->assertTrue($all->hasAttribute('checked'));
        $this->assertSame('true', $all->getAttribute('aria-checked'));
        $this->assertSame('Batalkan pilihan di halaman ini', $all->getAttribute('aria-label'));
    }

    public function test_filter_kolom_dan_kolom_tersembunyi_awal(): void
    {
        $columns = "[['key' => 'nama', 'header' => 'Nama'], ['key' => 'grup', 'header' => 'Info', 'children' => [['key' => 'kota', 'header' => 'Kota'], ['key' => 'telepon', 'header' => 'Telepon']]], ['key' => 'aksi', 'header' => 'Aksi', 'hideable' => false]]";
        $dom = $this->dom('<x-ceplok::table :columns="'.$columns.'" :data="[[\'id\' => 1, \'nama\' => \'A\']]" row-key="id" :filter="[\'default-hidden-columns\' => [\'grup\'], \'label\' => \'Kolom\']" />');
        $trigger = $this->find($dom, 'body > div > div:first-child button[popovertarget]');

        $this->assertSame('Kolom', trim($trigger->textContent));
        $this->assertHasClasses($trigger, 'border-gray-500 text-gray-500 h-[38px]');
        $this->assertSame('["grup"]', $this->find($dom, 'body > div')->getAttribute('data-hidden-columns'));
        $this->assertHasClasses($this->find($dom, '[popover]'), 'w-60');

        // Satu checkbox per kolom teratas; grup dihitung satu.
        $boxes = $this->all($dom, '[popover] input[type="checkbox"]');
        $this->assertSame(['Nama', 'Info', 'Aksi'], $this->texts($dom, '[popover] label'));
        $this->assertSame([true, false, true], array_map(fn (Element $b) => $b->hasAttribute('checked'), $boxes));
        $this->assertSame("toggleColumn('grup', \$event.target.checked)", $boxes[1]->getAttribute('x-on:change'));
        $this->assertTrue($boxes[2]->hasAttribute('disabled'));
        $this->assertFalse($boxes[2]->hasAttribute('x-bind:disabled'));

        // Grup tersembunyi sejak HTML pertama, judul induk dan semua anaknya.
        $grup = $this->all($dom, '[x-show="! isHidden(\'grup\')"]');
        $this->assertCount(5, $grup);
        foreach ($grup as $cell) {
            $this->assertStringContainsString('display: none', $cell->getAttribute('style'));
        }
        $this->assertFalse($this->find($dom, 'td[x-show="! isHidden(\'nama\')"]')->hasAttribute('style'));
    }

    public function test_kotak_pencarian_meneruskan_atribut_ke_input(): void
    {
        $dom = $this->table(":search=\"['wire:model.live' => 'q', 'class' => 'w-80']\"");
        $label = $this->find($dom, 'body > div > div:first-child > label');
        $input = $this->find($label, 'input');

        $this->assertHasClasses($label, 'flex h-10 w-full rounded-lg border border-gray-300 bg-gray-50 @xl:w-auto @xl:min-w-[22rem] w-80');
        $this->assertSame('search', $input->getAttribute('type'));
        $this->assertSame('Cari Data', $input->getAttribute('placeholder'));
        $this->assertSame('Cari Data', $input->getAttribute('aria-label'));
        $this->assertSame('q', $input->getAttribute('wire:model.live'));
        $this->assertHasClasses($input, '[&::-webkit-search-cancel-button]:appearance-none');
        $this->assertLacksClasses($input, 'w-80');
    }

    public function test_slot_actions_di_kanan_toolbar(): void
    {
        $dom = $this->dom('<x-ceplok::table :columns="'.self::COLUMNS.'" :data="[]" row-key="id"><x-slot:actions><button>Tambah</button></x-slot:actions></x-ceplok::table>');

        $this->assertHasClasses($this->find($dom, 'body > div > div:first-child'), 'flex flex-wrap items-center gap-4 p-4');
        $this->assertSame('Tambah', trim($this->find($dom, '.ml-auto > button')->textContent));
    }

    public function test_tombol_aksi_baris(): void
    {
        $columns = <<<'PHP'
            [['key' => 'aksi', 'header' => 'Aksi', 'actions' => [
                ['key' => 'ubah', 'icon' => 'ceplok-s-edit', 'label' => 'Ubah', 'theme' => 'yellow', 'attributes' => fn ($row) => ['wire:click' => 'ubah('.$row['id'].')']],
                ['key' => 'lihat', 'icon' => 'ceplok-s-eye', 'label' => fn ($row) => 'Lihat '.$row['id'], 'show-label' => true, 'variant' => 'outline', 'icon-size' => 'l', 'radius' => 'full', 'href' => fn ($row) => '/r/'.$row['id'], 'target' => '_blank', 'disabled' => fn ($row) => $row['id'] === 2],
                ['key' => 'hapus', 'icon' => 'ceplok-s-trash-bin', 'label' => 'Hapus', 'hidden' => fn ($row) => $row['id'] === 1],
            ]]]
            PHP;
        $dom = $this->dom('<x-ceplok::table :columns="'.$columns.'" :data="[[\'id\' => 1], [\'id\' => 2]]" row-key="id" />');
        [$first, $second] = $this->all($dom, 'tbody td');

        $ubah = $this->find($first, 'button[aria-label="Ubah"]');
        $this->assertSame('ubah(1)', $ubah->getAttribute('wire:click'));
        $this->assertHasClasses($ubah, 'bg-yellow-400 text-white hover:bg-yellow-500 size-6 rounded');
        $this->assertHasClasses($this->find($ubah, 'span'), '[&>svg]:size-full size-4');
        // Tooltip judul untuk tombol ikon saja.
        $this->assertHasClasses($ubah->parentElement, 'group/action relative inline-flex');
        $this->assertSame('Ubah', trim($this->find($ubah->parentElement, 'span[aria-hidden="true"]')->textContent));

        $lihat = $this->find($first, 'a');
        $this->assertSame('/r/1', $lihat->getAttribute('href'));
        $this->assertSame('noopener noreferrer', $lihat->getAttribute('rel'));
        $this->assertSame('Lihat 1', trim($lihat->textContent));
        $this->assertFalse($lihat->hasAttribute('aria-label'));
        $this->assertHasClasses($lihat, 'border border-primary-700 text-primary-700 h-8 gap-2 px-3 text-sm rounded-full');

        // Disabled: tautan menjadi tombol nonaktif. Hidden: tidak dirender.
        $this->assertNull($second->querySelector('a'));
        $this->assertTrue($this->find($second, 'button[disabled]')->hasAttribute('disabled'));
        $this->assertNull($first->querySelector('[aria-label="Hapus"]'));
        $this->assertNotNull($second->querySelector('[aria-label="Hapus"]'));
    }

    public function test_kolom_gambar_dan_berkas(): void
    {
        $columns = <<<'PHP'
            [
                ['key' => 'foto', 'header' => 'Foto', 'image' => ['src' => fn ($row) => $row['foto'], 'alt' => fn ($row) => $row['nama'], 'caption' => fn ($row) => 'cap-'.$row['id']]],
                ['key' => 'dok', 'header' => 'Dokumen', 'file' => ['name' => fn ($row) => $row['dok'], 'href' => fn ($row) => $row['url']]],
            ]
            PHP;
        $rows = "[['id' => 1, 'nama' => 'Andi', 'foto' => '/a.png', 'dok' => 'Doc.pdf', 'url' => '/d.pdf'], ['id' => 2, 'nama' => 'Budi', 'foto' => null, 'dok' => 'Surat.docx', 'url' => '/s.docx'], ['id' => 3, 'nama' => 'C', 'foto' => null, 'dok' => null, 'url' => null]]";
        $dom = $this->dom('<x-ceplok::table :columns="'.$columns.'" :data="'.$rows.'" row-key="id" />');
        $cells = $this->all($dom, 'tbody tr:first-child td');

        $preview = $this->find($cells[0], 'button');
        $this->assertSame('Lihat gambar Andi', $preview->getAttribute('aria-label'));
        $this->assertSame('show()', $preview->getAttribute('x-on:click'));
        $this->assertHasClasses($this->find($preview, 'img'), 'object-cover h-6 w-9 rounded');
        $this->assertStringContainsString('cap-1', $cells[0]->textContent);
        $this->assertSame('ceplokTablePreview', $this->find($cells[0], 'span')->getAttribute('x-data'));
        $this->assertSame('open', $this->find($cells[0], 'template')->getAttribute('x-if'));

        // PDF: tombol pratinjau berikon merah. Berkas lain: tautan unduh.
        $this->assertSame('Lihat Doc.pdf', $this->find($cells[1], 'button')->getAttribute('aria-label'));
        $this->assertHasClasses($this->find($cells[1], 'button > span'), 'size-6 shrink-0 text-red-500');
        $docx = $this->find($dom, 'tbody tr:nth-child(2) td:nth-child(2) a');
        $this->assertSame('Surat.docx', $docx->getAttribute('download'));
        $this->assertHasClasses($this->find($docx, 'span'), 'text-gray-400');

        // Nilai kosong menjadi "—".
        $this->assertSame(['—', '—'], $this->texts($dom, 'tbody tr:nth-child(3) td'));
    }

    public function test_gambar_compact_kotak_tanpa_caption_di_sel(): void
    {
        $dom = $this->dom('<x-ceplok::table size="compact" :columns="[[\'key\' => \'f\', \'header\' => \'F\', \'image\' => [\'src\' => fn ($r) => \'/a.png\', \'caption\' => fn ($r) => \'keterangan\']]]" :data="[[\'id\' => 1]]" row-key="id" />');
        $cell = $this->find($dom, 'tbody td');

        $this->assertHasClasses($this->find($cell, 'button img'), 'object-cover size-8 rounded-md');
        $this->assertLacksClasses($this->find($cell, 'button img'), 'h-6 w-9');
        // Caption hanya ada di pratinjau (di dalam <template>), tidak di bawah gambar kecil.
        $this->assertStringNotContainsString('keterangan', preg_replace('/<template.*<\/template>/s', '', $cell->innerHTML));
    }

    public function test_loading_dan_kosong_membentang_semua_kolom(): void
    {
        $loading = $this->dom('<x-ceplok::table :columns="'.self::COLUMNS.'" :data="[]" row-key="id" selectable loading />');
        $td = $this->find($loading, 'tbody td');

        $this->assertSame('3', $td->getAttribute('colspan'));
        $this->assertSame('true', $this->find($loading, 'table')->getAttribute('aria-busy'));
        $this->assertSame('Memuat data', $this->find($td, 'svg')->getAttribute('aria-label'));
        $this->assertTrue($this->find($loading, 'thead input')->hasAttribute('disabled'));

        $empty = $this->dom('<x-ceplok::table :columns="'.self::COLUMNS.'" :data="[]" row-key="id" empty-text="Belum ada pengajuan" />');
        $this->assertSame('2', $this->find($empty, 'tbody td')->getAttribute('colspan'));
        $this->assertSame('Belum ada pengajuan', trim($this->find($empty, 'tbody td')->textContent));
        $this->assertFalse($this->find($empty, 'table')->hasAttribute('aria-busy'));
    }

    public function test_kolom_menempel_dan_zebra(): void
    {
        $dom = $this->table(":sticky=\"['start' => true, 'end' => true]\" selectable striped");
        $cells = $this->all($dom, 'tbody tr:first-child td');

        $this->assertHasClasses($cells[0], 'sticky left-0 z-10 bg-white group-even:bg-gray-50 group-hover:bg-gray-100');
        $this->assertHasClasses($cells[1], 'sticky z-10 left-12 shadow-[inset_-1px_0_0_var(--color-gray-200)]');
        $this->assertHasClasses($cells[2], 'sticky z-10 right-0 shadow-[inset_1px_0_0_var(--color-gray-200)]');
        $this->assertHasClasses($this->find($dom, 'tbody tr'), 'group transition-colors even:bg-gray-50 hover:bg-gray-100');
        $this->assertHasClasses($this->find($dom, 'thead th:nth-child(2)'), 'sticky z-10 bg-gray-50 left-12');
    }

    public function test_judul_bertingkat_dan_posisi_judul(): void
    {
        $columns = "[['key' => 'nama', 'header' => 'Nama', 'children' => [['key' => 'awal', 'header' => 'Awal', 'width' => '8rem'], ['key' => 'akhir', 'header' => 'Akhir', 'header-align' => 'right']]], ['key' => 'email', 'header' => 'Email', 'align' => 'right']]";
        $dom = $this->dom('<x-ceplok::table :columns="'.$columns.'" :data="[]" row-key="id" />');
        [$top, $bottom] = $this->all($dom, 'thead tr');
        [$group, $email] = $this->childrenOf($top);

        $this->assertSame('colgroup', $group->getAttribute('scope'));
        $this->assertSame('2', $group->getAttribute('colspan'));
        $this->assertHasClasses($group, 'text-center pb-0 text-gray-500 border-t');
        $this->assertSame('2', $email->getAttribute('rowspan'));
        $this->assertHasClasses($email, 'text-right align-bottom border-b');
        [$awal, $akhir] = $this->childrenOf($bottom);
        $this->assertSame('width: 8rem;', $awal->getAttribute('style'));
        $this->assertHasClasses($awal, 'text-left');
        $this->assertLacksClasses($awal, 'border-t');
        $this->assertHasClasses($akhir, 'text-right');
    }

    public function test_prop_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::table :data="[]" row-key="id" size="besar" />', 'Prop `size` pada <x-ceplok::table>');
        $this->assertPropError('<x-ceplok::table :columns="[[\'key\' => \'a\', \'align\' => \'atas\']]" :data="[]" row-key="id" />', 'Prop `columns.align` pada <x-ceplok::table>');
        $this->assertPropError('<x-ceplok::table :columns="[[\'key\' => \'a\', \'actions\' => [[\'key\' => \'x\', \'icon\' => \'ceplok-s-eye\', \'label\' => \'X\', \'theme\' => \'biru\']]]]" :data="[[\'id\' => 1]]" row-key="id" />', 'Prop `actions.theme` pada <x-ceplok::table>');
        $this->assertPropError('<x-ceplok::table-file name="a.pdf" type="video" />', 'Prop `type` pada <x-ceplok::table-file>');
    }
}
