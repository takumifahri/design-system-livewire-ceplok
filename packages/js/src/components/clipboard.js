/**
 * Alpine.data component for "click to copy" tokens/values.
 * Usage: x-data="dkClipboard()"
 */
export function dkClipboard() {
    return {
        copied: null,
        copiedTimer: null,

        async copy(value, id) {
            try {
                await navigator.clipboard.writeText(value);
                this.copied = id;
                window.clearTimeout(this.copiedTimer);
                this.copiedTimer = window.setTimeout(() => {
                    this.copied = null;
                }, 1200);
            } catch {
                this.copied = null;
            }
        },
    };
}
