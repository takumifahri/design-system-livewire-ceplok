{{--
    Modal Popup contoh untuk halaman Modal: ikon peringatan, pesan konfirmasi,
    dan dua tombol aksi — port contoh Popup di ModalPage docs React.

    Class tombolnya sama dengan docs React: Button belum punya theme bahaya,
    jadi aksi hapus memakai override class lokal dengan merah red-700 di
    tema terang maupun gelap. Atribut lain (mis. close-on-escape, wire:key)
    diteruskan ke Modal.
--}}
@props([
    'triggerLabel' => 'Buka Popup',
])

@php
    $cancel = 'border-gray-400! text-gray-400! bg-transparent hover:bg-[light-dark(var(--color-gray-100),var(--color-surface-subtle))]! hover:text-[light-dark(var(--color-gray-700),var(--color-content))]!';
    $danger = 'bg-red-700! text-white! font-sans font-semibold! leading-[1.5] enabled:hover:bg-red-800! enabled:active:bg-red-900! focus:ring-[light-dark(var(--color-red-700),var(--color-red-300))]! focus-visible:ring-offset-2 focus-visible:ring-offset-surface';
@endphp

<x-ceplok::modal variant="popup" aria-label="Konfirmasi hapus konten" {{ $attributes }}>
    <x-slot:trigger>{{ $triggerLabel }}</x-slot:trigger>

    <div class="text-center">
        <div class="flex justify-center">
            {{-- Ikon dekoratif 48px; viewBox tanpa padding internal, sama seperti docs React. --}}
            <x-ceplok::icon class="size-12! shrink-0 text-red-600">{{ svg('ceplok-s-exclamation-circle', '', ['viewBox' => '2 2 20 20']) }}</x-ceplok::icon>
        </div>
        <p class="mx-auto mt-4 max-w-xs text-body font-medium text-content-muted">
            Apakah anda yakin ingin menghapus konten ini?
        </p>
    </div>

    <x-slot:footer>
        <div class="flex w-full flex-wrap justify-center gap-4">
            <x-ceplok::button variant="outline" theme="gray" size="xs" :class="$cancel" x-on:click="close()">Tidak, Batalkan</x-ceplok::button>
            <x-ceplok::button size="xs" :class="$danger" x-on:click="close()">Ya, hapus konten ini</x-ceplok::button>
        </div>
    </x-slot:footer>
</x-ceplok::modal>
