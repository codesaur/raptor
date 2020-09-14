<?php namespace Indoraptor\Models;

use codesaur\DataObject\CDO;
use codesaur\MultiModel\InitableModel;

use Indoraptor\Describes\LanguageDescribe;

class Language extends InitableModel
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(new LanguageDescribe());
        
        $this->setTable('language');
    }
     
    public function extract(\PDOStatement $pdostatement) : array
    {
        $languages = array();
        while ($row = $pdostatement->fetch(\PDO::FETCH_ASSOC)) {
            $languages[$row['short']] = $row['full'];
            if ((int) $row['is_default'] == 1) {
                $default = $row['short'];
            }
        }
        
        if (isset($default)) {
            $tmp = array();
            $tmp[$default] = $languages[$default];
            foreach ($languages as $short => $full) {
                if ($short != $default) {
                    $tmp[$short] = $full;
                }
            }
            $languages = $tmp;
        }
        
        return $languages;
    }

    public function getByCode(string $code, string $app = 'common', bool $isactive = true)
    {
        $pdostatement = $this->dataobject()->prepare(
                'SELECT * FROM ' . $this->getTable() .
                ' WHERE app=' . $this->dataobject()->quote($app) .
                ' AND short=' . $this->dataobject()->quote($code) .
                ' AND is_active=' . ($isactive ? '1' : '0'));
        $pdostatement->execute();
        
        if ($pdostatement->rowCount() > 0) {
            return $pdostatement->fetch(\PDO::FETCH_ASSOC);
        }
        
        return null;
    }
    
    public function retrieve(string $app = 'common', bool $isactive = true)
    {
        $pdostatement = $this->dataobject()->prepare(
                'SELECT * FROM ' . $this->getTable() .
                ' WHERE app=' . $this->dataobject()->quote($app) .
                ' AND is_active=' . ($isactive ? '1' : '0'));
        $pdostatement->execute();
        
        if ($pdostatement->rowCount() > 0) {
            return $this->extract($pdostatement);
        }
        
        return null;
    }

    public function initial() : bool
    {
        if ( ! parent::initial()) {
            $nowdate = \date('Y-m-d H:i:s');
            $sql =  "INSERT INTO {$this->getTable()} (created_at, short, full, app, is_default) " .
                    "VALUES ('$nowdate', 'mn', 'Монгол', 'common', 1), ('$nowdate', 'en', 'English', 'common', 0)";

            if ($this->dataobject()->exec($sql) === false) {
                return false;
            }
        }
        
        return true;
    }
}
