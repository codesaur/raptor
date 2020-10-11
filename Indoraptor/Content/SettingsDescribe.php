<?php namespace Indoraptor\Content;

use codesaur\DataObject\Column;

use Indoraptor\Common\MultiDescribe2;

class SettingsDescribe extends MultiDescribe2
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('alias', 'varchar', 16))->notNull(),
                   (new Column('favico', 'varchar', 255, ''))->setPostType(2),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                ),
                array(
                    new Column('title', 'varchar', 255),
                    new Column('phone', 'varchar', 255),
                    new Column('contact', 'text'),
                    new Column('address', 'text'),
                    new Column('open_hours', 'text'),
                    new Column('copyright', 'varchar', 255),
                    new Column('email', 'varchar', 255)
                )
        );
    }
}
