{{--
    Kartu contoh: label kecil di atas, lalu isi di dalam kotak berlatar netral.
    `dark` untuk komponen yang sedang ditampilkan dalam tampilan gelapnya —
    latarnya ikut gelap.
--}}
@props([
    'label' => null,
    'dark' => false,
])

<div {{ $attributes }}>
    @if ($label)
        <p class="mb-2 text-sm font-black text-gray-900">{{ $label }}</p>
    @endif
    <div @class([
        'rounded-xl border p-5',
        $dark ? 'border-gray-800 bg-gray-900' : 'border-border bg-surface-subtle',
    ])>{{ $slot }}</div>
</div>
