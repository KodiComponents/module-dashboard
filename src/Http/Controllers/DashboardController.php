<?php

namespace KodiCMS\Dashboard\Http\Controllers;

use Meta;
use KodiCMS\Dashboard\Contracts\WidgetManagerDashboard;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class DashboardController extends BackendController
{
    /**
     * @param WidgetManagerDashboard $widgetManager
     */
    public function getIndex(WidgetManagerDashboard $widgetManager)
    {
        Meta::loadPackage('gridster');

        $widgets = $widgetManager->getWidgets();
        
        $this->setContent('dashboard', compact('widgets'));
    }

    /**
     * @param WidgetManagerDashboard $widgetManager
     *
     * @return \View
     */
    public function getWidgetList(WidgetManagerDashboard $widgetManager)
    {
        $widgets = $widgetManager->getAvailableWidgets();

        return $this->setContent('partials.widgets', compact('widgets'));
    }
}
