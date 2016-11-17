<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        view()->composer(
            '*.*_list', 'App\Http\View\Permission'
        );
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('super', function ($app, $params = []) {
            return (new \App\Services\PermissionService(new \App\Repository\PermissionRepository(new \App\User())))->hasPermission($params);
        });
    }
}
