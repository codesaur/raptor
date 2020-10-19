<?php namespace Velociraptor;

class IndexTemplate extends TwigTemplate
{
    function __construct(string $template = null, array $vars = null)
    {
        parent::__construct($template, $vars);
        
        $this->set('meta', array());
    }
    
    public function title(string $value)
    {
        if ( ! $this->isEmpty($value)) {
            $this->get('meta')['title'] = $value;
        }
        
        return $this;
    }
    
    public function render($content = null)
    {
        if (isset($content)) {
            $this->addContent($content);
        }
        
        $this->set('content', $this->stringify($this->get('content')));
        
        parent::render();
    }
    
    public function hasContent() : bool
    {
        return $this->get('content') instanceof TwigTemplate;
    }

    public function addContent($content)
    {
        $this->setContentIndex($content);
        
        if ($this->hasContent()) {
            $this->get('content')->enhance('content', $this->stringify($content));
        } else {
            $this->enhance('content', $content);
        }
        
        return $this;
    }

    public function setContentVar(string $key, $value)
    {
        if ($this->hasContent()) {
            $this->get('content')->setIndex($this);
        } else {
            $this->set($key, $value);
        }
    }
    
    public function setContentIndex($content)
    {
        if ($content instanceof TwigTemplate) {
            $content->setIndex($this);
            
            return $this->setContentIndex($content->get('content'));
        }
    }
}
