export default function sidebar() {
    return {
        collapsed: false,
        userMenuOpen: false,
        collapsedObserver: null,

        init() {
            this.syncCollapsed();
            this.collapsedObserver = new MutationObserver(() => this.syncCollapsed());
            this.collapsedObserver.observe(this.$el, {
                attributes: true,
                attributeFilter: ['data-initial-collapsed'],
            });
        },

        destroy() {
            this.collapsedObserver?.disconnect();
        },

        syncCollapsed() {
            this.collapsed = this.$el.dataset.initialCollapsed === 'true';
        },

        toggleCollapse() {
            this.collapsed = !this.collapsed;
            this.userMenuOpen = false;
            this.$dispatch('collapse', { collapsed: this.collapsed });
        },

        expand() {
            if (!this.collapsed) return;

            this.collapsed = false;
            this.userMenuOpen = false;
            this.$dispatch('collapse', { collapsed: false });
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

export function sidebarSubmenu() {
    return {
        open: false,
        active: false,
        hasActiveChild: false,
        canExpand: false,

        init() {
            this.open = this.$el.dataset.initialOpen === 'true';
            this.active = this.$el.dataset.active === 'true';
            this.hasActiveChild = this.$el.dataset.hasActiveChild === 'true';
            this.canExpand = this.$el.dataset.canExpand === 'true';
        },

        get isOpen() {
            return this.open || this.active || this.hasActiveChild;
        },

        toggle() {
            if (this.collapsed) {
                if (this.canExpand) this.$dispatch('expand-sidebar');
                return;
            }

            this.open = !this.open;
        },
    };
}
