@props([
    'section' => 'home',
    'title' => 'Design System',
])

@php
    // Rail ikon paling kiri — tiga area utama situs.
    $rail = [
        'home' => [
            'label' => 'Home',
            'route' => 'home',
            'icon' => 'M3 10.5 12 3l9 7.5M5.5 9.5V20h13V9.5M9.5 20v-6h5v6',
        ],
        'components' => [
            'label' => 'Components',
            'route' => 'components.index',
            'icon' => 'M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z',
        ],
        'foundations' => [
            'label' => 'Foundations',
            'route' => 'foundations.colors',
            'icon' => 'M12 3.5c3.8 3.2 6.5 6 6.5 9.5a6.5 6.5 0 1 1-13 0c0-3.5 2.7-6.3 6.5-9.5Z',
        ],
    ];

    // Sidebar kontekstual per area. Home tidak punya sidebar (konten full width).
    $sidebars = [
        'foundations' => [
            'title' => 'Foundations',
            'items' => [
                ['label' => 'Colors', 'route' => 'foundations.colors'],
                ['label' => 'Typography', 'route' => 'foundations.typography'],
                ['label' => 'Spacing', 'route' => 'foundations.spacing'],
                ['label' => 'Border', 'route' => 'foundations.border'],
                ['label' => 'Elevation', 'route' => 'foundations.elevation'],
                ['label' => 'Icons', 'route' => 'foundations.icons'],
            ],
        ],
        'components' => [
            'title' => 'Components',
            'items' => [
                ['label' => 'Overview', 'route' => 'components.index'],
                ['label' => 'Button', 'route' => 'components.button'],
                ['label' => 'Badge', 'route' => 'components.badge'],
                ['label' => 'Card', 'route' => 'components.card'],
                ['label' => 'Footer', 'route' => 'components.footer'],
            ],
        ],
    ];

    $sidebar = $sidebars[$section] ?? null;
@endphp

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="State Security Service Design System — foundations & components.">

        <title>{{ $title }} — State Security Service Design System</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="designSystem" class="min-h-screen bg-gray-50">
        <div x-data="{ drawer: false, sidebar: true }"
             @keydown.escape.window="drawer = false"
             x-effect="document.body.classList.toggle('overflow-hidden', drawer)">

            {{-- ══ Top bar — mobile ══ --}}
            <div class="sticky top-0 z-30 flex items-center justify-between border-b border-border bg-white px-4 py-3 lg:hidden">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Beranda">
                    <span class="grid size-8 grid-cols-2 gap-0.5 rounded-lg bg-primary-700 p-1.5">
                        <span class="rounded-[3px] bg-white"></span><span class="rounded-[3px] bg-primary-300"></span>
                        <span class="rounded-[3px] bg-primary-300"></span><span class="rounded-[3px] bg-white"></span>
                    </span>
                    <span class="text-sm font-black tracking-tight text-gray-900">Design System</span>
                </a>
                <button @click="drawer = true" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100" aria-label="Buka navigasi">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>

            {{-- ══ Drawer — mobile ══ --}}
            <div x-show="drawer" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true" aria-label="Navigasi">
                <div x-show="drawer"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     @click="drawer = false"
                     class="absolute inset-0 bg-gray-900/50"></div>

                <aside x-show="drawer"
                       x-transition:enter="transition ease-out duration-300"
                       x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                       x-transition:leave="transition ease-in duration-200"
                       x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                       class="absolute inset-y-0 left-0 flex w-72 max-w-[85vw] flex-col overflow-y-auto bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b border-border px-5 py-4">
                        <span class="text-sm font-black tracking-tight text-gray-900">Navigasi</span>
                        <button @click="drawer = false" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100" aria-label="Tutup navigasi">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                        </button>
                    </div>

                    <nav class="px-4 py-5">
                        <p class="mb-2 px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">Area</p>
                        @foreach ($rail as $key => $item)
                            <a href="{{ route($item['route']) }}" class="ds-nav-link mt-1 w-full {{ $section === $key ? 'is-active' : '' }}">
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        @if ($sidebar)
                            <p class="mt-6 mb-2 px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">{{ $sidebar['title'] }}</p>
                            @foreach ($sidebar['items'] as $item)
                                <a href="{{ route($item['route']) }}" class="ds-nav-link mt-1 w-full {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                            @endforeach
                        @endif
                    </nav>
                </aside>
            </div>

            {{-- ══ Icon rail — desktop ══ --}}
            <aside class="fixed inset-y-0 left-0 z-30 hidden w-[76px] flex-col items-center border-r border-border bg-white py-5 lg:flex">
                <a href="{{ route('home') }}" class="grid size-9 grid-cols-2 gap-0.5 rounded-lg bg-primary-700 p-2 shadow-sm" aria-label="Beranda">
                    <span class="rounded-sm bg-white"></span><span class="rounded-sm bg-primary-300"></span>
                    <span class="rounded-sm bg-primary-300"></span><span class="rounded-sm bg-white"></span>
                </a>

                <nav class="mt-8 flex flex-col gap-2" aria-label="Area utama">
                    @foreach ($rail as $key => $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex w-14 flex-col items-center gap-1 rounded-xl py-2 text-[10px] font-bold transition-colors {{ $section === $key ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}"
                           aria-current="{{ $section === $key ? 'page' : 'false' }}">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                @if ($sidebar)
                    <button @click="sidebar = !sidebar"
                            class="mt-auto grid size-10 place-items-center rounded-xl text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-700"
                            :aria-expanded="sidebar"
                            :aria-label="sidebar ? 'Sembunyikan panel' : 'Tampilkan panel'">
                        <svg class="size-5 transition-transform duration-300" :class="sidebar ? '' : 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4V5Zm5.5 0v14"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 9.5 13.5 12l2.5 2.5"/>
                        </svg>
                    </button>
                @endif
            </aside>

            {{-- ══ Sidebar drawer — desktop ══ --}}
            @if ($sidebar)
                <aside class="fixed inset-y-0 left-[76px] z-20 hidden w-[248px] overflow-y-auto border-r border-border bg-white transition-transform duration-300 ease-out lg:block"
                       :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
                       :aria-hidden="!sidebar">
                    <div class="flex h-full flex-col px-5 py-7">
                        <p class="px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">{{ $sidebar['title'] }}</p>
                        <nav class="mt-3 space-y-1" aria-label="{{ $sidebar['title'] }}">
                            @foreach ($sidebar['items'] as $item)
                                <a href="{{ route($item['route']) }}" class="ds-nav-link w-full {{ request()->routeIs($item['route']) ? 'is-active' : '' }}" aria-current="{{ request()->routeIs($item['route']) ? 'page' : 'false' }}">{{ $item['label'] }}</a>
                            @endforeach
                        </nav>
                        <div class="mt-auto rounded-xl bg-gray-50 p-4">
                            <p class="text-xs font-bold text-gray-900">Foundation v1.0</p>
                            <p class="mt-1 text-xs leading-5 text-gray-500">Laravel · Alpine.js<br>Tailwind CSS v4</p>
                        </div>
                    </div>
                </aside>
            @endif

            {{-- ══ Konten ══ --}}
            {{-- Padding kiri = rail (76px), plus lebar sidebar (248px) saat terbuka. --}}
            <div class="transition-[padding] duration-300 ease-out @if (! $sidebar) lg:pl-[76px] @endif"
                 @if ($sidebar) :class="sidebar ? 'lg:pl-[324px]' : 'lg:pl-[76px]'" @endif>
                <main>{{ $slot }}</main>
            </div>
        </div>
    </body>
</html>
