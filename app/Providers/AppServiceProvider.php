<?php

namespace App\Providers;

use App\Modules\SearchEngine\DatabaseSearchEngine;
use App\Modules\SearchEngine\SearchEngineInterface;
use App\Modules\SMS\ClickSend\ClickSend;
use App\Modules\SMS\NullSms;
use App\Modules\SMS\SMSInterface;
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
            SMSInterface::class,
            fn () => $this->app->make(NullSms::class)
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
