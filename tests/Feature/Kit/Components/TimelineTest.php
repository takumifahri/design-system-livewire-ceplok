<?php

namespace Tests\Feature\Kit\Components;

use DateTimeImmutable;
use DateTimeZone;
use Dom\Element;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Exceptions;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TimelineTest extends TestCase
{
    private const ITEMS = <<<'PHP'
        [
            ['name' => 'User 01', 'role' => 'Role / Department', 'time' => '2026-01-01 09:00', 'description' => 'Pengajuan dibuat.'],
            ['name' => 'User 02', 'role' => 'Role / Department', 'time' => '2026-01-01 09:05'],
        ]
        PHP;

    public function test_desktop_berselang_seling_kiri_dan_kanan_garis(): void
    {
        $ol = $this->find($this->dom('<x-ceplok::timeline :items="'.self::ITEMS.'" />'), 'body > ol');
        [$first, $second] = $this->childrenOf($ol);

        $this->assertHasClasses($ol, 'relative flex flex-col leading-normal before:absolute before:inset-y-0 before:w-0.5 before:bg-border before:left-1/2 before:-translate-x-1/2 text-body-sm gap-6');
        $this->assertFalse($ol->hasAttribute('data-theme'));

        foreach ([$first, $second] as $li) {
            $this->assertHasClasses($li, 'grid items-start grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] gap-x-6');
            $this->assertHasClasses($this->avatar($li), 'col-start-2');
        }

        $this->assertHasClasses($this->column($first), 'row-start-1 min-w-0 col-start-1');
        $this->assertHasClasses($this->notch($first), 'absolute top-5.5 h-3 w-1.75 overflow-visible -right-1.75');
        $this->assertHasClasses($this->column($second), 'col-start-3');
        $this->assertHasClasses($this->notch($second), '-left-1.75 -scale-x-100');
    }

    public function test_mobile_menaruh_garis_di_kiri_dan_semua_kartu_di_kanannya(): void
    {
        $ol = $this->find($this->dom('<x-ceplok::timeline platform="mobile" :items="'.self::ITEMS.'" />'), 'ol');

        $this->assertHasClasses($ol, 'before:left-5.25');
        $this->assertLacksClasses($ol, 'before:left-1/2');

        foreach ($this->childrenOf($ol) as $li) {
            $this->assertHasClasses($li, 'grid-cols-[auto_minmax(0,1fr)] gap-x-3.5');
            $this->assertHasClasses($this->avatar($li), 'col-start-1');
            $this->assertHasClasses($this->column($li), 'col-start-2');
            $this->assertHasClasses($this->notch($li), '-left-1.75 -scale-x-100');
        }
    }

    public function test_ukuran_small(): void
    {
        $ol = $this->find($this->dom('<x-ceplok::timeline size="small" platform="mobile" :items="'.self::ITEMS.'" />'), 'ol');
        $li = $this->childrenOf($ol)[0];

        $this->assertHasClasses($ol, 'text-caption gap-4.5 before:left-4.25');
        $this->assertHasClasses($this->card($li), 'relative border border-border bg-timeline-surface shadow-sm p-3.75');
        $this->assertHasClasses($this->avatar($li), 'size-9!');
        $this->assertHasClasses($this->find($this->avatar($li), 'svg'), 'fill-current size-9!');
    }

    public function test_avatar_memakai_komponen_avatar_dengan_siluet_inisial_atau_foto(): void
    {
        $dom = $this->dom(<<<'BLADE'
            <x-ceplok::timeline :items="[
                ['name' => 'Tanpa foto'],
                ['name' => 'Rina Wulandari', 'initials' => 'RW'],
                ['name' => 'Budi Santoso', 'avatar' => '/images/avatar-sample.svg'],
            ]" />
            BLADE);
        [$silhouette, $initials, $photo] = array_map($this->avatar(...), $this->childrenOf($this->find($dom, 'ol')));

        // Kelas dasar Avatar lebih dulu, lalu ukuran dan warna yang ditimpa Timeline.
        $this->assertHasClasses($silhouette, 'grid shrink-0 place-items-center overflow-hidden rounded-full relative row-start-1 mt-3 bg-border! text-timeline-avatar! size-11!');
        $this->assertSame('0 0 44 44', $this->find($silhouette, 'span[aria-hidden="true"] > svg')->getAttribute('viewBox'));
        $this->assertFalse($silhouette->hasAttribute('role'));

        $this->assertSame('RW', trim($this->find($initials, 'span[aria-hidden="true"]')->textContent));
        $this->assertNull($initials->querySelector('svg'));

        $img = $this->find($photo, 'img');

        $this->assertSame('/images/avatar-sample.svg', $img->getAttribute('src'));
        $this->assertSame('', $img->getAttribute('alt'));
    }

    public function test_isi_kartu_dan_label_bawaan(): void
    {
        $card = $this->card($this->firstItem(<<<'BLADE'
            <x-ceplok::timeline :items="[[
                'name' => 'User 02',
                'role' => 'Role / Department',
                'time' => '2026-01-01 09:05',
                'elapsed' => '5 minutes',
                'description' => 'Lorem ipsum.',
                'details' => 'Sed do eiusmod.',
                'attachments' => [['name' => 'sample-document-02.pdf'], ['name' => 'sample-document-01.pdf']],
            ]]" />
            BLADE));

        $header = $this->find($card, 'div.flex.items-start.gap-3 > div');

        $this->assertHasClasses($header, 'min-w-0 flex-1 text-brand');
        $this->assertSame(['User 02', 'Role / Department'], $this->texts($header, 'p'));

        $body = $this->find($card, 'div.mt-1');
        [$main, $details, $attachments] = $this->childrenOf($body);

        $this->assertHasClasses($body, 'space-y-[1.5em] text-timeline-content');
        $this->assertSame(['01 Jan 2026 · 09:05', 'Elapsed time: 5 minutes', 'Lorem ipsum.'], $this->texts($main, 'p'));
        $this->assertSame('2026-01-01T09:05', $this->find($main, 'p > time')->getAttribute('datetime'));
        $this->assertSame('Details', trim($this->find($details, 'p')->textContent));
        $this->assertSame('Sed do eiusmod.', trim($this->find($details, 'div')->textContent));
        $this->assertSame('Attachments', trim($this->find($attachments, 'p')->textContent));
        $this->assertSame(['sample-document-02.pdf', 'sample-document-01.pdf'], $this->texts($attachments, 'li'));
        $this->assertNull($attachments->querySelector('a'));
    }

    /** @return array<string, array{string, string, string}> */
    public static function waktu(): array
    {
        return [
            'spasi sebelum jam' => ['2026-01-01 09:05', '01 Jan 2026 · 09:05', '2026-01-01T09:05'],
            'tanggal dan jam satu digit' => ['2026-08-07T07:30:59', '07 Aug 2026 · 07:30', '2026-08-07T07:30'],
            'jam 24 jam' => ['2026-12-25 15:45:00', '25 Dec 2026 · 15:45', '2026-12-25T15:45'],
            'zona waktu UTC dari JSON Laravel' => ['2026-03-17T23:30:00.000000Z', '17 Mar 2026 · 23:30', '2026-03-17T23:30'],
            'zona waktu ditulis apa adanya' => ['2026-05-02T08:00+07:00', '02 May 2026 · 08:00', '2026-05-02T08:00'],
        ];
    }

    #[DataProvider('waktu')]
    public function test_waktu_selalu_ditulis_dengan_format_desain(string $time, string $text, string $datetime): void
    {
        $element = $this->find($this->dom('<x-ceplok::timeline :items="[[\'name\' => \'A\', \'time\' => $time]]" />', ['time' => $time]), 'ol p > time');

        $this->assertSame($text, $element->textContent);
        $this->assertSame($datetime, $element->getAttribute('datetime'));
    }

    public function test_waktu_dari_datetime_ditulis_di_zona_waktunya(): void
    {
        $times = $this->dom('<x-ceplok::timeline :items="$items" />', ['items' => [
            ['name' => 'Carbon', 'time' => Carbon::parse('2026-01-01 02:05', 'UTC')->setTimezone('Asia/Jakarta')],
            ['name' => 'DateTimeImmutable', 'time' => new DateTimeImmutable('2026-01-01 23:59', new DateTimeZone('Asia/Jayapura'))],
        ]])->querySelectorAll('ol p > time');

        $this->assertSame(['01 Jan 2026 · 09:05', '01 Jan 2026 · 23:59'], array_map(fn (Element $el) => $el->textContent, iterator_to_array($times, false)));
    }

    public function test_singkatan_bulan_bisa_diganti(): void
    {
        $times = fn (array $months) => array_map(
            fn (Element $el) => $el->textContent,
            iterator_to_array($this->dom('<x-ceplok::timeline :labels="[\'months\' => $months]" :items="[
                [\'name\' => \'A\', \'time\' => \'2026-05-01 10:00\'],
                [\'name\' => \'B\', \'time\' => \'2026-10-12 08:30\'],
            ]" />', ['months' => $months])->querySelectorAll('ol p > time'), false),
        );

        $this->assertSame(['01 Mei 2026 · 10:00', '12 Okt 2026 · 08:30'], $times(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']));
        // Bulan yang tidak diisi tetap memakai singkatan bawaan.
        $this->assertSame(['01 Mei 2026 · 10:00', '12 Oct 2026 · 08:30'], $times(['Jan', 'Feb', 'Mar', 'Apr', 'Mei']));
    }

    public function test_waktu_yang_bukan_waktu_ditolak(): void
    {
        $message = "`time` pada item ke-1 <x-ceplok::timeline> harus string waktu ISO 8601 dengan jam, mis. '2026-01-01 09:05', atau DateTimeInterface";

        $this->assertPropError(<<<'BLADE'
            <x-ceplok::timeline :items="[['name' => 'A', 'time' => '01/02/2026']]" />
            BLADE, $message."; diberikan '01/02/2026'.");
        $this->assertPropError(<<<'BLADE'
            <x-ceplok::timeline :items="[['name' => 'A', 'time' => '01 Jan 2026 · 09:05']]" />
            BLADE, $message);
        $this->assertPropError(<<<'BLADE'
            <x-ceplok::timeline :items="[['name' => 'A', 'time' => '2026-01-01']]" />
            BLADE, $message);
        $this->assertPropError(<<<'BLADE'
            <x-ceplok::timeline :items="[['name' => 'A', 'time' => '2026-02-30 09:00']]" />
            BLADE, $message);
        $this->assertPropError(<<<'BLADE'
            <x-ceplok::timeline :items="[['name' => 'A', 'time' => '2026-01-01 09:00'], ['name' => 'B', 'time' => 1767258300]]" />
            BLADE, '`time` pada item ke-2 <x-ceplok::timeline> harus string waktu ISO 8601 dengan jam, mis. \'2026-01-01 09:05\', atau DateTimeInterface; diberikan int.');
    }

    public function test_di_production_waktu_yang_salah_dilaporkan_dan_tidak_ditampilkan(): void
    {
        config(['app.debug' => false]);
        Exceptions::fake();

        $card = $this->card($this->firstItem(<<<'BLADE'
            <x-ceplok::timeline :items="[['name' => 'User 01', 'time' => '01/02/2026', 'description' => 'Pengajuan dibuat.']]" />
            BLADE));

        $this->assertNull($card->querySelector('time'));
        $this->assertSame(['User 01', 'Pengajuan dibuat.'], $this->texts($card, 'p'));
        Exceptions::assertReported(fn (InvalidArgumentException $e) => str_contains($e->getMessage(), '`time` pada item ke-1'));
    }

    public function test_label_bisa_diganti_sebagian(): void
    {
        $card = $this->card($this->firstItem(<<<'BLADE'
            <x-ceplok::timeline :labels="['elapsed' => 'Waktu proses:', 'details' => 'Rincian']" :items="[[
                'name' => 'Budi', 'elapsed' => '1 jam', 'details' => 'Lengkap.', 'attachments' => [['name' => 'ba.pdf']],
            ]]" />
            BLADE));

        $text = preg_replace('/\s+/', ' ', $card->textContent);

        $this->assertStringContainsString('Waktu proses: 1 jam', $text);
        $this->assertStringContainsString('Rincian', $text);
        $this->assertStringContainsString('Attachments', $text);
    }

    public function test_bagian_yang_kosong_tidak_dirender(): void
    {
        $card = $this->card($this->firstItem("<x-ceplok::timeline :items=\"[['name' => 'User 01', 'time' => '', 'role' => null]]\" />"));

        $this->assertCount(2, $this->childrenOf($card));
        $this->assertSame(['User 01'], $this->texts($card, 'p'));
        $this->assertNull($card->querySelector('span.rounded-md'));
    }

    public function test_lampiran_bertautan_dengan_target_dan_download(): void
    {
        $links = $this->card($this->firstItem(<<<'BLADE'
            <x-ceplok::timeline :items="[[
                'name' => 'User 02',
                'attachments' => [
                    ['name' => 'teks.pdf'],
                    ['name' => 'tab-baru.pdf', 'href' => '/files/a.pdf', 'target' => '_blank'],
                    ['name' => 'unduh.pdf', 'href' => '/files/a.pdf', 'download' => true],
                    ['name' => 'nama.pdf', 'href' => '/files/a.pdf', 'download' => 'lampiran-ktp.pdf'],
                ],
            ]]" />
            BLADE))->querySelectorAll('li a');

        $this->assertCount(3, $links);

        [$tab, $unduh, $nama] = iterator_to_array($links, false);

        $this->assertSame(['/files/a.pdf', '_blank', 'noreferrer'], [$tab->getAttribute('href'), $tab->getAttribute('target'), $tab->getAttribute('rel')]);
        $this->assertFalse($tab->hasAttribute('download'));
        $this->assertHasClasses($tab, 'rounded-sm underline-offset-2 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand');

        // download="" seperti React: nilai "download" akan menjadi nama berkas hasil unduhan.
        $this->assertSame('', $unduh->getAttribute('download'));
        $this->assertFalse($unduh->hasAttribute('target'));
        $this->assertFalse($unduh->hasAttribute('rel'));
        $this->assertSame('lampiran-ktp.pdf', $nama->getAttribute('download'));
    }

    public function test_status_dirender_sebagai_badge_success_seperti_desain(): void
    {
        $card = $this->card($this->firstItem(<<<'BLADE'
            <x-ceplok::timeline :items="[[
                'name' => 'User 02',
                'status' => ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true, 'attributes' => ['wire:dismiss' => 'tutupStatus(2)']],
            ]]" />
            BLADE));

        $badge = $this->find($card, 'div.flex.items-start.gap-3 > span');

        $this->assertHasClasses($badge, 'inline-flex items-center rounded-md h-[22px] gap-1 px-2.5 py-0.5 text-sm bg-green-100 text-green-900 shrink-0');
        $this->assertSame('Badge', trim($this->find($badge, 'span.leading-none')->textContent));
        $this->assertSame('tutupStatus(2)', $badge->getAttribute('wire:dismiss'));
        $this->assertSame(
            $this->find($this->parse(svg('ceplok-s-clock')->toHtml()), 'path')->getAttribute('d'),
            $this->find($badge, 'span[aria-hidden="true"] svg path')->getAttribute('d'),
        );
        $this->assertSame('Hapus', $this->find($badge, 'button')->getAttribute('aria-label'));
    }

    public function test_status_menerima_variant_lain_dan_ikut_dark_mode_timeline(): void
    {
        $ol = $this->find($this->dom(<<<'BLADE'
            <x-ceplok::timeline dark-mode :items="[
                ['name' => 'A', 'status' => ['label' => 'Menunggu', 'variant' => 'warning']],
                ['name' => 'B', 'status' => ['label' => 'Terang', 'dark-mode' => false, 'class' => 'ml-auto']],
            ]" />
            BLADE), 'ol');
        [$waiting, $light] = array_map(fn (Element $li) => $this->find($this->card($li), 'div.flex > span'), $this->childrenOf($ol));

        $this->assertSame('dark', $ol->getAttribute('data-theme'));
        $this->assertHasClasses($waiting, 'bg-yellow-900 text-yellow-300');
        $this->assertNull($waiting->querySelector('button'));
        // `dark-mode` milik status menang atas milik Timeline; variant tetap bawaan success.
        $this->assertHasClasses($light, 'bg-green-100 text-green-900 shrink-0 ml-auto');
    }

    public function test_atribut_dan_class_diteruskan_ke_daftar(): void
    {
        $ol = $this->find($this->dom('<x-ceplok::timeline class="mt-8" data-uji="riwayat" :items="'.self::ITEMS.'" />'), 'ol');

        $this->assertHasClasses($ol, 'relative flex mt-8');
        $this->assertSame('riwayat', $ol->getAttribute('data-uji'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::timeline size="medium" :items="[]" />', "Prop `size` pada <x-ceplok::timeline> harus salah satu dari 'large', 'small'");
        $this->assertPropError('<x-ceplok::timeline platform="tablet" :items="[]" />', "Prop `platform` pada <x-ceplok::timeline> harus salah satu dari 'desktop', 'mobile'");
        $this->assertPropError('<x-ceplok::timeline dark-mode="ya" :items="[]" />', 'Prop `dark-mode` pada <x-ceplok::timeline>');
    }

    private function firstItem(string $template): Element
    {
        return $this->childrenOf($this->find($this->dom($template), 'ol'))[0];
    }

    private function avatar(Element $li): Element
    {
        return $this->childrenOf($li)[0];
    }

    private function column(Element $li): Element
    {
        return $this->childrenOf($li)[1];
    }

    private function card(Element $li): Element
    {
        return $this->childrenOf($this->column($li))[0];
    }

    private function notch(Element $li): Element
    {
        return $this->find($this->card($li), 'svg[viewBox="0 0 7 12"]');
    }

    /** @return list<string> */
    private function texts(Element $root, string $selector): array
    {
        return array_map(fn (Element $el) => trim(preg_replace('/\s+/', ' ', $el->textContent)), iterator_to_array($root->querySelectorAll($selector), false));
    }
}
