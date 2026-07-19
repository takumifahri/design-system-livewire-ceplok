import Alpine from 'alpinejs';
import { registerDesignKit, dkClipboard } from '@tpl/design-kit';

registerDesignKit(Alpine);

window.Alpine = Alpine;

Alpine.data('designSystem', () => ({
    ...dkClipboard(),

    section: window.location.hash === '#typography' ? 'typography' : 'colors',
    mobileMenu: false,

    showSection(section) {
        this.section = section;
        this.mobileMenu = false;
        window.history.replaceState(null, '', `#${section}`);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    copyToken(value, token) {
        this.copy(value, token);
    },
}));

Alpine.start();
