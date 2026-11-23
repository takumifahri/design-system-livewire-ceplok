{{--
    Daftar tautan panel samping — sub-halaman ditarik masuk di bawah induknya.
    Dipakai panel samping desktop dan laci navigasi mobile, jadi keduanya selalu
    sama.
--}}
@props([
    'items' => [],
    'route' => null,
])

@foreach ($items as $item)
    <div class="mt-1">
        <a
            href="{{ route($item['route']) }}"
            wire:navigate
            @class(['ds-nav-link w-full', 'is-active' => $route === $item['route']])
            @if ($route === $item['route']) aria-current="page" @endif
        >{{ $item['label'] }}</a>
        @if (! empty($item['children']))
            <div class="mt-1 ml-5 border-l border-border pl-2 dark:border-gray-700">
                @foreach ($item['children'] as $child)
                    <a
                        href="{{ route($child['route']) }}"
                        wire:navigate
                        @class(['ds-nav-link w-full py-2 text-[13px]', 'is-active' => $route === $child['route']])
                        @if ($route === $child['route']) aria-current="page" @endif
                    >{{ $child['label'] }}</a>
                @endforeach
            </div>
        @endif
    </div>
@endforeach
