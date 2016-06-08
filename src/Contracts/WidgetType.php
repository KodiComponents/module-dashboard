<?php

namespace KodiCMS\Dashboard\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface WidgetType extends Arrayable
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getClass();

    /**
     * @return null|string
     */
    public function getIcon();
}
