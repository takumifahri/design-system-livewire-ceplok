@php
    use CeplokUi\DesignKit\Support\ClassToggle;
    use CeplokUi\DesignKit\Support\Prop;
    use Illuminate\Support\Js;
@endphp

{{--
    Table of Contents — daftar isi "On this page" untuk halaman panjang, setara
    TableOfContents di kit React dengan layout `list`.

    Lebar 208px, latar `toc-surface` (gray-50, gray-800 di tema gelap), garis
    rel di kiri, dan entri aktif ditandai garis brand 2px yang meluncur ke
    entri yang disorot. Warnanya dari token, jadi tema gelap cukup lewat
    `data-theme="dark"` di pembungkus atau prop `dark-mode`.

    Berbeda dari kit React: baru layout `list` yang tersedia. Layout `auto` dan
    `drawer` (tombol yang membuka Drawer di bawah 1024px) menunggu Drawer
    di-port, jadi prop `layout` dan `drawer-contained` belum ada. Di layar
    sempit, letakkan daftar ini di atas isi halaman atau sembunyikan.

    Entri disorot otomatis sesuai bagian yang sedang dibaca (scroll-spy). Butuh
    plugin Alpine kit. Padanan callback React:
    - `wire:model` / `x-model` mengikat id entri aktif; setiap kali scroll-spy
      atau klik memindahkan sorotan, event `active-change` dikirim dengan
      `detail.id` (onActiveChange).
    - Event `item-click` dengan `detail.id` dikirim sebelum tautan berjalan
      (onItemClick). Panggil preventDefault, mis. `x-on:item-click.prevent`,
      untuk membatalkan perpindahannya.
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
])

@php
    $scrollSpy = Prop::bool($scrollSpy, true, 'table-of-contents', 'scroll-spy');
    $offset = Prop::int($offset, 96, 'table-of-contents', 'offset', min: 0);
    $anchor = Prop::bool($anchor, true, 'table-of-contents', 'anchor');
    $darkMode = Prop::bool($darkMode, false, 'table-of-contents', 'dark-mode');

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
    // Padanan title={null} di React adalah :title="false": Blade membuang
    // atribut bernilai null, jadi :title="null" tetap memakai judul bawaan.
    $hasTitle = ! in_array($title, [null, false, ''], true);
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
    {{ $attributes
        ->class(['bg-toc-surface transition-colors duration-300', 'w-52'])
        ->merge(['aria-label' => 'Daftar isi halaman']) }}
>
    <div>
        @if ($hasTitle)
            <p class="text-[11px] leading-4 font-bold tracking-[0.14em] text-content-disabled uppercase">{{ $title }}</p>
        @endif
        <ul
            x-ref="list"
            @if ($maxHeight !== null) style="max-height: {{ $maxHeight }}" @endif
            @class([
                'relative border-l border-border',
                'overflow-y-auto overscroll-contain' => $maxHeight !== null,
                'mt-3' => $hasTitle,
            ])
        >
            @foreach ($entries as $entry)
                @php
                    $isActive = $entry['id'] === $active;
                    $expression = 'active === '.Js::from($entry['id']);
                    // Garis brand entri aktif digambar indikator di bawah supaya bisa
                    // meluncur; garis entri sendiri cukup untuk hover.
                    $toggle = (new ClassToggle)
                        ->always('-ml-px block w-full border-l-2 pl-3.5 text-left transition-colors')
                        ->always('py-1.5 text-xs leading-5')
                        ->always('focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand')
                        ->when($expression, $isActive, 'border-transparent text-brand', 'border-transparent text-content-subtle hover:border-border-strong hover:text-content');
                @endphp
                <li>
                    @if ($anchor)
                        <a
                            href="{{ $hrefOf($entry) }}"
                            data-id="{{ $entry['id'] }}"
                            x-on:click="choose($event, $el.dataset.id)"
                            @if ($isActive) aria-current="location" @endif
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
                            @if ($isActive) aria-current="true" @endif
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
            <li
                aria-hidden="true"
                x-ref="indicator"
                style="display: none"
                class="pointer-events-none absolute top-0 -left-px w-0.5 rounded-full bg-brand"
                x-bind:class="{ 'transition-[transform,height] duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] motion-reduce:transition-none': animate }"
            ></li>
        </ul>
    </div>
</nav>
