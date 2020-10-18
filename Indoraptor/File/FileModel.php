<?php namespace Indoraptor\File;

use codesaur\DataObject\CDO;

class FileModel extends \Indoraptor\MultiModel2
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new FileDescribe());
        
        $this->setTables('file');
    }
    
    public function getTableRecord(string $table, int $record, int $type, $flag = null)
    {
        $files = new FilesModel($this->dataobject());
        $files->setTable($table);
        
        $condition = "record=$record AND type=$type AND is_active=1";
        if (isset($flag)) {            
            $condition .= " AND code='$flag'";
        }
        $rows = $files->getRows(
                array(
                    'WHERE'    => $condition,
                    'ORDER BY' => 'id desc',
                    'LIMIT'    => 1
                )
        );
        
        $files_record = \end($rows);
        if (isset($files_record['file'])) {
            $data = $this->getByID($files_record['file'], $flag);

            if ($data) {
                $data['files_id'] = $files_record['id'];
                $data['record']   = $files_record['record'];
                $data['type']     = $files_record['type'] ?? null;
                $data['code']     = $files_record['code'] ?? null;
                $data['rank']     = $files_record['rank'] ?? null;
            }
            
            return $data;
        }
        
        return null;
    }
}
