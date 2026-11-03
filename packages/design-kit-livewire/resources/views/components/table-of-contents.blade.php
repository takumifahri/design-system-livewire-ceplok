@php
    use CeplokUi\DesignKit\Enums\TableOfContentsLayout;
    use CeplokUi\DesignKit\Support\ClassToggle;
    use CeplokUi\DesignKit\Support\FieldId;
    use CeplokUi\DesignKit\Support\Prop;
    use Illuminate\Support\Js;
@endphp

{{--
    Table of Contents — daftar isi "On this page" untuk halaman panjang, setara
    TableOfContents di kit React.

    Lebar 208px, latar `toc-surface` (gray-50, gray-800 di tema gelap), garis
    rel di kiri, dan entri aktif ditandai garis brand 2px yang meluncur ke
    entri yang disorot. Warnanya dari token, jadi tema gelap cukup lewat
    `data-theme="dark"` di pembungkus atau prop `dark-mode`.

    `layout` menentukan bentuknya:
    - `auto` (bawaan): di bawah breakpoint `lg` (1024px) jadi tombol yang
      membuka Drawer dari kanan dengan teks 14px; mulai `lg` daftar biasa 12px.
    - `list`: selalu daftar biasa.
    - `drawer`: selalu tombol + Drawer.
    Drawer-nya `<x-ceplok::drawer>` (`<dialog>` modal yang menutupi layar).
    Dengan `drawer-contained`, Drawer berupa panel biasa yang muncul di dalam
    pembungkus `relative` terdekat, untuk pratinjau di dalam bingkai, mis.
    playground atau mockup ponsel. Panel itu tidak modal.

    Berbeda dari kit React: daftar di Drawer selalu dirender, tidak menunggu
    Drawer pertama kali dibuka, supaya markup dari server sama di setiap render
    Livewire.

    Entri disorot otomatis sesuai bagian yang sedang dibaca (scroll-spy). Butuh
    plugin Alpine kit. Padanan callback React:
    - `wire:model` / `x-model` mengikat id entri aktif; setiap kali scroll-spy
      atau klik memindahkan sorotan, event `active-change` dikirim dengan
      `detail.id` (onActiveChange).
    - Event `item-click` dengan `detail.id` dikirim sebelum tautan berjalan
      (onItemClick). Panggil preventDefault, mis. `x-on:item-click.prevent`,
      untuk membatalkan perpindahannya. Entri di Drawer tetap menutup Drawer.
    - `active-id` menjadikannya terkontrol: hanya entri itu yang disorot dan
      scroll-spy tidak dipasang. Nilai baru dari render ulang Livewire ikut
      dipakai.

    Judul bawaannya "On this page"; `:title="false"` menyembunyikannya
    (padanan title={null} di React).

    Komponen ini tidak menempel sendiri; beri `class="sticky top-10"` bila
    perlu, dan beri bagian yang dituju `scroll-mt-*` supaya judulnya tidak
    tertutup navbar.
--}}
@props([
    'items' => [],
    'title' => 'On this page',
    'activeId' => null,
    'scrollSpy' => true,
    'offset' => 96,
    'getHref' => null,
    'anchor' => true,
    'darkMode' => false,
    'maxHeight' => null,
    'scrollContainer' => null,
    'layout' => 'auto',
    'drawerContained' => false,
])

@php
    $scrollSpy = Prop::bool($scrollSpy, true, 'table-of-contents', 'scroll-spy');
    $offset = Prop::int($offset, 96, 'table-of-contents', 'offset', min: 0);
    $anchor = Prop::bool($anchor, true, 'table-of-contents', 'anchor');
    $darkMode = Prop::bool($darkMode, false, 'table-of-contents', 'dark-mode');
    $layout = Prop::oneOf(TableOfContentsLayout::class, $layout, TableOfContentsLayout::Auto, 'table-of-contents', 'layout');
    $drawerContained = Prop::bool($drawerContained, false, 'table-of-contents', 'drawer-contained');

    if (! is_iterable($items)) {
        Prop::violation('Prop `items` pada <x-ceplok::table-of-contents> harus array berisi [\'id\' => …, \'label\' => …].');
        $items = [];
    }

    $entries = [];
    foreach ($items as $item) {
        $id = is_array($item) ? ($item['id'] ?? null) : null;

        if (! is_string($id) || $id === '') {
            Prop::violation('Setiap item <x-ceplok::table-of-contents> butuh `id` berupa string yang sama dengan id bagian yang dituju.');

            continue;
        }

        $entries[] = ['id' => $id, 'label' => $item['label'] ?? $id, 'href' => $item['href'] ?? null];
    }

    // Sama dengan getHref di React: closure `fn (string $id) => …`, atau pola
    // dengan `{id}`, mis. "#/panduan#{id}". Bawaannya `#id`.
    $hrefOf = match (true) {
        $getHref instanceof Closure => fn (array $entry) => $entry['href'] ?? (string) $getHref($entry['id']),
        is_string($getHref) && $getHref !== '' => fn (array $entry) => $entry['href'] ?? str_replace('{id}', $entry['id'], $getHref),
        default => fn (array $entry) => $entry['href'] ?? '#'.$entry['id'],
    };

    // Angka polos berarti piksel, seperti maxHeight={320} di React; selain
    // itu nilai CSS apa adanya, mis. "calc(100vh - 8rem)".
    $maxHeight = match (true) {
        $maxHeight === null || $maxHeight === '' => null,
        is_int($maxHeight) || (is_string($maxHeight) && ctype_digit($maxHeight)) => $maxHeight.'px',
        is_string($maxHeight) => $maxHeight,
        default => null,
    };

    $controlled = $activeId !== null && $activeId !== '';
    $active = $controlled ? (string) $activeId : ($entries[0]['id'] ?? '');
    $activeLabel = collect($entries)->firstWhere('id', $active)['label'] ?? null;
    // Padanan title={null} di React adalah :title="false": Blade membuang
    // atribut bernilai null, jadi :title="null" tetap memakai judul bawaan.
    $hasTitle = ! in_array($title, [null, false, ''], true);
    $ariaLabel = $attributes->get('aria-label', 'Daftar isi halaman');

    $hasDrawer = $layout !== 'list';
    $drawerTitleId = $hasDrawer ? FieldId::for($attributes, 'table-of-contents').'-drawer-title' : null;

    $width = match ($layout) {
        'auto' => 'w-full lg:w-52',
        'list' => 'w-52',
        'drawer' => 'w-full',
    };

    // Kelas entri daftar biasa (`inline`, 12px) atau daftar di Drawer (14px
    // dengan baris 40px supaya mudah disentuh). Garis brand entri aktif
    // digambar indikator supaya bisa meluncur; garis entri sendiri cukup
    // untuk hover.
    $entryToggle = fn (array $entry, string $variant) => (new ClassToggle)
        ->always('-ml-px block w-full border-l-2 pl-3.5 text-left transition-colors')
        ->always($variant === 'inline' ? 'py-1.5 text-xs leading-5' : 'py-2.5 text-sm leading-5')
        ->always('focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand')
        ->when('active === '.Js::from($entry['id']), $entry['id'] === $active, 'border-transparent text-brand', 'border-transparent text-content-subtle hover:border-border-strong hover:text-content');
    $indicatorClass = 'pointer-events-none absolute top-0 -left-px w-0.5 rounded-full bg-brand';
    $indicatorAnimation = "'transition-[transform,height] duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none'";
@endphp

<nav
    x-data="ceplokTableOfContents"
    x-modelable="active"
    @if ($darkMode) data-theme="dark" @endif
    data-offset="{{ $offset }}"
    @if ($controlled) data-active-id="{{ $active }}" @endif
    @if (! $anchor || ! $scrollSpy) data-scroll-spy="false" @endif
    @if (! $anchor) data-anchor="false" @endif
    @if (is_string($scrollContainer) && $scrollContainer !== '') data-scroll-container="{{ $scrollContainer }}" @endif
    @if ($hasDrawer && $drawerContained) data-drawer-contained @endif
    {{ $attributes
        ->class(['bg-toc-surface transition-colors duration-300', $width])
        ->merge(['aria-label' => 'Daftar isi halaman']) }}
>
    @if ($hasDrawer)
        <button
            type="button"
            data-toc-trigger
            x-on:click="openDrawer()"
            aria-haspopup="dialog"
            aria-expanded="false"
            x-bind:aria-expanded="drawerOpen ? 'true' : 'false'"
            @class([
                'flex w-full items-center gap-3 rounded-lg border border-border bg-surface px-4 py-2.5 text-left text-sm text-content transition-colors hover:bg-surface-subtle focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand',
                'lg:hidden' => $layout === 'auto',
            ])
        >
            <x-ceplok-o-bars class="size-4 shrink-0 text-content-muted" aria-hidden="true" />
            <span class="min-w-0 flex-1 truncate">
                @if ($hasTitle)<span class="sr-only">{{ $title }}: </span>@endif
                {{-- Diganti Alpine dengan label entri aktif setiap sorotan berpindah. --}}
                <span data-toc-current>{{ $activeLabel ?? ($hasTitle ? $title : '') }}</span>
            </span>
            <x-ceplok-o-chevron-down class="size-4 shrink-0 text-content-muted" aria-hidden="true" />
        </button>

        @if ($drawerContained)
            {{-- Drawer asli memakai <dialog> + showModal(), yang selalu naik ke top
                 layer dan menutupi seluruh layar. Versi ini panel biasa yang
                 menempel di pembungkus `relative` terdekat; header dan body-nya
                 sama dengan Drawer. --}}
            @php
                $layer = (new ClassToggle)
                    ->always('absolute inset-0 z-20 overflow-hidden rounded-[inherit] transition-[visibility] duration-300')
                    ->always($layout === 'auto' ? 'lg:hidden' : '')
                    ->when('drawerOpen', false, 'visible', 'invisible');
                $backdrop = (new ClassToggle)
                    ->always('absolute inset-0 bg-gray-900/50 transition-opacity duration-300')
                    ->when('drawerOpen', false, 'opacity-100', 'opacity-0');
                $panel = (new ClassToggle)
                    ->always('absolute inset-y-0 right-0 flex w-64 max-w-[85%] flex-col bg-surface text-content shadow-2xl outline-none transition-transform duration-300 ease-out in-data-[theme=dark]:bg-gray-800')
                    ->when('drawerOpen', false, 'translate-x-0', 'translate-x-full');
            @endphp
            <div
                class="{{ $layer->classes() }}"
                x-bind:class="{{ $layer->binding() }}"
                inert
                x-bind:inert="! drawerOpen"
            >
                <div
                    aria-hidden="true"
                    x-on:click="closeDrawer()"
                    class="{{ $backdrop->classes() }}"
                    x-bind:class="{{ $backdrop->binding() }}"
                ></div>
                <div
                    role="dialog"
                    aria-labelledby="{{ $drawerTitleId }}"
                    tabindex="-1"
                    data-toc-panel
                    x-on:keydown.escape.stop="closeDrawer()"
                    class="{{ $panel->classes() }}"
                    x-bind:class="{{ $panel->binding() }}"
                >
                    <div class="flex shrink-0 items-center justify-between gap-4 px-5 pt-4 pb-2">
                        <div class="min-w-0 flex-1 space-y-1">
                            <p id="{{ $drawerTitleId }}" class="text-xs font-bold tracking-[0.14em] text-content-disabled uppercase">{{ $hasTitle ? $title : $ariaLabel }}</p>
                        </div>
                        <button
                            type="button"
                            x-on:click="closeDrawer()"
                            aria-label="Tutup drawer"
                            class="-mr-2 ml-auto inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
                        ><x-ceplok-o-close class="h-[18px] w-[18px]" aria-hidden="true" /></button>
                    </div>
                    <div class="min-h-0 flex-1 overflow-y-auto overscroll-y-contain px-5 pt-5 pb-6 text-xs text-gray-600">
                        <ul data-toc-list="drawer" class="relative border-l border-border">
                            @foreach ($entries as $entry)
                                @php
                                    $toggle = $entryToggle($entry, 'drawer');
                                    $expression = 'active === '.Js::from($entry['id']);
                                @endphp
                                <li>
                                    @if ($anchor)
                                        <a
                                            href="{{ $hrefOf($entry) }}"
                                            data-id="{{ $entry['id'] }}"
                                            x-on:click="chooseInDrawer($event, $el)"
                                            @if ($entry['id'] === $active) aria-current="location" @endif
                                            x-bind:aria-current="{{ $expression }} ? 'location' : null"
                                            class="{{ $toggle->classes() }}"
                                            x-bind:class="{{ $toggle->binding() }}"
                                        >{{ $entry['label'] }}</a>
                                    @else
                                        <button
                                            type="button"
                                            data-id="{{ $entry['id'] }}"
                                            x-on:click="chooseInDrawer($event, $el)"
                                            @if ($entry['id'] === $active) aria-current="true" @endif
                                            x-bind:aria-current="{{ $expression }} ? 'true' : null"
                                            class="{{ $toggle->classes() }}"
                                            x-bind:class="{{ $toggle->binding() }}"
                                        >{{ $entry['label'] }}</button>
                                    @endif
                                </li>
                            @endforeach
                            <li aria-hidden="true" data-toc-indicator style="display: none" class="{{ $indicatorClass }}" x-bind:class="{ {{ $indicatorAnimation }}: animated.drawer }"></li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            {{-- wire:ignore.self: morph Livewire tidak menutup <dialog> yang sedang
                 terbuka saat komponen di sekitarnya dirender ulang. --}}
            <x-ceplok::drawer
                x-model="drawerOpen"
                position="right"
                size="s"
                :dark-mode="$darkMode"
                aria-labelledby="{{ $drawerTitleId }}"
                wire:ignore.self
                :class="$layout === 'auto' ? 'lg:hidden' : ''"
            >
                <div class="flex shrink-0 items-center justify-between gap-4 px-5 pt-4 pb-2">
                    <div class="min-w-0 flex-1 space-y-1">
                        <h2 id="{{ $drawerTitleId }}" class="text-xs font-bold tracking-[0.14em] text-content-disabled uppercase">{{ $hasTitle ? $title : $ariaLabel }}</h2>
                    </div>
                    <button
                        type="button"
                        x-on:click="close()"
                        aria-label="Tutup drawer"
                        @class([
                            '-mr-2 ml-auto inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-gray-500 transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600',
                            $darkMode ? 'hover:bg-gray-700 hover:text-white' : 'hover:bg-gray-100 hover:text-gray-700',
                        ])
                    ><x-ceplok-o-close class="h-[18px] w-[18px]" aria-hidden="true" /></button>
                </div>
                <div @class([
                    'min-h-0 flex-1 overflow-y-auto overscroll-y-contain px-5 pt-5 pb-6 text-xs',
                    $darkMode ? 'text-gray-400' : 'text-gray-600',
                ])>
                    <ul data-toc-list="drawer" class="relative border-l border-border">
                        @foreach ($entries as $entry)
                            @php
                                $toggle = $entryToggle($entry, 'drawer');
                                $expression = 'active === '.Js::from($entry['id']);
                            @endphp
                            <li>
                                @if ($anchor)
                                    <a
                                        href="{{ $hrefOf($entry) }}"
                                        data-id="{{ $entry['id'] }}"
                                        x-on:click="chooseInDrawer($event, $el)"
                                        @if ($entry['id'] === $active) aria-current="location" @endif
                                        x-bind:aria-current="{{ $expression }} ? 'location' : null"
                                        class="{{ $toggle->classes() }}"
                                        x-bind:class="{{ $toggle->binding() }}"
                                    >{{ $entry['label'] }}</a>
                                @else
                                    <button
                                        type="button"
                                        data-id="{{ $entry['id'] }}"
                                        x-on:click="chooseInDrawer($event, $el)"
                                        @if ($entry['id'] === $active) aria-current="true" @endif
                                        x-bind:aria-current="{{ $expression }} ? 'true' : null"
                                        class="{{ $toggle->classes() }}"
                                        x-bind:class="{{ $toggle->binding() }}"
                                    >{{ $entry['label'] }}</button>
                                @endif
                            </li>
                        @endforeach
                        <li aria-hidden="true" data-toc-indicator style="display: none" class="{{ $indicatorClass }}" x-bind:class="{ {{ $indicatorAnimation }}: animated.drawer }"></li>
                    </ul>
                </div>
            </x-ceplok::drawer>
        @endif
    @endif

    @if ($layout !== 'drawer')
        <div @class(['hidden lg:block' => $layout === 'auto'])>
            @if ($hasTitle)
                <p class="text-[11px] leading-4 font-bold tracking-[0.14em] text-content-disabled uppercase">{{ $title }}</p>
            @endif
            <ul
                data-toc-list="inline"
                @if ($maxHeight !== null) style="max-height: {{ $maxHeight }}" @endif
                @class([
                    'relative border-l border-border',
                    'overflow-y-auto overscroll-contain' => $maxHeight !== null,
                    'mt-3' => $hasTitle,
                ])
            >
                @foreach ($entries as $entry)
                    @php
                        $toggle = $entryToggle($entry, 'inline');
                        $expression = 'active === '.Js::from($entry['id']);
                    @endphp
                    <li>
                        @if ($anchor)
                            <a
                                href="{{ $hrefOf($entry) }}"
                                data-id="{{ $entry['id'] }}"
                                x-on:click="choose($event, $el.dataset.id)"
                                @if ($entry['id'] === $active) aria-current="location" @endif
                                x-bind:aria-current="{{ $expression }} ? 'location' : null"
                                class="{{ $toggle->classes() }}"
                                x-bind:class="{{ $toggle->binding() }}"
                            >{{ $entry['label'] }}</a>
                        @else
                            {{-- Tanpa anchor entri tidak menuju ke mana pun, jadi tombol, bukan
                                 tautan: tidak ada alamat palsu yang bisa disalin atau dibuka. --}}
                            <button
                                type="button"
                                data-id="{{ $entry['id'] }}"
                                x-on:click="choose($event, $el.dataset.id)"
                                @if ($entry['id'] === $active) aria-current="true" @endif
                                x-bind:aria-current="{{ $expression }} ? 'true' : null"
                                class="{{ $toggle->classes() }}"
                                x-bind:class="{{ $toggle->binding() }}"
                            >{{ $entry['label'] }}</button>
                        @endif
                    </li>
                @endforeach
                {{-- Garis brand 2px yang meluncur ke entri aktif. Posisinya diukur Alpine
                     dari entri ber-aria-current; transisinya baru dinyalakan setelah
                     posisi pertama terpasang supaya tidak meluncur dari atas saat dimuat. --}}
                <li aria-hidden="true" data-toc-indicator style="display: none" class="{{ $indicatorClass }}" x-bind:class="{ {{ $indicatorAnimation }}: animated.inline }"></li>
            </ul>
        </div>
    @endif
</nav>
