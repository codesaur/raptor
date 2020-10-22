<?php namespace Velociraptor;

use PHPMailer\PHPMailer\PHPMailer;

class Application extends \codesaur\Base\Application
{
    function __construct(array $config)
    {
        if ( ! isset($config['/indo'])) {
            $config['/indo'] = 'Indoraptor\\';
        }
        
        parent::__construct($config);
        
        $this->session->start('raptor');
        
        if ($this->session->check('indo/jwt')) {
            \putenv("INDO_JWT={$this->session->get('indo/jwt')}");
        }
    }
    
    public function execute($class, string $action, array $args)
    {
        if ($class instanceof Controller) {
            $languages = $class->indoget('/language');
            if (isset($languages['data'])) {
                $this->language->create($languages['data']);
            }
            
            if ( ! $this->language->created()) {
                $this->error('Language initilization error!', 400);
            } else {
                $alias = $this->getNamespace() . 'Language';
                if ($this->session->check($alias)
                        && $this->language->current() != $this->session->get($alias)) {
                    $this->language->select($this->session->get($alias));
                }
            }
            
            $translation_names = array('default', 'user');
            if ($class instanceof DashboardController) {
                $translation_names[] = 'dashboard';
            }            
            $class->getTranslation($translation_names);
        }
        
        parent::execute($class, $action, $args);
    }    
    
    public function getPHPMailer($record, array $options = array(
        'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true)) : ?PHPMailer
    {
        if (empty($record) || ! isset($record['charset']) || ! isset($record['host']) || ! isset($record['port'])
                || ! isset($record['is_smtp']) || ! isset($record['smtp_auth']) || ! isset($record['smtp_secure'])
                || ! isset($record['username']) || ! isset($record['password']) || ! isset($record['email']) || ! isset($record['name'])) {
            return null;
        }

        $mailer = new PHPMailer(false);
        if (((int) $record['is_smtp']) == 1) {
           $mailer->IsSMTP(); 
        }
        $mailer->CharSet = $record['charset'];
        $mailer->SMTPAuth = (bool)((int) $record['smtp_auth']);
        $mailer->SMTPSecure = $record['smtp_secure'];
        $mailer->Host = $record['host'];
        $mailer->Port = $record['port'];
        $mailer->Username = $record['username'];
        $mailer->Password = $record['password'];
        $mailer->SetFrom($record['email'], $record['name']);
        $mailer->AddReplyTo($record['email'], $record['name']);
        $mailer->SMTPOptions = array('ssl' => $options);

        return $mailer;
    }
}
