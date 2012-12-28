<?php

namespace Sensorario\Controllers;

use Sensorario\Config\Settings;

/**
 * @BaseRoute(value="/")
 */
class Sensorario
{

    /**
     * @Route(value="contatti/contacts",name="contatti")
     * @View(path="contatti")
     */
    public function contatti()
    {
        return Settings::getGlobals([
                    'title' => 'Pagina dei contatti'
                ]);
    }

}