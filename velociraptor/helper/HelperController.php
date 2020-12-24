<?php namespace Velociraptor\Helper;

use codesaur as single;

use Velociraptor\Boot4\Boot4;
use Velociraptor\Boot4\Alert;
use Velociraptor\DashboardController;

class HelperController extends DashboardController
{
    public function index()
    {
        $template = new Boot4();
        $template->title('Indoraptor');
        
        try {
            if ( ! single::user()->can('system_indoraptor_index')) {
                throw new \Exception(single::text('system-no-permission'));
            }
        } catch (\Exception $ex) {
            $template->addContent(new Alert($ex->getMessage(), 'flaticon-security', 'alert-danger'));
        } finally {
            $template->render();
        }
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
