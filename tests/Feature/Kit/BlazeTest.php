<?php

namespace Tests\Feature\Kit;

use Illuminate\Support\Facades\Blade;
use Livewire\Blaze\Config;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Komponen kit dikompilasi Blaze menjadi fungsi PHP biasa (mode compile saja),
 * bukan lewat mesin komponen Blade yang lebih lambat.
 */
class BlazeTest extends TestCase
{
    /** @return array<string, array{string}> */
    public static function komponen(): array
    {
        $names = [
            'alert', 'avatar', 'badge', 'breadcrumb', 'button', 'card', 'checkbox', 'container', 'datepicker', 'dropdown',
            'floating-label', 'footer', 'hero', 'icon', 'input-field', 'modal', 'pagination', 'popover', 'radio', 'search', 'select', 'spinner',
            'table', 'table-file', 'table-image', 'table-of-contents', 'text-area', 'timeline', 'toast', 'toggle', 'upload',
        ];

        return array_combine($names, array_map(fn (string $name) => [$name], $names));
    }

    #[DataProvider('komponen')]
    public function test_komponen_kit_dikompilasi_tanpa_memo_dan_fold(string $name): void
    {
        $path = base_path("vendor/ceplok-ui/design-kit-livewire/resources/views/components/{$name}.blade.php");
        $config = app(Config::class);

        $this->assertFileExists($path);
        $this->assertTrue($config->shouldCompile($path));
        $this->assertFalse($config->shouldMemoize($path));
        $this->assertFalse($config->shouldFold($path));
    }

    public function test_daftar_komponen_di_tes_ini_lengkap(): void
    {
        $files = array_map(
            fn (string $file) => basename($file, '.blade.php'),
            glob(base_path('vendor/ceplok-ui/design-kit-livewire/resources/views/components/*.blade.php')),
        );

        $this->assertEqualsCanonicalizing(array_keys(self::komponen()), $files);
    }

    public function test_tag_komponen_dikompilasi_menjadi_pemanggilan_fungsi_blaze(): void
    {
        $compiled = Blade::compileString('<x-ceplok::badge variant="brand">Baru</x-ceplok::badge>');

        $this->assertStringContainsString('$__blaze->compile(', $compiled);
        $this->assertStringNotContainsString('startComponent', $compiled);
    }

    public function test_komponen_aplikasi_tetap_lewat_blade_biasa(): void
    {
        // Blaze hanya dipasang pada folder kit; komponen docs tidak ikut.
        $compiled = Blade::compileString('<x-docs.lead>Teks</x-docs.lead>');

        $this->assertStringContainsString('startComponent', $compiled);
    }
}
