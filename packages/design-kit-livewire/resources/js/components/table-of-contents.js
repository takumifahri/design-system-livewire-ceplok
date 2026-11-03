/**
 * Table of Contents, padanan scroll-spy, ActiveIndicator, dan Drawer daftar
 * isi di kit React.
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
 * - `data-drawer-contained`: Drawer berupa panel di dalam bingkai, bukan
 *   <dialog> modal; fokusnya diurus di sini.
 * Id tiap entri ada di `data-id`-nya. Daftar biasa dan daftar di Drawer
 * ditandai `data-toc-list="inline"` dan `"drawer"`, masing-masing dengan
 * garis `data-toc-indicator`.
 *
 * Daftar di Drawer berada di dalam x-data <x-ceplok::drawer>, jadi method di
 * sini juga dipanggil dari lingkup itu. Karena itu elemen <nav> disimpan saat
 * init, bukan dibaca dari `$root` atau `$refs`.
 *
 * Event yang dikirim dari <nav>:
 * - `active-change` dengan `detail.id`, saat sorotan berpindah karena
 *   scroll-spy atau klik (onActiveChange). Tidak dikirim saat halaman dimuat,
 *   supaya `wire:active-change` tidak memicu request di setiap kunjungan.
 * - `item-click` dengan `detail.id`, sebelum tautan berjalan (onItemClick).
 *   Event ini bisa dibatalkan: preventDefault juga membatalkan tautannya.
 */
export default function tableOfContents() {
    // Elemen <nav>; disimpan di luar data Alpine supaya tidak dibungkus proxy
    // reaktif, yang membuat method DOM-nya gagal dipanggil.
    let nav = null;

    return {
        active: '',
        drawerOpen: false,
        animated: { inline: false, drawer: false },

        // Diisi di init. Semuanya harus dideklarasikan di sini: Alpine
        // menaruh properti baru yang belum ada di lingkup mana pun ke lingkup
        // x-data terluar, sehingga dipakai bersama oleh semua Table of
        // Contents di halaman itu.
        offset: 96,
        anchor: true,
        controlled: false,
        spied: false,
        contained: false,
        drawerUsed: false,
        cleanups: [],
        stopSpy: null,
        frames: {},

        init() {
            nav = this.$root;
            const data = nav.dataset;
            this.offset = Number(data.offset ?? 96);
            this.anchor = data.anchor !== 'false';
            this.controlled = data.activeId !== undefined;
            this.spied = this.anchor && data.scrollSpy !== 'false' && !this.controlled;
            this.contained = data.drawerContained !== undefined;
            this.drawerUsed = false;
            this.cleanups = [];
            this.stopSpy = null;
            this.frames = {};

            this.active = this.controlled ? data.activeId : (this.ids()[0] ?? '');

            // Mode terkontrol: render ulang Livewire hanya mengganti atribut,
            // jadi atributnya diamati untuk mengikuti nilai barunya.
            if (this.controlled) {
                const observer = new MutationObserver(() => {
                    if (nav.dataset.activeId !== undefined) this.active = nav.dataset.activeId;
                });
                observer.observe(nav, { attributes: true, attributeFilter: ['data-active-id'] });
                this.cleanups.push(() => observer.disconnect());
            }

            this.$watch('active', () => this.$nextTick(() => this.refresh()));

            // Panel di dalam bingkai: fokus pindah ke panel begitu terbuka, dan
            // kembali ke tombol pembukanya saat tertutup. <dialog> asli mengurus
            // ini sendiri. `drawerUsed` mencegah tombolnya difokus saat dimuat.
            this.$watch('drawerOpen', (open) => {
                if (open) this.drawerUsed = true;
                this.$nextTick(() => {
                    this.measure();
                    if (!this.contained || !this.drawerUsed) return;
                    if (open) nav.querySelector('[data-toc-panel]')?.focus();
                    else nav.querySelector('[data-toc-trigger]')?.focus();
                });
            });

            // Ukuran daftar berubah (font termuat, label memanjang, breakpoint
            // berganti, Drawer terbuka): ukur ulang posisi garisnya.
            if (typeof ResizeObserver !== 'undefined') {
                const resize = new ResizeObserver(() => this.measure());
                this.lists().forEach((list) => resize.observe(list));
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
            this.lists().forEach((list) => entries.observe(list, { childList: true, subtree: true }));
            this.cleanups.push(() => entries.disconnect());

            this.watchSections();
            this.$nextTick(() => this.refresh());
        },

        destroy() {
            this.stopSpy?.();
            this.cleanups.forEach((cleanup) => cleanup());
            Object.values(this.frames).forEach((frame) => cancelAnimationFrame(frame));
        },

        /** Daftar biasa dan daftar di Drawer yang ada di layout ini. */
        lists() {
            return [...nav.querySelectorAll('[data-toc-list]')];
        },

        /** Id tiap entri, urut seperti di daftar. */
        ids() {
            const list = nav.querySelector('[data-toc-list]');
            return list ? [...list.querySelectorAll('[data-id]')].map((el) => el.dataset.id) : [];
        },

        container() {
            const selector = nav.dataset.scrollContainer;
            return selector ? document.querySelector(selector) : null;
        },

        refresh() {
            this.measure();
            this.follow();
            this.label();
        },

        openDrawer() {
            this.drawerOpen = true;
        },

        closeDrawer() {
            this.drawerOpen = false;
        },

        /** Memindahkan sorotan karena scroll-spy atau klik (bukan mode terkontrol). */
        select(id) {
            if (this.controlled || !id || id === this.active) return;
            this.active = id;
            nav.dispatchEvent(new CustomEvent('active-change', { detail: { id }, bubbles: true }));
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

        /** Mengirim `item-click`; true bila pendengarnya membatalkan tautan. */
        clicked(id) {
            const click = new CustomEvent('item-click', { detail: { id }, bubbles: true, cancelable: true });
            nav.dispatchEvent(click);
            return click.defaultPrevented;
        },

        /** Menggulir wadah `scroll-container` sampai bagiannya tepat di bawah offset. */
        scrollContainerTo(root, el) {
            root.scrollTo({
                top: el.getBoundingClientRect().top - root.getBoundingClientRect().top + root.scrollTop - this.offset + 1,
                behavior: 'smooth',
            });
        },

        /** Klik entri daftar biasa: tautan (anchor) atau tombol yang hanya memindahkan sorotan. */
        choose(event, id) {
            if (this.clicked(id)) {
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
            this.scrollContainerTo(root, el);
        },

        /**
         * Klik entri di Drawer. Tautannya tidak dibiarkan berjalan sendiri:
         * selama dialog modal terbuka, halaman di belakangnya tidak bisa
         * digulir. Drawer ditutup dulu, baru berpindah ke bagiannya.
         */
        chooseInDrawer(event, link) {
            const id = link.dataset.id;
            const handled = this.clicked(id);
            event.preventDefault();
            this.closeDrawer();
            if (handled) return;
            if (!this.anchor) {
                this.select(id);
                return;
            }
            const href = link.getAttribute('href');
            setTimeout(() => this.goTo(id, href), 0);
        },

        /** Berpindah ke bagian setelah Drawer tertutup. */
        goTo(id, href) {
            const root = this.container();
            const el = document.getElementById(id);
            if (root && el) {
                this.scrollContainerTo(root, el);
            } else if (!href.startsWith('#') || window.location.hash !== href) {
                // Untuk alamat `#…` ini navigasi di halaman yang sama dan memicu hashchange.
                window.location.assign(href);
            } else {
                // Alamat yang sama tidak memicu hashchange, jadi gulir langsung.
                el?.scrollIntoView({ behavior: 'smooth' });
            }
        },

        /**
         * Memasang garis brand di entri ber-aria-current pada tiap daftar.
         * Diukur dari entrinya, jadi tetap pas walau label memanjang dua
         * baris. Daftar yang tersembunyi (Drawer tertutup, breakpoint lain)
         * tidak punya ukuran: garisnya disembunyikan dan transisinya dimatikan
         * sampai daftar itu terlihat lagi, lalu baru dinyalakan setelah posisi
         * pertama terpasang supaya garisnya tidak meluncur dari atas.
         */
        measure() {
            this.lists().forEach((list) => {
                const variant = list.dataset.tocList;
                const bar = list.querySelector('[data-toc-indicator]');
                const link = list.querySelector('[aria-current]');
                if (!bar) return;
                if (!link || !link.offsetHeight) {
                    bar.style.display = 'none';
                    this.animated[variant] = false;
                    return;
                }

                bar.style.display = '';
                bar.style.height = `${link.offsetHeight}px`;
                bar.style.transform = `translateY(${link.offsetTop}px)`;

                if (!this.animated[variant] && !this.frames[variant]) {
                    this.frames[variant] = requestAnimationFrame(() => {
                        this.frames[variant] = 0;
                        this.animated[variant] = true;
                    });
                }
            });
        },

        /**
         * Daftar yang bisa digulir (`max-height`) mengikuti entri aktif.
         * scrollTop diatur langsung, bukan scrollIntoView, supaya halamannya
         * sendiri tidak ikut tergulir.
         */
        follow() {
            const list = nav.querySelector('[data-toc-list="inline"]');
            if (!list || list.scrollHeight <= list.clientHeight) return;
            const link = list.querySelector('[aria-current]');
            if (!link) return;
            const top = link.offsetTop;
            if (top < list.scrollTop) list.scrollTop = top;
            else if (top + link.offsetHeight > list.scrollTop + list.clientHeight) {
                list.scrollTop = top + link.offsetHeight - list.clientHeight;
            }
        },

        /** Tombol pembuka bawaan menampilkan label entri aktif. */
        label() {
            const current = nav.querySelector('[data-toc-current]');
            const link = nav.querySelector('[data-toc-list] [aria-current]');
            if (current && link) current.textContent = link.textContent;
        },
    };
}
