<?php namespace Velociraptor\Boot4Template;

use codesaur\HTML\TwigTemplate;

class Card extends TwigTemplate
{
    function __construct(
            $caption = null, $icon = null, $class = null, $id = null)
    {
        $vars = array('id' => $id);
        
        $vars['class'] = empty($class) ? 'border-primary mb-4' : $class;

        if ( ! empty($icon)) {
            $vars['icon'] = $icon;
        }
        
        parent::__construct(\dirname(__FILE__) . '/card.html', $vars);
        
        if ( ! empty($caption)) {
            if (\is_array($caption)) {
                $this->setCaption($caption[0], $caption[1] ?? null);
            } else {
                $this->setCaption($caption);
            }
        }
    }
    
    public function addClass($class)
    {
        $this->enhance('class', " $class");
    }
    
    public function setHeader($class, $content = null)
    {
        if ( ! $this->has('header')) {
            $this->set('header', array());
        }
        
        $this->get('header')['class'] = $class;

        if ( ! empty($content)) {
            $this->get('header')['content'] = $content;
        }
    }

    public function setCaption($text, $class = null)
    {
        if ( ! $this->has('header')) {
            $this->set('header', array());
        }
        
        $this->get('header')['caption']['text'] = $text;
        
        if ( ! empty($class)) {
            $this->get('header')['caption']['class'] = $class;
        }
    }
    
    public function addButton(
            $text, $href,
            $class = null, $icon = null,
            $attr = null, $li_class = null)
    {
        $button = array('text' => $text, 'href' => $href);
        
        if ( ! empty($class)) {
            $button['class'] = $class;
        }
        
        if ( ! empty($icon)) {
            $button['icon'] = $icon;
        }

        if ( ! empty($attr)) {
            $button['attr'] = $attr;
        }

        if ( ! empty($li_class)) {
            $button['li-class'] = $li_class;
        }

        if ( ! $this->has('header')) {
            $this->set('header', array());
        }
        
        if ( ! isset($this->get('header')['buttons'])) {
            $this->get('header')['buttons'] = array();
        }
        
        $this->get('header')['buttons'][] = $button;
        
        if ( ! isset($this->get('header')['caption']['class'])) {
            $this->get('header')['caption']['class'] = 'mt-2';
        } else {
            if ( ! \strpos($this->get('header')['caption']['class'], 'mt-2')) {
                $this->get('header')['caption']['class'] = "{$this->get('header')['caption']['class']} mt-2";
            }
        }
    }
    
    public function addContent($content, $class = null)
    {
        $this->enhance('content', $content);

        if ( ! empty($class)) {
            $this->enhance('body_class', $class);
        }
    }
}
