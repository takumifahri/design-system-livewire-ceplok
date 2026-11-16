@php
    $menuItems = [
        ['label' => 'Menu 1', 'icon' => 'ceplok-s-chart-pie'],
        ['label' => 'Menu 2', 'icon' => 'ceplok-s-file-lines', 'expanded' => false, 'children' => [['label' => 'Sub menu 1'], ['label' => 'Sub menu 2']]],
        ['label' => 'Menu 3', 'icon' => 'ceplok-s-cart', 'active' => true, 'expanded' => true, 'children' => [['label' => 'Sub menu 1', 'active' => true], ['label' => 'Sub menu 2']]],
        ['label' => 'Menu 4', 'icon' => 'ceplok-s-inbox'],
        ['label' => 'Menu 5', 'icon' => 'ceplok-s-lock', 'expanded' => false, 'children' => [['label' => 'Sub menu 1'], ['label' => 'Sub menu 2']]],
    ];
    $drawerProps = [
        ['open', 'bool', 'false', 'Status buka Drawer; mendukung wire:model dan x-model.'],
        ['position', 'right | left | top | bottom', 'right', 'Arah panel muncul.'],
        ['size', 's | m | l | xl | full', 'm', 'Ukuran panel.'],
        ['eyebrow', 'string', 'null', 'Label di header otomatis.'],
        ['header', 'Htmlable', 'null', 'Konten header kustom.'],
        ['items', 'array', 'null', 'Menu dengan icon, badge, href, active, disabled, attributes, serta children.'],
        ['theme', 'primary | purple | blue | gray', 'primary', 'Warna item aktif.'],
        ['font', 'lato | inter | arial', 'lato', 'Keluarga font panel dan seluruh item Drawer.'],
        ['footer', 'Htmlable', 'null', 'Konten footer.'],
        ['dark-mode', 'bool', 'false', 'Tema gelap.'],
    ];
@endphp

<x-docs.layout title="Drawer">
    <x-docs.page title="Drawer" description="Drawer adalah panel navigasi off-canvas di tepi layar untuk menampilkan menu dan konten sekunder tanpa meninggalkan konteks utama." :toc="['variants' => 'Variants', 'dark-mode' => 'Dark Mode', 'menu' => 'Menu', 'playground' => 'Playground', 'penggunaan' => 'Penggunaan', 'livewire' => 'Livewire', 'properties' => 'Properties']">
        <x-docs.section id="variants" title="Variants">
            <style>
                #variants .ds-scroll-x { height: 600px; }
                #variants .absolute.inset-y-0.right-0 { width: 320px; }
                #variants .ds-scroll-x .size-7.bg-primary-600 {
                    background-color: transparent;
                    background-image: url('/images/ceplok.svg');
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: contain;
                    color: transparent;
                }
                @media (min-width: 1024px) {
                    #variants .ds-scroll-x { overflow-x: visible; }
                }
                @media (max-width: 1023px) {
                    #variants .ds-scroll-x { justify-content: flex-start; overflow-x: auto; }
                    #variants .ds-scroll-x > div { max-width: none; min-width: 420px; }
                }
            </style>
            @if (true)
            <div class="mt-3">
                <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Drawer dapat berada dalam kondisi <strong>Terbuka (Open Drawer)</strong> saat dipanggil oleh pengguna, maupun <strong>Tertutup (Closed Drawer)</strong> dengan pemicu tombol hamburger menu pada header halaman.</p>
                <div class="grid gap-6 lg:grid-cols-2">
                    <article class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs">
                        <div class="shrink-0 border-b border-gray-200 bg-white px-4 py-3"><h3 class="text-sm font-bold text-gray-900">1. Drawer Terbuka (Open Drawer)</h3><p class="mt-1 text-xs leading-5 text-gray-500">Drawer muncul dari sisi kanan secara mengambang (overlay) di atas tampilan layar mobile.</p></div>
                        <div class="ds-scroll-x flex h-[500px] w-full items-center justify-center overflow-x-auto bg-gray-50/80 py-4">
                            <div class="relative flex h-full w-[420px] max-w-full shrink-0 flex-col overflow-hidden rounded-none border-x border-gray-200 bg-white shadow-md">
                                <div class="flex h-full w-full flex-col"><div class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 py-3.5"><div class="flex items-center gap-2.5"><div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">C</div><span class="text-base font-bold tracking-tight text-gray-900">CEPLOK</span></div><button type="button" aria-label="Buka menu drawer" class="inline-flex shrink-0 items-center justify-center rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700"><x-ceplok-o-bars-from-left class="size-6" /></button></div><div class="min-w-0 flex-1 overflow-y-auto bg-white p-6"><h4 class="mb-4 text-xl font-bold tracking-tight text-gray-900">Halaman Beranda</h4><p class="text-sm text-gray-500">Anda sedang berada di halaman utama Halaman Beranda.</p></div></div>
                                <div class="pointer-events-none absolute inset-0 z-10 bg-gray-900/20"></div>
                                <div class="absolute inset-y-0 right-0 z-20 flex h-full w-[250px] flex-col border-l border-gray-200 bg-white shadow-2xl"><div class="flex shrink-0 items-center justify-between gap-4 px-5 pb-2 pt-4"><span class="text-base font-semibold tracking-wider text-gray-500">MENU</span><button type="button" aria-label="Tutup drawer" class="-mr-2 ml-auto inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700"><x-ceplok-o-close class="size-[18px]" /></button></div><div class="flex-1 space-y-1 overflow-y-auto p-3">@foreach ($menuItems as $item)<div class="w-full"><div @class(['group flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left text-base font-medium transition-all', !empty($item['active']) ? 'bg-blue-50 text-blue-600' : 'text-gray-900 hover:bg-blue-50 hover:text-blue-600'])><div class="flex min-w-0 flex-1 items-center gap-3">@if (!empty($item['icon']))<span @class(['flex size-[18px] shrink-0 items-center justify-center', !empty($item['active']) ? 'text-blue-600' : 'text-gray-500'])>{!! \CeplokUi\DesignKit\Support\Content::icon($item['icon']) !!}</span>@endif<span class="truncate">{{ $item['label'] }}</span></div>@if (!empty($item['children']))@if (!empty($item['expanded']))<x-ceplok-o-chevron-up class="size-[18px] shrink-0" />@else<x-ceplok-o-chevron-down class="size-[18px] shrink-0" />@endif@endif</div>@if (!empty($item['children']) && !empty($item['expanded']))<div class="mt-1 space-y-1 pl-8">@foreach ($item['children'] as $child)<div @class(['rounded-lg px-3 py-2 text-base font-medium', !empty($child['active']) ? 'bg-blue-50/70 text-blue-600' : 'text-gray-900'])>{{ $child['label'] }}</div>@endforeach</div>@endif</div>@endforeach</div></div>
                            </div>
                        </div>
                    </article>
                    <article class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs">
                        <div class="shrink-0 border-b border-gray-200 bg-white px-4 py-3"><h3 class="text-sm font-bold text-gray-900">2. Drawer Tertutup (Closed State / Page View)</h3><p class="mt-1 text-xs leading-5 text-gray-500">Tampilan utama layar mobile dengan topbar header dan icon hamburger drawer di bagian kanan.</p></div>
                        <div class="ds-scroll-x flex h-[500px] w-full items-center justify-center overflow-x-auto bg-gray-50/80 py-4"><div class="relative flex h-full w-[420px] max-w-full shrink-0 flex-col overflow-hidden rounded-none border-x border-gray-200 bg-white shadow-md"><div class="flex h-full w-full flex-col"><div class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 py-3.5"><div class="flex items-center gap-2.5"><div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">C</div><span class="text-base font-bold tracking-tight text-gray-900">CEPLOK</span></div><button type="button" aria-label="Buka menu drawer" class="inline-flex shrink-0 items-center justify-center rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700"><x-ceplok-o-bars-from-left class="size-6" /></button></div><div class="min-w-0 flex-1 overflow-y-auto bg-white p-6"><h4 class="mb-4 text-xl font-bold tracking-tight text-gray-900">Halaman Beranda</h4><p class="text-sm text-gray-500">Anda sedang berada di halaman utama Halaman Beranda.</p></div></div></div></div>
                    </article>
                </div>
            </div>
            @endif

            @if (false)
            <x-docs.lead>Drawer dapat berada dalam kondisi <strong>Terbuka (Open Drawer)</strong> saat dipanggil oleh pengguna, maupun <strong>Tertutup (Closed Drawer)</strong> dengan pemicu tombol hamburger menu pada header halaman.</x-docs.lead>
            <style>
                #variants .ds-scroll-x { height: 432px; overflow-y: hidden; }
                #variants .ds-scroll-x > div { transform: scale(.6); transform-origin: center; }
            </style>
            <div class="grid gap-6 lg:grid-cols-2">
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs">
                    <div class="border-b border-gray-200 px-4 py-3"><h3 class="text-sm font-bold text-gray-900">1. Drawer Terbuka (Open Drawer)</h3><p class="mt-1 text-xs leading-5 text-gray-500">Drawer muncul dari sisi kanan secara mengambang (overlay) di atas tampilan layar mobile.</p></div>
                    <div class="ds-scroll-x flex h-[720px] items-center justify-center overflow-x-auto bg-gray-50 px-5 py-6"><div class="relative flex h-[700px] w-[628px] shrink-0 overflow-hidden border-x border-gray-200 bg-white shadow-md"><div class="w-full"><div class="flex h-[96px] items-center border-b border-gray-200 px-6"><div class="flex size-10 items-center justify-center rounded-lg bg-primary-600 text-lg font-semibold text-white">C</div><span class="ml-4 text-xl font-bold text-gray-900">CEPLOK</span></div><div class="p-9"><h4 class="text-2xl font-bold">Halaman Beranda</h4><p class="mt-6 text-base text-gray-500">Anda sedang berada di halaman utama Halaman Beranda.</p></div></div><div class="absolute inset-0 bg-gray-900/20"></div><div class="absolute inset-y-0 right-0 flex w-[375px] flex-col border-l border-gray-200 bg-white shadow-2xl"><div class="flex items-center justify-between px-7 pb-3 pt-7"><span class="text-xl font-semibold tracking-wider text-gray-500">MENU</span><x-ceplok-o-close class="size-6 text-gray-500" /></div><div class="space-y-1 p-5 text-gray-600">@foreach ($menuItems as $item)<div @class(['rounded-lg px-3 py-2.5 text-base font-medium', !empty($item['active']) ? 'bg-blue-50 text-blue-600' : 'text-gray-900'])><div class="flex items-center gap-3">@if (!empty($item['icon']))<span @class(['flex size-[18px] items-center justify-center', !empty($item['active']) ? 'text-blue-600' : 'text-gray-500'])>{!! \CeplokUi\DesignKit\Support\Content::icon($item['icon']) !!}</span>@endif<span>{{ $item['label'] }}</span>@if (!empty($item['children']))@if (!empty($item['expanded']))<x-ceplok-o-chevron-up class="ml-auto size-[18px]" />@else<x-ceplok-o-chevron-down class="ml-auto size-[18px]" />@endif@endif</div>@if (!empty($item['children']) && !empty($item['expanded']))<div class="mt-1 space-y-1 pl-8 text-base font-medium text-gray-900">@foreach ($item['children'] as $child)<div @class(['rounded-lg px-3 py-2', !empty($child['active']) ? 'bg-blue-50 text-blue-600' : ''])>{{ $child['label'] }}</div>@endforeach</div>@endif</div>@endforeach</div></div></div></div>
                </article>
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs">
                    <div class="border-b border-gray-200 px-4 py-3"><h3 class="text-sm font-bold text-gray-900">2. Drawer Tertutup (Closed State / Page View)</h3><p class="mt-1 text-xs leading-5 text-gray-500">Tampilan utama layar mobile dengan topbar header dan icon hamburger drawer di bagian kanan.</p></div>
                    <div class="ds-scroll-x flex h-[720px] items-center justify-center overflow-x-auto bg-gray-50 px-5 py-6"><div class="flex h-[700px] w-[628px] shrink-0 flex-col border-x border-gray-200 bg-white shadow-md"><div class="flex h-[96px] items-center border-b border-gray-200 px-6"><div class="flex size-10 items-center justify-center rounded-lg bg-primary-600 text-lg font-semibold text-white">C</div><span class="ml-4 text-xl font-bold text-gray-900">CEPLOK</span><x-ceplok-o-bars-from-left class="ml-auto size-7 text-gray-500" /></div><div class="p-9"><h4 class="text-2xl font-bold">Halaman Beranda</h4><p class="mt-6 text-base text-gray-500">Anda sedang berada di halaman utama Halaman Beranda.</p></div></div></div>
                </article>
            </div>
            @endif
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark Mode">
            <style>
                #dark-mode .ds-scroll-x { height: 600px; }
                #dark-mode .ds-scroll-x > div { height: 100%; width: 420px; max-width: 100%; }
                #dark-mode .absolute.inset-y-0.right-0 { z-index: 20; width: 320px; }
                #dark-mode .ds-scroll-x > div > .w-full > div:first-child > img { display: none; }
                #dark-mode .ds-scroll-x > div > .w-full > div:first-child::before {
                    display: flex;
                    width: 1.75rem;
                    height: 1.75rem;
                    flex: none;
                    align-items: center;
                    justify-content: center;
                    border-radius: .5rem;
                    background: transparent url('/images/ceplok.svg') center / contain no-repeat;
                    color: transparent;
                    content: '';
                    font-size: .75rem;
                    font-weight: 700;
                }
                #dark-mode .ds-scroll-x > div > .w-full > div:first-child > span { margin-left: .625rem; }
                @media (min-width: 1024px) {
                    #dark-mode .ds-scroll-x { overflow-x: visible; }
                }
                @media (max-width: 1023px) {
                    #dark-mode .ds-scroll-x { justify-content: flex-start; overflow-x: auto; }
                    #dark-mode .ds-scroll-x > div { max-width: none; min-width: 420px; }
                }
            </style>
            <x-docs.lead>Tampilan yang sama tersedia pada dark mode; latar, teks, border, dan state aktif menyesuaikan secara konsisten.</x-docs.lead>
            <div class="grid gap-6 lg:grid-cols-2">
                <article class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900 shadow-xs">
                    <div class="border-b border-gray-800 px-4 py-3"><h3 class="text-sm font-bold text-white">1. Drawer Terbuka (Dark Mode)</h3><p class="mt-1 text-xs leading-5 text-gray-400">Panel overlay dengan warna gelap.</p></div>
                    <div class="ds-scroll-x flex h-[520px] items-center justify-center overflow-x-auto bg-gray-950 px-6 py-4"><div class="relative flex h-[480px] w-[420px] shrink-0 overflow-hidden border-x border-gray-700 bg-gray-800 shadow-2xl"><div class="w-full"><div class="flex h-[72px] items-center border-b border-gray-700 px-4"><img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-8 object-contain"><span class="ml-3 text-base font-bold text-white">CEPLOK</span></div><div class="text-gray-300" style="padding: 28px"><h4 class="text-lg font-bold text-white">Halaman Beranda</h4><p class="mt-4 text-xs text-gray-400">Anda sedang berada di halaman utama.</p></div></div><div class="absolute inset-0 bg-gray-900/50"></div><div class="absolute inset-y-0 right-0 flex w-[260px] flex-col border-l border-gray-700 bg-gray-800 font-sans"><div class="flex items-center justify-between px-5 pb-2 pt-4"><span class="text-base font-semibold tracking-wider text-gray-500">MENU</span><x-ceplok-o-close class="size-5 text-gray-500" /></div><div class="space-y-1 p-3 text-xs text-gray-400">@foreach ($menuItems as $item)<div @class(['rounded-lg px-3 py-2.5 text-base font-medium', !empty($item['active']) ? 'bg-gray-700 text-white' : 'text-gray-50'])><div class="flex items-center gap-3">@if (!empty($item['icon']))<span @class(['flex size-[18px] items-center justify-center', !empty($item['active']) ? 'text-white' : 'text-gray-400'])>{!! \CeplokUi\DesignKit\Support\Content::icon($item['icon']) !!}</span>@endif<span>{{ $item['label'] }}</span>@if (!empty($item['children']))@if (!empty($item['expanded']))<x-ceplok-o-chevron-up class="ml-auto size-[18px] text-white" />@else<x-ceplok-o-chevron-down class="ml-auto size-[18px] text-gray-400" />@endif@endif</div>@if (!empty($item['children']) && !empty($item['expanded']))<div class="mt-1 space-y-1 pl-8 text-base font-medium text-gray-50">@foreach ($item['children'] as $child)<div @class(['rounded-lg px-3 py-2', !empty($child['active']) ? 'bg-gray-700/50 text-white' : ''])>{{ $child['label'] }}</div>@endforeach</div>@endif</div>@endforeach</div></div></div></div>
                </article>
                <article class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900 shadow-xs">
                    <div class="border-b border-gray-800 px-4 py-3"><h3 class="text-sm font-bold text-white">2. Drawer Tertutup (Dark Mode)</h3><p class="mt-1 text-xs leading-5 text-gray-400">Halaman mobile sebelum Drawer dibuka.</p></div>
                    <div class="ds-scroll-x flex h-[520px] items-center justify-center overflow-x-auto bg-gray-950 px-6 py-4"><div class="flex h-[480px] w-[420px] shrink-0 flex-col border-x border-gray-700 bg-gray-800 shadow-2xl"><div class="flex h-[72px] items-center border-b border-gray-700 px-4"><img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-8 object-contain"><span class="ml-3 text-base font-bold text-white">CEPLOK</span><x-ceplok-o-bars-from-left class="ml-auto size-6 text-gray-300" /></div><div class="text-gray-300" style="padding: 28px"><h4 class="text-lg font-bold text-white">Halaman Beranda</h4><p class="mt-4 text-xs text-gray-400">Anda sedang berada di halaman utama Halaman Beranda.</p></div></div></div>
                </article>
            </div>
        </x-docs.section>

        <x-docs.section id="menu" title="Menu">
            <x-docs.lead>Menu pada Drawer dikonfigurasi melalui prop <code>items</code> sebagai data-driven array. Gunakan <code>children</code> untuk membentuk submenu.</x-docs.lead>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900">Menu</h3>
                    <div class="mb-4 space-y-1 rounded-xl border border-border bg-surface p-4 shadow-xs" style="width: 332px; max-width: 100%">
                        <div class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-base font-medium text-gray-900 transition-colors hover:bg-blue-50 hover:text-blue-600"><span class="flex size-[18px] shrink-0 items-center justify-center text-gray-500 transition-colors group-hover:text-blue-600">{!! \CeplokUi\DesignKit\Support\Content::icon('ceplok-s-chart-pie') !!}</span><span class="truncate">Menu 1</span></div>
                        <div class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-base font-medium text-gray-900 transition-colors hover:bg-blue-50 hover:text-blue-600"><span class="flex size-[18px] shrink-0 items-center justify-center text-gray-500 transition-colors group-hover:text-blue-600">{!! \CeplokUi\DesignKit\Support\Content::icon('ceplok-s-file-lines') !!}</span><span class="truncate">Menu 2</span></div>
                        <div class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-base font-medium text-gray-900 transition-colors hover:bg-blue-50 hover:text-blue-600"><span class="flex size-[18px] shrink-0 items-center justify-center text-gray-500 transition-colors group-hover:text-blue-600">{!! \CeplokUi\DesignKit\Support\Content::icon('ceplok-s-inbox') !!}</span><span class="truncate">Menu 3</span></div>
                    </div>
                    <x-docs.code scroll>@verbatim
<x-ceplok::drawer
    :⟦items⟧="[
        [⟦'icon' => 'ceplok-s-chart-pie'⟧, 'label' => 'Menu 1'],
        [⟦'icon' => 'ceplok-s-file-lines'⟧, 'label' => 'Menu 2'],
        [⟦'icon' => 'ceplok-s-inbox'⟧, 'label' => 'Menu 3'],
    ]"
/>
@endverbatim</x-docs.code>
                </div>
                <div class="min-w-0">
                    <h3 class="mb-3 text-sm font-bold text-gray-900">Sub Menu</h3>
                    <div class="mb-4 rounded-xl border border-border bg-surface p-4 shadow-xs" style="width: 332px; max-width: 100%">
                        <div class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-base font-medium text-gray-900 transition-colors hover:bg-blue-50 hover:text-blue-600"><span class="flex size-[18px] shrink-0 items-center justify-center text-gray-500 transition-colors group-hover:text-blue-600">{!! \CeplokUi\DesignKit\Support\Content::icon('ceplok-s-cart') !!}</span><span class="truncate">Menu 3</span><x-ceplok-o-chevron-up class="ml-auto size-[18px] shrink-0 text-gray-500 transition-colors group-hover:text-blue-600" /></div>
                        <div class="mt-1 space-y-1 pl-8 text-base font-medium text-gray-900"><div class="truncate rounded-lg px-3 py-2 transition-colors hover:bg-blue-50 hover:text-blue-600">sub menu 1</div><div class="truncate rounded-lg px-3 py-2 transition-colors hover:bg-blue-50 hover:text-blue-600">sub menu 2</div></div>
                    </div>
                    <x-docs.code scroll>@verbatim
<x-ceplok::drawer
    :⟦items⟧="[
        [
            ⟦'icon' => 'ceplok-s-cart'⟧, 'label' => 'Menu 3', ⟦'expanded' => true⟧,
            ⟦'children'⟧ => [['label' => 'sub menu 1'], ['label' => 'sub menu 2']],
        ],
    ]"
/>
@endverbatim</x-docs.code>
                </div>
            </div>
        </x-docs.section>

        <livewire:playground.drawer />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>Gunakan <code>wire:model.live</code> untuk mengikat status buka/tutup. <code>wire:close</code> menangkap penutupan dari tombol, backdrop, atau Esc.</x-docs.lead>
            <livewire:playground.drawer-livewire />
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.props-table :rows="$drawerProps" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
