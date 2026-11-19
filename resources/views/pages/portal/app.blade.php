@php
    use App\Docs\Theme;
@endphp

{{--
    Portal Dashboard penuh layar, dibuka dari tombol di pintu masuknya
    (pages/portal/index). Mode gelapnya mengikuti pilihan yang sama dengan
    docs, dan tombol tema di Sidebar-nya mengubahnya lewat x-docs.app-frame.
    Posisi Sidebar desktop dipulihkan dari cookie agar tidak kembali expanded
    ketika pergantian tema memuat ulang halaman.
--}}
<x-docs.app-frame title="Portal Dashboard" :kembali="route('portal.index')">
    <livewire:portal.dashboard
        :dark-mode="Theme::dark()"
        :ringkas="request()->cookie('ceplok_portal_sidebar_collapsed') === '1'"
    />
</x-docs.app-frame>
