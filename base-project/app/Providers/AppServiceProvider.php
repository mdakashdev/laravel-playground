<?php

namespace App\Providers;

use App\Experiments\Provider\PaymentGateway;
use App\Experiments\Provider\StripePayment;
use App\Services\ReportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentGateway::class,
            StripePayment::class
        );

        $this->app->bind(
            ReportService::class,
            ReportService::class
        );

//        $this->app->bind(
//            ReportService::class,
//            function () {
//                return new ReportService();
//            }
//        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
