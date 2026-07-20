@props([
    'logo' => null,
    'logoAlt' => 'Logo',
    'menus' => [],
    'copyright' => null,
    'socials' => [],
])

{{-- @container: tata letak mengikuti lebar footer, bukan lebar viewport. --}}
<footer {{ $attributes->merge(['class' => '@container bg-gray-800 text-gray-300']) }}>
    {{-- Container mengikuti Figma: lebar 1280, gap 32, padding atas/bawah 16. --}}
    <div class="mx-auto flex max-w-7xl flex-col gap-8 px-6 py-16 @3xl:px-12">
        {{-- Baris atas: logo + menu --}}
        <div class="flex flex-col gap-7 @3xl:flex-row @3xl:items-center @3xl:gap-12">
            <div class="shrink-0">
                @if ($logo)
                    <img src="{{ $logo }}" alt="{{ $logoAlt }}" class="h-10 w-auto @3xl:h-11">
                @else
                    {{ $slot }}
                @endif
            </div>

            @if (count($menus))
                <nav class="@3xl:flex @3xl:flex-1 @3xl:justify-center" aria-label="Navigasi footer">
                    <ul class="flex flex-wrap items-center gap-x-8 gap-y-3 text-sm">
                        @foreach ($menus as $menu)
                            <li>
                                <a href="{{ $menu['url'] ?? '#' }}" class="rounded transition-colors hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">{{ $menu['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif
        </div>

        <hr class="border-white/15">

        {{-- Baris bawah: hak cipta + media sosial --}}
        <div class="flex flex-col gap-6 @xl:flex-row @xl:items-center @xl:justify-between">
            @if ($copyright)
                <p class="text-sm">{{ $copyright }}</p>
            @endif

            @if (count($socials))
                <ul class="flex items-center gap-5">
                    @foreach ($socials as $social)
                        @if (! empty($social['icon']))
                            <li>
                                <a href="{{ $social['url'] ?? '#' }}"
                                   class="block rounded text-gray-400 transition-colors hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"
                                   aria-label="{{ $social['label'] ?? 'Media sosial' }}"
                                   @if (($social['url'] ?? '#') !== '#') target="_blank" rel="noopener noreferrer" @endif>
                                    <x-dk::icon :path="$social['icon']" class="size-5.5" />
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</footer>
