@props([
    'image' => null,
    'imageAlt' => '',
    'title' => null,
    'description' => null,
    'href' => null,
    'linkLabel' => null,
])

{{--
    Semua varian pada desain (dengan/tanpa gambar, dengan/tanpa tombol,
    dengan tautan) terbentuk dari bagian yang sama — cukup hilangkan bagian
    yang tidak dipakai.

    @container: ukuran teks dan jarak mengikuti lebar kartu, bukan lebar layar,
    supaya kartu tetap proporsional di kolom grid selebar apa pun.
--}}
<article {{ $attributes->merge(['class' => '@container flex flex-col overflow-hidden rounded-lg border border-border bg-surface shadow-soft']) }}>
    @if ($image)
        {{-- Rasio gambar mengikuti Figma: 5:4 di mobile (238×190), 16:9 di desktop (384×216). --}}
        <img src="{{ $image }}" alt="{{ $imageAlt }}" class="aspect-[5/4] w-full object-cover @xs:aspect-video">
    @endif

    {{-- Padding 16 (mobile) / 24 (desktop), gap 12 antar bagian. --}}
    <div class="flex flex-1 flex-col gap-3 p-4 @xs:p-6">
        @if ($title)
            <h3 class="text-base leading-snug font-bold text-content @xs:text-xl">{{ $title }}</h3>
        @endif

        @if ($description)
            <p class="text-sm leading-6 text-content-subtle">{{ $description }}</p>
        @endif

        @if ($href)
            <a href="{{ $href }}"
               class="inline-flex w-fit items-center gap-1.5 rounded text-sm font-bold text-brand transition-colors hover:text-brand-hover hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                {{ $linkLabel ?? 'Selengkapnya' }}
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 4h6v6m0-6-8.5 8.5M18 14v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4"/>
                </svg>
            </a>
        @endif

        @isset($actions)
            <div class="flex flex-wrap items-center gap-3">
                {{ $actions }}
            </div>
        @endisset
    </div>
</article>
