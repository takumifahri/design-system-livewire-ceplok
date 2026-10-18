<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class DropdownTest extends TestCase
{
    private const ITEMS = "[['label' => 'Profil'], ['label' => 'Bantuan', 'href' => '/bantuan'], ['label' => 'Arsip', 'disabled' => true], ['label' => 'Keluar', 'tone' => 'danger', 'attributes' => ['wire:click' => 'keluar']]]";

    public function test_menu_aksi_dengan_pemicu_button_kit(): void
    {
        $dom = $this->dom('<x-ceplok::dropdown :items="'.self::ITEMS.'" aria-label="Menu akun"><x-slot:trigger variant="outline" theme="gray">Akun</x-slot:trigger></x-ceplok::dropdown>');
        $root = $this->find($dom, 'body > div');
        $trigger = $this->find($dom, 'body > div > button');
        $panel = $this->find($dom, 'body > div > [popover="auto"]');

        $this->assertHasClasses($root, 'relative inline-block');
        $this->assertSame('Menu akun', $root->getAttribute('aria-label'));
        $this->assertSame('ceplokDropdown(false)', $root->getAttribute('x-data'));
        // Pemicu dirender sebagai Button kit, lengkap dengan atribut Popover dari server.
        $this->assertHasClasses($trigger, 'border-gray-500 text-gray-500');
        $this->assertSame($panel->getAttribute('id'), $trigger->getAttribute('popovertarget'));
        $this->assertSame('toggle', $trigger->getAttribute('popovertargetaction'));
        $this->assertSame('false', $trigger->getAttribute('aria-expanded'));
        $this->assertFalse($trigger->hasAttribute('aria-haspopup'));

        $this->assertNull($panel->getAttribute('role'));
        $this->assertHasClasses($panel, 'mt-2 w-max [position-area:bottom_center] py-1 bg-surface');
        $this->assertSame('/bantuan', $this->find($panel, 'a[data-baris]')->getAttribute('href'));
        $this->assertTrue($this->find($panel, 'button[data-baris]:disabled')->hasAttribute('disabled'));
        $this->assertSame('keluar', $this->find($panel, 'button.text-red-600')->getAttribute('wire:click'));
    }

    public function test_pemicu_raw_dan_attached(): void
    {
        $dom = $this->dom('<x-ceplok::dropdown attached :items="[[\'label\' => \'Satu\']]"><x-slot:trigger raw class="w-full">Buka</x-slot:trigger></x-ceplok::dropdown>');
        $trigger = $this->find($dom, 'body > div > button');

        $this->assertHasClasses($this->find($dom, 'body > div'), 'contents');
        $this->assertSame('button', $trigger->getAttribute('type'));
        $this->assertSame('w-full', $trigger->getAttribute('class'));
        $this->assertFalse($trigger->hasAttribute('raw'));
        $this->assertHasClasses($this->find($dom, '[popover]'), 'm-0 max-h-72 overflow-y-auto');
    }

    public function test_kelompok_dengan_judul_pemisah_ikon_dan_keterangan(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::dropdown dark-mode :groups="[
                ['id' => 'akun', 'label' => 'Akun', 'items' => [['label' => 'Profil', 'icon' => 'ceplok-o-user', 'description' => 'Data diri']]],
                ['id' => 'lain', 'separator' => true, 'items' => [['label' => 'Keluar']]],
            ]"><x-slot:trigger raw>Menu</x-slot:trigger></x-ceplok::dropdown>
            BLADE);
        $panel = $this->find($dom, '[popover]');

        $this->assertHasClasses($panel, 'bg-gray-800 text-gray-300');
        $this->assertSame('Akun', trim($this->find($panel, 'p.uppercase')->textContent));
        $this->assertHasClasses($this->find($panel, 'hr'), 'border-gray-700');
        $this->assertNotNull($panel->querySelector('[data-baris] span[aria-hidden="true"] > svg'));
        $this->assertSame('Data diri', trim($this->find($panel, '[data-keterangan]')->textContent));
    }

    public function test_item_selected_menjadikan_panel_daftar_pilihan(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::dropdown content-label="Urutkan" :items="[
                ['label' => 'Terbaru', 'selected' => true, 'selected-when' => 'urut === 1', 'attributes' => ['x-on:click' => 'urut = 1']],
                ['label' => 'Terlama', 'selected' => false, 'disabled' => true, 'attributes' => ['x-on:click' => 'urut = 2']],
            ]"><x-slot:trigger raw>Urutkan</x-slot:trigger></x-ceplok::dropdown>
            BLADE);
        $panel = $this->find($dom, '[popover]');
        $trigger = $this->find($dom, 'body > div > button');
        [$first, $second] = iterator_to_array($panel->querySelectorAll('[role="option"]'), false);

        $this->assertSame('listbox', $panel->getAttribute('role'));
        $this->assertSame('Urutkan', $panel->getAttribute('aria-label'));
        $this->assertSame('listbox', $trigger->getAttribute('aria-haspopup'));
        $this->assertSame($panel->getAttribute('id'), $trigger->getAttribute('aria-controls'));
        $this->assertSame('true', $first->getAttribute('aria-selected'));
        $this->assertSame("(urut === 1) ? 'true' : 'false'", $first->getAttribute('x-bind:aria-selected'));
        $this->assertSame('urut = 1', $first->getAttribute('x-on:click'));
        // Pilihan yang dimatikan tidak membawa aksinya.
        $this->assertSame('true', $second->getAttribute('aria-disabled'));
        $this->assertFalse($second->hasAttribute('x-on:click'));
    }

    public function test_isi_bebas_tanpa_items(): void
    {
        $panel = $this->find($this->dom('<x-ceplok::dropdown content-class="p-4"><x-slot:trigger raw>Buka</x-slot:trigger><p id="isi">Bebas</p></x-ceplok::dropdown>'), '[popover]');

        $this->assertNotNull($panel->querySelector('#isi'));
        $this->assertHasClasses($panel, 'p-4');
        $this->assertLacksClasses($panel, 'py-1');
    }

    public function test_aksi_dan_pilihan_tidak_boleh_dicampur(): void
    {
        $this->assertPropError(
            "<x-ceplok::dropdown :items=\"[['label' => 'A', 'selected' => true], ['label' => 'B']]\"><x-slot:trigger raw>X</x-slot:trigger></x-ceplok::dropdown>",
            'tidak bisa mencampur baris aksi dan baris pilihan',
        );
        $this->assertPropError(
            "<x-ceplok::dropdown :items=\"[['label' => 'A', 'tone' => 'warning']]\"><x-slot:trigger raw>X</x-slot:trigger></x-ceplok::dropdown>",
            'Prop `items.tone` pada <x-ceplok::dropdown>',
        );
    }
}
