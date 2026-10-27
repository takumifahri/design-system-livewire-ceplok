<?php

namespace CeplokUi\DesignKit\Support;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Tanggal untuk Datepicker: membaca nilai dari Blade/Livewire dan menulis
 * teks kotaknya, padanan tulis dan tulisRentang di kit React. Tanggal
 * disimpan sebagai `YYYY-MM-DD`, tanpa jam dan zona waktu.
 *
 * Juga waktu kejadian Timeline (`time` dan `writeTime`), padanan formatTime
 * di kit React.
 */
final class Dates
{
    private const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    /** Singkatan bulan bawaan Timeline: bahasa Inggris, seperti label lain dari teks desain. */
    private const SHORT_MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    /**
     * Waktu kejadian Timeline dari DateTimeInterface atau string ISO 8601 yang
     * memuat jam, mis. `2026-01-01 09:05` atau `2026-01-01T09:05:00+07:00`;
     * null bila bukan waktu. String tanpa zona waktu dibaca di zona waktu
     * aplikasi. Format lain, mis. `01/02/2026`, ditolak karena urutan hari dan
     * bulannya rancu.
     */
    public static function time(mixed $value): ?DateTimeImmutable
    {
        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        if (! is_string($value) || preg_match('/^(\d{4})-(\d{2})-(\d{2})[T ](\d{2}):(\d{2})(?::(\d{2})(?:\.\d+)?)?(?:Z|[+-]\d{2}(?::?\d{2})?)?$/', trim($value), $m) !== 1) {
            return null;
        }

        if (! checkdate((int) $m[2], (int) $m[3], (int) $m[1]) || (int) $m[4] > 23 || (int) $m[5] > 59 || (int) ($m[6] ?? 0) > 59) {
            return null;
        }

        return new DateTimeImmutable(trim($value));
    }

    /**
     * "01 Jan 2026 · 09:05": format tetap waktu kejadian Timeline, ditulis di
     * zona waktu nilainya sendiri. Bulan yang tidak ada di `$months` memakai
     * singkatan bawaan.
     *
     * @param  array<int, string>  $months  dua belas singkatan bulan, dimulai Januari
     */
    public static function writeTime(DateTimeInterface $time, array $months = []): string
    {
        $month = (int) $time->format('n') - 1;

        return $time->format('d').' '.($months[$month] ?? self::SHORT_MONTHS[$month]).' '.$time->format('Y').' · '.$time->format('H:i');
    }

    /** `YYYY-MM-DD` dari string tanggal atau DateTimeInterface; null bila bukan tanggal. */
    public static function iso(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_string($value) && preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $value, $m) === 1 && checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
            return "{$m[1]}-{$m[2]}-{$m[3]}";
        }

        return null;
    }

    /**
     * Rentang `['start' => …, 'end' => …]` dari array, objek, atau null.
     * Rentang yang kedua sisinya null tetap rentang (Semua Waktu tanpa batas).
     *
     * @return array{start: ?string, end: ?string}|null
     */
    public static function range(mixed $value): ?array
    {
        if ($value === null || $value === '' || $value === []) {
            return null;
        }

        $value = is_object($value) && ! $value instanceof DateTimeInterface ? (array) $value : $value;

        if (! is_array($value)) {
            $day = self::iso($value);

            return $day === null ? null : ['start' => $day, 'end' => $day];
        }

        return ['start' => self::iso($value['start'] ?? null), 'end' => self::iso($value['end'] ?? null)];
    }

    /** "17 Agustus 1945". */
    public static function write(string $iso): string
    {
        $date = new DateTimeImmutable($iso);

        return $date->format('j').' '.self::BULAN[(int) $date->format('n') - 1].' '.$date->format('Y');
    }

    /** Rentang dalam satu baris; bulan dan tahun yang sama tidak ditulis dua kali. */
    public static function writeRange(string $start, string $end): string
    {
        if ($start === $end) {
            return self::write($start);
        }

        $a = new DateTimeImmutable($start);
        $b = new DateTimeImmutable($end);

        return match (true) {
            $a->format('Y') !== $b->format('Y') => self::write($start).' – '.self::write($end),
            $a->format('n') !== $b->format('n') => $a->format('j').' '.self::BULAN[(int) $a->format('n') - 1].' – '.self::write($end),
            default => $a->format('j').'–'.self::write($end),
        };
    }
}
