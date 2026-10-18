{{--
    Tombol contoh untuk slot `actions` di halaman Card.

    Tombol kedua (`secondary`) di kartu gelap memakai outline. Outline bawaan
    Button memakai primary-700 yang nyaris hilang di atas gray-700, jadi
    warnanya ditimpa ke primary-400 seperti desain — dengan `!` karena kelas
    bawaan tombolnya tidak ikut dicabut.
--}}
@props([
    'variant' => 'primary',
    'dark' => false,
])

@if ($variant === 'secondary' && $dark)
    <x-ceplok::button variant="outline" size="s" class="border-primary-400! text-primary-400! hover:bg-primary-400/10!">Button text</x-ceplok::button>
@else
    <x-ceplok::button size="s">Button text</x-ceplok::button>
@endif
