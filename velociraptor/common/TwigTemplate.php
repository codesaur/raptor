<?php namespace Velociraptor;

use codesaur as single;

class TwigTemplate extends \codesaur\HTML\TwigTemplate
{
    protected $twig;
    
    function __construct(string $template = null, array $vars = null)
    {
        parent::__construct($template, $vars);
        
        $this->addGlobal('app', single::app());
        $this->addGlobal('user', single::user());
        $this->addGlobal('request', single::request());
        $this->addGlobal('language', single::language());
        $this->addGlobal('controller', single::controller());
        $this->addFilter('text', function($string) { return single::text($string); });
        $this->addFilter('link', function($string, $params = []) { return single::link($string, $params); });
    }
}
