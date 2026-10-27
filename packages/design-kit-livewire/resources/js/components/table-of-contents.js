/**
 * Table of Contents, padanan scroll-spy dan ActiveIndicator di kit React.
 *
 * `active` adalah id entri yang disorot, diikat x-modelable, jadi
 * `wire:model` atau `x-model` bisa membaca dan mengubahnya. Pengaturan lain
 * dibaca dari atribut data di <nav> (ditulis komponen Blade), bukan dari
 * ekspresi x-data, supaya ekspresinya sama di setiap render Livewire:
 * - `data-offset`: jarak dari tepi atas tempat bagian dianggap mulai dibaca.
 * - `data-scroll-spy="false"`: scroll-spy tidak dipasang.
 * - `data-anchor="false"`: entri berupa tombol yang hanya memindahkan sorotan.
 * - `data-scroll-container`: selector wadah gulir isi halaman, bila bukan window.
 * - `data-active-id`: mode terkontrol. Hanya entri ini yang disorot, dan nilai
 *   baru dari render ulang Livewire diikuti.
 * Id tiap entri ada di `data-id`-nya.
 *
 * Event yang dikirim dari <nav>:
 * - `active-change` dengan `detail.id`, saat sorotan berpindah karena
 *   scroll-spy atau klik (onActiveChange). Tidak dikirim saat halaman dimuat,
 *   supaya `wire:active-change` tidak memicu request di setiap kunjungan.
 * - `item-click` dengan `detail.id`, sebelum tautan berjalan (onItemClick).
 *   Event ini bisa dibatalkan: preventDefault juga membatalkan tautannya.
 */
export default function tableOfContents() {
    return {
        active: '',
        animate: false,

        init() {
            const data = this.$root.dataset;
            this.offset = Number(data.offset ?? 96);
            this.anchor = data.anchor !== 'false';
            this.controlled = data.activeId !== undefined;
            this.spied = this.anchor && data.scrollSpy !== 'false' && !this.controlled;
            this.cleanups = [];
            this.stopSpy = null;
            this.frame = 0;

            this.active = this.controlled ? data.activeId : (this.ids()[0] ?? '');

            // Mode terkontrol: render ulang Livewire hanya mengganti atribut,
            // jadi atributnya diamati untuk mengikuti nilai barunya.
            if (this.controlled) {
                const observer = new MutationObserver(() => {
                    if (this.$root.dataset.activeId !== undefined) this.active = this.$root.dataset.activeId;
                });
                observer.observe(this.$root, { attributes: true, attributeFilter: ['data-active-id'] });
                this.cleanups.push(() => observer.disconnect());
            }

            this.$watch('active', () => this.$nextTick(() => this.refresh()));

            // Ukuran daftar berubah (font termuat, label memanjang, daftar baru
            // terlihat): ukur ulang posisi garisnya.
            if (typeof ResizeObserver !== 'undefined') {
                const resize = new ResizeObserver(() => this.measure());
                resize.observe(this.$refs.list);
                this.cleanups.push(() => resize.disconnect());
            }

            // Entri berganti setelah render ulang Livewire: scroll-spy dipasang
            // ulang hanya bila urutan id-nya berubah, seperti di React.
            let key = this.ids().join('|');
            const entries = new MutationObserver(() => {
                const next = this.ids().join('|');
                if (next === key) return;
                key = next;
                this.watchSections();
                this.$nextTick(() => this.refresh());
            });
            entries.observe(this.$refs.list, { childList: true, subtree: true });
            this.cleanups.push(() => entries.disconnect());

            this.watchSections();
            this.$nextTick(() => this.refresh());
        },

        destroy() {
            this.stopSpy?.();
            this.cleanups.forEach((cleanup) => cleanup());
            cancelAnimationFrame(this.frame);
        },

        /** Id tiap entri, urut seperti di daftar. */
        ids() {
            return [...this.$refs.list.querySelectorAll('[data-id]')].map((el) => el.dataset.id);
        },

        container() {
            const selector = this.$root.dataset.scrollContainer;
            return selector ? document.querySelector(selector) : null;
        },

        refresh() {
            this.measure();
            this.follow();
        },

        /** Memindahkan sorotan karena scroll-spy atau klik (bukan mode terkontrol). */
        select(id) {
            if (this.controlled || !id || id === this.active) return;
            this.active = id;
            this.$dispatch('active-change', { id });
        },

        /**
         * Menyorot bagian yang paling atas di pita sempit di bawah tepi atas
         * layar atau wadah, sama seperti useScrollSpy di React.
         */
        watchSections() {
            this.stopSpy?.();
            this.stopSpy = null;

            const ids = this.ids();
            if (!this.spied || typeof IntersectionObserver === 'undefined' || !ids.length) return;

            const root = this.container();
            const last = ids[ids.length - 1];
            const atBottom = () => {
                const el = root ?? document.documentElement;
                return el.scrollHeight - el.scrollTop - el.clientHeight < 2;
            };
            const observer = new IntersectionObserver(
                (entries) => {
                    // Callback ini bisa datang sesudah pengecekan dasar wadah di
                    // bawah, mis. setelah tombol End; di dasar, bagian terakhir
                    // tetap yang disorot.
                    if (atBottom()) {
                        this.select(last);
                        return;
                    }
                    // Saat menggulir cepat bisa ada beberapa judul yang masuk
                    // sekaligus; yang paling atas di layar itulah yang dibaca.
                    const visible = entries
                        .filter((entry) => entry.isIntersecting)
                        .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);
                    if (visible.length) this.select(visible[0].target.id);
                },
                { root, rootMargin: `-${this.offset}px 0px -72% 0px` },
            );
            ids.forEach((id) => {
                const el = document.getElementById(id);
                if (el) observer.observe(el);
            });

            // Di ujung bawah wadah, bagian terakhir yang pendek tidak pernah
            // sampai ke pita atas; sorot bagian terakhir begitu wadahnya mentok.
            // Pengecekannya membaca layout, jadi paling banyak sekali per frame.
            const target = root ?? window;
            let frame = 0;
            const check = () => {
                frame = 0;
                if (atBottom()) this.select(last);
            };
            const onScroll = () => {
                if (!frame) frame = requestAnimationFrame(check);
            };
            target.addEventListener('scroll', onScroll, { passive: true });

            this.stopSpy = () => {
                observer.disconnect();
                target.removeEventListener('scroll', onScroll);
                cancelAnimationFrame(frame);
            };
        },

        /** Klik entri: tautan (anchor) atau tombol yang hanya memindahkan sorotan. */
        choose(event, id) {
            const click = new CustomEvent('item-click', { detail: { id }, bubbles: true, cancelable: true });
            this.$root.dispatchEvent(click);
            if (click.defaultPrevented) {
                event.preventDefault();
                return;
            }

            if (!this.anchor) {
                this.select(id);
                return;
            }

            // Dengan wadah gulir, tautan tidak dibiarkan mengubah alamat
            // halaman; wadahnya digulir sampai bagiannya tepat di bawah offset.
            const root = this.container();
            const el = document.getElementById(id);
            if (!root || !el) return;
            event.preventDefault();
            root.scrollTo({
                top: el.getBoundingClientRect().top - root.getBoundingClientRect().top + root.scrollTop - this.offset + 1,
                behavior: 'smooth',
            });
        },

        /**
         * Memasang garis brand di entri ber-aria-current. Diukur dari entrinya,
         * jadi tetap pas walau label memanjang dua baris. Transisi baru
         * dinyalakan sekali, setelah posisi pertama terpasang.
         */
        measure() {
            const bar = this.$refs.indicator;
            const link = this.$refs.list.querySelector('[aria-current]');
            if (!link || !link.offsetHeight) {
                bar.style.display = 'none';
                this.animate = false;
                return;
            }

            bar.style.display = '';
            bar.style.height = `${link.offsetHeight}px`;
            bar.style.transform = `translateY(${link.offsetTop}px)`;

            if (!this.animate && !this.frame) {
                this.frame = requestAnimationFrame(() => {
                    this.frame = 0;
                    this.animate = true;
                });
            }
        },

        /**
         * Daftar yang bisa digulir (`max-height`) mengikuti entri aktif.
         * scrollTop diatur langsung, bukan scrollIntoView, supaya halamannya
         * sendiri tidak ikut tergulir.
         */
        follow() {
            const list = this.$refs.list;
            if (list.scrollHeight <= list.clientHeight) return;
            const link = list.querySelector('[aria-current]');
            if (!link) return;
            const top = link.offsetTop;
            if (top < list.scrollTop) list.scrollTop = top;
            else if (top + link.offsetHeight > list.scrollTop + list.clientHeight) {
                list.scrollTop = top + link.offsetHeight - list.clientHeight;
            }
        },
    };
}
