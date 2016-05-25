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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
		Permission::register('dashboard', 'dashboard', [
            'manage',
        ]);
    }

    public function contextBackend()
    {
        \Navigation::setFromArray([
            [
                'id'     => 'dashboard',
                'title'    => 'dashboard::core.title.dashboard',
                'icon'     => 'dashboard',
                'url'      => route('backend.dashboard'),
                'priority' => -1000,
            ],
        ]);
    }
}
