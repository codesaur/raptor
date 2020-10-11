<?php namespace Velociraptor\Report;

use codesaur as single;

use Velociraptor\Common\RaptorController;
use Velociraptor\Boot4Template\Dashboard;

class ReportController extends RaptorController
{
    public $orgAlias;
    
    function __construct()
    {
        parent::__construct();
        
        $this->orgAlias = (string) single::user()->organization('alias');
    }
    
    public function webReport()
    {
        $title = \ucwords(single::text('website-total-report'));
        
        $view = new Dashboard($title);
        $view->breadcrumb(array(single::text('website-total-report')));
        
        if ( ! single::user()->can($this->orgAlias . '_web_report')) {
            $view->noPermission();
        }
        
        if (single::user()->can($this->orgAlias . '_web_report_monthly')) {
            $view->addToolbar(array('title' => \ucwords(single::text('website-mounthly-report'))), 'flaticon-calendar-with-a-clock-time-tools', 'btn-info shadow-sm', single::link('web-report-monthly'));
        }
        
        if (single::user()->can($this->orgAlias . '_web_google_analytics')) {
            $view->addToolbar(array('text' => 'google-analytics'), 'flaticon-analytics', 'btn-warning shadow-sm', single::link('web-google-analytics'));
        }
        
        $view->renderTwig(\dirname(__FILE__) . '/web-report.html');
    }
    
    public function webGoogleAnalytics()
    {
        $view = new Dashboard(single::text('website') . ' - ' . single::text('google-analytics'));
        $view->breadcrumb(array(single::text('google-analytics')));
        
        if ( ! single::user()->can($this->orgAlias . '_web_google_analytics')) {
            $view->noPermission();
        }
        
        if (single::user()->can($this->orgAlias . '_web_report')) {
            $view->addToolbar(array('text' => 'website-total-report'), 'flaticon-line-graph', 'btn-success shadow-sm', single::link('web-report'));
        }
        
        if (single::user()->can($this->orgAlias . '_web_report_monthly')) {
            $view->addToolbar(array('text' => 'website-mounthly-report'), 'flaticon-calendar-with-a-clock-time-tools', 'btn-info shadow-sm', single::link('web-report-monthly'));
        }
        
        $view->renderTwig(
                \dirname(__FILE__) . '/web-google-analytics.html',
                array('GOOGLE_CLIENT_ID' => \getenv('GOOGLE_CLIENT_ID')));
    }
}
