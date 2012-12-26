Senso
-----

Usando Symfony2 e Doctrine, mi sono avvicinato alle annotation nel mondo PHP. Per gioco, ho cercato di implementarle a mia volta. Come esercizio, mi sono dato un MVC che mi ricordasse Symfony2 o Yii. Già che c'ero, ho anche cercato di fare un piccolissimo template engine. **Senso** è il risultato. Ecco cosa ho cercato di emulare:

* (Symfony2) la potenza di un comando lanciato da console
* (Symfony2) un template engine rudimentale per semplificare il codice
* (Symfony2) la struttura a bundle
* (bootstrap) un framework css
* (jquery) un framework javascript

Console
=======

Trovo che la console mi sia sempre stata di grande aiuto con Symfony2 quindi ho pensato di creare qualche comando che mi semplificasse la vita.

* $ ./routes
* $ ./show-routes

chiamate alternative:

* $ php console/routes.php
* $ php console/show-routes.php

Annotations
===========

Di Symfony 2ho apprezzato sopratutto le rotte gestibili attraverso le annotations.

-----------------
Class Annotations
-----------------

::

    * @BaseLayout(value="default")
    * @BaseRoute(value="/")
    * @Autore(value="Simone Gentili")

------------------
Method Annotations
------------------

::

    * @Route(value="contatti/contacts",name="contatti")
    * @View(path="contatti")

-----------------
Sample Controller
-----------------

::

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