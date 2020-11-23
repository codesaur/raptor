<?php namespace Indoraptor\Account;

use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class AccountDescribe extends Describe
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('username', 'varchar', 65))->unique(),
                   (new Column('password', 'varchar', 255, ''))->setPostType(3),
                    new Column('first_name', 'varchar', 50),
                    new Column('last_name', 'varchar', 50),
                    new Column('phone', 'varchar', 50),
                    new Column('address', 'varchar', 200),
                    new Column('city', 'varchar', 100),
                    new Column('country', 'varchar', 4),
                   (new Column('external', 'varchar', 255))->unique(),
                   (new Column('email', 'varchar', 65))->unique()->setPostType(4),
                   (new Column('photo', 'varchar', 255))->setPostType(2),
                    new Column('legend', 'int', 4, 1),
                    new Column('code', 'varchar', 6),
                   (new Column('status', 'tinyint', 1, 0))->setPostType(6),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                )
        );
    }
}
