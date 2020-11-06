<?php namespace Indoraptor\Content;

use codesaur\DataObject\CDO;

class ContentModel extends \Indoraptor\MultiModel2
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structures(new ContentDescribe());
    }
}
