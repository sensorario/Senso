<?php

namespace Senso;

class Render
{

    private $php;
    private $compiled;
    private $globals;
    private $viewCompilata;

    private function compilePhp($model, $layout, $matchParts = null)
    {
        $consts = require __DIR__ . '/../../../calcolati/routing.php';
        foreach ($model as $key => $value) {
            $this->globals .= '<?php $' . $key . ' = "' . $value . '"; ?>';
        }
        foreach ($consts["names"] as $key => $value) {
            $this->globals .= '<?php $_LINKS["' . $key . '"] = "' . ($consts["routes"][$value]) . '"; ?>';
        }

        /* Monto il layout */
        $this->php = str_replace("{{content}}", $this->php, $layout);

        /* Ultima compilazione */
        $patterns = [
            '/({for) (\d+) (times})(.+?)({endfor})/s' => '<?php for($i=0;$i<${2};$i++) : ?> ${4} <?php endfor; ?' . '>',
            '/({{)(\w+)(}})/i' => '<?php echo $${2}; ?' . '>',
            '/({path{)(\w+)(}})/i' => '<?php echo $_LINKS["${2}"]; ?' . '>',
        ];
        foreach ($patterns as $pattern => $replacement) {
            preg_match_all($pattern, $this->php, $matchParts);
            foreach ($matchParts[0] as $match) {
                $compiled = preg_replace($pattern, $replacement, $match);
                $this->php = str_replace($match, $compiled, $this->php);
            }
        }
        ob_start();
        eval('?>' . ($this->globals) . ($this->php) . '<?');
        $this->compiled = ob_get_clean();
    }

    public function __construct($model = array(), $view, $environment, $layout)
    {
        $cacheFileName = __DIR__ . '/../../../cache/' . md5($view) . '.php';
        $sourceFile = __DIR__ . "/../../../app/bundles/$view";
        if(!file_exists($sourceFile)) {

            $cacheFileName = __DIR__ . '/../../../cache/' . md5($view);
            $sourceFile = explode(".php", __DIR__ . "/../../../app/bundles/{$view}")[0];

            die("Occhio che {$sourceFile} non esiste!");
        }
        if($environment == Application::ENV_DEV || !file_exists($cacheFileName)) {
            $handle = fopen($cacheFileName, "w");
            $this->php = file_get_contents($sourceFile);
            $this->compilePhp($model, $layout);
//            $this->compilePhp($model, $layout);
            fwrite($handle, $this->compiled);
            fclose($handle);
        }
        ob_start();
        eval('?>' . (file_get_contents($cacheFileName)) . '<?');
        $this->viewCompilata = ob_get_clean();

        echo $this->viewCompilata;
    }

}