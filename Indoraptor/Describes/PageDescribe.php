<?php namespace Indoraptor\Describes;

use codesaur\DataObject\Column;

class PageDescribe extends Describe2
{
    function __construct(string $alias)
    {
        return $this->create(
                array(
                   (new Column('id', 'bigint', 20))->auto()->primary()->unique()->notNull(),
                    new Column('parent_id', 'bigint', 20, 0),
                   (new Column('meta_id', 'bigint', 20))->foreignKey($alias . '_metas(id)'),
                    new Column('date_hand', 'varchar', 96),
                    new Column('tags', 'varchar', 50),
                    new Column('type', 'varchar', 16, 'menu'),
                    new Column('menu_type', 'varchar', 16, 'general'),
                    new Column('width', 'varchar', 16),
                    new Column('style', 'varchar', 16, 'normal'),
                   (new Column('alias', 'varchar', 255))->unique(),
                    new Column('route', 'varchar', 255),
                    new Column('hotlink', 'varchar', 255),
                    new Column('category', 'varchar', 16, 'common'),
                   (new Column('show_comment', 'tinyint', 1, 0))->setPostType(6),
                   (new Column('can_comment', 'tinyint', 1, 0))->setPostType(6),
                    new Column('published', 'tinyint', 1, 1),
                    new Column('read_count', 'int', 11, 0),
                    new Column('position', 'int', 8, 100),
                    new Column('legend', 'int', 4, 1),
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
