/** Nama cookie mode gelap docs; dikecualikan dari enkripsi di bootstrap/app.php. */
const THEME_COOKIE = 'ceplok_docs_theme';

/**
 * Mengganti mode gelap situs dokumentasi (lihat App\Docs\Theme). Dipakai lewat
 * magic `$docsTheme(true)` atau `$docsTheme(false)`.
 *
 * Pratinjau komponen dirender server dengan `dark-mode` sesuai pilihan ini,
 * jadi halamannya dimuat ulang setelah cookie ditulis. Sengaja muat ulang
 * biasa, bukan wire:navigate: snapshot halaman yang sudah dikunjungi masih
 * memakai mode lama, dan tombol Back akan memulihkannya. Muat ulang
 * mengosongkan snapshot itu, dan posisi gulirnya dipulihkan browser.
 */
export default function setDocsTheme(dark) {
    document.cookie = `${THEME_COOKIE}=${dark ? 'dark' : 'light'}; path=/; max-age=31536000; samesite=lax`;
    window.location.reload();
}
