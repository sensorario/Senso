<?php

namespace Senso;

use \ReflectionClass;
use \ReflectionMethod;


class ControllersFinder
{

    private $nonFolders = ['.', '..'];

    private $bundleDirectory;

    private $itemController;

    private $controllersDirHandle;

    private $itemDirectory;

    private $bundleDirHandle;

    protected function setBundleDirectory()
    {
        $this->bundleDirectory = __DIR__ . '/../../../app/bundles';

    }

    protected function getHandleOfBundleDirectory()
    {
        $this->bundleDirHandle = opendir($this->bundleDirectory);

    }

    protected function isValidDirectory($dir)
    {
        return !in_array($dir, $this->nonFolders);

    }

    protected function getCurrentControllersFolder()
    {
        return "{$this->bundleDirectory}/{$this->itemDirectory}/Controllers";

    }

    protected function fetchController()
    {
        return $this->itemController = readdir($this->controllersDirHandle);

    }

    protected function handleControllersDirectory()
    {
        $this->controllersDirHandle = opendir($this->getCurrentControllersFolder());

    }

    public function fetchDirectory()
    {
        return $this->itemDirectory = readdir($this->bundleDirHandle);

    }

    protected function isValidItemDirectory()
    {
        return $this->isValidDirectory($this->itemDirectory);

    }

    protected function isValidItemController()
    {
        return $this->isValidDirectory($this->itemController);

    }

    protected function existsItemDirectory()
    {
        return file_exists($this->getCurrentControllersFolder());

    }

    public function __construct()
    {
        for ($i = 0; $i <= 40; $i++)
            echo "\n";

        echo "\n\nI am console routes generator";

        $all = [];

        $this->setBundleDirectory();
        $this->getHandleOfBundleDirectory();
        while($this->fetchDirectory()){
            if ($this->isValidItemDirectory()) {
                if ($this->existsItemDirectory()) {
                    $this->handleControllersDirectory();
                    while($this->fetchController()){
                        if ($this->isValidItemController()) {

                            /* Mostro il controller che devo caricare */
                            //echo "\n\n\tController: {$this->getCurrentControllersFolder()}/{$this->itemController}";
                            //$classPath = "{$this->getCurrentControllersFolder()}/{$this->itemController}";
                            $className = explode(".", "{$this->itemDirectory}/Controllers/{$this->itemController}")[0];
                            //echo "\n\tLoad: app/bundles/{$className}.php";
                            require_once "app/bundles/{$className}.php";

                            /* Recupero i commenti di questo controller */
                            $commenti = (new ReflectionClass(str_replace("/", "\\", $className)))->getDocComment();
                            preg_match_all("/\@(.*)\((.*)\)/", $commenti, $matches);

                            //echo "\n\tLa classe " . (str_replace("/", "\\", $className)) . " ha questi metodi:";

                            /* Ogni singola annotation. */
                            foreach ($matches[0] as $annotation) {
                                preg_match_all("/(\@)(.*)(\()/", $annotation, $matchesItemKey);
                                preg_match_all("/(\()(.*)(\))/", $annotation, $matchesItemValue);

                                $value = explode("=", $matchesItemValue[2][0])[1];
                                $valueRefactored = str_replace('"', "", $value);
                                $all[$className][$matchesItemKey[2][0]] = $valueRefactored;

                                /* Caricare tutti quanti i metodi e cercare le loro annotations */
                                $iMetodiDiQuestoController = (new ReflectionClass(str_replace("/", "\\", $className)))->getMethods();

                                /* Per ogni metodo */
                                foreach ($iMetodiDiQuestoController as $method) {
                                    $commentoDelMetodo = (new ReflectionMethod(str_replace("/", "\\", $className), $method->name))->getDocComment();
                                    preg_match_all("/\@(.*)\((.*)\)/", $commentoDelMetodo, $matchesDelCommentoDelMetodo);

                                    /* Ogni singola annotation. */
                                    foreach ($matchesDelCommentoDelMetodo[0] as $annotation) {
                                        preg_match_all("/(\@)(.*)(\()/", $annotation, $matchesMethodItemKey);
                                        preg_match_all("/(\()(.*)(\))/", $annotation, $matchesMethodItemValue);

                                        if (strpos($matchesMethodItemValue[2][0], ",")) {
                                            foreach (explode(",", $matchesMethodItemValue[2][0]) as $params) {
                                                if (explode("=", $params)[0] == 'value') {
                                                    $valueRefactored = str_replace('"', "", explode("=", $params)[1]);
                                                    $all[$className]['methods'][$method->name][$matchesMethodItemKey[2][0]] = $valueRefactored;
                                                }
                                                if (explode("=", $params)[0] == 'name') {
                                                    $valueRefactored = str_replace('"', "", explode("=", $params)[1]);
                                                    $all[$className]['names'][$method->name] = $valueRefactored;
                                                }
                                            }
                                        } else {
                                            $value = explode("=", $matchesMethodItemValue[2][0])[1];
                                            $valueRefactored = str_replace('"', "", $value);
                                            $all[$className]['methods'][$method->name][$matchesMethodItemKey[2][0]] = $valueRefactored;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        echo "\n\n\n";
        //print_r($all);

        /* Ricreo un array con solo le rotte */
        $rotte = [];
        $viste = [];
        $nomi = [];
        $layouts = [];
        foreach ($all as $controllerClass => $bundles) {
            foreach ($bundles['methods'] as $key => $value) {
                foreach ($value as $method => $methodValue) {
                    if ($method == "Route") {
                        //echo "\n\t{$bundles['BaseRoute']}{$methodValue} = {$controllerClass}::{$key}();";
                        $rotte["{$bundles['BaseRoute']}{$methodValue}"] = "{$controllerClass}::{$key}";
                    }
                    if ($method == "View") {
                        //echo "\n\t{$controllerClass}::{$key} = {$methodValue}();";
                        $viste["{$controllerClass}::{$key}"] = "{$methodValue}";
                    }
                    if (isset($bundles['BaseLayout'])) {
                        $layouts["{$controllerClass}::{$key}"] = "{$bundles['BaseLayout']}";
                    }
                }
            }
            if (isset($bundles['names'])) {
                foreach ($bundles['names'] as $key => $value) {
                    echo "\n\t\033[01;32m{$value} = {$controllerClass}::{$key}(); \033[0m";
                    $nomi["{$value}"] = "{$controllerClass}::{$key}";
                }
            }
        }

        $file = "<?php return ['routes'=>[\n";
        foreach ($rotte as $rotta => $action) {
            $file .= "\t'{$action}'=>'{$rotta}',\n";
        }
        $file .= "],'actions'=>[\n";
        foreach ($rotte as $rotta => $action) {
            $file .= "\t'{$rotta}'=>'{$action}',\n";
        }
        $file .= "],'views'=>[\n";
        foreach ($viste as $rotta => $action) {
            $file .= "\t'{$rotta}'=>'{$action}',\n";
        }
        $file .= "],'names'=>[\n";
        foreach ($nomi as $rotta => $action) {
            $file .= "\t'{$rotta}'=>'{$action}',\n";
        }
        $file .= "],'layouts'=>[\n";
        foreach ($layouts as $rotta => $action) {
            $file .= "\t'{$rotta}'=>'{$action}',\n";
        }
        $file .= ']];';

        $handle = fopen("calcolati/routing.php", "w+");
        fwrite($handle, $file);
        fclose($handle);

        echo "\n\n\n";

    }

}