<?php namespace Indoraptor\Models;

use codesaur\Globals\Post;
use codesaur\DataObject\CDO;
use codesaur\MultiModel\InitableModel;

use Indoraptor\Describes\AccountDescribe;

class Accounts extends InitableModel
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(new AccountDescribe());
        
        $this->setTable('accounts');
    }

    // <editor-fold defaultstate="collapsed" desc=" initial">
    public function initial() : bool
    {
        $tbl = $this->getTable();
        if ( ! parent::initial() &&
                $tbl == 'accounts') {            
            $now_date = \date('Y-m-d H:i:s');
            $password = $this->dataobject()->quote((new Post())->asPassword('password'));
            $s = "INSERT INTO $tbl (id,created_at,username,password,first_name,last_name,email,status)" .
                    " VALUES (1,'$now_date','admin',$password,'Admin','System','admin@example.com',1)";

            if ($this->dataobject()->exec($s) === false) {
                return false;
            }
        }
        
        return true;
    }
    // </editor-fold>
}
