<?php namespace Velociraptor\Boot4Template;

use codesaur\HTML\TwigTemplate;

class Callout extends TwigTemplate
{
    function __construct(
            $content, $type, $icon = null,
            $class = null, bool $close = true, $id = null)
    {
        if ( ! isset($class)) {
            $class = 'shadow-sm bg-light mt-auto';
        }
        
        $vars = array(
            'content' => $content, 'type' => $type, 'class' => $class,
            'id' => $id ?? \uniqid('callout_'), 'close' => $close ? '' : 'd-none'
        );
        
        if (isset($icon)) {
            $vars['icon'] = $icon;
        }
        
        parent::__construct(\dirname(__FILE__) . '/callout.html', $vars);
    }    
}
