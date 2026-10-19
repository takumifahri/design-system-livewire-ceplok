<?php

namespace Tests\Unit\Kit;

use CeplokUi\DesignKit\Support\Dates;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DatesTest extends TestCase
{
    public function test_iso_menerima_string_dan_datetime(): void
    {
        $this->assertSame('2026-08-17', Dates::iso('2026-08-17'));
        $this->assertSame('2026-08-17', Dates::iso('2026-08-17 13:45:00'));
        $this->assertSame('2026-08-17', Dates::iso(new DateTimeImmutable('2026-08-17 23:59')));
        $this->assertNull(Dates::iso('2026-02-30'));
        $this->assertNull(Dates::iso('17/08/2026'));
        $this->assertNull(Dates::iso(null));
    }

    public function test_range_membedakan_kosong_dari_semua_waktu(): void
    {
        $this->assertNull(Dates::range(null));
        $this->assertNull(Dates::range([]));
        $this->assertSame(['start' => null, 'end' => null], Dates::range(['start' => null, 'end' => null]));
        $this->assertSame(['start' => '2026-08-10', 'end' => null], Dates::range(['start' => '2026-08-10']));
        $this->assertSame(['start' => '2026-08-17', 'end' => '2026-08-17'], Dates::range('2026-08-17'));
        $this->assertSame(['start' => '2026-01-01', 'end' => '2026-01-31'], Dates::range((object) ['start' => '2026-01-01', 'end' => '2026-01-31']));
    }

    public function test_tanggal_ditulis_dengan_nama_bulan_indonesia(): void
    {
        $this->assertSame('17 Agustus 1945', Dates::write('1945-08-17'));
        $this->assertSame('1 Januari 2026', Dates::write('2026-01-01'));
    }

    /** @return array<string, array{string, string, string}> */
    public static function rentang(): array
    {
        return [
            'satu hari' => ['2026-08-17', '2026-08-17', '17 Agustus 2026'],
            'bulan sama' => ['2026-08-10', '2026-08-17', '10–17 Agustus 2026'],
            'bulan beda' => ['2026-07-28', '2026-08-03', '28 Juli – 3 Agustus 2026'],
            'tahun beda' => ['2025-12-28', '2026-01-03', '28 Desember 2025 – 3 Januari 2026'],
        ];
    }

    #[DataProvider('rentang')]
    public function test_rentang_tidak_menulis_bulan_dan_tahun_yang_sama_dua_kali(string $start, string $end, string $expected): void
    {
        $this->assertSame($expected, Dates::writeRange($start, $end));
    }
}
