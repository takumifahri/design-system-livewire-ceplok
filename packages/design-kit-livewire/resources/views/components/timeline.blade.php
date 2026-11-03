@php
    use CeplokUi\DesignKit\Enums\TimelinePlatform;
    use CeplokUi\DesignKit\Enums\TimelineSize;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Dates;
    use CeplokUi\DesignKit\Support\Prop;
    use Illuminate\Support\HtmlString;
    use Illuminate\View\ComponentAttributeBag;
@endphp

{{--
    Timeline — riwayat kejadian berurutan: siapa, kapan, dan apa yang terjadi,
    dengan avatar di sepanjang satu garis.

    `items` berisi array per kejadian: `name` (wajib), `role`, `time`,
    `elapsed`, `description`, `details`, `attachments`, `status`, `avatar`
    (alamat foto), dan `initials`. Selain `time`, teksnya boleh string atau
    markup (`HtmlString`).

    - `time`: string waktu ISO 8601 dengan jam (`2026-01-01 09:05`,
      `2026-01-01T09:05:00+07:00`) atau DateTimeInterface, mis. Carbon dari
      Eloquent. Ditulis dengan format tetap desain, `01 Jan 2026 · 09:05`, di
      zona waktu nilainya; string tanpa zona waktu dibaca di zona waktu
      aplikasi. Singkatan bulannya dari `labels['months']`.
    - `attachments`: daftar ['name' => …, 'href' => …, 'target' => …,
      'download' => …]. Tanpa `href` namanya tampil sebagai teks biasa.
      `download` bernilai true memakai nama dari alamatnya, sedangkan teks
      menjadi nama berkas hasil unduhan.
    - `status`: dirender sebagai <x-ceplok::badge>. Isinya `label` (wajib),
      `variant` (bawaan success), `icon` (markup atau nama ikon, mis.
      `ceplok-s-clock`), `dismissible`, `open`, `size`, `dark-mode` (bawaannya
      `dark-mode` Timeline), `class`, dan `attributes` untuk atribut tambahan
      pada Badge, mis. ['wire:dismiss' => 'tutupStatus(2)'].

    Avatar dan status memakai komponen Avatar dan Badge kit, sama seperti di
    kit React. Warnanya dari token semantik `border` dan `brand` serta token
    `--color-timeline-*`, jadi tema gelap cukup mengganti nilai token lewat
    `data-theme`. `dark-mode` memasang `data-theme="dark"` dan diteruskan ke
    Badge; Badge belum berbasis token, jadi ia tidak ikut gelap lewat
    `data-theme` pembungkus.
--}}
@props([
    'items' => [],
    'size' => 'large',
    'platform' => 'desktop',
    'labels' => [],
    'darkMode' => false,
])

@php
    $size = Prop::oneOf(TimelineSize::class, $size, TimelineSize::Large, 'timeline', 'size');
    $platform = Prop::oneOf(TimelinePlatform::class, $platform, TimelinePlatform::Desktop, 'timeline', 'platform');
    $darkMode = Prop::bool($darkMode, false, 'timeline', 'dark-mode');
    $items = array_values(is_iterable($items) ? collect($items)->all() : []);
    $labels = array_merge(
        ['elapsed' => 'Elapsed time:', 'details' => 'Details', 'attachments' => 'Attachments', 'months' => []],
        is_iterable($labels) ? collect($labels)->all() : [],
    );
    // Dua belas singkatan bulan, dimulai Januari; yang tidak diisi memakai bawaan Dates::writeTime().
    $months = is_iterable($labels['months']) ? array_values(collect($labels['months'])->all()) : [];
    $desktop = $platform === 'desktop';

    // Ukuran per bagian, sama dengan kit React. `line` menaruh garis mobile
    // tepat di tengah avatar. Padding desain (20px dan 16px) diukur dari tepi
    // luar termasuk garis tepi 1px, jadi padding CSS-nya 1px lebih kecil.
    // Avatar belum punya ukuran 44px dan 36px, jadi ukurannya ditimpa dengan
    // `!`: class yang bentrok tidak digabungkan, sehingga tanpa `!` yang
    // menang `size-8` bawaan Avatar.
    $sizes = [
        'large' => ['text' => 'text-body-sm', 'gap' => 'gap-6', 'card' => 'p-4.75', 'avatar' => 'size-11!', 'line' => 'before:left-5.25'],
        'small' => ['text' => 'text-caption', 'gap' => 'gap-4.5', 'card' => 'p-3.75', 'avatar' => 'size-9!', 'line' => 'before:left-4.25'],
    ];
    $s = $sizes[$size];
@endphp

<ol
    {{ $attributes
        ->class([
            'relative flex flex-col leading-normal before:absolute before:inset-y-0 before:w-0.5 before:bg-border',
            $desktop ? 'before:left-1/2 before:-translate-x-1/2' : $s['line'],
            $s['text'],
            $s['gap'],
        ])
        ->merge($darkMode ? ['data-theme' => 'dark'] : []) }}
>
    @foreach ($items as $index => $item)
        @php
            // Desktop: item ke-1, ke-3, dan seterusnya di kiri garis. Mobile:
            // semua kartu di kanan, jadi lancipnya selalu menunjuk ke kiri.
            $left = $desktop && $index % 2 === 0;
            $attachments = array_values(collect($item['attachments'] ?? [])->all());
            $time = Dates::time($item['time'] ?? null);

            // Waktu yang bukan waktu, mis. '01/02/2026', menjadi error saat app.debug aktif. Di production
            // kesalahannya dilaporkan ke log dan waktunya tidak ditampilkan, sama seperti di kit React.
            if ($time === null && Content::filled($item['time'] ?? null)) {
                $given = is_string($item['time']) ? var_export($item['time'], true) : get_debug_type($item['time']);
                Prop::violation('`time` pada item ke-'.($index + 1)." <x-ceplok::timeline> harus string waktu ISO 8601 dengan jam, mis. '2026-01-01 09:05', atau DateTimeInterface; diberikan {$given}.");
            }

            $hasMain = $time !== null
                || Content::filled($item['elapsed'] ?? null)
                || Content::filled($item['description'] ?? null);
            $hasBody = $hasMain || Content::filled($item['details'] ?? null) || $attachments !== [];
            $status = is_iterable($item['status'] ?? null) ? collect($item['status'])->all() : null;

            // Warna Avatar ikut ditimpa ke token Timeline. Warna gelap bawaan
            // Avatar hanya menyala lewat prop `dark-mode`, sedangkan token ini
            // juga ikut `data-theme` pembungkusnya. `relative` membuat avatar
            // tergambar di atas garis.
            $avatarClass = implode(' ', [
                'relative row-start-1 mt-3 bg-border! text-timeline-avatar!',
                $s['avatar'],
                $desktop ? 'col-start-2' : 'col-start-1',
            ]);

            if ($status !== null) {
                // Class Badge disusun di sini supaya `shrink-0` selalu lebih
                // dulu, lalu class dari `class` dan `attributes` menyusul.
                $statusAttributes = collect($status['attributes'] ?? [])->all();
                $badge = new ComponentAttributeBag(array_diff_key($statusAttributes, ['class' => true]));
                $badgeClass = trim(implode(' ', ['shrink-0', $status['class'] ?? '', $statusAttributes['class'] ?? '']));
                $badgeIcon = Content::filled($status['icon'] ?? null) ? new HtmlString(Content::icon($status['icon'])) : null;
            }
        @endphp
        <li @class([
            'grid items-start',
            $desktop ? 'grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] gap-x-6' : 'grid-cols-[auto_minmax(0,1fr)] gap-x-3.5',
        ])>
            {{-- Tanpa foto dan inisial, isi Avatar adalah siluet dari desain: kepala dan bahu yang terpotong lingkarannya. --}}
            <x-ceplok::avatar :src="$item['avatar'] ?? null" alt="" :initials="$item['initials'] ?? null" :class="$avatarClass">
                <svg viewBox="0 0 44 44" class="fill-current {{ $s['avatar'] }}" aria-hidden="true">
                    <circle cx="22" cy="14.08" r="6.16" />
                    <ellipse cx="22" cy="34.32" rx="15.84" ry="10.56" />
                </svg>
            </x-ceplok::avatar>

            <div @class(['row-start-1 min-w-0', $desktop ? ($left ? 'col-start-1' : 'col-start-3') : 'col-start-2'])>
                <div class="relative border border-border bg-timeline-surface shadow-sm {{ $s['card'] }}">
                    {{--
                        Lancip 7×12px di sisi kartu yang menghadap garis. Alasnya
                        menimpa tepi kartu, jadi kartu dan lancipnya terbaca sebagai
                        satu balon. Posisinya dihitung dari tepi dalam border: 22px
                        dari atas sama dengan 23px dari tepi luar seperti di desain.
                    --}}
                    <svg viewBox="0 0 7 12" aria-hidden="true" @class([
                        'absolute top-5.5 h-3 w-1.75 overflow-visible',
                        $left ? '-right-1.75' : '-left-1.75 -scale-x-100',
                    ])>
                        <path d="M0 0 7 6 0 12Z" class="fill-timeline-surface" />
                        <path d="M0 0 7 6 0 12" fill="none" class="stroke-border" />
                    </svg>

                    <div class="flex items-start gap-3">
                        <div class="min-w-0 flex-1 text-brand">
                            <p>{{ $item['name'] ?? '' }}</p>
                            @if (Content::filled($item['role'] ?? null))
                                <p>{{ $item['role'] }}</p>
                            @endif
                        </div>
                        @if ($status !== null)
                            <x-ceplok::badge
                                :attributes="$badge"
                                :variant="$status['variant'] ?? 'success'"
                                :size="$status['size'] ?? 'sm'"
                                :icon="$badgeIcon"
                                :dismissible="$status['dismissible'] ?? false"
                                :open="$status['open'] ?? null"
                                :dark-mode="$status['dark-mode'] ?? $darkMode"
                                :class="$badgeClass"
                            >{{ $status['label'] ?? '' }}</x-ceplok::badge>
                        @endif
                    </div>

                    {{-- Antar-kelompok diberi satu baris kosong seperti di desain: 1.5em sama dengan tinggi satu baris. --}}
                    @if ($hasBody)
                        <div class="mt-1 space-y-[1.5em] text-timeline-content">
                            @if ($hasMain)
                                <div>
                                    @if ($time !== null)
                                        <p><time datetime="{{ $time->format('Y-m-d\TH:i') }}">{{ Dates::writeTime($time, $months) }}</time></p>
                                    @endif
                                    @if (Content::filled($item['elapsed'] ?? null))
                                        <p>{{ $labels['elapsed'] }} {{ $item['elapsed'] }}</p>
                                    @endif
                                    @if (Content::filled($item['description'] ?? null))
                                        <p>{{ $item['description'] }}</p>
                                    @endif
                                </div>
                            @endif

                            @if (Content::filled($item['details'] ?? null))
                                <div>
                                    <p>{{ $labels['details'] }}</p>
                                    <div>{{ $item['details'] }}</div>
                                </div>
                            @endif

                            @if ($attachments !== [])
                                <div>
                                    <p>{{ $labels['attachments'] }}</p>
                                    <ul>
                                        @foreach ($attachments as $file)
                                            @php
                                                $target = $file['target'] ?? null;
                                                $download = $file['download'] ?? null;
                                            @endphp
                                            <li>
                                                @if (filled($file['href'] ?? null))
                                                    {{--
                                                        `download` ditulis tangan: Blade mencetak atribut boolean true
                                                        sebagai download="download", padahal nilai itu menjadi nama
                                                        berkas hasil unduhan. Tab baru tidak menerima referrer
                                                        halaman ini, sama seperti TableFile di kit React.
                                                    --}}
                                                    <a
                                                        href="{{ $file['href'] }}"
                                                        @if (filled($target)) target="{{ $target }}" @endif
                                                        @if ($target === '_blank') rel="noreferrer" @endif
                                                        @if ($download === true) download @elseif (is_string($download) && $download !== '') download="{{ $download }}" @endif
                                                        class="rounded-sm underline-offset-2 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                                                    >{{ $file['name'] ?? '' }}</a>
                                                @else
                                                    {{ $file['name'] ?? '' }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </li>
    @endforeach
</ol>
