<?php namespace Indoraptor\Account;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Model;
use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class ForgotModel extends Model
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure((new Describe())->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('account', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('use_id', 'varchar', 200),
                    new Column('username', 'varchar', 255),
                    new Column('first_name', 'varchar', 255),
                    new Column('last_name', 'varchar', 255),
                    new Column('email', 'varchar', 65),
                    new Column('flag', 'varchar', 6),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime')
                )
        ));
        
        $this->setTable('forgot');
    }
}
