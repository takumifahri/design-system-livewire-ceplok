@props([
    'id' => 'portal-theme-toggle',
    'darkMode' => false,
    'collapsed' => false,
])

@php
    $title = $darkMode ? 'Matikan mode gelap' : 'Nyalakan mode gelap';
    $toggleExpression = "\$dispatch('dark-mode-change', { darkMode: ".($darkMode ? 'false' : 'true').' })';
@endphp

{{-- Tinggi tetap menjaga separator footer berada di posisi yang sama saat Sidebar berubah ukuran. --}}
<div class="flex h-20 items-center p-3">
    {{-- Expanded: pengaturan penuh di footer, terpisah dari item navigasi. --}}
    <div
        data-portal-theme-toggle="expanded"
        x-show="!collapsed"
        @if ($collapsed) style="display: none;" @endif
        class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-content transition-colors hover:bg-surface-subtle"
    >
        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-surface-subtle text-content-muted" aria-hidden="true">
            @if ($darkMode)
                <x-ceplok-o-sun class="size-5" />
            @else
                <x-ceplok-o-moon class="size-5" />
            @endif
        </span>
        <label for="{{ $id }}" class="min-w-0 flex-1 cursor-pointer text-body-sm font-medium">Mode gelap</label>
        <x-ceplok::toggle
            :id="$id"
            :checked="$darkMode"
            :dark-mode="$darkMode"
            aria-label="Mode gelap"
            :title="$title"
            x-on:change="$dispatch('dark-mode-change', { darkMode: $event.target.checked })"
        />
    </div>

    {{-- Collapsed: icon-only tetap mudah dicapai dan dijelaskan lewat tooltip. --}}
    <x-ceplok::button
        icon-only
        variant="outline"
        theme="gray"
        size="s"
        :dark-mode="$darkMode"
        role="switch"
        aria-label="Mode gelap"
        aria-checked="{{ $darkMode ? 'true' : 'false' }}"
        title="{{ $title }}"
        data-portal-theme-toggle="collapsed"
        x-show="collapsed"
        :style="$collapsed ? null : 'display: none;'"
        x-on:click="{{ $toggleExpression }}"
        class="mx-auto"
    >
        @if ($darkMode)
            <x-ceplok-o-sun class="size-5" aria-hidden="true" />
        @else
            <x-ceplok-o-moon class="size-5" aria-hidden="true" />
        @endif
    </x-ceplok::button>
</div>
