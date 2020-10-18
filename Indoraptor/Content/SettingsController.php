<?php namespace Indoraptor\Content;

class SettingsController extends \Indoraptor\IndoController
{
    function __construct(bool $single = true)
    {
        parent::__construct($single);
        
        $this->connect();
    }
        
    public function settings($alias)
    {
        if ( ! $this->accept()) {
            return $this->error('Not Found');
        }
        
        $alias_clean = \preg_replace('/[^A-Za-z0-9_-]/', '', $alias);
        
        $settings = new SettingsModel($this->conn);
        return $this->success(array(
            'model'  => $settings->getMe(),
            'table'  => $settings->getTable(),
            'clean'  => $settings->getTableClean(),
            'record' => $settings->getBy('alias', $alias_clean)
        ));
    }
    
    public function socials($alias)
    {
        if ( ! $this->accept()) {
            return $this->error('Not Found');
        }
        
        $alias_clean = \preg_replace('/[^A-Za-z0-9_-]/', '', $alias);
        
        $socials = new SocialsModel($this->conn);        
        $this->success(array(
            'model'  => $socials->getMe(),
            'table'  => $socials->getTable(),
            'clean'  => $socials->getTableClean(),
            'record' => $socials->getBy('alias', $alias_clean)
        ));
    }
    
    public function mailer()
    {
        if ( ! $this->accept()) {
            return $this->error('Not Found');
        }
        
        $mailer = new MailerModel($this->conn);        
        return $this->success(array(
            'model'  => $mailer->getMe(),
            'record' => $mailer->getFirst(),
            'table'  => $mailer->getTable(),
            'clean'  => $mailer->getTableClean()
        ));
    }
}
