/**
 * Daftar isi "On this page": menyorot bagian yang sedang dibaca, dan menggulir
 * mulus ke bagian yang dipilih — padanan useActiveSection di docs React.
 *
 * Tautannya tetap `href="#id"` biasa, jadi alamatnya bisa disalin, dibuka di
 * tab baru, dan tetap benar setelah halaman dimuat ulang. Hanya klik biasa
 * yang dicegat untuk menggulir mulus.
 */
export default function docsToc(ids = []) {
    return {
        active: ids[0] ?? '',
        observer: null,

        init() {
            this.observer = new IntersectionObserver(
                (entries) => {
                    // Bisa ada beberapa judul yang masuk sekaligus saat menggulir
                    // cepat; yang paling atas di layar itulah yang sedang dibaca.
                    const visible = entries
                        .filter((entry) => entry.isIntersecting)
                        .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);

                    if (visible.length) this.active = visible[0].target.id;
                },
                // Pita sempit di bawah tepi atas layar, bukan seluruh viewport.
                { rootMargin: '-96px 0px -72% 0px' },
            );

            for (const id of ids) {
                const el = document.getElementById(id);
                if (el) this.observer.observe(el);
            }
        },

        destroy() {
            this.observer?.disconnect();
        },

        go(event, id) {
            const el = document.getElementById(id);
            if (!el || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button !== 0) return;

            // Entri riwayat baru tanpa state, sama seperti klik tautan #id biasa,
            // supaya tombol Back tetap ditangani wire:navigate dengan benar.
            event.preventDefault();
            history.pushState(null, '', `#${id}`);
            el.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
        },
    };
}
