<?php namespace Velociraptor\Boot4;

use codesaur as single;

class Dashboard extends Boot4
{
    function __construct($title = null, array $vars = [])
    {
        parent::__construct(\dirname(__FILE__) . '/content.html', $vars + $this->getMenu());

        if (isset($title)) {
            $this->title($title);
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

    public function breadcrumb($item)
    {
        if ($this->hasContent()) {
            $breadcrumb = $this->get('content')->has('breadcrumb') ?
                    $this->get('content')->get('breadcrumb') : null;
        
            if (empty($breadcrumb)) {
                $breadcrumb = array();
            }

            if (\is_array($item)) {
                $breadcrumb[] = array('text' => $item[0], 'link' => $item[1] ?? false);
            } else {
                $breadcrumb[] = array('text' => single::text($item), 'link' => single::link($item));
            }

            $this->get('content')->set('breadcrumb', $breadcrumb);
        }
        
        return $this;
    }
    
    public function addToolbar(
            $text, $icon = null, $class = '', $href = 'javascript:;', $modal = null)
    {
        $toolbar = $this->get('content')->get('toolbar') ?? array();
        $btn = array('class' => "btn $class", 'href' => $href);
        
        if (isset($icon)) {
            $btn['icon'] = $icon;
        }
        
        if (isset($modal)) {
            $btn['data-target'] = $modal;
            $btn['data-toggle'] = 'modal';
        }

        if (\is_array($text)) {
            $btn[\key($text)] = \current($text);
        } else {
            $btn['text'] = $text;
        }

        \array_push($toolbar, $btn);
        
        $this->setContentVar('toolbar', $toolbar);
    }
}
