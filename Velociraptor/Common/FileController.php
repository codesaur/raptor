<?php namespace Velociraptor\Common;

use codesaur as single;
use codesaur\Common\File;
use codesaur\Common\LogLevel;

defined('_document') || define('_document', \dirname($_SERVER['SCRIPT_FILENAME']));

class FileController extends RaptorController
{
    public $file;
    public $table;
    public $allow;
    public $local;
    public $public;
    public $overwrite;
    public $size_limit;
    
    function __construct(
            string $folder = '/files', int $allows = 0, $overwrite = false, $sizelimit = false)
    {
        parent::__construct();
        
        $this->file = new File();
        
        $this->setFolder($folder);
        $this->allowExtensions($this->file->getAllowed($allows));
        
        $this->overwrite = $overwrite;
        $this->size_limit = $sizelimit;
    }

    public function setTable(string $name)
    {
        $this->table = $name;
        
        return $this;
    }
    
    public function setFolder(
            string $folder, $local = null, $public = null, bool $relative = true)
    {
        $this->local = ($local ?? (_document . '/public')) . "$folder/";
        $this->public = ($public ?? single::app()->publicUrl($relative)) . "$folder/";
    }
    
    public function getPath() : string
    {
        return $this->local;
    }
    
    public function getPathUrl() : string
    {
        return $this->public;
    }

    public function setSizeLimit(int $size)
    {
        $this->size_limit = $size;
    }

    public function allowExtensions(array $exts)
    {
        $this->allow = $exts;
    }    
        
    public function checkInput($input)
    {
        return $this->file->isUpload($input);
    }

    public function upload($input)
    {
        if ($this->checkInput($input)) {
            return $this->file->upload($input, $this->local, $this->allow, $this->overwrite, $this->size_limit);
        }
        
        return false;
    }
    
    public function post(
            string $input, $record_id,
            array $table_record = [], array $file_record = [])
    {
        return $this->submit($this->upload($input), $record_id, $table_record, $file_record);
    }
    
    public function post_multi(
            string $input, $record_id, array $table_record = [], array $file_record = [])
    {
        $result = array();
        foreach (single::language()->codes() as $code) {
            $table_record['code'] = $code;
            $result[] = $this->submit($this->upload(array($input => $code)), $record_id, $table_record, $file_record);
        }
        
        return $result;
    }
    
    public function submit(
            $upload, $record_id, array $table_record = [], array $file_record = [])
    {
        if (isset($upload['dir'])
                && isset($upload['name'])
                        && ! $this->isEmpty($this->table)) {
            if (isset($table_record['type'])) {
                $existing = $this->getLast($record_id, $table_record['type'], $table_record['code'] ?? '');                
                if ($existing) {
                    $post = $this->indodelete("/record?table=$this->table&model=" .
                            \urlencode('Indoraptor\\File\\FilesModel'), array('id' => $existing['files_id']));
                    if (isset($post['data']['id'])) {
                        $text = "Мэдээллийн $this->table хүснэгтийн $record_id-р бичлэгийн "
                                . $existing['type'] . ' төрлийн (' . $existing['code']
                                . ') хэл дээрх файлыг бүртгэлээс хаслаа.';
                        $this->log(
                                'strip-file',
                                array(
                                    'message' => $text, 'table' => $this->table,
                                    'record' => $record_id, 'type' => $existing['type']),
                                LogLevel::Record);                        
                        if ($existing['protection'] == 1) {
                            $delete = $this->indodelete('/record?model='
                                    . \urlencode('Indoraptor\\File\\FileModel'), array('id' => $existing['id']));
                            if (isset($delete['data'])) {
                                $text = "Мэдээллийн file хүснэгтийн {$existing['id']}-р бичлэг "
                                        . ' дээрх файлыг устгалаа.';
                                $this->log(
                                        'delete-file',
                                        array('message' => $text, 'record' => $existing),
                                        LogLevel::Record, 'file');  
                            }
                        }
                    }
                }
            }
            
            if ( ! isset($file_record['content'])) {
                foreach (single::language()->codes() as $code) {
                    $file_record['content'][$code] = array('title' => '');
                }
            }
            
            $file_record['primary']['file'] = $upload['dir'] . $upload['name'];
            $file_record['primary']['path'] = $this->getPathUrl() . $upload['name'];
            
            $file = $this->indopost('/record?model='
                    . \urlencode('Indoraptor\\File\\FileModel'), array('record' => $file_record));
            
            if (isset($file['data']['id'])) {
                $table_record['record'] = $record_id;
                $table_record['file'] = $file['data']['id'];
                $post = $this->indopost("/record?table=$this->table&model="
                        . \urlencode('Indoraptor\\File\\FilesModel'), array('record' => $table_record));
                if (isset($post['data'])) {
                    $text = "Мэдээллийн $this->table хүснэгтийн $record_id"
                            . '-р бичлэгт зориулж ' . $file['data']['id']
                            . ' дугаартай файлыг байршууллаа.';
                    $this->log(
                            'insert-file',
                            array(
                                'table' => $this->table,
                                'message' => $text,
                                'record' => $record_id,
                                'file' => $file_record['primary']['file'],
                                'path' => $file_record['primary']['path']),
                            LogLevel::Record);
                    
                    return $file_record;
                }
            }
        }
        
        return false;
    }

    public function getRecord($record, $flag = null)
    {
        if ( ! isset($record['file'])) {
            return null;            
        }
        
        $response = $this->indopost("/record/retrieve?model="
                . \urlencode('Indoraptor\\File\\FileModel'), array('id' => $record['file'], 'flag' => $flag));

        if ( ! isset($response['data']['record'])) {
            return null;
        }

        $result = $response['data']['record'];
        $result['files_id'] = $record['id'];
        $result['record']   = $record['record'];
        $result['type']     = $record['type'] ?? null;
        $result['code']     = $record['code'] ?? null;
        $result['rank']     = $record['rank'] ?? null;

        return $result;
    }
    
    public function getLast(
            int $record, int $type, string $flag = '', array $condition = [])
    {
        if ($this->isEmpty($this->table)) {
            return null;
        }
        
        $condition['WHERE'] = "record=$record AND type=$type";
        if ( ! $this->isEmpty($flag)) {
            $condition['WHERE'] .= " AND code='$flag'";
        }
        
        $response = $this->indopost("/record/retrieve?table={$this->table}&model="
                . \urlencode('Indoraptor\\File\\FilesModel'), array('condition' => $condition));
        
        if ( ! isset($response['data']['rows'])) {
            return null;
        }
        
        return $this->getRecord(\end($response['data']['rows']));
    }
    
    public function getAsTwigColumn(string $name, $record, int $type = 1, string $flag = '') : array
    {
        $result = array('name' => $name);
        
        $image = \is_array($record) ? $record : $this->getLast($record, $type, $flag);
        
        if (isset($image['record'])
                && isset($image['files_id'])) {
            $result['record'] = $image['record'];
            $result['files_id'] = $image['files_id'];
            if (isset($image['path'])) {
                $result['path'] = $image['path'];
            }
        }
        
        return array($name => $result);
    }
    
    public function getAsTwigMultiColumn(string $name, int $record, int $type) : array
    {
        $result = array();
        foreach (single::language()->codes() as $flag) {
            $result[$name][$flag]['name'] = $name . "[$flag]";
            $image = $this->getLast($record, $type, $flag);
            if ($image) {
                $result[$name][$flag]['record'] =  $image['record'];
                $result[$name][$flag]['files_id'] =  $image['files_id'];
                if (isset($image['path'])) {
                    $result[$name][$flag]['path'] =  $image['path'];
                }
            }
        }
        
        return $result;
    }

    public function getLastError() : int
    {
        return $this->file->getLastError();
    }
}
