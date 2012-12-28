<?php

namespace Senso\Engines\Render;

use Senso\Application;
use Senso\Engines\Base\Engine;

class Render extends Engine
{

    private $codicePhpDaCompilare;
    private $phpCompilato;
    private $globals;
    private $layout;
    private $model;

    protected function cacheNotExists()
    {
        return $this->environment == Application::ENV_DEV || !file_exists($this->cacheFileName);
    }

    protected function generateCacheFile()
    {
        if($this->cacheNotExists()) {
            $handle = fopen($this->cacheFileName, "w");
            $this->codicePhpDaCompilare = file_get_contents($this->sourceFileName);
            $this->renderPhpWithModel();
            fwrite($handle, $this->phpCompilato);
            fclose($handle);
        }
    }

    private function setModel($model)
    {
        $this->model = $model;
    }

    public function __construct($model = array(), $view = null, $environment = null, $layout = null)
    {
        parent::__construct($view);
        $this->setEnvironment($environment);
        $this->setLayout($layout);
        $this->setModel($model);
        $this->generateCacheFile();
        $this->getCachedFile();
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    private function renderPhpWithModel()
    {
        $consts = require __DIR__ . '/../../../../../calcolati/routing.php';
        foreach ($this->model as $key => $value) {
            $this->globals .= '<?php $' . $key . ' = "' . $value . '"; ?>';
        }
        foreach ($consts["names"] as $key => $value) {
            $this->globals .= '<?php $_LINKS["' . $key . '"] = "' . ($consts["routes"][$value]) . '"; ?>';
        }

        /* Monto il layout */
        $this->codicePhpDaCompilare = str_replace("{{content}}", $this->codicePhpDaCompilare, $this->layout);

        /* Ultima compilazione */
        $patterns = [
            '/({for) (\d+) (times})(.+?)({endfor})/s' => '<?php for($i=0;$i<${2};$i++) : ?> ${4} <?php endfor; ?' . '>',
            '/({{)(\w+)(}})/i' => '<?php echo $${2}; ?' . '>',
            '/({path{)(\w+)(}})/i' => '<?php echo $_LINKS["${2}"]; ?' . '>',
        ];
        foreach ($patterns as $pattern => $replacement) {
            preg_match_all($pattern, $this->codicePhpDaCompilare, $matchParts);
            foreach ($matchParts[0] as $match) {
                $compiled = preg_replace($pattern, $replacement, $match);
                $this->codicePhpDaCompilare = str_replace($match, $compiled, $this->codicePhpDaCompilare);
            }
        }
        ob_start();
        eval('?>' . ($this->globals) . ($this->codicePhpDaCompilare) . '<?');
        $this->phpCompilato = ob_get_clean();
    }

}