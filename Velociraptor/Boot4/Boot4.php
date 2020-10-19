<?php namespace Velociraptor\Boot4;

use codesaur as single;
use codesaur\Http\Controller;

use Velociraptor\TwigTemplate;
use Velociraptor\IndexTemplate;

class Boot4 extends IndexTemplate
{
    function __construct(string $template = null, array $vars = null)
    {
        parent::__construct(\dirname(__FILE__) . '/index.html');

        if (isset($template)) {
            $content = new TwigTemplate($template, $vars);
            $content->setIndex($this);
            $this->set('content', $content);
        }
    }

    public function alert(
            $content, $icon = null, $class = null, $role = null)
    {
        if (empty($class)) {
            $class = 'alert-primary alert-dismissible';
        }
        
        return $this->addContent(new Alert($content, $icon, "$class mb-3", $role));
    }

    public function callout(
            $content, $type, $icon = null,
            $class = null, bool $close = true, $id = null)
    {
        if (empty($class)) {
            $class = 'bg-light';
        }
        
        return $this->addContent(new Callout($content, $type, $icon, "$class shadow-sm", $close, $id));
    }

    public function addDelete(
            $data, $container = 'table',
            $question = null, $action = null,
            $crud = 'delete', $method = null, $selector = null)
    {
        $options = \json_encode(array(
            'data'         => $data,
            'selector'     => $selector ?? '.delete',
            'text' => array(
                'no'       => single::text('no'),
                'yes'      => single::text('yes'),
                'title'    => single::text('confirmation'),
                'question' => $question ?? single::text('delete-ask-record?')
            ),
            'ajax' => array(
                'method'   => $method ?? 'POST',
                'url'      => $action ?? single::link('crud', array('action' => $crud))
            )
        ));
        
        $delete_script = '<script defer src="https://cdn.jsdelivr.net/gh/codesaur/resources/dist/scripts/delete.js"></script>';
        $delete_script .= '<script>document.addEventListener("DOMContentLoaded",function(){$("' . $container . '").Delete(' . $options . ');});</script>';

        $this->addContent($delete_script);
    }
    
    public function render($content = null)
    {
        parent::render($content);
        
        if (single::controller() instanceof Controller
                && single::controller()->hasMethod('log')) {
            single::controller()->log('request', single::controller()->getMe() . ' rendering ' . $this->getMe());
        }
    }
    
    public function alertNoPermission($icon = 'flaticon-security', $reload = true)
    {
        $html = single::text('system-no-permission');
        if ($reload) {
            $html .= ' <i class="flaticon2-reload float-right" style="cursor:pointer" onclick="window.location.reload()"></i>';
        }
        
        return new Alert($html, $icon, 'alert-danger');
    }
    
    public function noPermission($modal = false, $callback = null)
    {
        if ($modal) {
            (new TwigTemplate(
                \dirname(__FILE__) . '/no-permission-modal.html',
                array('alert' => $this->alertNoPermission(null, false))))->render();
        } else {
            $this->addContent($this->alertNoPermission())->render();
        }
        
        if (isset($callback)) {
            $callback();
        }
        
        return false;
    }
    
    public function getFolder() : string
    {
        return \dirname(__FILE__);
    }
}
