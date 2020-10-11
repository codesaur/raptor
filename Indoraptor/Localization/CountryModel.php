<?php namespace Indoraptor\Localization;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

use Indoraptor\Content\LookupModel;
use Indoraptor\Common\MultiDescribe2;

class CountryModel extends LookupModel
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
    
    public function structures(MultiDescribe2 $describes)
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
