<?php namespace Velociraptor\Content;

use codesaur as single;
use codesaur\MultiModel\MultiDescribe;

use Velociraptor\TwigTemplate;
use Velociraptor\FileController;
use Velociraptor\ImageController;
use Velociraptor\Boot4\Dashboard;
use Velociraptor\DashboardController;

use Indoraptor\Content\MailerDescribe;
use Indoraptor\Content\SocialsDescribe;
use Indoraptor\Content\SettingsDescribe;

class SettingsController extends DashboardController
{
    public function index()
    {
        if (single::request()->hasParam('table')) {
            $table = single::request()->getParam('table');
            if ($this->hasMethod($table)
                && $this->isCallable($table)) {
                return $this->$table();
            }
        }
        
        single::app()->error('Invalid request!');
    }
    
    public function settings()
    {
        $view = new Dashboard();
        $view->title(single::text('settings'))->breadcrumb(array(single::text('settings')));
        
        if ( ! single::user()->can(single::user()->organization('alias') . '_website_settings')) {
            $view->noPermission();
        }
        
        $settings = $this->indoget('/settings/' . single::user()->organization('alias'));
        
        if ( ! isset($settings['data'])) {
            return $view->render();
        }
        
        $table = $settings['data']['clean'] ?? 'settings';
        
        if (empty($settings['data']['record'])) {
            $record = array();
            $status = $this->indopost('/status', array('table' => $table));
            $auto_increment = (int) ($status['data']['Auto_increment'] ?? 1);
        } else {
            $record = $settings['data']['record'];
            $auto_increment = $record['id'];
            $view->addDelete(
                    array('table' => $table, 'logger' => 'settings'),
                    'body', single::text('delete-image-ask'), null, 'strip_file');
        }
        
        $image = (new ImageController())->setTable($table);
        $column = (new SettingsDescribe())->getTwigColumns($record);
        
        if (empty($record)) {
            $column['alias']['value'] = single::user()->organization('alias');
        }
        
        $column += $image->getAsTwigMultiColumn('full', $auto_increment, \getenv('IMAGE_MAIN', true) ?: 1);
        $column += $image->getAsTwigMultiColumn('short', $auto_increment, \getenv('IMAGE_LOGO_SMALL', true) ?: 2);
        $column += $image->getAsTwigColumn('apple_touch', $auto_increment, \getenv('IMAGE_APPLE_TOUCH', true) ?: 6);
        $column += $image->getAsTwigColumn('ico_png', $auto_increment, \getenv('IMAGE_ICO_PNG', true) ?: 7);
        $column += $image->getAsTwigColumn('background', $auto_increment, \getenv('IMAGE_BACKGROUND', true) ?: 8);
        
        $action = single::link('crud-submit', array('action' => empty($record) ? 'insert' : 'update'))
                . '?logger=settings&controller=' . \urlencode($this->getMe()) . "&table=$table";

        $view->render(new TwigTemplate(
                \dirname(__FILE__) . '/settings/settings.html',
                array('column' => $column, 'action' => $action)));
    }
    
    public function socials()
    {
        if ( ! single::user()->can(single::user()->organization('alias') . '_website_socials')) {
            return (new Dashboard())->noPermission(true);
        }

        $response = $this->indoget('/settings/socials/' . \urlencode(single::user()->organization('alias')));
        $table = $response['data']['clean'] ?? 'socials';
        $record = $response['data']['record'] ?? array();
        
        $column = (new SocialsDescribe())->getTwigColumns($record);
        if (empty($record)) {
            $column['alias']['value'] = single::user()->organization('alias');
        }

        (new TwigTemplate(
                \dirname(__FILE__) . '/settings/socials-modal.html',
                array(
                    'column' => $column,
                    'action' => single::link('crud-submit', array('action' => empty($record) ? 'insert' : 'update'))
                    . '?logger=settings&controller=' . \urlencode($this->getMe()) . "&table=$table")))->render();
    }
    
    public function mailer()
    {
        if ( ! single::user()->can('system_system_mailer')) {
            return (new Dashboard())->noPermission(true);
        }
        
        $response = $this->indoget('/settings/mailer');
        $table = $response['data']['clean'] ?? 'mailer';
        $record = $response['data']['record'] ?? array();

        (new TwigTemplate(
                \dirname(__FILE__) . '/settings/mailer-modal.html',
                array(
                    'column' => (new MailerDescribe())->getTwigColumns($record),
                    'action' => single::link('crud-submit', array('action' => empty($record) ? 'insert' : 'update'))
                    . '?logger=settings&controller=' . \urlencode($this->getMe()) . "&table=$table")))->render();
    }
    
    public function submit(string $action, $table)
    {
        if ( ! isset($table)) {
            return false;
        }

        if ($table == 'mailer') {
            if ( ! single::user()->can('system_system_mailer')) {
                return false;
            }

            $describe = new MailerDescribe();
            $record = $describe->getPostValues();
            if ( ! isset($record['is_smtp'])) {
                $record['is_smtp'] = 0;
            }
            if ( ! isset($record['smtp_auth'])) {
                $record['smtp_auth'] = 0;
            }
            $model = \urlencode('Indoraptor\\Content\\MailerModel');
        } elseif ($table == 'socials') {
            if ( ! single::user()->can(single::user()->organization('alias') . '_website_socials')) {
                return false;
            }
            
            $describe = new SocialsDescribe();
            $record = $describe->getPostValues();
            $model = \urlencode('Indoraptor\\Content\\SocialsModel');
        } elseif ($table == 'settings') {
            if ( ! single::user()->can(single::user()->organization('alias') . '_website_settings')) {
                return false;
            }

            $describe = new SettingsDescribe();
            $record = $describe->getPrimary()->getPostValues();
            $content = $describe->getContent()->getPostValues(single::language()->codes());
            $model = \urlencode('Indoraptor\\Content\\SettingsModel');
        } else {
            return false;
        }
        
        if ($action == 'update'
                && ! isset($record['id'])) {
            return false;
        }
        
        if ($action == 'insert'
                && isset($record['id'])) {
            unset($record['id']);
        }
        
        $status = $this->indopost('/status', array('table' => $table));
        $auto_increment = (int) ($record['id'] ?? $status['data']['Auto_increment'] ?? 1);
        
        if ($describe instanceof SettingsDescribe) {
            if (\is_numeric($auto_increment)) {                
                $file = (new FileController("/$table/$auto_increment"));
                $file->setSizeLimit(262144);
                $file->allowExtensions(['ico']);
                $icon = $file->upload('txt_favico');
                if (isset($icon['name'])) {
                    $record['favico'] = $file->getPathUrl() . $icon['name'];
                }
                
                $image = (new ImageController("/$table/$auto_increment"))->setTable($table);
                $image->post_multi('full', $auto_increment, array('type' => \getenv('IMAGE_MAIN', true) ?: 1));
                $image->post_multi('short', $auto_increment, array('type' => \getenv('IMAGE_LOGO_SMALL', true) ?: 2));
                $image->post('apple_touch', $auto_increment, array('type' => \getenv('IMAGE_APPLE_TOUCH', true) ?: 6));
                $image->post('ico_png', $auto_increment, array('type' => \getenv('IMAGE_ICO_PNG', true) ?: 7));
                $image->post('background', $auto_increment, array('type' => \getenv('IMAGE_BACKGROUND', true) ?: 8));
            }
            
            if (isset($record['favico'])
                    && $this->isEmpty($record['favico'])) {
                unset($record['favico']);
            }
        }
        
        $data = $describe instanceof MultiDescribe ?
                array('primary' => $record, 'content' => $content) : $record;

        $method = $action == 'insert' ? 'indopost' : 'indoput';
        $response = $this->$method(
                "/record?model=$model&table=$table", array('record' => $data));
        
        if (isset($response['data'])) {
            $response['data']['href'] = single::link('home');
        }
        
        return $response['data'] ?? null;
    }
}
