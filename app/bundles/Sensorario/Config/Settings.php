<?php

namespace Sensorario\Config;

class Settings
{

    public static function getGlobals($locals)
    {
        return $locals + [
            'description' => 'RobbaGrossa',
            'title' => 'Vigliac',
            'variabile' => 'valore',
            'email' => 'sensorario@gmail.com',
            'cellulare' => '+39 349 2893030',
            'project_name' => 'Sensorario'
        ];
    }

}