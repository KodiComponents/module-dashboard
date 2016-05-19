<?php

namespace KodiCMS\Dashboard\Contracts;

interface WidgetManagerDashboard
{
    /**
     * @param string   $widgetId
     * @param int|null $userId
     *
     * @return WidgetDashboard|null
     */
    public function getWidgetById($widgetId, $userId = null);

    /**
     * @param            $type
     * @param array|null $settings
     * @param null|int   $userId
     *
     * @return WidgetDashboard|null
     */
    public function addWidget($type, array $settings = null, $userId = null);

    /**
     * @param string   $widgetId
     * @param array    $settings
     * @param null|int $userId
     *
     * @return WidgetDashboard|null
     */
    public function updateWidget($widgetId, array $settings, $userId = null);

    /**
     * @param string   $widgetId
     * @param null|int $userId
     *
     * @return bool
     */
    public function deleteWidgetById($widgetId, $userId = null);

    /**
     * @param string   $widgetId
     * @param string   $column
     * @param null|int $userId
     *
     * @return bool
     */
    public function moveWidget($widgetId, $column, $userId = null);
}
