<?php

namespace Tests\Unit\Kit;

use CeplokUi\DesignKit\Support\Dates;
use DateTimeImmutable;
use DateTimeZone;
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

    public function test_time_menerima_waktu_iso_dan_datetime_dengan_zona_waktunya(): void
    {
        $this->assertSame('2026-01-01 09:05', Dates::time('2026-01-01 09:05')?->format('Y-m-d H:i'));
        $this->assertSame('2026-01-01T09:05:30+07:00', Dates::time('2026-01-01T09:05:30+07:00')?->format(DATE_ATOM));
        $this->assertSame('2026-01-01T09:05:00+07:00', Dates::time('2026-01-01T09:05+0700')?->format(DATE_ATOM));
        $this->assertSame('2026-03-17T23:30:00+00:00', Dates::time('2026-03-17T23:30:00.000000Z')?->format(DATE_ATOM));

        $jayapura = new DateTimeImmutable('2026-01-01 23:59', new DateTimeZone('Asia/Jayapura'));

        $this->assertSame('2026-01-01T23:59:00+09:00', Dates::time($jayapura)?->format(DATE_ATOM));
    }

    /** @return array<string, array{mixed}> */
    public static function bukanWaktu(): array
    {
        return [
            'tanpa jam' => ['2026-01-01'],
            'urutan hari dan bulan rancu' => ['01/02/2026'],
            'teks tampilan' => ['01 Jan 2026 · 09:05'],
            'tanggal yang tidak ada' => ['2026-02-30 09:00'],
            'jam 24' => ['2026-01-01 24:00'],
            'kata relatif' => ['now'],
            'angka' => [1767258300],
            'null' => [null],
        ];
    }

    #[DataProvider('bukanWaktu')]
    public function test_time_menolak_selain_waktu_iso_dengan_jam(mixed $value): void
    {
        $this->assertNull(Dates::time($value));
    }

    public function test_waktu_timeline_ditulis_dengan_format_tetap(): void
    {
        $natal = new DateTimeImmutable('2026-12-25 15:45');

        $this->assertSame('07 Aug 2026 · 07:05', Dates::writeTime(new DateTimeImmutable('2026-08-07 07:05')));
        $this->assertSame('25 Des 2026 · 15:45', Dates::writeTime($natal, ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']));
        $this->assertSame('25 Dec 2026 · 15:45', Dates::writeTime($natal, ['Jan', 'Feb']));
    }
}
