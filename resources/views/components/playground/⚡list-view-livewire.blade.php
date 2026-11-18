<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $selectedId = 'project-brief';

    public int $interactionCount = 0;

    public bool $dark = false;

    /** @var array<string, array{title: string, metadata: array<int, string>, description: string}> */
    private const DOCUMENTS = [
        'project-brief' => [
            'title' => 'Project brief',
            'metadata' => ['PDF', '2.4 MB', 'Updated Oct 10, 2026'],
            'description' => 'Ringkasan ruang lingkup, target, dan jadwal awal proyek.',
        ],
        'meeting-notes' => [
            'title' => 'Meeting notes',
            'metadata' => ['DOCX', '840 KB', 'Updated Oct 09, 2026'],
            'description' => 'Catatan keputusan dan tindak lanjut rapat tim terbaru.',
        ],
    ];

    public function selectDocument(string $id): void
    {
        if (! array_key_exists($id, self::DOCUMENTS)) {
            return;
        }

        $this->selectedId = $id;
        $this->interactionCount++;
    }

    /** @return array{title: string, metadata: array<int, string>, description: string} */
    #[Computed]
    public function selectedDocument(): array
    {
        return self::DOCUMENTS[$this->selectedId];
    }
};
?>

<x-docs.section id="livewire" title="Livewire">
    <x-docs.lead>Klik tombol Open untuk memperbarui dokumen terpilih tanpa memuat ulang halaman.</x-docs.lead>

    <x-docs.stage max-width="max-w-6xl" min-width="52rem" :dark="$dark">
        <div x-data="{ selectedId: @entangle('selectedId'), documents: @js(self::DOCUMENTS) }" class="grid min-h-[360px] gap-6 lg:grid-cols-[minmax(0,514px)_minmax(0,1fr)] lg:items-center">
            <x-ceplok::list-view gap="sm" :dark-mode="$dark">
                @foreach (self::DOCUMENTS as $id => $document)
                    @php($actionWireClick = "selectDocument('{$id}')")
                    <div
                        x-on:click="if ($event.target.closest('button')) selectedId = '{{ $id }}'"
                        x-bind:class="selectedId === '{{ $id }}' ? 'rounded-lg ring-2 ring-primary-500 ring-offset-2' : ''"
                    >
                        <x-ceplok::list-view
                            wire:key="list-view-livewire-{{ $id }}"
                            :title="$document['title']"
                            :metadata="$document['metadata']"
                            :dark-mode="$dark"
                            action-label="Open"
                            :action-wire-click="$actionWireClick"
                        />
                    </div>
                @endforeach
            </x-ceplok::list-view>

            <div @class(['rounded-xl border p-6', $dark ? 'border-gray-700 bg-gray-800' : 'border-border bg-surface'])>
                <p class="ds-eyebrow">Detail dokumen</p>
                <h3 @class(['mt-2 text-heading-3 font-black', $dark ? 'text-white' : 'text-gray-900']) x-text="documents[selectedId].title">{{ $this->selectedDocument['title'] }}</h3>
                <p @class(['mt-3 text-body-sm', $dark ? 'text-gray-300' : 'text-gray-600']) x-text="documents[selectedId].description">{{ $this->selectedDocument['description'] }}</p>
                <p @class(['mt-5 text-sm', $dark ? 'text-gray-400' : 'text-gray-500'])>{{ implode(' · ', $this->selectedDocument['metadata']) }}</p>
            </div>
        </div>
    </x-docs.stage>

    <p class="mt-5 text-body-sm text-gray-600 dark:text-gray-300">
        Dokumen terpilih: <strong class="text-gray-900">{{ $this->selectedDocument['title'] }}</strong>
        <span aria-hidden="true">·</span>
        Interaksi: <strong class="text-gray-900">{{ $interactionCount }}×</strong>
    </p>
</x-docs.section>
