<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public bool $dark = false;
    public string $platform = 'front-office';
    public string $identity = 'guest';
    public int $menuCount = 4;
    public int $viewportWidth = 1440;
    public int $viewportHeight = 94;
    public bool $disclosure = true;
    public bool $search = true;
    public bool $disabled = false;
    public bool $icons = true;
    public bool $notification = false;

    public function updatedIdentity(string $identity): void
    {
        if ($identity !== 'authenticated') $this->notification = false;
    }

    public function updatedPlatform(string $platform): void
    {
        if ($platform === 'front-office' && $this->identity !== 'authenticated') $this->notification = false;
    }

    /** @return list<array<string, mixed>> */
    #[Computed]
    public function items(): array
    {
        $items = [
            ['type' => 'destination', 'id' => 'home', 'label' => 'Beranda', 'href' => '#beranda', 'current' => true],
            ['type' => 'destination', 'id' => 'profile', 'label' => 'Profil Instansi', 'href' => '#profil'],
            $this->disclosure
                ? ['type' => 'disclosure', 'id' => 'services', 'label' => 'Layanan Digital', 'children' => [
                    ['type' => 'destination', 'id' => 'service-catalog', 'label' => 'Katalog Layanan', 'href' => '#katalog'],
                    ['type' => 'destination', 'id' => 'service-status', 'label' => 'Status Permohonan', 'href' => '#status'],
                ]]
                : ['type' => 'destination', 'id' => 'services', 'label' => 'Layanan Digital', 'href' => '#layanan'],
            ['type' => 'destination', 'id' => 'information', 'label' => 'Informasi Publik', 'href' => '#informasi', 'disabled' => $this->disabled],
            ['type' => 'destination', 'id' => 'complaints', 'label' => 'Pengaduan Masyarakat', 'href' => '#pengaduan'],
            ['type' => 'destination', 'id' => 'transparency', 'label' => 'Transparansi dan Akuntabilitas', 'href' => '#transparansi'],
            ['type' => 'disclosure', 'id' => 'regulations', 'label' => 'Regulasi dan Kebijakan', 'children' => [
                ['type' => 'destination', 'id' => 'local-regulations', 'label' => 'Peraturan Daerah', 'href' => '#peraturan-daerah'],
                ['type' => 'destination', 'id' => 'public-policy', 'label' => 'Dokumen Kebijakan Publik', 'href' => '#kebijakan-publik'],
            ]],
            ['type' => 'destination', 'id' => 'help', 'label' => 'Pusat Bantuan', 'href' => '#bantuan'],
            ['type' => 'destination', 'id' => 'statistics', 'label' => 'Statistik Pelayanan Publik', 'href' => '#statistik'],
            ['type' => 'destination', 'id' => 'integrated-information', 'label' => 'Informasi Pelayanan Publik Terpadu', 'href' => '#informasi-terpadu'],
        ];

        return array_slice($items, 0, $this->menuCount);
    }

    #[Computed]
    public function usage(): string
    {
        $lines = [
            '<x-ceplok::navbar',
            '    platform="'.$this->platform.'"',
            $this->dark ? '    dark-mode' : null,
            '    :brand="$brand"',
            '    :items="$items"',
            $this->search ? '    :search="$search"' : null,
            $this->platform === 'front-office' && $this->identity === 'guest' ? '    :guest-actions="$guestActions"' : null,
            $this->platform === 'front-office' && $this->identity === 'authenticated' ? '    :account="$account"' : null,
            $this->platform === 'back-office' ? '    :sidebar-items="$sidebarItems"' : null,
            $this->platform === 'back-office' ? '    :sidebar-user="$sidebarUser"' : null,
            $this->notification ? '    :notification-trigger="$notificationTrigger"' : null,
            '/>',
        ];

        return implode("\n", array_filter($lines, fn (?string $line) => $line !== null));
    }

    #[Computed]
    public function previewUrl(): string
    {
        return route('previews.navbar', [
            'platform' => $this->platform,
            'identity' => $this->identity,
            'items' => $this->menuCount,
            'disclosure' => (int) $this->disclosure,
            'search' => (int) $this->search,
            'disabled' => (int) $this->disabled,
            'notification' => (int) $this->notification,
            'icons' => (int) $this->icons,
            'dark' => (int) $this->dark,
        ]);
    }
};
?>

@php
    $brand = ['content' => new \Illuminate\Support\HtmlString('<span class="text-lg font-black">Ceplok</span>'), 'href' => '#', 'ariaLabel' => 'Ceplok'];
    $searchConfig = $search
        ? array_filter([
            'placeholder' => 'Cari layanan',
            'categories' => $platform === 'back-office' ? [['value' => 'all', 'label' => 'Semua']] : null,
        ])
        : null;
    $guestActions = ['signIn' => ['label' => 'Masuk', 'href' => '#masuk'], 'signUp' => ['label' => 'Daftar', 'href' => '#daftar']];
    $account = ['name' => 'Ayu Lestari', 'initials' => 'AL', 'items' => [
        ['type' => 'destination', 'id' => 'account-profile', 'label' => 'Profil', 'href' => '#profil-akun'],
        ['type' => 'action', 'id' => 'account-logout', 'label' => 'Keluar', 'tone' => 'danger', 'attributes' => ['x-on:click' => '']],
    ]];
    $sidebarItems = $this->items;
    $sidebarUser = ['name' => 'Ayu Lestari', 'initials' => 'AL'];
    $notificationTrigger = $notification ? ['ariaLabel' => 'Buka notifikasi'] : null;
@endphp

<div>
<x-docs.section id="playground" title="Playground">
    <x-docs.lead>
        Playground merender Navbar production. Tema awal mengikuti dokumentasi; kontrol lain hanya
        mengubah data consumer dan komposisi yang diberikan ke Navbar.
    </x-docs.lead>
    <x-docs.navbar-frame
        wire:key="navbar-preview-{{ $platform }}-{{ $identity }}-{{ $menuCount }}-{{ $viewportWidth }}-{{ $viewportHeight }}-{{ (int) $disclosure }}-{{ (int) $search }}-{{ (int) $disabled }}-{{ (int) $icons }}-{{ (int) $notification }}-{{ (int) $dark }}"
        title="Preview"
        :src="$this->previewUrl"
        :width="$viewportWidth"
        :height="$viewportHeight"
        :dark="$dark"
    />

    <x-docs.controls>
        <x-docs.control label="Theme"><x-docs.segmented label="Theme" model="dark" :value="$dark" :options="[['value' => false, 'label' => 'Light'], ['value' => true, 'label' => 'Dark']]" /></x-docs.control>
        <x-docs.control label="Platform"><x-docs.segmented label="Platform" model="platform" :value="$platform" :options="[['value' => 'front-office', 'label' => 'Front Office'], ['value' => 'back-office', 'label' => 'Back Office']]" /></x-docs.control>
        @if ($platform === 'front-office')
            <x-docs.control label="Identity"><x-docs.segmented label="Identity" model="identity" :value="$identity" :options="[['value' => 'guest', 'label' => 'Guest'], ['value' => 'authenticated', 'label' => 'Authenticated'], ['value' => 'none', 'label' => 'No actions']]" /></x-docs.control>
        @endif
        <x-docs.control label="Viewport width"><x-docs.segmented label="Viewport width" model="viewportWidth" :value="$viewportWidth" :options="[['value' => 375, 'label' => '375px'], ['value' => 412, 'label' => '412px'], ['value' => 768, 'label' => '768px'], ['value' => 1024, 'label' => '1024px'], ['value' => 1440, 'label' => '1440px']]" /></x-docs.control>
        <x-docs.control label="Viewport height"><x-docs.segmented label="Viewport height" model="viewportHeight" :value="$viewportHeight" :options="[['value' => 94, 'label' => '94px'], ['value' => 140, 'label' => '140px'], ['value' => 320, 'label' => '320px']]" /></x-docs.control>
        <x-docs.control label="Navigation count"><x-docs.segmented label="Navigation count" model="menuCount" :value="$menuCount" :options="[['value' => 2, 'label' => '2 items'], ['value' => 4, 'label' => '4 items'], ['value' => 6, 'label' => '6 items'], ['value' => 8, 'label' => '8 items'], ['value' => 10, 'label' => '10 items']]" /></x-docs.control>
        <x-docs.control label="Disclosure"><x-docs.segmented label="Disclosure" model="disclosure" :value="$disclosure" :options="[['value' => true, 'label' => 'On'], ['value' => false, 'label' => 'Off']]" /></x-docs.control>
        <x-docs.control label="Search"><x-docs.segmented label="Search" model="search" :value="$search" :options="[['value' => true, 'label' => 'On'], ['value' => false, 'label' => 'Off']]" /></x-docs.control>
        <x-docs.control label="Disabled item"><x-docs.segmented label="Disabled item" model="disabled" :value="$disabled" :options="[['value' => true, 'label' => 'On'], ['value' => false, 'label' => 'Off']]" /></x-docs.control>
        <x-docs.control label="Icons"><x-docs.segmented label="Icons" model="icons" :value="$icons" :options="[['value' => true, 'label' => 'On'], ['value' => false, 'label' => 'Off']]" /></x-docs.control>
        @if ($platform === 'back-office' || $identity === 'authenticated')
            <x-docs.control label="Notification"><x-docs.segmented label="Notification" model="notification" :value="$notification" :options="[['value' => true, 'label' => 'On'], ['value' => false, 'label' => 'Off']]" /></x-docs.control>
        @endif
    </x-docs.controls>
</x-docs.section>

<x-docs.section id="penggunaan" title="Penggunaan">
    <x-docs.lead>Blok ini mengikuti kontrol Playground. Prop yang masih memakai nilai bawaan sengaja tidak ditulis.</x-docs.lead>
    <x-docs.code flush>{{ $this->usage }}</x-docs.code>
</x-docs.section>
</div>
