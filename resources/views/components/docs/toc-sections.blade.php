{{--
    Bagian-bagian halaman tiruan di Playground Table of Contents, port
    AnchoredSections di docs React. Masing-masing punya id dan tautan `#` di
    judulnya seperti judul bagian di dokumentasi ini; tautannya menggulir
    wadahnya sendiri (`scroll`, id wadah gulir), bukan mengubah alamat halaman.
    Tanpa `anchors`, bagian tetap tampil dan hanya tautan `#`-nya yang hilang.
--}}
@props([
    'items' => [],
    'scroll',
    'anchors' => true,
])

<div {{ $attributes->class('space-y-8 pb-[60%]') }}>
    @foreach ($items as $i => $item)
        <section id="{{ $item['id'] }}" wire:key="{{ $item['id'] }}">
            <h3 class="group flex items-baseline gap-2 text-base font-black text-content">
                <span class="min-w-0">{{ $item['label'] }}</span>
                @if ($anchors)
                    <a
                        href="#{{ $item['id'] }}"
                        x-on:click.prevent="
                            const root = document.getElementById(@js($scroll));
                            const el = document.getElementById(@js($item['id']));
                            root.scrollTo({ top: el.getBoundingClientRect().top - root.getBoundingClientRect().top + root.scrollTop - 16, behavior: 'smooth' });
                        "
                        aria-label="Tautan ke bagian {{ $item['label'] }}"
                        class="rounded text-brand opacity-0 transition-opacity group-hover:opacity-100 focus-visible:opacity-100"
                    >#</a>
                @endif
            </h3>
            <div class="mt-3 space-y-2" aria-hidden="true">
                <div class="h-2 w-full rounded bg-border transition-colors duration-300"></div>
                <div class="h-2 w-11/12 rounded bg-border transition-colors duration-300"></div>
                <div class="h-2 w-4/6 rounded bg-border transition-colors duration-300"></div>
                @if ($i % 3 === 1)
                    <div class="mt-4! h-20 rounded-lg bg-border transition-colors duration-300"></div>
                @endif
            </div>
        </section>
    @endforeach
</div>
