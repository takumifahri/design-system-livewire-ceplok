@php
    use App\Docs\Code;
@endphp

{{--
    Kartu cuplikan kode dengan tombol salin — port DocUsage/SectionCode di docs
    React.

    Kodenya dari prop `code`, atau dari slot. Isi slot WAJIB dibungkus
    @verbatim … @endverbatim supaya tag <x-…> dan {{ }} di dalamnya tidak ikut
    dikompilasi Blade. Indentasi yang sama di semua baris dibuang, jadi kode
    boleh ditulis menjorok mengikuti markup di sekitarnya. Bagian yang disorot
    ditandai ⟦…⟧, atau ⟦*…⟧ untuk prop yang dikendalikan Playground (lihat
    App\Docs\Code). Tombol salin menyalin teks yang sedang tampil.

    Di dalam ekspresi PHP, mis. kunci array pada :items, sorotan ditulis lewat
    prop `mark` (teks atau daftar teks), bukan ⟦…⟧. Ekstensi PHP di editor
    tetap mem-parse nilai atribut itu walau dibungkus @verbatim, dan penanda
    di tengah ekspresi membuatnya melaporkan error sintaks.

    Tanpa `title` tampil ringkas: hanya kartu kode berjarak atas `mt-4`;
    `flush` menghapus jarak itu bila pemanggil sudah mengaturnya sendiri.
--}}
@props([
    'code' => null,
    'label' => 'Blade',
    'title' => null,
    'description' => null,
    'flush' => false,
    'wrap' => false,
    'scroll' => false,
    'mark' => [],
])

@php
    $source = $code !== null ? Code::dedent($code) : Code::dedent($slot->toHtml(), fromSlot: true);

    foreach ((array) $mark as $text) {
        $source = str_replace($text, '⟦'.$text.'⟧', $source);
    }

    $id = 'code-'.substr(md5($source), 0, 8);
    $preStyle = $scroll && ! $wrap ? 'overflow-x: scroll' : null;
@endphp

<div {{ $attributes->class([$title ? 'mt-8' : 'mt-4' => ! $flush]) }}>
    @if ($title)
        <div class="mb-5">
            <p class="ds-eyebrow">Cara pakai</p>
            <h3 class="mt-2 text-heading-3 font-black text-gray-900">{{ $title }}</h3>
            @if ($description)
                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
    @endif

    <article class="ds-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-border bg-surface-subtle px-4 py-2.5">
            <span class="text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">{{ $label }}</span>
            <button
                type="button"
                x-on:click="copy($el.closest('article').querySelector('code').textContent, @js($id))"
                aria-label="Salin kode"
                x-bind:aria-label="copied === @js($id) ? 'Kode tersalin' : 'Salin kode'"
                class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-bold text-gray-600 transition-colors hover:bg-gray-200 hover:text-gray-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
            >
                <svg x-show="copied !== @js($id)" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V5.5A1.5 1.5 0 0 1 10.5 4h8A1.5 1.5 0 0 1 20 5.5v8a1.5 1.5 0 0 1-1.5 1.5H15M5.5 9h8A1.5 1.5 0 0 1 15 10.5v8a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 18.5v-8A1.5 1.5 0 0 1 5.5 9Z" />
                </svg>
                <svg x-show="copied === @js($id)" x-cloak class="size-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4.5 4.5L19 7" />
                </svg>
                <span x-text="copied === @js($id) ? 'Tersalin!' : 'Salin'">Salin</span>
            </button>
        </div>
        <pre
            @if ($preStyle) style="{{ $preStyle }}" @endif
            @class([
            'bg-gray-900 p-5 text-xs leading-6 text-gray-300',
            'ds-scroll-x overflow-x-scroll' => $scroll && ! $wrap,
            'ds-scroll-x overflow-x-auto' => ! $scroll && ! $wrap,
            'whitespace-pre-wrap break-words' => $wrap,
        ])><code>{{ Code::highlight($source) }}</code></pre>
    </article>
</div>
