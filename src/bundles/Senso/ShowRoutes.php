<?php

namespace Senso;

class ShowRoutes
{

    public function __construct()
    {
        $rotte = include 'calcolati/routing.php';
        foreach ($rotte['names'] as $nomeRotta => $rotta) {

            echo "\n\t\033[01;32m{$nomeRotta}\t{$rotte['routes'][$rotta]}\033[0m";
        }
        echo "\n\n";
    }

}