<?php namespace Indoraptor\Models;

use codesaur\DataObject\CDO;

use Indoraptor\Describes\SettingDescribe;

class Settings extends MultiModel
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new SettingDescribe());
        
        $this->setTables('settings');
    }    
}
