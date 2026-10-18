/**
 * Pencarian halaman dokumentasi — padanan DocsSearch di docs React.
 *
 * Dibuka lewat tombol Cari, Ctrl+K (⌘K di Mac), atau "/". Mengetik menyaring
 * daftar halaman; panah atas/bawah memindah pilihan, Enter membukanya, Esc
 * atau klik di luar menutupnya.
 *
 * Memakai <dialog> yang dibuka showModal(): ia naik ke top layer, fokus
 * terkunci di dalamnya, Esc ditangani peramban, dan fokus kembali ke tombol
 * pembukanya saat ditutup. `closedby="any"` menambahkan tutup lewat klik di
 * luar; peramban yang belum mendukungnya (Safari) memakai cadangan di
 * `clickBackdrop`.
 */

/** Huruf kecil tanpa tanda diakritik, supaya ketikan dan label dibandingkan setara. */
const normalize = (text) =>
    text
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .toLowerCase();

/** Apakah salah satu kata di `text` diawali `key`. */
const hasWordStart = (text, key) => text.split(/[\s/(),.›-]+/).some((word) => word.startsWith(key));

/**
 * Peringkat kecocokan, makin kecil makin atas: 0 label diawali ketikan, 1 tiap
 * kata ketikan mengawali kata di label, 2 cocok lewat letak atau kata lainnya
 * (mis. "tombol" untuk Button). `null` bila tidak cocok sama sekali.
 */
function rank(page, query) {
    const label = normalize(page.label);
    if (label.startsWith(query)) return 0;
    const words = query.split(' ');
    if (words.every((word) => hasWordStart(label, word))) return 1;
    const all = normalize(`${page.label} ${page.letak} ${page.kata}`);
    if (words.every((word) => hasWordStart(all, word))) return 2;
    return null;
}

/** Fokus sedang di tempat mengetik? Di sana "/" harus tetap jadi huruf biasa. */
const isTyping = (el) =>
    el instanceof HTMLElement && (el.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(el.tagName));

export const shortcutLabel = () => (/Mac|iPhone|iPad/.test(navigator.userAgent) ? '⌘K' : 'Ctrl K');

export default function docsSearch(pages = []) {
    return {
        pages,
        query: '',
        active: 0,
        shortcut: shortcutLabel(),

        get results() {
            const query = normalize(this.query).replace(/\s+/g, ' ').trim();
            if (!query) return this.pages;

            return this.pages
                .map((page, order) => ({ page, order, score: rank(page, query) }))
                .filter((item) => item.score !== null)
                .sort((a, b) => a.score - b.score || a.order - b.order)
                .map((item) => item.page);
        },

        get open() {
            return this.$root.open;
        },

        show() {
            if (this.open) return;
            this.$root.showModal();
            this.$refs.input.focus();
        },

        hide() {
            if (this.open) this.$root.close();
        },

        /** Dipanggil event `close` dialog — dari Esc, klik di luar, atau hide(). */
        reset() {
            this.query = '';
            this.active = 0;
        },

        /**
         * Ctrl+K / ⌘K dari mana pun, dan "/" selama tidak sedang mengetik.
         * Tombol yang sudah dipakai komponen lain sudah `defaultPrevented`
         * begitu sampai di window, jadi dilewati.
         */
        shortcutKey(event) {
            if (event.defaultPrevented) return;

            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                this.open ? this.hide() : this.show();
            } else if (event.key === '/' && !this.open && !isTyping(event.target)) {
                event.preventDefault();
                this.show();
            }
        },

        move(event) {
            const count = this.results.length;
            if (!count) return;

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.active = (this.active + 1) % count;
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.active = (this.active - 1 + count) % count;
            } else if (event.key === 'Enter') {
                event.preventDefault();
                this.visit(this.results[this.active].url);
                return;
            } else {
                return;
            }

            // Pilihan yang aktif selalu terlihat saat dipindah dengan panah.
            this.$nextTick(() => document.getElementById(`docs-search-${this.active}`)?.scrollIntoView({ block: 'nearest' }));
        },

        /** Klik dengan tombol pengubah dibuka di tab baru; jendelanya tetap. */
        click(event, url) {
            if (event.ctrlKey || event.metaKey || event.shiftKey || event.button !== 0) return;
            event.preventDefault();
            this.visit(url);
        },

        visit(url) {
            this.hide();
            window.Livewire.navigate(url);
        },

        /** Cadangan tutup-lewat-klik-di-luar untuk peramban tanpa `closedby`. */
        clickBackdrop(event) {
            if ('closedBy' in HTMLDialogElement.prototype || event.target !== this.$root) return;

            const box = this.$root.getBoundingClientRect();
            const inside = box.top <= event.clientY && event.clientY <= box.bottom && box.left <= event.clientX && event.clientX <= box.right;
            if (!inside) this.hide();
        },
    };
}
