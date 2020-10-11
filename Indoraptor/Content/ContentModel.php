<?php namespace Indoraptor\Content;

use codesaur\DataObject\CDO;

use Indoraptor\Common\MultiModel2;

class ContentModel extends MultiModel2
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new ContentDescribe());
    }
}
