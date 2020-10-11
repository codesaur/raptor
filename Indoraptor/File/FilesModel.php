<?php namespace Indoraptor\File;

use codesaur\DataObject\Model;

class FilesModel extends Model
{
    public $file;
    
    public function setTable(string $name) : bool
    {
        $alias = \preg_replace('/[^A-Za-z0-9_-]/', '', $name);
        
        $this->structure(new FilesDescribe($alias));
        
        return parent::setTable($alias . '_files');
    }

    public function getTableClean()
    {
        return \substr($this->getTable(), 0, -(\strlen('_files')));
    }
}
