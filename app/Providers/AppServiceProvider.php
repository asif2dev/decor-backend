<?php

namespace App\Providers;

use App\Modules\SearchEngine\DatabaseSearchEngine;
use App\Modules\SearchEngine\SearchEngineInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            SearchEngineInterface::class,
            fn () => $this->app->make(DatabaseSearchEngine::class)
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
