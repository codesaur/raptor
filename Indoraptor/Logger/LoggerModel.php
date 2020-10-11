<?php namespace Indoraptor\Logger;

use codesaur\DataObject\CDO;
use codesaur\Common\LogLevel;
use codesaur\DataObject\Model;
use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class LoggerModel extends Model
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(
                (new Describe())->create(
                        array(
                           (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                            new Column('level', 'tinyint', 2, LogLevel::Basic),
                            new Column('type', 'int', 4),
                            new Column('reason', 'varchar', 128),
                            new Column('info', 'text'),
                            new Column('created_at', 'datetime'),
                           (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)')
                        )
                )                
        );
    }
    
    public function setTable(string $name) : bool
    {
        return parent::setTable($name . '_log');
    }
    
    public function getName() : string
    {
        return \substr($this->getTable(), 0, -\strlen('_log'));
    }
    
    public function getNames() : array
    {
        $tables = [];
        $pdostmt = $this->dataobject()->prepare(
                'SHOW TABLES FROM ' . $this->dataobject()->getDB() .
                ' LIKE ' . $this->dataobject()->quote('%_log') . ';');
        $pdostmt->execute();
        while ($rows = $pdostmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = \substr(\current($rows), 0, -\strlen('_log'));
        }
        
        return $tables;
    }
}
