<?php

namespace KodiCMS\Dashboard\Http\Controllers;

use Meta;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class DashboardController extends BackendController
{
    public function getIndex()
    {
        Meta::loadPackage('gridster');
        $this->setContent('dashboard');
        $this->breadcrumbs = false;
    }
}
