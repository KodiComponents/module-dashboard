<?php

namespace KodiCMS\Dashboard\Contracts;

interface WidgetType extends \KodiCMS\Widgets\Contracts\WidgetType
{
    /**
     * @return null|string
     */
    public function getIcon();
}
