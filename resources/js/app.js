import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('designSystem', () => ({
    section: window.location.hash === '#typography' ? 'typography' : 'colors',
    mobileMenu: false,
    copied: null,
    copiedTimer: null,

    showSection(section) {
        this.section = section;
        this.mobileMenu = false;
        window.history.replaceState(null, '', `#${section}`);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    async copyToken(value, token) {
        try {
            await navigator.clipboard.writeText(value);
            this.copied = token;
            window.clearTimeout(this.copiedTimer);
            this.copiedTimer = window.setTimeout(() => {
                this.copied = null;
            }, 1200);
        } catch {
            this.copied = null;
        }
    },
}));

Alpine.start();
