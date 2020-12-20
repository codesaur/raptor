<?php namespace Indoraptor;

class Routing extends \codesaur\Http\Routing
{
    function getBasics() : array
    {
        return array(
            ['', 'view@Indoraptor\\IndoController'],
            ['/status', 'status@Indoraptor\\IndoController', ['methods' => 'POST']],
            ['/cdo/query', 'query@Indoraptor\\IndoController', ['methods' => 'POST']],
            ['/statement', 'statement@Indoraptor\\IndoController', ['methods' => 'POST']]
        );
    }
    
    function getEmailRules() : array
    {
        return array(
            ['/send/email', 'sendEmail@Indoraptor\\IndoController', ['methods' => 'POST']],
            ['/send/smtp/email', 'sendSMTPEmail@Indoraptor\\IndoController', ['methods' => 'POST']]
        );
    }
    
    function getAuthRules() : array
    {
        return array(
            ['/auth/jwt', 'jwt@Indoraptor\\AuthController', ['methods' => 'POST']],
            ['/auth/try', 'entry@Indoraptor\\AuthController', ['methods' => 'POST']],
            ['/auth/jwt/org', 'jwtOrganization@Indoraptor\\AuthController', ['methods' => 'POST']]
        );
    }

    function getAccountRules() : array
    {
        return array(
            ['/account/signup', 'signup@Indoraptor\\Account\\AccountController', ['methods' => 'POST']],
            ['/account/forgot', 'forgot@Indoraptor\\Account\\AccountController', ['methods' => 'POST']],
            ['/account/get/forgot', 'getForgot@Indoraptor\\Account\\AccountController', ['methods' => 'POST']],
            ['/account/set/password', 'setPassword@Indoraptor\\Account\\AccountController', ['methods' => 'POST']],            
            ['/account/get/organizations/names', 'getOrganizationsNames@Indoraptor\\Account\\AccountController']
        );
    }
    
    function getRecordRules() : array
    {
        return array(
            ['/record', 'insert@Indoraptor\\RecordController', ['methods' => 'POST']],
            ['/record', 'update@Indoraptor\\RecordController', ['methods' => 'PUT']],
            ['/record', 'delete@Indoraptor\\RecordController', ['methods' => 'DELETE']],
            ['/record/retrieve', 'retrieve@Indoraptor\\RecordController', ['methods' => 'POST']]
        );
    }
    
    function getContentRules() : array
    {
        return array(
            ['/content', 'Indoraptor\\Content\\ContentController', ['methods' => 'POST']],
            
            ['/lookup', 'lookup@Indoraptor\\Content\\ContentController', ['methods' => 'POST']]
        );
    }
    
    function getLanguageRules() : array
    {
        return array(
            ['/language', 'Indoraptor\\Localization\\LanguageController'],
            ['/language/copy/translation', 'copyTranslation@Indoraptor\\Localization\\LanguageController', ['methods' => 'POST']]
        );
    }
    
    function getTranslationRules() : array
    {
        return array(
            ['/translation', 'Indoraptor\\Localization\\TranslationController'],
            ['/translation/getby', 'getBy@Indoraptor\\Localization\\TranslationController', ['methods' => 'POST']],
            ['/translation/retrieve', 'retrieve@Indoraptor\\Localization\\TranslationController', ['methods' => 'POST']]
        );
    }
    
    function getLoggerRules() : array
    {
        return array(
            ['/log/get/names', 'names@Indoraptor\\Logger\\LoggerController'],
            ['/log/:table', 'Indoraptor\\Logger\\LoggerController', ['filters' => ['table' => '(\w+)']]],
            ['/log/:table/:id', 'Indoraptor\\Logger\\LoggerController', ['filters' => ['table' => '(\w+)', 'id' => '(\d+)']]],
            ['/log/:table', 'insert@Indoraptor\\Logger\\LoggerController', ['methods' => 'POST', 'filters' => ['table' => '(\w+)']]],
            ['/log/:table/select', 'select@Indoraptor\\Logger\\LoggerController', ['methods' => 'POST', 'filters' => ['table' => '(\w+)']]]
        );
    }
    
    function getSettingsRules() : array
    {
        return array(
            ['/settings/mailer', 'mailer@Indoraptor\\Content\\SettingsController'],
            ['/settings/:alias', 'settings@Indoraptor\\Content\\SettingsController', ['filters' => ['alias' => '(\w+)']]],
            ['/settings/socials/:alias', 'socials@Indoraptor\\Content\\SettingsController', ['filters' => ['alias' => '(\w+)']]]
        );
    }
    
    function getHelperRules() : array
    {
        return array(
            ['/web/menu/:alias/:flag', 'menu@Indoraptor\\Helper\\WebsiteController', ['filters' => ['alias' => '(\w+)', 'flag' => '(\w+)']]],
            ['/web/general/:alias/:flag', 'general@Indoraptor\\Helper\\WebsiteController', ['filters' => ['alias' => '(\w+)', 'flag' => '(\w+)']]],
            ['/web/page/:alias/:flag/:id', 'page@Indoraptor\\Helper\\WebsiteController', ['filters' => ['alias' => '(\w+)', 'flag' => '(\w+)', 'id' => '(\d+)']]],
            
            ['/web/report/total', 'total@Indoraptor\\Helper\\WebsiteReportController'],
            ['/web/report/daily', 'daily@Indoraptor\\Helper\\WebsiteReportController']
        );
    }
}
