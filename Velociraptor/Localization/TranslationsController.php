<?php namespace Velociraptor\Localization;

use codesaur as single;
use codesaur\Globals\Post;
use codesaur\HTML\HTML5 as html;

use Velociraptor\Boot4\Card;
use Velociraptor\TwigTemplate;
use Velociraptor\Boot4\Dashboard;
use Velociraptor\DashboardController;

use Indoraptor\Localization\TranslationDescribe;

class TranslationsController extends DashboardController
{
    public function index()
    {
        $view = new Dashboard();
        $view->title(single::text('translation'))
                ->breadcrumb(array(single::text('translation')));
        
        if ( ! single::user()->can('system_translation_index')) {
            $view->noPermission();
        }
        
        $view->alert(single::text('translation-note'), 'flaticon-exclamation', 'alert-dark');
        
        $translation = $this->indoget('/translation', [], true);
        if (isset($translation->data->names)) {
            $initial = new \Indoraptor\Initial();
            $methods = \get_class_methods($initial);
            
            $systems = array();
            foreach ($methods as $name) {
                if (\substr($name, 0, 12) != 'translation_') {
                    continue;
                }
                
                $systems[] = \substr($name, 12, \strlen($name) - 16);
            }

            $users = array();
            foreach ($translation->data->names as $name) {
                if ( ! \in_array($name, $systems)) {
                    $users[] = $name;
                }
            }
            $tables = \array_merge($users, $systems);

            $colors = array('primary', 'dark', 'danger', 'warning', 'info', 'success');            
            if ( ! empty($tables)) {
                $cards = array();
                foreach ($tables as $table) {
                    $color = $colors[\rand(0, \count($colors) - 1)];
                    $cards[$table] = new Card(array($table, "text-uppercase text-$color"), 'la la-language', "border-$color mb-4");
                    
                    $query = "?logger=localization&table=$table&controller=" . \urlencode($this->getMe());
                    if (single::user()->can('system_translatione_insert')) {
                        $cards[$table]->addButton(single::text('new'),
                                single::link('crud', array('action' => 'insert')) . $query,
                                "btn btn-$color shadow-sm text-uppercase", 'flaticon-add', 'data-target="#modal" data-toggle="modal"');
                    }
                    
                    if (single::user()->can('system_translation_initial')) {
                        $cards[$table]->addButton(single::text('get-initial-code'),
                                single::link('crud', array('action' => 'initial')) .
                                "$query&model=" . \urlencode('Indoraptor\\Localization\\TranslationModel') .
                                '&describe=' . \urlencode('Indoraptor\\Localization\\TranslationDescribe'),
                                "btn btn-clean btn-outline-$color shadow-sm text-uppercase", 'la la-leaf', 'data-target="#modal" data-toggle="modal"');
                    }

                    $cards[$table]->addContent(new TwigTemplate(\dirname(__FILE__) . '/translation-index-table.html', array('id' => $table)));
                }
            }
        }
        
        $view->addDelete(array('logger' => 'localization', 'model' => 'Indoraptor\\Localization\\TranslationModel'));        
        $view->render($cards ?? null);
    }
    
    public function crud(string $action, $id, $table)
    {
        try {
            if ( ! \in_array($action, array('insert', 'update', 'retrieve'))) {
                throw new \Exception("Invalid action [$action]!");
            }

            if ( ! single::user()->can("system_translation_$action")) {
                return (new Dashboard())->noPermission(true);
            }

            $crud = single::link('crud-submit', array('action' => $action))
                    . "?logger=localization&table=$table&controller=" . \urlencode($this->getMe());
            
            if (isset($id)) {
                $response = $this->indopost('/record/retrieve?model='
                        . \urlencode('Indoraptor\\Localization\\TranslationModel') . "&table=$table", array('id' => $id));

                if ( ! isset($response['data']['record'])) {
                    throw new \Exception("No data for $action!");
                }
            }
            
            $column = (new TranslationDescribe())->getTwigColumns($response['data']['record'] ?? array());
            
            if ($action == 'insert') {
                $column['type']['value'] = 1;
            }
            
            $lookup = $this->getLookup('record_type');
            $lookup['account'] = $this->getAccounts();

            (new TwigTemplate(
                \dirname(__FILE__) . '/translation-crud-action-modal.html',
                array(
                    'crud' => $crud,
                    'action' => $action,
                    'column' => $column,
                    'table' => "translation_$table",
                    'lookup' => $lookup ?? array())))->render();
            
            return true;
        } catch (\Exception $e) {
            if (DEBUG) {
                \error_log($e->getMessage());
            }
            
            return false;
        }
    }
    
    public function submit(string $action)
    {
        try {
            if ( ! single::user()->can("system_translation_$action")) {
                return false;
            }
            
            $describe = new TranslationDescribe();
            $record = $describe->getPrimary()->getPostValues();
            $content = $describe->getContent()->getPostValues(single::language()->codes());
            
            if ($action == 'update'
                    && ! isset($record['id'])) {
                return false;
            }
            
            if (  ! single::request()->hasParam('table')) {
                throw new \Exception(single::text('invalid-table-name'));
            }
            
            if ($this->isEmpty($record['_keyword_'] ?? null)) {
                throw new \Exception(single::text('empty-keyword'));
            }
            
            $table = single::request()->getParam('table');
            
            $found = $this->indopost('/translation/getby', array('_keyword_' => $record['_keyword_']));
            if (isset($found['data'])) {
                if ($action == 'insert' || $found['data']['id'] != $record['id']
                        || $found['data']['table'] != "translation_{$table}_key") {
                    throw new \Exception(single::text('keyword-existing')
                            . '<br />ID: ' . $found['data']['id'] . '<br />Table: ' . $found['data']['table']);
                }
            }
            
            if ($action == 'insert'
                    && isset($record['id'])) {
                unset($record['id']);
            }
            
            $method = $action == 'insert' ? 'indopost' : 'indoput';
            $response = $this->$method(
                    "/record?table=$table&model=" . \urlencode('Indoraptor\\Localization\\TranslationModel'),
                    array('record' => array('primary' => $record, 'content' => $content)));
            

            return $response['data'] ?? false;
        } catch (\Exception $e) {
            single::response()->json(array(
                'status'  => 'error',
                'message' => $e->getMessage(),
                'title'   => single::text('error')
            ));
            exit;
        }
    }
        
    public function datatable($table)
    {
        $rows = array();
        $response = $this->indopost("/record/retrieve?table=$table&model=" . \urlencode('Indoraptor\\Localization\\TranslationModel'));
        if (isset($response['data']['clean']) && single::user()->can('system_translation_index')) {
            $type = $this->getLookup('record_type');
            $actual_table = $response['data']['clean'];
            $query = "?logger=localization&table=$actual_table&controller=" . \urlencode($this->getMe());            
            foreach ($response['data']['rows'] ?? array() as $record) {
                $row = array(\htmlentities($record['_keyword_']));

                foreach (single::language()->codes() as $code) {
                    $row[] = \htmlentities($record['title'][$code] ?? '');
                }

                $row[] = \htmlentities($type['record_type'][$record['type']] ?? $record['type'] ?? 0);
                
                $action = '<div class="float-right">';
                if (single::user()->can('system_translation_retrieve')) {
                    $action .= html::a(
                            array(
                                'data-target' => '#modal', 'data-toggle' => 'modal',
                                'class'       => 'ajax-modal btn btn-info shadow-sm',
                                'href'        => single::link('crud', array('action' => 'retrieve')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-eye"></i>'
                    );
                }
                if (single::user()->can('system_translation_update')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'data-target' => '#modal', 'data-toggle' => 'modal',
                                'class'       => 'ajax-modal btn btn-primary shadow-sm',
                                'href'        => single::link('crud', array('action' => 'update')) . "$query&id={$record['id']}"
                            ),  '<i class="la la-edit"></i>'
                    );
                }
                if (single::user()->can('system_translation_delete')) {
                    $action .= html::nbsp() . html::a(
                            array(
                                'href'   => $record['id'],
                                'custom' => "alias=\"$table\"",
                                'class'  => 'delete btn btn-danger shadow-sm'), 
                                '<i class="la la-trash"></i>'
                    );
                }
                $action .= '</div>';
                
                $row[] = $action;

                $rows[] = $row;
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
