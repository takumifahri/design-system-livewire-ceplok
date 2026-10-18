<?php

namespace App\Docs;

use Illuminate\Support\HtmlString;

/**
 * Struktur navigasi dokumentasi — satu-satunya sumber untuk rail, panel
 * samping, pencarian halaman (Ctrl+K), dan menu footer di layout docs.
 * Urutannya mengikuti dokumentasi kit React.
 *
 * @phpstan-type NavLink array{label: string, route: string, keywords?: string}
 * @phpstan-type NavItem array{label: string, route: string, keywords?: string, children?: list<NavLink>}
 * @phpstan-type RailItem array{key: string, label: string, route: string, icon: string, keywords?: string}
 */
final class Navigation
{
    /** @return list<RailItem> */
    public static function rail(): array
    {
        return [
            [
                'key' => 'home',
                'label' => 'Home',
                'route' => 'home',
                'icon' => 'M3 10.5 12 3l9 7.5M5.5 9.5V20h13V9.5M9.5 20v-6h5v6',
                'keywords' => 'beranda halaman utama',
            ],
            [
                'key' => 'components',
                'label' => 'Components',
                'route' => 'components.index',
                'icon' => 'M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z',
            ],
            [
                'key' => 'form',
                'label' => 'Form',
                'route' => 'form.index',
                'icon' => 'M4 6h16M4 12h10M4 18h13M17.5 10.5l3 3-4.5 4.5H13v-3l4.5-4.5Z',
            ],
            [
                'key' => 'foundations',
                'label' => 'Foundations',
                'route' => 'foundations.index',
                'icon' => 'M12 3.5c3.8 3.2 6.5 6 6.5 9.5a6.5 6.5 0 1 1-13 0c0-3.5 2.7-6.3 6.5-9.5Z',
            ],
        ];
    }

    /**
     * Panel samping per area. `keywords` adalah kata lain untuk pencarian
     * halaman, dipisah spasi — terutama padanan bahasa Indonesia dari label
     * yang berbahasa Inggris, mis. "tombol" untuk Button. `children` adalah
     * sub-halaman yang ditarik masuk di bawah induknya.
     *
     * @return array<string, array{title: string, items: list<NavItem>}>
     */
    public static function sidebars(): array
    {
        return [
            'components' => [
                'title' => 'Components',
                'items' => [
                    ['label' => 'Overview', 'route' => 'components.index', 'keywords' => 'ikhtisar komponen'],
                    ['label' => 'Container', 'route' => 'components.container', 'keywords' => 'wadah'],
                    ['label' => 'Button', 'route' => 'components.button', 'keywords' => 'tombol'],
                    ['label' => 'Badge', 'route' => 'components.badge', 'keywords' => 'lencana label'],
                    ['label' => 'Avatar', 'route' => 'components.avatar', 'keywords' => 'foto profil inisial'],
                    ['label' => 'Spinner', 'route' => 'components.spinner', 'keywords' => 'memuat loading'],
                    ['label' => 'Dropdown', 'route' => 'components.dropdown', 'keywords' => 'menu'],
                    ['label' => 'Alert', 'route' => 'components.alert', 'keywords' => 'peringatan pesan'],
                    ['label' => 'Toast', 'route' => 'components.toast', 'keywords' => 'notifikasi'],
                    ['label' => 'Card', 'route' => 'components.card', 'keywords' => 'kartu'],
                    ['label' => 'Hero', 'route' => 'components.hero', 'keywords' => 'banner'],
                    ['label' => 'Footer', 'route' => 'components.footer', 'keywords' => 'kaki halaman'],
                    ['label' => 'Breadcrumb', 'route' => 'components.breadcrumb', 'keywords' => 'jejak remah'],
                    ['label' => 'Pagination', 'route' => 'components.pagination', 'keywords' => 'halaman paginasi'],
                ],
            ],
            'form' => [
                'title' => 'Form',
                'items' => [
                    ['label' => 'Overview', 'route' => 'form.index', 'keywords' => 'ikhtisar formulir'],
                    [
                        'label' => 'Input Field Form',
                        'route' => 'form.input-field',
                        'keywords' => 'isian',
                        'children' => [
                            ['label' => 'Input Field', 'route' => 'form.input-field.input', 'keywords' => 'isian teks password kata sandi'],
                            ['label' => 'Floating Label', 'route' => 'form.input-field.floating-label', 'keywords' => 'label melayang'],
                            ['label' => 'Text Area', 'route' => 'form.input-field.text-area', 'keywords' => 'teks panjang editor'],
                        ],
                    ],
                    ['label' => 'Regular Select Form', 'route' => 'form.select', 'keywords' => 'pilihan dropdown provinsi'],
                    ['label' => 'Search Form', 'route' => 'form.search', 'keywords' => 'cari pencarian'],
                    ['label' => 'Datepicker', 'route' => 'form.datepicker', 'keywords' => 'tanggal kalender'],
                    ['label' => 'Upload Form', 'route' => 'form.upload', 'keywords' => 'unggah berkas file'],
                    ['label' => 'Radio Button', 'route' => 'form.radio', 'keywords' => 'pilihan tunggal'],
                    ['label' => 'Toggle Button', 'route' => 'form.toggle', 'keywords' => 'sakelar switch'],
                    ['label' => 'Checkbox', 'route' => 'form.checkbox', 'keywords' => 'centang kotak'],
                ],
            ],
            'foundations' => [
                'title' => 'Foundations',
                'items' => [
                    ['label' => 'Overview', 'route' => 'foundations.index', 'keywords' => 'ikhtisar dasar'],
                    ['label' => 'Colors', 'route' => 'foundations.colors', 'keywords' => 'warna palet token'],
                    ['label' => 'Typography', 'route' => 'foundations.typography', 'keywords' => 'tipografi huruf font teks'],
                    ['label' => 'Spacing', 'route' => 'foundations.spacing', 'keywords' => 'jarak spasi'],
                    ['label' => 'Border', 'route' => 'foundations.border', 'keywords' => 'garis radius sudut'],
                    ['label' => 'Elevation', 'route' => 'foundations.elevation', 'keywords' => 'bayangan shadow'],
                    ['label' => 'Icons', 'route' => 'foundations.icons', 'keywords' => 'ikon'],
                ],
            ],
        ];
    }

    /** Area dari nama route yang sedang dibuka: `components.button` → `components`. */
    public static function sectionOf(?string $route): string
    {
        $area = strtok((string) $route, '.');

        return in_array($area, ['components', 'form', 'foundations'], true) ? $area : 'home';
    }

    /**
     * Menu footer: area utama, diturunkan dari rail yang sama dengan navigasi
     * kiri supaya keduanya tak mungkin melenceng.
     *
     * @return list<array{label: string, url: string}>
     */
    public static function footerMenus(): array
    {
        return array_map(
            fn (array $area) => ['label' => $area['label'], 'url' => route($area['route'])],
            self::rail(),
        );
    }

    /**
     * Ikon media sosial di footer docs — sama dengan socialIcons di docs React.
     *
     * @return list<array{label: string, url: string, icon: HtmlString}>
     */
    public static function socials(): array
    {
        $icon = fn (string $path) => new HtmlString(
            '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="'.$path.'" /></svg>'
        );

        return [
            ['label' => 'Instagram', 'url' => '#', 'icon' => $icon('M12 2.2c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.21 15.58 2.2 15.2 2.2 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.21 8.8 2.2 12 2.2Zm0 1.8c-3.15 0-3.5.01-4.74.07-.9.04-1.38.19-1.7.32-.43.16-.74.36-1.06.68-.32.32-.52.63-.68 1.06-.13.32-.28.8-.32 1.7C3.44 8.9 3.43 9.25 3.43 12s.01 3.1.07 4.34c.04.9.19 1.38.32 1.7.16.43.36.74.68 1.06.32.32.63.52 1.06.68.32.13.8.28 1.7.32 1.24.06 1.59.07 4.74.07s3.5-.01 4.74-.07c.9-.04 1.38-.19 1.7-.32.43-.16.74-.36 1.06-.68.32-.32.52-.63.68-1.06.13-.32.28-.8.32-1.7.06-1.24.07-1.59.07-4.34s-.01-3.1-.07-4.34c-.04-.9-.19-1.38-.32-1.7a2.85 2.85 0 0 0-.68-1.06 2.85 2.85 0 0 0-1.06-.68c-.32-.13-.8-.28-1.7-.32C15.5 4.01 15.15 4 12 4Zm0 3.06A4.94 4.94 0 1 1 12 16.94 4.94 4.94 0 0 1 12 7.06Zm0 8.14A3.2 3.2 0 1 0 12 8.8a3.2 3.2 0 0 0 0 6.4Zm6.3-8.34a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0Z')],
            ['label' => 'X', 'url' => '#', 'icon' => $icon('M17.53 3h3.02l-6.6 7.54L21.75 21h-5.9l-4.62-6.04L5.94 21H2.92l7.06-8.07L2.5 3h6.05l4.18 5.52L17.53 3Zm-1.06 16.2h1.67L7.6 4.72H5.8L16.47 19.2Z')],
            ['label' => 'Facebook', 'url' => '#', 'icon' => $icon('M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z')],
        ];
    }

    /**
     * Seluruh halaman yang bisa dicari, urut seperti navigasi. Area tanpa panel
     * samping (Home) masuk sebagai satu halaman; sub-halaman menyusul induknya
     * dengan letak "Form › Input Field Form".
     *
     * @return list<array{label: string, url: string, letak: string, kata: string}>
     */
    public static function pages(): array
    {
        $sidebars = self::sidebars();
        $pages = [];

        foreach (self::rail() as $area) {
            $panel = $sidebars[$area['key']] ?? null;

            if ($panel === null) {
                $pages[] = ['label' => $area['label'], 'url' => route($area['route']), 'letak' => 'Area utama', 'kata' => $area['keywords'] ?? ''];

                continue;
            }

            foreach ($panel['items'] as $item) {
                $pages[] = ['label' => $item['label'], 'url' => route($item['route']), 'letak' => $panel['title'], 'kata' => $item['keywords'] ?? ''];

                foreach ($item['children'] ?? [] as $child) {
                    $pages[] = ['label' => $child['label'], 'url' => route($child['route']), 'letak' => $panel['title'].' › '.$item['label'], 'kata' => $child['keywords'] ?? ''];
                }
            }
        }

        return $pages;
    }
}
