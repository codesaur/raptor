<?php namespace Indoraptor\Localization;

use codesaur\DataObject\Column;

use Indoraptor\Common\MultiDescribe2;

class TranslationDescribe extends MultiDescribe2
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('_keyword_', 'varchar', 128))->unique(),
                    new Column('type', 'int', 4, 0),
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
