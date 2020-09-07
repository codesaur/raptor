<?php namespace Velociraptor\Controllers;

use codesaur as single;

use Velociraptor\Templates\Boot4\Boot4;

class HelperController extends RaptorController
{
    public function index()
    {
        $view = new Boot4();
        $view->title('Indoraptor');
        
        if ( ! single::user()->can('system_indoraptor_index')) {
            $view->noPermission();
        }
        
        $view->render();
    }
    
    public function logjson()
    {
        if ( ! single::user()->can('system_log_index')) {
            exit(single::response()->json(array('Access Denied, You don\'t have permission to access on this resource!')));
        }
        
        if (single::request()->hasParam('table')) {
            $table = single::request()->getParam('table');
        }
        
        exit(single::response()->json($this->indoget('/log/' . ($table ?? 'dashboard'))['data'] ?? array()));
    }
}
