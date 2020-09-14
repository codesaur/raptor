<?php namespace Indoraptor\Describes;

use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class LanguageDescribe extends Describe
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                    new Column('app', 'varchar', 22, 'common'),
                    new Column('short', 'varchar', 6),
                    new Column('full', 'varchar', 32),
                    new Column('copy', 'varchar', 6),
                    new Column('description', 'text'),
                   (new Column('is_default', 'tinyint', 1, 0))->setPostType(6),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                )
        );
    }
}
