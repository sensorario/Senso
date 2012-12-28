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
            $layout = file_get_contents(__DIR__ . "/../../../app/bundles/" . $bundle . "/Layouts/" . $rotteValide['layouts'][$action] . '.php');
            switch ($engine) {
                case self::ENGINE_RENDER:
                    echo (new Render($model, $viewPaht, $environment, $layout))
                            ->getRenderedCache();
                    break;
                case self::ENGINE_TEMPLATE:
                    echo (new Template($viewPaht, $bundle))
                            ->model($model)
                            ->getRenderedCache();
                    break;
            }
        } else {
            echo "Rotta {$_SERVER['REDIRECT_URL']} non valida";
        }
    }

}