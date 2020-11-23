<?php namespace Velociraptor;

use codesaur as single;
use codesaur\Http\Route;

class Routing extends \codesaur\Http\Routing
{
    function entry($route)
    {
        try {
            if ( ! single::session()->check('indo/jwt')) {
                throw new \Exception();
            }
        
            $response = (new IndoClient())->internal(
                    '/auth/jwt', 'POST', false,
                    array('jwt' => single::session()->get('indo/jwt')));
            
            if (isset($response['error']['message'])) {
                throw new \Exception($response['error']['message']);
            }
            
            single::user()->login(
                    $response['data']['account'],
                    $response['data']['organizations'],
                    $response['data']['rbac']);
        
            if ($this->isLockSession($route)) {
                single::session()->lock();
            }
        
            return $route;            
        } catch (\Exception $ex) {
            if (single::session()->check('indo/jwt')) {
                single::session()->release('indo/jwt');
            }

            if ($route instanceof Route) {
                $class = $route->getController();

                if (\class_exists($class)) {
                    $controller = new $class();

                    if ($controller instanceof Account\LoginController) {
                        return $route;
                    }
                }
            }

            $url = single::request()->getPathComplete();
            $url .= single::router()->generate('login', array())[0];
            if ( ! empty($ex->getMessage())) {
                $url .= '?message=' . \urlencode($ex->getMessage()) . '&message_type=danger';
            }

            single::header()->redirect($url);
        }
    }
    
    function isLockSession($route) : bool
    {
        if ($route instanceof Route) {
            if ($route->getName() == 'language') {
                return false;
            }

            foreach ($this->getLoginRules() as $rule) {
                if ($route->getPattern() == $rule[0]) {
                    return false;
                }
            }
        }        

        return true;
    }
    
    final function getBasicRules() : array
    {
        return array(
            ['/log', 'logjson@Velociraptor\\Helper\\HelperController', ['name' => 'log-json']],
            ['/indoraptor', 'Velociraptor\\Helper\\HelperController', ['name' => 'indoraptor']],
            ['/frontend', 'frontend@Velociraptor\\Account\\LoginController', ['name' => 'frontend']],
            ['/datatable', 'onDatatable@Velociraptor\\Helper\\HelperController', ['name' => 'datatable']],
            ['/web/report', 'webReport@Velociraptor\\Report\\ReportController', ['name' => 'web-report']],
            ['/web/report/mounthly', 'webReportMounthly@Velociraptor\\Report\\ReportController', ['name' => 'web-report-mounthly']],
            ['/web/google/analytics', 'webGoogleAnalytics@Velociraptor\\Report\\ReportController', ['name' => 'web-google-analytics']],
            ['/language/:language', 'changeLanguage@Velociraptor\\Account\\LoginController', ['name' => 'language', 'filters' => ['language' => '(\w+)']]],
            ['/crud/:action', 'act@Velociraptor\\CRUDController', ['methods' => 'GET,POST,PUT,DELETE', 'name' => 'crud', 'filters' => ['action' => '(\w+)']]],
            ['/crud/submit/:action', 'submit@Velociraptor\\CRUDController', ['methods' => 'POST', 'name' => 'crud-submit', 'filters' => ['action' => '(\w+)']]],
            ['/private/:table/:record', 'readPrivateFile@Velociraptor\\Controller', ['methods' => 'GET', 'name' => 'private-file-read', 'filters' => ['table' => '(\w+)', 'record' => '(\w+)']]]
        );
    }
    
    function getLoginRules() : array
    {
        return array(
            ['/login', 'Velociraptor\\Account\\LoginController', ['name' => 'login']],
            ['/logout', 'logout@Velociraptor\\Account\\LoginController', ['name' => 'logout']],
            ['/login/try', 'entry@Velociraptor\\Account\\LoginController', ['methods' => 'POST', 'name' => 'entry']],
            ['/login/signup', 'signup@Velociraptor\\Account\\LoginController', ['methods' => 'POST', 'name' => 'signup']],
            ['/login/set/password', 'setPassword@Velociraptor\\Account\\LoginController', ['methods' => 'POST', 'name' => 'login-set-password']],
            ['/login/request/password', 'requestPassword@Velociraptor\\Account\\LoginController', ['methods' => 'POST', 'name' => 'login-request']],
            ['/login/organization/:id', 'selectOrganization@Velociraptor\\Account\\LoginController', ['name' => 'organization', 'filters' => ['id' => '(\d+)']]]
        );
    }
    
    final function getAccountRules() : array
    {
        return array(
            ['/account/accept', 'approve@Velociraptor\\Account\\AccountController', ['name' => 'account-accept', 'methods' => 'POST']],
            ['/account/:id/organization/set', 'organizationSet@Velociraptor\\Account\\AccountController', ['name' => 'account-organization-set', 'filters' => ['id' => '(\d+)'], 'methods' => 'GET,POST']]
        );
    }
}
