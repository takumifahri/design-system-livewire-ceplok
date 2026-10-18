{{--
    Satu bagian halaman: judul ber-anchor, lalu isi — port FlowSection di docs
    React. `id` harus sama dengan kunci di `toc` milik <x-docs.page>.
--}}
@props([
    'id',
    'title',
])

<section id="{{ $id }}" {{ $attributes->class('scroll-mt-24') }}>
    <h2 class="group flex items-baseline gap-2 text-heading-3 font-black text-gray-900">
        {{ $title }}
        <a
            href="#{{ $id }}"
            x-on:click="go($event, @js($id))"
            title="Tautan ke bagian ini"
            aria-label="Tautan ke bagian {{ $title }}"
            class="rounded text-primary-300 opacity-0 transition-opacity group-hover:opacity-100 hover:text-primary-600 focus-visible:opacity-100"
        >#</a>
    </h2>
    <div class="mt-3">{{ $slot }}</div>
</section>
