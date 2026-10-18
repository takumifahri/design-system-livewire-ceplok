{{--
    Bingkai hero di pratinjau. Hero gelap berlatar gray-800, dekat dengan
    panggung gelap, jadi tanpa garis tepi ia menyatu dengan latarnya. Garis
    gray-700 dan bayangan yang lebih pekat membuatnya tetap terbaca sebagai
    kartu, seperti hero terang di atas panggung abu-abu.
--}}
@props([
    'dark' => false,
])

<div @class([
    'overflow-hidden rounded-xl',
    $dark ? 'ring-1 ring-gray-700 shadow-[0_16px_40px_-8px_rgb(0_0_0/0.6)]' : 'shadow-soft',
])>{{ $slot }}</div>
