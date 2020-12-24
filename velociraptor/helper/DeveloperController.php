<?php namespace Velociraptor\Helper;

use codesaur as single;

use Velociraptor\TwigTemplate;
use Velociraptor\DashboardController;

class DeveloperController extends DashboardController
{
     public function index()
    {
        $template = $this->getTemplate(single::text('developer'));
        
        if ( ! single::user()->can('system_developer_index')) {
            return $template->alertErrorPermission();
        }
        
        $template->render(new TwigTemplate(\dirname(__FILE__) . '/developer-index-table.html'));
    }
}
