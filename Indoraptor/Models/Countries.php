<?php namespace Indoraptor\Models;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;
use codesaur\MultiModel\MultiDescribe;

use Indoraptor\Describes\CountryDescribe;

class Countries extends Lookup
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->key = 't_code';
        $this->flag = 'language';
        
        $this->structures(new CountryDescribe());        
        $this->setTables($this->getTableClean());
    }
    
    public function getTableClean()
    {
        return 'countries';
    }
    
    public function structures(MultiDescribe $describes)
    {
        parent::structure($describes->primary);
        
        $this->content->structure(
                (new Describe())->create(
                        array(
                           (new Column('inc', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                           (new Column($this->key, 'varchar', 19))->notNull(),
                            new Column($this->flag, 'varchar', 6)
                        )
                )
        );
        
        foreach ($describes->content->getColumns() as $column) {
            $this->content->describe->addColumn($column);
        }
    }
}
