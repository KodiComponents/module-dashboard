<?php

namespace KodiCMS\Dashboard;

class WidgetType extends \KodiCMS\Widgets\WidgetType implements \KodiCMS\Dashboard\Contracts\WidgetType
{
    /**
     * @var null|string
     */
    protected $icon;

    /**
     * WidgetType constructor.
     *
     * @param string $type
     * @param string $title
     * @param string $class
     * @param string $icon
     */
    public function __construct($type, $title, $class, $icon = null)
    {
        parent::__construct($type, $title, $class);

        $this->icon = $icon;
    }

    /**
     * @return null|string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->getType(),
            'title' => $this->getTitle(),
            'class' => $this->getClass(),
            'icon' => $this->getIcon(),
        ];
    }
}