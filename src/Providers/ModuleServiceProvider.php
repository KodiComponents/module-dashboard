<?php

namespace KodiCMS\Dashboard\Providers;

use KodiCMS\Dashboard\WidgetManagerDashboard;
use KodiCMS\Dashboard\Contracts\WidgetManagerDashboard as WidgetManagerDashboardInterface;
use KodiCMS\Dashboard\WidgetType;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Users\Model\Permission;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('dashboard.manager')
            ->registerWidget(new WidgetType('profiler', 'cms::profiler.title', 'KodiCMS\Dashboard\Widget\Profiler', 'bar-chart'))
            ->registerWidget(new WidgetType('cache_button', 'dashboard::types.cache_button.title', 'KodiCMS\Dashboard\Widget\Cache', 'trash-o'))
            ->registerWidget(new WidgetType('mini_calendar', 'dashboard::types.mini_calendar.title', 'KodiCMS\Dashboard\Widget\MiniCalendar', 'calendar'))
            ->registerWidget(new WidgetType('kodicms_rss', 'dashboard::types.kodicms_rss.title', 'KodiCMS\Dashboard\Widget\KodiCMSRss', 'github-alt'));
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

        $this->app->singleton(WidgetManagerDashboardInterface::class, function () {
            return new WidgetManagerDashboard();
        });

        $this->app->alias(WidgetManagerDashboardInterface::class, 'dashboard.manager');
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
