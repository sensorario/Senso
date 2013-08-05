<?php

namespace Senso\Engines\Template;

/* @todo: Isolare la logica (salvo la cache) per entrambi gli engine. */

//use Senso\Application;
use Senso\Engines\Base\Engine;

class Template extends Engine
{

    private $template;
    private $blocks;
    private $globals;

    private function includes()
    {
        preg_match_all("/\{include '(.*)'\}/i", $this->template, $matches);
        foreach ($matches[1] as $token) {
            $this->template = str_replace("{include '{$token}'}", file_get_contents('views/' . $token . '.php'), $this->template);
        }
    }

    private function defineBlocks()
    {
        preg_match_all("/\{start (.*)}/i", $this->template, $matches);
        if(count($matches[1]) > 0) {
            $this->template = str_replace("\n", "", $this->template);
            foreach ($matches[1] as $blocco) {
                preg_match_all("/\{start " . $blocco . "}(.*)\{end " . $blocco . "}/", $this->template, $match);
                $this->blocks[$blocco] = $match[1][0];
            }
        }
    }

    private function setBlocks(Template $template)
    {
        $this->blocks += $template->getBlocks();
    }

    private function getBlocks()
    {
        return $this->blocks;
    }

    private function getTemplate()
    {
        return $this->template;
    }

    private function setTemplate(Template $template)
    {
        $this->template = $template->getTemplate();
    }

    private function compile()
    {
        $this->template = str_replace("\n", "", $this->template);
        foreach ($this->blocks as $nome => $blocco) {
            preg_match_all("/\{start " . $nome . "}(.*)\{end " . $nome . "}/", $this->getTemplate(), $match);
            $this->template = str_replace($match[0][0], $this->blocks[$nome], $this->getTemplate());
        }
    }

    public function model($params = [])
    {
        $patterns = [
            '/({{)(\w+)(}})/i' => '<?php echo $${2}; ?' . '>',
            '/({path{)(\w+)(}})/i' => '<?php echo $_LINKS["${2}"]; ?' . '>',
        ];
        foreach ($patterns as $pattern => $replacement) {
            preg_match_all($pattern, $this->template, $matchParts);
            foreach ($matchParts[0] as $match) {
                $compiled = preg_replace($pattern, $replacement, $match);
                $this->template = str_replace($match, $compiled, $this->template);
            }
        }

        $this->globals = '';

        foreach ($params as $name => $value) {
            $this->globals .= '<?php $' . $name . ' = "' . $value . '"; ?>';
        }

        $consts = require __DIR__ . '/../../../../../calcolati/routing.php';
        foreach ($consts["names"] as $key => $value) {
            $this->globals .= '<?php $_LINKS["' . $key . '"] = "' . ($consts["routes"][$value]) . '"; ?>';
        }
        
        ob_start();
        eval('?>' . ($this->globals) . ($this->template) . '<?');
        $this->cache = ob_get_clean();

        return $this;
    }

    public function __construct($view, $bundle = 'Sensorario')
    {
        parent::__construct($view);

        $this->template = file_get_contents($this->sourceFileName);
        $this->blocks = [];

        $this->includes();
        $this->defineBlocks();

        preg_match_all("/\{extends '(.*)'\}/i", $this->template, $matches);
        if(count($matches[1])) {
            foreach ($matches[1] as $token) {
                $parent = new Template($bundle . "/Views/" . $token . ".php");
                $this->setBlocks($parent);
                $this->setTemplate($parent);
                $this->compile();
            }
        }

        return $this;
    }

}