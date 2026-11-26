/**
 * Breadcrumb yang meringkas level tengah menjadi "…" saat tidak muat,
 * padanan useLayoutEffect + ResizeObserver di kit React.
 *
 * Server merender semua item. `ol` tak terlihat (`x-ref="measure"`) memuat
 * seluruh item dengan label yang sama, jadi lebar penuhnya tetap bisa diukur
 * walau tampilan sedang diringkas. Bila lebarnya melebihi ruang di dalam
 * `nav` (tanpa padding), `collapsed` menyembunyikan item tengah dan
 * menampilkan "…" — item pertama dan halaman aktif selalu terlihat.
 */
export default function breadcrumb() {
    return {
        collapsed: false,
        observer: null,

        init() {
            this.measure();

            if (typeof ResizeObserver === 'undefined') {
                return;
            }

            this.observer = new ResizeObserver(() => this.measure());
            this.observer.observe(this.$el);
            this.observer.observe(this.$refs.measure);
        },

        measure() {
            const style = window.getComputedStyle(this.$el);
            const padding = Number.parseFloat(style.paddingLeft) + Number.parseFloat(style.paddingRight);
            const available = this.$el.clientWidth - padding;
            const collapsed = this.$refs.measure.scrollWidth > available;

            if (collapsed !== this.collapsed) {
                this.collapsed = collapsed;
            }
        },

        destroy() {
            this.observer?.disconnect();
        },
    };
}
