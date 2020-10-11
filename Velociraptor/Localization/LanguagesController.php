<?php namespace Velociraptor\Localization;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\Common\LogLevel;
use codesaur\HTML\TwigTemplate;
use codesaur\HTML\HTML5 as html;

use Velociraptor\Boot4Template\Card;
use Velociraptor\Boot4Template\Dashboard;
use Velociraptor\Common\RaptorController;

use Indoraptor\Localization\LanguageDescribe;

class LanguagesController extends RaptorController
{   
    public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('languages'))
                ->breadcrumb(array(single::text('languages')));
        
        if ( ! single::user()->can('system_language_index')) {
            $view->noPermission();
        }
        
        $view->callout(single::text('languages-note'), 'danger');
        
        $card = new Card(array(single::text('language'), 'text-uppercase text-danger'), 'la la-language');

        $query = '?logger=localization&table=language&controller=' . \urlencode($this->getMe());
        
        if (single::user()->can('system_language_insert')) {
            $card->addButton(single::text('new'),
                    single::link('crud', array('action' => 'insert')) . $query,
                    'btn btn-danger shadow-sm text-uppercase', 'flaticon-add', 'data-target="#modal" data-toggle="modal"');
        }
        
        if (single::user()->can('system_language_initial')) {
            $card->addButton(single::text('get-initial-code'),
                    single::link('crud', array('action' => 'initial')) .
                    "$query&model=" . \urlencode('Indoraptor\\Localization\\LanguageModel') .
                    '&describe=' . \urlencode('Indoraptor\\Localization\\LanguageDescribe'),
                    'btn btn-clean btn-outline-danger shadow-sm text-uppercase', 'la la-leaf', 'data-target="#modal" data-toggle="modal"');
        }
        
        $card->addContent(new TwigTemplate(\dirname(__FILE__) . '/language-index-table.html'));

        $view->addDelete(array('logger' => 'localization', 'model' => 'Indoraptor\\Localization\\LanguageModel'));
        
        $view->render($card);
    }
    
    public function crud(string $action, $id, $table)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update', 'retrieve'))) {
                throw new \Exception("Invalid action [$action]!");
            }
            
            if ( ! single::user()->can("system_language_$action")) {
                return (new Dashboard())->noPermissionModal();   
            }
            
            $query = "?logger=localization&table=$table&controller=" . \urlencode($this->getMe());
            $crud = single::link('crud-submit', array('action' => $action)) . $query;
            
            if ($action == 'insert') {
                $languages = $this->indoget('/language');
                $countries = $this->indopost(
                        '/record/retrieve?model=' . \urlencode('Indoraptor\\Localization\\CountryModel'),
                        array('condition' => array('WHERE' => "c.language='" . single::flag() . "'")));
            } elseif (\in_array($action, array('update', 'retrieve')) && isset($id)) {
                $response = $this->indopost('/record/retrieve?model='
                        . \urlencode('Indoraptor\\Localization\\LanguageModel'), array('id' => $id));
                
                if ( ! isset($response['data']['record'])) {
                    throw new \Exception("No data for $action!");
                }
            }
            
            (new TwigTemplate(
                \dirname(__FILE__) . "/language-$action-modal.html",
                array(
                    'languages' => $languages['data'] ?? array(),
                    'action' => $crud, 'account' => $this->getAccounts(),
                    'countries' => $countries['data']['rows'] ?? array(),
                    'column' => (new LanguageDescribe())->getTwigColumns($response['data']['record'] ?? array()))))->render();
            
            return true;
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return false;
        }
    }
    
    public function submit_insert()
    {
        if ( ! single::user()->can('system_language_insert')) {
            return false;
        }
        
        try {
            $record = (new LanguageDescribe())->getPostValues();
        
            if (isset($record['id'])) {
                unset($record['id']);
            }

            $languages = $this->indoget('/language?from=common');
            
            if ( ! isset($record['full']) || ! isset($record['short']) || ! isset($record['copy']) || ! isset($languages['data'])
                    ||  $this->isEmpty($record['short']) || $this->isEmpty($record['full']) || $this->isEmpty($record['copy'])) {
                throw new \Exception(single::text('invalid-request'));
            }

            
            foreach ($languages['data'] as $key => $value) {
                if ($record['short'] == $key && $record['full'] == $value) {
                    throw new \Exception(single::text('lang-existing'));
               }
               if ($record['short'] == $key) {
                    throw new \Exception(single::text('lang-code-existing'));
               }
               if ($record['full'] == $value) {
                    throw new \Exception(single::text('lang-name-existing'));
               }
            }
            
            $record['app'] = 'common';
            
            $response = $this->indopost('/record?model='
                    . \urlencode('Indoraptor\\Localization\\LanguageModel'), array('record' => $record));
            if ( ! isset($response['data']['id'])) {
                return false;
            }
            
            $id = $response['data']['id'];
            $retrieve = $this->indopost('/record/retrieve?model='
                    . \urlencode('Indoraptor\\Localization\\LanguageModel'),
                    array('condition' => array('WHERE' => "app='common' AND short='{$record['copy']}' AND is_active=1")));
            if (isset($retrieve['data']['rows'])) {
                $mother = \reset($retrieve['data']['rows']);
                $translated = $this->indopost('/language/copy/translation', 
                        array('from' => $mother['short'], 'to' => $record['short']));

                if (isset($translated['data'])) {
                    $names = ' ';
                    foreach ($translated['data'] as $name) {
                        $names .= "$name; ";
                    }
                    
                    $log = $this->getMeClean() . ' объект нь ' . $mother['short'] . ' хэлнээс ' .
                            $record['short'] . ' хэлийг хуулбарлан үүсгэлээ. ' . "Өөрчлөлт орсон хүснэгтүүд ба объектууд: [$names]";
                    
                    $this->log('copy', array('message' => $log, 'record' => $id), LogLevel::Record);
                }                
            }
            
            single::response()->json(
                    array(
                        'confirm'  => true,
                        'status'   => 'success',
                        'alert'    => 'SweetAlert',
                        'title'    => single::text('success'),
                        'message'  => single::text('language-added') . ' ' . single::text('translated-tables:') . ($names ?? ''),
                        'href'     => single::link('crud', array('action' => 'index')) . '?logger=localization&controller=' . \urlencode($this->getMe())
                    ), false, true
            );
            
            return $response['data'];
        } catch (\Exception $e) {
            single::response()->json(array(
                'status'  => 'error',
                'alert'   => 'SweetAlert',
                'message' => $e->getMessage(),
                'title'   => single::text('error')
            ));
            exit;
        }
    }
    
    public function submit_update()
    {
        if ( ! single::user()->can('system_language_update')) {
            return false;
        }

        $record = (new LanguageDescribe())->getPostValues();
        
        if ( ! isset($record['id'])) {
            return false;
        }
        
        $response = $this->indoput('/record?model='
                . \urlencode('Indoraptor\\Localization\\LanguageModel'), array('record' => $record));
        
        return $response['data'] ?? null;
    }
    
    public function datatable()
    {
        $response = $this->indopost('/record/retrieve?model='
                . \urlencode('Indoraptor\\Localization\\LanguageModel'));
        if (isset($response['data']['clean']) && single::user()->can('system_language_index')) {
            $table = $response['data']['clean'];
            $query = "?logger=localization&table=$table&controller=" . \urlencode($this->getMe());
            $rows = array();
            foreach ($response['data']['rows'] ?? array() as $record) {
                $action = '<div class="float-right">';
                if (single::user()->can('system_language_retrieve')) {
                    $action .= html::a(
                            array(
                                'data-target' => '#modal', 'data-toggle' => 'modal',
                                'class'       => 'ajax-modal btn btn-info shadow-sm',
                                'href'        => single::link('crud', array('action' => 'retrieve')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-eye"></i>'
                    );
                }
                if (single::user()->can('system_language_update')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'data-target' => '#modal', 'data-toggle' => 'modal',
                                'class'       => 'ajax-modal btn btn-primary shadow-sm',
                                'href'        => single::link('crud', array('action' => 'update')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-edit"></i>'
                    );
                }
                if (single::user()->can('system_language_delete')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'href'   => $record['id'],
                                'custom' => "alias=\"$table\"",
                                'class'  => 'delete btn btn-danger shadow-sm'), 
                                '<i class="la la-trash"></i>'
                    );
                }
                $action .= '</div>';

                $rows[] = array(
                    \htmlentities($record['short']),
                    \htmlentities($record['full']),
                    html::img(['src' => "https://cdn.jsdelivr.net/gh/codesaur/resources/dist/flags/16x11/{$record['short']}.png"]),
                    $record['created_at'],
                    $action ?? ''
                );
            }
        }        
        
        $post = new Post();
        single::response()->json(array(
            'data' => $rows ?? array(),
            'recordsTotal' => \count($rows ?? array()),
            'recordsFiltered' => \count($rows ?? array()),
            'draw' => $post->has('draw') ? $post->value('draw', FILTER_VALIDATE_INT) : 0
        ));
    }
}
