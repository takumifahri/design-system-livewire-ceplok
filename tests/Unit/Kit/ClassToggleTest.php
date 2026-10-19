<?php

namespace Tests\Unit\Kit;

use CeplokUi\DesignKit\Support\ClassToggle;
use PHPUnit\Framework\TestCase;

class ClassToggleTest extends TestCase
{
    public function test_kelas_awal_mengikuti_nilai_ekspresi_saat_dirender_server(): void
    {
        $toggle = (new ClassToggle)->always('flex px-4')->when('buka', false, 'border-primary-600', 'border-border-strong');

        $this->assertSame('flex px-4 border-border-strong', $toggle->classes());

        $open = (new ClassToggle)->when('buka', true, 'border-primary-600', 'border-border-strong');

        $this->assertSame('border-primary-600', $open->classes());
    }

    public function test_kelas_yang_sama_di_kedua_sisi_dipasang_statis_dan_tidak_diikat(): void
    {
        // left-3 ada di kedua sisi; mengikatnya di dua kunci akan membuatnya tercabut.
        $toggle = (new ClassToggle)->when('naik', false, 'top-0 left-3 text-xs', 'top-1/2 text-sm left-3');

        $this->assertSame('left-3 top-1/2 text-sm', $toggle->classes());
        // Js::from meng-escape garis miring; '\/' tetap berarti '/' di JavaScript.
        $this->assertSame("{ 'top-0 text-xs': naik, 'top-1\\/2 text-sm': ! (naik) }", $toggle->binding());
    }

    public function test_tanpa_kelas_yang_berganti_tidak_ada_binding(): void
    {
        $this->assertNull((new ClassToggle)->always('flex')->binding());
        $this->assertNull((new ClassToggle)->when('x', true, 'a b', 'a b')->binding());
    }

    public function test_sisi_kosong_tidak_menghasilkan_kunci(): void
    {
        $this->assertSame("{ 'bg-primary-50': seret }", (new ClassToggle)->when('seret', false, 'bg-primary-50')->binding());
    }
}
