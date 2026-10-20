<?php

namespace Tests\Feature\Kit;

use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * x-data komponen kit harus sama di setiap render Livewire. Alpine
 * menginisialisasi ulang data yang ekspresi x-data-nya berubah, dan keadaan
 * yang sedang berjalan ikut hilang: fokus Floating Label, rentang Datepicker
 * yang baru terisi tanggal mulainya, atau transisi keluar Toast.
 */
class StableAlpineDataTest extends TestCase
{
    public function test_x_data_tidak_berubah_saat_properti_wire_model_berganti(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public string $kota = '';

            /** @var array{start: ?string, end: ?string}|null */
            public ?array $periode = null;

            public string $provinsi = '';

            public string $kategori = 'izin';

            public bool $tampil = true;

            public int $halaman = 1;

            /** @var array{key: string, direction: string}|null */
            public ?array $urutan = null;

            /** @var list<int> */
            public array $terpilih = [];

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::floating-label id="kota" label="Kota" wire:model.live="kota" />
                        <x-ceplok::datepicker id="periode" type="period" label="Periode" wire:model.live="periode" />
                        <x-ceplok::select id="provinsi" label="Provinsi" placeholder="Pilih" wire:model.live="provinsi" :options="['jb' => 'Jawa Barat', 'jt' => 'Jawa Tengah']" />
                        <x-ceplok::search aria-label="Cari" :categories="['izin' => 'Perizinan', 'pajak' => 'Pajak']" :category-attributes="['wire:model.live' => 'kategori']" />
                        <x-ceplok::toast wire:model="tampil">Tersimpan</x-ceplok::toast>
                        <x-ceplok::pagination wire:model.live="halaman" :current-page="$halaman" :total-pages="5" />
                        <x-ceplok::table
                            :columns="[['key' => 'nama', 'header' => 'Nama', 'sortable' => true]]"
                            :data="[['id' => 1, 'nama' => 'Andi'], ['id' => 2, 'nama' => 'Budi'], ['id' => 3, 'nama' => 'Citra']]"
                            row-key="id"
                            selectable
                            :filter="[]"
                            :sort="$urutan"
                            :pagination="['page' => $halaman, 'page-size' => 1]"
                            wire:model.live="terpilih"
                        />
                    </div>
                    BLADE;
            }
        });

        $xData = fn () => array_map(
            fn ($el) => $el->getAttribute('x-data'),
            iterator_to_array($this->parse($component->html())->querySelectorAll('[x-data]'), false),
        );
        $before = $xData();

        $component
            ->set('kota', 'Bandung')
            ->set('periode', ['start' => '2026-08-10', 'end' => null])
            ->set('provinsi', 'jt')
            ->set('kategori', 'pajak')
            ->set('tampil', false)
            ->set('halaman', 3)
            ->set('urutan', ['key' => 'nama', 'direction' => 'desc'])
            ->set('terpilih', [2]);

        $this->assertSame($before, $xData());
    }

    public function test_select_hanya_membawa_pilihan_awal_yang_belum_dibawa_markup(): void
    {
        // Tanpa nilai eksplisit: peramban memilih placeholder, dan Alpine diberi tahu.
        $this->assertSame("ceplokSelect('', 'Pilih')", $this->find($this->dom('<x-ceplok::select aria-label="Provinsi" placeholder="Pilih" :options="[\'a\' => \'A\']" />'), 'body > div')->getAttribute('x-data'));
        // Dengan value, atribut `selected` sudah membawanya.
        $this->assertSame("ceplokSelect(null, 'Pilih')", $this->find($this->dom('<x-ceplok::select aria-label="Provinsi" placeholder="Pilih" value="a" :options="[\'a\' => \'A\']" />'), 'body > div')->getAttribute('x-data'));
        // Kategori Search tanpa atribut `selected`, seperti select terkendali di React.
        $this->assertSame("ceplokSelect('pajak', 'Kategori')", $this->find($this->dom('<x-ceplok::search aria-label="Cari" category="pajak" :categories="[\'izin\' => \'Perizinan\', \'pajak\' => \'Pajak\']" />'), '[role="search"] [x-data]')->getAttribute('x-data'));
    }

    public function test_opsi_select_dan_kategori_search_berkunci_wire_key(): void
    {
        $select = $this->dom('<x-ceplok::select aria-label="Provinsi" :options="[\'jb\' => \'Jawa Barat\', \'jt\' => \'Jawa Tengah\']" />');

        $this->assertSame(['jb', 'jt'], array_map(fn ($o) => $o->getAttribute('wire:key'), iterator_to_array($select->querySelectorAll('option'), false)));

        $search = $this->dom('<x-ceplok::search aria-label="Cari" :categories="[\'izin\' => \'Perizinan\']" />');

        $this->assertSame('izin', $this->find($search, 'option[value="izin"]')->getAttribute('wire:key'));
    }
}
