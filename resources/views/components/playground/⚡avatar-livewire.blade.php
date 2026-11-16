<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Avatar: data pengguna aktif berasal
 * dari state server dan prop Avatar ikut diperbarui pada setiap pilihan.
 */
new class extends Component
{
    /** @var list<array{name: string, avatar: ?string}> */
    public array $users = [
        ['name' => 'Putri Handayani', 'avatar' => '/images/avatar-sample.svg'],
        ['name' => 'Rafli Arandhana', 'avatar' => null],
    ];

    public int $selected = 0;

    public function select(int $index): void
    {
        if (array_key_exists($index, $this->users)) {
            $this->selected = $index;
        }
    }
};
?>

<div class="flex flex-col items-start gap-4">
    <div class="flex items-center gap-3">
        <x-ceplok::avatar :src="$users[$selected]['avatar']" :name="$users[$selected]['name']" alt="" />
        <span class="text-sm font-semibold text-gray-900">{{ $users[$selected]['name'] }}</span>
    </div>

    <div class="flex flex-wrap gap-2" role="group" aria-label="Pilih pengguna aktif">
        @foreach ($users as $index => $option)
            <x-ceplok::button
                size="s"
                :variant="$selected === $index ? 'filled' : 'outline'"
                wire:click="select({{ $index }})"
            >
                {{ $option['name'] }}
            </x-ceplok::button>
        @endforeach
    </div>
</div>
