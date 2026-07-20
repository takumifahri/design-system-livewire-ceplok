@props([
    'title' => null,
    'description' => null,
    'label' => 'Blade',
    'code' => '',
])

@php
    // Kode disimpan sebagai string PHP biasa, bukan markup. Untungnya:
    // - {{ $code }} menampilkannya apa adanya (Blade meng-escape otomatis)
    // - @js($code) mengirim versi mentahnya ke clipboard
    // sehingga yang tersalin persis sama dengan yang terlihat.
    $code = trim($code);
    $id = 'usage-'.substr(md5($code), 0, 8);
@endphp

{{-- Tanpa `title` komponen tampil ringkas: hanya kartu kode, cocok
     disisipkan tepat di bawah masing-masing section. --}}
<div class="{{ $title ? 'mt-8' : 'mt-4' }}">
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

            <button type="button"
                    @click="copy(@js($code), '{{ $id }}')"
                    class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-bold text-gray-600 transition-colors hover:bg-gray-200 hover:text-gray-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
                    :aria-label="copied === '{{ $id }}' ? 'Kode tersalin' : 'Salin kode'">
                <svg x-show="copied !== '{{ $id }}'" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V5.5A1.5 1.5 0 0 1 10.5 4h8A1.5 1.5 0 0 1 20 5.5v8a1.5 1.5 0 0 1-1.5 1.5H15M5.5 9h8A1.5 1.5 0 0 1 15 10.5v8a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 18.5v-8A1.5 1.5 0 0 1 5.5 9Z"/>
                </svg>
                <svg x-show="copied === '{{ $id }}'" x-cloak class="size-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4.5 4.5L19 7"/>
                </svg>
                <span x-text="copied === '{{ $id }}' ? 'Tersalin!' : 'Salin'">Salin</span>
            </button>
        </div>

        <pre class="ds-scroll-x overflow-x-auto bg-gray-900 p-5 text-xs leading-6 text-gray-300"><code>{{ $code }}</code></pre>
    </article>
</div>
