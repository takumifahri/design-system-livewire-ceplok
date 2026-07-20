import Alpine from 'alpinejs';
import { registerDesignKit, dkClipboard } from '@tpl/design-kit';

registerDesignKit(Alpine);

window.Alpine = Alpine;

Alpine.data('designSystem', () => ({
    ...dkClipboard(),

    copyToken(value, token) {
        this.copy(value, token);
    },
}));

Alpine.start();
