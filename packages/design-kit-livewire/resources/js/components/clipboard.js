const SUCCESS = 'Copied to clipboard.';
const FAILURE = 'Could not copy. Select the text and copy it manually.';

export default function clipboard() {
    return {
        ceplokMessage: '',
        ceplokPending: false,
        ceplokWriting: false,
        ceplokHovered: false,
        ceplokFocusVisible: false,
        ceplokTooltipDismissed: false,
        ceplokTimer: null,
        ceplokGeneration: 0,
        ceplokObserver: null,
        ceplokMounted: false,

        init() {
            this.ceplokMounted = true;
            this.ceplokObserver = new MutationObserver(() => this.ceplokReset());
            this.ceplokObserver.observe(this.$root, {
                attributes: true,
                attributeFilter: ['data-value', 'data-disabled'],
            });
        },

        destroy() {
            this.ceplokMounted = false;
            clearTimeout(this.ceplokTimer);
            this.ceplokObserver?.disconnect();
        },

        get ceplokDisabled() {
            return this.$root.dataset.disabled === 'true';
        },

        get ceplokTooltip() {
            return (this.ceplokHovered || this.ceplokFocusVisible)
                && !this.ceplokTooltipDismissed
                && this.ceplokMessage !== SUCCESS
                && !this.ceplokDisabled;
        },

        ceplokReset() {
            this.ceplokGeneration++;
            this.ceplokMessage = '';
            clearTimeout(this.ceplokTimer);
            if (this.ceplokDisabled) {
                this.$refs.input?.blur();
                this.$refs.input?.setSelectionRange(0, 0);
            }
        },

        async ceplokCopy() {
            if (this.ceplokDisabled || this.ceplokWriting) return;
            const generation = this.ceplokGeneration;
            const value = this.$root.dataset.value ?? '';
            this.ceplokWriting = true;
            this.ceplokPending = true;
            this.ceplokMessage = '';
            clearTimeout(this.ceplokTimer);

            let failed = false;
            let failure;
            try {
                if (!navigator.clipboard?.writeText) throw new Error('Clipboard API is unavailable.');
                await navigator.clipboard.writeText(value);
            } catch (error) {
                failed = true;
                failure = error;
            } finally {
                this.ceplokWriting = false;
                if (this.ceplokMounted) this.ceplokPending = false;
            }

            if (
                !this.ceplokMounted
                || generation !== this.ceplokGeneration
                || this.ceplokDisabled
                || (this.$root.dataset.value ?? '') !== value
            ) return;

            if (failed) {
                this.ceplokMessage = FAILURE;
                try { this.$dispatch('copy-error', { error: failure }); } catch { /* Listener tidak mengubah hasil copy. */ }
                return;
            }

            this.ceplokTooltipDismissed = true;
            this.ceplokMessage = SUCCESS;
            try { this.$dispatch('copy-success', { value }); } catch { /* Listener tidak mengubah hasil copy. */ }
            this.ceplokTimer = setTimeout(() => {
                if (this.ceplokMounted && (this.$root.dataset.value ?? '') === value) this.ceplokMessage = '';
            }, 1600);
        },

        ceplokBlock(event) {
            if (this.ceplokDisabled) event.preventDefault();
        },
    };
}
