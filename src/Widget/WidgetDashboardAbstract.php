<?php

namespace KodiCMS\Dashboard\Widget;

use Illuminate\Support\Collection;
use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\Dashboard\Contracts\WidgetManagerDashboard;
use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Traits\WidgetRender;
use KodiCMS\Widgets\Widget\WidgetAbstract as WidgetAbstract;

abstract class WidgetDashboardAbstract extends WidgetAbstract implements WidgetDashboard, WidgetRenderable
{
    use WidgetRender;

    /**
     * @var bool
     */
    protected $updateSettingsPage = false;

    /**
     * @var bool
     */
    protected $hasSettingsPage = false;

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var array
     */
    protected $mediaPackages = [];

    /**
     * @var array
     */
    protected $size = [
        'x' => 2,
        'y' => 1,
        'max_size' => [2, 1],
        'min_size' => [2, 1],
    ];

    /**
     * @var WidgetManagerDashboard
     */
    protected $widgetManager;

    /**
     * Decorator constructor.
     *
     * @param WidgetManagerDashboard $widgetManager
     */
    public function __construct(WidgetManagerDashboard $widgetManager)
    {
        $this->relatedWidgets = new Collection;
        $this->widgetManager = $widgetManager;
        $this->type = $this->widgetManager->getTypeByClassName(get_called_class());
    }

    /**
     * @return mixed
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * @return bool
     */
    public function isUpdateSettingsPage()
    {
        return $this->updateSettingsPage;
    }

    /**
     * @return bool
     */
    public function hasSettingsPage()
    {
        return $this->hasSettingsPage;
    }

    /**
     * @return array
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return array
     */
    public function getMediaPackages()
    {
        return $this->mediaPackages;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_except(parent::toArray(), ['name', 'description']) + [
            'size' => $this->getSize(),
            'multiple' => $this->isMultiple(),
            'packages' => $this->getMediaPackages(),
            'updateSettingsPage' => $this->isUpdateSettingsPage()
        ];
    }
}
