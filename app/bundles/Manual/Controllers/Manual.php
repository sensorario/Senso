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
        return Settings::getGlobals([
                    'title' => 'Manuale'
                ]);
    }

    /**
     * @Route(value="credits",name="manual_credits");
     * @View(value="credits")
     */
    public function credits()
    {
        return Settings::getGlobals([
                    'title' => 'Crediti'
                ]);
    }

}