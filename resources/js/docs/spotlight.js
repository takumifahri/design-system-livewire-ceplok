/**
 * `x-spotlight` — sorotan lembut yang mengikuti kursor, plus tepi yang ikut
 * menyala di sisi terdekat pointer (gayanya di resources/css/docs.css,
 * `.ds-spotlight`). Port SpotlightCard di docs React.
 *
 * Yang berubah hanya dua custom property (`--mx`, `--my`) yang dibaca gradien
 * di CSS, dijepit ke satu frame lewat rAF supaya rentetan `pointermove` tidak
 * memaksa gaya dihitung ulang berkali-kali dalam satu frame. Layar sentuh tidak
 * punya kursor untuk diikuti, jadi efeknya dimatikan di sana.
 */
export default function spotlight(el, _directive, { cleanup }) {
    if (matchMedia('(pointer: coarse)').matches) return;

    let frame = 0;
    const move = (event) => {
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => {
            const box = el.getBoundingClientRect();
            el.style.setProperty('--mx', `${event.clientX - box.left}px`);
            el.style.setProperty('--my', `${event.clientY - box.top}px`);
        });
    };

    el.addEventListener('pointermove', move);
    cleanup(() => {
        el.removeEventListener('pointermove', move);
        cancelAnimationFrame(frame);
    });
}
