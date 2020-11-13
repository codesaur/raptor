<?php namespace Velociraptor\Helper;

use codesaur as single;

use Velociraptor\Boot4\Boot4;
use Velociraptor\DashboardController;

class HelperController extends DashboardController
{
    public function index()
    {
        $template = new Boot4();
        $template->title('Indoraptor');
        
        if ( ! single::user()->can('system_indoraptor_index')) {
            return $template->noPermission();
        }
        
        $template->render();
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
