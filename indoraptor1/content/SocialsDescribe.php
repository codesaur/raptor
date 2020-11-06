<?php namespace Indoraptor\Content;

use codesaur\DataObject\Column;
use codesaur\DataObject\Describe;

class SocialsDescribe extends Describe
{
    function __construct()
    {
        return $this->create(
                array(                    
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                   (new Column('alias', 'varchar', 16))->notNull(),
                    new Column('facebook', 'varchar', 255),
                    new Column('facebook_widget', 'text'),
                    new Column('facebook_pixels', 'text'),
                    new Column('twitter', 'varchar', 255),
                    new Column('twitter_widget', 'text'),
                    new Column('youtube', 'varchar', 255),
                    new Column('flickr', 'varchar', 255),
                    new Column('created_at', 'datetime'),
                   (new Column('created_by', 'bigint', 20))->foreignKey('accounts(id)'),
                    new Column('updated_at', 'datetime'),
                   (new Column('updated_by', 'bigint', 20))->foreignKey('accounts(id)')
                )
        );
    }
}
