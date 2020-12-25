<?php namespace Velociraptor\Boot4;

use codesaur as single;

class Menu
{
    public $orgAlias;
    
    function __construct()
    {
        $this->orgAlias = (string) single::user()->organization('alias');
    }
    
    public function getContentMenu(array &$menu)
    {
        $content_dropdown = array();
        if (single::user()->can($this->orgAlias . '_pages_index')) {
            $content_dropdown[] = array(
                'route' => 'crud',
                'text'  => 'pages',
                'icon'  => 'flaticon2-list-3',
                'param' => array('action' => 'index'),
                'query' => 'logger=pages&controller=Velociraptor\\Content\\PagesController'
            );
        }
        if (single::user()->can($this->orgAlias . '_news_index')) {
            $content_dropdown[] = array(
                'route' => 'crud',
                'text'  => 'news',
                'icon'  => 'flaticon2-sheet',
                'param' => array('action' => 'index'),
                'query' => 'logger=news&controller=Velociraptor\\Content\\NewsController'
            );
        }
        if (single::user()->can($this->orgAlias . '_slider_index')) {
            $content_dropdown[] = array(
                'divider' => true,
                'route'   => 'crud',
                'text'    => 'slide1',
                'icon'    => 'flaticon-presentation',
                'param'   => array('action' => 'index'),
                'query'   => 'logger=slider&controller=Velociraptor\\Content\\LayerSliderController'
            );
        }
        if (single::user()->can($this->orgAlias . '_website_settings')) {
            $content_dropdown[] = array(
                'divider' => true,
                'route'   => 'crud',
                'text'    => 'settings',
                'icon'    => 'flaticon-globe',
                'param'   => array('action' => 'index'),
                'query'   => 'logger=settings&table=settings&controller=Velociraptor\\Content\\SettingsController'
            );
        }
        if (single::user()->can($this->orgAlias . '_website_socials')) {
            $content_dropdown[] = array(
                'route' => 'crud',
                'text'  => 'social-network',
                'icon'  => 'flaticon-network',
                'param' => array('action' => 'index'),
                'attr'  => 'data-toggle="modal" data-target="#modal"',
                'query' => 'logger=settings&table=socials&controller=Velociraptor\\Content\\SettingsController'
            );
        }
        if (single::user()->can($this->orgAlias . '_web_google_analytics')) {
            $content_dropdown[] = array(
                'divider' => true,
                'text'    => 'google-analytics',
                'route'   => 'web-google-analytics'
            );
        }
        if (single::user()->can($this->orgAlias . '_web_report')) {
            $content_dropdown[] = array(
                'divider' => true,
                'route' => 'web-report',
                'text'  => 'website-total-report'
            );
        }
        if (single::user()->can($this->orgAlias . '_web_report_monthly')) {
            $content_dropdown[] = array(
                'route' => 'web-report-monthly',
                'text'  => 'website-mounthly-report'
            );
        }

        if ( ! empty($content_dropdown)) {
            $menu[] = array(
                'text'     => 'website',
                'dropdown' => $content_dropdown
            );
        }
    }
    
    public function getSystemMenu(array &$menu)
    {
        $system_dropdown = array();
        if (single::user()->can('system_account_index')
                || single::user()->can(single::user()->organization('alias') . '_account_index')) {
            $system_dropdown[] = array(
                'route' => 'crud',
                'text'  => 'accounts',
                'icon'  => 'flaticon2-avatar',
                'attr'  => 'id="account_link"',
                'param' => array('action' => 'index'),
                'query' => 'logger=account&controller=Velociraptor\\Account\\AccountController'
            );
        }
        if (single::user()->can('system_org_index')) {
            $system_dropdown[] = array(
                'route' => 'crud',
                'text'  => 'organization',
                'icon'  => 'la la-bank',
                'param' => array('action' => 'index'),
                'query' => 'logger=organization&controller=Velociraptor\\Organization\\OrganizationController'
            );
        }
        if (single::user()->can('system_language_index')) {
            $system_dropdown[] = array(
                'divider' => true,
                'route'   => 'crud',
                'text'    => 'language',
                'icon'    => 'la la-flag',
                'param'   => array('action' => 'index'),
                'query'   => 'logger=localization&table=language&controller=Velociraptor\\Localization\\LanguagesController'
            );
        }
        if (single::user()->can('system_translation_index')) {
            $system_dropdown[] = array(
                'route' => 'crud',
                'text'  => 'translation',
                'icon'  => 'la la-language',
                'param' => array('action' => 'index'),
                'query' => 'logger=localization&controller=Velociraptor\\Localization\\TranslationsController'
            );
        }
        if (false && single::user()->can('system_file_index')) {
            $system_dropdown[] = array(
                'divider' => true,
                'route'   => 'crud',
                'text'    => 'file',
                'icon'    => 'flaticon2-file',
                'param'   => array('action' => 'index'),
                'query'   => 'logger=files&controller=Velociraptor\\Localization\\FilesController'
            );
        }
        if (false && single::user()->can('system_image_index')) {
            $system_dropdown[] = array(
                'route' => 'crud',
                'text'  => 'image',
                'icon'  => 'flaticon2-photograph',
                'param' => array('action' => 'index'),
                'query' => 'logger=files&controller=Velociraptor\\Content\\ImagesController'
            );
        }
        if (false && single::user()->can('system_reference_index')) {
            $system_dropdown[] = array(
                'divider' => true,
                'route'   => 'crud',
                'text'    => 'general-tables',
                'icon'    => 'flaticon2-paper',
                'param'   => array('action' => 'index'),
                'query'   => 'logger=reference&controller=Velociraptor\\Content\\ReferenceController'
            );
        }
        if (single::user()->can('system_template_index')) {
            $system_dropdown[] = array(
                'route' => 'crud',
                'icon'  => 'flaticon2-copy',
                'text'  => 'document-templates',
                'param' => array('action' => 'index'),
                'query' => 'logger=reference&controller=Velociraptor\\Content\\TemplatesController'
            );
        }
        if (single::user()->can('system_mailer')) {
            $system_dropdown[] = array(
                'divider' => true,
                'route'   => 'crud',
                'text'    => 'mail-carrier',
                'icon'    => 'flaticon2-mail-1',
                'param'   => array('action' => 'index'),
                'attr'    => 'data-toggle="modal" data-target="#modal"',
                'query'   => 'logger=settings&table=mailer&controller=Velociraptor\\Content\\SettingsController'
            );
        }
        if (single::user()->can('system_log_index')) {
            $system_dropdown[] = array(
                'text'  => 'log',
                'route' => 'crud',
                'text'  => 'access-log',
                'icon'  => 'flaticon-list',
                'param' => array('action' => 'index'),
                'query' => 'controller=Velociraptor\\Log\\AdvancedLogController'
            );
        }
        if ( ! empty($system_dropdown)) {
            $menu[] = array(
                'text'     => 'system',
                'dropdown' => $system_dropdown
            );
        }
    }

    public function getHeader(array $menu = [])
    {
        $menuMethod = $this->orgAlias . 'Menu';
        
        if (\method_exists($this, $menuMethod)) {
            $this->$menuMethod($menu);
        }

        $this->getContentMenu($menu);
        
        $this->getSystemMenu($menu);
        
        return $menu;
    }

    public function getFooter(array $items = [])
    {
        if (single::user()->can('system_documentation_index')) {
            $items[] = array(
                'route' => 'crud',
                'title' => 'Documentation',
                'icon'  => 'flaticon-light',
                'attr'  => 'target="_blank"',
                'param' => array('action' => 'index'),
                'query' => 'logger=developer&controller=Velociraptor\\Helper\\DocumentationController'
            );
        }
        
        if (single::user()->can('system_developer_index')) {
            $items[] = array(
                'route' => 'crud',
                'text'  => 'developer',
                'icon'  => 'flaticon2-user-1',
                'attr'  => 'target="_blank"',
                'param' => array('action' => 'index'),
                'query' => 'logger=developer&controller=Velociraptor\\Helper\\DeveloperController'
            );
        }
        
        if (single::user()->can('system_indoraptor_index')) {
            $items[] = array(
                'route' => 'crud',
                'title' => 'Indoraptor',
                'icon'  => 'flaticon2-protected',
                'attr'  => 'target="_blank"',
                'param' => array('action' => 'index'),
                'query' => 'logger=developer&controller=Velociraptor\\Helper\\HelperController'
            );
        }

        if (single::user()->can('system_log_index') &&
                single::request()->getParam('controller') != 'Velociraptor\\Log\\AdvancedLogController') {
            $item = array(
                'route' => 'log-json',
                'text'  => 'access-log',
                'icon'  => 'flaticon2-ui',
                'attr'  => 'target="_blank"'
            );
            if (single::request()->hasParam('logger')) {
                $item['query'] = 'table=' . \urlencode(single::request()->getParam('logger'));
            }
            
            $items[] = $item;
        }
        
        return $items;
    }
}
