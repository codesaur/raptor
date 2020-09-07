<?php namespace Velociraptor\Controllers;

use codesaur as single;

use Velociraptor\Templates\Boot4\Dashboard;

class DeveloperController extends RaptorController
{
     public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('developer'))->breadcrumb(array(single::text('developer')));
        
        if ( ! single::user()->can('system_developer_index')) {
            $view->noPermission();
        }
        
        $view->renderTwig(velociraptor_html . '/developer/index-table.html');
    }
}
