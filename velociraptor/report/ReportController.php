<?php namespace Velociraptor\Report;

use codesaur as single;

use Velociraptor\TwigTemplate;
use Velociraptor\DashboardController;

class ReportController extends DashboardController
{
    public $orgAlias;
    
    function __construct()
    {
        $this->orgAlias = (string) single::user()->organization('alias');
    }
    
    public function webReport()
    {
        $title = \ucwords(single::text('website-total-report'));
        
        $template = $this->getTemplate($title, array(single::text('website-total-report')));
        
        if ( ! single::user()->can($this->orgAlias . '_web_report')) {
            return $template->noPermission();
        }
        
        if (single::user()->can($this->orgAlias . '_web_report_monthly')) {
            $template->addToolbar(array('title' => \ucwords(single::text('website-mounthly-report'))), 'flaticon-calendar-with-a-clock-time-tools', 'btn-info shadow-sm', single::link('web-report-monthly'));
        }
        
        if (single::user()->can($this->orgAlias . '_web_google_analytics')) {
            $template->addToolbar(array('text' => 'google-analytics'), 'flaticon-analytics', 'btn-warning shadow-sm', single::link('web-google-analytics'));
        }
        
        $template->render(new TwigTemplate(\dirname(__FILE__) . '/web-report.html'));
    }
    
    public function webGoogleAnalytics()
    {
        $template = $this->getTemplate(single::text('website') . ' - ' . single::text('google-analytics'), array(single::text('google-analytics')));
        
        if ( ! single::user()->can($this->orgAlias . '_web_google_analytics')) {
            return $template->noPermission();
        }
        
        if (single::user()->can($this->orgAlias . '_web_report')) {
            $template->addToolbar(array('text' => 'website-total-report'), 'flaticon-line-graph', 'btn-success shadow-sm', single::link('web-report'));
        }
        
        if (single::user()->can($this->orgAlias . '_web_report_monthly')) {
            $template->addToolbar(array('text' => 'website-mounthly-report'), 'flaticon-calendar-with-a-clock-time-tools', 'btn-info shadow-sm', single::link('web-report-monthly'));
        }
        
        $template->render(new TwigTemplate(
                \dirname(__FILE__) . '/web-google-analytics.html',
                array('GOOGLE_CLIENT_ID' => \getenv('GOOGLE_CLIENT_ID', true))));
    }
}
