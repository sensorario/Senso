<?php

namespace Senso\Engines\Base;

abstract class Engine
{

    protected $cache;
    protected $cacheFileName;
    protected $sourceFileName;
    protected $environment;

    public function getRenderedCache()
    {
        return $this->cache;
    }

    protected function findIfExistsSourceFile()
    {
        if(!file_exists($this->sourceFileName)) {
            die("Il file sorgente {$this->sourceFileName} indicato non è valido.");
        }
    }

    protected function setCancheAndSourceFile($view)
    {
        $this->cacheFileName = __DIR__ . '/../../../../../cache/' . md5($view) . '.php';
        $this->sourceFileName = __DIR__ . "/../../../../../app/bundles/$view";
    }

    public function __construct($view)
    {
        $this->setCancheAndSourceFile($view);
        $this->findIfExistsSourceFile();
    }

    protected function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    protected function getCachedFile()
    {
        ob_start();
        eval('?>' . (file_get_contents($this->cacheFileName)) . '<?');
        $this->cache = ob_get_clean();
    }

}
