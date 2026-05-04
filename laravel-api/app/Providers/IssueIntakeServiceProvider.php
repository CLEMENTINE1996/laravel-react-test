<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class IssueIntakeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\TicketRepositoryInterface::class,
            \App\Repositories\TicketRepository::class
        );

        $this->app->bind(
            \App\Services\Interfaces\AnalyzeTicketInterface::class,
            \App\Services\AnalyzeTicketService::class
        );
        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
