{{--
    Deretan kontrol Playground — tiga per baris mulai xl. Baru di xl karena
    beberapa kontrol berisi banyak tombol; di lebar lg tiap kolom belum cukup.

    Kontrol pertama selalu "Font": font pratinjau di x-docs.stage, sama dengan
    pilihan di halaman Typography. Nilainya di Alpine store `preview`, jadi
    berganti di browser tanpa request. `:fonts="false"` melepasnya untuk
    Playground yang tidak memakai x-docs.stage atau tidak berisi teks.
    Urutan berikutnya: mode gelap (bila ada), lalu kontrol komponennya.
--}}
@props([
    'fonts' => true,
])

<div x-data {{ $attributes->class('mt-4 grid items-start gap-6 sm:grid-cols-2 xl:grid-cols-3') }}>
    @if ($fonts)
        <x-docs.control label="Font">
            <x-docs.segmented alpine label="Pilih font pratinjau" model="$store.preview.font" value="sans" :options="[
                ['value' => 'sans', 'label' => 'Lato'],
                ['value' => 'alt', 'label' => 'Inter'],
                ['value' => 'alt2', 'label' => 'Arial'],
            ]" />
        </x-docs.control>
    @endif
    {{ $slot }}
</div>
