{{--
    Kartu contoh: label kecil di atas, lalu isi di dalam kotak berlatar netral.
    `dark` untuk komponen yang sedang ditampilkan dalam tampilan gelapnya —
    latarnya ikut gelap. `light` kebalikannya, untuk versi terang komponen di
    mode gelap docs: `data-theme="light"` mengembalikan color-scheme terang dan
    `dark:text-content` warna teks yang diwarisi komponen. Tanpa keduanya kotak
    mengikuti mode docs, karena halamannya memberi `dark-mode` pada komponen di
    dalamnya (lihat App\Docs\Theme).

    `min-width` untuk isi yang tidak boleh lebih sempit dari lebar tertentu,
    mis. Timeline desktop. Di layar yang lebih sempit kotaknya digeser ke
    samping seperti tabel props, alih-alih isinya diperas.
--}}
@props([
    'label' => null,
    'dark' => false,
    'light' => false,
    'minWidth' => null,
])

<div {{ $attributes }}>
    @if ($label)
        <p class="mb-2 text-sm font-black text-gray-900 dark:text-white">{{ $label }}</p>
    @endif
    <div
        @if ($light) data-theme="light" @endif
        @class([
            'rounded-xl border p-5',
            'ds-scroll-x overflow-x-auto' => $minWidth,
            match (true) {
                (bool) $dark => 'border-gray-800 bg-gray-900 dark:border-gray-700',
                (bool) $light => 'border-border bg-surface-subtle dark:text-content',
                default => 'border-border bg-surface-subtle dark:border-gray-700 dark:bg-gray-900',
            },
        ])
    >
        @if ($minWidth)
            <div style="min-width: {{ $minWidth }}">{{ $slot }}</div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
