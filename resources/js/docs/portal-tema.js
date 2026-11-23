/*
 * Toggle tema Portal Dashboard — padanan toggle di kaki Sidebar dan di header
 * mobile docs React.
 *
 * Mode gelap docs Livewire berlaku lewat muat ulang halaman (lihat theme.js).
 * Supaya pergantiannya tetap terasa halus, pilihan baru digeser dulu di
 * browser, lalu dikabarkan ke bingkai docs lewat event `dark-mode-change`
 * setelah transisinya selesai. Bila gerak diminta dikurangi, pilihan langsung
 * dikabarkan.
 */
const DURASI = 300;

export default (awal = false) => ({
    gelap: awal,
    terkirim: false,

    pilih(gelap) {
        if (this.terkirim || gelap === this.gelap) return;

        this.gelap = gelap;
        this.terkirim = true;

        const kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        setTimeout(() => this.$dispatch('dark-mode-change', { darkMode: gelap }), kurangiGerak ? 0 : DURASI);
    },
});
