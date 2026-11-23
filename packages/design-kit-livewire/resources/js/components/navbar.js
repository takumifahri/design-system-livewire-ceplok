export default function navbar() {
    let capacityFrame = 0;
    let capacityObserver = null;
    let overflowPanel = null;
    let overflowToggle = null;
    let overflowClick = null;
    let openObserver = null;

    return {
        mobileOpen: false,
        drawerOpen: false,
        capacityReady: false,
        capacityImpossible: false,
        desktopVisibleCount: 0,
        overflowLoadedCount: 1,
        overflowExpanded: {},
        restoreDrawerFocus: true,
        init() {
            this.mobileOpen = this.$root.dataset.open === 'true';
            this.desktopVisibleCount = Number(this.$root.dataset.navbarItemCount || 0);
            this.overflowExpanded = this.currentOverflowDisclosures();
            this.$watch('mobileOpen', (open) => this.syncDialog(open));
            this.$watch('drawerOpen', (open, previous) => {
                if (!open && previous && this.restoreDrawerFocus) {
                    this.$nextTick(() => {
                        if (this.$refs.drawerTrigger?.offsetParent !== null) {
                            this.$refs.drawerTrigger.focus({ preventScroll: true });
                        }
                    });
                }
                if (!open) this.restoreDrawerFocus = true;
            });
            openObserver = new MutationObserver(() => {
                const requested = this.$root.dataset.open === 'true';
                if (requested !== this.mobileOpen) this.mobileOpen = requested;
            });
            openObserver.observe(this.$root, { attributes: true, attributeFilter: ['data-open'] });
            this.$nextTick(() => {
                this.syncDialog(this.mobileOpen);
                this.setupDesktopCapacity();
                this.setupOverflowPanel();
            });
            this.media = window.matchMedia('(min-width: 1024px)');
            this.onDesktop = (event) => {
                this.closeNavbarDropdowns();
                if (event.matches) {
                    this.closeMobile(false);
                    this.restoreDrawerFocus = false;
                    this.drawerOpen = false;
                }
                this.requestDesktopCapacity();
            };
            this.media.addEventListener('change', this.onDesktop);
            this.onDesktop(this.media);
        },
        destroy() {
            this.media?.removeEventListener('change', this.onDesktop);
            cancelAnimationFrame(capacityFrame);
            capacityObserver?.disconnect();
            openObserver?.disconnect();
            overflowPanel?.removeEventListener('toggle', overflowToggle);
            overflowPanel?.removeEventListener('click', overflowClick);
        },
        setupDesktopCapacity() {
            const nav = this.$refs.desktopNavigation;
            if (!nav) {
                this.capacityReady = true;
                return;
            }
            capacityObserver = new ResizeObserver(() => this.requestDesktopCapacity());
            capacityObserver.observe(nav);
            const row = nav.parentElement;
            const measurement = nav.querySelector('[data-navbar-primary-measure]');
            if (row) {
                capacityObserver.observe(row);
                for (const child of row.children) capacityObserver.observe(child);
            }
            if (measurement) {
                capacityObserver.observe(measurement);
                for (const item of measurement.children) capacityObserver.observe(item);
            }
            this.requestDesktopCapacity();
            document.fonts?.ready.then(() => this.requestDesktopCapacity());
        },
        requestDesktopCapacity() {
            if (capacityFrame) return;
            capacityFrame = requestAnimationFrame(() => {
                capacityFrame = 0;
                this.measureDesktopCapacity();
            });
        },
        measureDesktopCapacity() {
            const nav = this.$refs.desktopNavigation;
            const measurement = nav?.querySelector('[data-navbar-primary-measure]');
            if (!nav || !measurement || window.matchMedia('(max-width: 1023px)').matches) {
                this.desktopVisibleCount = Number(this.$root.dataset.navbarItemCount || 0);
                this.capacityReady = true;
                return;
            }
            const items = [...measurement.querySelectorAll('[data-navbar-measure-item]')];
            const overflow = measurement.querySelector('[data-navbar-measure-overflow]');
            const row = nav.parentElement;
            const header = row?.closest('header');
            const brand = row?.querySelector(':scope > a');
            const search = row?.querySelector(':scope > [data-navbar-search]');
            const identity = row?.querySelector(':scope > [data-navbar-identity]');
            if (!row || !header || (this.$root.dataset.navbarPlatform === 'front-office' && !brand) || !overflow) {
                this.capacityReady = false;
                return;
            }
            const rowStyle = getComputedStyle(row);
            const style = getComputedStyle(measurement);
            const gap = Number.parseFloat(style.columnGap || style.gap || '0');
            const widths = items.map((item) => item.getBoundingClientRect().width);
            const rowRect = row.getBoundingClientRect();
            const parsedMaximum = Number.parseFloat(rowStyle.maxWidth);
            const platform = this.$root.dataset.navbarPlatform;
            const maximumRowWidth = platform === 'front-office'
                ? window.matchMedia('(min-width: 1280px)').matches && Number.isFinite(parsedMaximum)
                    ? Math.max(rowRect.width, Math.min(parsedMaximum, header.clientWidth))
                    : rowRect.width
                : Math.max(
                    0,
                    rowRect.width
                        - (Number.parseFloat(rowStyle.paddingLeft) || 0)
                        - (Number.parseFloat(rowStyle.paddingRight) || 0),
                );
            const majorGap = Number.parseFloat(rowStyle.columnGap || rowStyle.gap || '0');
            const fixedGroupCount = 1 + (brand ? 1 : 0) + (search ? 1 : 0) + (identity ? 1 : 0);
            const fixedWidth = (brand?.getBoundingClientRect().width ?? 0)
                + (search?.getBoundingClientRect().width ?? 0)
                + (identity?.getBoundingClientRect().width ?? 0)
                + majorGap * Math.max(0, fixedGroupCount - 1);
            const available = Math.max(0, maximumRowWidth - fixedWidth);
            const allWidth = widths.reduce((sum, width) => sum + width, 0) + gap * Math.max(0, widths.length - 1);
            let visible = widths.length;
            if (allWidth > available + 0.5) {
                const budget = Math.max(0, available - overflow.getBoundingClientRect().width - gap);
                let used = 0;
                visible = 0;
                for (const width of widths) {
                    const next = used + (visible > 0 ? gap : 0) + width;
                    if (next > budget + 0.5) break;
                    used = next;
                    visible += 1;
                }
            }
            if (visible !== this.desktopVisibleCount) {
                this.desktopVisibleCount = visible;
                this.resetOverflow(!this.capacityReady);
                this.closeOverflowPanel();
            }
            this.capacityImpossible = widths.length > 0 && visible === 0;
            this.capacityReady = true;
        },
        setupOverflowPanel() {
            overflowPanel = this.$root.querySelector('[data-navbar-overflow] [popover]');
            if (!overflowPanel) return;
            overflowToggle = (event) => {
                if (event.newState === 'open') {
                    requestAnimationFrame(() => {
                        this.overflowLoadedCount = this.overflowBatchCount(0, true);
                        this.measureOverflowLabels();
                    });
                    return;
                }
                if (event.newState === 'closed') this.resetOverflow();
            };
            overflowClick = (event) => {
                const destination = event.target.closest('[data-navbar-overflow-destination]');
                if (!destination || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                destination.dispatchEvent(new CustomEvent('dropdown-close', { bubbles: true }));
            };
            overflowPanel.addEventListener('toggle', overflowToggle);
            overflowPanel.addEventListener('click', overflowClick);
        },
        overflowBatchCount(current, initial = false) {
            const total = Math.max(0, Number(this.$root.dataset.navbarItemCount || 0) - this.desktopVisibleCount);
            if (current >= total) return total;
            const measurements = [...(overflowPanel?.querySelectorAll('[data-navbar-overflow-measure-item]') ?? [])]
                .slice(this.desktopVisibleCount + current);
            const panelStyle = overflowPanel ? getComputedStyle(overflowPanel) : null;
            const panelPadding = panelStyle
                ? Number.parseFloat(panelStyle.paddingTop || '0') + Number.parseFloat(panelStyle.paddingBottom || '0')
                : 0;
            const maxHeight = Number.parseFloat(overflowPanel?.style.maxHeight || '0');
            const moreHeight = overflowPanel?.querySelector('[data-navbar-load-more]')?.getBoundingClientRect().height ?? 0;
            const available = initial && maxHeight > 0 ? Math.max(0, maxHeight - panelPadding - moreHeight) : Infinity;
            let height = 0;
            let added = 0;
            for (const row of measurements) {
                const nextHeight = row.getBoundingClientRect().height;
                if (added > 0 && height + nextHeight > available + 0.5) break;
                height += nextHeight;
                added += 1;
                if (height >= 160 - 0.5) break;
            }
            return Math.min(total, current + Math.max(1, added));
        },
        loadMoreOverflow(event) {
            const scrollTop = overflowPanel?.scrollTop ?? 0;
            const firstNewIndex = this.desktopVisibleCount + this.overflowLoadedCount;
            this.overflowLoadedCount = this.overflowBatchCount(this.overflowLoadedCount);
            this.$nextTick(() => {
                if (overflowPanel) overflowPanel.scrollTop = scrollTop;
                this.measureOverflowLabels();
                if (event.currentTarget.offsetParent === null) {
                    overflowPanel?.querySelector(`[data-navbar-overflow-item="${firstNewIndex}"] a, [data-navbar-overflow-item="${firstNewIndex}"] button`)?.focus({ preventScroll: true });
                }
            });
        },
        toggleOverflowDisclosure(id) {
            this.overflowExpanded[id] = !this.overflowExpanded[id];
            this.$nextTick(() => this.measureOverflowLabels());
        },
        measureOverflowLabels() {
            for (const label of overflowPanel?.querySelectorAll('[data-navbar-overflow-label]') ?? []) {
                const truncated = label.scrollHeight > label.clientHeight + 1 || label.scrollWidth > label.clientWidth + 1;
                if (truncated) label.setAttribute('title', label.textContent.trim());
                else label.removeAttribute('title');
            }
        },
        currentOverflowDisclosures() {
            return Object.fromEntries(
                JSON.parse(this.$root.dataset.navbarCurrentDisclosures || '[]').map((id) => [id, true]),
            );
        },
        resetOverflow(includeCurrent = false) {
            this.overflowLoadedCount = 1;
            this.overflowExpanded = includeCurrent ? this.currentOverflowDisclosures() : {};
        },
        closeOverflowPanel() {
            if (overflowPanel?.matches(':popover-open')) overflowPanel.hidePopover();
        },
        closeNavbarDropdowns() {
            for (const panel of this.$root.querySelectorAll('[popover]:popover-open')) panel.hidePopover();
        },
        syncDialog(open) {
            const dialog = this.$refs.mobileDialog;
            if (!dialog) return;
            if (open && !dialog.open) {
                dialog.showModal();
                this.$nextTick(() => this.$refs.mobileClose?.focus({ preventScroll: true }));
            }
            if (!open && dialog.open) dialog.close();
        },
        openMobile() {
            this.restoreDrawerFocus = false;
            this.drawerOpen = false;
            this.mobileOpen = true;
            this.$dispatch('open-change', { open: true });
        },
        toggleDrawer() {
            if (!this.drawerOpen) this.closeMobile(false);
            this.drawerOpen = !this.drawerOpen;
        },
        onDialogClose() {
            if (!this.mobileOpen) return;
            this.mobileOpen = false;
            this.$dispatch('open-change', { open: false });
        },
        closeMobile(restoreFocus = true) {
            if (!this.mobileOpen && !this.$refs.mobileDialog?.open) return;
            this.mobileOpen = false;
            this.$dispatch('open-change', { open: false });
            if (restoreFocus && this.$refs.mobileTrigger?.offsetParent !== null) {
                this.$nextTick(() => this.$refs.mobileTrigger?.focus());
            }
        },
        onCancel(event) {
            event.preventDefault();
            this.closeMobile();
        },
        onMobileDialogClick(event) {
            if (event.target === this.$refs.mobileDialog) {
                this.closeMobile();
                return;
            }
            const guest = event.target.closest('[data-navbar-mobile-guest]');
            const destination = event.target.closest('[data-navbar-mobile-destination]');
            const accountItem = event.target.closest('[role="menuitem"]');
            if (accountItem?.tagName === 'BUTTON' && !accountItem.disabled) {
                this.closeMobile();
                return;
            }
            if ((guest || destination || accountItem) && !event.defaultPrevented && event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey) {
                this.closeMobile();
            }
        },
    };
}
