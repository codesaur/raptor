<?php namespace Velociraptor\Boot4;

use codesaur as single;

use Velociraptor\TwigTemplate;
use Velociraptor\IndexTemplate;

class Boot4 extends IndexTemplate
{
    function __construct()
    {
        parent::__construct(\dirname(__FILE__) . '/index.html');
    }
    
    public function breadcrumb($item)
    {
        if ( ! $this->hasContent()) {
            return;
        }
        
        $breadcrumb = $this->getContent()->has('breadcrumb') ?
                $this->getContent()->get('breadcrumb') : null;

        if (empty($breadcrumb)) {
            $breadcrumb = array();
        }

        if (\is_array($item)) {
            $breadcrumb[] = array('text' => $item[0], 'link' => $item[1] ?? false);
        } else {
            $breadcrumb[] = array('text' => single::text($item), 'link' => single::link($item));
        }

        $this->getContent()->set('breadcrumb', $breadcrumb);
    }
    
    public function addToolbar(
            $text, $icon = null, $class = '', $href = 'javascript:;', $modal = null)
    {
        if ( ! $this->hasContent()) {
            return;
        }
        
        $toolbar = $this->getContent()->has('toolbar') ?
                $this->getContent()->get('toolbar') : null;

        if (empty($toolbar)) {
            $toolbar = array();
        }
        
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

        $this->getContent()->set('toolbar', $toolbar);
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
        $delete_script .= '<script>document.addEventListener(\'DOMContentLoaded\',function(){$(\'' . $container . "').Delete(" . $options . ');});</script>';

        $this->addContent($delete_script);
    }
    
    public function alertNoPermission($icon = 'flaticon-security', $reload = true)
    {
        $html = single::text('system-no-permission');
        if ($reload) {
            $html .= ' <i class="flaticon2-reload float-right" style="cursor:pointer" onclick="window.location.reload()"></i>';
        }
        
        return new Alert($html, $icon, 'alert-danger');
    }
    
    public function noPermission($is_modal = false, $callback = null)
    {
        if ($is_modal) {
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
    
    public function getSourceFolder() : string
    {
        return \dirname(__FILE__);
    }
    
    public function &getContent() : ?TwigTemplate
    {
        return $this->get('content');
    }
}

