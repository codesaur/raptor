<?php namespace Indoraptor\File;

use codesaur\DataObject\Column;

use Indoraptor\Common\MultiDescribe2;

class FileDescribe extends MultiDescribe2
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('file', 'varchar', 255))->setPostType(2),
                    new Column('path', 'varchar', 255, ''),
                   (new Column('protection', 'tinyint', 1, 1))->notNull(), // 1 => public; 2 => private
                    new Column('category', 'int', 4, 1),
                    new Column('size', 'bigint', 20),
                    new Column('_keyword_', 'varchar', 128),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                ),
                array(
                    new Column('title', 'varchar', 255)
                )
        );
    }
}
