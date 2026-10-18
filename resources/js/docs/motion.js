/**
 * Primitif gerak situs dokumentasi — padanan docs/motion di docs React, tanpa
 * GSAP. Animasinya memakai Web Animations API dan transisi CSS; gayanya di
 * resources/css/docs.css. Bukan bagian dari kit.
 *
 * Pengguna yang meminta gerak dikurangi tidak mendapat animasi sama sekali:
 * elemen langsung tampil di keadaan akhirnya. Efek yang mengikuti pointer
 * (magnetic, tilt) dimatikan di layar sentuh.
 */

export const reducedMotion = () => matchMedia('(prefers-reduced-motion: reduce)').matches;
const coarsePointer = () => matchMedia('(pointer: coarse)').matches;

/** Kurva yang dipakai GSAP di docs React. */
const EASE = {
    power2: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
    power3: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
    power4: 'cubic-bezier(0.165, 0.84, 0.44, 1)',
};

/** Menjalankan `callback` sekali saat `el` masuk viewport sejauh `start` (mis. 0.85 = 85% tinggi layar). */
function onceVisible(el, start, callback) {
    const observer = new IntersectionObserver(
        (entries) => {
            if (entries.some((entry) => entry.isIntersecting)) {
                observer.disconnect();
                callback();
            }
        },
        { rootMargin: `0px 0px -${Math.round((1 - start) * 100)}% 0px` },
    );
    observer.observe(el);

    return () => observer.disconnect();
}

/**
 * `x-reveal` — elemen (atau tiap anaknya, dengan `data-stagger`) naik dan
 * memudar masuk saat pertama kali terlihat. `data-delay` dalam detik.
 * Sebelum terlihat, CSS menyembunyikannya (lihat `.ds-js [x-reveal]`).
 */
export function reveal(el, _directive, { cleanup }) {
    const show = () => el.classList.add('is-revealed');
    if (reducedMotion()) return show();

    const delay = Number(el.dataset.delay ?? 0) * 1000;
    const targets = el.hasAttribute('data-stagger') ? [...el.children] : [el];

    cleanup(
        onceVisible(el, 0.85, () => {
            show();
            targets.forEach((target, i) =>
                target.animate([{ opacity: 0, transform: 'translateY(24px)' }, { opacity: 1, transform: 'none' }], {
                    duration: 700,
                    delay: delay + i * 80,
                    easing: EASE.power3,
                    fill: 'backwards',
                }),
            );
        }),
    );
}

/** `x-split-words` — tiap `[data-word]` naik dari balik garisnya, berurutan. */
export function splitWords(el) {
    el.classList.add('is-revealed');
    if (reducedMotion()) return;

    const delay = Number(el.dataset.delay ?? 0) * 1000;
    el.querySelectorAll('[data-word]').forEach((word, i) =>
        word.animate([{ opacity: 0, transform: 'translateY(115%)' }, { opacity: 1, transform: 'none' }], {
            duration: 900,
            delay: delay + i * 45,
            easing: EASE.power4,
            fill: 'backwards',
        }),
    );
}

/** `x-count-up` — angka di dalamnya berhitung dari nol saat terlihat. */
export function countUp(el, _directive, { cleanup }) {
    const value = Number(el.textContent);
    if (reducedMotion() || !Number.isFinite(value)) return;

    let frame = 0;
    cleanup(
        onceVisible(el, 0.9, () => {
            const start = performance.now();
            const tick = (now) => {
                const p = Math.min(1, (now - start) / 1600);
                el.textContent = String(Math.round(value * (1 - (1 - p) * (1 - p))));
                if (p < 1) frame = requestAnimationFrame(tick);
            };
            frame = requestAnimationFrame(tick);
        }),
    );
    cleanup(() => cancelAnimationFrame(frame));
}

/** Pembaruan per frame dari event yang bisa datang bertubi-tubi (pointer, scroll). */
function perFrame(callback) {
    let frame = 0;
    const run = (event) => {
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => callback(event));
    };
    run.cancel = () => cancelAnimationFrame(frame);

    return run;
}

/**
 * `x-magnetic="12"` — isinya (anak pertama) sedikit tertarik ke arah kursor.
 * Transisi CSS di `.ds-magnetic > *` yang menghaluskannya.
 */
export function magnetic(el, { expression }, { cleanup }) {
    if (coarsePointer() || reducedMotion()) return;

    const strength = Number(expression || 18);
    const inner = el.firstElementChild;
    const move = perFrame((event) => {
        const box = el.getBoundingClientRect();
        const x = ((event.clientX - box.left) / box.width - 0.5) * strength * 2;
        const y = ((event.clientY - box.top) / box.height - 0.5) * strength * 2;
        inner.style.transform = `translate(${x}px, ${y}px)`;
    });
    const leave = () => {
        move.cancel();
        inner.style.transform = '';
    };

    el.addEventListener('pointermove', move);
    el.addEventListener('pointerleave', leave);
    cleanup(() => {
        el.removeEventListener('pointermove', move);
        el.removeEventListener('pointerleave', leave);
        move.cancel();
    });
}

/** `x-tilt="7"` — lempeng di dalamnya (`.ds-tilt-plate`) memiringkan diri mengikuti kursor. */
export function tilt(el, { expression }, { cleanup }) {
    if (coarsePointer() || reducedMotion()) return;

    const max = Number(expression || 7);
    const plate = el.querySelector('.ds-tilt-plate');
    const move = perFrame((event) => {
        const box = el.getBoundingClientRect();
        const px = (event.clientX - box.left) / box.width - 0.5;
        const py = (event.clientY - box.top) / box.height - 0.5;
        plate.style.transform = `rotateX(${-py * max * 2}deg) rotateY(${px * max * 2}deg)`;
    });
    const leave = () => {
        move.cancel();
        plate.style.transform = '';
    };

    el.addEventListener('pointermove', move);
    el.addEventListener('pointerleave', leave);
    cleanup(() => {
        el.removeEventListener('pointermove', move);
        el.removeEventListener('pointerleave', leave);
        move.cancel();
    });
}

/** `x-scroll-progress` — garis di puncak halaman yang memanjang seiring halaman digulir. */
export function scrollProgress(el, _directive, { cleanup }) {
    if (reducedMotion()) return;

    const update = perFrame(() => {
        const max = document.documentElement.scrollHeight - innerHeight;
        el.style.transform = `scaleX(${max > 0 ? Math.min(1, scrollY / max) : 0})`;
    });
    update();
    addEventListener('scroll', update, { passive: true });
    addEventListener('resize', update);
    cleanup(() => {
        removeEventListener('scroll', update);
        removeEventListener('resize', update);
        update.cancel();
    });
}

/**
 * `x-parallax` — lapisan `[data-depth]` di dalamnya turun pelan selama elemen
 * ini digulir keluar dari layar, makin dalam makin jauh (seperti Aurora di
 * docs React). Gumpalan cahayanya bergerak sendiri lewat animasi CSS.
 */
export function parallax(el, _directive, { cleanup }) {
    if (reducedMotion()) return;

    const layers = [...el.querySelectorAll('[data-depth]')];
    const update = perFrame(() => {
        const box = el.getBoundingClientRect();
        const progress = Math.min(1, Math.max(0, -box.top / box.height));
        layers.forEach((layer) => {
            layer.style.transform = `translateY(${progress * (6 + Number(layer.dataset.depth) * 7)}%)`;
        });
    });
    update();
    addEventListener('scroll', update, { passive: true });
    cleanup(() => {
        removeEventListener('scroll', update);
        update.cancel();
    });
}

const between = (min, max) => min + Math.random() * (max - min);

/**
 * Jadwal ketukan untuk satu potongan, meniru orang yang mengetik: jeda antar
 * huruf tidak pernah sama, ada jeda kecil sesudah spasi, simbol lebih lambat
 * karena butuh Shift, sesekali ragu di tengah baris, dan jeda lebih panjang
 * sebelum Enter. Indentasi muncul sekaligus bersama Enter, seperti editor.
 */
function planTyping(lines) {
    const keys = [];
    let t = 0;

    lines.forEach((text, line) => {
        const indent = text.length - text.trimStart().length;

        if (line > 0) {
            t += between(0.14, 0.32);
            keys.push({ line, col: indent, at: t });
        }

        for (let col = indent; col < text.length; col++) {
            let d = between(0.018, 0.046);
            if (text[col - 1] === ' ') d += between(0.02, 0.06);
            if (/[<>="'{}[\]/]/.test(text[col])) d += between(0.015, 0.045);
            if (Math.random() < 0.04) d += between(0.14, 0.3);
            t += d;
            keys.push({ line, col: col + 1, at: t });
        }
    });

    return { keys, end: t };
}

/**
 * Jendela kode di hero yang mengetik sendiri isinya, memilih semua, menghapus,
 * lalu berpindah ke potongan berikutnya — terus berputar.
 *
 * Yang dianimasikan LEBAR tiap baris dalam satuan `ch`: fontnya monospace, jadi
 * lebar `n ch` memotong baris tepat setelah karakter ke-n. Semua potongan
 * dirender server (`[data-group]`, hanya yang aktif yang tampil), jadi teksnya
 * sudah ada di DOM sejak awal dan yang terbaca pembaca layar selalu potongan
 * yang lengkap. Tanpa animasi, potongan pertama tampil utuh.
 */
export function codeWindow(snippets = []) {
    return {
        snippets,
        index: 0,
        selected: false,
        timers: [],

        get snippet() {
            return this.snippets[this.index];
        },

        /** Teks polos tiap baris potongan aktif. */
        get texts() {
            return this.snippet.lines.map((tokens) => tokens.map((token) => token.t).join(''));
        },

        init() {
            if (reducedMotion() || !this.snippets.length) {
                this.$refs.code.classList.add('is-ready');
                return;
            }

            // Digambar kosong dulu sebelum CSS penahannya dilepas, supaya kode
            // utuh tidak sempat berkedip muncul lalu hilang.
            this.place(0, 0);
            this.$refs.code.classList.add('is-ready');
            this.play(true);
        },

        destroy() {
            this.timers.forEach(clearTimeout);
        },

        later(seconds, callback) {
            this.timers.push(setTimeout(callback, seconds * 1000));
        },

        /** Baris sebelum kursor utuh, baris kursor terpotong di kolomnya, sesudahnya belum ada. */
        place(line, col) {
            const group = this.$refs.code.querySelector(`[data-group="${this.index}"]`);
            const texts = this.texts;

            group.querySelectorAll('[data-line]').forEach((el, i) => {
                el.style.width = `${i < line ? texts[i].length : i === line ? col : 0}ch`;
            });
            group.querySelectorAll('[data-caret]').forEach((caret, i) => {
                caret.style.visibility = i === line ? 'visible' : 'hidden';
                if (i !== line) return;
                // Kedip diulang dari awal tiap ketukan: diam menyala selama mengetik.
                caret.style.animation = 'none';
                void caret.offsetWidth;
                caret.style.animation = '';
            });
        },

        play(first = false) {
            this.timers.forEach(clearTimeout);
            this.timers = [];
            this.place(0, 0);

            const { keys, end } = planTyping(this.texts);
            const delay = first ? 0.6 : 0.35;
            keys.forEach((key) => this.later(delay + key.at, () => this.place(key.line, key.col)));

            // Menghapus seperti orang menghapus berkas: pilih semua, jeda, hilang sekaligus.
            const hold = delay + end + 1.8;
            this.later(hold, () => (this.selected = true));
            this.later(hold + 0.45, () => {
                this.selected = false;
                this.place(0, 0);
            });
            this.later(hold + 0.75, () => {
                this.index = (this.index + 1) % this.snippets.length;
                this.$nextTick(() => this.play());
            });
        },
    };
}
