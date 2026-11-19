{{--
    Bagian "Dark mode" di halaman komponen — port ModeSection di docs React,
    dengan perilaku `stayDark`-nya: bagian ini tetap memperagakan `dark-mode`
    di kedua mode docs dan tidak bertukar menjadi Light mode. Pengantarnya
    dari slot `lead`. Isinya ditulis halaman: komponen dengan `dark-mode` di
    dalam <x-docs.demo dark>, dan cuplikan yang selalu menulis `⟦dark-mode⟧`.
--}}
<x-docs.section id="dark-mode" title="Dark mode">
    <x-docs.lead>{{ $lead }}</x-docs.lead>
    {{ $slot }}
</x-docs.section>
