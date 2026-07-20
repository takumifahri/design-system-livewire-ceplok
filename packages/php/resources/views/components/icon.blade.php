@props([
    'name' => null,
    'path' => null,
    'dir' => 'icons',
])

@php
    /**
     * Menyisipkan berkas SVG sebagai markup inline agar warnanya mengikuti
     * currentColor — sehingga ikon otomatis menyesuaikan warna teks induknya
     * (mis. putih di tombol primary, biru di tombol secondary).
     *
     * Blok <mask>, <clipPath>, dan <defs> sengaja dilindungi: warna di dalamnya
     * bersifat teknis (menentukan area tampil), bukan warna yang terlihat.
     * Menggantinya akan merusak bentuk ikon.
     */
    $file = public_path($path ?? trim($dir, '/').'/'.$name.'.svg');
    $svg = is_file($file) ? file_get_contents($file) : null;

    if ($svg) {
        $protected = [];

        $svg = preg_replace_callback('/<(mask|clipPath|defs)\b.*?<\/\1>/is', function ($m) use (&$protected) {
            $key = '@@ICON_PROTECTED_'.count($protected).'@@';
            $protected[$key] = $m[0];

            return $key;
        }, $svg);

        $svg = preg_replace(
            '/(fill|stroke)="(?!none")(?:#[0-9a-f]{3,8}|white|black)"/i',
            '$1="currentColor"',
            $svg
        );

        $svg = strtr($svg, $protected);

        // Ukuran ditentukan oleh class, bukan atribut bawaan berkas.
        $classes = $attributes->get('class') ?: 'size-4';

        $svg = preg_replace_callback('/<svg\b([^>]*)>/i', function ($m) use ($classes) {
            $attrs = preg_replace('/\s(?:width|height|class)="[^"]*"/i', '', $m[1]);

            return '<svg'.$attrs.' class="'.e($classes).'" aria-hidden="true" focusable="false">';
        }, $svg, 1);
    }
@endphp

@if ($svg)
    {!! $svg !!}
@endif
