<?php namespace Velociraptor\Log;

use codesaur as single;

use Velociraptor\TwigTemplate;
use Velociraptor\DashboardController;

class AdvancedLogController extends DashboardController
{
    public function index()
    {
        $template = $this->getTemplate(single::text('logs'), array(single::text('access-log')));
        
        if ( ! single::user()->can('system_log_index')) {
            return $template->noPermission();
        }
        
        $vars = array(
            'logs' => array(),
            'accounts' => $this->getAccounts(),
            'names' => $this->indoget('/log/get/names')['data']['names'] ?? array()
        );
        
        foreach ($vars['names'] ?? array() as $name) {
            $vars['logs'][$name] = $this->indoget("/log/$name?limit=100")['data'] ?? array();
        }
        
        $template->render(new TwigTemplate(\dirname(__FILE__) . '/index-list-logs.html', $vars));
    }
    
    public function crud(string $action, $id, $table)
    {
        try {
            if ($action != 'retrieve') {
                throw new \Exception("Invalid action [$action]!");
            }
            
            if ( ! single::user()->can('system_log_index')) {
                return $this->getTemplate()->noPermission(true);
            }
            
            $logdata = $this->indoget("/log/$table/$id");
            
            (new TwigTemplate(
                \dirname(__FILE__) . '/retrieve-log-modal.html',
                array(
                    'table' => $table, 'id' => $id,
                    'accounts' => $this->getAccounts(),
                    'data' => $logdata['data'] ?? null)))->render();
            
            return true;
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return false;
        }
    }
}
