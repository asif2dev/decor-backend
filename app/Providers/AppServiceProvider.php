<?php

namespace App\Providers;

use App\Modules\SearchEngine\DatabaseSearchEngine;
use App\Modules\SearchEngine\SearchEngineInterface;
use App\Modules\LoginVerification\ClickSend\ClickSend;
use App\Modules\LoginVerification\NullLoginVerification;
use App\Modules\LoginVerification\LoginVerificationInterface;
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


        $this->app->singleton(
            LoginVerificationInterface::class,
            fn () => $this->app->environment('production') === false
                ? $this->app->make(NullLoginVerification::class)
                : $this->app->make(ClickSend::class)
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
