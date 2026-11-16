<?php

namespace App\Docs;

/**
 * Mode gelap situs dokumentasi — padanan docsTheme.ts di docs React.
 *
 * Terpisah dari tema komponen kit. Kit memakai `data-theme="dark"`, dan
 * atribut itu mengganti nilai token (surface, border, field, …) untuk seluruh
 * isinya: dipasang di <html>, komponen tanpa `dark-mode` ikut gelap
 * setengah-setengah. Karena itu docs memakai atributnya sendiri,
 * `data-docs-theme`, yang hanya dibaca varian `dark:` milik situs (lihat
 * resources/css/docs.css). Komponen kit tidak membacanya, jadi pratinjau
 * komponen baru gelap bila halamannya memberi prop `dark-mode`.
 *
 * Pilihan pembaca disimpan di cookie `ceplok_docs_theme` yang ditulis
 * JavaScript (resources/js/docs/theme.js), jadi cookie ini dikecualikan dari
 * enkripsi di bootstrap/app.php. Server membacanya, sehingga HTML pertama
 * sudah memakai mode yang benar, termasuk `dark-mode` pada pratinjaunya.
 */
final class Theme
{
    public const COOKIE = 'ceplok_docs_theme';

    /**
     * Halaman yang sudah punya tampilan gelap. Mode gelap dicoba di sebagian
     * halaman dulu. Di halaman lain situs tetap terang dan tombolnya
     * diredupkan, sedangkan pilihan pembaca tetap disimpan untuk halaman yang
     * sudah siap.
     *
     * Menambah halaman: beri elemen halamannya varian `dark:`, beri
     * `dark-mode` pada komponen kit di pratinjaunya (cuplikan kodenya ikut
     * menuliskannya lewat penanda ⟨…⟩, lihat App\Docs\Code), lalu daftarkan
     * rutenya di sini.
     */
    private const PAGES = [
        'home',
        'components.index',
        'components.alert',
        'components.toast',
        'components.timeline',
        'form.index',
        'form.input-field',
        'form.input-field.input',
        'form.input-field.floating-label',
        'form.input-field.text-area',
        'form.select',
        'form.search',
        'form.datepicker',
        'form.upload',
        'form.radio',
        'form.toggle',
        'form.checkbox',
        'foundations.index',
        'foundations.spacing',
        'foundations.border',
        'foundations.elevation',
        'foundations.icons',
        'portal.index',
        'portal.app',
    ];

    /** @return list<string> */
    public static function pages(): array
    {
        return self::PAGES;
    }

    /**
     * Halaman ini sudah punya tampilan gelap. Tanpa argumen yang diperiksa
     * rute request saat ini, jadi panggil dari render halaman, bukan dari
     * request pembaruan Livewire.
     */
    public static function available(?string $route = null): bool
    {
        $route ??= request()->route()?->getName();

        return in_array($route, self::PAGES, true);
    }

    /** Pilihan pembaca, terlepas dari apakah halamannya sudah mendukung. */
    public static function preferred(): bool
    {
        return request()->cookie(self::COOKIE) === 'dark';
    }

    /**
     * Mode gelap yang benar-benar berlaku di halaman yang sedang dirender.
     * Halaman memakainya untuk memberi `dark-mode` pada pratinjau; Playground
     * menyimpannya di properti saat mount, karena request pembaruan Livewire
     * tidak membawa rute halamannya.
     */
    public static function dark(): bool
    {
        return self::preferred() && self::available();
    }
}
