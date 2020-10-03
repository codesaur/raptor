<?php namespace Indoraptor;

class Routing extends \codesaur\Http\Routing
{
    function getBasics() : array
    {
        return array(
            ['', 'view@Indoraptor\\Controllers\\IndoController'],
            ['/status', 'status@Indoraptor\\Controllers\\IndoController', ['methods' => 'POST']],
            ['/cdo/query', 'query@Indoraptor\\Controllers\\IndoController', ['methods' => 'POST']],
            ['/statement', 'statement@Indoraptor\\Controllers\\IndoController', ['methods' => 'POST']],
            ['/generate/jwt', 'getJWT@Indoraptor\\Controllers\\IndoController', ['methods' => 'POST']]
        );
    }
    
    function getAuthRules() : array
    {
        return array(
            ['/auth/jwt', 'jwt@Indoraptor\\Controllers\\AuthController', ['methods' => 'POST']],
            ['/auth/try', 'entry@Indoraptor\\Controllers\\AuthController', ['methods' => 'POST']],
            ['/auth/signup', 'signup@Indoraptor\\Controllers\\AuthController', ['methods' => 'POST']],
            
            ['/auth/forgot', 'forgot@Indoraptor\\Controllers\\AuthController', ['methods' => 'POST']],
            ['/auth/get/forgot', 'getForgot@Indoraptor\\Controllers\\AuthController', ['methods' => 'POST']],
            ['/auth/set/password', 'setPassword@Indoraptor\\Controllers\\AuthController', ['methods' => 'POST']]
        );
    }
    
    function getRecordRules() : array
    {
        return array(
            ['/record', 'insert@Indoraptor\\Controllers\\RecordController', ['methods' => 'POST']],
            ['/record', 'update@Indoraptor\\Controllers\\RecordController', ['methods' => 'PUT']],
            ['/record', 'delete@Indoraptor\\Controllers\\RecordController', ['methods' => 'DELETE']],
            ['/record/retrieve', 'retrieve@Indoraptor\\Controllers\\RecordController', ['methods' => 'POST']]
        );
    }
    
    function getContentRules() : array
    {
        return array(
            ['/content', 'Indoraptor\\Controllers\\ContentController', ['methods' => 'POST']],
            ['/lookup', 'lookup@Indoraptor\\Controllers\\ContentController', ['methods' => 'POST']]
        );
    }
    
    function getLanguageRules() : array
    {
        return array(
            ['/language', 'Indoraptor\\Controllers\\LanguageController'],
            ['/language/copy/translation', 'copyTranslation@Indoraptor\\Controllers\\LanguageController', ['methods' => 'POST']]
        );
    }
    
    function getTranslationRules() : array
    {
        return array(
            ['/translation', 'Indoraptor\\Controllers\\TranslationController'],
            ['/translation/getby', 'getBy@Indoraptor\\Controllers\\TranslationController', ['methods' => 'POST']],
            ['/translation/retrieve', 'retrieve@Indoraptor\\Controllers\\TranslationController', ['methods' => 'POST']]
        );
    }
    
    function getLoggerRules() : array
    {
        return array(
            ['/log/get/names', 'names@Indoraptor\\Controllers\\LoggerController'],
            ['/log/:table', 'Indoraptor\\Controllers\\LoggerController', ['filters' => ['table' => '(\w+)']]],
            ['/log/:table/:id', 'Indoraptor\\Controllers\\LoggerController', ['filters' => ['table' => '(\w+)', 'id' => '(\d+)']]],
            ['/log/:table', 'insert@Indoraptor\\Controllers\\LoggerController', ['methods' => 'POST', 'filters' => ['table' => '(\w+)']]],
            ['/log/:table/select', 'select@Indoraptor\\Controllers\\LoggerController', ['methods' => 'POST', 'filters' => ['table' => '(\w+)']]]
        );
    }
    
    function getSettingsRules() : array
    {
        return array(
            ['/settings/mailer', 'mailer@Indoraptor\\Controllers\\SettingsController'],
            ['/settings/:alias', 'settings@Indoraptor\\Controllers\\SettingsController', ['filters' => ['alias' => '(\w+)']]],
            ['/settings/socials/:alias', 'socials@Indoraptor\\Controllers\\SettingsController', ['filters' => ['alias' => '(\w+)']]]
        );
    }
    
    function getWebRules() : array
    {
        return array(
            ['/web/menu/:alias/:flag', 'menu@Indoraptor\\Controllers\\WebsiteController', ['filters' => ['alias' => '(\w+)', 'flag' => '(\w+)']]],
            ['/web/general/:alias/:flag', 'general@Indoraptor\\Controllers\\WebsiteController', ['filters' => ['alias' => '(\w+)', 'flag' => '(\w+)']]],
            ['/web/page/:alias/:flag/:id', 'page@Indoraptor\\Controllers\\WebsiteController', ['filters' => ['alias' => '(\w+)', 'flag' => '(\w+)', 'id' => '(\d+)']]],
            
            ['/web/report/total', 'total@Indoraptor\\Controllers\\WebsiteReportController'],
            ['/web/report/daily', 'daily@Indoraptor\\Controllers\\WebsiteReportController']
        );
    }
}
