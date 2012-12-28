<?php

namespace Blog\Controllers;

use Sensorario\Config\Settings;

/**
 * @BaseRoute(value="/blog")
 */
class Dashboard
{

    /**
     * @Route(value="/dashboard",name="blog_dashboard");
     * @View(paht="dashboard")
     */
    public function sandro()
    {
        return Settings::getGlobals([]);
    }

}