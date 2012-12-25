<?php

namespace Sensorario\Controllers;

use Sensorario\Config\Settings;

/**
 * @BaseLayout(value="default")
 * @BaseRoute(value="/")
 * @Autore(value="Simone Gentili")
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