{{--
    Pasangan Desktop dan Mobile untuk bagian Platform dan Dark mode di halaman
    Table of Contents, port PlatformPair di docs React. Bingkainya memakai
    token semantik dan dibungkus `data-theme`, jadi versi gelapnya cukup
    menukar atribut itu; judul kolomnya tetap ikut halaman.

    Berbeda dari docs React: mockup ponselnya punya navbar (x-docs.toc-phone).
    Ikon kiri membuka menu sidebar, ikon kanan membuka menu navbar dengan
    daftar isi yang bisa dilipat di bawahnya.
--}}
@props([
    'items' => [],
    'dark' => false,
])

@php
    $theme = $dark ? 'dark' : 'light';
@endphp

<div {{ $attributes->class('grid gap-4 md:grid-cols-2') }}>
    <article>
        <h3 class="text-sm font-black text-gray-900">Desktop · 12px</h3>
        <p class="mt-1 mb-3 text-body-sm text-gray-500">
            Daftar biasa di kanan isi halaman, mulai lebar layar 1024px.
        </p>
        <div data-theme="{{ $theme }}" class="rounded-2xl border border-border bg-surface-subtle p-6">
            <x-ceplok::table-of-contents :items="$items" layout="list" :anchor="false" :dark-mode="$dark" />
        </div>
    </article>

    <article>
        <h3 class="text-sm font-black text-gray-900">Mobile · 14px</h3>
        <p class="mt-1 mb-3 text-body-sm text-gray-500">
            Ikon kiri navbar membuka menu sidebar. Ikon kanan membuka menu navbar, dengan daftar isi
            yang bisa dilipat di bawahnya.
        </p>
        {{-- Bingkai `relative` supaya panelnya muncul di sini, bukan menutupi seluruh layar. --}}
        <div
            data-theme="{{ $theme }}"
            class="relative h-100 overflow-hidden rounded-[1.75rem] border-4 border-border-strong bg-toc-surface"
        >
            <x-docs.toc-phone>
                <x-slot:toc>
                    <x-ceplok::table-of-contents :items="$items" layout="list" :title="false" :anchor="false" :dark-mode="$dark" />
                </x-slot:toc>
                <div class="space-y-2.5 p-5" aria-hidden="true">
                    <div class="h-3 w-1/2 rounded bg-border-strong"></div>
                    <div class="h-2 w-full rounded bg-border"></div>
                    <div class="h-2 w-5/6 rounded bg-border"></div>
                    <div class="h-2 w-4/6 rounded bg-border"></div>
                    <div class="mt-5! h-24 rounded-lg bg-border"></div>
                    <div class="h-2 w-full rounded bg-border"></div>
                    <div class="h-2 w-3/6 rounded bg-border"></div>
                </div>
            </x-docs.toc-phone>
        </div>
    </article>
</div>
