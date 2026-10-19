/**
 * Tombol salin di halaman dokumentasi — cuplikan kode dan nilai token — dengan
 * penanda "Tersalin!" selama 1,6 detik, sama seperti useCopy di docs React.
 * Dipasang di <body>, jadi bisa dipakai dari mana pun, termasuk dari dalam
 * komponen Livewire. `id` membedakan tombol mana yang baru dipakai.
 */
export default function copy() {
    return {
        copied: null,
        copiedTimer: null,

        async copy(value, id) {
            try {
                await navigator.clipboard.writeText(value);
                this.copied = id;
                clearTimeout(this.copiedTimer);
                this.copiedTimer = setTimeout(() => (this.copied = null), 1600);
            } catch {
                // Clipboard tidak tersedia (mis. bukan HTTPS): tombolnya diam saja.
                this.copied = null;
            }
        },

        copyToken(value, id) {
            return this.copy(value, id);
        },
    };
}
