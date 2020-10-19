<?php namespace Velociraptor\Helper;

use codesaur as single;

use Velociraptor\TwigTemplate;
use Velociraptor\Boot4\Dashboard;
use Velociraptor\DashboardController;

class DeveloperController extends DashboardController
{
     public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('developer'))->breadcrumb(array(single::text('developer')));
        
        if ( ! single::user()->can('system_developer_index')) {
            $view->noPermission();
        }
        
        $view->render(new TwigTemplate(\dirname(__FILE__) . '/developer-index-table.html'));
    }
}
