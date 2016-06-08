<?php

namespace KodiCMS\Dashboard\Http\Controllers\API;

use PackageManager;
use KodiCMS\Dashboard\Contracts\WidgetManagerDashboard;
use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\Dashboard\WidgetRenderDashboardHTML;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Widgets\Engine\WidgetRenderSettingsHTML;

class DashboardController extends Controller
{
    /**
     * @var array
     */
    protected $permissions = [
        'getWidgetSettings' => 'backend.dashboard.manage',
        'putWidget'         => 'backend.dashboard.manage',
        'postWidget'        => 'backend.dashboard.manage',
        'deleteWidget'      => 'backend.dashboard.manage',
    ];

    /**
     * @param WidgetManagerDashboard $widgetManager
     */
    public function getAvailableWidgets(WidgetManagerDashboard $widgetManager)
    {
        $widgets = $widgetManager->getAvailableWidgets()->toArray();
        $this->setContent($widgets);
    }

    /**
     * @param WidgetManagerDashboard $widgetManager
     */
    public function getWidgetList(WidgetManagerDashboard $widgetManager)
    {
        $this->setContent(
            $widgetManager->getWidgets()
        );
    }

    /**
     * @param WidgetManagerDashboard $widgetManager
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public function putWidget(WidgetManagerDashboard $widgetManager)
    {
        $widgetType = $this->getRequiredParameter('widget_type');

        $widget = $widgetManager->addWidget($widgetType);

        $this->setContent([
            'widget' => $widget->toArray(),
            'template' => (string) (new WidgetRenderDashboardHTML($widget))->render()
        ]);
    }

    /**
     * @param WidgetManagerDashboard $widgetManager
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public function getWidgetSettings(WidgetManagerDashboard $widgetManager)
    {
        $widgetId = $this->getRequiredParameter('id');
        $widget = $widgetManager->getWidgetById($widgetId);

        $this->setContent([
            'widget' => $widget->toArray(),
            'template' => (string) (new WidgetRenderSettingsHTML($widget))->render()
        ]);
    }

    /**
     * @param WidgetManagerDashboard $widgetManager
     */
    public function deleteWidget(WidgetManagerDashboard $widgetManager)
    {
        $widgetId = $this->getRequiredParameter('id');
        $widgetManager->deleteWidgetById($widgetId);
    }

    /**
     * @param WidgetManagerDashboard $widgetManager
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public function postWidget(WidgetManagerDashboard $widgetManager)
    {
        $widgetId = $this->getRequiredParameter('id');
        $settings = $this->getParameter('settings', []);

        $widget = $widgetManager->updateWidget($widgetId, $settings);

        if ($widget instanceof WidgetDashboard) {
            $this->setContent([
                'widget' => $widget->toArray(),
                'template' => (string) (new WidgetRenderSettingsHTML($widget))->render()
            ]);
        }
    }
}
