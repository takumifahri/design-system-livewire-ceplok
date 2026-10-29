<?php

namespace Tests\Feature\Kit;

use Tests\TestCase;

/**
 * Font Lato 2.0 yang ikut paket: salinan persis dari kit React (lihat
 * scripts/sync-fonts.mjs), dimuat styles.css alih-alih Google Fonts.
 */
class FontsTest extends TestCase
{
    private function kit(string $path): string
    {
        return base_path('vendor/ceplok-ui/design-kit-livewire/resources/'.$path);
    }

    public function test_sembilan_ketebalan_dan_italic_punya_berkasnya(): void
    {
        $css = file_get_contents($this->kit('fonts/lato.css'));

        preg_match_all('/@font-face\s*{[^}]*font-style:\s*(\w+);[^}]*font-weight:\s*(\d+);[^}]*url\(\'\.\/([^\']+)\'\)/', $css, $faces, PREG_SET_ORDER);

        $weights = array_map(fn (array $face) => $face[2].($face[1] === 'italic' ? 'i' : ''), $faces);
        $this->assertSame(['100', '200', '300', '400', '400i', '500', '600', '700', '800', '900'], $weights);

        foreach ($faces as $face) {
            $this->assertFileExists($this->kit('fonts/'.$face[3]));
        }

        $this->assertFileExists($this->kit('fonts/OFL.txt'));
    }

    public function test_styles_memuat_lato_dari_paket_bukan_google_fonts(): void
    {
        $styles = file_get_contents($this->kit('css/styles.css'));

        $this->assertStringContainsString("@import '../fonts/lato.css';", $styles);
        $this->assertStringNotContainsString('family=Lato', $styles);
        // @import URL Inter harus tetap di atas lato.css: @import sesudah aturan @font-face diabaikan browser.
        $this->assertLessThan(strpos($styles, '../fonts/lato.css'), strpos($styles, 'family=Inter'));
    }
}
