@php
    use CeplokUi\DesignKit\Enums\SearchApplication;
    use CeplokUi\DesignKit\Enums\SearchPlatform;
    use CeplokUi\DesignKit\Enums\SearchTrigger;
    use CeplokUi\DesignKit\Support\Bound;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\FieldId;
    use CeplokUi\DesignKit\Support\Options;
    use CeplokUi\DesignKit\Support\Prop;
    use Illuminate\Support\Js;
    use Illuminate\View\ComponentAttributeBag;
@endphp

{{--
    Search Form — kolom pencarian dengan tombol cari.

    Tanpa `categories` ia satu kotak berisi ikon kaca pembesar, isian, dan
    tombol berlabel. Dengan `categories` ia tiga ruas menyatu: dropdown
    kategori, isian, lalu tombol ikon. Daftar kategorinya panel Dropdown, sama
    dengan daftar pilihan Select.

    Event `search` (`detail.value`, `detail.category`), padanan onSearch di
    React, dikirim saat Enter ditekan di dalam field atau tombol cari ditekan;
    dengan `trigger="change"`/`"auto"` juga setiap isian berubah (jeda
    `debounce`, bawaan 300ms) dan setiap kategori diganti. Mengganti kategori
    juga memicu `category-change` (`detail.value`). Kedua event dikirim dari
    elemen input-nya, mis. `wire:search="cari($event.detail.value)"`.

    Atribut lain (`name`, `wire:model`, `placeholder`, …) diteruskan ke
    `<input type="search">`-nya; `class` dan `wire:key` ke pembungkus terluar.
    Kategori awalnya lewat `category`; `category-attributes` diteruskan ke
    `<select>` kategorinya, mis. `['wire:model.live' => 'kategori']`.

    Pembungkusnya `role="search"`, bukan `<form>`: kolom pencarian sering
    dipasang di dalam formulir lain, dan form bersarang tidak sah.
--}}
@props([
    'label' => null,
    'helperText' => null,
    'buttonLabel' => 'Cari',
    'trigger' => 'enter',
    'debounce' => 300,
    'showButton' => null,
    'platform' => 'default',
    'application' => 'default',
    'categories' => null,
    'categoryPlaceholder' => 'Kategori',
    'category' => null,
    'categoryAttributes' => [],
    'darkMode' => false,
])

@php
    $searchTrigger = Prop::oneOf(SearchTrigger::class, $trigger, SearchTrigger::Enter, 'search', 'trigger');
    $debounce = Prop::int($debounce, 300, 'search', 'debounce', min: 0);
    $platform = Prop::oneOf(SearchPlatform::class, $platform, SearchPlatform::Default, 'search', 'platform');
    $application = Prop::oneOf(SearchApplication::class, $application, SearchApplication::Default, 'search', 'application');
    $darkMode = Prop::bool($darkMode, false, 'search', 'dark-mode');

    $id = FieldId::for($attributes, 'search');
    $helperId = $id.'-helper';
    $disabled = ! in_array($attributes->get('disabled', false), [false, null, 'false'], true);
    $hasLabel = Content::filled($label);
    $hasHelper = Content::filled($helperText);

    // Tinggi kotak, ukuran ikon, ukuran teks, dan ukuran tombol per platform.
    // Tombolnya bukan Button: aksen purple Button purple-700 sedangkan tombol
    // cari purple-500, dan jarak sampingnya 13px, bukan px-4.
    $platforms = [
        'default' => ['field' => 'h-13.5', 'icon' => 'size-4.5', 'text' => 'text-base', 'button' => 'h-[38px] px-[13px] text-sm'],
        'mobile' => ['field' => 'h-12.5', 'icon' => 'size-3.5', 'text' => 'text-sm', 'button' => 'h-[34px] px-[13px] text-xs'],
        'compact' => ['field' => 'h-[37px]', 'icon' => 'size-3.5', 'text' => 'text-sm', 'button' => 'h-[25px] px-[10px] text-xs'],
    ];

    // `focus` dipakai varian polos yang garisnya di pembungkus, `groupFocus`
    // varian kategori yang garisnya tersebar di dua ruas.
    $accents = [
        'default' => ['solid' => 'bg-primary-700 hover:bg-primary-800 focus-visible:outline-primary-700', 'focus' => 'focus-within:border-primary-500', 'groupFocus' => 'group-focus-within:border-primary-500'],
        'purple' => ['solid' => 'bg-purple-500 hover:bg-purple-600 focus-visible:outline-purple-500', 'focus' => 'focus-within:border-purple-500', 'groupFocus' => 'group-focus-within:border-purple-500'],
    ];
    $size = $platforms[$platform];
    $accent = $accents[$application];

    // Kategori: placeholder ikut jadi baris pilihan biasa, seperti <option value=""> pertama.
    $categoryList = $categories === null ? [] : collect(Options::fromArray($categories))->flatMap(fn (array $group) => $group['options'])->values()->all();
    $withCategory = $categoryList !== [];
    $compactCategory = $withCategory && $platform === 'compact';
    // Search berkategori selalu punya tombol bawaan, juga pada platform compact.
    // Search polos compact tetap tanpa tombol seperti sebelumnya.
    $hasButton = $showButton === null
        ? ($withCategory || $platform !== 'compact')
        : Prop::bool($showButton, true, 'search', 'show-button');
    $categoryBag = new ComponentAttributeBag(collect($categoryAttributes)->all());
    $categoryValue = (string) ($category ?? Bound::text($categoryBag) ?? '');
    $categoryOptions = [['value' => '', 'label' => (string) $categoryPlaceholder, 'disabled' => false], ...$categoryList];
    $categoryLabel = collect($categoryOptions)->firstWhere('value', $categoryValue)['label'] ?? $categoryPlaceholder;
    $categoryLabelCompact = mb_strlen($categoryLabel) > 12
        ? mb_substr($categoryLabel, 0, 12).'…'
        : $categoryLabel;
    $categoryItems = array_map(fn (array $option) => [
        'label' => $option['label'],
        'disabled' => $option['disabled'],
        'selected' => $option['value'] === $categoryValue,
        'selected-when' => 'ceplokValue === '.Js::from($option['value']),
        'attributes' => ['x-on:click' => 'ceplokChoose('.Js::from($option['value']).')'],
    ], $categoryOptions);

    $rootClass = (string) $attributes->get('class', '');
    $root = $attributes->only(['class', 'wire:key'])->merge($darkMode ? ['data-theme' => 'dark'] : []);
    $input = $attributes
        ->except(['class', 'wire:key', 'id', 'type'])
        ->merge([
            'aria-describedby' => $hasHelper ? $helperId : null,
            'value' => $attributes->has('value') ? null : Bound::text($attributes),
        ])
        // Safari dan Chrome menambahkan tombol silang sendiri pada type="search";
        // ia bukan bagian dari rancangan ini dan tidak bisa diberi gaya, jadi dimatikan.
        ->class('min-w-0 flex-1 bg-transparent outline-none text-content placeholder:text-content-muted disabled:cursor-not-allowed disabled:text-content-disabled [&::-webkit-search-cancel-button]:appearance-none');
@endphp

<div {{ $root->class(['w-full' => ! str_contains($rootClass, 'w-')]) }} x-data="ceplokSearch(@js($searchTrigger), @js($debounce))">
    @if ($hasLabel)
        <label for="{{ $id }}" class="mb-2 block text-sm font-bold text-content">{{ $label }}</label>
    @endif

    @if ($withCategory)
        {{--
            Bentuk reguler 39px: tombol kanan 37px dengan sisa 1px di atas dan
            bawah. Bentuk compact Navbar tetap 37px untuk seluruh ruas.
        --}}
        <div
            role="search"
            @class([
                'group flex items-stretch bg-search-category-surface',
                $compactCategory ? 'h-[37px] rounded-lg' : 'h-[39px] rounded-[10px]',
            ])
        >
            <div
                @class([
                    'relative flex shrink-0 items-center rounded-l-lg border transition-colors',
                    $compactCategory ? 'w-[128px] border-field-border' : 'w-[113px] border-search-category-border',
                    'bg-search-category-surface',
                    $accent['groupFocus'] => ! $disabled,
                ])
                x-data="ceplokSelect(@js(Bound::has($categoryBag) ? null : $categoryValue), @js((string) $categoryPlaceholder))"
            >
                {{-- Pembawa nilai saja; lebar ruas kategori dikunci oleh desain. --}}
                <select
                    @disabled($disabled)
                    {{ $categoryBag->merge(['aria-hidden' => 'true', 'tabindex' => '-1'])->class('pointer-events-none absolute inset-0 h-full w-full appearance-none opacity-0') }}
                >
                    @foreach ($categoryOptions as $option)
                        {{-- wire:key: lihat Select — opsi yang diganti server dipasang sebagai elemen baru. --}}
                        <option wire:key="{{ $option['value'] }}" value="{{ $option['value'] }}" @disabled($option['disabled'])>{{ $option['label'] }}</option>
                    @endforeach
                </select>

                <x-ceplok::dropdown attached :content-label="$categoryPlaceholder" :items="$categoryItems" :dark-mode="$darkMode">
                    <x-slot:trigger
                        raw
                        :disabled="$disabled"
                        :aria-label="$categoryPlaceholder.': '.$categoryLabel"
                        :title="$categoryLabel"
                        @class([
                            'absolute inset-0 flex items-center justify-center text-sm outline-none disabled:cursor-not-allowed text-content disabled:text-content-disabled',
                            'px-[5px] py-[2px]' => $compactCategory,
                        ])
                    >
                        @if ($compactCategory)
                            <span
                                class="block max-w-[12ch] truncate text-center"
                                x-text="ceplokLabel.length > 12 ? ceplokLabel.slice(0, 12) + '…' : ceplokLabel"
                            >{{ $categoryLabelCompact }}</span>
                            <span aria-hidden="true" @class(['absolute right-[5px] flex shrink-0 items-center', $disabled ? 'text-content-disabled' : 'text-content'])>
                                <svg class="size-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 4.5 6 8.5l4-4" />
                                </svg>
                            </span>
                        @else
                            <span class="flex min-w-0 max-w-[106px] items-center gap-[10px]">
                                <span class="min-w-0 truncate" x-text="ceplokLabel">{{ $categoryLabel }}</span>
                                <span aria-hidden="true" @class(['flex shrink-0 items-center', $disabled ? 'text-content-disabled' : 'text-content'])>
                                    <svg class="size-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 4.5 6 8.5l4-4" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </x-slot:trigger>
                </x-ceplok::dropdown>
            </div>

            <div
                @class([
                    'flex min-w-0 flex-1 items-center border-y px-2.5 text-sm transition-colors',
                    'border-field-border bg-search-category-field-surface',
                    $accent['groupFocus'] => ! $disabled,
                    'rounded-r-lg border-r' => ! $hasButton,
                ])
            >
                <input id="{{ $id }}" type="search" {{ $input }} />
            </div>

            @if ($hasButton)
                <button
                    type="button"
                    x-on:click="ceplokSubmit()"
                    @disabled($disabled)
                    aria-label="{{ $buttonLabel }}"
                    @class([
                        'flex shrink-0 items-center justify-center rounded-r-lg text-white transition-colors',
                        $compactCategory ? 'w-[37px]' : 'my-px h-[37px] w-[42px]',
                        'focus-visible:outline-2 focus-visible:outline-offset-2',
                        'disabled:cursor-not-allowed disabled:opacity-50',
                        $accent['solid'],
                    ])
                >
                    {{ svg('ceplok-o-search', $compactCategory ? 'size-3.5' : 'size-4') }}
                </button>
            @endif
        </div>
    @else
        <div
            role="search"
            @class([
                'flex items-center gap-2 rounded-lg border px-2.5 transition-colors',
                $disabled ? 'border-field-border bg-field-disabled' : 'border-field-border bg-search-field-surface',
                $size['field'],
                $size['text'],
                $accent['focus'] => ! $disabled,
            ])
        >
            <span @class(['flex shrink-0 items-center', $disabled ? 'text-content-disabled' : 'text-content-muted'])>
                {{ svg('ceplok-o-search', $size['icon']) }}
            </span>

            <input id="{{ $id }}" type="search" {{ $input }} />

            @if ($hasButton)
                <button
                    type="button"
                    x-on:click="ceplokSubmit()"
                    @disabled($disabled)
                    @class([
                        'shrink-0 rounded-lg font-medium text-white transition-colors',
                        'focus-visible:outline-2 focus-visible:outline-offset-2',
                        'disabled:cursor-not-allowed disabled:opacity-50',
                        $size['button'],
                        $accent['solid'],
                    ])
                >{{ $buttonLabel }}</button>
            @endif
        </div>
    @endif

    @if ($hasHelper)
        <p id="{{ $helperId }}" @class(['mt-2 text-sm', $disabled ? 'text-content-disabled' : 'text-content-muted'])>{{ $helperText }}</p>
    @endif
</div>
