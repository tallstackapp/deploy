<?php

namespace TallStackApp\Deploy\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        //
    }

    private function loadRoutes()
    {
        $this->loadRoutesFrom(
            $this->path('routes/routes.php')
        );

        return $this;
    }

    private function loadViews(): self
    {
        $this->loadViewsFrom(
            $this->path('resources/views'),
            'ja-inertia'
        );

        return $this;
    }

    private function loadTranslations()
    {
        $this->loadTranslationsFrom(
            $this->path('lang'),
            'ja_inertia'
        );

        return $this;
    }

    private function path(string ...$path): string
    {
        return join('/', [
            Str::remove('src/Providers', __DIR__),
            ...$path
        ]);
    }
}
