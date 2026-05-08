<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
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
        Gate::define('admin-superadmin', function ($user) {
            \Illuminate\Support\Facades\Log::info('Checking gate for user: ' . $user->name . ' with role: ' . $user->role);
            return $user->isAdmin();
        });
    }
}
