<?php namespace Indoraptor\Content;

use codesaur\DataObject\CDO;

use Indoraptor\Common\MultiModel2;

class LookupModel extends MultiModel2
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new LookupDescribe());
    }
    
    public function setTables(string $primary, $content = null) : bool
    {
        return parent::setTables("lookup_$primary", $content ?? "lookup_{$primary}_content");
    }
    
    public function getTableClean()
    {
        return \str_replace('lookup_', '', $this->getTable());
    }
    
    public function getLookupNames() : array
    {
        $pdostmt = $this->second()->dataobject()->prepare(
                'SHOW TABLES FROM ' . $this->dataobject()->getDB() .
                ' LIKE ' . $this->dataobject()->quote('lookup_%_content;'));
        $pdostmt->execute();

        $tables = array();
        while ($rows = $pdostmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = \substr(
                    \substr(\current($rows), \strlen('lookup_')), 0, -(\strlen('_content')));
        }
        
        return $tables;
    }
    
    public function retrieve(string $table, $language = null) : array
    {
        $this->setTables($table);

        $pdostmt = $this->statement(
                array('_keyword_'), array('title', $this->flag),
                $language ? array('WHERE' => "c.$this->flag=" . $this->dataobject()->quote($language)) : array()
        );
        
        $rows = array();
        while ($row = $pdostmt->fetch(\PDO::FETCH_ASSOC)) {
            if ($language != false) {
                $rows[$row['_keyword_']] = $row['title'];
            } else {
                $rows[$row['_keyword_']][$row[$this->flag]] = $row['title'];
            }
        }
        
        return $rows;
    }
}
