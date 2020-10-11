<?php namespace Indoraptor\Common;

use codesaur\MultiModel\MultiModel;

class MultiModel2 extends MultiModel
{
    public function initial() : bool
    {
        $initial = new Initial();
        
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
