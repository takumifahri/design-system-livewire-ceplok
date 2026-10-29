{{--
    Kartu contoh: label kecil di atas, lalu isi di dalam kotak berlatar netral.
    `dark` untuk komponen yang sedang ditampilkan dalam tampilan gelapnya —
    latarnya ikut gelap.

    `min-width` untuk isi yang tidak boleh lebih sempit dari lebar tertentu,
    mis. Timeline desktop. Di layar yang lebih sempit kotaknya digeser ke
    samping seperti tabel props, alih-alih isinya diperas.
--}}
@props([
    'label' => null,
    'dark' => false,
    'minWidth' => null,
])

<div {{ $attributes }}>
    @if ($label)
        <p class="mb-2 text-sm font-black text-gray-900">{{ $label }}</p>
    @endif
    <div @class([
        'rounded-xl border p-5',
        'ds-scroll-x overflow-x-auto' => $minWidth,
        $dark ? 'border-gray-800 bg-gray-900' : 'border-border bg-surface-subtle',
    ])>
        @if ($minWidth)
            <div style="min-width: {{ $minWidth }}">{{ $slot }}</div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
