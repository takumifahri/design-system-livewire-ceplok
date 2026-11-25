@php
    $platform = request('platform') === 'back-office' ? 'back-office' : 'front-office';
    $identity = in_array(request('identity'), ['authenticated', 'none'], true) ? request('identity') : 'guest';
    $dark = request()->boolean('dark');
    $searchEnabled = ! request()->has('search') || request()->boolean('search');
    $disclosure = ! request()->has('disclosure') || request()->boolean('disclosure');
    $disabled = request()->boolean('disabled');
    $notification = request()->boolean('notification');
    $icons = ! request()->has('icons') || request()->boolean('icons');
    $count = max(2, min(10, (int) request('items', 4)));
    $brand = [
        'content' => new \Illuminate\Support\HtmlString('<span class="flex h-9 w-[114px] items-center gap-2 text-sm font-black tracking-wide lg:h-[46px] lg:w-36 lg:text-base"><img src="/images/ceplok.svg" alt="" class="size-7 shrink-0 object-contain lg:size-9"><span>CEPLOK</span></span>'),
        'href' => '#',
        'ariaLabel' => 'CEPLOK — Beranda',
    ];
    $items = [
        ['type' => 'destination', 'id' => 'home', 'label' => 'Beranda', 'href' => '#beranda', 'current' => true, 'icon' => $icons ? svg('ceplok-o-home') : null],
        $disclosure
            ? ['type' => 'disclosure', 'id' => 'services', 'label' => 'Layanan Digital', 'children' => [
                ['type' => 'destination', 'id' => 'service-catalog', 'label' => 'Katalog Layanan', 'href' => '#katalog'],
                ['type' => 'destination', 'id' => 'service-status', 'label' => 'Status Permohonan', 'href' => '#status'],
            ]]
            : ['type' => 'destination', 'id' => 'services', 'label' => 'Layanan Digital', 'href' => '#layanan', 'icon' => $icons ? svg('ceplok-o-info-circle') : null],
        ['type' => 'destination', 'id' => 'profile', 'label' => 'Profil Instansi', 'href' => '#profil', 'icon' => $icons ? svg('ceplok-o-home') : null],
        ['type' => 'destination', 'id' => 'information', 'label' => 'Informasi Publik', 'href' => '#informasi', 'disabled' => $disabled, 'icon' => $icons ? svg('ceplok-o-info-circle') : null],
        ['type' => 'disclosure', 'id' => 'regulations', 'label' => 'Regulasi dan Kebijakan', 'children' => [
            ['type' => 'destination', 'id' => 'local-regulations', 'label' => 'Peraturan Daerah', 'href' => '#peraturan-daerah'],
            ['type' => 'destination', 'id' => 'public-policy', 'label' => 'Dokumen Kebijakan Publik', 'href' => '#kebijakan-publik'],
        ]],
        ['type' => 'destination', 'id' => 'complaints', 'label' => 'Pengaduan Masyarakat', 'href' => '#pengaduan'],
        ['type' => 'destination', 'id' => 'transparency', 'label' => 'Transparansi dan Akuntabilitas Penyelenggaraan Pemerintahan', 'href' => '#transparansi'],
        ['type' => 'disclosure', 'id' => 'help', 'label' => 'Pusat Bantuan', 'children' => [
            ['type' => 'destination', 'id' => 'faq', 'label' => 'Pertanyaan yang Sering Diajukan', 'href' => '#pertanyaan'],
            ['type' => 'destination', 'id' => 'contact', 'label' => 'Hubungi Petugas Layanan', 'href' => '#hubungi'],
        ]],
        ['type' => 'destination', 'id' => 'statistics', 'label' => 'Statistik dan Data Pelayanan Publik Daerah', 'href' => '#statistik'],
        ['type' => 'destination', 'id' => 'integrated-information', 'label' => 'Informasi dan Dokumentasi Pelayanan Publik Terpadu Nasional', 'href' => '#informasi-terpadu'],
    ];
    $items = array_slice($items, 0, $count);
    $search = $searchEnabled ? array_filter([
        'placeholder' => 'Cari dokumentasi ...',
        'categories' => $platform === 'back-office' ? [['value' => 'all', 'label' => 'Semua']] : null,
    ]) : null;
    $guestActions = $platform === 'front-office' && $identity === 'guest'
        ? ['signIn' => ['label' => 'Masuk', 'href' => '#masuk'], 'signUp' => ['label' => 'Daftar', 'href' => '#daftar']]
        : null;
    $account = $platform === 'front-office' && $identity === 'authenticated'
        ? ['name' => 'Ayu Lestari', 'initials' => 'AL', 'items' => [
            ['type' => 'destination', 'id' => 'account-profile', 'label' => 'Profil', 'href' => '#profil-akun'],
            ['type' => 'action', 'id' => 'account-logout', 'label' => 'Keluar', 'tone' => 'danger', 'attributes' => ['x-on:click' => '']],
        ]]
        : null;
    $sidebarUser = ['name' => 'Ayu Lestari', 'initials' => 'AL'];
@endphp

<!DOCTYPE html>
<html lang="id" @if ($dark) data-docs-theme="dark" @endif>
    <x-docs.head title="Navbar preview" />
    <body class="m-0 bg-gray-50 dark:bg-gray-900">
        <x-ceplok::navbar
            :platform="$platform"
            :brand="$brand"
            :items="$items"
            :search="$search"
            :account="$account"
            :guest-actions="$guestActions"
            :notification-trigger="$notification && ($platform === 'back-office' || $account) ? ['ariaLabel' => 'Buka notifikasi'] : null"
            :sidebar-items="$platform === 'back-office' ? $items : []"
            :sidebar-user="$platform === 'back-office' ? $sidebarUser : null"
            :dark-mode="$dark"
        />
        @livewireScriptConfig
    </body>
</html>
