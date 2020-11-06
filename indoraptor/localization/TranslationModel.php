<?php namespace Indoraptor\Localization;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Model;

class TranslationModel extends \Indoraptor\MultiModel2
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new TranslationDescribe());
    }
    
    public function setTables(string $primary, $content = null) : bool
    {
        return parent::setTables("translation_{$primary}_key", $content ?? "translation_$primary");
    }
    
    public function getTableClean()
    {
        return \substr($this->second()->getTable(), \strlen('translation_'));
    }

    public function queryKeyTables() : \PDOStatement
    {
        $pdostmt = $this->dataobject()->prepare(
                'SHOW TABLES FROM ' . $this->dataobject()->getDB() .
                ' LIKE ' . $this->dataobject()->quote('translation_%_key') . ';');        
        $pdostmt->execute();
        
        return $pdostmt;
    }
    
    public function getTranslationNames() : array
    {
        $pdostmt = $this->second()->dataobject()->prepare(
                'SHOW TABLES FROM ' . $this->dataobject()->getDB() .
                ' LIKE ' . $this->dataobject()->quote('translation_%') . ';');
        $pdostmt->execute();
        
        $likeness = [];
        while ($rows = $pdostmt->fetch(\PDO::FETCH_ASSOC)) {
            $likeness[] = \current($rows);
        }
        
        $names = [];
        foreach ($likeness as $name) {
            if (\in_array($name . '_key', $likeness)) {
                $names[] = \substr($name, \strlen('translation_'));
            }
        }
        
        return $names;
    }

    public function retrieve(string $flag = null) : array
    {
        $text = array();

        if (isset($flag)) {
            $flag = \preg_replace('/[^A-Za-z]/', '', $flag);
            
            $stmt = $this->statement(
                    array('_keyword_'), array('title'),
                    array(
                        'WHERE' => "c.$this->flag=" .
                        $this->dataobject()->quote($flag) . ' AND p.is_active=1'
                    )
            );            
            if ($stmt->rowCount()) {
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $text[$row['_keyword_']] = $row['title'];
                }
            }
        } else {
            $stmt = $this->statement(['_keyword_'], [$this->flag, 'title']);
            if ($stmt->rowCount()) {
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $text[$row['_keyword_']][$row[$this->flag]] = $row['title'];
                }
            }
        }

        return $text;
    }
    
    public function getByKeyword(string $value)
    {
        $keytables = $this->queryKeyTables();
        $keyword = $this->describe->getColumn('_keyword_');
        
        $model = new Model($this->dataobject());        
        while ($table = $keytables->fetch(\PDO::FETCH_NUM)) {
            $pdostmt = $model->dataobject()->prepare(
                    'SELECT * FROM ' . $table[0] .
                    ' WHERE ' . $keyword->getName() . '=' . $keyword->getBindName());
            $pdostmt->bindParam($keyword->getBindName(), $value, $keyword->getDataType(), $keyword->getLength());
            $pdostmt->execute();
            
            if ($pdostmt->rowCount() == 1) {
                $result = $pdostmt->fetch(\PDO::FETCH_ASSOC);
                $result['table'] = $table[0];
                
                return $result;
            }
        }
        
        return false;
    }
}
