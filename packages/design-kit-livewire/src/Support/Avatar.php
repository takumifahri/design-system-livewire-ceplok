<?php

namespace CeplokUi\DesignKit\Support;

class Avatar
{
    public static function initials(?string $name): string
    {
        $words = preg_split('/\s+/u', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($words === []) {
            return '';
        }

        $characters = static function (string $value): array {
            preg_match_all('/\X/u', $value, $matches);

            return $matches[0];
        };

        if (count($words) === 1) {
            return mb_strtoupper(implode('', array_slice($characters($words[0]), 0, 2)));
        }

        return mb_strtoupper(($characters($words[0])[0] ?? '').($characters($words[array_key_last($words)])[0] ?? ''));
    }

    /** SVG profil dibawa sebagai data URI supaya paket tidak memerlukan publish aset publik. */
    public static function profile(string $fallback, string $theme): string
    {
        $file = __DIR__.'/../../resources/avatar/'.ucfirst($fallback).'-'.ucfirst($theme).'.svg';

        return 'data:image/svg+xml;base64,'.base64_encode((string) file_get_contents($file));
    }
}
