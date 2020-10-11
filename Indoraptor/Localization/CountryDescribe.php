<?php namespace Indoraptor\Localization;

use codesaur\DataObject\Column;

use Indoraptor\Common\MultiDescribe2;

class CountryDescribe extends MultiDescribe2
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('code', 'varchar', 19))->primary()->unique()->notNull(),
                   (new Column('_keyword_', 'varchar', 128))->unique(),
                    new Column('speak', 'varchar', 32),
                    new Column('type', 'int', 4, 0),
                    new Column('is_active', 'tinyint', 1, 1),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                ),
                array(
                    new Column('title', 'varchar', 255),
                    new Column('short', 'text'),
                    new Column('full', 'text'),
                   (new Column('status', 'tinyint', 1, 1))->setPostType(6)
                )
        );
    }
}
