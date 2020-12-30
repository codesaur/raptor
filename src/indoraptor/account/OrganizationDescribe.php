<?php namespace Indoraptor\Account;

use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class OrganizationDescribe extends Describe
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                    new Column('parent_id', 'bigint', 20),
                    new Column('name', 'varchar', 255),
                   (new Column('logo', 'varchar', 255))->setPostType(2),
                    new Column('home_url', 'varchar', 255),                    
                    new Column('external', 'varchar', 255),
                    new Column('alias', 'varchar', 16, 'common'),
                   (new Column('status', 'tinyint', 1, 1))->setPostType(6),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                )
        );
    }
}
