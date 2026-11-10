@php
    use App\Docs\Navigation;
@endphp

{{--
    Layout dokumentasi — port DocsLayout di docs React: rail ikon, panel samping,
    laci navigasi di mobile, pencarian halaman (Ctrl+K), dan footer kit.

    Tautan internal memakai wire:navigate, jadi berpindah halaman terasa seperti
    SPA tanpa memuat ulang aset. Keadaan panel samping disimpan di cookie
    `ceplok_docs_panel` (lihat resources/js/docs/layout.js) sehingga server
    sudah merender lebarnya yang benar sejak HTML pertama.
--}}
@props([
    'title' => 'Design System',
])

@php
    $route = request()->route()?->getName();
    $section = Navigation::sectionOf($route);
    $sidebar = Navigation::sidebars()[$section] ?? null;
    $panel = request()->cookie('ceplok_docs_panel') !== '0';
    $searchIcon = 'm20 20-4.5-4.5M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z';

    // Satu menu di rail: seluruh kolom (ikon + label) bisa diklik, tetapi latar
    // penandanya hanya pill di belakang ikon. Label tanpa latar. text-center:
    // label dua kata ("Portal Dashboard") turun ke baris kedua dan harus tetap
    // di tengah, di bawah ikonnya.
    $railItem = fn (bool $active) => [
        'group flex w-full flex-col items-center gap-1 rounded-lg py-0.5 text-center text-[11px] font-bold transition-colors',
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600',
        $active ? 'text-primary-700' : 'text-gray-500 hover:text-gray-800',
    ];
    $railIcon = fn (bool $active) => [
        'grid h-8 w-14 place-items-center rounded-lg transition-colors',
        $active ? 'bg-primary-50' : 'group-hover:bg-gray-100',
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
    <x-docs.head :title="$title" />
    <body x-data="designSystem">
        <div x-data="docsLayout(@js($panel))" class="flex min-h-screen flex-col bg-gray-50">
            {{-- ══ Top bar — mobile ══ --}}
            <div class="sticky top-0 z-30 flex items-center justify-between border-b border-border bg-white px-4 py-3 lg:hidden">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2.5" aria-label="Beranda">
                    <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-8 shrink-0 object-contain">
                    <span class="text-sm font-black tracking-tight text-gray-900">Design System</span>
                </a>
                <div class="flex items-center gap-1">
                    <button type="button" x-on:click="$dispatch('docs-search')" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100" aria-label="Cari halaman">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="{{ $searchIcon }}" />
                        </svg>
                    </button>
                    <button type="button" x-on:click="drawer = true" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100" aria-label="Buka navigasi">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ══ Laci navigasi — mobile ══ --}}
            <div
                x-show="drawer"
                x-cloak
                x-trap.inert.noscroll="drawer"
                x-on:keydown.escape.window="drawer = false"
                class="fixed inset-0 z-50 lg:hidden"
                role="dialog"
                aria-modal="true"
                aria-label="Navigasi"
            >
                <div
                    x-show="drawer"
                    x-transition:enter="transition-opacity duration-300 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]"
                    x-transition:enter-start="opacity-0"
                    x-transition:leave="transition-opacity duration-300 ease-[cubic-bezier(0.55,0.085,0.68,0.53)]"
                    x-transition:leave-end="opacity-0"
                    x-on:click="drawer = false"
                    class="absolute inset-0 bg-gray-900/50"
                    aria-hidden="true"
                ></div>
                <aside
                    x-show="drawer"
                    x-transition:enter="transition-transform duration-[450ms] ease-[cubic-bezier(0.215,0.61,0.355,1)]"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:leave="transition-transform duration-[450ms] ease-[cubic-bezier(0.55,0.055,0.675,0.19)]"
                    x-transition:leave-end="-translate-x-full"
                    class="ds-scroll-y absolute inset-y-0 left-0 flex w-72 max-w-[85vw] flex-col overflow-y-auto bg-white shadow-2xl"
                >
                    <div class="ds-drawer-item flex items-center justify-between border-b border-border px-5 py-4" style="--i: 0">
                        <span class="text-sm font-black tracking-tight text-gray-900">Navigasi</span>
                        <button type="button" x-on:click="drawer = false" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100" aria-label="Tutup navigasi">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
                            </svg>
                        </button>
                    </div>

                    <nav class="px-4 py-5" x-on:click="$event.target.closest('a') && (drawer = false)">
                        <p class="ds-drawer-item mb-2 px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase" style="--i: 1">Area</p>
                        @foreach (Navigation::rail() as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                wire:navigate
                                @class(['ds-drawer-item ds-nav-link mt-1 w-full', 'is-active' => $section === $item['key']])
                                style="--i: {{ $loop->index + 2 }}"
                            >
                                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        @if ($sidebar)
                            <div class="ds-drawer-item" style="--i: {{ count(Navigation::rail()) + 2 }}">
                                <p class="mt-6 mb-2 px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">{{ $sidebar['title'] }}</p>
                                <x-docs.nav-links :items="$sidebar['items']" :route="$route" />
                            </div>
                        @endif
                    </nav>
                </aside>
            </div>

            {{--
                Baris utama: rail + panel samping + konten. Keduanya `sticky`
                (bukan `fixed`) supaya tingginya berhenti di ujung baris ini —
                footer di bawahnya jadi bisa melebar penuh dari tepi kiri layar.
            --}}
            <div class="flex flex-1">
                {{-- ══ Rail ikon — desktop ══ --}}
                <aside class="sticky top-0 z-30 hidden h-screen w-22 shrink-0 flex-col items-center border-r border-border bg-white px-2 py-5 lg:flex">
                    <a href="{{ route('home') }}" wire:navigate aria-label="Beranda">
                        <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-9 shrink-0 object-contain">
                    </a>
                    {{-- Pencarian halaman — juga lewat Ctrl+K / ⌘K atau "/" dari halaman mana pun. --}}
                    <button
                        type="button"
                        x-on:click="$dispatch('docs-search')"
                        @class(['mt-6', ...$railItem(false)])
                        aria-label="Cari halaman"
                        aria-keyshortcuts="Control+K Meta+K /"
                        title="Cari halaman (Ctrl K)"
                        x-init="$el.title = `Cari halaman (${/Mac|iPhone|iPad/.test(navigator.userAgent) ? '⌘K' : 'Ctrl K'})`"
                    >
                        <span @class($railIcon(false))>
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $searchIcon }}" />
                            </svg>
                        </span>
                        Cari
                    </button>
                    <nav class="mt-3 flex w-full flex-col gap-3" aria-label="Area utama">
                        @foreach (Navigation::rail() as $item)
                            @php $active = $section === $item['key']; @endphp
                            <a href="{{ route($item['route']) }}" wire:navigate @class($railItem($active)) @if ($active) aria-current="page" @endif>
                                <span @class($railIcon($active))>
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                    </svg>
                                </span>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                    {{-- Di area tanpa panel tombolnya tetap ada, hanya dipudarkan. --}}
                    <button
                        type="button"
                        x-on:click="togglePanel()"
                        @class([
                            'mt-auto grid size-10 place-items-center rounded-xl text-gray-400 transition-[opacity,transform,color,background-color] duration-300 hover:bg-gray-100 hover:text-gray-700',
                            $sidebar ? 'opacity-100' : 'pointer-events-none scale-75 opacity-0',
                        ])
                        aria-expanded="{{ $panel ? 'true' : 'false' }}"
                        x-bind:aria-expanded="panel"
                        aria-label="{{ $panel ? 'Sembunyikan panel' : 'Tampilkan panel' }}"
                        x-bind:aria-label="panel ? 'Sembunyikan panel' : 'Tampilkan panel'"
                        @if (! $sidebar) inert @endif
                    >
                        <svg
                            @class(['size-5 transition-transform duration-300', 'rotate-180' => ! $panel])
                            x-bind:class="{ 'rotate-180': ! panel }"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4V5Zm5.5 0v14" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 9.5 13.5 12l2.5 2.5" />
                        </svg>
                    </button>
                </aside>

                {{-- ══ Panel samping — desktop ══ --}}
                {{-- Lebar isi dikunci 248px supaya tidak ikut mengkerut saat panel menutup. --}}
                @if ($sidebar)
                    <aside
                        @class([
                            'sticky top-0 z-20 hidden h-screen shrink-0 overflow-hidden bg-white transition-[width] duration-300 ease-out lg:block',
                            $panel ? 'w-62 border-r border-border' : 'w-0',
                        ])
                        x-bind:class="{ 'w-62 border-r border-border': panel, 'w-0': ! panel }"
                        @if (! $panel) inert @endif
                        x-bind:inert="! panel"
                    >
                        <div class="ds-scroll-y flex h-full w-62 flex-col overflow-y-auto px-5 py-7">
                            <p class="px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">{{ $sidebar['title'] }}</p>
                            <nav class="mt-2" aria-label="{{ $sidebar['title'] }}">
                                <x-docs.nav-links :items="$sidebar['items']" :route="$route" />
                            </nav>
                            <div class="mt-auto rounded-xl bg-gray-50 p-4">
                                <p class="text-xs font-bold text-gray-900">Foundation v1.0</p>
                                <p class="mt-1 text-xs leading-5 text-gray-500">
                                    Laravel · Livewire 4
                                    <br>
                                    Tailwind CSS v4
                                </p>
                            </div>
                        </div>
                    </aside>
                @endif

                {{-- ══ Konten ══ --}}
                {{-- min-w-0: tabel & blok kode lebar menggulung sendiri, tidak melebarkan baris. --}}
                <div class="min-w-0 flex-1">
                    <main>{{ $slot }}</main>
                </div>
            </div>

            {{-- Saudara dari baris di atas — melebar penuh selebar layar, di bawah rail. --}}
            <x-ceplok::footer
                fluid
                logo="/images/footer-logo.svg"
                logo-alt="Ceplok Design System"
                :menus="Navigation::footerMenus()"
                :copyright="'© '.date('Y').' Ceplok Design System'"
                :socials="Navigation::socials()"
            />

            <x-docs.search />
        </div>

        {{-- Livewire dan Alpine dibundel di resources/js/app.js; di sini cukup konfigurasinya. --}}
        @livewireScriptConfig
    </body>
</html>
