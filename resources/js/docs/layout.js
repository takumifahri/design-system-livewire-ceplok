/** Nama cookie keadaan panel samping; dikecualikan dari enkripsi di bootstrap/app.php. */
const PANEL_COOKIE = 'ceplok_docs_panel';

/**
 * Kerangka layout dokumentasi: laci navigasi di mobile dan panel samping di
 * desktop.
 *
 * Keadaan panel (buka/tutup) disimpan di cookie, bukan localStorage, supaya
 * server sudah merender lebar panel yang benar sejak HTML pertama — tidak ada
 * panel yang sempat terbuka lalu menutup sendiri saat halaman dimuat.
 */
export default function docsLayout(panel = true) {
    return {
        drawer: false,
        panel,

        togglePanel() {
            this.panel = !this.panel;
            document.cookie = `${PANEL_COOKIE}=${this.panel ? 1 : 0}; path=/; max-age=31536000; samesite=lax`;
        },
    };
}
