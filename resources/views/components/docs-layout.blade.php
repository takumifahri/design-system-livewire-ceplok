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
            ],
        ],
    ];

    $sidebar = $sidebars[$section] ?? null;
    $gridCols = $sidebar ? 'lg:grid-cols-[76px_248px_1fr]' : 'lg:grid-cols-[76px_1fr]';
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
        <div x-data="{ mobileNav: false }" class="min-h-screen">
            {{-- Top bar — mobile only --}}
            <div class="flex items-center justify-between border-b border-border bg-white px-4 py-3 lg:hidden">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Beranda">
                    <span class="grid size-8 grid-cols-2 gap-0.5 rounded-lg bg-primary-700 p-1.5">
                        <span class="rounded-[3px] bg-white"></span><span class="rounded-[3px] bg-primary-300"></span>
                        <span class="rounded-[3px] bg-primary-300"></span><span class="rounded-[3px] bg-white"></span>
                    </span>
                    <span class="text-sm font-black tracking-tight text-gray-900">Design System</span>
                </a>
                <button @click="mobileNav = !mobileNav" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100" :aria-expanded="mobileNav" aria-label="Buka navigasi">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>

            {{-- Mobile nav panel --}}
            <nav x-show="mobileNav" x-cloak class="border-b border-border bg-white px-4 py-4 lg:hidden" aria-label="Navigasi">
                <div class="flex gap-2">
                    @foreach ($rail as $key => $item)
                        <a href="{{ route($item['route']) }}" class="flex-1 rounded-lg border px-3 py-2 text-center text-xs font-bold transition-colors {{ $section === $key ? 'border-primary-200 bg-primary-50 text-primary-700' : 'border-border text-gray-600' }}">{{ $item['label'] }}</a>
                    @endforeach
                </div>
                @if ($sidebar)
                    <p class="mt-4 mb-2 px-1 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">{{ $sidebar['title'] }}</p>
                    @foreach ($sidebar['items'] as $item)
                        <a href="{{ route($item['route']) }}" class="ds-nav-link w-full {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                    @endforeach
                @endif
            </nav>

            <div class="lg:grid {{ $gridCols }} min-h-screen">
                {{-- Icon rail --}}
                <aside class="hidden flex-col items-center border-r border-border bg-white py-5 lg:fixed lg:inset-y-0 lg:flex lg:w-[76px]">
                    <a href="{{ route('home') }}" class="grid size-9 grid-cols-2 gap-0.5 rounded-lg bg-primary-700 p-2 shadow-sm" aria-label="Beranda">
                        <span class="rounded-sm bg-white"></span><span class="rounded-sm bg-primary-300"></span>
                        <span class="rounded-sm bg-primary-300"></span><span class="rounded-sm bg-white"></span>
                    </a>
                    <nav class="mt-8 flex flex-col gap-2" aria-label="Area utama">
                        @foreach ($rail as $key => $item)
                            <a href="{{ route($item['route']) }}"
                               class="group flex w-14 flex-col items-center gap-1 rounded-xl py-2 text-[10px] font-bold transition-colors {{ $section === $key ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}"
                               aria-current="{{ $section === $key ? 'page' : 'false' }}">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </aside>

                {{-- Contextual sidebar --}}
                @if ($sidebar)
                    <aside class="hidden border-r border-border bg-white lg:block lg:fixed lg:inset-y-0 lg:left-[76px] lg:w-[248px]">
                        <div class="flex h-full flex-col px-5 py-7">
                            <p class="px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">{{ $sidebar['title'] }}</p>
                            <nav class="mt-3 space-y-1" aria-label="{{ $sidebar['title'] }}">
                                @foreach ($sidebar['items'] as $item)
                                    <a href="{{ route($item['route']) }}" class="ds-nav-link w-full {{ request()->routeIs($item['route']) ? 'is-active' : '' }}" aria-current="{{ request()->routeIs($item['route']) ? 'page' : 'false' }}">{{ $item['label'] }}</a>
                                @endforeach
                            </nav>
                            <div class="mt-auto hidden rounded-xl bg-gray-50 p-4 lg:block">
                                <p class="text-xs font-bold text-gray-900">Foundation v1.0</p>
                                <p class="mt-1 text-xs leading-5 text-gray-500">Laravel · Alpine.js<br>Tailwind CSS v4</p>
                            </div>
                        </div>
                    </aside>
                @endif

                {{-- Main content --}}
                <main class="{{ $sidebar ? 'lg:col-start-3' : 'lg:col-start-2' }}">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
