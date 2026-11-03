<?php

namespace CeplokUi\DesignKit\Enums;

/**
 * Bentuk tampilan Table of Contents: `auto` (daftar mulai 1024px, tombol +
 * Drawer di bawahnya), `list` (selalu daftar), atau `drawer` (selalu tombol +
 * Drawer).
 */
enum TableOfContentsLayout: string
{
    case Auto = 'auto';
    case List = 'list';
    case Drawer = 'drawer';
}
