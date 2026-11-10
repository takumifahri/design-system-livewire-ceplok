/**
 * Tirai transisi antara pintu masuk contoh aplikasi (mis. /portal-dashboard)
 * dan aplikasinya yang penuh layar (/portal-dashboard/app) — padanan
 * pages/example/transition.ts di docs React, dengan Web Animations API
 * alih-alih GSAP. Bukan bagian dari kit.
 *
 * Masuk: gelembung biru tumbuh dari tombol sampai menutup layar, halaman
 * berpindah, lalu gelembung di aplikasi mengempis ke tengah layar. Pulang:
 * panel menyapu naik dari bawah, halaman berpindah, lalu panel di pintu masuk
 * melanjutkan sapuannya keluar lewat atas. Arah pulang sengaja bukan gelembung
 * yang dibalik: satu gerakan menaik terbaca sebagai "keluar", bukan "masuk"
 * yang diputar mundur.
 *
 * Kedua halaman berpindah lewat wire:navigate, jadi halaman tujuan dirender
 * server tanpa tahu dari mana ia didatangi. Penandanya dipasang di <html>
 * lewat onSwap, yang dijalankan Livewire tepat setelah body baru terpasang
 * dan sebelum cat pertamanya; docs.css lalu menutup layar dengan tirai
 * sebelum isi halaman sempat terlihat. Membuka halaman langsung dari URL atau
 * me-refresh-nya tidak membawa penanda itu, jadi halaman tampil apa adanya —
 * sama dengan `asal` di docs React.
 */
import { reducedMotion } from './motion';

/**
 * Kurva `sine.inOut` GSAP: tanpa puncak percepatan yang tajam, jadi bidang
 * selebar layar tidak pernah bergerak menyentak.
 */
const SINE_IN_OUT = 'cubic-bezier(0.37, 0, 0.63, 1)';
const SINE_IN = 'cubic-bezier(0.12, 0, 0.39, 0)';

/** Klik dengan Ctrl/⌘/Shift atau tombol tengah membuka tab baru; itu urusan browser. */
const klikBiasa = (event) => event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey;

/** Pindah halaman lewat wire:navigate, dan pasang `tanda` di halaman tujuan sebelum ia tampil. */
function pindah(url, tanda = null) {
    if (tanda) {
        document.addEventListener(
            'livewire:navigating',
            (event) =>
                event.detail.onSwap(() => {
                    document.documentElement.dataset.tirai = tanda;
                }),
            { once: true },
        );
    }

    window.Livewire.navigate(url);
}

/** Titik tengah elemen, supaya gelembung tumbuh dari tombolnya. */
function titikTengah(el) {
    const box = el.getBoundingClientRect();

    return { x: box.left + box.width / 2, y: box.top + box.height / 2 };
}

/**
 * `x-data="docsTirai"` di pembungkus pintu masuk maupun aplikasinya. Isinya
 * `x-ref="konten"` (yang memudar saat berangkat), `x-ref="gelembung"`, dan
 * `x-ref="panel"`; tautan berangkatnya memanggil `masuk($event)` atau
 * `pulang($event)`.
 */
export default function docsTirai() {
    return {
        /**
         * Tautan mati begitu ditekan sekali: klik kedua akan memulai animasi
         * dari awal dan menitipkan satu perpindahan halaman lagi.
         */
        berangkat: false,

        init() {
            const tanda = document.documentElement.dataset.tirai;
            if (!tanda) return;

            // Animasinya dimulai dulu, baru penandanya dicabut: fill `both`
            // menahan keadaan awal animasi, jadi layar tetap tertutup selama jedanya.
            if (!reducedMotion()) {
                if (tanda === 'masuk' && this.$refs.gelembung) this.bukaGelembung();
                if (tanda === 'pulang' && this.$refs.panel) this.bukaPanel();
            }

            delete document.documentElement.dataset.tirai;
        },

        /** Dari pintu masuk ke aplikasinya. */
        masuk(event) {
            if (!klikBiasa(event)) return;
            event.preventDefault();
            if (this.berangkat) return;
            this.berangkat = true;

            const url = event.currentTarget.href;
            if (reducedMotion()) return pindah(url);

            // Konten menyusut sedikit sambil memudar, jadi terasa ditelan gelembung.
            this.$refs.konten?.animate([{ opacity: 1, scale: 1 }, { opacity: 0, scale: 0.96 }], {
                duration: 550,
                easing: SINE_IN,
                fill: 'forwards',
            });

            const { x, y } = titikTengah(event.currentTarget);
            const pusat = `calc(${x}px - 50%) calc(${y}px - 50%)`;

            this.$refs.gelembung
                .animate(
                    [
                        { translate: pusat, scale: 0 },
                        { translate: pusat, scale: 1 },
                    ],
                    { duration: 1000, delay: 120, easing: SINE_IN_OUT, fill: 'forwards' },
                )
                .finished.then(() => pindah(url, 'masuk'));
        },

        /** Dari aplikasi kembali ke pintu masuknya. */
        pulang(event) {
            if (!klikBiasa(event)) return;
            event.preventDefault();
            if (this.berangkat) return;
            this.berangkat = true;

            const url = event.currentTarget.href;
            if (reducedMotion()) return pindah(url);

            // Konten ikut terangkat sedikit, jadi terasa didorong panel.
            this.$refs.konten?.animate(
                [
                    { opacity: 1, translate: '0 0' },
                    { opacity: 0, translate: '0 -28px' },
                ],
                { duration: 500, easing: SINE_IN, fill: 'forwards' },
            );

            this.$refs.panel
                .animate([{ translate: '0 100%' }, { translate: '0 0' }], {
                    duration: 700,
                    delay: 80,
                    easing: SINE_IN_OUT,
                    fill: 'forwards',
                })
                .finished.then(() => pindah(url, 'pulang'));
        },

        /**
         * Gelembung yang menutup layar mengempis ke tengah. Titik klik ada di
         * halaman sebelumnya, jadi tengah layar yang dipakai.
         */
        bukaGelembung() {
            const pusat = 'calc(50vw - 50%) calc(50vh - 50%)';

            this.$refs.gelembung.animate(
                [
                    { translate: pusat, scale: 1 },
                    { translate: pusat, scale: 0 },
                ],
                { duration: 950, delay: 120, easing: SINE_IN_OUT, fill: 'both' },
            );
        },

        /** Panel yang menutup layar melanjutkan sapuannya keluar lewat atas. */
        bukaPanel() {
            this.$refs.panel.animate([{ translate: '0 0' }, { translate: '0 -100%' }], {
                duration: 800,
                delay: 50,
                easing: SINE_IN_OUT,
                fill: 'both',
            });
        },
    };
}
