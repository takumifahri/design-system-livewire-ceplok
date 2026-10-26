{{--
    Panggung Playground: kartu berlatar netral, lebar isinya ditransisikan
    saat `max-width` berganti. Tulis kelas lebarnya utuh di pemanggil (mis.
    `max-w-[380px]`) supaya ikut dipindai Tailwind.

    Isinya memakai font pratinjau dari kontrol "Font" (lihat
    x-docs.controls), yang disimpan di Alpine store `preview`: pilihannya murni
    tampilan, jadi tidak memicu request dan bertahan saat Livewire merender
    ulang Playground.
--}}
@props([
    'maxWidth' => 'max-w-none',
    'dark' => false,
])

<div {{ $attributes->class([
    'rounded-2xl border p-6 transition-colors sm:p-10',
    $dark ? 'border-gray-800 bg-gray-900' : 'border-border bg-surface-subtle',
]) }}>
    <div
        class="mx-auto font-sans transition-[max-width] duration-300 ease-out {{ $maxWidth }}"
        x-data
        x-bind:class="{ 'font-sans': $store.preview.font === 'sans', 'font-alt': $store.preview.font === 'alt', 'font-alt-2': $store.preview.font === 'alt2' }"
    >{{ $slot }}</div>
</div>
