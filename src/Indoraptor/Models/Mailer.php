<?php namespace Indoraptor\Models;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Model;

use Indoraptor\Describes\MailerDescribe;

class Mailer extends Model
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(new MailerDescribe());
        
        $this->setTable('mailer');
    }
}
