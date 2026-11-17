@php
    use App\Docs\Theme;
@endphp

{{--
    Bagian "Dark mode" di halaman komponen, yang selalu menunjukkan mode yang
    sedang tidak dipakai docs — port ModeSection di docs React.

    Di mode terang docs ia memperagakan `dark-mode` dengan slot `lead` milik
    halaman. Di mode gelap docs, ketika semua contoh lain sudah gelap, ia
    bertukar menjadi Light mode. Isinya ditulis halaman: komponen dengan
    `:dark-mode="! $docsDark"` di dalam
    <x-docs.demo :dark="! $docsDark" :light="$docsDark">, dan cuplikan yang
    menulis `dark-mode` hanya di mode terang lewat penanda ⟨!…⟩ (lihat
    App\Docs\Code). Label di daftar isinya ditukar x-docs.page.
--}}
@props([
    // Nama komponen di kalimat Light mode, mis. "Input Field".
    'name',
])

@php
    $docsDark = Theme::dark();
@endphp

<x-docs.section id="dark-mode" :title="$docsDark ? 'Light mode' : 'Dark mode'">
    <x-docs.lead>
        @if ($docsDark)
            Dokumentasi sedang dalam mode gelap, jadi contoh lain di halaman ini memakai
            <span class="text-primary-500 dark:text-primary-400">dark-mode</span>, begitu pula cuplikan kodenya.
            Tanpa prop itu {{ $name }} tampil terang, seperti di bawah ini.
        @else
            {{ $lead }}
        @endif
    </x-docs.lead>
    {{ $slot }}
</x-docs.section>
