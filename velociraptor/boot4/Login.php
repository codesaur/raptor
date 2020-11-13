<?php namespace Velociraptor\Boot4;

use Velociraptor\TwigTemplate;
use Velociraptor\DashboardTemplateInterface;

class Login extends Boot4 implements DashboardTemplateInterface
{
    function __construct($title = null, array $vars = [])
    {
        parent::__construct();
        
        $this->set('content', new TwigTemplate(\dirname(__FILE__) . '/login.html', $vars));
        
        if (isset($title)) {
            $this->title($title);
        }
    }
}
