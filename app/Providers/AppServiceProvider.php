<?php

namespace App\Providers;

use App\Services\Contracts\ReportServiceInterface;
use App\Services\Contracts\ServiceInterface;
use App\Services\ReportService;
use App\Services\Support\Service;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        ServiceInterface::class => Service::class,
        ReportServiceInterface::class => ReportService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Paginator::useTailwind();
    }
}
