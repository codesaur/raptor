<?php namespace Velociraptor;

use codesaur\Base\File;

class ImageController extends FileController
{
    function __construct(
            string $folder = '/files', int $allows = 3, $overwrite = false, $sizelimit = false)
    {
        parent::__construct($folder, $allows, $overwrite, $sizelimit);
    }
    
    function &file() : File
    {
        return $this->file;
    }
    
    public function checkInput($input)
    {
        return $this->file()->isUploadImage($input);
    }
    
    public function single($record, string $flag = '')
    {
        $data = $this->getLast($record, \getenv('IMAGE_MAIN', true) ?: 1, $flag);
        if ( ! $data) {
            return $this->getLast($record, \getenv('IMAGE_BASE', true) ?: 5, $flag);
        }
        
        return $data;
    }
    
    public function singlePath($record, string $flag = '')
    {
        $data = $this->single($record, $flag);
        
        return $data['path'] ?? null;
    }
}
