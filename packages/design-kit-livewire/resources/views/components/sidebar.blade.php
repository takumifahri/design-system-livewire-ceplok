@php
    use CeplokUi\DesignKit\Support\Bound;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\FieldId;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

{{--
    Sidebar — navigasi vertikal utama aplikasi dengan dukungan logo, profil
    pengguna, menu bersarang (submenu), badge notifikasi, content separator,
    serta mode collapsed dan dark mode.

    Komponen ini padanan Sidebar di kit React (@ceplok-ui/design-kit-react).
--}}
@props([
    'items' => [],
    'groups' => null,
    'logo' => null,
    'collapsedLogo' => null,
    'user' => null,
    'sticky' => false,
    'collapsed' => false,
    'showCollapseButton' => false,
    'footer' => null,
    'darkMode' => false,
])

@php
    $sticky = Prop::bool($sticky, false, 'sidebar', 'sticky');
    $collapsed = Prop::bool($collapsed, false, 'sidebar', 'collapsed');
    $showCollapseButton = Prop::bool($showCollapseButton, false, 'sidebar', 'show-collapse-button');
    $darkMode = Prop::bool($darkMode, false, 'sidebar', 'dark-mode');

    $resolvedGroups = !empty($groups) ? $groups : (!empty($items) ? [['id' => 'default', 'items' => $items]] : []);
    $allItems = !empty($groups) ? collect($groups)->flatMap(fn ($g) => $g['items'] ?? [])->all() : $items;
    $hasMenuIcons = collect($allItems)->some(function ($item) {
        if (!empty($item['icon'])) {
            return true;
        }
        foreach ($item['children'] ?? [] as $child) {
            if (!empty($child['icon'])) {
                return true;
            }
        }
        return false;
    });
    $hasCollapseListener = $attributes->has('wire:collapse')
        || $attributes->has('x-on:collapse')
        || $attributes->has('@collapse');
    // Seperti onCollapse di React: pendengar event `collapse` menampilkan
    // tombolnya dalam keadaan aktif, `show-collapse-button` saja nonaktif.
    $hasCollapseButton = $hasMenuIcons && ($showCollapseButton || $hasCollapseListener);
    $hasHeader = Content::filled($logo) || Content::filled($collapsedLogo) || $hasCollapseButton;
    $sidebarId = FieldId::for($attributes, 'sidebar');

    $userGroups = !empty($user['groups'])
        ? $user['groups']
        : (!empty($user['items']) ? [['id' => 'default', 'items' => $user['items']]] : []);
    $hasUserMenu = !empty($user) && collect($userGroups)->some(fn ($g) => !empty($g['items']));

    $avatarSrc = $user['avatarSrc'] ?? $user['avatar'] ?? null;
    $userName = $user['name'] ?? '';
    $userInitials = $user['initials'] ?? (filled($userName) ? mb_strtoupper(mb_substr($userName, 0, 1)) : '?');
    $avatarAlt = $user['avatarAlt'] ?? $userName;
    $profileLabel = $user['profileLabel'] ?? 'Lihat Profil';
    // Padanan user.onClick di React, sama seperti `attributes` pada item menu.
    $userAttributes = new \Illuminate\View\ComponentAttributeBag($user['attributes'] ?? []);
    // Profil tanpa tujuan tidak melompat ke "#", seperti di React.
    $userWithoutHref = empty($user['href']) || $user['href'] === '#';
@endphp

<aside
    x-data="ceplokSidebar"
    data-initial-collapsed="{{ $collapsed ? 'true' : 'false' }}"
    @keydown.window="handleKeyDown($event)"
    x-on:expand-sidebar="expand()"
    {{ $attributes
        ->class([
            'flex min-h-screen flex-col border-r border-border bg-sidebar-surface text-content transition-all duration-200',
            $sticky ? 'sticky top-0 h-screen self-start' : '',
        ])
        ->merge($darkMode ? ['data-theme' => 'dark'] : []) }}
    style="width: {{ $collapsed ? '72px' : '280px' }}"
    x-bind:style="{ width: collapsed ? '72px' : '280px' }"
>
    @if ($hasHeader)
        <header
            class="flex shrink-0 items-center px-4"
            x-bind:class="collapsed ? 'flex-col gap-3 py-4' : 'h-[72px] justify-between'"
        >
            @if (Content::filled($collapsedLogo) && Content::filled($logo))
                <div
                    class="flex min-w-0 items-center text-content"
                    x-bind:class="collapsed ? 'order-2 w-full max-w-full justify-center' : ''"
                >
                    <span x-show="!collapsed" @if ($collapsed) style="display: none;" @endif>{{ $logo }}</span>
                    <span x-show="collapsed" @if (!$collapsed) style="display: none;" @endif>{{ $collapsedLogo }}</span>
                </div>
            @elseif (Content::filled($logo))
                <div
                    class="flex min-w-0 items-center text-content"
                    x-bind:class="collapsed ? 'order-2 w-full max-w-full justify-center' : ''"
                >
                    {{ $logo }}
                </div>
            @elseif (Content::filled($collapsedLogo))
                <div
                    class="flex min-w-0 items-center text-content"
                    x-bind:class="collapsed ? 'order-2 w-full max-w-full justify-center' : ''"
                >
                    {{ $collapsedLogo }}
                </div>
            @endif

            @if ($hasCollapseButton)
                <button
                    type="button"
                    aria-label="{{ $collapsed ? 'Buka sidebar' : 'Tutup sidebar' }}"
                    x-bind:aria-label="collapsed ? 'Buka sidebar' : 'Tutup sidebar'"
                    x-on:click="toggleCollapse()"
                    @if (!$hasCollapseListener) disabled @endif
                    class="flex size-8 shrink-0 items-center justify-center rounded-lg transition-colors disabled:cursor-not-allowed disabled:hover:bg-transparent text-content-muted hover:bg-surface"
                    x-bind:class="collapsed ? 'order-1' : ''"
                >
                    <x-ceplok-o-chevron-left
                        class="w-[28px] h-[28px] shrink-0 text-content transition-transform"
                        x-bind:class="collapsed ? 'rotate-180' : ''"
                    />
                </button>
            @endif
        </header>
    @endif

    {{-- Profile --}}
    @if ($user)
        <div class="px-4 pb-3 {{ !$hasHeader ? 'pt-4' : '' }}">
            <div class="relative">
                @if (!$hasUserMenu)
                    <a
                        href="{{ !empty($user['href']) ? $user['href'] : '#' }}"
                        {{ $userAttributes }}
                        @if ($userWithoutHref) x-on:click.prevent @endif
                        @if (!empty($user['external'])) target="_blank" rel="noreferrer noopener" @endif
                        class="flex transition-colors"
                        x-bind:class="collapsed ? 'justify-center' : 'items-center gap-3 rounded-lg bg-primary-50 px-3 py-2.5 hover:bg-primary-100/70 in-data-[theme=dark]:bg-gray-600 in-data-[theme=dark]:hover:bg-gray-600/80'"
                    >
                        @if ($avatarSrc)
                            <img
                                src="{{ $avatarSrc }}"
                                alt="{{ $avatarAlt }}"
                                class="size-9 shrink-0 rounded-full object-cover {{ $collapsed ? 'ring-2 ring-transparent transition hover:ring-primary-500' : '' }}"
                                x-bind:class="collapsed ? 'ring-2 ring-transparent transition hover:ring-primary-500' : ''"
                            />
                        @else
                            <span
                                class="flex size-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold transition {{ $darkMode ? 'bg-primary-900 text-primary-300' : 'bg-primary-100 text-primary-700 in-data-[theme=dark]:bg-primary-900 in-data-[theme=dark]:text-primary-300' }} {{ $collapsed ? 'hover:ring-2 hover:ring-primary-500' : '' }}"
                                x-bind:class="collapsed ? 'hover:ring-2 hover:ring-primary-500' : ''"
                                aria-hidden="true"
                            >
                                {{ $userInitials }}
                            </span>
                        @endif

                        <div
                            x-show="!collapsed"
                            @if ($collapsed) style="display: none;" @endif
                            class="min-w-0 flex-1"
                        >
                            <p class="truncate text-sm font-medium text-content">{{ $userName }}</p>
                            <p class="mt-0.5 truncate text-xs text-brand">{{ $profileLabel }}</p>
                        </div>
                    </a>
                @else
                    <button
                        x-ref="userTrigger"
                        type="button"
                        {{ $userAttributes }}
                        aria-haspopup="true"
                        x-bind:aria-expanded="userMenuOpen ? 'true' : 'false'"
                        aria-expanded="false"
                        aria-label="Menu profil {{ $userName }}"
                        x-on:click="toggleUserMenu()"
                        class="flex w-full text-left transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
                        x-bind:class="collapsed ? 'justify-center p-0 rounded-full' : 'items-center gap-3 rounded-lg bg-primary-50 px-3 py-2.5 hover:bg-primary-100/70 text-content in-data-[theme=dark]:bg-gray-600 in-data-[theme=dark]:hover:bg-gray-600/80'"
                    >
                        @if ($avatarSrc)
                            <img
                                src="{{ $avatarSrc }}"
                                alt="{{ $avatarAlt }}"
                                class="size-9 shrink-0 rounded-full object-cover {{ $collapsed ? 'ring-2 ring-transparent transition hover:ring-primary-500' : '' }}"
                                x-bind:class="collapsed ? 'ring-2 ring-transparent transition hover:ring-primary-500' : ''"
                            />
                        @else
                            <span
                                class="flex size-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold transition {{ $darkMode ? 'bg-primary-900 text-primary-300' : 'bg-primary-100 text-primary-700 in-data-[theme=dark]:bg-primary-900 in-data-[theme=dark]:text-primary-300' }} {{ $collapsed ? 'hover:ring-2 hover:ring-primary-500' : '' }}"
                                x-bind:class="collapsed ? 'hover:ring-2 hover:ring-primary-500' : ''"
                                aria-hidden="true"
                            >
                                {{ $userInitials }}
                            </span>
                        @endif

                        <div
                            x-show="!collapsed"
                            @if ($collapsed) style="display: none;" @endif
                            class="min-w-0 flex-1"
                        >
                            <p class="truncate text-sm font-medium text-content">{{ $userName }}</p>
                            <p class="mt-0.5 truncate text-xs text-brand">{{ $profileLabel }}</p>
                        </div>

                        <span
                            x-show="!collapsed"
                            @if ($collapsed) style="display: none;" @endif
                        >
                            <x-ceplok-o-chevron-down
                                class="size-4 shrink-0 transition-transform motion-reduce:transition-none text-content-muted"
                                x-bind:class="userMenuOpen ? 'rotate-180' : ''"
                            />
                        </span>
                    </button>

                    <div
                        x-show="userMenuOpen"
                        x-on:click.outside="closeUserMenu()"
                        x-transition
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                        class="absolute z-50 rounded-lg border border-border bg-sidebar-surface text-content py-1.5 shadow-lg shadow-gray-300/50 in-data-[theme=dark]:shadow-black/40 {{ $collapsed ? 'left-[calc(100%+16px)] top-0 w-60' : 'left-0 right-0 top-full mt-1.5 w-full' }}"
                        x-bind:class="collapsed ? 'left-[calc(100%+16px)] top-0 w-60' : 'left-0 right-0 top-full mt-1.5 w-full'"
                    >
                        <div
                            x-show="collapsed"
                            @if (!$collapsed) style="display: none;" @endif
                            class="border-b border-border px-3 py-2"
                        >
                            <p class="truncate text-sm font-semibold text-content">{{ $userName }}</p>
                            @if ($profileLabel)
                                <p class="mt-0.5 truncate text-xs text-content-muted">{{ $profileLabel }}</p>
                            @endif
                        </div>

                        <div class="py-1">
                            @foreach ($userGroups as $uGroup)
                                <div>
                                    @if (!empty($uGroup['separator']))
                                        <hr class="my-1 border-t border-border" />
                                    @endif
                                    @if (!empty($uGroup['label']))
                                        <div class="px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-content-muted">
                                            {{ $uGroup['label'] }}
                                        </div>
                                    @endif
                                    @foreach ($uGroup['items'] ?? [] as $uItem)
                                        @php
                                            $isDanger = ($uItem['tone'] ?? 'default') === 'danger';
                                            $isDisabled = !empty($uItem['disabled']);
                                            $itemClasses = 'group flex w-full items-center gap-2.5 px-3 py-2 text-sm text-left transition-colors focus-visible:outline-none ' . (
                                                $isDisabled
                                                    ? 'cursor-not-allowed opacity-50'
                                                    : ($isDanger
                                                        ? 'text-feedback-error hover:bg-red-50 hover:text-red-700 in-data-[theme=dark]:hover:bg-red-950/40 in-data-[theme=dark]:hover:text-red-300 focus-visible:bg-red-50 in-data-[theme=dark]:focus-visible:bg-red-950/40'
                                                        : 'text-content hover:bg-gray-100 in-data-[theme=dark]:hover:bg-surface focus-visible:bg-gray-100 in-data-[theme=dark]:focus-visible:bg-surface')
                                            );
                                        @endphp
                                        @if (!empty($uItem['href']))
                                            <a
                                                href="{{ $isDisabled ? null : $uItem['href'] }}"
                                                role="menuitem"
                                                @if ($isDisabled) aria-disabled="true" tabindex="-1" x-on:click.prevent @else tabindex="0" x-on:click="closeUserMenu()" @endif
                                                @if (!empty($uItem['external'])) target="_blank" rel="noreferrer noopener" @endif
                                                class="{{ $itemClasses }}"
                                            >
                                                @if (!empty($uItem['icon']))
                                                    <span class="flex size-5 shrink-0 items-center justify-center [&>svg]:size-4 {{ $isDanger ? 'text-feedback-error' : 'text-content-muted group-hover:text-content' }}">
                                                        {!! Content::icon($uItem['icon']) !!}
                                                    </span>
                                                @endif
                                                <div class="min-w-0 flex-1">
                                                    <span class="block truncate font-medium">{{ $uItem['label'] }}</span>
                                                    @if (!empty($uItem['description']))
                                                        <span class="block truncate text-xs text-content-muted">{{ $uItem['description'] }}</span>
                                                    @endif
                                                </div>
                                            </a>
                                        @else
                                            <button
                                                type="button"
                                                role="menuitem"
                                                @if ($isDisabled) disabled @endif
                                                x-on:click="closeUserMenu()"
                                                class="{{ $itemClasses }}"
                                            >
                                                @if (!empty($uItem['icon']))
                                                    <span class="flex size-5 shrink-0 items-center justify-center [&>svg]:size-4 {{ $isDanger ? 'text-feedback-error' : 'text-content-muted group-hover:text-content' }}">
                                                        {!! Content::icon($uItem['icon']) !!}
                                                    </span>
                                                @endif
                                                <div class="min-w-0 flex-1">
                                                    <span class="block truncate font-medium">{{ $uItem['label'] }}</span>
                                                    @if (!empty($uItem['description']))
                                                        <span class="block truncate text-xs text-content-muted">{{ $uItem['description'] }}</span>
                                                    @endif
                                                </div>
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Navigation --}}
    @if (!empty($resolvedGroups))
        <nav class="ds-scroll-y min-h-0 flex-1 overflow-y-auto px-3 py-3" aria-label="Navigasi sidebar">
            @foreach ($resolvedGroups as $group)
                <section
                    class="{{ !empty($group['separator']) ? 'mt-3 border-t border-border pt-3' : '' }}"
                    @if (!empty($group['label'])) aria-label="{{ $group['label'] }}" @endif
                >
                    @if (!empty($group['label']))
                        <h2
                            x-show="!collapsed"
                            @if ($collapsed) style="display: none;" @endif
                            class="px-3 pb-2 text-xs font-semibold uppercase tracking-wide text-content-muted"
                        >
                            {{ $group['label'] }}
                        </h2>
                    @endif

                    <ul class="space-y-1">
                        @foreach ($group['items'] ?? [] as $item)
                            @php
                                $hasChildren = !empty($item['children']);
                                $itemId = $item['id'] ?? ($sidebarId.'-'.\Illuminate\Support\Str::slug($item['label'] ?? ''));
                                $isActive = !empty($item['active']);
                                $isDisabled = !empty($item['disabled']);
                                $isSubmenuDisabled = !empty($item['submenuToggleDisabled']);
                                $hasActiveChild = collect($item['children'] ?? [])->contains(fn ($child) => !empty($child['active']));
                                $defaultOpen = !empty($item['defaultOpen']);
                                $isOpen = $defaultOpen || $isActive || $hasActiveChild;
                                $itemAttributes = new \Illuminate\View\ComponentAttributeBag($item['attributes'] ?? []);
                                $menuItemClasses = 'group flex w-full rounded-lg text-sm font-medium transition-colors text-content hover:bg-gray-100 in-data-[theme=dark]:hover:bg-gray-600 ' . (
                                    $collapsed ? 'justify-center px-2 py-2.5 ' : 'items-center gap-3 px-3 py-2.5 '
                                ) . (
                                    $isActive ? 'bg-gray-100 in-data-[theme=dark]:bg-gray-600 text-content ' : ''
                                ) . (
                                    $isDisabled ? 'cursor-not-allowed opacity-50 ' : ''
                                );
                            @endphp
                            <li>
                                @if (!$hasChildren)
                                    <a
                                        href="{{ $isDisabled ? null : ($item['href'] ?? '#') }}"
                                        {{ $itemAttributes }}
                                        class="{{ $menuItemClasses }}"
                                        x-bind:class="collapsed ? 'justify-center px-2 py-2.5' : 'items-center gap-3 px-3 py-2.5'"
                                        @if ($isActive) aria-current="page" @endif
                                        @if ($isDisabled) aria-disabled="true" tabindex="-1" x-on:click.prevent @endif
                                        @if ($collapsed) title="{{ $item['label'] ?? '' }}" @endif
                                        x-bind:title="collapsed ? '{{ addslashes($item['label'] ?? '') }}' : null"
                                        @if (!empty($item['external'])) target="_blank" rel="noreferrer noopener" @endif
                                    >
                                        @if (!empty($item['icon']))
                                            <span class="flex size-5 shrink-0 items-center justify-center transition-colors [&>svg]:size-5 {{ $isActive ? 'text-content' : 'text-content-muted group-hover:text-content' }}">
                                                {!! Content::icon($item['icon']) !!}
                                            </span>
                                        @endif
                                        <span
                                            x-show="!collapsed"
                                            @if ($collapsed) style="display: none;" @endif
                                            class="min-w-0 flex-1 truncate"
                                        >
                                            {{ $item['label'] ?? '' }}
                                        </span>
                                        @if (isset($item['badge']) && $item['badge'] !== null)
                                            <span
                                                x-show="!collapsed"
                                                @if ($collapsed) style="display: none;" @endif
                                                class="flex min-w-5 shrink-0 items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-600 in-data-[theme=dark]:bg-surface in-data-[theme=dark]:text-red-400"
                                            >
                                                {{ $item['badge'] }}
                                            </span>
                                        @endif
                                    </a>
                                @else
                                    <div
                                        x-data="ceplokSidebarSubmenu"
                                        data-initial-open="{{ $defaultOpen ? 'true' : 'false' }}"
                                        data-active="{{ $isActive ? 'true' : 'false' }}"
                                        data-has-active-child="{{ $hasActiveChild ? 'true' : 'false' }}"
                                        data-can-expand="{{ $hasCollapseListener ? 'true' : 'false' }}"
                                    >
                                        <button
                                            type="button"
                                            {{ $itemAttributes }}
                                            class="{{ $menuItemClasses }}"
                                            x-bind:class="collapsed ? 'justify-center px-2 py-2.5' : 'items-center gap-3 px-3 py-2.5'"
                                            x-on:click="toggle()"
                                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                            x-bind:aria-expanded="collapsed ? null : isOpen ? 'true' : 'false'"
                                            aria-controls="sidebar-submenu-{{ $itemId }}"
                                            x-bind:disabled="collapsed && !canExpand"
                                            @if ($isDisabled || $isSubmenuDisabled || ($collapsed && !$hasCollapseListener)) disabled aria-disabled="true" @endif
                                            @if ($collapsed) title="{{ $item['label'] ?? '' }}" @endif
                                            x-bind:title="collapsed ? '{{ addslashes($item['label'] ?? '') }}' : null"
                                        >
                                            @if (!empty($item['icon']))
                                                <span class="flex size-5 shrink-0 items-center justify-center transition-colors [&>svg]:size-5 {{ $isActive ? 'text-content' : 'text-content-muted group-hover:text-content' }}">
                                                    {!! Content::icon($item['icon']) !!}
                                                </span>
                                            @endif
                                            <span
                                                x-show="!collapsed"
                                                @if ($collapsed) style="display: none;" @endif
                                                class="min-w-0 flex-1 truncate text-left"
                                            >
                                                {{ $item['label'] ?? '' }}
                                            </span>

                                            @if (isset($item['badge']) && $item['badge'] !== null)
                                                <span
                                                    x-show="!collapsed"
                                                    @if ($collapsed) style="display: none;" @endif
                                                    class="flex min-w-5 shrink-0 items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-600 in-data-[theme=dark]:bg-surface in-data-[theme=dark]:text-red-400"
                                                >
                                                    {{ $item['badge'] }}
                                                </span>
                                            @endif

                                            <span
                                                x-show="!collapsed"
                                                @if ($collapsed) style="display: none;" @endif
                                            >
                                                <x-ceplok-o-chevron-down
                                                    class="w-[28px] h-[28px] shrink-0 text-content transition-transform {{ $defaultOpen ? 'rotate-180' : '' }}"
                                                    x-bind:class="isOpen ? 'rotate-180' : ''"
                                                />
                                            </span>
                                        </button>

                                        <ul
                                            x-show="!collapsed && isOpen"
                                            id="sidebar-submenu-{{ $itemId }}"
                                            class="mt-1 space-y-1 pl-8"
                                            @if ($collapsed || !$isOpen) style="display: none;" @endif
                                        >
                                            @foreach ($item['children'] as $child)
                                                @php
                                                    $childActive = !empty($child['active']);
                                                    $childDisabled = !empty($child['disabled']);
                                                    $childAttributes = new \Illuminate\View\ComponentAttributeBag($child['attributes'] ?? []);
                                                @endphp
                                                <li>
                                                    <a
                                                        href="{{ $childDisabled ? null : ($child['href'] ?? '#') }}"
                                                        {{ $childAttributes }}
                                                        class="group flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors text-content-subtle hover:bg-gray-100 hover:text-content in-data-[theme=dark]:hover:bg-gray-600 {{ $childActive ? 'bg-gray-100 font-medium text-content in-data-[theme=dark]:bg-gray-600 ' : '' }}{{ $childDisabled ? 'cursor-not-allowed opacity-50 ' : '' }}"
                                                        @if ($childActive) aria-current="page" @endif
                                                        @if ($childDisabled) aria-disabled="true" tabindex="-1" x-on:click.prevent @endif
                                                        @if (!empty($child['external'])) target="_blank" rel="noreferrer noopener" @endif
                                                    >
                                                        @if (!empty($child['icon']))
                                                            <span class="flex size-5 shrink-0 items-center justify-center transition-colors [&>svg]:size-5 {{ $childActive ? 'text-content' : 'text-content-muted group-hover:text-content' }}">
                                                                {!! Content::icon($child['icon']) !!}
                                                            </span>
                                                        @endif
                                                        <span class="truncate">{{ $child['label'] ?? '' }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </nav>
    @endif

    {{-- Slot custom --}}
    @if (Content::filled($slot))
        {{ $slot }}
    @endif

    {{-- Footer --}}
    @if (Content::filled($footer))
        <div class="shrink-0 border-t border-border">
            {{ $footer }}
        </div>
    @endif
</aside>
