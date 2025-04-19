<?php

namespace App\Providers;

use App\Repositories\LeaveRequestRepository;
use App\Repositories\LeaveRequestRepositoryInterface;
use App\Services\LeaveRequestService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LeaveRequestRepositoryInterface::class, LeaveRequestRepository::class);
        
        $this->app->bind(LeaveRequestService::class, function ($app) {
            return new LeaveRequestService($app->make(LeaveRequestRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
