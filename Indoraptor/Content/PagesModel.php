<?php namespace Indoraptor\Content;

use Indoraptor\Common\MultiModel2;

class PagesModel extends MultiModel2
{
    public function setTables(string $primary, $content = null) : bool
    {
        $alias = \preg_replace('/[^A-Za-z0-9_-]/', '', $primary);
        
        $this->structures(new PagesDescribe($alias));
        
        return parent::setTables($alias . '_pages', $content);
    }
    
    public function getTableClean()
    {
        return \substr($this->getTable(), 0, -(\strlen('_pages')));
    }
}
