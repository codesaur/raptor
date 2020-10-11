<?php namespace Indoraptor\File;

use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class FilesDescribe extends Describe
{
    function __construct(string $alias)
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('record', 'bigint', 20))->notNull()->foreignKey("$alias(id)"),
                   (new Column('file', 'bigint', 20))->notNull()->foreignKey('file(id)'),
                    new Column('type', 'int', 5),
                    new Column('code', 'varchar', 6, ''),
                    new Column('rank', 'int', 8, 10),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                )
        );
    }
}
