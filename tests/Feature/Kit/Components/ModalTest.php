<?php

namespace Tests\Feature\Kit\Components;

use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

class ModalTest extends TestCase
{
    public function test_modal_default_sama_dengan_kit_react(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::modal title="Terms of Service" size="m" class="extra" aria-describedby="isi">
                <x-slot:trigger>Buka</x-slot:trigger>
                <p>Isi</p>
                <x-slot:footer><button x-on:click="close()">OK</button></x-slot:footer>
            </x-ceplok::modal>
            BLADE);
        $root = $this->find($dom, 'body > div');
        $trigger = $this->find($dom, 'body > div > button');
        $dialog = $this->find($root, 'dialog');
        [$header, $body, $footer] = $this->childrenOf($dialog);
        $title = $this->find($header, 'h2');

        $this->assertSame('ceplokModal', $root->getAttribute('x-data'));
        $this->assertSame('open', $root->getAttribute('x-modelable'));
        $this->assertSame('false', $root->getAttribute('data-open'));
        $this->assertHasClasses($root, 'contents');

        // Trigger: Button kit dengan atribut dialog.
        $this->assertSame('dialog', $trigger->getAttribute('aria-haspopup'));
        $this->assertSame('false', $trigger->getAttribute('aria-expanded'));
        $this->assertSame('show()', $trigger->getAttribute('x-on:click'));
        $this->assertHasClasses($trigger, 'bg-primary-700 text-white');

        // Atribut pemakai ke <dialog>; judul menjadi nama aksesibel.
        $this->assertTrue($dialog->hasAttribute('wire:ignore.self'));
        $this->assertSame($title->getAttribute('id'), $dialog->getAttribute('aria-labelledby'));
        $this->assertSame('isi', $dialog->getAttribute('aria-describedby'));
        $this->assertHasClasses($dialog, 'm-auto max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] rounded-lg border shadow-xl backdrop:bg-gray-900/50 open:flex open:flex-col max-w-[640px] bg-surface extra');
        $this->assertSame('true', $dialog->getAttribute('data-close-on-escape'));

        $this->assertHasClasses($header, 'flex shrink-0 items-start justify-between gap-4 px-6 border-b py-5');
        $this->assertHasClasses($title, 'min-w-0 flex-1 font-alt text-lg leading-[1.5] font-semibold text-content');
        $this->assertSame('Terms of Service', trim($title->textContent));
        $close = $this->find($header, 'button');
        $this->assertSame('Tutup modal', $close->getAttribute('aria-label'));
        $this->assertSame('close()', $close->getAttribute('x-on:click'));
        $this->assertHasClasses($close, '-my-2 -mr-2 size-10');

        $this->assertHasClasses($body, 'min-h-0 flex-1 overflow-y-auto overscroll-y-contain font-sans text-body font-medium text-content-muted px-5 py-6');
        $this->assertHasClasses($footer, 'flex shrink-0 flex-wrap items-center gap-3 border-t px-6 py-4');
    }

    public function test_modal_popup_tanpa_judul_dan_divider(): void
    {
        $dom = $this->dom('<x-ceplok::modal variant="popup" aria-label="Konfirmasi" :close-on-escape="false" :close-on-backdrop="false" :show-close-button="false" x-model="buka">Hapus?<x-slot:footer><button>Ya</button></x-slot:footer></x-ceplok::modal>');
        $root = $this->find($dom, 'body > div');
        $dialog = $this->find($root, 'dialog');
        [$header, $body, $footer] = $this->childrenOf($dialog);

        // x-model di pembungkus pemegang x-data, bukan di dialog.
        $this->assertSame('buka', $root->getAttribute('x-model'));
        $this->assertFalse($dialog->hasAttribute('x-model'));
        $this->assertNull($dom->querySelector('body > div > button'));

        $this->assertSame('Konfirmasi', $dialog->getAttribute('aria-label'));
        $this->assertFalse($dialog->hasAttribute('aria-labelledby'));
        $this->assertSame('false', $dialog->getAttribute('data-close-on-escape'));
        $this->assertSame('false', $dialog->getAttribute('data-close-on-backdrop'));
        $this->assertHasClasses($dialog, 'max-w-[416px] bg-[light-dark(var(--color-surface),var(--color-gray-600))]');
        $this->assertHasClasses($header, 'relative h-7 px-5');
        $this->assertNull($header->querySelector('button'));
        $this->assertHasClasses($body, 'px-5 pb-4 pt-5');
        $this->assertHasClasses($footer, 'px-5 pb-5');
        $this->assertLacksClasses($footer, 'border-t');
    }

    public function test_modal_popup_memiliki_icon_actions_dan_tema_seperti_react(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::modal
                variant="popup"
                aria-label="Konfirmasi"
                dark-mode
                x-model="open"
                :actions="[
                    'secondary' => ['label' => 'Batal', 'attributes' => ['x-on:click' => 'close()']],
                    'danger' => ['label' => 'Hapus', 'attributes' => ['wire:click' => 'hapus']],
                ]"
            >Hapus data?</x-ceplok::modal>
            BLADE);
        $dialog = $this->find($dom, 'dialog');
        $body = $this->childrenOf($dialog)[1];
        $footer = $this->childrenOf($dialog)[2];
        $buttons = $footer->querySelectorAll('button');

        $this->assertSame('dark', $dialog->getAttribute('data-theme'));
        $this->assertNotNull($body->querySelector('svg'));
        $this->assertCount(2, $buttons);
        $this->assertSame('Batal', trim($buttons[0]->textContent));
        $this->assertSame('close()', $buttons[0]->getAttribute('x-on:click'));
        $this->assertHasClasses($buttons[0], 'border-gray-50! text-gray-50!');
        $this->assertSame('Hapus', trim($buttons[1]->textContent));
        $this->assertSame('hapus', $buttons[1]->getAttribute('wire:click'));
        $this->assertHasClasses($footer, 'justify-center gap-4');
        $this->assertHasClasses($buttons[1], 'text-white! font-semibold!');
    }

    public function test_modal_icon_dapat_diganti_atau_dihilangkan_dan_actions_tidak_dapat_dicampur_footer(): void
    {
        $custom = $this->dom('<x-ceplok::modal variant="popup" aria-label="Info" icon="ceplok-o-info-circle" x-model="open">Info</x-ceplok::modal>');
        $without = $this->dom('<x-ceplok::modal variant="popup" aria-label="Info" :icon="false" x-model="open">Info</x-ceplok::modal>');

        $this->assertNotNull($this->find($custom, 'dialog')->querySelector('svg'));
        $this->assertNull($this->find($without, 'dialog')->querySelector('[class*="text-feedback-error"]'));
        $this->assertPropError(
            '<x-ceplok::modal title="X" :actions="[\'primary\' => [\'label\' => \'Simpan\']]" x-model="open"><x-slot:footer>Footer</x-slot:footer></x-ceplok::modal>',
            'salah satu `actions` atau slot `footer`',
        );
    }

    public function test_modal_terbuka_dari_properti_livewire(): void
    {
        $html = Livewire::test(new class extends Component
        {
            public bool $open = true;

            public function render(): string
            {
                return '<div><x-ceplok::modal wire:model="open" title="Edit">Isi</x-ceplok::modal></div>';
            }
        })->html();
        $root = $this->find($this->parse($html), '[x-data="ceplokModal"]');

        $this->assertSame('true', $root->getAttribute('data-open'));
        $this->assertSame('open', $root->getAttribute('wire:model'));
    }

    public function test_modal_memeriksa_prop(): void
    {
        $this->assertPropError('<x-ceplok::modal size="xl"><x-slot:trigger>Buka</x-slot:trigger></x-ceplok::modal>', 'Prop `size` pada <x-ceplok::modal>');
        $this->assertPropError('<x-ceplok::modal variant="popup" title="Judul" aria-label="X"><x-slot:trigger>Buka</x-slot:trigger></x-ceplok::modal>', 'tidak memakai `size` dan `title`');
        $this->assertPropError('<x-ceplok::modal title="Tanpa pembuka">Isi</x-ceplok::modal>', 'memerlukan slot `trigger`');
    }
}
