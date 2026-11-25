@php
    $docsDark = \App\Docs\Theme::dark();
    $brand = ['content' => new \Illuminate\Support\HtmlString('<span class="text-lg font-black">Ceplok</span>'), 'href' => '#', 'ariaLabel' => 'Ceplok'];
    $items = [
        ['type' => 'destination', 'id' => 'home', 'label' => 'Beranda', 'href' => '#beranda', 'current' => true],
        ['type' => 'destination', 'id' => 'profile', 'label' => 'Profil Instansi', 'href' => '#profil'],
        ['type' => 'disclosure', 'id' => 'services', 'label' => 'Layanan Digital', 'children' => [
            ['type' => 'destination', 'id' => 'service-catalog', 'label' => 'Katalog Layanan', 'href' => '#katalog'],
            ['type' => 'destination', 'id' => 'service-status', 'label' => 'Status Permohonan', 'href' => '#status'],
        ]],
        ['type' => 'destination', 'id' => 'information', 'label' => 'Informasi Publik', 'href' => '#informasi'],
    ];
    $search = ['placeholder' => 'Cari layanan'];
    $guestActions = ['signIn' => ['label' => 'Masuk', 'href' => '#masuk'], 'signUp' => ['label' => 'Daftar', 'href' => '#daftar']];
    $account = ['name' => 'Ayu Lestari', 'initials' => 'AL', 'items' => [
        ['type' => 'destination', 'id' => 'account-profile', 'label' => 'Profil', 'href' => '#profil-akun'],
        ['type' => 'action', 'id' => 'account-logout', 'label' => 'Keluar', 'tone' => 'danger', 'attributes' => ['x-on:click' => '']],
    ]];
    $sidebarUser = ['name' => 'Ayu Lestari', 'initials' => 'AL'];
    $hl = 'text-primary-500 dark:text-primary-400';
    $props = [
        ['platform', '"front-office" | "back-office"', 'required', 'Menentukan kontrak komposisi Front Office atau Back Office.'],
        ['brand', 'array', 'required', 'content, href, dan ariaLabel milik consumer.'],
        ['items', 'array', '[]', 'Destination dan disclosure navigasi utama.'],
        ['search', 'array', 'null', 'Konfigurasi Search internal. BO dapat menyertakan categories.'],
        ['account', 'array', 'null', 'Identitas dan menu akun Front Office authenticated.'],
        ['guest-actions', 'array', 'null', 'Aksi signIn dan signUp Front Office guest.'],
        ['notification-trigger', 'array', 'null', 'ariaLabel, badge, dan attributes untuk tombol notifikasi.'],
        ['sidebar-items', 'array', '[]', 'Navigasi Sidebar aplikasi Back Office yang dipakai kembali pada mobile.'],
        ['sidebar-user', 'array', 'null', 'Identitas Sidebar Back Office yang dipakai kembali pada mobile.'],
        ['dark-mode', 'bool', 'false', 'Boundary tema gelap Navbar dan surface mobile.'],
        ['mobile-navigation-open', 'bool', 'false', 'State awal navigasi mobile tanpa binding.'],
        ['wire:model / x-model', 'atribut', '—', 'Mengikat state navigasi mobile; padanan mobileNavigationOpen + onMobileNavigationOpenChange.'],
        ['open-change', 'event', '—', 'Dikirim saat navigasi mobile meminta perubahan state, dengan detail.open.'],
        ['class / …atribut', 'atribut HTML', '—', 'Class dan atribut tambahan diteruskan ke elemen header.'],
    ];
    $types = [
        ['NavbarDestination', 'array', '—', 'type=destination, id, label, href; mendukung target, rel, current, disabled, icon, dan attributes.'],
        ['NavbarDisclosure', 'array', '—', 'type=disclosure, id, label, children; parent adalah button disclosure dan tidak bernavigasi.'],
        ['NavbarAccount', 'array', '—', 'name, description, avatarSrc, initials, dan items opsional.'],
        ['NavbarAccountItem', 'array', '—', 'Destination atau action akun; mendukung tone default/danger, disabled, dan attributes.'],
        ['NavbarGuestActions', 'array', '—', 'signIn wajib dan signUp opsional; tiap link berisi label, href, dan attributes.'],
    ];
@endphp

<x-docs.layout title="Navbar">
    <x-docs.page
        eyebrow="Components · Navbar"
        title="Navbar"
        description="Header navigasi global yang menyatukan brand, navigasi utama, pencarian, notifikasi, dan akses akun dalam komposisi Front Office atau Back Office yang responsif."
        :toc="[
            'navbar' => 'Navbar',
            'platform' => 'Platform',
            'identity-account' => 'Identity & Account',
            'navigation' => 'Navigation & Disclosure',
            'slots' => 'Search & consumer slots',
            'responsive' => 'Responsive behavior',
            'dark-mode' => 'Dark Mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="navbar" title="Navbar">
            <x-docs.lead>
                Navbar adalah header navigasi global aplikasi. Komponen menyusun brand, navigasi utama,
                Search, tombol notifikasi, dan identitas atau aksi pengguna, lalu otomatis mengganti
                komposisi desktop dan mobile. Navbar tidak menangani routing, hasil pencarian, atau data
                notifikasi; consumer memasok datanya dan memperbarui current route.
            </x-docs.lead>
            <x-docs.navbar-frame title="Front Office" :src="route('previews.navbar', ['dark' => (int) $docsDark])" :dark="$docsDark" />
            <x-docs.code>
                @verbatim
                <x-ceplok::navbar⟨ dark-mode⟩
                    ⟦platform⟧="front-office"
                    :brand="$brand"
                    :items="$items"
                    :search="$search"
                    :guest-actions="$guestActions"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                Front Office dan Back Office memiliki kontrak komposisi berbeda. Pada BO, items adalah
                navigasi Navbar desktop sekaligus navigasi mobile, sedangkan sidebarItems dan sidebarUser
                berasal dari Sidebar aplikasi yang dirender consumer pada desktop dan dipakai kembali pada mobile.
            </x-docs.lead>
            <div class="space-y-8">
                <div class="space-y-4">
                    <x-docs.navbar-frame title="Front Office" :src="route('previews.navbar', ['dark' => (int) $docsDark])" :dark="$docsDark" />
                    <x-docs.code>@verbatim<x-ceplok::navbar platform="front-office" :brand="$brand" :items="$items" :search="$search" :guest-actions="$guestActions" />@endverbatim</x-docs.code>
                </div>
                <div class="space-y-4">
                    <x-docs.navbar-frame title="Back Office" :src="route('previews.navbar', ['platform' => 'back-office', 'identity' => 'authenticated', 'notification' => 1, 'dark' => (int) $docsDark])" :width="1184" :dark="$docsDark" />
                    <x-docs.code>@verbatim<x-ceplok::navbar platform="back-office" :brand="$brand" :items="$navbarItems" :sidebar-items="$sidebarItems" :sidebar-user="$sidebarUser" :search="$search" :notification-trigger="$notificationTrigger" />@endverbatim</x-docs.code>
                </div>
            </div>
        </x-docs.section>

        <x-docs.section id="identity-account" title="Identity & Account">
            <x-docs.lead>
                FO guest memakai guestActions dan FO authenticated memakai account. Pada BO, identitas
                beserta aksi akun dimiliki Sidebar; Navbar memakai sidebarUser kembali pada komposisi mobile.
            </x-docs.lead>
            <div class="space-y-8">
                <div class="space-y-4"><x-docs.navbar-frame title="FO guest" :src="route('previews.navbar', ['identity' => 'guest', 'dark' => (int) $docsDark])" :dark="$docsDark" /><x-docs.code>@verbatim<x-ceplok::navbar platform="front-office" :brand="$brand" :items="$items" :search="$search" :guest-actions="$guestActions" />@endverbatim</x-docs.code></div>
                <div class="space-y-4"><x-docs.navbar-frame title="FO authenticated" :src="route('previews.navbar', ['identity' => 'authenticated', 'notification' => 1, 'dark' => (int) $docsDark])" :dark="$docsDark" /><x-docs.code>@verbatim<x-ceplok::navbar platform="front-office" :brand="$brand" :items="$items" :search="$search" :account="$account" :notification-trigger="$notificationTrigger" />@endverbatim</x-docs.code></div>
                <div class="space-y-4"><x-docs.navbar-frame title="Back Office identity in Sidebar" :src="route('previews.navbar', ['platform' => 'back-office', 'identity' => 'authenticated', 'notification' => 0, 'dark' => (int) $docsDark])" :width="1184" :dark="$docsDark" /><x-docs.code>@verbatim<x-ceplok::sidebar :user="$sidebarUser" />
<x-ceplok::navbar platform="back-office" :sidebar-user="$sidebarUser" ... />@endverbatim</x-docs.code></div>
            </div>
        </x-docs.section>

        <x-docs.section id="navigation" title="Navigation & Disclosure">
            <x-docs.lead>
                Destination memakai anchor native; Disclosure dan trigger mobile memakai button native.
                <span class="{{ $hl }}">current</span> menjadi aria-current. Item disabled tidak memiliki
                tujuan atau event. Parent disclosure tidak bernavigasi; hanya children yang menjadi destination.
            </x-docs.lead>
            <div class="space-y-8">
                <div class="space-y-4"><x-docs.navbar-frame title="Destination" :src="route('previews.navbar', ['identity' => 'authenticated', 'disclosure' => 0, 'dark' => (int) $docsDark])" :dark="$docsDark" /><x-docs.code>@verbatim['type' => 'destination', 'id' => 'home', 'label' => 'Beranda', 'href' => '/beranda']@endverbatim</x-docs.code></div>
                <div class="space-y-4"><x-docs.navbar-frame title="Disclosure" :src="route('previews.navbar', ['identity' => 'authenticated', 'disclosure' => 1, 'dark' => (int) $docsDark])" :dark="$docsDark" /><x-docs.code>@verbatim['type' => 'disclosure', 'id' => 'services', 'label' => 'Layanan Digital', 'children' => $destinations]@endverbatim</x-docs.code></div>
                <div class="space-y-4"><x-docs.navbar-frame title="Disabled" :src="route('previews.navbar', ['identity' => 'authenticated', 'disclosure' => 0, 'disabled' => 1, 'dark' => (int) $docsDark])" :dark="$docsDark" /><x-docs.code>@verbatim['type' => 'destination', 'id' => 'information', 'label' => 'Informasi Publik', 'href' => '/informasi-publik', 'disabled' => true]@endverbatim</x-docs.code></div>
            </div>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Jangan gunakan href pada parent disclosure. Untuk mempertahankan halaman ringkasan lama,
                tambahkan destination “Ringkasan” sebagai child pertama.
            </p>
        </x-docs.section>

        <x-docs.section id="slots" title="Search & consumer slots">
            <x-docs.lead>
                Navbar merender Search internal dari konfigurasi consumer. notificationTrigger mengonfigurasi
                tombol Bell bawaan. Search FO compact tanpa kategori dan tombol secara default; Search BO dapat
                memakai kategori dan tombol. Untuk filter atau panel kontekstual lain, render Drawer di luar Navbar.
            </x-docs.lead>
            <div class="space-y-8">
                <div class="space-y-4">
                    <x-docs.navbar-frame title="Front Office Search & notification" :src="route('previews.navbar', ['identity' => 'authenticated', 'notification' => 1, 'dark' => (int) $docsDark])" :dark="$docsDark" />
                    <x-docs.code>@verbatim<x-ceplok::navbar platform="front-office" :search="$search" :account="$account" :notification-trigger="$notificationTrigger" ... />@endverbatim</x-docs.code>
                </div>
                <div class="space-y-4">
                    <x-docs.navbar-frame title="Back Office categorized Search & automatic navigation Drawer" :src="route('previews.navbar', ['platform' => 'back-office', 'identity' => 'authenticated', 'dark' => (int) $docsDark])" :width="1184" :dark="$docsDark" />
                    <x-docs.code>@verbatim<x-ceplok::navbar
    platform="back-office"
    :search="['categories' => $searchCategories, 'categoryPlaceholder' => 'Kategori']"
    :sidebar-items="$sidebarItems"
    :sidebar-user="$sidebarUser"
    ...
/>@endverbatim</x-docs.code>
                </div>
            </div>
        </x-docs.section>

        <x-docs.section id="responsive" title="Responsive behavior">
            <x-docs.lead>
                Viewport berasal dari environment consumer, bukan prop Navbar. Pada BO desktop, consumer merender
                Sidebar aplikasi dan TOC halaman di layout konten. Pada mobile, Navbar merender sidebarItems dan
                sidebarUser di dialog Sidebar serta menyajikan items melalui Drawer navigasi. Search kategori BO
                kembali ke baris utama mulai 576px.
            </x-docs.lead>
            <div class="grid gap-5 lg:grid-cols-2">
                <x-docs.navbar-frame title="FO mobile · 412px" :src="route('previews.navbar', ['identity' => 'authenticated', 'dark' => (int) $docsDark])" :width="412" :height="140" :dark="$docsDark" />
                <x-docs.navbar-frame title="BO mobile · 458px" :src="route('previews.navbar', ['platform' => 'back-office', 'identity' => 'authenticated', 'dark' => (int) $docsDark])" :width="458" :height="140" :dark="$docsDark" />
            </div>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Gunakan <span class="{{ $hl }}">wire:model</span> atau <span class="{{ $hl }}">x-model</span>
                bila shell perlu mengontrol state mobile. Saat kembali ke desktop, Navbar membersihkan dialog
                tanpa mengembalikan fokus ke trigger yang sudah tersembunyi.
            </p>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark Mode">
            <x-docs.lead>
                Gunakan dark-mode untuk menggelapkan Navbar, Search, Dropdown, dan surface mobile. Boundary
                ambient data-theme juga didukung; data-theme langsung pada Navbar menjadi override eksplisit.
            </x-docs.lead>
            <div class="space-y-8">
                <div class="space-y-8 lg:grid lg:grid-cols-2 lg:gap-5 lg:space-y-0">
                    <x-docs.navbar-frame title="FO desktop Dark" :src="route('previews.navbar', ['dark' => 1])" dark />
                    <x-docs.navbar-frame title="BO desktop Dark" :src="route('previews.navbar', ['platform' => 'back-office', 'identity' => 'authenticated', 'dark' => 1])" :width="1184" dark />
                </div>
                <div class="grid gap-5 lg:grid-cols-2">
                    <x-docs.navbar-frame title="FO mobile Dark" :src="route('previews.navbar', ['identity' => 'authenticated', 'dark' => 1])" :width="412" :height="140" dark />
                    <x-docs.navbar-frame title="BO mobile Dark" :src="route('previews.navbar', ['platform' => 'back-office', 'identity' => 'authenticated', 'dark' => 1])" :width="458" :height="140" dark />
                </div>
            </div>
        </x-docs.section>

        <livewire:playground.navbar :dark="$docsDark" />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>Kontrak Navbar dibedakan oleh platform. Tabel kedua merangkum bentuk data yang dipasok consumer.</x-docs.lead>
            <div class="space-y-8">
                <div><h3 class="mb-3 text-heading-4 font-bold text-content">Navbar props</h3><x-docs.props-table :rows="$props" min-width="54rem" /></div>
                <div><h3 class="mb-3 text-heading-4 font-bold text-content">Supporting types</h3><x-docs.props-table :rows="$types" min-width="54rem" /></div>
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
