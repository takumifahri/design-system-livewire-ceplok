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

        $this->assertHasClasses($root, 'inline-block');
        $this->assertSame('Menu akun', $root->getAttribute('aria-label'));
        $this->assertSame("ceplokDropdown('start', true)", $root->getAttribute('x-data'));
        $this->assertSame('ceplokClose()', $root->getAttribute('x-on:dropdown-close.stop'));
        $this->assertStringContainsString('detail?.panelId', $root->getAttribute('x-on:dropdown-close.window'));
        // Pemicu dirender sebagai Button kit, lengkap dengan atribut Popover dari server.
        $this->assertHasClasses($trigger, 'border-gray-500 text-gray-500');
        $this->assertSame($panel->getAttribute('id'), $trigger->getAttribute('popovertarget'));
        $this->assertSame('toggle', $trigger->getAttribute('popovertargetaction'));
        $this->assertSame('false', $trigger->getAttribute('aria-expanded'));
        $this->assertFalse($trigger->hasAttribute('aria-haspopup'));

        $this->assertNull($panel->getAttribute('role'));
        $this->assertHasClasses($panel, 'm-0 overflow-y-auto shadow-dropdown py-1 bg-dropdown-surface text-dropdown-content');
        $this->assertSame('/bantuan', $this->find($panel, 'a[data-baris]')->getAttribute('href'));
        $this->assertTrue($this->find($panel, 'button[data-baris]:disabled')->hasAttribute('disabled'));
        $this->assertSame('keluar', $this->find($panel, 'button.text-feedback-error')->getAttribute('wire:click'));
    }

    public function test_pemicu_raw(): void
    {
        $dom = $this->dom('<x-ceplok::dropdown :items="[[\'label\' => \'Satu\']]"><x-slot:trigger raw class="w-full">Buka</x-slot:trigger></x-ceplok::dropdown>');
        $trigger = $this->find($dom, 'body > div > button');

        $this->assertHasClasses($this->find($dom, 'body > div'), 'inline-block');
        $this->assertSame('button', $trigger->getAttribute('type'));
        $this->assertSame('w-full', $trigger->getAttribute('class'));
        $this->assertFalse($trigger->hasAttribute('raw'));
        $this->assertHasClasses($this->find($dom, '[popover]'), 'm-0 overflow-y-auto overscroll-contain');
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

        $this->assertSame('dark', $this->find($dom, 'body > div')->getAttribute('data-theme'));
        $this->assertHasClasses($panel, 'bg-dropdown-surface text-dropdown-content');
        $this->assertSame('Akun', trim($this->find($panel, 'p.uppercase')->textContent));
        $this->assertHasClasses($this->find($panel, 'hr'), 'border-border');
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
        $panel = $this->find($this->dom('<x-ceplok::dropdown content-label="Pengaturan tampilan" content-class="p-4"><x-slot:trigger raw>Buka</x-slot:trigger><p id="isi">Bebas</p></x-ceplok::dropdown>'), '[popover]');

        $this->assertNotNull($panel->querySelector('#isi'));
        $this->assertSame('group', $panel->getAttribute('role'));
        $this->assertSame('Pengaturan tampilan', $panel->getAttribute('aria-label'));
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

    public function test_align_close_on_select_dan_navigasi_mengikuti_kontrak_v2(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::dropdown align="end" :close-on-select="false" :items="[
                ['label' => 'Dokumentasi', 'href' => '/docs', 'target' => '_blank', 'current' => true],
                ['label' => 'Kustom', 'href' => '/custom', 'target' => '_self', 'rel' => 'author', 'current' => 'step'],
            ]"><x-slot:trigger raw>Buka</x-slot:trigger></x-ceplok::dropdown>
            BLADE);
        $root = $this->find($dom, 'body > div');
        [$docs, $custom] = iterator_to_array($dom->querySelectorAll('[popover] a[data-baris]'), false);

        $this->assertSame("ceplokDropdown('end', false)", $root->getAttribute('x-data'));
        $this->assertSame('_blank', $docs->getAttribute('target'));
        $this->assertSame('noopener noreferrer', $docs->getAttribute('rel'));
        $this->assertSame('page', $docs->getAttribute('aria-current'));
        $this->assertSame('author', $custom->getAttribute('rel'));
        $this->assertSame('step', $custom->getAttribute('aria-current'));
        $this->assertHasClasses($docs, 'bg-dropdown-item-current-surface text-content');
    }

    public function test_align_dan_close_on_select_divalidasi(): void
    {
        $this->assertPropError(
            '<x-ceplok::dropdown align="left"><x-slot:trigger raw>X</x-slot:trigger></x-ceplok::dropdown>',
            'Prop `align` pada <x-ceplok::dropdown>',
        );
        $this->assertPropError(
            '<x-ceplok::dropdown close-on-select="maybe"><x-slot:trigger raw>X</x-slot:trigger></x-ceplok::dropdown>',
            'Prop `close-on-select` pada <x-ceplok::dropdown>',
        );
    }
}
