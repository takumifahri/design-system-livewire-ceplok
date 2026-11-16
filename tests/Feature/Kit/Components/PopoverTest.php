<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class PopoverTest extends TestCase
{
    public function test_popover_sama_dengan_kit_react(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::popover title="Nomor referensi" side="top" data-uji="panel">
                <x-slot:trigger raw aria-label="Info" class="size-9" aria-controls="lain">i</x-slot:trigger>
                Isi popover
            </x-ceplok::popover>
            BLADE);
        $root = $this->find($dom, 'body > div');
        $trigger = $this->find($dom, 'body > div > button');
        $panel = $this->find($root, '[popover]');
        [$arrow, $surface] = $this->childrenOf($panel);
        [$header, $body, $border] = $this->childrenOf($surface);

        $this->assertSame('ceplokPopover', $root->getAttribute('x-data'));
        $this->assertSame('open', $root->getAttribute('x-modelable'));

        $this->assertSame('auto', $panel->getAttribute('popover'));
        $this->assertSame('top', $panel->getAttribute('data-side'));
        $this->assertSame('panel', $panel->getAttribute('data-uji'));
        $this->assertHasClasses($panel, 'fixed inset-auto m-0 w-[255px] shrink-0 overflow-visible border-0 bg-transparent p-0 invisible');
        $this->assertSame('panelStyle()', $panel->getAttribute('x-bind:style'));

        // Trigger mendapat id, aria-controls (ditambah, bukan ditimpa), dan aria-expanded.
        $this->assertSame($panel->getAttribute('id').'-trigger', $trigger->getAttribute('id'));
        $this->assertSame('lain '.$panel->getAttribute('id'), $trigger->getAttribute('aria-controls'));
        $this->assertSame('false', $trigger->getAttribute('aria-expanded'));
        $this->assertSame('Info', $trigger->getAttribute('aria-label'));
        $this->assertSame('toggle($event)', $trigger->getAttribute('x-on:click'));
        $this->assertSame('size-9', $trigger->getAttribute('class'));

        // Panah awal di sisi yang diminta; keempat sisinya diikat ke state Alpine.
        $this->assertSame('true', $arrow->getAttribute('aria-hidden'));
        $this->assertHasClasses($arrow, 'pointer-events-none absolute z-30 size-4 bottom-[-8px] rotate-180');
        $this->assertStringContainsString("side === 'left'", $arrow->getAttribute('x-bind:class'));
        $this->assertHasClasses($this->find($arrow, 'span'), 'absolute size-2.5 rotate-45 border-t border-l border-popover-border bg-popover-surface');

        $this->assertHasClasses($surface, 'relative z-10 overflow-hidden rounded-md bg-popover-surface');
        $this->assertHasClasses($header, 'border-b px-3 py-1.5 text-sm font-semibold border-popover-border bg-popover-header-surface text-popover-header-content');
        $this->assertSame('Nomor referensi', trim($header->textContent));
        $this->assertHasClasses($body, 'px-3 py-2 text-sm font-medium bg-popover-surface text-popover-body-content');
        $this->assertSame('Isi popover', trim($body->textContent));
        $this->assertHasClasses($border, 'pointer-events-none absolute inset-0 z-20 rounded-md border border-popover-border');
    }

    public function test_popover_dark_mode_dan_panah_bawah(): void
    {
        $dom = $this->dom('<x-ceplok::popover title="P" side="bottom" dark-mode open><x-slot:trigger>Buka</x-slot:trigger>Isi</x-ceplok::popover>');
        $root = $this->find($dom, 'body > div');
        $panel = $this->find($root, '[popover]');
        [$arrow, $surface] = $this->childrenOf($panel);

        $this->assertSame('true', $root->getAttribute('data-open'));
        $this->assertSame('dark', $panel->getAttribute('data-theme'));
        $this->assertSame('true', $this->find($dom, 'body > div > button')->getAttribute('aria-expanded'));
        $this->assertHasClasses($arrow, 'top-[-8px]');
        // Panah bawah menyentuh header, jadi warnanya ikut header gelap.
        $this->assertHasClasses($this->find($arrow, 'span'), 'border-popover-border bg-popover-header-surface');
        $this->assertHasClasses($surface, 'bg-popover-surface');
        $this->assertHasClasses($this->childrenOf($surface)[0], 'border-popover-border bg-popover-header-surface text-popover-header-content');
    }

    public function test_popover_meneruskan_boundary_tema_ambient_ke_panel(): void
    {
        $dom = $this->dom('<x-ceplok::popover title="P" data-theme="dark"><x-slot:trigger>Buka</x-slot:trigger>Isi</x-ceplok::popover>');

        $this->assertSame('dark', $this->find($dom, '[popover]')->getAttribute('data-theme'));
    }

    public function test_popover_merangkai_handler_trigger_consumer_dengan_toggle_internal(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::popover title="P">
                <x-slot:trigger raw x-on:click="audit()" aria-expanded="consumer">Buka</x-slot:trigger>
                Isi
            </x-ceplok::popover>
            BLADE);
        $trigger = $this->find($dom, 'button');

        $this->assertSame('audit(); if (!$event.defaultPrevented) toggle($event)', $trigger->getAttribute('x-on:click'));
        $this->assertSame('false', $trigger->getAttribute('aria-expanded'));
        $this->assertSame("open ? 'true' : 'false'", $trigger->getAttribute('x-bind:aria-expanded'));
    }

    public function test_popover_memeriksa_prop(): void
    {
        $this->assertPropError('<x-ceplok::popover title="P" side="tengah"><x-slot:trigger>Buka</x-slot:trigger>Isi</x-ceplok::popover>', 'Prop `side` pada <x-ceplok::popover>');
        $this->assertPropError('<x-ceplok::popover title="P"><x-slot:trigger href="/tujuan">Buka</x-slot:trigger>Isi</x-ceplok::popover>', 'harus dirender sebagai <button>');
    }
}
