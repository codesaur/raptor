<?php namespace Indoraptor\Models;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Model;

use Indoraptor\Describes\SocialDescribe;

class Socials extends Model
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(new SocialDescribe());
        
        $this->setTable('socials');
    }
}
