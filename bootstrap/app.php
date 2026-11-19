<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Di produksi, container hanya menerima request dari reverse proxy di
        // VPS (port-nya terikat ke 127.0.0.1, lihat compose.yaml). Header
        // X-Forwarded-* dipercaya supaya URL aset ikut memakai https.
        $middleware->trustProxies(at: '*');

        // Keadaan panel samping, mode gelap docs, dan Sidebar Portal ditulis
        // JavaScript supaya dapat dipulihkan sebelum render halaman berikutnya.
        $middleware->encryptCookies(except: [
            'ceplok_docs_panel',
            'ceplok_docs_theme',
            'ceplok_portal_sidebar_collapsed',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
