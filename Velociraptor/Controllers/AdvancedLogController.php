<?php namespace Velociraptor\Controllers;

use codesaur as single;
use codesaur\HTML\TwigTemplate;

use Velociraptor\Templates\Boot4\Dashboard;

class AdvancedLogController extends RaptorController
{
    public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('logs'))->breadcrumb(array(single::text('access-log')));
        
        if ( ! single::user()->can('system_log_index')) {
            $view->noPermission();
        }
        
        $vars = array(
            'logs' => array(),
            'accounts' => $this->getAccounts(),
            'names' => $this->indoget('/log/get/names')['data']['names'] ?? array()
        );
        
        foreach ($vars['names'] ?? array() as $name) {
            $vars['logs'][$name] = $this->indoget("/log/$name?limit=100")['data'] ?? array();
        }
        
        $view->renderTwig(velociraptor_html . '/logger/index-list-logs.html', $vars);
    }
    
    public function crud(string $action, $id, $table)
    {
        try {
            if ($action != 'retrieve') {
                throw new \Exception("Invalid action [$action]!");
            }
            
            if ( ! single::user()->can('system_log_index')) {
                return (new Dashboard())->noPermissionModal();
            }
            
            $logdata = $this->indoget("/log/$table/$id");
            
            (new TwigTemplate(
                velociraptor_html . '/logger/retrieve-modal.html',
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
