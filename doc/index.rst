Senso
-----

Usando Symfony2 e Doctrine, mi sono avvicinato alle annotation nel mondo PHP. Per gioco, ho cercato di implementarle a mia volta. Come esercizio, mi sono dato un MVC che mi ricordasse Symfony2 o Yii. Già che c'ero, ho anche cercato di fare un piccolissimo template engine. **Senso** è il risultato. Ecco cosa ho cercato di emulare:

* (Symfony2) la potenza di un comando lanciato da console
* (Symfony2) la struttura a bundle
* un template engine rudimentale per semplificare il codice

Vendors:

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

--------
./routes
--------

Questo comando richiama la classe "Senso\ControllersFinder();" che analizza tutte
le annotations sui controllers e genera le rotte. Una volta fatto il lavoro, viene
mostrato un output più o meno simile a questo:

::

    $ ./routes

	manual_homepage = Manual/Controllers/Manual::index(); 
	manual_credits = Manual/Controllers/Manual::credits(); 
	contatti = Sensorario/Controllers/Sensorario::contatti(); 
	homepage = Sensorario/Controllers/Altro::index(); 
	blog_dashboard = Blog/Controllers/Dashboard::sandro(); 

Sulla sinistra è possibile vedere il nome della rotta mentre sulla destra il metodo
che verrà invocato quando si aprirà quella particolare rotta.

--------
./show-routes
--------

Una volta trovate tutte le rotte verrà generato un file che le conterrà tutte.

::

    $ ./show-routes 

	manual_homepage	/manual
	manual_credits	/manualcredits
	contatti	/contatti/contacts
	homepage	/
	blog_dashboard	/blog/dashboard

Da questo momento in poi non avrà più importanza in quale controller si trovi
una action: l'importante sarà indicare la rotta ed il proprio nome sulle proprie
annotations.

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

Template Engine
===============

---------
Variabili
---------

Le variabili possono essere scritte in questo modo:

    {{nome_variabile}}

che equivale a 

    <?php echo $nome_variabile; ?>

Per fare questo dobbiamo assicurarci di aver passato alla view il nome della 
variabile e il suo valore.

::

    /**
     * @Route(value="contatti/contacts",name="contatti")
     * @View(path="contatti")
     */
    public function contatti()
    {
        return Settings::getGlobals([
            'nome_variabile' => 'valore'
        ]);
    }

----
Link
----

Un link, all'interno della view, può essere scritto in questo modo:

    {path{contatti}}

Questo a patto che esita una rotta con quel nome. Non è importante di dove si 
trovi il codice ma che nelle annotation sia presente la annotation @Route
e che come parametro abbia name="contatti" proprio come indicato qui sotto:

::

    /**
     * @Route(value="contatti/contacts",name="contatti")
     * @View(path="contatti")
     */
    public function contatti()
    {
        return Settings::getGlobals([]);
    }
