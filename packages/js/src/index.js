import { dkClipboard } from './components/clipboard.js';
import { dkDisclosure } from './components/disclosure.js';

/**
 * Registers all Design Kit Alpine.data components on a given Alpine instance.
 * Usage in app.js:
 *
 *   import Alpine from 'alpinejs';
 *   import { registerDesignKit } from '@tpl/design-kit';
 *
 *   registerDesignKit(Alpine);
 *   window.Alpine = Alpine;
 *   Alpine.start();
 */
export function registerDesignKit(Alpine) {
    Alpine.data('dkClipboard', dkClipboard);
    Alpine.data('dkDisclosure', dkDisclosure);
}

export { dkClipboard, dkDisclosure };
