<?php

namespace Tests\Feature\Kit\Components;

use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

class UploadDatepickerTest extends TestCase
{
    public function test_upload_default(): void
    {
        $dom = $this->dom('<x-ceplok::upload label="KTP" helper-text="PDF" name="ktp" accept=".pdf" multiple class="max-w-md" />');
        $input = $this->find($dom, 'input[type="file"]');

        $this->assertSame('ceplokUpload', $this->find($dom, 'body > div')->getAttribute('x-data'));
        $this->assertHasClasses($this->find($dom, 'body > div'), 'w-full max-w-md');
        $this->assertHasClasses($input, 'sr-only');
        $this->assertSame('ktp', $input->getAttribute('name'));
        $this->assertSame('.pdf', $input->getAttribute('accept'));
        // Unggahannya selalu tunggal.
        $this->assertFalse($input->hasAttribute('multiple'));
        $this->assertSame($input->getAttribute('id'), $this->find($dom, 'label')->getAttribute('for'));

        $choose = $this->find($dom, 'button[x-on\:click="ceplokBrowse()"]');

        $this->assertSame('Pilih File', trim($choose->textContent));
        $this->assertSame('Belum ada file yang dipilih', trim($this->find($dom, 'p > span.truncate')->textContent));
        // Tombol hapus dan ukuran berkas baru tampil setelah ada berkas.
        $this->assertTrue($this->find($dom, 'button[x-on\:click="ceplokRemove()"]')->hasAttribute('x-cloak'));
    }

    public function test_upload_attach_dan_dark(): void
    {
        $dom = $this->dom('<x-ceplok::upload type="attach" dark-mode attach-label="Seret ke sini" attach-hint="PDF" aria-label="Lampiran" />');
        $zone = $this->find($dom, 'label[for]');

        $this->assertNotNull($zone->querySelector('input[type="file"]'));
        $this->assertHasClasses($zone, 'h-[230px] border-dashed cursor-pointer border-gray-500 bg-gray-700');
        $this->assertSame('ceplokDrop($event)', $zone->getAttribute('x-on:drop.prevent'));
        $this->assertSame("{ 'border-primary-500 bg-gray-600': ceplokDrag }", html_entity_decode($zone->getAttribute('x-bind:class')));
        $this->assertSame('Seret ke sini', trim($this->find($zone, 'span.mt-2.text-sm')->textContent));
    }

    public function test_upload_disabled(): void
    {
        $dom = $this->dom('<x-ceplok::upload type="attach" disabled aria-label="Lampiran" />');

        $this->assertTrue($this->find($dom, 'input')->hasAttribute('disabled'));
        $this->assertFalse($this->find($dom, 'label[for]')->hasAttribute('x-bind:class'));
        $this->assertHasClasses($this->find($dom, 'label[for]'), 'cursor-not-allowed');
    }

    public function test_upload_default_dark_mengikuti_state_dan_aksen_terbaru(): void
    {
        $dom = $this->dom('<x-ceplok::upload dark-mode application="purple" platform="mobile" aria-label="Lampiran" />');
        $row = $this->find($dom, 'body > div > div.flex');
        [$field, $remove] = $this->childrenOf($row);
        $choose = $this->find($field, 'button[x-on\:click="ceplokBrowse()"]');
        $name = $this->find($field, 'p');

        $this->assertHasClasses($field, 'h-10 border-upload-empty-surface');
        $this->assertStringContainsString('border-gray-700', html_entity_decode($field->getAttribute('x-bind:class')));
        $this->assertHasClasses($choose, 'px-[21px] text-xs border-purple-500 bg-purple-500');
        $this->assertHasClasses($name, 'bg-upload-empty-surface text-gray-400');
        $nameBinding = html_entity_decode($name->getAttribute('x-bind:class'));
        $this->assertStringContainsString('bg-gray-700', $nameBinding);
        $this->assertStringContainsString('text-white', $nameBinding);
        $this->assertHasClasses($remove, 'border-gray-700 text-gray-400');
    }

    public function test_datepicker_single(): void
    {
        $dom = $this->dom('<x-ceplok::datepicker label="Tanggal lahir" name="lahir" value="1945-08-17" min="1900-01-01" class="mt-2" data-uji="1" />');
        $root = $this->find($dom, 'body > div');
        $box = $this->find($dom, 'button[popovertarget]');
        $panel = $this->find($dom, '[popover]');

        $this->assertHasClasses($root, 'w-full max-w-81.25 mt-2');
        $this->assertSame('1', $root->getAttribute('data-uji'));
        $this->assertSame('ceplokModel', $root->getAttribute('x-modelable'));

        // x-data="ceplokDatepicker(JSON.parse('…'))": isi string JavaScript-nya dibaca sebagai JSON.
        preg_match("/JSON\\.parse\\('(.*)'\\)\\)$/", $root->getAttribute('x-data'), $m);
        $config = json_decode(json_decode('"'.$m[1].'"'), true);

        $this->assertSame('1945-08-17', $config['value']);
        $this->assertSame('1900-01-01', $config['min']);
        $this->assertNull($config['max']);
        $this->assertSame('single', $config['type']);

        $this->assertSame('17 Agustus 1945', trim($box->textContent));
        $this->assertSame('toggle', $box->getAttribute('popovertargetaction'));
        $this->assertSame('dialog', $box->getAttribute('aria-haspopup'));
        $this->assertSame($box->getAttribute('id').'-label '.$box->getAttribute('id').'-teks', $box->getAttribute('aria-labelledby'));

        $this->assertSame('dialog', $panel->getAttribute('role'));
        $this->assertHasClasses($panel, 'w-71 bg-white');
        $this->assertSame('1945-08-17', $this->find($dom, 'input[type="hidden"][name="lahir"]')->getAttribute('value'));
        $this->assertCount(1, $panel->querySelectorAll('[role="grid"]'));
        $this->assertSame(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'], array_map(fn ($el) => trim($el->textContent), iterator_to_array($panel->querySelectorAll('[role="columnheader"]'), false)));
    }

    public function test_datepicker_period_menulis_rentang_dan_semua_waktu(): void
    {
        $range = $this->dom("<x-ceplok::datepicker type=\"period\" label=\"Periode\" :value=\"['start' => '2026-08-10', 'end' => '2026-08-17']\" />");

        $this->assertSame('10–17 Agustus 2026', trim($this->find($range, 'button[popovertarget]')->textContent));
        $this->assertCount(5, $range->querySelectorAll('[popover] .grid button, [popover] .grid + div button'));

        $all = $this->dom("<x-ceplok::datepicker type=\"period\" label=\"Periode\" :value=\"['start' => null, 'end' => null]\" />");

        $this->assertSame('Semua Waktu', trim($this->find($all, 'button[popovertarget]')->textContent));
    }

    public function test_datepicker_multiple_dua_kotak_dan_dua_kalender(): void
    {
        $dom = $this->dom("<x-ceplok::datepicker type=\"multiple\" label=\"Masa berlaku\" name=\"masa\" end-placeholder=\"Sampai\" :value=\"['start' => '2026-08-10', 'end' => null]\" />");
        $boxes = iterator_to_array($dom->querySelectorAll('button[popovertarget]'), false);

        $this->assertCount(2, $boxes);
        $this->assertSame('show', $boxes[0]->getAttribute('popovertargetaction'));
        $this->assertSame('mulai10 Agustus 2026', trim($boxes[0]->textContent));
        $this->assertSame('selesaiSampai', trim($boxes[1]->textContent));
        $this->assertCount(2, $dom->querySelectorAll('[role="grid"]'));
        $this->assertSame(['masa[start]' => '2026-08-10', 'masa[end]' => ''], collect(iterator_to_array($dom->querySelectorAll('input[type="hidden"]'), false))->mapWithKeys(fn ($i) => [$i->getAttribute('name') => $i->getAttribute('value')])->all());
    }

    public function test_datepicker_tanpa_label_memakai_aria_label(): void
    {
        $dom = $this->dom('<x-ceplok::datepicker aria-label="Tanggal terbit" />');

        $this->assertSame('Tanggal terbit', trim($this->find($dom, 'span[hidden]')->textContent));
        $this->assertFalse($this->find($dom, 'body > div')->hasAttribute('aria-label'));
    }

    public function test_datepicker_membaca_nilai_awal_wire_model(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public ?string $tanggal = '2026-03-09';

            /** @var array{start: ?string, end: ?string} */
            public array $periode = ['start' => '2026-07-28', 'end' => '2026-08-03'];

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::datepicker id="tanggal" label="Tanggal" wire:model="tanggal" />
                        <x-ceplok::datepicker id="periode" type="period" label="Periode" wire:model.live="periode" />
                    </div>
                    BLADE;
            }
        });

        $dom = $this->parse($component->html());

        $this->assertSame('9 Maret 2026', trim($this->find($dom, '#tanggal')->textContent));
        $this->assertSame('28 Juli – 3 Agustus 2026', trim($this->find($dom, '#periode')->textContent));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::datepicker type="range" />', "Prop `type` pada <x-ceplok::datepicker> harus salah satu dari 'single', 'period', 'multiple'");
        $this->assertPropError('<x-ceplok::upload type="drop" />', 'Prop `type` pada <x-ceplok::upload>');
    }
}
