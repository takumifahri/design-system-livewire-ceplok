/**
 * Alpine.data component for simple open/close UI (mobile menu, dropdown, accordion panel).
 * Usage: x-data="dkDisclosure()" or dkDisclosure(true) to start open.
 */
export function dkDisclosure(initialOpen = false) {
    return {
        open: initialOpen,

        toggle() {
            this.open = !this.open;
        },

        close() {
            this.open = false;
        },
    };
}
