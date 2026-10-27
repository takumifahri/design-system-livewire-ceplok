<?php

namespace Tests\Feature\Docs;

use App\Docs\Navigation;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as Router;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Navigation adalah satu-satunya sumber untuk rail, panel samping, pencarian,
 * dan menu footer docs, jadi isinya harus sejalan dengan rute yang ada.
 */
class NavigationTest extends TestCase
{
    /** @return list<string> */
    private function docsRoutes(): array
    {
        return collect(Router::getRoutes()->getRoutes())
            ->filter(fn (Route $route) => in_array('GET', $route->methods(), true))
            ->map(fn (Route $route) => $route->getName())
            ->filter(fn (?string $name) => $name === 'home' || Str::startsWith((string) $name, ['components.', 'form.', 'foundations.']))
            ->values()
            ->all();
    }

    public function test_setiap_halaman_docs_ada_di_navigasi(): void
    {
        $navigated = collect(Navigation::sidebars())
            ->flatMap(fn (array $panel) => $panel['items'])
            ->flatMap(fn (array $item) => [$item['route'], ...array_column($item['children'] ?? [], 'route')])
            ->merge(array_column(Navigation::rail(), 'route'))
            ->unique()
            ->values()
            ->all();

        $this->assertEqualsCanonicalizing($this->docsRoutes(), $navigated);
    }

    public function test_setiap_area_rail_yang_punya_panel_dibuka_dari_overview_panel_itu(): void
    {
        $sidebars = Navigation::sidebars();

        foreach (Navigation::rail() as $area) {
            if (isset($sidebars[$area['key']])) {
                $this->assertSame($area['route'], $sidebars[$area['key']]['items'][0]['route']);
            }
        }
    }

    public function test_pencarian_memuat_seluruh_halaman_tanpa_duplikat(): void
    {
        $pages = Navigation::pages();
        $urls = array_column($pages, 'url');

        $this->assertCount(41, $pages);
        $this->assertSame(array_unique($urls), $urls);
        $this->assertEqualsCanonicalizing(array_map(fn (string $name) => route($name), $this->docsRoutes()), $urls);

        foreach ($pages as $page) {
            $this->assertNotSame('', $page['label']);
            $this->assertContains($page['letak'], ['Area utama', 'Components', 'Form', 'Form › Input Field Form', 'Foundations']);
        }
    }

    public function test_sub_halaman_menyusul_induknya_dengan_letak_bertingkat(): void
    {
        $labels = array_column(Navigation::pages(), 'label');
        $parent = array_search('Input Field Form', $labels, true);

        $this->assertSame(['Input Field', 'Floating Label', 'Text Area'], array_slice($labels, $parent + 1, 3));
        $this->assertSame('Form › Input Field Form', collect(Navigation::pages())->firstWhere('label', 'Text Area')['letak']);
    }

    public function test_kata_kunci_bahasa_indonesia_untuk_label_berbahasa_inggris(): void
    {
        $button = collect(Navigation::pages())->firstWhere('label', 'Button');

        $this->assertStringContainsString('tombol', $button['kata']);
    }

    public function test_section_of_menentukan_area_dari_nama_rute(): void
    {
        $this->assertSame('components', Navigation::sectionOf('components.button'));
        $this->assertSame('form', Navigation::sectionOf('form.input-field.text-area'));
        $this->assertSame('foundations', Navigation::sectionOf('foundations.index'));
        $this->assertSame('home', Navigation::sectionOf('home'));
        $this->assertSame('home', Navigation::sectionOf(null));
    }

    public function test_menu_footer_mengikuti_rail(): void
    {
        $this->assertSame(
            [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Components', 'url' => route('components.index')],
                ['label' => 'Form', 'url' => route('form.index')],
                ['label' => 'Foundations', 'url' => route('foundations.index')],
            ],
            Navigation::footerMenus(),
        );
    }

    public function test_ikon_sosial_dekoratif(): void
    {
        foreach (Navigation::socials() as $social) {
            $this->assertNotSame('', $social['label']);
            $this->assertStringContainsString('aria-hidden="true"', $social['icon']->toHtml());
        }
    }
}
