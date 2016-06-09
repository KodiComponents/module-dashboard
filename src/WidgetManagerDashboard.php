<?php

namespace KodiCMS\Dashboard;

use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\Dashboard\Contracts\WidgetManagerDashboard as WidgetManagerDashboardInterface;
use KodiCMS\Dashboard\Contracts\WidgetType;
use KodiCMS\Users\Model\UserMeta;
use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Manager\WidgetManager;

class WidgetManagerDashboard extends WidgetManager implements WidgetManagerDashboardInterface
{

    const WIDGET_BLOCKS_KEY   = 'dashboard';
    const WIDGET_SETTINGS_KEY = 'dashboard_widget_settings';

    /**
     * @param \KodiCMS\Widgets\Contracts\WidgetType $type
     *
     * @return $this
     * @throws \Exception
     */
    public function registerWidget(\KodiCMS\Widgets\Contracts\WidgetType $type)
    {
        if (! ($type instanceof WidgetType)) {
            throw new \Exception('Widget type must be instance of [KodiCMS\Dashboard\Contracts\WidgetType]');
        }

        return parent::registerWidget($type);
    }

    /**
     * @return string
     */
    public function getWidgets()
    {
        $widgetsPosition = UserMeta::get(static::WIDGET_BLOCKS_KEY, []);
        $widgetSettings  = UserMeta::get(static::WIDGET_SETTINGS_KEY, []);

        $widgets = [];
        foreach ($widgetsPosition as $i => $data) {
            if (! isset($data['id'])) {
                unset($widgetsPosition[$i]);
                continue;
            }

            $widget = array_get($widgetSettings, $data['id']);

            if (is_array($widget) and is_null($widget = $this->toWidget($widget))) {
                unset($widgetsPosition[$i]);
                continue;
            }

            $widgetsPosition[$i]['max-size'] = array_get($widget->getSize(), 'max_size');
            $widgetsPosition[$i]['min-size'] = array_get($widget->getSize(), 'min_size');
            
            if (! ($widget instanceof WidgetDashboard)) {
                unset($widgetsPosition[$i]);
                continue;
            }

            $widgets[$i] = [
                'data' => array_except($widgetsPosition[$i], 'id'),
                'id' => $data['id'],
                'widget' => $widget->toArray(),
                'template' => (string) (new \KodiCMS\Dashboard\WidgetRenderDashboardHTML($widget))->render(),
            ];
        }

        return $widgets;
    }

    /**
     * @return array
     */
    public function getAvailableWidgets()
    {
        $widgetSettings = $this->getSettings();

        $placedWidgetsTypes = [];
        foreach ($widgetSettings as $widget) {
            $widget = $this->toWidget($widget);

            if ($widget instanceof WidgetDashboard) {
                $placedWidgetsTypes[$widget->getType()] = $widget->isMultiple();
            }
        }

        return $this->types->filter(function (WidgetType $type) use ($placedWidgetsTypes) {
            return array_get($placedWidgetsTypes, $type->getType()) !== false;
        });
    }

    /**
     * @return array
     */
    public function getAvailableTypes()
    {
        return $this->types;
    }

    /**
     * @param array $data
     *
     * @return \KodiCMS\Widgets\Contracts\Widget|null
     */
    public function toWidget(array $data)
    {
        /**
         * @var string $type
         * @var string $id
         * @var array $settings
         * @var array $parameters
         */
        extract($data);

        $widget = $this->makeWidget($type, $type, null, $settings);

        $widget->setId($id);

        if (is_array($parameters)) {
            $widget->setParameters($parameters);
        }

        return $widget;
    }

    /**
     * @param int $pageId
     *
     * @return \Illuminate\Support\Collection
     */
    public function getWidgetsByPage($pageId)
    {

    }

    /**
     * @param int $pageId
     *
     * @return array
     */
    public function getPageWidgetBlocks($pageId)
    {

    }

    /**
     * @param string $widgetId
     * @param int|null $userId
     *
     * @return WidgetDashboard|null
     */
    public function getWidgetById($widgetId, $userId = null)
    {
        $widget = array_get($this->getSettings($userId), $widgetId);

        if (is_null($widget = $this->toWidget($widget)) or ! ($widget instanceof WidgetDashboard)) {
            return;
        }

        return $widget;
    }

    /**
     * @param            $type
     * @param array|null $settings
     * @param null|int $userId
     *
     * @return WidgetDashboard|null
     */
    public function addWidget($type, array $settings = null, $userId = null)
    {
        $widgetSettings = $this->getSettings($userId);

        $widget = $this->makeWidget($type, $type, null, $settings);

        if (is_null($widget)) {
            return false;
        }

        $widget->setId(uniqid());

        $widgetSettings[$widget->getId()] = $widget->toArray();

        $this->saveSettings($widgetSettings, $userId);

        return $widget;
    }

    /**
     * @param string $widgetId
     * @param array $settings
     * @param null|int $userId
     *
     * @return WidgetDashboard|null
     */
    public function updateWidget($widgetId, array $settings, $userId = null)
    {
        $widgetSettings = $this->getSettings($userId);
        $widget         = array_get($widgetSettings, $widgetId);

        if (is_array($widget) and is_null($widget = $this->toWidget($widget))) {
            return;
        }

        $widget->setSettings($settings);

        $widgetSettings[$widgetId] = $widget->toArray();
        $this->saveSettings($widgetSettings, $userId);

        return $widget;
    }

    /**
     * @param string $widgetId
     * @param null|int $userId
     *
     * @return bool
     */
    public function deleteWidgetById($widgetId, $userId = null)
    {
        $widgetSettings = $this->getSettings($userId);

        unset($widgetSettings[$widgetId]);

        $this->saveSettings($widgetSettings, $userId);

        return true;
    }

    /**
     * @param string $widgetId
     * @param string $column
     * @param null|int $userId
     *
     * @return bool
     */
    public function moveWidget($widgetId, $column, $userId = null)
    {
        $widgetSettings = $this->getSettings($userId);
        $found          = false;

        foreach ($widgetSettings as $data) {
            foreach ($ids as $i => $id) {
                if ($id = $widgetId and $column != $column) {
                    $found = true;
                    unset($blocks[$column][$i]);
                    break;
                }
            }
        }

        if ($found === true) {
            $blocks[$column][] = $widgetId;
            UserMeta::set(self::WIDGET_BLOCKS_KEY, $blocks, $userId);

            return true;
        }

        return false;
    }

    /**
     * @param null|int $userId
     *
     * @return array
     */
    protected function getSettings($userId = null)
    {
        return UserMeta::get(self::WIDGET_SETTINGS_KEY, [], $userId);
    }

    /**
     * @param array $settings
     * @param null|int $userId
     */
    protected function saveSettings(array $settings, $userId = null)
    {
        UserMeta::set(self::WIDGET_SETTINGS_KEY, $settings, $userId);
    }

    /**
     * @param string $type
     * @param string $name
     * @param string|null $description
     * @param array|null $settings
     *
     * @return Widget|null
     */
    public function makeWidget($type, $name, $description = null, array $settings = null)
    {
        $class = $this->getClassNameByType($type);

        if (! $this->isWidgetable($class)) {
            return;
        }

        $widget = app($class);

        if (! is_null($settings)) {
            $widget->setSettings(array_except($settings, 'max-size', 'min-size'));
        }

        return $widget;
    }
}
