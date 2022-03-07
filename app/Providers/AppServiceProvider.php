<?php

namespace App\Providers;

use App\Modules\LoginVerification\LoginVerificationInterface;
use App\Modules\LoginVerification\SmsProviderFactory;
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


        $this->app->singleton(
            LoginVerificationInterface::class,
            fn () => SmsProviderFactory::create($this->app, config('sms.provider'))
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
