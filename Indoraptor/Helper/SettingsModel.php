<?php namespace Indoraptor\Helper;

use codesaur\DataObject\CDO;

use Indoraptor\Common\MultiModel2;

class SettingsModel extends MultiModel2
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new SettingsDescribe());
        
        $this->setTables('settings');
    }    
}
