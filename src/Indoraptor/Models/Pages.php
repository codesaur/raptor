<?php namespace Indoraptor\Models;

use Indoraptor\Describes\PageDescribe;

class Pages extends MultiModel
{
    public function setTables(string $primary, $content = null) : bool
    {
        $alias = \preg_replace('/[^A-Za-z0-9_-]/', '', $primary);
        
        $this->structures(new PageDescribe($alias));
        
        return parent::setTables($alias . '_pages', $content);
    }
    
    public function getTableClean()
    {
        return \substr($this->getTable(), 0, -(\strlen('_pages')));
    }
}
