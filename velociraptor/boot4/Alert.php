<?php namespace Velociraptor\Boot4;

use Velociraptor\TwigTemplate;

class Alert extends TwigTemplate
{
    function __construct(
            $content, $icon = null, $class = null, $role = null)
    {
        if ( ! isset($class)) {
            $class = 'alert-primary alert-dismissible mt-auto';
        }
        
        $vars = array(
            'content' => $content, 'class' => $class,
            'role' => $role, 'close' => \strpos($class, 'dismissible') ? '' : 'd-none'
        );
        
        if (isset($icon)) {
            $vars['icon'] = $icon;
        }
        
        parent::__construct(\dirname(__FILE__) . '/alert.html', $vars);
    }    
}
