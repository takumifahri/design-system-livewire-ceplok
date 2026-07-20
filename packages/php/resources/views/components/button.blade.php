@props([
    'variant' => 'primary',
    'as' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-brand text-white hover:bg-brand-hover focus-visible:outline-primary-600',
        'secondary' => 'bg-surface text-content border border-border hover:bg-surface-subtle',
        'danger' => 'bg-feedback-error text-white hover:bg-danger-700',
        'ghost' => 'text-content hover:bg-surface-subtle',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50 '
        .($variants[$variant] ?? $variants['primary']);
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $as }}>
