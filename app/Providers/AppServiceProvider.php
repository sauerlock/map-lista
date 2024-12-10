<?php

namespace App\Providers;

use App\Services\CEPValidationService;
use App\Services\GoogleGeocodingService;
use App\Services\CPFValidationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registra o GoogleGeocodingService como singleton
        $this->app->singleton(GoogleGeocodingService::class, function ($app) {
            return new GoogleGeocodingService();
        });

        // Registra o CPFValidationService como singleton
        $this->app->singleton(CPFValidationService::class, function ($app) {
            return new CPFValidationService();
        });

        $this->app->singleton(CEPValidationService::class, function ($app) {
            return new CEPValidationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pode adicionar configurações adicionais aqui se necessário
    }
}
