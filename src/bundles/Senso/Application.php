<?php

namespace Senso;

class Application
{

    const ENV_DEV = 0;
    const ENV_PROD = 1;

    public function __construct($environment = self::ENV_PROD)
    {
        $rotteValide = require __DIR__ . '/../../../routing.php';
        if(in_array($_SERVER['REDIRECT_URL'], $rotteValide['routes'])) {
            $action = $rotteValide['actions'][$_SERVER['REDIRECT_URL']];
            $view = $rotteValide['views'][$action];
            $viewComposed = explode("/", $action)[0] . "/Views/{$view}.php";
            $class = "\\" . str_replace("/", "\\", explode("::", $action)[0]);
            $method = explode("::", $action)[1];
            $model = (new $class())->$method();
            if(!is_array($model)) {
                die("{$action}() deve restituire un array.");
            }
            $layout = file_get_contents(__DIR__ . "/../../../app/bundles/" . (explode("\\", $class)[1]) . "/Layouts/" . $rotteValide['layouts'][$action] . '.php');
          
            (new Render($model, $viewComposed, $environment, $layout));
        } else {
            echo "Rotta {$_SERVER['REDIRECT_URL']} non valida";
        }
    }

}