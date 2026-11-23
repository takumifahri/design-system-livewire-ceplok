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
    'darkMode' => false,
])

<x-ceplok::modal
    variant="popup"
    aria-label="Konfirmasi hapus konten"
    :dark-mode="$darkMode"
    :actions="[
        'secondary' => ['label' => 'Tidak, Batalkan', 'attributes' => ['x-on:click' => 'close()']],
        'danger' => ['label' => 'Ya, hapus konten ini', 'attributes' => ['x-on:click' => 'close()']],
    ]"
    {{ $attributes }}
>
    <x-slot:trigger :tone="$darkMode ? 'dark' : 'light'">{{ $triggerLabel }}</x-slot:trigger>

    <p class="mx-auto max-w-xs text-center text-body font-medium text-content-muted">
        Apakah anda yakin ingin menghapus konten ini?
    </p>
</x-ceplok::modal>
