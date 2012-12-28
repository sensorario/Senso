<?php

namespace Senso;

use Senso\Engines\Render\Render;
use Senso\Engines\Template\Template;

class Application
{

    const ENV_DEV = 0;
    const ENV_PROD = 1;
    const ENGINE_RENDER = 100;
    const ENGINE_TEMPLATE = 101;

    public function __construct($environment = self::ENV_PROD, $engine = self::ENGINE_TEMPLATE)
    {
        $rotteValide = require __DIR__ . '/../../../calcolati/routing.php';
        if(in_array($_SERVER['REDIRECT_URL'], $rotteValide['routes'])) {
            $action = $rotteValide['actions'][$_SERVER['REDIRECT_URL']];
            $view = $rotteValide['views'][$action];
            $viewPaht = explode("/", $action)[0] . "/Views/{$view}.php";
            $class = "\\" . str_replace("/", "\\", explode("::", $action)[0]);
            $method = explode("::", $action)[1];
            $model = (new $class())->$method();
            if(!is_array($model)) {
                die("{$action}() deve restituire un array.");
            }
            $bundle = (explode("\\", $class)[1]);
            echo (new Template($viewPaht, $bundle))
                    ->model($model)
                    ->getRenderedCache();
        } else {
            echo "Rotta {$_SERVER['REDIRECT_URL']} non valida";
        }
    }

}