<?php namespace Indoraptor\Account;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;
use codesaur\MultiModel\InitableModel;

class OrganizationUserModel extends InitableModel
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure((new Describe())->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('account_id', 'bigint', 20))->notNull()->foreignKey('accounts(id)'),
                   (new Column('organization_id', 'bigint', 20))->notNull()->foreignKey('organizations(id)'),
                   (new Column('status', 'tinyint', 1, 1))->setPostType(6),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                )
        ));
        
        $this->setTable('organization_users');
    }
    
    public function retrieve($organization_id, $account_id)
    {
        $pdo_stmt = $this->dataobject()->prepare(
                "SELECT * FROM {$this->getTable()} " .
                'WHERE account_id=:account AND organization_id=:org AND is_active=1'
        );
        
        $pdo_stmt->bindParam(':account', $account_id, \PDO::PARAM_INT);        
        $pdo_stmt->bindParam(':org', $organization_id, \PDO::PARAM_INT);
        
        $pdo_stmt->execute();
        if ($pdo_stmt->rowCount()) {
            $record = $pdo_stmt->fetch(\PDO::FETCH_ASSOC);
            if ((int)$record['status'] == 0) {
                return false;
            }
        } else {
            $record = array(
                'account_id' => $account_id,
                'organization_id' => $organization_id);
            
            $id = $this->insert($record);
            
            if ($id) {
                $record['id'] = $id;
                $record['status'] = 1;
                $record['is_active'] = 1;
            }
        }
        
        if (isset($record['id'])) {
            return $record;
        } else {
            return false;
        }
    }
    
    public function initial() : bool
    {
        $table = $this->getTable();
        if ( ! parent::initial() &&
                $table == 'organization_users') {
            $nowdate = \date('Y-m-d H:i:s');
            $sql =  "INSERT INTO $table (id,created_at,account_id,organization_id) " .
                    "VALUES (1,'$nowdate',1,1)";

            if ($this->dataobject()->exec($sql) === false) {
                return false;
            }
        }
        
        return true;
    }
}
