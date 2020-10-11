<?php namespace Indoraptor\Content;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Model;

class SocialsModel extends Model
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(new SocialsDescribe());
        
        $this->setTable('socials');
    }
}
