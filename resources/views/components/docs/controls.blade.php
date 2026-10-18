{{--
    Deretan kontrol Playground — tiga per baris mulai xl. Baru di xl karena
    beberapa kontrol berisi banyak tombol; di lebar lg tiap kolom belum cukup.
--}}
<div {{ $attributes->class('mt-4 grid items-start gap-6 sm:grid-cols-2 xl:grid-cols-3') }}>{{ $slot }}</div>
