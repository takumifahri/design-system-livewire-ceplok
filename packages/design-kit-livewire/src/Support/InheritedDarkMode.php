<?php

namespace CeplokUi\DesignKit\Support;

/**
 * Mode gelap yang diwariskan Table ke isinya, padanan DarkModeContext di kit
 * React: Badge dan Button theme `gray` di sel Table ber-`dark-mode` ikut gelap
 * tanpa perlu diberi `dark-mode` satu per satu. `dark-mode` yang diisi
 * langsung tetap menang, termasuk `false`.
 *
 * Bukan `@aware`: `@aware('darkMode')` membaca `dark-mode` dari pembungkus
 * apa pun, jadi tombol gray di Card atau Alert yang gelap ikut berubah,
 * padahal di React hanya Table yang mewariskan.
 *
 * Bedanya dengan React: Blade merender isi slot sebelum badan komponennya,
 * jadi slot `actions` di toolbar sudah jadi HTML saat Table memasang penanda
 * ini. Tombol gray di sana perlu `dark-mode` sendiri. Isi sel (closure
 * `cell`) dirender di badan Table, jadi ikut.
 *
 * Scoped per request, supaya penanda yang tertinggal karena render gagal
 * tidak terbawa ke request berikutnya di server berumur panjang (Octane).
 */
final class InheritedDarkMode
{
    /** @var list<bool> */
    private array $stack = [];

    /** Mulai bagian yang mewariskan mode gelap, mis. badan Table. */
    public static function push(bool $dark): void
    {
        app(self::class)->stack[] = $dark;
    }

    public static function pop(): void
    {
        array_pop(app(self::class)->stack);
    }

    /** Mode gelap dari pembungkus terdekat; false di luar Table. */
    public static function current(): bool
    {
        $stack = app(self::class)->stack;

        return $stack === [] ? false : $stack[array_key_last($stack)];
    }
}
