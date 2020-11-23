<?php namespace Velociraptor\File;

use codesaur as single;

class PrivateImageController extends ImageController
{
    public function setFolder(string $folder, bool $relative = true)
    {
        $this->local = _document . '/../private' . $folder;
        $this->public = single::app()->getWebUrl($relative) . '/private' . $folder;
    }
    
    public function getPathUrl(string $fileName) : string
    {
        return $this->public . '?name=' . \urlencode($fileName);
    }
}
