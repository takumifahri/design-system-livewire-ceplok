<?php

namespace Tests\Feature\Docs;

use Dom\HTMLDocument;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Playground dan contoh Livewire halaman Form dan Dropdown. Daftar lengkapnya
 * diperiksa PlaygroundTest; di sini perilaku tiap Playground.
 */
class FormPlaygroundTest extends TestCase
{
    public function test_input_field_password_tanpa_inactive_dan_tombol_hapus(): void
    {
        $component = Livewire::test('playground.input-field')->set('state', 'inactive')->set('kind', 'password');
        $dom = $this->domOf($component);
        $input = $this->find($dom, '#playground input');

        // Password tidak memakai inactive: field tampil default, pilihannya dimatikan.
        $this->assertSame('password', $input->getAttribute('type'));
        $this->assertFalse($input->hasAttribute('disabled'));
        $this->assertSame('Default', trim($this->find($dom, '[aria-label="Pilih state"] button[aria-pressed="true"]')->textContent));
        $this->assertTrue($this->find($dom, '[aria-label="Tampilkan tombol hapus"] button')->hasAttribute('disabled'));

        $code = $this->code($dom);

        $this->assertStringContainsString('type="password"', $code);
        $this->assertStringContainsString('wire:model="sandi"', $code);
        $this->assertStringNotContainsString('clearable', $code);
        $this->assertStringNotContainsString('state=', $code);
    }

    public function test_input_field_validasi_memetakan_error_ke_state_failed(): void
    {
        $component = Livewire::test('playground.input-field-validate')->call('simpan')->assertHasErrors('nama');
        $input = $this->find($this->domOf($component), 'input');

        $this->assertSame('true', $input->getAttribute('aria-invalid'));
        $component->assertSeeText('Nama lengkap wajib diisi.');

        $component->set('nama', 'Budi')->call('simpan')->assertHasNoErrors()->assertSet('tersimpan', 'Budi')->assertSeeText('Tersimpan: Budi');
    }

    public function test_floating_label_naik_saat_nilainya_diisi_server(): void
    {
        $component = Livewire::test('playground.floating-label-fill');

        $this->assertHasClasses($this->find($this->domOf($component), 'label'), 'top-1/2');

        $component->set('kota', 'Bandung');
        $dom = $this->domOf($component);

        $this->assertSame('Bandung', $this->find($dom, 'input')->getAttribute('value'));
        $this->assertHasClasses($this->find($dom, 'label'), 'top-0');
        // x-data tetap sama antar-render, supaya fokus yang sedang berjalan tidak ikut diulang.
        $this->assertSame('ceplokFloatingLabel', $this->find($dom, 'div[x-data]')->getAttribute('x-data'));
    }

    public function test_text_area_penghitung_dan_tombol_kirim(): void
    {
        $component = Livewire::test('playground.text-area')->set('hint', 'counter')->set('value', 'Halo');
        $dom = $this->domOf($component);

        $this->assertSame('4/500', trim($this->find($dom, '#playground label + span')->textContent));
        $this->assertStringContainsString('wire:model.live="pesan"', $this->code($dom));

        $dom = $this->domOf($component->set('type', 'default'));

        $this->assertTrue($this->find($dom, '[aria-label="Tampilkan tombol kirim"] button')->hasAttribute('disabled'));
        $this->assertStringNotContainsString('submit-label', $this->code($dom));
    }

    public function test_text_area_kirim_dan_alat_toolbar(): void
    {
        Livewire::test('playground.text-area-send')
            ->call('kirim')->assertHasErrors('pesan')
            ->set('pesan', 'Pesan pertama')->call('kirim')
            ->assertSet('terkirim', ['Pesan pertama'])->assertSet('pesan', '')
            ->call('pakaiAlat', 'emoji')->assertSeeText('Alat dipilih: emoji');
    }

    public function test_select_menampilkan_pilihan_dan_kode(): void
    {
        $dom = $this->domOf(Livewire::test('playground.select')->set('value', 'jateng')->set('state', 'inactive'));
        $trigger = $this->find($dom, '#playground button[popovertarget]');

        $this->assertSame('Jawa Tengah', trim($trigger->textContent));
        $this->assertTrue($trigger->hasAttribute('disabled'));
        $this->assertStringContainsString('state="inactive"', $this->code($dom));
    }

    public function test_select_bergantung_mengosongkan_kota_saat_provinsi_berganti(): void
    {
        $component = Livewire::test('playground.select-region');

        $this->assertTrue($this->find($this->domOf($component), 'div.grid > div:nth-child(2) button[popovertarget]')->hasAttribute('disabled'));

        $component->set('provinsi', 'jabar')->set('kota', 'bogor');
        $options = array_map(fn ($o) => $o->getAttribute('value'), iterator_to_array($this->domOf($component)->querySelectorAll('select[wire\:model="kota"] option'), false));

        $this->assertSame(['', 'bandung', 'bogor', 'cirebon'], $options);

        $component->set('provinsi', 'jatim')->assertSet('kota', '');
        $kota = $this->find($this->domOf($component), 'select[wire\:model="kota"]');

        // Opsi dirender dengan wire:key supaya morph memasangnya sebagai elemen baru.
        $this->assertSame('surabaya', $this->find($kota, 'option[value="surabaya"]')->getAttribute('wire:key'));
    }

    public function test_search_menampilkan_pencarian_terakhir(): void
    {
        $component = Livewire::test('playground.search')->call('cari', '', '')->assertSeeText('(kosong)');

        $component->set('category', true)->call('cari', 'kominfo', 'organisasi')->assertSet('terakhir', 'kominfo · organisasi');

        $code = $this->code($this->domOf($component));

        $this->assertStringContainsString(':categories="$kategori"', $code);
        $this->assertStringContainsString("'wire:model' => 'kategori'", $code);
        $this->assertStringNotContainsString('platform=', $code);
    }

    public function test_search_auto_menyaring_daftar(): void
    {
        Livewire::test('playground.search-live')
            ->call('cari', 'izin')
            ->assertSeeText('3 layanan untuk “izin”')
            ->call('cari', 'tidak ada')
            ->assertSeeText('Tidak ada layanan yang cocok.');
    }

    public function test_datepicker_menulis_nilai_properti(): void
    {
        $component = Livewire::test('playground.datepicker')->set('type', 'period')->set('rentang', ['start' => '2026-08-10', 'end' => null]);
        $dom = $this->domOf($component);

        $this->assertStringContainsString("['start' => '2026-08-10', 'end' => null]", $this->find($dom, '#playground p.mt-4')->textContent);
        $this->assertStringContainsString('public ?array $periode = null;', $this->code($dom));

        // Diikat wire:model, jadi nilainya tidak ikut ditulis di x-data.
        // x-data="ceplokDatepicker(JSON.parse('…'))": isi string JavaScript-nya dibaca sebagai JSON.
        preg_match("/JSON\\.parse\\('(.*)'\\)\\)$/", $this->find($dom, '#playground [x-modelable]')->getAttribute('x-data'), $m);
        $config = json_decode(json_decode('"'.$m[1].'"'), true);

        $this->assertNull($config['value']);
        $this->assertSame('period', $config['type']);
    }

    public function test_datepicker_menyaring_pengajuan(): void
    {
        $all = Livewire::test('playground.datepicker-filter');

        $all->assertSeeText('8 pengajuan · semua tanggal');

        $today = today()->toDateString();
        $all->set('periode', ['start' => $today, 'end' => $today])->assertSeeText('1 pengajuan');
    }

    public function test_dropdown_mode_selection_dan_contoh_radio(): void
    {
        $selection = $this->domOf(Livewire::test('playground.dropdown')->set('mode', 'selection')->set('separator', true));

        $this->assertNotNull($selection->querySelector('#playground [popover][role="listbox"]'));
        $this->assertSame('Waktu', trim($this->find($selection, '#playground [role="group"] p')->textContent));
        $this->assertTrue($this->find($selection, '[aria-label="Pilih contoh Dropdown"] button')->hasAttribute('disabled'));
        $this->assertStringContainsString("'separator' => true", $this->code($selection));

        $radio = $this->domOf(Livewire::test('playground.dropdown')->set('example', 'radio')->set('dark', true));

        $this->assertCount(3, $radio->querySelectorAll('#playground [popover] input[type="radio"]'));
        $this->assertStringContainsString('@php($dark = true)', $this->code($radio));
    }

    public function test_dropdown_selection_dan_aksi_livewire(): void
    {
        $sort = Livewire::test('playground.dropdown-sort')->set('urut', 'Terlama');
        $dom = $this->domOf($sort);

        $this->assertSame('Urutkan: Terlama', trim($this->find($dom, 'button[popovertarget]')->textContent));
        $this->assertSame('true', $this->find($dom, '[role="option"]:nth-child(2)')->getAttribute('aria-selected'));
        $this->assertSame("\$set('urut', 'Nama A–Z')", $this->find($dom, '[role="option"]:nth-child(3)')->getAttribute('wire:click'));

        Livewire::test('playground.dropdown-actions')->assertSeeText('Belum ada aksi.')->call('hapus')->assertSeeText('Dokumen dihapus.');
    }

    public function test_upload_livewire_memvalidasi_berkas(): void
    {
        $component = Livewire::test('playground.upload-livewire')
            ->set('dokumen', UploadedFile::fake()->create('surat.pdf', 120, 'application/pdf'))
            ->assertHasNoErrors()
            ->assertSeeText('surat.pdf');

        $component->set('dokumen', UploadedFile::fake()->image('foto.png'))
            ->assertHasErrors('dokumen')
            ->assertSeeText('Berkas harus PDF atau JPG.');
    }

    public function test_upload_attach_dan_kode(): void
    {
        $dom = $this->domOf(Livewire::test('playground.upload')->set('type', 'attach')->set('platform', 'mobile'));

        $this->assertNotNull($dom->querySelector('#playground label[for] input[type="file"]'));
        // Platform tidak berlaku pada attach, jadi tidak ditulis.
        $this->assertStringNotContainsString('platform=', $this->code($dom));
        $this->assertStringContainsString('type="attach"', $this->code($dom));
    }

    public function test_radio_toggle_checkbox_terikat_wire_model(): void
    {
        $radio = $this->domOf(Livewire::test('playground.radio')->set('pilihan', 'wna'));

        $this->assertTrue($this->find($radio, '#playground input[value="wna"]')->hasAttribute('checked'));
        $this->assertFalse($this->find($radio, '#playground input[value="wni"]')->hasAttribute('checked'));

        $toggle = $this->domOf(Livewire::test('playground.toggle')->set('aktif', false)->set('state', 'inactive'));
        $switch = $this->find($toggle, '#playground input[role="switch"]');

        $this->assertFalse($switch->hasAttribute('checked'));
        $this->assertTrue($switch->hasAttribute('disabled'));

        $checkbox = $this->domOf(Livewire::test('playground.checkbox')->set('dipilih', ['kk', 'npwp']));
        $checked = array_map(fn ($el) => $el->getAttribute('value'), iterator_to_array($checkbox->querySelectorAll('#playground input[checked]'), false));

        $this->assertSame(['kk', 'npwp'], $checked);
        $this->assertStringContainsString('wire:model="dipilih"', $this->code($checkbox));
    }

    public function test_contoh_livewire_radio_toggle_checkbox(): void
    {
        Livewire::test('playground.radio-livewire')->set('kewarganegaraan', 'wna')->assertSeeText('paspor dan izin tinggal');
        Livewire::test('playground.toggle-livewire')->set('notifikasi', false)->assertSet('diubah', 1)->assertSeeText('diubah 1×');
        Livewire::test('playground.checkbox-livewire')->set('berkas', ['ktp', 'kk'])->assertSeeText('KTP elektronik, Kartu keluarga');
    }

    private function domOf(Testable $component): HTMLDocument
    {
        return $this->parse($component->html());
    }

    /** Teks kode pada blok Penggunaan, tanpa markup sorotannya. */
    private function code(HTMLDocument $dom): string
    {
        return $this->find($dom, '#penggunaan pre > code')->textContent;
    }
}
