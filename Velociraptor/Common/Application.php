<?php namespace Velociraptor;

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
            
            $class->getTranslation(array('default', 'user'));
        }

        if ( ! ($class instanceof Account\LoginController)) {
            $this->session->Lock();
        }
        
        parent::execute($class, $action, $args);
    }
}
