<?php

namespace KodiCMS\Dashboard;

class WidgetType implements \KodiCMS\Dashboard\Contracts\WidgetType
{

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $class;

    /**
     * @var null|string
     */
    private $icon;

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
        $this->type  = $type;
        $this->title = $title;
        $this->class = $class;
        $this->icon  = $icon;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return strpos($this->title, '::') !== false ? trans($this->title) : $this->title;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
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