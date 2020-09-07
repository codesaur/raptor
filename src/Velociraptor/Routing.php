<?php namespace Velociraptor;

use codesaur as single;
use codesaur\RBAC\User;
use codesaur\Http\Route;

class Routing extends \codesaur\Http\Routing
{
    function entry($route)
    {
        if ( ! single::session()->check('indo/jwt')) {
            return $this->redirectLogin($route);
        }
        
        $controller = new Controllers\BackController();
        $response = $controller->indopost('/auth/jwt', array(
            'jwt' => single::session()->get('indo/jwt')));

        if ( ! isset($response['data']['account']['id'])
                || ! isset($response['data']['organization']['alias'])) {
            return $this->redirectLogin($route);
        }

        $rbac = new User();
        if ( ! $rbac->init(
                $response['data']['account']['id'],
                $response['data']['organization']['alias'])) {
            return $this->redirectLogin($route);
        }

        $response['data']['rbac'] = $rbac;
        single::user()->login($response['data']);

        if (\getenv(_ACCOUNT_ID_, true) === false) {
            \putenv(_ACCOUNT_ID_ . "={$response['data']['account']['id']}");
        }
        
        return $route;
    }
    
    function redirectLogin($route)
    {
        if (single::session()->check('indo/jwt')) {
            single::session()->release('indo/jwt');
        }
        
        if ($route instanceof Route) {
            $class = $route->getController();
            
            if (\strpos($class, '\\') === false) {
                $class = single::app()->getNamespace() . "Controllers\\$class";
            }
            
            if (\class_exists($class)) {
                $controller = new $class();

                if ($controller instanceof Controllers\LoginController) {
                    return $route;
                }
            }
        }
        
        return single::redirect('login');
    }
    
    final function getBasicRules() : array
    {
        return array(
            ['/log', 'logjson@Velociraptor\\Controllers\\HelperController', ['name' => 'log-json']],
            ['/indoraptor', 'Velociraptor\\Controllers\\HelperController', ['name' => 'indoraptor']],
            ['/frontend', 'frontend@Velociraptor\\Controllers\\LoginController', ['name' => 'frontend']],
            ['/datatable', 'onDatatable@Velociraptor\\Controllers\\HelperController', ['name' => 'datatable']],
            ['/web/report', 'webReport@Velociraptor\\Controllers\\ReportController', ['name' => 'web-report']],
            ['/web/report/mounthly', 'webReportMounthly@Velociraptor\\Controllers\\ReportController', ['name' => 'web-report-mounthly']],
            ['/web/google/analytics', 'webGoogleAnalytics@Velociraptor\\Controllers\\ReportController', ['name' => 'web-google-analytics']],
            ['/language/:language', 'changeLanguage@Velociraptor\\Controllers\\LoginController', ['name' => 'language', 'filters' => ['language' => '(\w+)']]],
            ['/crud/:action', 'act@Velociraptor\\Controllers\\CRUDController', ['methods' => 'GET,POST,PUT,DELETE', 'name' => 'crud', 'filters' => ['action' => '(\w+)']]],
            ['/crud/submit/:action', 'submit@Velociraptor\\Controllers\\CRUDController', ['methods' => 'POST', 'name' => 'crud-submit', 'filters' => ['action' => '(\w+)']]]
        );
    }
    
    function getLoginRules() : array
    {
        return array(
            ['/login', 'Velociraptor\\Controllers\\LoginController', ['name' => 'login']],
            ['/logout', 'logout@Velociraptor\\Controllers\\LoginController', ['name' => 'logout']],
            ['/login/try', 'entry@Velociraptor\\Controllers\\LoginController', ['methods' => 'POST', 'name' => 'entry']],
            ['/login/signup', 'signup@Velociraptor\\Controllers\\LoginController', ['methods' => 'POST', 'name' => 'signup']],
            ['/login/set/password', 'setPassword@Velociraptor\\Controllers\\LoginController', ['methods' => 'POST', 'name' => 'login-set-password']],
            ['/login/request/password', 'requestPassword@Velociraptor\\Controllers\\LoginController', ['methods' => 'POST', 'name' => 'login-request']]
        );
    }
    
    final function getAccountRules() : array
    {
        return array(
            ['/account/accept', 'approve@Velociraptor\\Controllers\\AccountController', ['name' => 'account-accept', 'methods' => 'POST']],
            ['/organization/:id', 'selectOrganization@Velociraptor\\Controllers\\LoginController', ['name' => 'organization', 'filters' => ['id' => '(\d+)']]],
            ['/account/:id/organization/set', 'organizationSet@Velociraptor\\Controllers\\AccountController', ['name' => 'account-organization-set', 'filters' => ['id' => '(\d+)'], 'methods' => 'GET,POST']]
        );
    }
}
