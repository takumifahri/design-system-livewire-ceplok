@php
    use CeplokUi\DesignKit\Enums\DropdownAlign;
    use CeplokUi\DesignKit\Enums\DropdownItemTone;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\FieldId;
    use CeplokUi\DesignKit\Support\Prop;
    use Illuminate\View\ComponentAttributeBag;
@endphp

{{--
    Dropdown — panel yang dibuka dari sebuah tombol.

    Tombolnya slot `trigger`: dirender sebagai Button kit dengan atribut slot
    sebagai props-nya (`<x-slot:trigger variant="outline">Aksi</x-slot:trigger>`),
    atau sebagai `<button>` polos dengan `raw`. Dropdown yang merendernya,
    supaya atribut Popover dan ARIA-nya ikut dari server dan bertahan saat
    Livewire merender ulang.

    Isinya `items` atau `groups` (`id`, `label`, `separator`, `items`). Tiap
    item berisi `label`, opsional `description`, `icon` (markup atau nama ikon),
    `href` (dirender sebagai tautan), `tone` (`default` | `danger`), `disabled`,
    dan `attributes` untuk tombolnya, mis. `['wire:click' => 'hapus(3)']`.
    Menekan sebuah baris menutup panel. Tanpa items dan groups, slot bawaan
    yang menjadi isi panel.

    Begitu satu item memakai `selected`, panel menjadi daftar pilihan
    (`listbox`) yang ditelusuri dengan panah; semua item lain harus memakainya
    juga. `selected-when` (ekspresi Alpine) membuat `aria-selected`-nya ikut
    berganti tanpa render ulang server.

    `align` mengatur perataan panel; `close-on-select` menentukan apakah item
    generated menutup panel. Event `dropdown-close` menutup isi kustom secara
    eksplisit; event yang dikirim dari luar harus membawa `detail.panelId`.
    `content-label` menamai daftar pilihan atau panel slot kustom; `content-class` menambah class panel;
    `dark-mode` membuat boundary tema gelap. Atribut lain ke pembungkus terluar.
--}}
@props([
    'items' => null,
    'groups' => null,
    'align' => 'start',
    'closeOnSelect' => true,
    'contentLabel' => null,
    'contentClass' => null,
    'panelId' => null,
    'darkMode' => false,
    'trigger',
])

@php
    $align = Prop::oneOf(DropdownAlign::class, $align, DropdownAlign::Start, 'dropdown', 'align');
    $closeOnSelect = Prop::bool($closeOnSelect, true, 'dropdown', 'close-on-select');
    $darkMode = Prop::bool($darkMode, false, 'dropdown', 'dark-mode');
    $panelId ??= filled($attributes->get('id')) ? $attributes->get('id').'-panel' : FieldId::for($attributes, 'dropdown');

    $list = collect($groups ?? ($items !== null ? [['id' => 'utama', 'items' => $items]] : []))
        ->map(fn ($group) => [
            'label' => filled($group['label'] ?? null) ? (string) $group['label'] : null,
            'separator' => ! empty($group['separator']),
            'items' => collect($group['items'] ?? [])->values()->all(),
        ])
        ->all();
    $allItems = collect($list)->flatMap(fn (array $group) => $group['items']);
    $hasList = $list !== [];
    $selection = $allItems->contains(fn ($item) => array_key_exists('selected', $item));

    if ($selection && $allItems->contains(fn ($item) => ! array_key_exists('selected', $item) || isset($item['href']))) {
        Prop::violation('<x-ceplok::dropdown> tidak bisa mencampur baris aksi dan baris pilihan. Begitu satu item memakai `selected`, semua item menjadi pilihan (listbox); aksi tombol atau tautan, termasuk item ber-`href`, harus di Dropdown terpisah.');
    }

    // Satu kelompok tanpa judul dan tanpa pemisah tidak perlu pembungkus: pada
    // daftar pilihan `option` harus menjadi anak langsung `listbox`.
    $plain = count($list) === 1 && $list[0]['label'] === null && ! $list[0]['separator'];

    $tones = [
        'default' => 'text-dropdown-item-content hover:bg-dropdown-item-hover-surface',
        'danger' => 'text-feedback-error hover:bg-dropdown-item-danger-hover-surface',
    ];
    $base = 'flex w-full items-center gap-3 rounded-md px-4 py-2 text-left text-sm leading-normal font-medium transition-colors';

    $triggerAttributes = ($trigger->attributes ?? new ComponentAttributeBag)->merge([
        'popovertarget' => $panelId,
        'popovertargetaction' => 'toggle',
        'aria-expanded' => 'false',
        'x-bind:aria-expanded' => "ceplokOpen ? 'true' : 'false'",
    ] + ($selection ? ['aria-haspopup' => 'listbox', 'aria-controls' => $panelId] : []));
    $raw = $triggerAttributes->has('raw');
@endphp

<div
    {{ $attributes->merge(['data-theme' => $darkMode ? 'dark' : null])->class('inline-block') }}
    x-data="ceplokDropdown(@js($align), @js($closeOnSelect))"
    x-on:dropdown-close.stop="ceplokClose()"
    x-on:dropdown-close.window="if ($event.detail?.panelId === @js($panelId)) ceplokClose()"
>
    @if ($raw)
        <button {{ $triggerAttributes->except('raw')->merge(['type' => 'button']) }}>{{ $trigger }}</button>
    @else
        <x-ceplok::button :attributes="$triggerAttributes">{{ $trigger }}</x-ceplok::button>
    @endif

    <div
        id="{{ $panelId }}"
        popover="auto"
        @if ($selection) role="listbox" @if (filled($contentLabel)) aria-label="{{ $contentLabel }}" @endif
        @elseif (! $hasList && filled($contentLabel)) role="group" aria-label="{{ $contentLabel }}"
        @endif
        @class([
            'fixed inset-auto m-0 overflow-x-hidden overflow-y-auto overscroll-contain rounded-lg shadow-dropdown [overflow-wrap:anywhere]',
            'bg-dropdown-surface text-dropdown-content',
            'py-1' => $hasList,
            $contentClass,
        ])
    >
        @if (! $hasList)
            {{ $slot }}
        @else
            @foreach ($list as $group)
                @if (! $plain)
                    <div @if ($selection) role="group" @if ($group['label'] !== null) aria-label="{{ $group['label'] }}" @endif @endif>
                @endif

                @if ($group['separator'])
                    <hr aria-hidden="true" class="my-1 border-0 border-t border-border">
                @endif

                @if ($group['label'] !== null)
                    {{-- Pada daftar pilihan kelompoknya sudah bernama lewat aria-label, jadi judul ini disembunyikan dari pembaca layar. --}}
                    <p @if ($selection) aria-hidden="true" @endif class="px-4 pt-2 pb-1 text-caption font-bold tracking-wide text-dropdown-group-label uppercase">{{ $group['label'] }}</p>
                @endif

                @foreach ($group['items'] as $item)
                    @php
                        $tone = Prop::oneOf(DropdownItemTone::class, $item['tone'] ?? 'default', DropdownItemTone::Default, 'dropdown', 'items.tone');
                        $disabled = ! empty($item['disabled']);
                        $icon = $item['icon'] ?? null;
                        $description = $item['description'] ?? null;
                        $extra = new ComponentAttributeBag($item['attributes'] ?? []);
                        $current = ! empty($item['href']) ? ($item['current'] ?? null) : null;
                        $current = $current === true || $current === 'true' ? 'page' : (($current === false || $current === 'false') ? null : $current);
                    @endphp

                    @php ob_start(); @endphp
                    @if (Content::filled($icon))
                        {{-- Entri daftar, bukan kunci bersyarat: di tampilan terang kedua warnanya sama-sama text-gray-500, dan kunci array yang sama saling menimpa. --}}
                        <span
                            aria-hidden="true"
                            @class([
                                'flex shrink-0 items-center [&_svg]:size-3.5',
                                $tone === 'default' ? 'text-dropdown-item-icon' : null,
                                $disabled ? 'text-dropdown-item-support-disabled' : null,
                            ])
                        >{!! Content::icon($icon) !!}</span>
                    @endif
                    @if (Content::filled($description))
                        <span class="min-w-0 flex-1">
                            <span class="block">{{ $item['label'] ?? '' }}</span>
                            <span
                                data-keterangan=""
                                @class(['mt-0.5 block text-xs font-normal text-dropdown-item-description', 'text-dropdown-item-support-disabled' => $disabled])
                            >{{ $description }}</span>
                        </span>
                    @else
                        {{ $item['label'] ?? '' }}
                    @endif
                    @php $content = ob_get_clean(); @endphp

                    @if ($selection)
                        {{--
                            Baris daftar pilihan adalah `option`, bukan tombol. Rupanya
                            sengaja tidak dibedakan dari baris menu: yang terpilih ditandai
                            aria-selected dan menerima fokus saat panel dibuka. Atribut
                            item yang dimatikan tidak dipasang, supaya aksinya tidak jalan.
                        --}}
                        <div
                            role="option"
                            tabindex="-1"
                            data-baris=""
                            aria-selected="{{ ! empty($item['selected']) ? 'true' : 'false' }}"
                            @if (filled($item['selected-when'] ?? null)) x-bind:aria-selected="({{ $item['selected-when'] }}) ? 'true' : 'false'" @endif
                            @if ($disabled) aria-disabled="true" @endif
                            {{ ($disabled ? new ComponentAttributeBag : $extra)->class([
                                $base,
                                // `select-none` dan kursor panah menyamakan baris ini dengan baris menu yang berupa <button>.
                                'outline-none select-none',
                                $disabled
                                    ? 'cursor-not-allowed opacity-50 '.($tone === 'danger' ? 'text-feedback-error' : 'text-dropdown-item-disabled')
                                    : 'cursor-default '.$tones[$tone].' focus:bg-dropdown-item-hover-surface',
                            ]) }}
                        >{!! $content !!}</div>
                    @else
                        @php
                            $row = $extra->class([
                                $base,
                                'focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-brand',
                                'disabled:cursor-not-allowed disabled:opacity-50',
                                $current ? 'bg-dropdown-item-current-surface text-content' : $tones[$tone],
                            ]);
                        @endphp
                        {{-- Aksi yang berpindah halaman dirender sebagai tautan; yang dimatikan tetap <button>. --}}
                        @if (filled($item['href'] ?? null) && ! $disabled)
                            @php
                                $target = $item['target'] ?? null;
                                $rel = $target === '_blank' ? ($item['rel'] ?? 'noopener noreferrer') : ($item['rel'] ?? null);
                            @endphp
                            <a
                                href="{{ $item['href'] }}"
                                @if (filled($target)) target="{{ $target }}" @endif
                                @if (filled($rel)) rel="{{ $rel }}" @endif
                                @if (filled($current)) aria-current="{{ $current }}" @endif
                                data-baris=""
                                {{ $row }}
                            >{!! $content !!}</a>
                        @else
                            <button type="button" data-baris="" @disabled($disabled) {{ $row }}>{!! $content !!}</button>
                        @endif
                    @endif
                @endforeach

                @if (! $plain)
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>
