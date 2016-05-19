<?php

namespace KodiCMS\Dashboard\Providers;

use KodiCMS\Dashboard\WidgetManagerDashboard;
use KodiCMS\Dashboard\Contracts\WidgetManagerDashboard as WidgetManagerDashboardInterface;
use KodiCMS\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(WidgetManagerDashboardInterface::class, function () {
            return new WidgetManagerDashboard();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
