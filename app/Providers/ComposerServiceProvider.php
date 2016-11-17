<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        view()->composer(
            'layouts.aside','App\Http\View\Menu'
        );
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
