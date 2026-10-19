{{--
    Panggung Playground: kartu berlatar netral, lebar isinya ditransisikan
    saat `max-width` berganti. Tulis kelas lebarnya utuh di pemanggil (mis.
    `max-w-[380px]`) supaya ikut dipindai Tailwind.
--}}
@props([
    'maxWidth' => 'max-w-none',
    'dark' => false,
])

<div {{ $attributes->class([
    'rounded-2xl border p-6 transition-colors sm:p-10',
    $dark ? 'border-gray-800 bg-gray-900' : 'border-border bg-surface-subtle',
]) }}>
    <div class="mx-auto transition-[max-width] duration-300 ease-out {{ $maxWidth }}">{{ $slot }}</div>
</div>
