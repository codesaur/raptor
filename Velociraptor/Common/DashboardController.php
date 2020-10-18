<?php namespace Velociraptor;

use codesaur as single;
use codesaur\Base\Base;
use codesaur\Base\LogLevel;

use Boot4Template\Dashboard;

abstract class DashboardController extends Controller
{
    function __construct()
    {
        $this->getTranslation('dashboard');
    }
    
    public function index()
    {
        $method_alias =  'index_' . ((string) single::user()->organization('alias'));
        
        if ($this->hasMethod($method_alias)
                && $this->isCallable($method_alias)) {
            $result = $this->$method_alias();
        }
        
        if ( ! ($result ?? false)) {
            (new Dashboard())->noPermission();
        }
    }
    
    public function index_system()
    {
        $you = single::user()->account('first_name') . ' ' . single::user()->account('last_name');
            
        $dashboard = new Dashboard();
        $dashboard->render("Welcome $you!");
        
        return true;
    }

    public function log(
            string $reason, $data = null,
            int $level = LogLevel::Basic, 
            $table = null, $type = null, $time = null)
    {
        if (single::request()->getParam('controller') ==
                'Velociraptor\\Log\\AdvancedLogController') {
            return;
        }
        
        if (empty($table)) {
            $table = single::request()->getParam('logger') ?? 'dashboard';
        }
        
        $this->indolog($reason, $data, $level, $table, $type, $time);
    }
    
    public function onChangeLanguage()
    {
        if (single::user()->isLogin()
                && single::user()->has('account', 'id')) {
            $this->indoput('/record?model='
                    . \urlencode('Indoraptor\\Account\\AccountModel'),
                    array('record' =>
                        array('id' => single::user()->account('id'), 'code' => single::language()->current())));
        }
    }
    
    final public function payload(bool $assoc = false, int $depth = 512, int $options = 0)
    {
        return single::header()->getEntityBodyJson($assoc, $depth, $options);
    }

    final public function getLookup($table)
    {
        $lookup = $this->indopost('/lookup',
                array('table' => $table, 'flag' => single::language()->current()));
        
        return $lookup['data'] ?? array();
    }
    
    final public function grab($thing, $arg = null)
    {
        if (single::request()->hasParam($thing))
        {
            $class = single::request()->getParam($thing);
            if ( ! $this->isEmpty($class)) {                
                return $this->loadClass($class, $arg);
            }
        }
        
        return null;
    }
    
    final public function onDatatable()
    {
        $controller = $this->grab('controller');
        
        if ($controller instanceof Base
                && $controller->hasMethod('datatable')
                && $controller->isCallable('datatable')) {
            $controller->datatable(single::request()->getParam('table'));
        } else {
            die(single::response()->json(array('data' => array())));
        }
    }
    
    final function getAccounts() : array
    {
        $response = $this->indopost('/record/retrieve?model='
                    . \urlencode('Indoraptor\\Account\\AccountModel'),
                    array('condition' => array('WHERE' => 'is_active!=596')));        
        $accounts = array();
        foreach ($response['data']['rows'] ?? array() as $account) {
            $accounts[$account['id']] = $account['username'] . ' » ' . $account['first_name'] . ' ' . $account['last_name'] . ' (' . $account['email'] . ')';
        }
        
        return $accounts;
    }
}
