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
