export default function sidebar(config = {}) {
    return {
        collapsed: Boolean(config.collapsed),
        userMenuOpen: false,

        init() {
            if (this.$el.dataset.initialCollapsed !== undefined) {
                this.collapsed = this.$el.dataset.initialCollapsed === 'true';
            }
        },

        toggleCollapse() {
            this.collapsed = !this.collapsed;
            this.userMenuOpen = false;
            this.$dispatch('collapse', { collapsed: this.collapsed });
        },

        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
        },

        closeUserMenu() {
            this.userMenuOpen = false;
        },

        handleKeyDown(event) {
            if (event.key === 'Escape' && this.userMenuOpen) {
                event.preventDefault();
                this.closeUserMenu();
                this.$refs.userTrigger?.focus();
            }
        },
    };
}
