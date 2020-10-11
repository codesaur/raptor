<?php namespace Indoraptor\Account;

use codesaur\DataObject\CDO;
use codesaur\MultiModel\InitableModel;

class OrganizationModel extends InitableModel
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(new OrganizationDescribe());
        
        $this->setTable('organizations');
    }
    
    public function initial() : bool
    {
        $table = $this->getTable();        
        if ( ! parent::initial() &&
                $table == 'organizations') {
            $nowdate = \date('Y-m-d H:i:s');
            $sql = "INSERT INTO $table(id,created_at,name,external,alias)" .
                    " VALUES(1,'$nowdate','System',NULL,'system')";

            if ($this->dataobject()->exec($sql) === false) {
                return false;
            }
        }
        
        return true;
    }
}
