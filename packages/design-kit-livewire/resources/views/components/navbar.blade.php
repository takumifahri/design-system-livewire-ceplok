@php
    use CeplokUi\DesignKit\Support\Bound;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\FieldId;
    use CeplokUi\DesignKit\Support\Prop;
    use Illuminate\View\ComponentAttributeBag;
@endphp

{{--
    Navbar — header navigasi global Front Office atau Back Office.

    Bentuk data mengikuti discriminated union React. Item `destination` memakai
    anchor native; item `disclosure` memakai button dan Dropdown. Callback React
    diwakili `attributes` (wire:click, x-on:click, dan atribut HTML lain).
--}}
@props([
    'platform',
    'brand',
    'items' => [],
    'search' => null,
    'account' => null,
    'guestActions' => null,
    'notificationTrigger' => null,
    'sidebarItems' => [],
    'sidebarUser' => null,
    'darkMode' => false,
    'mobileNavigationOpen' => false,
])

@php
    $platform = is_string($platform) ? $platform : '';
    if (! in_array($platform, ['front-office', 'back-office'], true)) {
        Prop::violation('Prop `platform` pada <x-ceplok::navbar> harus `front-office` atau `back-office`.');
    }
    $darkMode = Prop::bool($darkMode, false, 'navbar', 'dark-mode');
    $bound = Bound::has($attributes);
    $mobileNavigationOpen = Bound::bool($attributes, Prop::bool($mobileNavigationOpen, false, 'navbar', 'mobile-navigation-open'));
    $brand = (array) $brand;
    foreach (['content', 'href', 'ariaLabel'] as $key) {
        if (! array_key_exists($key, $brand)) Prop::violation("Prop `brand` pada <x-ceplok::navbar> memerlukan `{$key}`.");
    }
    if ($platform === 'front-office' && $account !== null && $guestActions !== null) {
        Prop::violation('<x-ceplok::navbar platform="front-office"> memakai salah satu `account` atau `guest-actions`.');
    }
    if ($platform === 'front-office' && $account === null && $notificationTrigger !== null) {
        Prop::violation('<x-ceplok::navbar platform="front-office"> hanya menerima `notification-trigger` bersama `account`.');
    }
    if ($platform === 'front-office' && ($sidebarItems !== [] || $sidebarUser !== null)) {
        Prop::violation('<x-ceplok::navbar platform="front-office"> tidak memakai `sidebar-items` atau `sidebar-user`.');
    }
    if ($platform === 'back-office' && ($account !== null || $guestActions !== null)) {
        Prop::violation('<x-ceplok::navbar platform="back-office"> menempatkan identitas pada `sidebar-user`, bukan `account` atau `guest-actions`.');
    }
    if ($platform === 'back-office' && $search !== null && empty($search['categories'])) {
        Prop::violation('<x-ceplok::navbar platform="back-office"> memerlukan `search.categories` ketika Search digunakan.');
    }
    if ($notificationTrigger !== null && ! filled($notificationTrigger['ariaLabel'] ?? null)) {
        Prop::violation('Prop `notification-trigger.ariaLabel` pada <x-ceplok::navbar> wajib diisi.');
    }
    if ($guestActions !== null) {
        foreach (['label', 'href'] as $key) {
            if (! filled($guestActions['signIn'][$key] ?? null)) Prop::violation("Prop `guest-actions.signIn.{$key}` pada <x-ceplok::navbar> wajib diisi.");
            if (isset($guestActions['signUp']) && ! filled($guestActions['signUp'][$key] ?? null)) Prop::violation("Prop `guest-actions.signUp.{$key}` pada <x-ceplok::navbar> wajib diisi.");
        }
    }
    if ($account !== null) {
        if (! filled($account['name'] ?? null)) Prop::violation('Prop `account.name` pada <x-ceplok::navbar> wajib diisi.');
        $accountIds = [];
        foreach ((array) ($account['items'] ?? []) as $index => $accountItem) {
            $accountItem = (array) $accountItem;
            foreach (['id', 'type', 'label'] as $key) {
                if (! filled($accountItem[$key] ?? null)) Prop::violation("Prop `account.items.{$index}.{$key}` pada <x-ceplok::navbar> wajib diisi.");
            }
            if (isset($accountIds[$accountItem['id']])) Prop::violation("Prop `account.items` memerlukan `id` unik; `{$accountItem['id']}` dipakai lebih dari sekali.");
            $accountIds[$accountItem['id']] = true;
            if (! in_array($accountItem['type'], ['destination', 'action'], true)) Prop::violation("Prop `account.items.{$index}.type` harus `destination` atau `action`.");
            if ($accountItem['type'] === 'destination' && ! filled($accountItem['href'] ?? null)) Prop::violation("Destination `account.items.{$index}` memerlukan `href`.");
            if ($accountItem['type'] === 'action' && empty($accountItem['attributes'])) Prop::violation("Action `account.items.{$index}` memerlukan `attributes` callback.");
        }
    }
    $primaryIds = [];
    foreach ((array) $items as $index => $item) {
        $item = (array) $item;
        foreach (['id', 'type', 'label'] as $key) {
            if (! filled($item[$key] ?? null)) Prop::violation("Prop `items.{$index}.{$key}` pada <x-ceplok::navbar> wajib diisi.");
        }
        if (isset($primaryIds[$item['id']])) Prop::violation("Prop `items` pada <x-ceplok::navbar> memerlukan `id` unik; `{$item['id']}` dipakai lebih dari sekali.");
        $primaryIds[$item['id']] = true;
        if (! in_array($item['type'], ['destination', 'disclosure'], true)) {
            Prop::violation("Prop `items.{$index}.type` pada <x-ceplok::navbar> harus `destination` atau `disclosure`.");
        }
        if ($item['type'] === 'destination') {
            if (! filled($item['href'] ?? null)) Prop::violation("Destination `items.{$index}` pada <x-ceplok::navbar> memerlukan `href`.");
            if (! empty($item['children'])) Prop::violation("Destination `items.{$index}` pada <x-ceplok::navbar> tidak menerima `children`.");
            continue;
        }
        if (filled($item['href'] ?? null)) Prop::violation("Disclosure `items.{$index}` pada <x-ceplok::navbar> tidak menerima `href`.");
        if (empty($item['children']) || ! is_array($item['children'])) Prop::violation("Disclosure `items.{$index}` pada <x-ceplok::navbar> memerlukan `children`.");
        $childIds = [];
        foreach ((array) ($item['children'] ?? []) as $childIndex => $child) {
            $child = (array) $child;
            foreach (['id', 'type', 'label', 'href'] as $key) {
                if (! filled($child[$key] ?? null)) Prop::violation("Prop `items.{$index}.children.{$childIndex}.{$key}` pada <x-ceplok::navbar> wajib diisi.");
            }
            if ($child['type'] !== 'destination') Prop::violation("Child `items.{$index}.children.{$childIndex}` pada <x-ceplok::navbar> harus bertipe `destination`.");
            if (isset($childIds[$child['id']])) Prop::violation("Children pada disclosure `{$item['id']}` memerlukan `id` unik; `{$child['id']}` dipakai lebih dari sekali.");
            $childIds[$child['id']] = true;
        }
    }
    $id = FieldId::for($attributes, 'navbar');
    $mobileId = $id.'-mobile-navigation';
    $wrapperKeys = array_keys($attributes->whereStartsWith(['wire:model', 'x-model', 'wire:key'])->getAttributes());
    $wrapper = $attributes->only($wrapperKeys);
    $navAttributes = $attributes->except([...$wrapperKeys, 'data-theme']);
    $explicitTheme = in_array($attributes->get('data-theme'), ['light', 'dark'], true) ? $attributes->get('data-theme') : null;
    $effectiveDark = $explicitTheme ? $explicitTheme === 'dark' : $darkMode;
    $navbarTheme = $explicitTheme ?? ($darkMode ? 'dark' : null);

    $toDropdownItems = function (array $children): array {
        return array_map(function (array $item): array {
            return array_filter([
                'label' => $item['label'] ?? '',
                'description' => $item['description'] ?? null,
                'icon' => $item['icon'] ?? null,
                'href' => empty($item['disabled']) ? ($item['href'] ?? null) : null,
                'target' => empty($item['disabled']) ? ($item['target'] ?? null) : null,
                'rel' => empty($item['disabled']) ? ($item['rel'] ?? null) : null,
                'current' => !empty($item['current']),
                'disabled' => !empty($item['disabled']),
                'tone' => $item['tone'] ?? 'default',
                'attributes' => $item['attributes'] ?? [],
            ], fn ($value) => $value !== null && $value !== false);
        }, $children);
    };
    $accountItems = $account ? $toDropdownItems((array) ($account['items'] ?? [])) : [];
    $mobileItems = $platform === 'back-office' ? $sidebarItems : $items;
    $mobileSidebarItems = array_map(function (array $item): array {
        $children = array_map(function (array $child): array {
            $attributes = (array) ($child['attributes'] ?? []);
            if (empty($child['disabled'])) $attributes['data-navbar-mobile-destination'] = '';
            return array_filter([
                'label' => $child['label'] ?? '',
                'href' => $child['href'] ?? null,
                'external' => empty($child['disabled']) && ($child['target'] ?? null) === '_blank',
                'active' => !empty($child['current']) || !empty($child['active']),
                'disabled' => !empty($child['disabled']),
                'icon' => $child['icon'] ?? null,
                'attributes' => $attributes,
            ], fn ($value) => $value !== null && $value !== false && $value !== []);
        }, (array) ($item['children'] ?? []));
        $attributes = (array) ($item['attributes'] ?? []);
        if (empty($item['disabled']) && $children === []) $attributes['data-navbar-mobile-destination'] = '';
        return array_filter([
            'label' => $item['label'] ?? '',
            'href' => $children === [] ? ($item['href'] ?? null) : null,
            'external' => empty($item['disabled']) && ($item['target'] ?? null) === '_blank',
            'active' => !empty($item['current']) || !empty($item['active']),
            'disabled' => !empty($item['disabled']),
            'icon' => $item['icon'] ?? null,
            'attributes' => $attributes,
            'children' => $children,
        ], fn ($value) => $value !== null && $value !== false && $value !== []);
    }, $mobileItems);
    $mobileSidebarUser = $platform === 'back-office'
        ? $sidebarUser
        : ($account ? array_merge($account, ['profileLabel' => $account['description'] ?? 'Lihat Profil']) : null);
    $drawerDestinationAttributes = function (array $item): array {
        if (! empty($item['disabled'])) return [];
        $attributes = (array) ($item['attributes'] ?? []);
        if (! empty($item['target'])) $attributes['target'] = $item['target'];
        $rel = ($item['target'] ?? null) === '_blank' ? ($item['rel'] ?? 'noopener noreferrer') : ($item['rel'] ?? null);
        if (! empty($rel)) $attributes['rel'] = $rel;
        $consumerClick = $attributes['x-on:click'] ?? '';
        $closeAccepted = "queueMicrotask(() => { if (!\$event.defaultPrevented && \$event.button === 0 && !\$event.altKey && !\$event.ctrlKey && !\$event.metaKey && !\$event.shiftKey) close() })";
        $attributes['x-on:click'] = trim($consumerClick.'; '.$closeAccepted, '; ');
        return $attributes;
    };
    $drawerItems = array_map(function (array $item) use ($drawerDestinationAttributes): array {
        $isDisclosure = ($item['type'] ?? null) === 'disclosure';
        $children = array_map(function (array $child) use ($drawerDestinationAttributes): array {
            return array_filter([
                'id' => $child['id'] ?? null,
                'label' => $child['label'] ?? '',
                'href' => empty($child['disabled']) ? ($child['href'] ?? null) : null,
                'active' => ! empty($child['current']),
                'disabled' => ! empty($child['disabled']),
                'icon' => $child['icon'] ?? null,
                'attributes' => $drawerDestinationAttributes($child),
            ], fn ($value) => $value !== null && $value !== false && $value !== []);
        }, (array) ($item['children'] ?? []));
        return array_filter([
            'id' => $item['id'] ?? null,
            'label' => $item['label'] ?? '',
            'href' => ! $isDisclosure && empty($item['disabled']) ? ($item['href'] ?? null) : null,
            'active' => !empty($item['current']),
            'disabled' => !empty($item['disabled']),
            'icon' => $item['icon'] ?? null,
            'expanded' => $isDisclosure ? collect($item['children'] ?? [])->contains(fn ($child) => ! empty($child['current'])) : null,
            'attributes' => $isDisclosure ? ($item['attributes'] ?? []) : $drawerDestinationAttributes($item),
            'children' => $children,
        ], fn ($value) => $value !== null && $value !== false && $value !== []);
    }, $items);
    $hasMobileNavigation = $mobileSidebarItems !== [] || $mobileSidebarUser !== null || $guestActions !== null;
    $hasMobileDrawer = $platform === 'back-office' && $drawerItems !== [];
    $desktopItemPadding = $platform === 'back-office'
        ? 'px-[clamp(6px,calc(2.495192px+0.480769cqw),8px)]'
        : 'px-[clamp(6px,calc(1.07692px+0.480769vw),8px)]';
    $desktopNavigationGap = $platform === 'back-office'
        ? 'gap-[clamp(4px,calc(-3.009615px+0.961538cqw),8px)]'
        : 'gap-[clamp(4px,calc(-5.84615px+0.961538vw),8px)]';
    $currentDisclosureIds = collect($items)
        ->filter(fn ($item) => ($item['type'] ?? null) === 'disclosure' && collect($item['children'] ?? [])->contains(fn ($child) => ! empty($child['current'])))
        ->pluck('id')
        ->values()
        ->all();
@endphp

<div
    x-data="ceplokNavbar"
    x-modelable="mobileOpen"
    data-open="{{ $mobileNavigationOpen ? 'true' : 'false' }}"
    data-navbar-platform="{{ $platform }}"
    data-navbar-item-count="{{ count($items) }}"
    data-navbar-current-disclosures='@json($currentDisclosureIds)'
    {{ $wrapper->class('contents') }}
>
    <header
        {{ $navAttributes->class(['relative z-30 border-b border-border bg-surface text-content', '[container-type:inline-size]' => $platform === 'back-office'])->merge($navbarTheme ? ['data-theme' => $navbarTheme] : []) }}
    >
        <div @class([
            'hidden min-h-[93px] items-center lg:flex',
            'mx-auto gap-[clamp(12px,calc(-37.2308px+4.80769vw),32px)] w-[calc(100%-clamp(40px,calc(-19.07692px+5.76924vw),64px))] max-w-[1280px] xl:w-max xl:min-w-[min(1280px,calc(100%-clamp(40px,calc(-19.07692px+5.76924vw),64px)))] xl:max-w-[min(1320px,calc(100%-clamp(40px,calc(-19.07692px+5.76924vw),64px)))]' => $platform === 'front-office',
            'w-full gap-[clamp(12px,calc(-23.03846px+4.807692cqw),32px)] px-8' => $platform === 'back-office',
        ])>
            @if ($platform === 'front-office')
                <a href="{{ $brand['href'] }}" aria-label="{{ $brand['ariaLabel'] }}" class="shrink-0 rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                    {{ $brand['content'] }}
                </a>
            @endif

            @if ($search)
                <div data-navbar-search @class([
                    'hidden w-full lg:block',
                    'min-w-[180px] w-[348px] max-w-[348px] flex-[0_1_348px]' => $platform === 'front-office',
                    'min-w-[360px] w-[420px] max-w-[480px] flex-[0_1_420px]' => $platform === 'back-office',
                ]) @if (array_key_exists('darkMode', $search)) data-theme="{{ $search['darkMode'] ? 'dark' : 'light' }}" @endif>
                    <x-ceplok::search
                        :label="$search['label'] ?? null"
                        :helper-text="$search['helperText'] ?? null"
                        :button-label="$search['buttonLabel'] ?? 'Cari'"
                        :trigger="$search['trigger'] ?? 'enter'"
                        :debounce="$search['debounce'] ?? 300"
                        :platform="$search['platform'] ?? 'compact'"
                        :application="$search['application'] ?? 'default'"
                        :dark-mode="$search['darkMode'] ?? $effectiveDark"
                        :show-button="$search['showButton'] ?? ($platform === 'back-office')"
                        :categories="$search['categories'] ?? null"
                        :category-placeholder="$search['categoryPlaceholder'] ?? 'Kategori'"
                        :category="$search['category'] ?? null"
                        :category-attributes="$search['categoryAttributes'] ?? []"
                        :placeholder="$search['placeholder'] ?? 'Cari'"
                        :attributes="new ComponentAttributeBag($search['attributes'] ?? [])"
                    />
                </div>
            @endif

            <nav
                x-ref="desktopNavigation"
                aria-label="Navigasi utama"
                x-bind:data-navbar-capacity="capacityReady ? (capacityImpossible ? 'impossible' : 'ready') : 'measuring'"
                x-bind:class="{ 'invisible': !capacityReady }"
                class="relative ml-auto hidden min-w-max flex-none lg:block"
            >
                <ul class="flex items-center justify-end {{ $desktopNavigationGap }}">
                @foreach ($items as $index => $item)
                    @php
                        $item = (array) $item;
                        $type = $item['type'] ?? (empty($item['children']) ? 'destination' : 'disclosure');
                        $disabled = !empty($item['disabled']);
                        $base = 'inline-flex items-center rounded '.$desktopItemPadding.' py-1.5 text-base font-semibold leading-6 transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand';
                    @endphp
                    <li data-navbar-primary-item="{{ $index }}" x-show="desktopVisibleCount > {{ $index }}">
                    @if ($type === 'disclosure')
                        <x-ceplok::dropdown align="center" close-on-select :dark-mode="$effectiveDark" :items="$toDropdownItems((array) ($item['children'] ?? []))">
                            <x-slot:trigger raw :disabled="$disabled" class="{{ $base }} gap-1 {{ $disabled ? 'cursor-not-allowed opacity-50' : 'hover:text-brand-hover' }}">
                                {{ $item['label'] }}
                                <x-ceplok-o-chevron-down class="size-4" />
                            </x-slot:trigger>
                        </x-ceplok::dropdown>
                    @else
                        <a
                            @if (!$disabled) href="{{ $item['href'] }}" @endif
                            @if (!empty($item['current'])) aria-current="page" @endif
                            @if ($disabled) aria-disabled="true" @endif
                            @if (!$disabled && !empty($item['target'])) target="{{ $item['target'] }}" @endif
                            @if (!$disabled && (!empty($item['rel']) || ($item['target'] ?? null) === '_blank')) rel="{{ $item['rel'] ?? 'noopener noreferrer' }}" @endif
                            {{ new ComponentAttributeBag($disabled ? [] : ($item['attributes'] ?? [])) }}
                            class="{{ $base }} {{ $disabled ? 'cursor-not-allowed opacity-50' : 'hover:text-brand-hover' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endif
                    </li>
                @endforeach
                    <li data-navbar-overflow x-show="desktopVisibleCount < {{ count($items) }}" x-cloak>
                        <x-ceplok::dropdown
                            align="end"
                            :close-on-select="false"
                            content-label="Navigasi lainnya"
                            content-class="w-[min(clamp(240px,21vw,304px),calc(100vw-48px))] p-2"
                            :dark-mode="$effectiveDark"
                        >
                            <x-slot:trigger raw class="inline-flex items-center gap-1 whitespace-nowrap rounded {{ $desktopItemPadding }} py-1.5 text-base font-semibold leading-6 text-content transition-colors hover:text-brand-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                                Lainnya
                                <x-ceplok-o-chevron-down class="size-4" />
                            </x-slot:trigger>

                            <div data-navbar-overflow-content class="w-full min-w-0">
                                @foreach ($items as $index => $overflowItem)
                                    @php
                                        $overflowItem = (array) $overflowItem;
                                        $overflowType = $overflowItem['type'] ?? (empty($overflowItem['children']) ? 'destination' : 'disclosure');
                                        $overflowDisabled = ! empty($overflowItem['disabled']);
                                        $overflowAttributes = new ComponentAttributeBag($overflowDisabled ? [] : ($overflowItem['attributes'] ?? []));
                                        $overflowRow = 'flex w-full min-w-0 items-center gap-2 rounded-md px-3 py-2 text-left text-base font-semibold leading-6 text-content transition-colors';
                                    @endphp
                                    <div
                                        data-navbar-overflow-item="{{ $index }}"
                                        x-show="{{ $index }} >= desktopVisibleCount && {{ $index }} < desktopVisibleCount + overflowLoadedCount"
                                        class="min-w-0"
                                    >
                                        @if ($overflowType === 'disclosure')
                                            <button
                                                type="button"
                                                @disabled($overflowDisabled)
                                                data-baris=""
                                                x-bind:aria-expanded="overflowExpanded[@js($overflowItem['id'])] ? 'true' : 'false'"
                                                aria-controls="{{ $id }}-overflow-group-{{ $index }}"
                                                x-on:click="toggleOverflowDisclosure(@js($overflowItem['id']))"
                                                class="{{ $overflowRow }} hover:bg-dropdown-item-hover-surface focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-brand disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                @if (!empty($overflowItem['icon']))
                                                    <span class="inline-flex size-5 shrink-0 items-center justify-center [&>svg]:size-full" aria-hidden="true">{!! Content::icon($overflowItem['icon']) !!}</span>
                                                @endif
                                                <span data-navbar-overflow-label class="min-w-0 flex-1 line-clamp-2 break-words">{{ $overflowItem['label'] }}</span>
                                                <span class="size-4 shrink-0 transition-transform" x-bind:class="{ 'rotate-180': overflowExpanded[@js($overflowItem['id'])] }">
                                                    <x-ceplok-o-chevron-down class="size-full" />
                                                </span>
                                            </button>
                                            <div
                                                id="{{ $id }}-overflow-group-{{ $index }}"
                                                x-show="overflowExpanded[@js($overflowItem['id'])]"
                                                class="grid min-w-0 border-l border-border-subtle"
                                            >
                                                @foreach ((array) ($overflowItem['children'] ?? []) as $child)
                                                    @php
                                                        $child = (array) $child;
                                                        $childDisabled = ! empty($child['disabled']);
                                                        $childAttributes = new ComponentAttributeBag($childDisabled ? [] : ($child['attributes'] ?? []));
                                                        $childRel = ($child['target'] ?? null) === '_blank' ? ($child['rel'] ?? 'noopener noreferrer') : ($child['rel'] ?? null);
                                                    @endphp
                                                    @if ($childDisabled)
                                                        <span aria-disabled="true" class="{{ $overflowRow }} cursor-not-allowed pr-3 pl-7 opacity-50">
                                                            <span data-navbar-overflow-label class="min-w-0 flex-1 line-clamp-2 break-words">{{ $child['label'] ?? '' }}</span>
                                                        </span>
                                                    @else
                                                        <a
                                                            href="{{ $child['href'] }}"
                                                            @if (!empty($child['target'])) target="{{ $child['target'] }}" @endif
                                                            @if (!empty($childRel)) rel="{{ $childRel }}" @endif
                                                            @if (!empty($child['current'])) aria-current="page" @endif
                                                            data-navbar-overflow-destination
                                                            data-baris=""
                                                            {{ $childAttributes }}
                                                            class="{{ $overflowRow }} pr-3 pl-7 hover:bg-dropdown-item-hover-surface focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-brand"
                                                        >
                                                            <span data-navbar-overflow-label class="min-w-0 flex-1 line-clamp-2 break-words">{{ $child['label'] ?? '' }}</span>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif ($overflowDisabled)
                                            <span aria-disabled="true" class="{{ $overflowRow }} cursor-not-allowed opacity-50">
                                                <span data-navbar-overflow-label class="min-w-0 flex-1 line-clamp-2 break-words">{{ $overflowItem['label'] }}</span>
                                            </span>
                                        @else
                                            @php $overflowRel = ($overflowItem['target'] ?? null) === '_blank' ? ($overflowItem['rel'] ?? 'noopener noreferrer') : ($overflowItem['rel'] ?? null); @endphp
                                            <a
                                                href="{{ $overflowItem['href'] }}"
                                                @if (!empty($overflowItem['target'])) target="{{ $overflowItem['target'] }}" @endif
                                                @if (!empty($overflowRel)) rel="{{ $overflowRel }}" @endif
                                                @if (!empty($overflowItem['current'])) aria-current="page" @endif
                                                data-navbar-overflow-destination
                                                data-baris=""
                                                {{ $overflowAttributes }}
                                                class="{{ $overflowRow }} hover:bg-dropdown-item-hover-surface focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-brand"
                                            >
                                                @if (!empty($overflowItem['icon']))
                                                    <span class="inline-flex size-5 shrink-0 items-center justify-center [&>svg]:size-full" aria-hidden="true">{!! Content::icon($overflowItem['icon']) !!}</span>
                                                @endif
                                                <span data-navbar-overflow-label class="min-w-0 flex-1 line-clamp-2 break-words">{{ $overflowItem['label'] }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                                <button
                                    type="button"
                                    data-navbar-load-more
                                    x-show="desktopVisibleCount + overflowLoadedCount < {{ count($items) }}"
                                    x-on:click="loadMoreOverflow($event)"
                                    class="mt-1 flex w-full min-w-0 items-center justify-center rounded-md px-3 py-2 text-center text-base font-semibold leading-6 text-content transition-colors hover:bg-dropdown-item-hover-surface focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-brand"
                                >Muat lebih banyak</button>
                            </div>

                            <div aria-hidden="true" data-navbar-overflow-measure class="pointer-events-none fixed top-0 left-0 invisible w-[min(clamp(240px,21vw,304px),calc(100vw-48px))] p-2">
                                @foreach ($items as $measureItem)
                                    @php $measureType = $measureItem['type'] ?? null; @endphp
                                    <div data-navbar-overflow-measure-item>
                                        <div class="flex w-full min-w-0 items-center gap-2 rounded-md px-3 py-2 text-base font-semibold leading-6">
                                            @if (!empty($measureItem['icon']))<span class="size-5 shrink-0"></span>@endif
                                            <span class="min-w-0 flex-1 line-clamp-2 break-words">{{ $measureItem['label'] ?? '' }}</span>
                                            @if ($measureType === 'disclosure')<span class="size-4 shrink-0"></span>@endif
                                        </div>
                                        @if ($measureType === 'disclosure')
                                            <div x-show="overflowExpanded[@js($measureItem['id'])]" class="border-l border-border-subtle">
                                                @foreach ((array) ($measureItem['children'] ?? []) as $measureChild)
                                                    <div class="flex w-full min-w-0 items-center rounded-md py-2 pr-3 pl-7 text-base font-semibold leading-6">
                                                        <span class="min-w-0 flex-1 line-clamp-2 break-words">{{ $measureChild['label'] ?? '' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </x-ceplok::dropdown>
                    </li>
                </ul>

                <ul aria-hidden="true" data-navbar-primary-measure class="pointer-events-none invisible absolute top-0 left-0 flex w-max items-center {{ $desktopNavigationGap }}">
                    @foreach ($items as $measureItem)
                        <li data-navbar-measure-item @class([
                            'inline-flex items-center rounded '.$desktopItemPadding.' py-1.5 text-base font-semibold leading-6 whitespace-nowrap',
                            'gap-1' => ($measureItem['type'] ?? null) === 'disclosure',
                        ])>
                            {{ $measureItem['label'] ?? '' }}
                            @if (($measureItem['type'] ?? null) === 'disclosure')<span class="size-4 shrink-0"></span>@endif
                        </li>
                    @endforeach
                    <li data-navbar-measure-overflow class="inline-flex items-center gap-1 rounded {{ $desktopItemPadding }} py-1.5 text-base font-semibold leading-6 whitespace-nowrap">Lainnya<span class="size-4 shrink-0"></span></li>
                </ul>
                <span x-show="capacityReady && capacityImpossible" role="status" class="sr-only">Kapasitas Navbar tidak cukup untuk menampilkan pemicu navigasi lainnya tanpa tumpang tindih.</span>
            </nav>

            @if ($notificationTrigger || ($platform === 'front-office' && ($account || $guestActions)))
            <div
                data-navbar-identity
                @class([
                    'ml-auto flex shrink-0 items-center',
                    'gap-3' => (bool) ($account || $notificationTrigger),
                    'gap-[clamp(8px,calc(-51.0769px+5.76923vw),32px)]' => ! $account && ! $notificationTrigger && (bool) $guestActions,
                ])
            >
                @if ($notificationTrigger)
                    @php $notificationAttributes = new ComponentAttributeBag($notificationTrigger['attributes'] ?? []); @endphp
                    <button type="{{ $notificationTrigger['type'] ?? 'button' }}" aria-label="{{ $notificationTrigger['ariaLabel'] ?? 'Notifikasi' }}" {{ $notificationAttributes->except('type')->class('relative inline-flex size-10 shrink-0 items-center justify-center rounded-lg text-content transition-colors hover:bg-surface-subtle focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand') }}>
                        <svg viewBox="0 0 24 24" fill="currentColor" class="size-6" aria-hidden="true"><path d="M12 22a2.4 2.4 0 0 0 2.3-1.7H9.7A2.4 2.4 0 0 0 12 22Zm7-5-2-2.4V10a5 5 0 0 0-4-4.9V4a1 1 0 1 0-2 0v1.1A5 5 0 0 0 7 10v4.6L5 17v1h14v-1Z" /></svg>
                        @if (isset($notificationTrigger['badge']))<span class="absolute right-0.5 top-0.5 min-w-4 rounded-full bg-feedback-error px-1 text-center text-[10px] leading-4 text-white">{{ $notificationTrigger['badge'] }}</span>@endif
                    </button>
                @endif

                @if ($platform === 'front-office' && $account)
                    @if ($accountItems)
                        <x-ceplok::dropdown align="end" close-on-select :dark-mode="$effectiveDark" :items="$accountItems">
                            <x-slot:trigger raw aria-label="Buka menu akun {{ $account['name'] }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg p-1 text-left hover:bg-surface-subtle focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                                <x-ceplok::avatar size="small" :name="$account['name']" :src="$account['avatarSrc'] ?? null" :initials="$account['initials'] ?? null" />
                                <span class="hidden text-sm font-semibold sm:block">{{ $account['name'] }}</span>
                                <x-ceplok-o-chevron-down class="hidden size-4 sm:block" />
                            </x-slot:trigger>
                        </x-ceplok::dropdown>
                    @else
                        <div class="flex items-center gap-2 px-2 py-1.5">
                            <x-ceplok::avatar size="small" :name="$account['name']" :src="$account['avatarSrc'] ?? null" :initials="$account['initials'] ?? null" />
                            <span class="hidden text-sm font-semibold sm:block">{{ $account['name'] }}</span>
                        </div>
                    @endif
                @elseif ($platform === 'front-office' && $guestActions)
                    <x-ceplok::button size="base" variant="outline" :left-icon="svg('ceplok-o-arrow-right-to-bracket')" :href="$guestActions['signIn']['href']" :attributes="new ComponentAttributeBag($guestActions['signIn']['attributes'] ?? [])" class="in-data-[theme=dark]:[--color-primary-700:var(--color-gray-200)] in-data-[theme=dark]:[--color-primary-50:var(--color-gray-600)]">{{ $guestActions['signIn']['label'] }}</x-ceplok::button>
                    @if (!empty($guestActions['signUp']))
                        <x-ceplok::button size="base" :left-icon="svg('ceplok-o-edit')" :href="$guestActions['signUp']['href']" :attributes="new ComponentAttributeBag($guestActions['signUp']['attributes'] ?? [])">{{ $guestActions['signUp']['label'] }}</x-ceplok::button>
                    @endif
                @endif
            </div>
            @endif
        </div>

        @if ($platform === 'front-office')
            <div class="w-full px-4 py-4 sm:px-5 lg:hidden">
                <div class="flex min-h-10 items-center gap-3">
                    <a href="{{ $brand['href'] }}" aria-label="{{ $brand['ariaLabel'] }}" class="shrink-0 rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                        {{ $brand['content'] }}
                    </a>
                    <div class="ml-auto flex items-center gap-3">
                        @if ($notificationTrigger)
                            @php $mobileNotificationAttributes = new ComponentAttributeBag($notificationTrigger['attributes'] ?? []); @endphp
                            <button type="{{ $notificationTrigger['type'] ?? 'button' }}" aria-label="{{ $notificationTrigger['ariaLabel'] ?? 'Notifikasi' }}" {{ $mobileNotificationAttributes->except('type')->class('relative inline-flex size-9 items-center justify-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle hover:text-content focus-visible:outline-2 focus-visible:outline-brand') }}>
                                <svg viewBox="0 0 24 24" fill="currentColor" class="size-5" aria-hidden="true"><path d="M12 22a2.4 2.4 0 0 0 2.3-1.7H9.7A2.4 2.4 0 0 0 12 22Zm7-5-2-2.4V10a5 5 0 0 0-4-4.9V4a1 1 0 1 0-2 0v1.1A5 5 0 0 0 7 10v4.6L5 17v1h14v-1Z" /></svg>
                                @if (isset($notificationTrigger['badge']))<span class="absolute right-0 top-0 min-w-4 rounded-full bg-feedback-error px-1 text-center text-[10px] leading-4 text-white">{{ $notificationTrigger['badge'] }}</span>@endif
                            </button>
                        @endif
                        @if ($account)
                            <x-ceplok::avatar size="small" :name="$account['name']" :src="$account['avatarSrc'] ?? null" :initials="$account['initials'] ?? null" />
                        @endif
                        @if ($hasMobileNavigation)
                            <button
                                x-ref="mobileTrigger"
                                type="button"
                                class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle hover:text-content focus-visible:outline-2 focus-visible:outline-brand"
                                x-bind:aria-label="mobileOpen ? 'Tutup navigasi utama' : 'Buka navigasi utama'"
                                aria-haspopup="dialog"
                                aria-controls="{{ $mobileId }}"
                                x-bind:aria-expanded="mobileOpen ? 'true' : 'false'"
                                x-on:click="mobileOpen ? closeMobile() : openMobile()"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" /></svg>
                            </button>
                        @endif
                    </div>
                </div>
                @if ($search)
                    <div class="mt-3 min-w-0" @if (array_key_exists('darkMode', $search)) data-theme="{{ $search['darkMode'] ? 'dark' : 'light' }}" @endif>
                        <x-ceplok::search
                            :label="$search['label'] ?? null"
                            :helper-text="$search['helperText'] ?? null"
                            :button-label="$search['buttonLabel'] ?? 'Cari'"
                            :trigger="$search['trigger'] ?? 'enter'"
                            :debounce="$search['debounce'] ?? 300"
                            :platform="$search['platform'] ?? 'compact'"
                            :application="$search['application'] ?? 'default'"
                            :dark-mode="$search['darkMode'] ?? $effectiveDark"
                            :show-button="$search['showButton'] ?? false"
                            :categories="$search['categories'] ?? null"
                            :category-placeholder="$search['categoryPlaceholder'] ?? 'Kategori'"
                            :category="$search['category'] ?? null"
                            :category-attributes="$search['categoryAttributes'] ?? []"
                            :placeholder="$search['placeholder'] ?? 'Cari'"
                            :attributes="new ComponentAttributeBag($search['attributes'] ?? [])"
                        />
                    </div>
                @endif
            </div>
        @else
            <div @class([
                'grid min-h-[72px] grid-cols-[auto_minmax(0,1fr)_auto_auto] items-center gap-x-3 gap-y-3 px-4 py-4 sm:px-5 lg:hidden',
                'grid-rows-[minmax(40px,auto)_auto] min-[576px]:grid-cols-[auto_auto_minmax(0,1fr)_auto_auto] min-[576px]:grid-rows-none' => (bool) $search,
            ])>
                @if ($hasMobileNavigation)
                    <button
                        x-ref="mobileTrigger"
                        type="button"
                        class="col-start-1 row-start-1 inline-flex size-9 shrink-0 items-center justify-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle hover:text-content focus-visible:outline-2 focus-visible:outline-brand"
                        x-bind:aria-label="mobileOpen ? 'Tutup navigasi utama' : 'Buka navigasi utama'"
                        aria-haspopup="dialog"
                        aria-controls="{{ $mobileId }}"
                        x-bind:aria-expanded="mobileOpen ? 'true' : 'false'"
                        x-on:click="mobileOpen ? closeMobile() : openMobile()"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" /></svg>
                    </button>
                @endif
                <a href="{{ $brand['href'] }}" aria-label="{{ $brand['ariaLabel'] }}" class="col-start-2 row-start-1 min-w-0 shrink-0 rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">
                    {{ $brand['content'] }}
                </a>
                @if ($search)
                    <div class="col-span-4 row-start-2 min-w-0 min-[576px]:col-span-1 min-[576px]:col-start-3 min-[576px]:row-start-1" @if (array_key_exists('darkMode', $search)) data-theme="{{ $search['darkMode'] ? 'dark' : 'light' }}" @endif>
                        <x-ceplok::search
                            :label="$search['label'] ?? null"
                            :helper-text="$search['helperText'] ?? null"
                            :button-label="$search['buttonLabel'] ?? 'Cari'"
                            :trigger="$search['trigger'] ?? 'enter'"
                            :debounce="$search['debounce'] ?? 300"
                            :platform="$search['platform'] ?? 'compact'"
                            :application="$search['application'] ?? 'default'"
                            :dark-mode="$search['darkMode'] ?? $effectiveDark"
                            :show-button="$search['showButton'] ?? true"
                            :categories="$search['categories']"
                            :category-placeholder="$search['categoryPlaceholder'] ?? 'Kategori'"
                            :category="$search['category'] ?? null"
                            :category-attributes="$search['categoryAttributes'] ?? []"
                            :placeholder="$search['placeholder'] ?? 'Cari'"
                            :attributes="new ComponentAttributeBag($search['attributes'] ?? [])"
                        />
                    </div>
                @endif
                @if ($notificationTrigger)
                    @php $mobileNotificationAttributes = new ComponentAttributeBag($notificationTrigger['attributes'] ?? []); @endphp
                    <button type="{{ $notificationTrigger['type'] ?? 'button' }}" aria-label="{{ $notificationTrigger['ariaLabel'] ?? 'Notifikasi' }}" {{ $mobileNotificationAttributes->except('type')->class('relative col-start-3 row-start-1 inline-flex size-9 items-center justify-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle hover:text-content focus-visible:outline-2 focus-visible:outline-brand min-[576px]:col-start-4') }}>
                        <svg viewBox="0 0 24 24" fill="currentColor" class="size-5" aria-hidden="true"><path d="M12 22a2.4 2.4 0 0 0 2.3-1.7H9.7A2.4 2.4 0 0 0 12 22Zm7-5-2-2.4V10a5 5 0 0 0-4-4.9V4a1 1 0 1 0-2 0v1.1A5 5 0 0 0 7 10v4.6L5 17v1h14v-1Z" /></svg>
                        @if (isset($notificationTrigger['badge']))<span class="absolute right-0 top-0 min-w-4 rounded-full bg-feedback-error px-1 text-center text-[10px] leading-4 text-white">{{ $notificationTrigger['badge'] }}</span>@endif
                    </button>
                @endif
                @if ($hasMobileDrawer)
                    <button
                        x-ref="drawerTrigger"
                        type="button"
                        aria-label="Buka navigasi Navbar"
                        aria-controls="{{ $id }}-drawer"
                        x-bind:aria-expanded="drawerOpen ? 'true' : 'false'"
                        x-on:click="toggleDrawer()"
                        @class([
                            'row-start-1 inline-flex size-9 items-center justify-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle hover:text-content focus-visible:outline-2 focus-visible:outline-brand',
                            'col-start-4 min-[576px]:col-start-5' => (bool) $notificationTrigger,
                            'col-start-3 min-[576px]:col-start-4' => ! $notificationTrigger,
                        ])
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-width="1.8" d="M5 6h14M9 12h10M5 18h14" /></svg>
                    </button>
                @endif
            </div>
        @endif
    </header>

    <dialog
        x-ref="mobileDialog"
        id="{{ $mobileId }}"
        x-on:cancel="onCancel($event)"
        x-on:click="onMobileDialogClick($event)"
        x-on:close="onDialogClose()"
        aria-label="Navigasi utama"
        class="fixed inset-y-0 right-auto left-0 m-0 h-full max-h-full w-[280px] max-w-[calc(100vw-2rem)] overflow-visible border-0 bg-transparent p-0 text-content shadow-none backdrop:bg-gray-900/50 lg:hidden"
        @if ($navbarTheme) data-theme="{{ $navbarTheme }}" @endif
    >
        <div class="relative h-full">
            <x-ceplok::sidebar class="h-full min-h-0! shadow-soft" :items="$mobileSidebarItems" :user="$mobileSidebarUser" :dark-mode="$effectiveDark">
                <x-slot:logo>
                    <a href="{{ $brand['href'] }}" aria-label="{{ $brand['ariaLabel'] }}">{{ $brand['content'] }}</a>
                </x-slot:logo>
                @if ($platform === 'front-office' && $guestActions)
                    <x-slot:footer>
                        <div class="grid gap-2">
                            <x-ceplok::button
                                variant="outline"
                                :left-icon="svg('ceplok-o-arrow-right-to-bracket')"
                                :href="$guestActions['signIn']['href']"
                                :attributes="(new ComponentAttributeBag($guestActions['signIn']['attributes'] ?? []))->merge(['data-navbar-mobile-guest' => ''])"
                                class="in-data-[theme=dark]:[--color-primary-700:var(--color-gray-200)] in-data-[theme=dark]:[--color-primary-50:var(--color-gray-600)]"
                            >{{ $guestActions['signIn']['label'] }}</x-ceplok::button>
                            @if (!empty($guestActions['signUp']))
                                <x-ceplok::button
                                    :left-icon="svg('ceplok-o-edit')"
                                    :href="$guestActions['signUp']['href']"
                                    :attributes="(new ComponentAttributeBag($guestActions['signUp']['attributes'] ?? []))->merge(['data-navbar-mobile-guest' => ''])"
                                >{{ $guestActions['signUp']['label'] }}</x-ceplok::button>
                            @endif
                        </div>
                    </x-slot:footer>
                @endif
            </x-ceplok::sidebar>
            <button x-ref="mobileClose" type="button" x-on:click="closeMobile()" aria-label="Tutup navigasi utama" class="absolute top-5 right-4 z-10 inline-flex size-8 items-center justify-center rounded-lg text-content-muted hover:bg-surface-subtle focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-width="1.8" d="m6 6 12 12M18 6 6 18" /></svg>
            </button>
        </div>
    </dialog>

    @if ($platform === 'back-office' && $hasMobileDrawer)
        <x-ceplok::drawer
            id="{{ $id }}-drawer"
            x-model="drawerOpen"
            position="right"
            size="s"
            eyebrow="MENU"
            aria-label="Menu"
            :items="$drawerItems"
            :dark-mode="$effectiveDark"
            class="lg:hidden"
        >
        </x-ceplok::drawer>
    @endif
</div>
