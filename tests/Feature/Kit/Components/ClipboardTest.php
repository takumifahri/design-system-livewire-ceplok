<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class ClipboardTest extends TestCase
{
    public function test_default_merender_nilai_label_helper_dan_aksi(): void
    {
        $root = $this->find($this->dom('<x-ceplok::clipboard value="INV-2026-001" label="Nomor referensi" helper-text="Gunakan nomor ini." />'), 'body > div');
        $input = $this->find($root, 'input');
        $label = $this->find($root, 'label');

        $this->assertSame('ceplokClipboard', $root->getAttribute('x-data'));
        $this->assertSame('INV-2026-001', $root->getAttribute('data-value'));
        $this->assertSame('INV-2026-001', $input->getAttribute('value'));
        $this->assertSame('input', $input->getAttribute('x-ref'));
        $this->assertTrue($input->hasAttribute('readonly'));
        $this->assertSame($input->getAttribute('id'), $label->getAttribute('for'));
        $this->assertSame($input->getAttribute('id').'-helper', $input->getAttribute('aria-describedby'));
        $this->assertSame('Copy', trim($this->find($root, 'button')->textContent));
        $this->assertHasClasses($input, 'h-10.5 px-3 text-sm leading-[1.25] text-clipboard-value');
    }

    public function test_segmented_merender_prefix_dan_tombol_ikon(): void
    {
        $root = $this->find($this->dom('<x-ceplok::clipboard value="https://example.test" variant="segmented" prefix="URL" aria-label="Alamat situs" />'), 'div');
        $input = $this->find($root, 'input');

        $this->assertSame('URL', trim($this->find($root, 'span.bg-clipboard-prefix-surface')->textContent));
        $this->assertSame('Alamat situs', $input->getAttribute('aria-label'));
        $this->assertHasClasses($input, 'h-10 px-3 text-clipboard-segmented-value');
        $this->assertNotNull($root->querySelector('button[aria-label="Copy to clipboard"] svg'));
    }

    public function test_mobile_disabled_dan_dark_mode(): void
    {
        $root = $this->find($this->dom('<x-ceplok::clipboard value="ABC" aria-label="Kode" platform="mobile" disabled dark-mode />'), 'div');
        $input = $this->find($root, 'input');
        $button = $this->find($root, 'button');

        $this->assertSame('dark', $root->getAttribute('data-theme'));
        $this->assertSame('true', $root->getAttribute('data-disabled'));
        $this->assertTrue($input->hasAttribute('disabled'));
        $this->assertTrue($button->hasAttribute('disabled'));
        $this->assertHasClasses($input, 'h-9.75 px-2.5 text-xs');
    }

    public function test_event_listener_dan_class_dipasang_di_pembungkus(): void
    {
        $root = $this->find($this->dom('<x-ceplok::clipboard value="ABC" aria-label="Kode" class="max-w-sm" wire:copy-success="copied" />'), 'div');

        $this->assertHasClasses($root, 'w-full max-w-sm');
        $this->assertSame('copied', $root->getAttribute('wire:copy-success'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::clipboard label="Nomor referensi" />', 'Prop `value` pada <x-ceplok::clipboard> wajib diisi.');
        $this->assertPropError('<x-ceplok::clipboard value="ABC" />', '<x-ceplok::clipboard> memerlukan `label`, `aria-label`, atau `aria-labelledby` yang tidak kosong.');
        $this->assertPropError('<x-ceplok::clipboard value="ABC" label="   " />', '<x-ceplok::clipboard> memerlukan `label`, `aria-label`, atau `aria-labelledby` yang tidak kosong.');
        $this->assertPropError('<x-ceplok::clipboard value="ABC" aria-label="Kode" prefix="ID" />', 'Prop `prefix` pada <x-ceplok::clipboard> hanya dapat dipakai dengan variant="segmented".');
        $this->assertPropError('<x-ceplok::clipboard value="ABC" aria-label="Kode" variant="icon" />', "Prop `variant` pada <x-ceplok::clipboard> harus salah satu dari 'default', 'segmented'; diberikan 'icon'.");
        $this->assertPropError('<x-ceplok::clipboard value="ABC" aria-label="Kode" platform="tablet" />', "Prop `platform` pada <x-ceplok::clipboard> harus salah satu dari 'default', 'mobile'; diberikan 'tablet'.");
    }
}
