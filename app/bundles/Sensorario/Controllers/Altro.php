<?php

namespace Sensorario\Controllers;

use Sensorario\Config\Settings;

/**
 * @BaseLayout(value="default")
 * @BaseRoute(value="/")
 * @Autore(value="Simone Gentili")
 */
class Altro
{

    /**
     * @Route(value="",name="homepage")
     * @View(path="index")
     */
    public function index()
    {
        /* @todo: Implementare questo view-source:http://twitter.github.com/bootstrap/examples/carousel.html */
        
        return Settings::getGlobals([]);
    }

}