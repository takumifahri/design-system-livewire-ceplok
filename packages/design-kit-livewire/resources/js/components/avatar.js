/**
 * Avatar yang fotonya gagal dimuat jatuh kembali ke inisial, seperti di kit
 * React. Alamat foto profil paling sering datang dari sistem lain dan bisa mati
 * kapan saja; tanpa ini yang tersisa hanya ikon gambar rusak.
 *
 * `load` mengembalikan foto saat Livewire mengganti `src`-nya ke alamat yang
 * berhasil, jadi kegagalan alamat lama tidak menempel ke alamat baru. Gambar
 * yang disembunyikan x-show tetap diunduh peramban, jadi event itu tetap datang.
 */
export default function avatar() {
    return {
        failedSrc: null,
        srcObserver: null,

        get failed() {
            return this.failedSrc === this.$refs.img?.getAttribute('src');
        },

        fail(src) {
            this.failedSrc = src;
        },

        loaded(src) {
            if (this.failedSrc === src) this.failedSrc = null;
        },

        init() {
            // Gambar bisa saja sudah gagal sebelum Alpine sempat memasang
            // listener `error` — misalnya alamatnya langsung ditolak server.
            const img = this.$refs.img;

            if (img && img.complete && img.naturalWidth === 0) {
                this.failedSrc = img.getAttribute('src');
            }

            // Setiap perubahan src memberi URL tujuan kesempatan baru untuk
            // dimuat, termasuk ketika kembali ke URL yang pernah gagal.
            this.srcObserver = new MutationObserver(() => {
                this.failedSrc = null;
            });
            if (img) this.srcObserver.observe(img, { attributes: true, attributeFilter: ['src'] });
        },

        destroy() {
            this.srcObserver?.disconnect();
        },
    };
}
