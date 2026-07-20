<?php

namespace Tpl\DesignKit;

use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DesignKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Ikon brand/sosial milik design kit — <x-brand-facebook>, svg('brand-x'), ...
        // Harus didaftarkan pada fase register: blade-icons meregistrasikan
        // komponen Blade saat ViewFactory pertama kali di-resolve, sebelum boot.
        $this->callAfterResolving(IconFactory::class, function (IconFactory $factory) {
            $factory->add('design-kit-brands', [
                'path' => __DIR__.'/../resources/svg/brands',
                'prefix' => 'brand',
            ]);
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'design-kit');

        // <x-dk::button>, <x-dk::badge>, ...
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'dk');

        $this->publishes([
            __DIR__.'/../resources/css/tokens.css' => resource_path('css/vendor/design-kit/tokens.css'),
        ], 'design-kit-assets');

        $this->publishes([
            __DIR__.'/../resources/views/components' => resource_path('views/vendor/design-kit/components'),
        ], 'design-kit-views');
    }
}
