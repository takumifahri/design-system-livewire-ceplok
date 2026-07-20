@props([
    'variant' => 'gray',
])

@php
    $variants = [
        'gray' => 'bg-gray-100 text-gray-700',
        'brand' => 'bg-primary-50 text-primary-700',
        'danger' => 'bg-danger-100 text-danger-700',
        'warning' => 'bg-warning-100 text-warning-800',
        'success' => 'bg-success-100 text-success-700',
    ];

    $classes = 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold '
        .($variants[$variant] ?? $variants['gray']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
