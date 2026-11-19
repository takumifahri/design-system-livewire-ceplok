<?php

namespace App\Docs;

use Illuminate\Support\HtmlString;

/**
 * Pengolah cuplikan kode di halaman dokumentasi (`<x-docs.code>`).
 *
 * Bagian kode yang perlu disorot ditandai `⟦…⟧` (warna aksen) atau `⟦*…⟧`
 * (aksen tebal, untuk prop yang sedang dikendalikan Playground) — padanan
 * <H> dan <Mark> di dokumentasi React. Tanda kurung ini sengaja dipilih karena
 * tidak pernah muncul di kode Blade/PHP, beda dengan `[[` yang dipakai array
 * bersarang.
 *
 * Nilai yang baru diketahui di browser — mis. lebar hasil ukur di Playground
 * Container — ditandai `⟪ekspresi|cadangan⟫`: ekspresi Alpine yang mengisi
 * angkanya, dan teks cadangan yang dirender server sebelum Alpine berjalan.
 * Tombol salin menyalin teks yang sedang tampil, jadi nilai itu ikut tersalin.
 *
 * Bagian yang hanya berlaku di satu mode docs (lihat App\Docs\Theme) ditandai
 * `⟨…⟩` (hanya di mode gelap docs, mis. ` dark-mode` yang ikut ditulis
 * pratinjaunya) atau `⟨!…⟩` (hanya di mode terang docs). Padanan `dm.inline`
 * dan `dm.line` di docs React.
 */
final class Code
{
    private const MARK = '/⟦(\*?)(.*?)⟧/su';

    private const LIVE = '/⟪(.*?)(?:\|(.*?))?⟫/su';

    private const THEME = '/⟨(!?)(.*?)⟩/u';

    /**
     * Menyisakan bagian `⟨…⟩` di mode gelap docs dan `⟨!…⟩` di mode terang,
     * lalu membuang yang lain. Baris yang hanya berisi penanda yang dibuang
     * ikut dihapus, jadi prop yang ditulis satu per baris tidak menyisakan
     * baris kosong. `$dark` kosong berarti mode halaman yang sedang dirender;
     * kode tanpa penanda tidak membacanya sama sekali.
     */
    public static function theme(string $code, ?bool $dark = null): string
    {
        if (! str_contains($code, '⟨')) {
            return $code;
        }

        $dark ??= Theme::dark();
        $lines = [];

        foreach (preg_split('/\R/', $code) ?: [] as $line) {
            $kept = (string) preg_replace_callback(
                self::THEME,
                fn (array $m) => ($m[1] === '!') !== $dark ? $m[2] : '',
                $line,
            );

            if ($kept !== $line && trim($kept) === '') {
                continue;
            }

            $lines[] = $kept;
        }

        return implode("\n", $lines);
    }

    /**
     * Membuang baris kosong di ujung dan indentasi yang sama di semua baris,
     * seperti heredoc — kode boleh ditulis menjorok mengikuti markup di sekitarnya.
     *
     * Isi slot sudah di-trim Blade, jadi indentasi baris pertamanya hilang.
     * Untuk kode dari slot (`$fromSlot`), indentasi dasarnya diukur dari baris
     * kedua dan seterusnya — biasanya ada tag penutup atau `/>` di tingkat itu.
     */
    public static function dedent(string $code, bool $fromSlot = false): string
    {
        $lines = preg_split('/\R/', $code) ?: [];

        while ($lines !== [] && trim($lines[0]) === '') {
            array_shift($lines);
        }

        while ($lines !== [] && trim($lines[array_key_last($lines)]) === '') {
            array_pop($lines);
        }

        $indent = null;

        foreach (array_slice($lines, $fromSlot ? 1 : 0) as $line) {
            if (trim($line) !== '') {
                $width = strlen($line) - strlen(ltrim($line, ' '));
                $indent = $indent === null ? $width : min($indent, $width);
            }
        }

        $result = array_map(
            fn (string $line) => rtrim(substr($line, min($indent ?? 0, strlen($line)))),
            $lines,
        );

        if ($fromSlot && $lines !== []) {
            $result[0] = trim($lines[0]);
        }

        return implode("\n", $result);
    }

    /**
     * HTML untuk ditampilkan: kode di-escape, lalu tanda sorot menjadi span
     * berwarna dan nilai hidup menjadi span ber-x-text.
     */
    public static function highlight(string $code): HtmlString
    {
        $html = preg_replace_callback(
            self::MARK,
            fn (array $m) => '<span class="'.($m[1] === '*' ? 'font-bold text-primary-300' : 'text-primary-300').'">'.$m[2].'</span>',
            e($code),
        );

        return new HtmlString((string) preg_replace_callback(
            self::LIVE,
            fn (array $m) => '<span x-text="'.$m[1].'">'.($m[2] ?? '').'</span>',
            (string) $html,
        ));
    }
}
