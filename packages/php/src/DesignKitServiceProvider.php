<?php

namespace Tpl\DesignKit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DesignKitServiceProvider extends ServiceProvider
{
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
