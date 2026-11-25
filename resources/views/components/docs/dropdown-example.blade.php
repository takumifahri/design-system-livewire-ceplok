@php
    use Illuminate\Support\Js;
@endphp

{{--
    Satu Dropdown utuh untuk contoh mana pun di halaman Dropdown — port
    ContohDropdown di docs React.

    `actions` dan `icons` memakai `items`/`groups`, yang daftarnya disusun
    Dropdown sendiri. `radio`, `checkbox`, dan `scroll` memakai slot, karena
    isinya kontrol form, bukan daftar aksi. `selection` adalah daftar pilihan
    urutan; pilihannya dipegang Alpine (`urut`), kecuali `model` diisi nama
    properti Livewire — barisnya lalu memanggil $set.

    Kelas scrollbar gelap ditulis utuh di sini supaya ikut dipindai Tailwind.
    Varian not-supports-[…] dipakai, bukan [@supports_not_…]: ekstensi PHP di
    editor membaca `@nama(…)` di string sebagai direktif Blade dan salah lapor:
    not-supports-[selector(::-webkit-scrollbar)]:[scrollbar-color:var(--color-gray-600)_var(--color-gray-800)]
--}}
@props([
    'example' => 'actions',
    'groupName' => 'contoh-dropdown',
    'separator' => false,
    'description' => false,
    'disabled' => false,
    'align' => 'start',
    'closeOnSelect' => true,
    'dark' => false,
    'model' => null,
    'selected' => 'Terbaru',
])

@php
    // Trigger meniru Button kit. Di tampilan gelap ia menjadi tombol putih,
    // fallback filled Button ber-darkMode di docs React, bukan primary biru
    // yang hanya digelapkan. Button kit Livewire belum punya dark-mode, jadi
    // warnanya ditulis di sini.
    $triggerClass = implode(' ', [
        'inline-flex h-10 items-center justify-center gap-2',
        'rounded-lg px-4',
        'text-base font-medium',
        $dark ? 'bg-gray-50 text-gray-700 hover:bg-white hover:text-gray-700' : 'bg-primary-700 text-white hover:bg-primary-800',
        'transition-colors duration-200',
        'focus:outline-none focus:ring-2 focus:ring-primary-400',
        'disabled:pointer-events-none disabled:opacity-50',
    ]);
    $row = $dark ? 'w-full rounded-md px-2 hover:bg-gray-700' : 'w-full rounded-md px-2 hover:bg-gray-100';
    $disabledRow = 'w-full rounded-md px-2';
    $hr = $dark ? 'my-1 border-0 border-t border-gray-700' : 'my-1 border-0 border-t border-border';
    $scroll = $dark
        ? 'ds-scroll-y [&::-webkit-scrollbar-track]:bg-gray-800 [&::-webkit-scrollbar-thumb]:bg-gray-600 [&::-webkit-scrollbar-thumb:hover]:bg-gray-500 [&::-webkit-scrollbar-button]:hidden '
          .'not-supports-[selector(::-webkit-scrollbar)]:[scrollbar-color:var(--color-gray-600)_var(--color-gray-800)]'
        : '';
    $chevron = svg('ceplok-o-chevron-down', 'size-3.5 shrink-0', ['aria-hidden' => 'true']);

    $withIcons = $example === 'icons';
    $actions = [
        ['label' => 'Profil', 'icon' => $withIcons ? 'ceplok-o-user' : null, 'description' => $description ? 'Lihat dan ubah profil.' : null],
        ['label' => 'Pengaturan', 'icon' => $withIcons ? 'ceplok-o-cog' : null, 'description' => $description ? 'Atur preferensi akun.' : null],
        ['label' => 'Bantuan', 'icon' => $withIcons ? 'ceplok-o-question-circle' : null, 'disabled' => $disabled],
        ['label' => 'Keluar', 'tone' => 'danger', 'icon' => $withIcons ? 'ceplok-o-arrow-right-to-bracket' : null],
    ];

    $sortOptions = [
        'Terbaru' => $description ? 'Urutkan dari yang paling baru.' : null,
        'Terlama' => $description ? 'Urutkan dari yang paling lama.' : null,
        'Nama A–Z' => null,
    ];
    $sortItems = collect($sortOptions)->map(fn ($desc, $label) => [
        'label' => $label,
        'description' => $desc,
        'disabled' => $label === 'Nama A–Z' && $disabled,
        'selected' => $label === $selected,
        'selected-when' => $model ? null : 'urut === '.Js::from($label),
        'attributes' => $model
            ? ['wire:click' => '$set('.Js::from($model).', '.Js::from($label).')']
            : ['x-on:click' => 'urut = '.Js::from($label)],
    ])->values()->all();
@endphp

@if ($example === 'selection')
    <div @unless ($model) x-data="{ urut: @js($selected) }" @endunless {{ $attributes->class('contents') }}>
        <x-ceplok::dropdown
            :dark-mode="$dark"
            :align="$align"
            :close-on-select="$closeOnSelect"
            content-label="Urutan hasil"
            :items="$separator ? null : $sortItems"
            :groups="$separator ? [['id' => 'waktu', 'label' => 'Waktu', 'items' => array_slice($sortItems, 0, 2)], ['id' => 'nama', 'separator' => true, 'items' => array_slice($sortItems, 2)]] : null"
        >
            <x-slot:trigger raw :class="$triggerClass">
                @if ($model)
                    Urutkan: {{ $selected }}
                @else
                    <span x-text="'Urutkan: ' + urut">Urutkan: {{ $selected }}</span>
                @endif
                {{ $chevron }}
            </x-slot:trigger>
        </x-ceplok::dropdown>
    </div>
@elseif ($example === 'actions' || $example === 'icons')
    <x-ceplok::dropdown
        :attributes="$attributes"
        :dark-mode="$dark"
        :align="$align"
        :close-on-select="$closeOnSelect"
        :items="$separator ? null : $actions"
        :groups="$separator ? [['id' => 'utama', 'items' => array_slice($actions, 0, 3)], ['id' => 'keluar', 'separator' => true, 'items' => array_slice($actions, 3)]] : null"
        aria-label="Daftar aksi"
    >
        <x-slot:trigger raw :class="$triggerClass">Dropdown button {{ $chevron }}</x-slot:trigger>
    </x-ceplok::dropdown>
@elseif ($example === 'scroll')
    <x-ceplok::dropdown :attributes="$attributes" :dark-mode="$dark" :align="$align" :close-on-select="$closeOnSelect" content-class="p-4" content-label="Pilih notifikasi dengan scroll">
        <x-slot:trigger raw :class="$triggerClass">Dropdown button {{ $chevron }}</x-slot:trigger>
        <div @class(['flex max-h-48 flex-col gap-4 overflow-y-auto overscroll-y-contain', $scroll])>
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Email" :helper-text="$description ? 'Notifikasi melalui email.' : null" checked />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Push notification" :helper-text="$description ? 'Notifikasi pada perangkat.' : null" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="SMS" :helper-text="$description ? 'Notifikasi melalui SMS.' : null" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="WhatsApp" checked />
            @if ($separator)
                <hr class="{{ $hr }}">
            @endif
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Pembaruan produk" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Aktivitas akun" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$disabled ? $disabledRow : $row" label="Keamanan" :disabled="$disabled" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Promosi" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Laporan mingguan" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Pengingat" checked />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Integrasi" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Sistem" />
        </div>
    </x-ceplok::dropdown>
@elseif ($example === 'radio')
    <x-ceplok::dropdown :attributes="$attributes" :dark-mode="$dark" :align="$align" :close-on-select="$closeOnSelect" content-class="p-4" content-label="Pilih akses">
        <x-slot:trigger raw :class="$triggerClass">Dropdown button {{ $chevron }}</x-slot:trigger>
        <div class="flex flex-col gap-4">
            <x-ceplok::radio :dark-mode="$dark" :class="$row" :name="$groupName" value="viewer" label="Viewer" />
            <x-ceplok::radio :dark-mode="$dark" :class="$row" :name="$groupName" value="editor" label="Editor" :helper-text="$description ? 'Dapat mengubah konten.' : null" checked />
            @if ($separator)
                <hr class="{{ $hr }}">
            @endif
            <x-ceplok::radio :dark-mode="$dark" :class="$disabled ? $disabledRow : $row" :name="$groupName" value="admin" label="Admin" :disabled="$disabled" />
        </div>
    </x-ceplok::dropdown>
@else
    <x-ceplok::dropdown :attributes="$attributes" :dark-mode="$dark" :align="$align" :close-on-select="$closeOnSelect" content-class="p-4" content-label="Pilih notifikasi">
        <x-slot:trigger raw :class="$triggerClass">Dropdown button {{ $chevron }}</x-slot:trigger>
        <div class="flex flex-col gap-4">
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Email" :helper-text="$description ? 'Kirim pembaruan melalui email.' : null" />
            <x-ceplok::checkbox :dark-mode="$dark" :class="$row" label="Push notification" :helper-text="$description ? 'Tampilkan pada perangkat.' : null" checked />
            @if ($separator)
                <hr class="{{ $hr }}">
            @endif
            <x-ceplok::checkbox :dark-mode="$dark" :class="$disabled ? $disabledRow : $row" label="SMS" :disabled="$disabled" />
        </div>
    </x-ceplok::dropdown>
@endif
