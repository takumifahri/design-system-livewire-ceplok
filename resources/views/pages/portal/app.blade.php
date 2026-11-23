@php
    use App\Docs\Theme;
@endphp

{{--
    Portal Dashboard penuh layar, dibuka dari tombol di pintu masuknya
    (pages/portal/index). Mode gelapnya mengikuti pilihan yang sama dengan
    docs, dan tombol tema di header-nya mengubahnya lewat x-docs.app-frame.
--}}
<x-docs.app-frame title="Portal Dashboard" :kembali="route('portal.index')">
    <livewire:portal.dashboard :dark-mode="Theme::dark()" />
</x-docs.app-frame>
