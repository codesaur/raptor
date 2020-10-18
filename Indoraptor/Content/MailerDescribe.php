<?php namespace Indoraptor\Content;

use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class MailerDescribe extends Describe
{
    function __construct()
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('is_smtp', 'tinyint', 1))->setPostType(7),
                    new Column('charset', 'varchar', 6),
                   (new Column('smtp_auth', 'tinyint', 1))->setPostType(7),
                    new Column('smtp_secure', 'varchar', 6),
                    new Column('host', 'varchar', 255),
                    new Column('port', 'int', 15),
                    new Column('username', 'varchar', 128),
                   (new Column('password', 'varchar', 255))->setPostType(3),
                    new Column('name', 'varchar', 255),
                   (new Column('email', 'varchar', 128))->setPostType(4),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                )
        );
    }
}
