<?php namespace Indoraptor\Content;

use codesaur\DataObject\CDO;
use codesaur\DataObject\Model;

class MailerModel extends Model
{
    function __construct(CDO $conn)
    {
        parent::__construct($conn);
        
        $this->structure(new MailerDescribe());
        
        $this->setTable('mailer');
    }
}
