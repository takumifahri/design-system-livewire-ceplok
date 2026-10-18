{{--
    Bingkai pratinjau komponen selebar wadahnya (Hero, Footer): sudutnya
    dirapikan. `label` memberi nama tiap pratinjau, mis. nama frame desain; `dark`
    untuk komponen dark-mode, supaya tepinya tidak kontras putih.
--}}
@props([
    'label' => null,
    'dark' => false,
])

<div {{ $attributes }}>
    @if ($label)
        <p class="mb-2 text-sm font-black text-gray-900">{{ $label }}</p>
    @endif
    <div @class([
        'rounded-2xl border p-4 sm:p-5',
        $dark ? 'border-gray-800 bg-gray-950' : 'border-border bg-surface-subtle',
    ])>
        <x-docs.hero-frame :dark="$dark">{{ $slot }}</x-docs.hero-frame>
    </div>
</div>
