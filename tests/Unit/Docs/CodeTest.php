<?php

namespace Tests\Unit\Docs;

use App\Docs\Code;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    public function test_dedent_membuang_indentasi_bersama_dan_baris_kosong_di_ujung(): void
    {
        $code = "\n        <div>\n            <p>Isi</p>\n        </div>\n    \n";

        $this->assertSame("<div>\n    <p>Isi</p>\n</div>", Code::dedent($code));
    }

    public function test_dedent_mengabaikan_baris_kosong_di_tengah(): void
    {
        $this->assertSame("a\n\nb", Code::dedent("    a\n\n    b"));
    }

    public function test_dedent_membuang_spasi_di_ujung_baris(): void
    {
        $this->assertSame("a\n  b", Code::dedent("  a   \n    b  "));
    }

    public function test_dedent_dari_slot_mengukur_indentasi_mulai_baris_kedua(): void
    {
        // Blade men-trim isi slot, jadi baris pertama sudah kehilangan indentasinya.
        $code = "<x-ceplok::badge\n                    variant=\"success\"\n                >Aktif</x-ceplok::badge>";

        $this->assertSame("<x-ceplok::badge\n    variant=\"success\"\n>Aktif</x-ceplok::badge>", Code::dedent($code, fromSlot: true));
    }

    public function test_dedent_menerima_akhir_baris_windows(): void
    {
        $this->assertSame("a\n  b", Code::dedent("    a\r\n      b\r\n"));
    }

    public function test_highlight_meng_escape_kode_lalu_memasang_sorotan(): void
    {
        $html = Code::highlight('<x-ceplok::badge ⟦variant⟧="success" ⟦*dark-mode⟧ />')->toHtml();

        $this->assertSame(
            '&lt;x-ceplok::badge <span class="text-primary-300">variant</span>=&quot;success&quot; <span class="font-bold text-primary-300">dark-mode</span> /&gt;',
            $html,
        );
    }

    public function test_highlight_nilai_hidup_menjadi_x_text_dengan_teks_cadangan(): void
    {
        $html = Code::highlight('max-w-[⟪width|720⟫px] ⟪measured.avail⟫')->toHtml();

        $this->assertSame('max-w-[<span x-text="width">720</span>px] <span x-text="measured.avail"></span>', $html);
    }

    public function test_highlight_tidak_mengubah_kurung_siku_biasa(): void
    {
        $this->assertSame('[[&#039;label&#039; =&gt; &#039;Home&#039;]]', Code::highlight("[['label' => 'Home']]")->toHtml());
    }

    public function test_theme_menyisakan_penanda_gelap_hanya_di_mode_gelap(): void
    {
        $code = '<x-ceplok::alert variant="success"⟨ dark-mode⟩ />';

        $this->assertSame('<x-ceplok::alert variant="success" dark-mode />', Code::theme($code, dark: true));
        $this->assertSame('<x-ceplok::alert variant="success" />', Code::theme($code, dark: false));
    }

    public function test_theme_menyisakan_penanda_terang_hanya_di_mode_terang(): void
    {
        $code = '<x-ceplok::alert⟨! ⟦dark-mode⟧⟩ variant="success" />';

        $this->assertSame('<x-ceplok::alert ⟦dark-mode⟧ variant="success" />', Code::theme($code, dark: false));
        $this->assertSame('<x-ceplok::alert variant="success" />', Code::theme($code, dark: true));
    }

    public function test_theme_menghapus_baris_yang_hanya_berisi_penanda_yang_dibuang(): void
    {
        $code = "<x-ceplok::alert\n    variant=\"success\"\n    ⟨dark-mode⟩\n\n/>";

        $this->assertSame("<x-ceplok::alert\n    variant=\"success\"\n    dark-mode\n\n/>", Code::theme($code, dark: true));
        $this->assertSame("<x-ceplok::alert\n    variant=\"success\"\n\n/>", Code::theme($code, dark: false));
    }

    public function test_theme_tidak_membaca_mode_halaman_untuk_kode_tanpa_penanda(): void
    {
        // Tes unit ini tidak punya request; kode tanpa penanda dikembalikan apa adanya.
        $this->assertSame("a\n\n  b", Code::theme("a\n\n  b"));
    }

    public function test_highlight_tidak_menjalankan_markup_di_dalam_kode(): void
    {
        $html = Code::highlight('⟦<script>alert(1)</script>⟧')->toHtml();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
