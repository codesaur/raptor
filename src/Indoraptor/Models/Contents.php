<?php namespace Indoraptor\Models;

use codesaur\DataObject\CDO;

use Indoraptor\Describes\ContentDescribe;

class Contents extends MultiModel
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new ContentDescribe());
    }
}
