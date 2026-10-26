<?php

namespace Tests\Unit\Kit;

use CeplokUi\DesignKit\Enums\TableSortDirection;
use CeplokUi\DesignKit\Support\Table;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    public function test_kolom_otomatis_dari_field_baris_pertama(): void
    {
        $columns = Table::columns(null, [['id' => 1, 'namaLayanan' => 'KTP', 'jenis_layanan' => 'Kependudukan']]);

        $this->assertSame(['id', 'namaLayanan', 'jenis_layanan'], array_column($columns, 'key'));
        $this->assertSame(['Id', 'Nama layanan', 'Jenis layanan'], array_column($columns, 'header'));
        $this->assertSame([], Table::columns(null, []));
    }

    public function test_judul_bertingkat_membentang_seperti_kit_react(): void
    {
        $tree = Table::columns([
            ['key' => 'nama', 'header' => 'Nama', 'children' => [['key' => 'awal', 'header' => 'Awal'], ['key' => 'akhir', 'header' => 'Akhir']]],
            ['key' => 'email', 'header' => 'Email'],
        ], []);
        $rows = Table::headRows($tree);

        $this->assertSame(['awal', 'akhir', 'email'], array_column(Table::leaves($tree), 'key'));
        $this->assertSame(['nama', 'nama', 'email'], Table::leafOwners($tree));
        $this->assertCount(2, $rows);

        // Baris pertama: induk selebar dua daun, Email turun sampai baris terakhir.
        $this->assertSame([[true, 2, 1], [false, 1, 2]], array_map(fn ($cell) => [$cell['group'], $cell['colspan'], $cell['rowspan']], $rows[0]));
        // Baris kedua: daun milik grup "nama".
        $this->assertSame([['awal', 0, 'nama'], ['akhir', 1, 'nama']], array_map(fn ($cell) => [$cell['column']['key'], $cell['leaf'], $cell['top']], $rows[1]));
    }

    /** @return array<string, array{array<string, mixed>, ?array{key: string, direction: string}, ?array{key: string, direction: string}}> */
    public static function siklusSort(): array
    {
        $nama = ['key' => 'nama'];
        $terbaru = ['key' => 'tanggal', 'sort-directions' => ['desc', 'asc'], 'sort-resettable' => false];

        return [
            'bawaan mulai naik' => [$nama, null, ['key' => 'nama', 'direction' => 'asc']],
            'naik lalu turun' => [$nama, ['key' => 'nama', 'direction' => 'asc'], ['key' => 'nama', 'direction' => 'desc']],
            'turun lalu tanpa urutan' => [$nama, ['key' => 'nama', 'direction' => 'desc'], null],
            'kolom lain mulai dari awal' => [$nama, ['key' => 'email', 'direction' => 'desc'], ['key' => 'nama', 'direction' => 'asc']],
            'terbaru dulu' => [$terbaru, null, ['key' => 'tanggal', 'direction' => 'desc']],
            'tidak kembali tanpa urutan' => [$terbaru, ['key' => 'tanggal', 'direction' => 'asc'], ['key' => 'tanggal', 'direction' => 'desc']],
        ];
    }

    #[DataProvider('siklusSort')]
    public function test_siklus_arah_sort_mengikuti_kolom(array $column, ?array $sort, ?array $expected): void
    {
        $this->assertSame($expected, Table::nextSort($column, $sort));
    }

    public function test_siklus_arah_untuk_alpine(): void
    {
        $this->assertSame(['asc', 'desc', null], Table::sortCycle(['key' => 'nama']));
        $this->assertSame(['desc', 'asc'], Table::sortCycle(['key' => 'tanggal', 'sort-directions' => ['desc', 'asc'], 'sort-resettable' => false]));
        $this->assertSame(['asc', null], Table::sortCycle(['key' => 'id', 'sort-directions' => [TableSortDirection::Asc]]));
    }

    public function test_pengurutan_alami_angka_dan_sort_value(): void
    {
        $rows = [['nama' => 'Record 10', 'n' => 3], ['nama' => 'Record 9', 'n' => 1], ['nama' => 'record 2', 'n' => 2]];
        $leaves = [['key' => 'nama'], ['key' => 'n'], ['key' => 'balik', 'sort-value' => fn ($row) => -$row['n']]];

        // "Record 9" sebelum "Record 10", tanpa membedakan huruf besar.
        $this->assertSame(['record 2', 'Record 9', 'Record 10'], array_column(Table::sort($rows, $leaves, ['key' => 'nama', 'direction' => 'asc']), 'nama'));
        $this->assertSame([3, 2, 1], array_column(Table::sort($rows, $leaves, ['key' => 'n', 'direction' => 'desc']), 'n'));
        $this->assertSame([3, 2, 1], array_column(Table::sort($rows, $leaves, ['key' => 'balik', 'direction' => 'asc']), 'n'));
        // Tanpa urutan, atau kolom yang tidak dikenal: apa adanya.
        $this->assertSame($rows, Table::sort($rows, $leaves, null));
        $this->assertSame($rows, Table::sort($rows, $leaves, ['key' => 'tidak-ada', 'direction' => 'asc']));
    }

    public function test_sort_fn_menang_atas_sort_value(): void
    {
        $rows = [['a' => 'x'], ['a' => 'yy'], ['a' => 'z']];
        $leaves = [['key' => 'a', 'sort-value' => fn () => 0, 'sort-fn' => fn ($l, $r) => strlen($r['a']) <=> strlen($l['a'])]];

        $this->assertSame('yy', Table::sort($rows, $leaves, ['key' => 'a', 'direction' => 'asc'])[0]['a']);
    }

    public function test_urutan_dari_properti_livewire_dirapikan(): void
    {
        $this->assertSame(['key' => 'nama', 'direction' => 'desc'], Table::sortState(['key' => 'nama', 'direction' => TableSortDirection::Desc]));
        $this->assertNull(Table::sortState(null));
        $this->assertNull(Table::sortState(['key' => 'nama', 'direction' => 'acak']));
        $this->assertNull(Table::sortState(['key' => '', 'direction' => 'asc']));
    }

    public function test_nilai_sel_dan_kunci_baris(): void
    {
        $this->assertSame('—', Table::format(null));
        $this->assertSame('—', Table::format(''));
        $this->assertSame('true', Table::format(true));
        $this->assertSame('{"a":1}', Table::format(['a' => 1]));
        $this->assertSame('3', Table::format(3));

        $this->assertSame(7, Table::key(['id' => 7], 'id'));
        $this->assertSame('abc', Table::key((object) ['kode' => 'abc'], 'kode'));
        $this->assertSame('k-1', Table::key(['id' => 1], fn ($row) => 'k-'.$row['id']));
    }

    public function test_jenis_berkas_ditebak_dari_ekstensi(): void
    {
        $this->assertSame('pdf', Table::fileType('Document.PDF'));
        $this->assertSame('image', Table::fileType('/foto/a.jpeg?v=2'));
        $this->assertSame('file', Table::fileType('Surat.docx'));
        $this->assertSame('file', Table::fileType('tanpa-ekstensi'));
    }
}
