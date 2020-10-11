<?php namespace Velociraptor\Helper;

use codesaur as single;

use Velociraptor\Common\RaptorController;
use Velociraptor\Boot4Template\Dashboard;

class DeveloperController extends RaptorController
{
     public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('developer'))->breadcrumb(array(single::text('developer')));
        
        if ( ! single::user()->can('system_developer_index')) {
            $view->noPermission();
        }
        
        $view->renderTwig(\dirname(__FILE__) . '/developer-index-table.html');
    }
}
