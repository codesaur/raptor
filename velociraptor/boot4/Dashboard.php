<?php namespace Velociraptor\Boot4;

use codesaur as single;

use Velociraptor\TwigTemplate;
use Velociraptor\DashboardTemplateInterface;

class Dashboard extends Boot4 implements DashboardTemplateInterface
{
    function __construct($title = null, array $vars = [])
    {
        parent::__construct();

        $this->set('content', new TwigTemplate(\dirname(__FILE__) . '/content.html', $vars));

        if (isset($title)) {
            $this->title($title);
        }
        
        if (single::user()->isLogin()) {
            $this->setArray($this->getMenu());
        }
    }
    
    function getMenu() : array
    {
        $menu = array();
        $controller = single::app()->getNamespace() . 'Menu';
        if (\class_exists($controller)) {
            $menuController = new $controller();

            if (\method_exists($menuController, 'getHeader') &&
                    \is_callable(array($menuController, 'getHeader'))) {
                $menu['header'] = $menuController->getHeader();
            }

            if (\method_exists($menuController, 'getFooter') &&
                    \is_callable(array($menuController, 'getFooter'))) {
                $menu['footer'] = $menuController->getFooter();
            }
        }
        
        if ( ! isset($menu['header'])) {
            $menu['header'] = (new Menu())->getHeader();
        }

        if ( ! isset($menu['footer'])) {
            $menu['footer'] = (new Menu())->getFooter();
        }
        
        return $menu;
    }
}
