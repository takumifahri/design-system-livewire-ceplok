/**
 * Buka/tutup Drawer panel off-canvas — padanan komponen Drawer di kit React.
 *
 * Mengendalikan elemen <dialog> native:
 * - `open`: state yang diikat x-modelable (bisa dikendalikan via wire:model atau x-model).
 * - `closeOnOverlayClick`: klik pada backdrop (luar kotak dialog) memicu penutupan.
 * - `closeOnEsc`: tombol ESC memicu penutupan dialog.
 * - Event `close`: dikirim ke listener (@close / wire:close) saat Drawer ditutup.
 */
export default function drawer() {
    return {
        open: false,
        closeOnOverlayClick: true,
        closeOnEsc: true,

        init() {
            this.open = this.$el.dataset.initialOpen === 'true';
            this.closeOnOverlayClick = this.$el.dataset.closeOnOverlay !== 'false';
            this.closeOnEsc = this.$el.dataset.closeOnEsc !== 'false';

            this.$watch('open', (val) => {
                this.syncDialog(val);
            });

            // Seperti handleNativeClose di React: dialog yang ditutup dengan
            // cara lain (mis. form method="dialog") ikut menyetel state-nya.
            // Event `close` milik kit sendiri adalah CustomEvent, jadi dilewati.
            this.$el.addEventListener('close', (event) => {
                if (event instanceof CustomEvent || !this.open) return;
                this.open = false;
                this.$dispatch('close');
            });

            if (this.open) {
                this.$nextTick(() => {
                    this.syncDialog(true);
                });
            }
        },

        syncDialog(shouldOpen) {
            const dialog = this.$el;
            if (!dialog || typeof dialog.showModal !== 'function') return;

            if (shouldOpen) {
                if (!dialog.open) {
                    try {
                        dialog.showModal();
                    } catch {
                        // Dialog mungkin sudah terbuka.
                    }
                }
            } else {
                if (dialog.open) {
                    dialog.close();
                }
            }
        },

        handleCancel(e) {
            if (!this.closeOnEsc) {
                e.preventDefault();
                return;
            }
            e.preventDefault();
            this.close();
        },

        handleClick(e) {
            if (!this.closeOnOverlayClick || e.defaultPrevented || e.target !== this.$el) return;

            const rect = this.$el.getBoundingClientRect();
            const outsidePanel =
                e.clientX < rect.left ||
                e.clientX > rect.right ||
                e.clientY < rect.top ||
                e.clientY > rect.bottom;

            if (outsidePanel) {
                this.close();
            }
        },

        close() {
            this.open = false;
            this.syncDialog(false);
            this.$dispatch('close');
        },
    };
}

/**
 * Item menu Drawer (x-ceplok::drawer.nav-item) — padanan state expanded dan
 * activeSubId di kit React.
 *
 * - `expanded`: status submenu. Nilai awalnya dari `data-expanded`; bila
 *   server merender nilai lain (mis. properti Livewire yang diubah lewat
 *   wire:expanded-change), state ikut disinkronkan — padanan prop `expanded`
 *   terkendali. Klik tetap membuka/menutup secara lokal.
 * - Event `expanded-change` (`detail.expanded`, `detail.label`) dikirim setiap
 *   submenu dibuka/ditutup, padanan onExpandedChange.
 * - `activeSub`: sub-item tanpa `active` yang terakhir diklik.
 */
export function drawerItem() {
    return {
        expanded: false,
        activeSub: null,
        expandedObserver: null,

        init() {
            this.expanded = this.$el.dataset.expanded === 'true';
            this.expandedObserver = new MutationObserver(() => {
                this.expanded = this.$el.dataset.expanded === 'true';
            });
            this.expandedObserver.observe(this.$el, { attributes: true, attributeFilter: ['data-expanded'] });
        },

        destroy() {
            this.expandedObserver?.disconnect();
        },

        toggle(label) {
            this.expanded = !this.expanded;
            this.$dispatch('expanded-change', { expanded: this.expanded, label });
        },
    };
}
