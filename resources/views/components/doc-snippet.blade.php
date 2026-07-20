@props([
    'code' => '',
    'description' => null,
])

@php
    // Kode disimpan sebagai string PHP: {{ $code }} menampilkannya apa adanya,
    // @js($code) mengirim versi mentahnya ke clipboard.
    $code = trim($code);
    $id = 'snippet-'.substr(md5($code), 0, 8);
@endphp

<article class="ds-card overflow-hidden">
    <div class="flex flex-col sm:flex-row">
        {{-- Kartu gelap: kode + tombol salin --}}
        <div class="relative min-w-0 flex-1 bg-gray-900">
            <button type="button"
                    @click="copy(@js($code), '{{ $id }}')"
                    class="absolute top-2.5 right-2.5 z-10 inline-flex items-center gap-1.5 rounded-md bg-white/10 px-2 py-1 text-[11px] font-bold text-gray-300 transition-colors hover:bg-white/20 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    :aria-label="copied === '{{ $id }}' ? 'Kode tersalin' : 'Salin kode'">
                <svg x-show="copied !== '{{ $id }}'" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V5.5A1.5 1.5 0 0 1 10.5 4h8A1.5 1.5 0 0 1 20 5.5v8a1.5 1.5 0 0 1-1.5 1.5H15M5.5 9h8A1.5 1.5 0 0 1 15 10.5v8a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 18.5v-8A1.5 1.5 0 0 1 5.5 9Z"/>
                </svg>
                <svg x-show="copied === '{{ $id }}'" x-cloak class="size-3.5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4.5 4.5L19 7"/>
                </svg>
                <span x-text="copied === '{{ $id }}' ? 'Tersalin!' : 'Salin'">Salin</span>
            </button>

            <pre class="ds-scroll-x overflow-x-auto py-4 pr-24 pl-4 text-xs leading-6 text-gray-300"><code>{{ $code }}</code></pre>
        </div>

        {{-- Contoh hasil render, di samping kode --}}
        @isset($preview)
            <div class="grid shrink-0 place-items-center border-t border-border bg-surface-subtle px-6 py-5 sm:w-32 sm:border-t-0 sm:border-l">
                {{ $preview }}
            </div>
        @endisset
    </div>

    @if ($description)
        <div class="border-t border-border px-4 py-3">
            <p class="text-xs leading-5 text-gray-500">{!! $description !!}</p>
        </div>
    @endif
</article>
