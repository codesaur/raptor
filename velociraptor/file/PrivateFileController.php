<?php namespace Velociraptor\File;

use codesaur as single;

class PrivateFileController extends FileController
{
    public function setFolder(string $folder, bool $relative = true)
    {
        $this->local = _document . '/../private' . $folder;
        $this->public = single::app()->getWebUrl($relative) . '/private/file?name=' . \urlencode($folder);
    }
    
    public function getPathUrl(string $fileName) : string
    {
        return $this->public . \urlencode("/$fileName");
    }
}
