<?php

namespace KodiCMS\Dashboard\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface WidgetDashboard extends Arrayable
{
    /**
     * @return bool
     */
    public function isMultiple();

    /**
     * @return bool
     */
    public function isUpdateSettingsPage();

    /**
     * @return bool
     */
    public function hasSettingsPage();

    /**
     * @return array
     */
    public function getSize();

    /**
     * @return array
     */
    public function getMediaPackages();
}
