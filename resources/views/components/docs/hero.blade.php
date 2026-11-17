{{-- Hero halaman Overview dan Foundations: eyebrow, judul, deskripsi, isi tambahan. --}}
@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<header class="border-b border-border bg-white px-5 py-10 sm:px-8 lg:px-12 lg:py-12 xl:px-14 dark:border-gray-700 dark:bg-gray-800">
    <div class="mx-auto max-w-5xl">
        @if ($eyebrow)
            <p class="ds-eyebrow">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-2 text-heading-1 font-black tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
        @if ($description)
            <p class="mt-3 max-w-2xl text-body text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
        {{ $slot }}
    </div>
</header>
