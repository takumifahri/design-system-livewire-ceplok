import { Livewire } from '../../../vendor/livewire/livewire/dist/livewire.esm';

/*
 * Playground halaman Dropdown — port perilaku Playground Dropdown di docs
 * React. Panelnya dibuka sejak halaman dimuat, dan dibuka lagi setelah
 * kontrolnya diganti: menekan kontrol di luar panel menutupnya, dan tanpa
 * dibuka lagi variasinya tidak bisa dibandingkan.
 *
 * Tombol dan panelnya dicari dari DOM panggung (`x-ref="stage"`). Ini halaman
 * dokumentasi yang mengintip DOM-nya sendiri, bukan pemakaian yang dianjurkan.
 */
export default () => ({
    reopen: false,
    off: null,

    init() {
        // Satu frame ditunggu supaya Dropdown di panggung sudah diinisialisasi Alpine.
        requestAnimationFrame(() => this.open());

        // Setelah Livewire menambal panggungnya, panel — yang mungkin elemen baru — dibuka lagi.
        this.off = Livewire.hook('morphed', ({ component }) => {
            if (component.id !== this.$wire.$id || !this.reopen) return;
            this.reopen = false;
            requestAnimationFrame(() => this.open());
        });
    },

    destroy() {
        this.off?.();
    },

    /** Dipanggil pada pointerdown di kontrol, sebelum klik itu menutup panelnya. */
    remember(event) {
        if (event.button !== 0) return;
        this.reopen = this.panel()?.matches(':popover-open') ?? false;
    },

    panel() {
        return this.$refs.stage.querySelector('[popover]');
    },

    open() {
        const panel = this.panel();
        const trigger = this.$refs.stage.querySelector('button');
        if (panel?.isConnected && trigger && !panel.matches(':popover-open')) panel.showPopover({ source: trigger });
    },
});
