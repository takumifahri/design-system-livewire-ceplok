{{--
    Tombol contoh untuk slot `actions` di halaman Alert. Warnanya mengikuti
    "ink" variant Alert yang sama — ditulis di sini karena `actions` adalah
    slot bebas, bukan bagian dari komponen Alert.

    Belum memakai <x-ceplok::button>: theme-nya belum punya merah untuk danger.
    Pada tampilan gelap warnanya dari desain: -600, kecuali success dan
    warning yang -500. Hover-nya satu tingkat lebih gelap.
--}}
@props([
    'variant' => 'info',
    'dark' => false,
])

@php
    $colors = [
        'success' => ['bg-green-800 hover:bg-green-900', 'bg-green-500 hover:bg-green-600'],
        'danger' => ['bg-red-800 hover:bg-red-900', 'bg-red-600 hover:bg-red-700'],
        'warning' => ['bg-yellow-800 hover:bg-yellow-900', 'bg-yellow-500 hover:bg-yellow-600'],
        'info' => ['bg-primary-800 hover:bg-primary-900', 'bg-primary-600 hover:bg-primary-700'],
        'purple' => ['bg-purple-800 hover:bg-purple-900', 'bg-purple-600 hover:bg-purple-700'],
    ];
    $variant = $variant instanceof BackedEnum ? $variant->value : $variant;
    [$light, $darkColor] = $colors[$variant] ?? $colors['info'];
@endphp

<button
    type="button"
    @class([
        'inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2',
        $dark ? $darkColor : $light,
    ])
>
    Button text
    {{ svg('ceplok-o-messages', 'size-4') }}
</button>
