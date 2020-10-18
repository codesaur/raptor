<?php namespace Indoraptor\Content;

use codesaur\DataObject\CDO;

class SettingsModel extends \Indoraptor\MultiDescribe2
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new SettingsDescribe());
        
        $this->setTables('settings');
    }    
}
