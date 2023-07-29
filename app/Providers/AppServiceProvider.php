<?php

namespace App\Providers;

use App\DataProviders\DataProviderFactory;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
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
        $this->app->singleton(TransactionRepository::class, function ($app) {
            return new TransactionRepository();
        });

        $this->app->singleton(TransactionService::class, function ($app) {
            return new TransactionService($app->make(TransactionRepository::class));
        });
    }
}
