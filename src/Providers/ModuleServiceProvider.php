<?php

namespace KodiCMS\Dashboard\Providers;

use KodiCMS\Dashboard\WidgetManagerDashboard;
use KodiCMS\Dashboard\Contracts\WidgetManagerDashboard as WidgetManagerDashboardInterface;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Users\Model\Permission;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(WidgetManagerDashboardInterface::class, function () {
            return new WidgetManagerDashboard();
        });

        Permission::register('dashboard', 'dashboard', [
            'manage',
        ]);
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
