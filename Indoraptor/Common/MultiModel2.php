<?php namespace Indoraptor;

use codesaur as single;
use codesaur\MultiModel\MultiModel;

class MultiModel2 extends MultiModel
{
    public function initial() : bool
    {
        $initialModel = single::app()->getNamespace() . 'Initial';
        $initial = \class_exists($initialModel) ? new $initialModel() : new Initial();
        
        $method = $this->getTable();
        if (\method_exists($initial, $method)) {
            if (\is_callable(array($initial, $this->getTable()))) {
                $initial->$method($this);
                return true;
            }
        }
        
        return false;
    }
}
