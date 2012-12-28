La guida di Senso
=================

.. header::

   .. oddeven::

      .. class:: headertable

      +---+---------------------+----------------+
      |   |.. class:: centered  |.. class:: right|
      |   |                     |                |
      |   |Section ###Section###|Page ###Page### |
      +---+---------------------+----------------+

      .. class:: headertable

      +---------------+---------------------+---+
      |               |.. class:: centered  |   |
      |               |                     |   |
      |Page ###Page###|Section ###Section###|   |
      +---------------+---------------------+---+

.. contents::

.. section-numbering::

.. raw:: pdf

   PageBreak oneColumn

Introduzione
============

Per gioco, ho cercato di implementarle le annotations come ho visto fare su altri
frameworks. Come esercizio, mi sono dato un MVC che mi ricordasse Symfony2 o Yii.
Già che c'ero, ho anche cercato di fare un piccolissimo template engine. 
**Senso** è il risultato. 

Questo MVC serve più che altro per apprendere cose nuove con PHP5.4 ed imparare
ad usare anche rst2pdf per scrivere la mia documentazione.

-----------------
La documentazione
-----------------

Questa documentazione è compilabile con rst2pdf.

-----
Cache
-----

Quando il template engine renderizza le pagine, viene generata una cache con il 
codice già compilato. Questo serve per non rielaborare una pagina ogni volta. Il
sistema controlla se esiste il template richiesto e se non esiste lo genera e
poi lo mostra a video. Quando invece esiste già, non si cura del template engine
e mostra direttalmente il codice già compilato in precedenza.

La stessa coda accade con le rotte: vengono generate e calcolate usando la console
e non ad ogni richiesta.

Configurazione
==============

Per essere utilizzato **Senso** richiede qualche piccola configazione. Per esempio
dobbiamo impostare il nostro VirtualHost nel file **/etc/apache2/sites-available/Senso**.

-----------
VirtualHost
-----------

Ecco il contenuto del mio VirtualHost per poter usare Senso.

::

    <VirtualHost *:80>

            ServerAdmin sensorario@gmail.com
            ServerName senso
            ServerAlias senso.local.com 
            DocumentRoot /var/www/Senso
            DirectoryIndex index.php

            <Directory /var/www/Senso>
                    AllowOverride all
            </Directory>

            ErrorLog ${APACHE_LOG_DIR}/senso.error.log

            # Possible values include: debug, info, notice, warn, error, crit,
            # alert, emerg.

            LogLevel notice

            CustomLog ${APACHE_LOG_DIR}/senso.access.log combined

    </VirtualHost>


Console
=======

Trovo che la console mi sia sempre stata di grande aiuto con Symfony2 quindi ho
pensato di creare qualche comando che mi semplificasse la vita.

* $ ./docgen
* $ ./routes
* $ ./show-routes

chiamate alternative:

* $ rst2pdf doc/index.rst -o doc/manual.pdf
* $ php console/routes.php
* $ php console/show-routes.php

--------
./docgen
--------

Docgen è un comando scritto per convertire questa documentazione in un file pdf.

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

-------------
./show-routes
-------------

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

Template Engine (Template)
==========================

Questo engine è più potente perchè consente di estendere un template dal template
stesso. Mentre in Render bisogna indicare il layout al quale applicare una data 
view, in Template basta usare una parola chiave.

Con questo engine non serve usare la annotation @BaseLayout.

::

    echo (new Template($viewPaht, $bundle, $model))
            ->getRenderedCache();

-------
extends
-------

Per indicare il layout che si vuole estendere, bisogna usare la parola chiave

::

    {extends 'nome_template'}

-------
blocchi
-------

Dentro al layout ed alle view si possono indicare i blocchi in questo modo:

::

    {start nome_blocco}
        ...
    {end nome_blocco}



Template Engine (Render)
========================

::

    echo (new Render($model, $viewPaht, $environment, $layout))
            ->getRenderedCache();

------------
Il ciclo for
------------

E' possibile usare il ciclo for.
        
    {for 6 times}!{endfor}

Questo stamperà "!!!!!!"

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

Configurazione applicazione
===========================

---------------------
calcolati/routing.php
---------------------

Questo è un esempio di come potrebbe essere costruito un file delle rotte. Come
si può vedere è possibile risalire ad una rotta da una action o viceversa. Oppure
possiamo ricavare la action partendo dal nome della rotta. Ultimo ma non meno
importante, possiamo anche ricavare il layout di base da una determinata acion.

Questo file non va assolutamente scritto a mano ma va creato con il comando **./routes**.

::

    <?php return ['routes'=>[
	'Manual/Controllers/Manual::index'=>'/manual',
	'Manual/Controllers/Manual::credits'=>'/manualcredits',
	'Sensorario/Controllers/Sensorario::contatti'=>'/contatti/contacts',
	'Sensorario/Controllers/Altro::index'=>'/',
	'Blog/Controllers/Dashboard::sandro'=>'/blog/dashboard',
    ],'actions'=>[
	'/manual'=>'Manual/Controllers/Manual::index',
	'/manualcredits'=>'Manual/Controllers/Manual::credits',
	'/contatti/contacts'=>'Sensorario/Controllers/Sensorario::contatti',
	'/'=>'Sensorario/Controllers/Altro::index',
	'/blog/dashboard'=>'Blog/Controllers/Dashboard::sandro',
    ],'views'=>[
	'Manual/Controllers/Manual::index'=>'index',
	'Manual/Controllers/Manual::credits'=>'credits',
	'Sensorario/Controllers/Sensorario::contatti'=>'contatti',
	'Sensorario/Controllers/Altro::index'=>'index',
	'Blog/Controllers/Dashboard::sandro'=>'dashboard',
    ],'names'=>[
	'manual_homepage'=>'Manual/Controllers/Manual::index',
	'manual_credits'=>'Manual/Controllers/Manual::credits',
	'contatti'=>'Sensorario/Controllers/Sensorario::contatti',
	'homepage'=>'Sensorario/Controllers/Altro::index',
	'blog_dashboard'=>'Blog/Controllers/Dashboard::sandro',
    ],'layouts'=>[
	'Manual/Controllers/Manual::index'=>'default',
	'Manual/Controllers/Manual::credits'=>'default',
	'Sensorario/Controllers/Sensorario::contatti'=>'default',
	'Sensorario/Controllers/Altro::index'=>'default',
	'Blog/Controllers/Dashboard::sandro'=>'default',
    ]];