<?php

namespace Manual\Controllers;

use Sensorario\Config\Settings;

/**
 * @BaseRoute(value="/manual");
 * @BaseLayout(value="default");
 */
class Manual
{

    /**
     * @Route(value="",name="manual_homepage");
     * @View(value="index")
     */
    public function index()
    {
        return Settings::getGlobals([]);
    }

}