{{--
    <head> bersama untuk layout dokumentasi dan bingkai contoh aplikasi.
    Asetnya harus sama persis di keduanya: wire:navigate hanya memuat ulang
    skrip yang berbeda, jadi modul JavaScript tetap hidup saat berpindah dari
    dokumentasi ke aplikasi contoh dan sebaliknya.
--}}
@props([
    'title' => 'Design System',
])

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ceplok Design Kit — foundations dan komponen Blade + Livewire.">

    <title>{{ $title }} — Ceplok Design Kit</title>

    {{--
        Sengaja tanpa varian .svg: images/ceplok.svg berisi raster ter-embed
        1,5 MB, dan browser yang mendukung SVG akan memilihnya untuk ikon 16px.
    --}}
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="180x180" href="/favicon.png">
    <link rel="apple-touch-icon" href="/images/favicon-180.png">

    {{-- Penanda JavaScript aktif: elemen yang punya animasi masuk baru disembunyikan bila ini ada. --}}
    <script>document.documentElement.classList.add('ds-js')</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
