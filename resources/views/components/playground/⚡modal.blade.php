<?php

use CeplokUi\DesignKit\Enums\ModalSize;
use CeplokUi\DesignKit\Enums\ModalVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Modal, port Playground di ModalPage docs React: mode
 * warna, variant, ukuran, contoh isi, dan cara menutup.
 *
 * `wire:key` Modal-nya memuat semua pengaturan: atribut `<dialog>` dikunci
 * `wire:ignore.self` (lihat komponen Modal), jadi Modal dipasang ulang setiap
 * pengaturannya berganti.
 */
new class extends Component
{
    public bool $dark = false;

    public ModalVariant $variant = ModalVariant::Default;

    public ModalSize $size = ModalSize::S;

    /** `basic`, `image`, `long`, atau `nested`. */
    public string $example = 'basic';

    public bool $closeOnBackdrop = true;

    public bool $closeOnEscape = true;

    public bool $showCloseButton = true;

    public function updatedExample(string $example): void
    {
        if ($example === 'nested') {
            $this->variant = ModalVariant::Default;
        }
    }

    /** @return list<string> */
    public static function ceplokCopy(): array
    {
        return [
            'Ceplok merupakan platform digital yang dirancang untuk mempermudah akses layanan pemerintah dengan pendekatan yang lebih modern, efisien, dan ramah pengguna. Melalui penyederhanaan alur layanan, Ceplok berfokus pada kemudahan pengguna',
            'Dalam pengembangannya, Ceplok mengadopsi prinsip desain berbasis kebutuhan pengguna (user-centered design), memastikan setiap fitur menjawab masalah nyata di lapangan. Elemen seperti navigasi, form input, dan komponen notifikasi ditampilkan secara sederhana tanpa detail visual berlebihan',
        ];
    }

    /** @return list<string> */
    public static function longContent(): array
    {
        return [
            'Modal dapat memuat informasi yang perlu dibaca sebelum pengguna melanjutkan proses.',
            'Saat isi bertambah, tinggi panel tetap dibatasi viewport agar tombol tutup dan tombol aksi tetap terjangkau.',
            'Header berada di bagian atas dan tidak ikut bergerak ketika pengguna menggulir isinya.',
            'Footer juga tetap terlihat, jadi pengguna tidak perlu menggulir untuk menemukan tombol aksinya.',
            'Pakai struktur semantik — paragraf, daftar, heading bagian, atau field form — langsung sebagai isi Modal.',
            'Hindari menetapkan tinggi tetap hanya demi menyamai satu contoh desain; panjang isi dan ukuran layar selalu berubah.',
            'Pada layar sempit, lebar Modal menyesuaikan ruang yang ada sambil menyisakan jarak di kedua sisinya.',
            'Consumer tetap menentukan hasil aksi, validasi, status memuat, dan kapan proses dinyatakan selesai.',
            'Kalau proses simpannya lama, tampilkan statusnya pada tombol aksi tanpa mengubah tanggung jawab Modal.',
            'Pesan error dari server tetap bagian dari konten atau form yang Anda susun sendiri di dalam Modal.',
            'Konten yang terstruktur sebaiknya menjaga urutan baca yang jelas supaya tetap terbaca saat digulir.',
            'Pakai label yang spesifik pada tombol aksi supaya pengguna paham akibatnya sebelum menekan.',
            'Untuk tindakan yang merusak data, sediakan pilihan batal dan jangan arahkan fokus awal ke tombol perusaknya.',
            'Gambar boleh diletakkan bersama teks selama sumber, teks alternatif, dan perilaku responsifnya Anda tentukan.',
            'Tabel atau daftar panjang tetap bisa dipakai, tapi Anda yang memastikan isinya ikut responsif.',
            'Tombol di footer menjalankan aksi milik consumer dan tidak menutup Modal secara otomatis.',
            'Secara bawaan, klik latar dan tombol Escape menutup Modal melalui state internalnya.',
            'Contoh ini sengaja tidak menetapkan tinggi tetap — gulirannya muncul sendiri dari panjang isi dan ukuran layar.',
        ];
    }

    /** Kunci yang berganti bersama setiap pengaturan Modal. */
    #[Computed]
    public function modalKey(): string
    {
        return implode('-', [
            'playground-modal',
            $this->variant->value,
            $this->size->value,
            $this->example,
            (int) $this->closeOnBackdrop,
            (int) $this->closeOnEscape,
            (int) $this->showCloseButton,
        ]);
    }

    #[Computed]
    public function usage(): string
    {
        $themeProp = $this->dark ? '    dark-mode' : null;
        $dismissal = array_values(array_filter([
            $this->closeOnBackdrop ? null : '    :⟦close-on-backdrop⟧="false"',
            $this->closeOnEscape ? null : '    :⟦close-on-escape⟧="false"',
            $this->showCloseButton ? null : '    :⟦show-close-button⟧="false"',
        ]));

        if ($this->example === 'nested') {
            return implode("\n", array_filter([
                '<div x-data="{ defaultOpen: false, popupOpen: false }">',
                '    <x-ceplok::button x-on:click="defaultOpen = true">Buka Modal Bertingkat</x-ceplok::button>',
                '',
                '    <x-ceplok::modal',
                $themeProp ? '    dark-mode' : null,
                '        x-model="defaultOpen"',
                ...($this->size === ModalSize::M ? ['        size="m"'] : []),
                '        title="Kelola konten"',
                '    >',
                '        <x-ceplok::button size="xs" x-on:click="popupOpen = true">Hapus konten</x-ceplok::button>',
                '    </x-ceplok::modal>',
                '',
                '    <x-ceplok::modal',
                $themeProp ? '    dark-mode' : null,
                '        x-model="popupOpen"',
                '        variant="popup"',
                '        aria-label="Konfirmasi hapus konten"',
                '    >',
                '        <p>Apakah anda yakin ingin menghapus konten ini?</p>',
                '    </x-ceplok::modal>',
                '</div>',
            ], fn (?string $line) => $line !== null));
        }

        if ($this->variant === ModalVariant::Popup) {
            return implode("\n", [
                '<x-ceplok::modal',
                $themeProp,
                '    ⟦variant⟧="popup"',
                '    ⟦aria-label⟧="Konfirmasi hapus konten"',
                ...$dismissal,
                '    :actions="[',
                "        'secondary' => ['label' => 'Tidak, Batalkan', 'attributes' => ['x-on:click' => 'close()']],",
                "        'danger' => ['label' => 'Ya, hapus konten ini', 'attributes' => ['x-on:click' => 'close()']],",
                '    ]"',
                '>',
                '    <x-slot:trigger>Buka Modal</x-slot:trigger>',
                '',
                '    <p class="mx-auto max-w-xs text-center text-body font-medium text-content-muted">',
                '        Apakah anda yakin ingin menghapus konten ini?',
                '    </p>',
                '</x-ceplok::modal>',
            ]);
        }

        $long = $this->example === 'long';
        $body = match ($this->example) {
            'image' => [
                '    <⟦img⟧',
                '        src="/images/ceplok.svg"',
                '        alt="Ilustrasi motif Ceplok"',
                '        class="aspect-[600/437] w-full rounded-lg object-cover"',
                '    />',
                '    <div class="mt-5 space-y-4">',
                '        <p>…</p>',
                '        <p>…</p>',
                '    </div>',
            ],
            'long' => ['    {{-- Konten panjang; body Modal akan menggulir secara otomatis. --}}'],
            default => ['    <div class="space-y-4">', '        <p>…</p>', '        <p>…</p>', '    </div>'],
        };

        return implode("\n", [
            '<x-ceplok::modal',
            $themeProp,
            ...$dismissal,
            ...($this->size === ModalSize::M ? ['    ⟦size⟧="m"'] : []),
            '    title="'.($long ? 'Ketentuan Layanan' : 'Terms of Service').'"',
            "    :actions=\"['primary' => ['label' => '".($long ? 'Saya mengerti' : 'Ya, saya setuju')."', 'attributes' => ['x-on:click' => 'close()']]]\"",
            '>',
            '    <x-slot:trigger>Buka Modal</x-slot:trigger>',
            '',
            ...$body,
            '</x-ceplok::modal>',
        ]);
    }
};
?>

@php
    $isPopup = $variant === \CeplokUi\DesignKit\Enums\ModalVariant::Popup;
    $isNested = $example === 'nested';
    $long = $example === 'long';
@endphp

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Variant menentukan struktur Modal. Default memakai ukuran S secara bawaan; ubah ke M bila
            diperlukan. Popup menggunakan struktur dan ukuran bawaannya sendiri.
        </x-docs.lead>

        <x-docs.stage max-width="max-w-[420px]" :dark="$dark">
            <div data-theme="{{ $dark ? 'dark' : 'light' }}" class="flex min-h-40 items-center justify-center rounded-lg bg-surface">
                @if ($isNested)
                    <div x-data="{ defaultOpen: false, popupOpen: false }">
                        <x-ceplok::button :tone="$dark ? 'dark' : 'light'" x-on:click="defaultOpen = true">Buka Modal Bertingkat</x-ceplok::button>
                        <x-ceplok::modal
                            x-model="defaultOpen"
                            :dark-mode="$dark"
                            :size="$size"
                            title="Kelola konten"
                            :actions="['primary' => ['label' => 'Selesai', 'attributes' => ['x-on:click' => 'close()']]]"
                        >
                            <p class="mb-4">Buka konfirmasi tanpa menutup Modal ini.</p>
                            <x-ceplok::button size="xs" x-on:click="popupOpen = true">Hapus konten</x-ceplok::button>
                        </x-ceplok::modal>
                        <x-ceplok::modal
                            x-model="popupOpen"
                            :dark-mode="$dark"
                            variant="popup"
                            aria-label="Konfirmasi hapus konten"
                            :actions="[
                                'secondary' => ['label' => 'Tidak, Batalkan', 'attributes' => ['x-on:click' => 'close()']],
                                'danger' => ['label' => 'Ya, hapus konten ini', 'attributes' => ['x-on:click' => 'close()']],
                            ]"
                        >
                            <p class="text-center">Apakah anda yakin ingin menghapus konten ini?</p>
                        </x-ceplok::modal>
                    </div>
                @elseif ($isPopup)
                    <x-docs.modal-popup
                        trigger-label="Buka Modal"
                        :dark-mode="$dark"
                        :wire:key="$this->modalKey"
                        :close-on-backdrop="$closeOnBackdrop"
                        :close-on-escape="$closeOnEscape"
                        :show-close-button="$showCloseButton"
                        :actions="['primary' => ['label' => $long ? 'Saya mengerti' : 'Ya, saya setuju', 'attributes' => ['x-on:click' => 'close()']]]"
                    />
                @else
                    <x-ceplok::modal
                        :dark-mode="$dark"
                        :wire:key="$this->modalKey"
                        :size="$size"
                        :title="$long ? 'Ketentuan Layanan' : 'Terms of Service'"
                        :close-on-backdrop="$closeOnBackdrop"
                        :close-on-escape="$closeOnEscape"
                        :show-close-button="$showCloseButton"
                    >
                        <x-slot:trigger :tone="$dark ? 'dark' : 'light'">Buka Modal</x-slot:trigger>

                        @if ($example === 'image')
                            <img src="/images/ceplok.svg" alt="Ilustrasi motif Ceplok" class="aspect-[600/437] w-full rounded-lg object-cover">
                            <div class="mt-5 space-y-4">
                                @foreach ($this::ceplokCopy() as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        @elseif ($long)
                            <div class="space-y-5">
                                @foreach ($this::longContent() as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($this::ceplokCopy() as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        @endif

                    </x-ceplok::modal>
                @endif
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode warna Modal" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Variant">
                <x-docs.segmented label="Pilih variant Modal" model="variant" :value="$variant" :disabled="$isNested" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'popup', 'label' => 'Popup'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Size">
                <x-docs.segmented label="Pilih ukuran Modal" model="size" :value="$size" :disabled="$isPopup" :options="[
                    ['value' => 's', 'label' => 'S'],
                    ['value' => 'm', 'label' => 'M'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Contoh">
                <x-docs.segmented label="Pilih contoh susunan Modal" model="example" :value="$example" :disabled="$isPopup" wrap item-class="basis-1/2 justify-center px-2.5" :options="[
                    ['value' => 'basic', 'label' => 'Dasar'],
                    ['value' => 'image', 'label' => 'Dengan Gambar'],
                    ['value' => 'long', 'label' => 'Konten Panjang'],
                    ['value' => 'nested', 'label' => 'Nested Modal'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Cara Menutup">
                <div class="flex flex-col gap-2">
                    @foreach (['closeOnBackdrop' => 'Tutup lewat backdrop', 'closeOnEscape' => 'Tutup lewat Escape', 'showCloseButton' => 'Tampilkan tombol X'] as $prop => $label)
                        <label class="flex items-center gap-2 text-body-sm" wire:key="tutup-{{ $prop }}">
                            <input type="checkbox" wire:model.live="{{ $prop }}">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </x-docs.control>
        </x-docs.controls>

        <p class="mt-4 max-w-2xl text-body-sm text-gray-500">
            Variant menentukan struktur Modal. Contoh hanya mengubah komposisi konten pada Default,
            sedangkan Size mengatur lebar maksimum Default Modal.
        </p>
        @if ($isPopup)
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">
                Popup digunakan untuk konfirmasi ringkas. Gunakan <span class="text-primary-500">aria-label</span> atau
                <span class="text-primary-500">aria-labelledby</span> sebagai nama aksesibel, susun icon dan pesan di slot bawaan,
                lalu tempatkan tombol di slot footer. Contoh dan Size hanya tersedia untuk Default Modal.
            </p>
        @elseif ($isNested)
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Nested Modal memakai state terpisah untuk dialog Default dan Popup. Dialog yang dibuka
                terakhir berada di top layer dan penutupannya tidak menutup dialog pertama.
            </p>
        @elseif ($example === 'image')
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">
                Gambar disusun sebagai bagian dari slot bawaan; Modal tidak memiliki prop khusus untuk gambar.
            </p>
        @elseif ($long)
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">
                Konten panjang menggunakan gulir body Modal secara otomatis tanpa prop khusus.
            </p>
        @endif
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Kode mengikuti pilihan di Playground. Prop yang nilainya masih bawaan sengaja tidak ditulis.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
