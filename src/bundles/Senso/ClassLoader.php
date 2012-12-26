<?php

function __autoload($class)
{
    try {
        if(file_exists(str_replace("\\", "/", __DIR__ . '\\..\\' . $class . '.php'))) {
            require_once str_replace("\\", "/", __DIR__ . '\\..\\' . $class . '.php');
        } elseif(file_exists(str_replace("\\", "/", __DIR__ . '\\..\\src\\bundles\\' . $class . '.php'))) {
            require_once str_replace("\\", "/", __DIR__ . '\\..\\src\\bundles\\' . $class . '.php');
        } elseif(file_exists(str_replace("\\", "/", __DIR__ . '\\..\\..\\..\\app\\bundles\\' . $class . '.php'))) {
            require_once str_replace("\\", "/", __DIR__ . '\\..\\..\\..\\app\\bundles\\' . $class . '.php');
        } else {
            echo "Classe '$class' non trovata\n";
            echo "Cercata in:\n";
            echo "<br />" . str_replace("\\", "/", __DIR__ . '\\..\\' . $class . '.php');
            echo "<br />" . str_replace("\\", "/", __DIR__ . '\\..\\src\\bundles\\' . $class . '.php');
            echo "<br />" . str_replace("\\", "/", __DIR__ . '\\..\\app\\bundles\\' . $class . '.php');
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
