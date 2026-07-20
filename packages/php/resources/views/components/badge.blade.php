@props([
    'variant' => 'gray',
])

@php
    $variants = [
        'gray' => 'bg-gray-100 text-gray-700',
        'brand' => 'bg-primary-50 text-primary-700',
        'danger' => 'bg-red-100 text-red-700',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'success' => 'bg-green-100 text-green-700',
    ];

    $classes = 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold '
        .($variants[$variant] ?? $variants['gray']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
